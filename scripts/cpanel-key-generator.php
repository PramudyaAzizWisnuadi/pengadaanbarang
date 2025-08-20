<?php

/**
 * cPanel Key Generator (No proc_open required)
 *
 * Specifically designed for cPanel hosting where proc_open() is disabled
 * This script generates Laravel application key without using artisan command
 */

echo "🔑 cPanel Laravel Key Generator\n";
echo "===============================\n";

$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    echo "❌ Error: .env file not found!\n";
    echo "Please make sure .env file exists in the root directory.\n";
    exit(1);
}

$envContent = file_get_contents($envFile);

// Check if key already exists and is valid
if (preg_match('/APP_KEY=base64:[A-Za-z0-9+\/]+=*/', $envContent)) {
    echo "ℹ️  Valid application key already exists.\n";
    echo "If you need to regenerate, remove the current APP_KEY line from .env first.\n";
    exit(0);
}

echo "🔧 Generating new application key...\n";

// Generate a secure 32-byte key (Laravel standard)
$key = base64_encode(random_bytes(32));
$appKeyLine = "APP_KEY=base64:{$key}";

if (strpos($envContent, 'APP_KEY=') !== false) {
    // Replace existing APP_KEY line
    $envContent = preg_replace('/APP_KEY=.*/', $appKeyLine, $envContent);
    echo "🔄 Replaced existing APP_KEY\n";
} else {
    // Add new APP_KEY line
    $envContent .= "\n{$appKeyLine}\n";
    echo "➕ Added new APP_KEY\n";
}

// Write back to .env file
if (file_put_contents($envFile, $envContent)) {
    echo "✅ Application key generated successfully!\n";
    echo "🔐 Key: {$appKeyLine}\n";
    echo "\n📋 Next steps:\n";
    echo "1. Verify the key is in your .env file\n";
    echo "2. Run: php artisan config:cache\n";
    echo "3. Test your application\n";
} else {
    echo "❌ Error: Could not write to .env file!\n";
    echo "Please check file permissions.\n";
    exit(1);
}

echo "\n🎉 Key generation completed!\n";
echo "Your Laravel application is now ready with a secure key.\n";
