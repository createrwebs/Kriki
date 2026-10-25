<?php

require __DIR__.'/forms-bootstrap.php';

use App\Lab\Github\GithubRepoImporter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

$fixtureRoot = base_path('.agent-work/qa/github-archive-'.Str::uuid());
File::ensureDirectoryExists($fixtureRoot);
$extract = new ReflectionMethod(GithubRepoImporter::class, 'extractZip');
$importer = app(GithubRepoImporter::class);
$created = [];
$archive = function (array $files, array $links=[]) use ($fixtureRoot) {
    $path = $fixtureRoot.'/'.Str::uuid().'.zip';
    $zip = new ZipArchive;
    if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) throw new RuntimeException('Fixture ZIP unavailable');
    foreach ($files as $name=>$body) {
        if (str_ends_with($name, '/')) $zip->addEmptyDir(rtrim($name, '/'));
        else $zip->addFromString($name, $body);
    }
    foreach ($links as $name) $zip->setExternalAttributesName($name, ZipArchive::OPSYS_UNIX, 0120777 << 16);
    $zip->close();
    return $path;
};
$reject = function (array $files, string $label, array $links=[]) use ($archive, $extract, $importer, &$created) {
    try {
        $created[] = $extract->invoke($importer, $archive($files, $links));
    } catch (InvalidArgumentException | RuntimeException) {
        formCheck(true, $label);
        return;
    }
    formCheck(false, $label);
};
try {
    $safe = $extract->invoke($importer, $archive(['repo/'=>'', 'repo/src/'=>'', 'repo/src/App.jsx'=>'FICTIONAL SOURCE', 'repo/LICENSE'=>'FICTIONAL LICENSE', 'repo/NOTICE'=>'FICTIONAL NOTICE']));
    $created[] = $safe;
    formCheck(File::get($safe.'/repo/src/App.jsx') === 'FICTIONAL SOURCE' && File::get($safe.'/repo/LICENSE') === 'FICTIONAL LICENSE' && File::get($safe.'/repo/NOTICE') === 'FICTIONAL NOTICE', 'Ordinary source/license/NOTICE preserved');
    foreach (['../escape.txt','/absolute.txt','C:/escape.txt','\\\\server/share/file.txt','repo\\escape.txt','repo/%2e%2e/escape.txt','repo/file.txt:stream','repo/CON.txt','repo/name.','repo/name ','repo/.githooks/pre-commit'] as $name) {
        $reject([$name=>'FICTIONAL'], 'Unsafe path rejected before extraction: '.$name);
    }
    $reject(['repo/App.jsx'=>'A','repo/APP.jsx'=>'B'], 'Case-colliding files rejected');
    $reject(['repo/src/a.jsx'=>'A','repo/SRC/b.jsx'=>'B'], 'Case-colliding parent directories rejected');
    $reject(['repo/src'=>'FILE','repo/src/a.jsx'=>'CHILD'], 'File/directory collisions rejected');
    $reject(['repo/link'=>'../target'], 'Symbolic link rejected', ['repo/link']);
    $nulArchive = $archive(['repo/qa-name.txt'=>'FICTIONAL']);
    File::put($nulArchive, str_replace('repo/qa-name.txt', "repo/q\0-name.txt", File::get($nulArchive)));
    try {
        $created[] = $extract->invoke($importer, $nulArchive);
        formCheck(false, 'NUL name rejected');
    } catch (InvalidArgumentException) { formCheck(true, 'NUL name rejected'); }
    $mixedNames = $archive(['repo/a.txt'=>'FICTIONAL']);
    $raw = File::get($mixedNames);
    $localName = strpos($raw, 'repo/a.txt');
    File::put($mixedNames, substr_replace($raw, 'repo/b.txt', $localName, strlen('repo/a.txt')));
    try {
        $created[] = $extract->invoke($importer, $mixedNames);
        formCheck(false, 'Conflicting local/directory names rejected');
    } catch (InvalidArgumentException) { formCheck(true, 'Conflicting local/directory names rejected'); }
    $reject([str_repeat('dir/',33).'file.txt'=>'DEEP'], 'Depth limit enforced');
    $reject(['repo/large.txt'=>str_repeat('a',2*1024*1024+1)], 'Per-file expanded limit enforced');
    $reject(['repo/compressed.txt'=>str_repeat('x',32*1024)], 'Compression-ratio limit enforced');
    config(['lab.github.max_expanded_bytes'=>10]);
    $reject(['repo/a.txt'=>'123456','repo/b.txt'=>'123456'], 'Total expanded limit enforced');
    config(['lab.github.max_expanded_bytes'=>100*1024*1024, 'lab.github.max_files'=>100]);
    $many=[]; for ($i=0;$i<101;$i++) $many['repo/file-'.$i.'.txt']='SMALL';
    $reject($many, 'File count limit enforced');
    config(['lab.github.max_zip_bytes'=>1024*1024]);
    $reject(['repo/random.bin'=>random_bytes(1024*1024+128)], 'Compressed archive limit enforced');
    formResult('GitHub source archive path/type/expansion safety');
} finally {
    foreach ($created as $directory) {
        if (!str_starts_with($directory, sys_get_temp_dir().DIRECTORY_SEPARATOR.'krikkit-gh-')) throw new RuntimeException('Unexpected extraction directory');
        File::deleteDirectory($directory);
    }
    File::deleteDirectory($fixtureRoot);
}
