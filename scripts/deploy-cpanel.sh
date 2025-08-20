#!/bin/bash

# cPanel Production Deployment Script
# Run this script via cPanel Terminal or SSH

echo "🚀 Starting cPanel Production Deployment..."

# Step 1: Set environment
export APP_ENV=production
echo "APP_ENV=production" >> .env

# Step 2: Aggressive cache clearing
echo "🧹 Clearing all caches..."
rm -rf bootstrap/cache/* 2>/dev/null || true
rm -rf storage/framework/cache/data/* 2>/dev/null || true
rm -rf storage/framework/views/* 2>/dev/null || true
rm -rf storage/framework/sessions/* 2>/dev/null || true

# Step 3: Run production fix
echo "🔧 Running production fix..."
php scripts/production-fix.php

# Step 4: Install production dependencies
echo "📦 Installing production dependencies..."
composer install --no-dev --optimize-autoloader --no-cache

# Step 5: Generate key if needed
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Step 6: Database setup
echo "🗄️ Setting up database..."
php artisan migrate --force
php artisan db:seed --force

# Step 7: Production optimization
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 8: Set permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Deployment completed successfully!"
echo ""
echo "🌐 Your application is ready for production!"
echo "📋 Login credentials:"
echo "   Super Admin: superadmin@mdgroup.id / Murahsetiaphari"
echo "   IT Staff: staffit@mdgroup.id / password"
