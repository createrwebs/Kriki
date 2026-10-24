# Deploy hosting panel components to AWS test server (PowerShell)
# Usage: .\deploy-to-aws.ps1

$AWS_IP = "47.84.179.226"
$AWS_USER = "root"
$AWS_KEY = "_ssh_alibaba_generic_ai.pem"
$REMOTE_DIR = "/root/krikkit-hosting-test"

# Check if SSH key exists
if (-not (Test-Path $AWS_KEY)) {
    Write-Host "ERROR: SSH key not found: $AWS_KEY" -ForegroundColor Red
    Write-Host "Please place the key file in the current directory or update AWS_KEY path"
    exit 1
}

Write-Host "=== Connecting to AWS server ===" -ForegroundColor Cyan
ssh -i $AWS_KEY -o StrictHostKeyChecking=no "$AWS_USER@$AWS_IP" "mkdir -p $REMOTE_DIR/components"

Write-Host "=== Uploading hosting panel components ===" -ForegroundColor Cyan
$components = @(
    "resources/js/lab/components/DeployPanel.jsx",
    "resources/js/lab/components/DeploymentHistory.jsx",
    "resources/js/lab/components/SecretsManager.jsx",
    "resources/js/lab/components/SecurityScanPanel.jsx",
    "resources/js/lab/components/HostingPanel.jsx"
)

foreach ($file in $components) {
    if (Test-Path $file) {
        Write-Host "  Uploading: $file" -ForegroundColor Green
        scp -i $AWS_KEY -o StrictHostKeyChecking=no $file "$AWS_USER@${AWS_IP}:$REMOTE_DIR/components/"
    } else {
        Write-Host "  WARNING: File not found: $file" -ForegroundColor Yellow
    }
}

Write-Host "=== Uploading labConstants.js ===" -ForegroundColor Cyan
if (Test-Path "resources/js/lab/lib/labConstants.js") {
    scp -i $AWS_KEY -o StrictHostKeyChecking=no "resources/js/lab/lib/labConstants.js" "$AWS_USER@${AWS_IP}:$REMOTE_DIR/"
}

Write-Host ""
Write-Host "=== Upload complete ===" -ForegroundColor Cyan
Write-Host "Files uploaded to: $AWS_USER@${AWS_IP}:$REMOTE_DIR"
Write-Host ""
Write-Host "To verify on server:" -ForegroundColor Yellow
Write-Host "  ssh -i $AWS_KEY $AWS_USER@$AWS_IP"
Write-Host "  ls -la $REMOTE_DIR/components/"
