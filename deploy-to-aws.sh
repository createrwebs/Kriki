#!/bin/bash
# Deploy hosting panel components to AWS test server
# Usage: ./deploy-to-aws.sh

AWS_IP="47.84.179.226"
AWS_USER="root"
AWS_KEY="_ssh_alibaba_generic_ai.pem"
REMOTE_DIR="/root/krikkit-hosting-test"

# Check if SSH key exists
if [ ! -f "$AWS_KEY" ]; then
    echo "ERROR: SSH key not found: $AWS_KEY"
    echo "Please place the key file in the current directory or update AWS_KEY path"
    exit 1
fi

# Set correct permissions on key
chmod 600 "$AWS_KEY"

echo "=== Connecting to AWS server ==="
ssh -i "$AWS_KEY" -o StrictHostKeyChecking=no "$AWS_USER@$AWS_IP" "mkdir -p $REMOTE_DIR/components"

echo "=== Uploading hosting panel components ==="
scp -i "$AWS_KEY" -o StrictHostKeyChecking=no \
    resources/js/lab/components/DeployPanel.jsx \
    resources/js/lab/components/DeploymentHistory.jsx \
    resources/js/lab/components/SecretsManager.jsx \
    resources/js/lab/components/SecurityScanPanel.jsx \
    resources/js/lab/components/HostingPanel.jsx \
    "$AWS_USER@$AWS_IP:$REMOTE_DIR/components/"

echo "=== Uploading labConstants.js ==="
scp -i "$AWS_KEY" -o StrictHostKeyChecking=no \
    resources/js/lab/lib/labConstants.js \
    "$AWS_USER@$AWS_IP:$REMOTE_DIR/"

echo "=== Upload complete ==="
echo "Files uploaded to: $AWS_USER@$AWS_IP:$REMOTE_DIR"
echo ""
echo "To verify on server:"
echo "  ssh -i $AWS_KEY $AWS_USER@$AWS_IP"
echo "  ls -la $REMOTE_DIR/components/"
