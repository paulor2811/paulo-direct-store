#!/bin/bash

# Stop on error
set -e

echo "🚀 Starting Server Setup for PauloDirect (Amazon Linux 2023)..."

# 1. System Update
echo "📦 Updating system packages..."
sudo dnf update -y

# 2. Configure SWAP (Critical for t3.micro 1GB RAM)
if [ ! -f /swapfile ]; then
    echo "💾 Configuring 2GB Swap file for t3.micro..."
    sudo dd if=/dev/zero of=/swapfile bs=128M count=16
    sudo chmod 600 /swapfile
    sudo mkswap /swapfile
    sudo swapon /swapfile
    echo '/swapfile swap swap defaults 0 0' | sudo tee -a /etc/fstab
    echo "✅ Swap created successfully!"
else
    echo "✅ Swap already exists."
fi

# 3. Install Docker & Docker Compose
if ! command -v docker &> /dev/null; then
    echo "🐳 Installing Docker..."
    sudo dnf install -y docker
    
    # Start Docker
    sudo systemctl start docker
    sudo systemctl enable docker
    
    # Add current user to docker group
    sudo usermod -aG docker ec2-user
    
    # Install Docker Compose (Standalone)
    mkdir -p ~/.docker/cli-plugins/
    curl -SL https://github.com/docker/compose/releases/download/v2.24.5/docker-compose-linux-x86_64 -o ~/.docker/cli-plugins/docker-compose
    chmod +x ~/.docker/cli-plugins/docker-compose
    
    echo "✅ Docker installed. You MUST logout and login again for permissions to take effect."
else
    echo "✅ Docker already installed."
fi

# 4. Project Setup (Permissions)
echo "🔒 Setting up permissions..."
# Ensure storage folders exist
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Fix permissions
# On Amazon Linux, the user is usually ec2-user, but inside docker it might be different.
# For now, we give broad permissions to storage to avoid issues
chmod -R 777 storage bootstrap/cache

echo "🎉 Setup Complete! You are ready to deploy."
echo "👉 IMPORTANT: Log out and log back in to apply Docker permissions."
echo "👉 Then run: docker compose -f docker-compose.prod.yml up -d --build"
