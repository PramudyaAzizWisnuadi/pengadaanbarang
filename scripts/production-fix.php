<?php

/**
 * Production Bootstrap Fix
 *
 * This file helps handle Laravel Pail ServiceProvider issues in production
 * Place this in your deployment scripts before running migrations
 */

// Clear all cached configurations
if (file_exists(__DIR__ . '/../bootstrap/cache/config.php')) {
    unlink(__DIR__ . '/../bootstrap/cache/config.php');
}

if (file_exists(__DIR__ . '/../bootstrap/cache/routes.php')) {
    unlink(__DIR__ . '/../bootstrap/cache/routes.php');
}

if (file_exists(__DIR__ . '/../bootstrap/cache/services.php')) {
    unlink(__DIR__ . '/../bootstrap/cache/services.php');
}

// Ensure APP_ENV is set to production
if (!getenv('APP_ENV')) {
    putenv('APP_ENV=production');
}

echo "✅ Production bootstrap fix applied\n";
echo "📝 Environment: " . (getenv('APP_ENV') ?: 'not set') . "\n";
echo "🧹 Cache files cleared\n";
