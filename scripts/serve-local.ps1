param(
    [string]$PhpPath = 'C:/MAMP/bin/php/php8.5.10/php.exe',
    [int]$Port = 8889
)

$ErrorActionPreference = 'Stop'
if (-not (Test-Path -LiteralPath $PhpPath)) {
    throw "PHP executable not found: $PhpPath. Specify -PhpPath with PHP 8.3 or newer."
}

$projectDirectory = Split-Path -Parent $PSScriptRoot
$extensionDirectory = Join-Path (Split-Path -Parent $PhpPath) 'ext'
$phpArguments = @('-d', "extension_dir=$extensionDirectory")
$caBundle = 'C:/MAMP/bin/apache/bin/cacert.pem'
if (Test-Path -LiteralPath $caBundle) {
    $phpArguments += @('-d', "curl.cainfo=$caBundle", '-d', "openssl.cafile=$caBundle")
}
foreach ($extension in @('openssl', 'pdo_mysql', 'curl', 'mbstring', 'fileinfo', 'zip', 'gd', 'intl')) {
    $phpArguments += @('-d', "extension=$extension")
}
$phpArguments += @(
    '-S', "127.0.0.1:$Port", '-t', '.',
    '../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php'
)

Push-Location (Join-Path $projectDirectory 'public')
try {
    & $PhpPath @phpArguments
    if ($LASTEXITCODE -ne 0) { throw "Local PHP server exited with code $LASTEXITCODE." }
} finally {
    Pop-Location
}
