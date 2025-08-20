# 🚨 cPanel Deployment Guide (proc_open Issue Fix)

## Problem: `Call to undefined function Laravel\Prompts\proc_open()`

This error occurs when cPanel hosting disables the `proc_open()` function for security reasons. Laravel's `artisan key:generate` command uses Laravel Prompts which requires `proc_open()`.

## 🔧 Solution Methods

### Method 1: Manual Key Generation (Recommended)

Use our custom key generator that doesn't require `proc_open()`:

```bash
php scripts/cpanel-key-generator.php
```

### Method 2: Direct .env Edit

1. Generate a key manually:

    ```php
    <?php
    echo 'APP_KEY=base64:' . base64_encode(random_bytes(32));
    ?>
    ```

2. Add the generated key to your `.env` file:
    ```env
    APP_KEY=base64:YOUR_GENERATED_KEY_HERE
    ```

### Method 3: Use Updated Deployment Script

Our updated `deploy-cpanel.php` script now handles this automatically:

```bash
php scripts/deploy-cpanel.php
```

## 📋 Complete cPanel Deployment Steps

### Step 1: Upload Files

-   Upload all project files to `public_html` or subdirectory
-   Ensure `.env` file is configured correctly

### Step 2: Set Environment Variables

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=  # Will be generated automatically
DB_CONNECTION=sqlite  # or mysql
```

### Step 3: Run Deployment Script

```bash
php scripts/deploy-cpanel.php
```

**OR Manual Steps:**

```bash
# 1. Clear caches manually
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*

# 2. Generate key (cPanel compatible)
php scripts/cpanel-key-generator.php

# 3. Install dependencies
composer install --no-dev --optimize-autoloader

# 4. Database migration
php artisan migrate --force
php artisan db:seed --force

# 5. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 4: Set File Permissions

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## 🔍 Verification

Check if key was generated correctly:

```bash
php -r "echo file_get_contents('.env');" | grep APP_KEY
```

Should show something like:

```
APP_KEY=base64:randomBase64StringHere==
```

## 🌟 Troubleshooting

### Error: "No application encryption key has been specified"

-   Run: `php scripts/cpanel-key-generator.php`
-   Verify `.env` file has `APP_KEY=base64:...`

### Error: "Class not found" or ServiceProvider errors

-   Run: `php scripts/production-fix.php`
-   Clear all caches manually

### Error: Database connection

-   Check database credentials in `.env`
-   Ensure database exists
-   Test connection: `php artisan migrate:status`

### Error: File permissions

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📱 Testing

1. **Web Access**: Visit your domain
2. **Login Test**: Use default credentials
    - Super Admin: `superadmin@mdgroup.id` / `Murahsetiaphari`
    - IT Staff: `staffit@mdgroup.id` / `password`
3. **Feature Test**: Try creating a new pengadaan

## 🎯 Production Checklist

-   [ ] `APP_ENV=production` in `.env`
-   [ ] `APP_DEBUG=false` in `.env`
-   [ ] `APP_KEY` generated and valid
-   [ ] Database connected and migrated
-   [ ] File permissions set correctly
-   [ ] Composer packages installed (production only)
-   [ ] Caches optimized
-   [ ] Application accessible via browser
-   [ ] Login functionality working

## 🔗 Additional Resources

-   **Key Generator**: `scripts/cpanel-key-generator.php`
-   **Deployment Script**: `scripts/deploy-cpanel.php`
-   **Production Fix**: `scripts/production-fix.php`
-   **Verification**: `scripts/verify-deployment.php`

---

**💡 Pro Tip**: Save this guide for future deployments. The scripts provided work on any cPanel hosting that has PHP disabled shell functions.

**🚨 Security Note**: Never commit your generated `APP_KEY` to version control. Each environment should have its own unique key.
