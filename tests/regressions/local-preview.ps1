param(
    [string]$PreviewUrl = 'http://127.0.0.1:8891',
    [string]$AppUrl = ''
)
$ErrorActionPreference = 'Stop'
foreach ($base in @($PreviewUrl, $AppUrl) | Where-Object { $_ }) {
    $address = [Uri]$base
    if ($address.Scheme -ne 'http' -or $address.Host -notin @('localhost', '127.0.0.1') -or $address.UserInfo -or $address.Query -or $address.AbsolutePath -ne '/') {
        throw 'These smoke checks may target a loopback root URL only.'
    }
}
$previewChecks = 0
function Assert-Preview($condition, [string]$label) {
    if (-not $condition) { throw "FAIL: $label" }
    $script:previewChecks++
}
$page = Invoke-WebRequest "$($PreviewUrl.TrimEnd('/'))/features" -SkipHttpErrorCheck -TimeoutSec 30
Assert-Preview ($page.StatusCode -eq 200 -and $page.Content.Contains('krikkit-sales')) 'Presentation HTML renders, not just HTTP200'
Assert-Preview ($page.Content.Contains('sales-local-note')) 'Fixture has an explicit read-only notice'
Assert-Preview (-not $page.Content.Contains('Fatal error:')) 'No PHP bootstrap failure in body'
$manifestPath = Join-Path $PSScriptRoot '../../public/build/manifest.json'
$manifest = Get-Content -LiteralPath $manifestPath -Raw | ConvertFrom-Json
$assetUrl = "$($PreviewUrl.TrimEnd('/'))/build/$($manifest.'resources/css/app.css'.file)"
$asset = Invoke-WebRequest $assetUrl -SkipHttpErrorCheck -TimeoutSec 15
Assert-Preview ($asset.StatusCode -eq 200 -and $asset.Headers.'Content-Type' -match 'text/css') 'Built CSS serves correctly'
foreach ($method in @('POST', 'PUT')) {
    $blocked = Invoke-WebRequest $assetUrl -Method $method -Body 'read-only-fixture' -SkipHttpErrorCheck -TimeoutSec 15
    Assert-Preview ($blocked.StatusCode -eq 405 -and $blocked.Headers.Allow -match 'GET') "Fixture rejects $method even for static assets"
}
if ($AppUrl) {
    $appPage = Invoke-WebRequest "$($AppUrl.TrimEnd('/'))/features" -SkipHttpErrorCheck -TimeoutSec 30
    Assert-Preview ($appPage.StatusCode -eq 200 -and $appPage.Content.Contains('krikkit-sales')) 'Configured local application renders product page'
    Assert-Preview (-not $appPage.Content.Contains('sales-local-note') -and -not $appPage.Content.Contains('Fatal error:')) 'Configured application is not fixture or PHP fatal page'
}
Write-Output "PASS: local presentation/startup ($previewChecks checks)."
