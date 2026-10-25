param(
    [string]$Php = 'C:/MAMP/bin/php/php8.5.10/php.exe',
    [ValidateRange(1024, 65535)][int]$Port = 8892
)
$ErrorActionPreference = 'Stop'
$localProject = Split-Path -Parent $PSScriptRoot
$localPublic = Join-Path $localProject 'public'
$localRouter = Join-Path $localProject 'vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php'
if (-not (Test-Path -LiteralPath $Php)) { throw 'Set -Php to a supported PHP executable.' }
if (-not (Test-Path -LiteralPath $localRouter)) { throw 'Install the project PHP dependencies first.' }
Write-Host "Local application: http://localhost:$Port/features"
Write-Host 'Uses the existing application configuration. No migrations or settings are applied. Press Ctrl+C to stop.'
Push-Location $localPublic
try {
    # MAMP's cached web bytecode failed during dotenv/mbstring bootstrap.
    # Keep this development-only switch out of php.ini and production settings.
    & $Php -d extension_dir="$(Split-Path -Parent $Php)/ext" `
        -d extension=openssl -d extension=pdo_mysql -d extension=pdo_sqlite `
        -d extension=mbstring -d extension=curl -d extension=fileinfo -d extension=zip -d extension=intl -d extension=gd `
        -d opcache.enable=0 -d opcache.enable_cli=0 -d variables_order=EGPCS `
        -S "127.0.0.1:$Port" -t $localPublic $localRouter
    if ($LASTEXITCODE -ne 0) { throw "Local PHP server exited with status $LASTEXITCODE." }
} finally { Pop-Location }
