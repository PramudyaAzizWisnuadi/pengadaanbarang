<?php

/**
 * Production Bootstrap Fix
 *
 * This file helps handle dev-only ServiceProvider issues in production
 * Fixes: Laravel Pail, Laravel Breeze, and other dev-only packages
 */

echo "🚀 Starting Production Bootstrap Fix...\n";

// Step 1: Set environment to production
if (!getenv('APP_ENV')) {
    putenv('APP_ENV=production');
}
$_ENV['APP_ENV'] = 'production';

echo "📝 Environment set to: " . getenv('APP_ENV') . "\n";

// Step 2: Clear all cached configurations
$cacheFiles = [
    __DIR__ . '/../bootstrap/cache/config.php',
    __DIR__ . '/../bootstrap/cache/routes.php', 
    __DIR__ . '/../bootstrap/cache/services.php',
    __DIR__ . '/../bootstrap/cache/packages.php',
    __DIR__ . '/../storage/framework/cache/data/*',
];

$clearedFiles = 0;
foreach ($cacheFiles as $file) {
    if (strpos($file, '*') !== false) {
        // Handle wildcard patterns
        $pattern = $file;
        $files = glob($pattern);
        foreach ($files as $f) {
            if (file_exists($f)) {
                unlink($f);
                $clearedFiles++;
            }
        }
    } else {
        if (file_exists($file)) {
            unlink($file);
            $clearedFiles++;
            echo "🗑️  Cleared: " . basename($file) . "\n";
        }
    }
}

// Step 3: Clear artisan cache if possible
try {
    if (function_exists('exec')) {
        exec('php artisan config:clear 2>/dev/null', $output, $return_var);
        exec('php artisan cache:clear 2>/dev/null', $output, $return_var);
        echo "🧹 Artisan cache cleared\n";
    }
} catch (Exception $e) {
    echo "⚠️  Could not run artisan cache clear (this is OK)\n";
}

// Step 4: Check for problematic dev packages in cached config
$configPath = __DIR__ . '/../bootstrap/cache/config.php';
if (file_exists($configPath)) {
    $configContent = file_get_contents($configPath);
    $devPackages = ['Laravel\\Pail\\', 'Laravel\\Breeze\\', 'Laravel\\Sail\\'];
    
    foreach ($devPackages as $package) {
        if (strpos($configContent, $package) !== false) {
            echo "⚠️  Found {$package} in cached config - removing cache file\n";
            unlink($configPath);
            break;
        }
    }
}

echo "✅ Production bootstrap fix completed\n";
echo "� Cache files cleared: {$clearedFiles}\n";
echo "🎯 Ready for migration!\n\n";

echo "Next steps:\n";
echo "1. composer install --no-dev --optimize-autoloader\n";
echo "2. php artisan key:generate --force\n";
echo "3. php artisan migrate --force\n";
echo "4. php artisan db:seed --force\n";
