#!/bin/bash

echo "🔍 Verifying Production Deployment..."
echo "=================================="

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check functions
check_pass() {
    echo -e "${GREEN}✅ $1${NC}"
}

check_fail() {
    echo -e "${RED}❌ $1${NC}"
}

check_warn() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# Test 1: Check if Laravel is accessible
echo "1. Testing Laravel Application..."
if php artisan --version > /dev/null 2>&1; then
    check_pass "Laravel artisan command works"
    php artisan --version
else
    check_fail "Laravel artisan command failed"
fi

echo ""

# Test 2: Check environment
echo "2. Checking Environment Configuration..."
if grep -q "APP_ENV=production" .env; then
    check_pass "Environment set to production"
else
    check_warn "Environment is not set to production"
fi

if grep -q "APP_DEBUG=false" .env; then
    check_pass "Debug mode disabled"
else
    check_warn "Debug mode is still enabled"
fi

echo ""

# Test 3: Check database connection
echo "3. Testing Database Connection..."
if php artisan migrate:status > /dev/null 2>&1; then
    check_pass "Database connection successful"
    echo "Migration status:"
    php artisan migrate:status
else
    check_fail "Database connection failed"
fi

echo ""

# Test 4: Check file permissions
echo "4. Checking File Permissions..."
if [ -w storage ]; then
    check_pass "Storage directory is writable"
else
    check_fail "Storage directory is not writable"
fi

if [ -w bootstrap/cache ]; then
    check_pass "Bootstrap cache directory is writable"
else
    check_fail "Bootstrap cache directory is not writable"
fi

echo ""

# Test 5: Check for dev packages in production
echo "5. Checking for Development Packages..."
if php -r "
try {
    \$config = require 'bootstrap/cache/config.php';
    \$providers = \$config['app']['providers'] ?? [];
    \$devPackages = ['Laravel\\\\Pail\\\\PailServiceProvider', 'Laravel\\\\Breeze\\\\BreezeServiceProvider', 'Laravel\\\\Sail\\\\SailServiceProvider'];
    \$found = array_intersect(\$providers, \$devPackages);
    if (empty(\$found)) {
        echo 'clean';
    } else {
        echo 'found: ' . implode(', ', \$found);
    }
} catch (Exception \$e) {
    echo 'no_cache';
}
" 2>/dev/null | grep -q "clean"; then
    check_pass "No development packages found in production"
elif php -r "echo 'test';" 2>/dev/null | grep -q "no_cache"; then
    check_warn "No cached config found (run php artisan config:cache)"
else
    check_fail "Development packages found in production - run production-fix.php"
fi

echo ""

# Test 6: Check cache files
echo "6. Checking Cache Optimization..."
if [ -f bootstrap/cache/config.php ]; then
    check_pass "Configuration cache exists"
else
    check_warn "Configuration not cached (run php artisan config:cache)"
fi

if [ -f bootstrap/cache/routes-v7.php ]; then
    check_pass "Routes cache exists"
else
    check_warn "Routes not cached (run php artisan route:cache)"
fi

echo ""

# Test 7: Check key generation
echo "7. Checking Application Key..."
if grep -q "APP_KEY=base64:" .env; then
    check_pass "Application key is generated"
else
    check_warn "Application key may not be generated properly"
fi

echo ""

# Test 8: Quick web test (if possible)
echo "8. Testing Web Access..."
if command -v curl > /dev/null 2>&1; then
    if curl -s -o /dev/null -w "%{http_code}" http://localhost | grep -q "200\|302"; then
        check_pass "Web server responds correctly"
    else
        check_warn "Web server response check failed"
    fi
else
    check_warn "curl not available for web testing"
fi

echo ""
echo "=================================="
echo "🎯 Deployment Verification Complete!"
echo ""
echo "📋 Quick fixes if needed:"
echo "   • Run: php scripts/production-fix.php"
echo "   • Set permissions: chmod -R 755 storage bootstrap/cache"
echo "   • Cache config: php artisan optimize"
echo ""
echo "🌐 Default Login Credentials:"
echo "   Super Admin: superadmin@mdgroup.id / Murahsetiaphari"
echo "   IT Staff: staffit@mdgroup.id / password"
