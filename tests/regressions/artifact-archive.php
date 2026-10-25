<?php

require __DIR__.'/forms-bootstrap.php';

use App\Lab\Publish\LabPublishArtifactDeployer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

$fixtureRoot = base_path('.agent-work/qa/archive-'.Str::uuid());
File::ensureDirectoryExists($fixtureRoot);
$destination = $fixtureRoot.'/live';
$deployer = app(LabPublishArtifactDeployer::class);
$archive = function (array $files, array $links = []) use ($fixtureRoot) {
    $path = $fixtureRoot.'/'.Str::uuid().'.zip';
    $zip = new ZipArchive;
    if ($zip->open($path,ZipArchive::CREATE|ZipArchive::OVERWRITE) !== true) throw new RuntimeException('Fixture ZIP unavailable');
    foreach ($files as $name=>$body) $zip->addFromString($name,$body);
    foreach ($links as $name) $zip->setExternalAttributesName($name, ZipArchive::OPSYS_UNIX, 0120777 << 16);
    $zip->close();
    return $path;
};
try {
    $deployer->deploy($archive(['dist/index.html'=>'PLAIN SITE','dist/assets/app.js'=>'// static']),$destination);
    formCheck(File::get($destination.'/index.html') === 'PLAIN SITE', 'Plain sites retain existing dist archive publishing without a managed manifest');
    try {
        $deployer->deploy($archive(['index.html'=>'UNREVIEWED']),$destination,fn () => abort(409,'Stale build'));
        throw new RuntimeException('Invalid build was promoted');
    } catch (Symfony\Component\HttpKernel\Exception\HttpException $error) {
        formCheck($error->getStatusCode() === 409 && File::get($destination.'/index.html') === 'PLAIN SITE', 'Stage validation rejects before replacing the last-good site');
    }
    foreach (['../escape.js','C:/escape.js','assets/secret.js:stream','assets/name.','assets/CON.txt','assets/a.js\\..\\escape.js'] as $unsafe) {
        try {
            $deployer->deploy($archive(['index.html'=>'UNSAFE',$unsafe=>'// unsafe']),$destination);
            throw new RuntimeException('Unsafe archive accepted: '.$unsafe);
        } catch (InvalidArgumentException) {
            formCheck(File::get($destination.'/index.html') === 'PLAIN SITE','Unsafe path rejected before extraction/promotion: '.$unsafe);
        }
    }
    foreach ([['index.html'=>'FIRST','INDEX.HTML'=>'SECOND'],['index.html'=>'UNSAFE','assets/link'=>'target']] as $index=>$files) {
        try {
            $deployer->deploy($archive($files,$index===1?['assets/link']:[]),$destination);
            throw new RuntimeException('Duplicate or symlink archive was accepted');
        } catch (InvalidArgumentException) {
            formCheck(File::get($destination.'/index.html') === 'PLAIN SITE','Case-colliding or symbolic-link archive preserves previous site');
        }
    }
    config(['lab.publish.artifact_max_expanded_bytes'=>100]);
    try {
        $deployer->deploy($archive(['index.html'=>'OVERSIZE','assets/large.js'=>str_repeat('x',101)]),$destination);
        throw new RuntimeException('Expanded limit was ignored');
    } catch (InvalidArgumentException) {
        formCheck(File::get($destination.'/index.html') === 'PLAIN SITE','Expanded archive limit preserves previous deployment');
    }
    formResult('production archive path safety, plain sites and last-good staging');
} finally {
    File::deleteDirectory($fixtureRoot);
}
