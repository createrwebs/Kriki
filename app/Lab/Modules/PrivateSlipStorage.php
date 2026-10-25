<?php

namespace App\Lab\Modules;

use App\Datastore\DatastoreHttp;
use App\Models\LabModuleInstallation;

final class PrivateSlipStorage
{
    public function __construct(private readonly ModuleInstallations $modules) {}

    public static function bucket(LabModuleInstallation $module): string
    {
        return 'krikkit-slips-'.$module->uuid;
    }

    public function verifyBucket(LabModuleInstallation $module): void
    {
        $response = $this->request($module, 'GET', '/bucket/'.self::bucket($module));
        $bucket = $response->json();
        abort_unless(is_array($bucket) && ($bucket['id'] ?? null) === self::bucket($module)
            && ($bucket['name'] ?? null) === self::bucket($module) && ($bucket['public'] ?? null) === false
            && ($bucket['file_size_limit'] ?? null) === SlipImage::MAX_BYTES && ($bucket['allowed_mime_types'] ?? null) === ['image/png'], 503, 'The original private slip bucket is unavailable or incompatible.');
    }

    public function upload(LabModuleInstallation $module, array $receipt, array $image): void
    {
        $path = $this->objectPath($module, $receipt['bucket_id'], $receipt['object_path']);
        $this->verifyBucket($module);
        $response = $this->request($module, 'POST', '/object/'.$path, $image['contents']);
        abort_unless($response->json('Key') === $receipt['bucket_id'].'/'.$receipt['object_path'], 503, 'Slip object upload could not be verified.');
    }

    public function deleteUncommitted(LabModuleInstallation $module, array $receipt): void
    {
        abort_unless($receipt['state'] === 'cleanup_pending' && empty($receipt['attempt_uuid']), 409, 'Only uncommitted uploads may be cleaned up.');
        $this->objectPath($module, $receipt['bucket_id'], $receipt['object_path']);
        $this->verifyBucket($module);
        $this->request($module, 'DELETE', '/object/'.$receipt['bucket_id'], ['prefixes' => [$receipt['object_path']]]);
    }

    public function signedImage(LabModuleInstallation $module, string $bucket, string $object): array
    {
        $path = $this->objectPath($module, $bucket, $object);
        $this->verifyBucket($module);
        $response = $this->request($module, 'POST', '/object/sign/'.$path, ['expiresIn' => 300]);
        $signed = $response->json('signedURL');
        $prefix = '/object/sign/'.$path.'?token=';
        abort_unless(is_string($signed) && str_starts_with($signed, $prefix)
            && preg_match('/^[A-Za-z0-9_.-]{1,4096}$/D', substr($signed, strlen($prefix))), 503, 'Private image viewing is temporarily unavailable.');
        $plane = $this->modules->plane($module);
        return ['url' => rtrim($plane['host_url'], '/').'/storage/v1'.$signed, 'expires_at' => now()->addMinutes(5)->toIso8601String()];
    }

    private function objectPath(LabModuleInstallation $module, string $bucket, string $object): string
    {
        $prefix = $module->project->uuid.'/'.$module->uuid.'/';
        abort_unless($module->key === 'manual_payments' && $bucket === self::bucket($module) && str_starts_with($object, $prefix)
            && preg_match('~^[a-f0-9-]{36}/[a-f0-9-]{36}\.png$~D', substr($object, strlen($prefix))), 404);
        return $bucket.'/'.$object;
    }

    private function request(LabModuleInstallation $module, string $method, string $path, array|string|null $body = null): \Illuminate\Http\Client\Response
    {
        $plane = $this->modules->plane($module);
        try {
            $http = DatastoreHttp::make()->withHeaders(['apikey' => $plane['steward_token'], 'Authorization' => 'Bearer '.$plane['steward_token']])->acceptJson();
            $url = rtrim($plane['host_url'], '/').'/storage/v1'.$path;
            $response = is_string($body) ? $http->withHeaders(['x-upsert' => 'false'])->withBody($body, 'image/png')->post($url)
                : $http->send($method, $url, $body === null ? [] : ['json' => $body]);
        } catch (\Throwable) { abort(503, 'Private slip storage is temporarily unavailable.'); }
        abort_unless($response->successful(), 503, 'Private slip storage is temporarily unavailable.');
        return $response;
    }
}
