<?php

/**
 * Production Deployment Verification Script
 * Use this to verify your deployment is working correctly
 */

echo "🔍 Verifying Production Deployment...\n";
echo "==================================\n";

$passed = 0;
$failed = 0;
$warnings = 0;

function check_pass($message) {
    global $passed;
    $passed++;
    echo "✅ $message\n";
}

function check_fail($message) {
    global $failed;
    $failed++;
    echo "❌ $message\n";
}

function check_warn($message) {
    global $warnings;
    $warnings++;
    echo "⚠️  $message\n";
}

// Test 1: Check Laravel
echo "1. Testing Laravel Application...\n";
try {
    $output = shell_exec('php artisan --version 2>&1');
    if ($output && strpos($output, 'Laravel Framework') !== false) {
        check_pass("Laravel artisan command works");
        echo "   " . trim($output) . "\n";
    } else {
        check_fail("Laravel artisan command failed");
    }
} catch (Exception $e) {
    check_fail("Laravel check error: " . $e->getMessage());
}
echo "\n";

// Test 2: Environment
echo "2. Checking Environment Configuration...\n";
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    if (strpos($envContent, 'APP_ENV=production') !== false) {
        check_pass("Environment set to production");
    } else {
        check_warn("Environment is not set to production");
    }
    
    if (strpos($envContent, 'APP_DEBUG=false') !== false) {
        check_pass("Debug mode disabled");
    } else {
        check_warn("Debug mode is still enabled");
    }
} else {
    check_fail(".env file not found");
}
echo "\n";

// Test 3: Database
echo "3. Testing Database Connection...\n";
try {
    $output = shell_exec('php artisan migrate:status 2>&1');
    if ($output && strpos($output, 'Migration name') !== false) {
        check_pass("Database connection successful");
        echo "   Migration status checked\n";
    } else {
        check_fail("Database connection failed");
        echo "   Error: " . trim($output) . "\n";
    }
} catch (Exception $e) {
    check_fail("Database check error: " . $e->getMessage());
}
echo "\n";

// Test 4: File Permissions
echo "4. Checking File Permissions...\n";
$storageDir = __DIR__ . '/../storage';
$cacheDir = __DIR__ . '/../bootstrap/cache';

if (is_writable($storageDir)) {
    check_pass("Storage directory is writable");
} else {
    check_fail("Storage directory is not writable");
}

if (is_writable($cacheDir)) {
    check_pass("Bootstrap cache directory is writable");
} else {
    check_fail("Bootstrap cache directory is not writable");
}
echo "\n";

// Test 5: Dev Packages
echo "5. Checking for Development Packages...\n";
$configFile = __DIR__ . '/../bootstrap/cache/config.php';
if (file_exists($configFile)) {
    try {
        $config = require $configFile;
        $providers = $config['app']['providers'] ?? [];
        $devPackages = [
            'Laravel\\Pail\\PailServiceProvider',
            'Laravel\\Breeze\\BreezeServiceProvider', 
            'Laravel\\Sail\\SailServiceProvider'
        ];
        
        $found = array_intersect($providers, $devPackages);
        if (empty($found)) {
            check_pass("No development packages found in production");
        } else {
            check_fail("Development packages found: " . implode(', ', $found));
            echo "   Run: php scripts/production-fix.php\n";
        }
    } catch (Exception $e) {
        check_warn("Could not check cached config: " . $e->getMessage());
    }
} else {
    check_warn("No cached config found (run php artisan config:cache)");
}
echo "\n";

// Test 6: Cache files
echo "6. Checking Cache Optimization...\n";
if (file_exists(__DIR__ . '/../bootstrap/cache/config.php')) {
    check_pass("Configuration cache exists");
} else {
    check_warn("Configuration not cached (run php artisan config:cache)");
}

if (file_exists(__DIR__ . '/../bootstrap/cache/routes-v7.php')) {
    check_pass("Routes cache exists");
} else {
    check_warn("Routes not cached (run php artisan route:cache)");
}
echo "\n";

// Test 7: Application Key
echo "7. Checking Application Key...\n";
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'APP_KEY=base64:') !== false) {
        check_pass("Application key is generated");
    } else {
        check_warn("Application key may not be generated properly");
    }
}
echo "\n";

// Test 8: Basic functionality
echo "8. Testing Basic Functionality...\n";
try {
    $output = shell_exec('php artisan route:list --name=login 2>&1');
    if ($output && strpos($output, 'login') !== false) {
        check_pass("Routes are accessible");
    } else {
        check_warn("Route check inconclusive");
    }
} catch (Exception $e) {
    check_warn("Route check failed: " . $e->getMessage());
}
echo "\n";

// Summary
echo "==================================\n";
echo "🎯 Deployment Verification Complete!\n\n";

echo "📊 Results Summary:\n";
echo "   ✅ Passed: $passed\n";
echo "   ⚠️  Warnings: $warnings\n"; 
echo "   ❌ Failed: $failed\n\n";

if ($failed > 0) {
    echo "🔧 Action Required: Fix failed checks before going live\n\n";
} elseif ($warnings > 0) {
    echo "⚡ Recommended: Address warnings for optimal performance\n\n";
} else {
    echo "🎉 Excellent: Deployment looks good!\n\n";
}

echo "📋 Quick fixes if needed:\n";
echo "   • Run: php scripts/production-fix.php\n";
echo "   • Set permissions: chmod -R 755 storage bootstrap/cache\n";
echo "   • Cache config: php artisan optimize\n\n";

echo "🌐 Default Login Credentials:\n";
echo "   Super Admin: superadmin@mdgroup.id / Murahsetiaphari\n";
echo "   IT Staff: staffit@mdgroup.id / password\n";
