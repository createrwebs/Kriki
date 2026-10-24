param(
    [string]$Php = 'C:/MAMP/bin/php/php8.5.10/php.exe',
    [ValidateRange(1024, 65535)][int]$Port = 8891
)
$ErrorActionPreference = 'Stop'
$previewProject = Split-Path -Parent $PSScriptRoot
if (-not (Test-Path -LiteralPath $Php)) { throw 'Set -Php to a PHP 8.4.1+ executable.' }
Write-Host "Read-only local UI preview: http://127.0.0.1:$Port/features"
Write-Host 'No customer database or .env is used. Press Ctrl+C to stop.'
Push-Location $previewProject
try {
    & $Php -d extension_dir="$(Split-Path -Parent $Php)/ext" -d extension=openssl -d extension=pdo_sqlite -d extension=mbstring -d extension=curl -d extension=fileinfo -d opcache.enable=0 -d opcache.enable_cli=0 -d variables_order=EGPCS -S "127.0.0.1:$Port" -t public scripts/preview-landing.php
} finally { Pop-Location }
