#!/bin/bash

# Stop on error
set -e

echo "🚀 Starting Server Setup for PauloDirect..."

# 1. System Update
echo "📦 Updating system packages..."
sudo apt-get update
sudo apt-get upgrade -y

# 2. Configure SWAP (Critical for t3.micro 1GB RAM)
if [ ! -f /swapfile ]; then
    echo "💾 Configuring 2GB Swap file for t3.micro..."
    sudo fallocate -l 2G /swapfile
    sudo chmod 600 /swapfile
    sudo mkswap /swapfile
    sudo swapon /swapfile
    echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
    echo "✅ Swap created successfully!"
else
    echo "✅ Swap already exists."
fi

# 3. Install Docker & Docker Compose
if ! command -v docker &> /dev/null; then
    echo "🐳 Installing Docker..."
    sudo apt-get install -y ca-certificates curl gnupg
    
    # Add Docker's official GPG key
    sudo install -m 0755 -d /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    sudo chmod a+r /etc/apt/keyrings/docker.gpg

    # Add the repository to Apt sources
    echo \
      "deb [arch=\"$(dpkg --print-architecture)\" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
      $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
      sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
    
    sudo apt-get update
    sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

    # Add current user to docker group
    sudo usermod -aG docker $USER
    echo "✅ Docker installed. You might need to logout and login again."
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
sudo chown -R $USER:$USER .
chmod -R 775 storage bootstrap/cache

echo "🎉 Setup Complete! You are ready to deploy."
echo "👉 Next step: Run 'docker compose -f docker-compose.prod.yml up -d --build'"
