<?php

/**
 * cPanel Production Deployment Script (PHP Version)
 * Use this if your cPanel doesn't support bash scripts
 */

echo "🚀 Starting cPanel Production Deployment...\n";

// Step 1: Set environment
putenv('APP_ENV=production');
$_ENV['APP_ENV'] = 'production';

// Update .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'APP_ENV=') === false) {
        file_put_contents($envFile, "\nAPP_ENV=production\n", FILE_APPEND);
    } else {
        $envContent = preg_replace('/APP_ENV=.*/m', 'APP_ENV=production', $envContent);
        file_put_contents($envFile, $envContent);
    }
    echo "📝 Environment set to production\n";
}

// Step 2: Aggressive cache clearing
echo "🧹 Clearing all caches...\n";
$cacheDirs = [
    __DIR__ . '/../bootstrap/cache',
    __DIR__ . '/../storage/framework/cache/data',
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../storage/framework/sessions'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "🗑️  Cleared: " . basename($dir) . "\n";
    }
}

// Step 3: Run production fix
echo "🔧 Running production fix...\n";
include __DIR__ . '/production-fix.php';

// Step 4: Install production dependencies
echo "📦 Installing production dependencies...\n";
$composerCommand = 'composer install --no-dev --optimize-autoloader --no-cache';
exec($composerCommand, $output, $return_var);
if ($return_var === 0) {
    echo "✅ Dependencies installed successfully\n";
} else {
    echo "⚠️  Composer install had issues, continuing...\n";
}

// Step 5: Generate key if needed (cPanel Compatible - No proc_open)
$envContent = file_get_contents($envFile);
if (strpos($envContent, 'APP_KEY=') === false || preg_match('/APP_KEY=\s*$/m', $envContent) || !preg_match('/APP_KEY=base64:.+/', $envContent)) {
    echo "🔑 Generating application key (cPanel compatible)...\n";
    
    // Generate key manually without Laravel Prompts
    $key = base64_encode(random_bytes(32));
    
    if (strpos($envContent, 'APP_KEY=') !== false) {
        // Replace existing empty key
        $envContent = preg_replace('/APP_KEY=.*/', "APP_KEY=base64:{$key}", $envContent);
    } else {
        // Add new key
        $envContent .= "\nAPP_KEY=base64:{$key}\n";
    }
    
    file_put_contents($envFile, $envContent);
    echo "✅ Application key generated successfully!\n";
    echo "🔐 Key: APP_KEY=base64:{$key}\n";
} else {
    echo "ℹ️  Application key already exists\n";
}

// Step 6: Database setup
echo "🗄️ Setting up database...\n";
exec('php artisan migrate --force', $output, $return_var);
if ($return_var === 0) {
    echo "✅ Migrations completed\n";

    exec('php artisan db:seed --force', $output, $return_var);
    if ($return_var === 0) {
        echo "✅ Seeders completed\n";
    } else {
        echo "⚠️  Seeders had issues, you may need to run manually\n";
    }
} else {
    echo "❌ Migration failed! Check your database configuration\n";
    exit(1);
}

// Step 7: Production optimization
echo "⚡ Optimizing for production...\n";
$optimizationCommands = [
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache'
];

foreach ($optimizationCommands as $cmd) {
    exec($cmd, $output, $return_var);
    echo "✅ " . str_replace('php artisan ', '', $cmd) . " completed\n";
}

// Step 8: Set permissions
echo "🔒 Setting permissions...\n";
chmod(__DIR__ . '/../storage', 0755);
chmod(__DIR__ . '/../bootstrap/cache', 0755);

echo "\n✅ Deployment completed successfully!\n";
echo "\n🌐 Your application is ready for production!\n";
echo "📋 Login credentials:\n";
echo "   Super Admin: superadmin@mdgroup.id / Murahsetiaphari\n";
echo "   IT Staff: staffit@mdgroup.id / password\n";
echo "\n🔗 Access your application now!\n";
