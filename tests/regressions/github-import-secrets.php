<?php

require __DIR__.'/managed-module-bootstrap.php';

use App\Lab\SiteWorkspace;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

$fixtureRoot = base_path('.agent-work/qa/github-secrets-'.Str::uuid());
$originalStorage = app()->storagePath(); app()->useStoragePath($fixtureRoot);
$workspace = app(SiteWorkspace::class); $root = $workspace->ensure($project);
File::put($root.'/src/App.jsx', 'LAST GOOD SOURCE');
$revision = $workspace->importRevision($project);
$token = 'ghp_'.str_repeat('A',36); // Fictional detector fixture, never an actual token.
$jwtPart = fn (array $data) => rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
$jwt = $jwtPart(['alg'=>'HS256','typ'=>'JWT']).'.'.$jwtPart(['role'=>'service_role','ref'=>'fictional']).'.'.str_repeat('A',32);
$cases = [
    ['.env', 'APP_NAME=Fictional'],
    ['.env.local', 'PUBLIC_LABEL=Fictional'],
    ['deployment/private.key', 'FICTIONAL KEY CONTAINER'],
    ['credentials.json', '{"fictional":true}'],
    ['src/config.js', 'export const key="'.$token.'"'],
    ['src/config.js', 'export const key="sb_secret_'.str_repeat('B',32).'"'],
    ['src/config.js', 'export const key="'.$jwt.'"'],
    ['README.md', "-----BEGIN PRIVATE KEY-----\n".str_repeat('A',48)."\n-----END PRIVATE KEY-----"],
    ['.env.example', 'SUPABASE_SERVICE_ROLE_KEY='.str_repeat('C',40)],
];
try {
    foreach ($cases as [$path,$body]) foreach (['map','tree'] as $mode) {
        $rejected = false; $message = '';
        try {
            if ($mode === 'map') $workspace->importFileMap($project, ['src/App.jsx'=>'UNPROMOTED', $path=>$body], replace:true);
            else {
                $tree = $fixtureRoot.'/source-'.Str::uuid();
                File::ensureDirectoryExists(dirname($tree.'/'.$path)); File::put($tree.'/'.$path,$body);
                $workspace->importTree($project, $tree, replace:true);
            }
        } catch (InvalidArgumentException $error) { $rejected = true; $message = $error->getMessage(); }
        formCheck($rejected, 'Credential source rejected before ingestion: '.$mode.' '.$path);
        formCheck($workspace->importRevision($project) === $revision, 'Prior source preserved: '.$mode.' '.$path);
        formCheck(!str_contains($message, $token) && !str_contains($message, $jwt) && !str_contains($message, $body), 'Credential source absent from errors: '.$mode.' '.$path);
    }
    $safe = ['src/App.jsx'=>'export default function App(){return null}', '.env.example'=>"VITE_APP_LABEL=Fictional\nSUPABASE_SERVICE_ROLE_KEY=replace_me", 'package-lock.json'=>'{"lockfileVersion":3}', 'LICENSE'=>'FICTIONAL LICENSE', 'public/icon.bin'=>pack('C*',137,80,78,71,0,255)];
    $result = $workspace->importFileMap($project,$safe,replace:true);
    formCheck(count($result['paths']) === count($safe) && $result['skipped'] === 0, 'Safe examples, lock, license and binary source remain importable');
    foreach ($safe as $path=>$body) formCheck(File::get($root.'/'.$path) === $body, 'Accepted source bytes unchanged: '.$path);
    formCheck(glob($root.'.import-*') === [] && glob($root.'.previous-*') === [], 'No rejected source stages remain');
    formResult('GitHub source credential ingestion boundary');
} finally {
    app()->useStoragePath($originalStorage);
    $qaRoot=realpath(base_path('.agent-work/qa')); $resolved=realpath($fixtureRoot);
    if (!$qaRoot || !$resolved || !str_starts_with($resolved,$qaRoot.DIRECTORY_SEPARATOR)) throw new RuntimeException('Refuse out-of-scope fixture cleanup');
    File::deleteDirectory($resolved);
}
