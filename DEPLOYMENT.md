# 🚀 Production Deployment Guide

## Deployment Options

We provide multiple deployment methods to suit different hosting environments:

1. **🖥️ Web Interface (Recommended for beginners)**
2. **🐧 Bash Script (Linux/cPanel with shell access)**
3. **🐘 PHP Script (All environments)**
4. **⚙️ Manual Steps (Complete control)**

## Preparation Steps

1. **Update Environment Configuration**

    ```bash
    cp .env.example .env
    ```

    Update your `.env` file with production settings:

    ```env
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=https://yourdomain.com

    # Database Configuration
    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=pengadaan_barang
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

2. **Database Setup**
    - Create MySQL database: `pengadaan_barang`
    - Import the structure using migrations

---

## 🖥️ Method 1: Web Interface Deployment

**Best for:** Beginners, shared hosting, visual deployment monitoring

1. Upload `scripts/deploy-manager.html` to your web root
2. Open `https://yourdomain.com/deploy-manager.html` in your browser
3. Click "Start Deployment" and monitor progress
4. Wait for completion message

**Features:**

-   ✅ Visual step-by-step progress
-   ✅ Real-time deployment logs
-   ✅ Built-in error handling
-   ✅ No terminal access required

---

## 🐧 Method 2: Bash Script Deployment

**Best for:** Linux servers, cPanel with shell access, VPS

### Quick Deployment (Automated)

```bash
cd /path/to/your/application
bash scripts/deploy-cpanel.sh
```

**Features:**

-   ✅ Fully automated process
-   ✅ Comprehensive error handling
-   ✅ Automatic cache clearing
-   ✅ Production optimization

---

## 🐘 Method 3: PHP Script Deployment

**Best for:** Shared hosting without shell access, Windows servers

1. Upload all files to your web root
2. Run the PHP deployment script:
    ```bash
    php scripts/deploy-cpanel.php
    ```

**Or via web browser:**

-   Navigate to: `https://yourdomain.com/scripts/deploy-cpanel.php`

**Features:**

-   ✅ Works on all PHP environments
-   ✅ No shell access required
-   ✅ Comprehensive error handling
-   ✅ Cross-platform compatibility

---

## ⚙️ Method 4: Manual Deployment

**Best for:** Advanced users, custom configurations, troubleshooting

### Step-by-Step Manual Process

1. **Upload Files**

    - Upload all files to folder public_html atau subdirectory
    - Pastikan folder `storage` dan `bootstrap/cache` writable (755 atau 777)

2. **Environment Configuration**

    ```bash
    cp .env.example .env
    # Edit .env dengan setting production
    ```

3. **Fix Development Package Issues**

    ```bash
    php scripts/production-fix.php
    ```

4. **Install Dependencies**

    ```bash
    composer install --no-dev --optimize-autoloader
    ```

5. **Generate Application Key**

    ```bash
    php artisan key:generate --force
    ```

6. **Database Migration**

    ```bash
    php artisan migrate --force
    php artisan db:seed --force
    ```

7. **Cache Optimization**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

8. **Set Permissions**
    ```bash
    chmod -R 755 storage
    chmod -R 755 bootstrap/cache
    ```

---

## 🔧 Troubleshooting Production Errors

### Laravel Dev Package Errors

If you encounter ServiceProvider errors like:

-   `Laravel\Pail\PailServiceProvider not found`
-   `Laravel\Breeze\BreezeServiceProvider not found`
-   `Laravel\Sail\SailServiceProvider not found`

**Solution 1: Automated Fix**

```bash
php scripts/production-fix.php
```

**Solution 2: Manual Fix**

1. **Clear All Caches**

    ```bash
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    ```

2. **Remove Cached Files**

    ```bash
    rm -rf bootstrap/cache/*.php
    rm -rf storage/framework/cache/data/*
    rm -rf storage/framework/views/*
    ```

3. **Set Environment**

    ```bash
    export APP_ENV=production
    echo "APP_ENV=production" >> .env
    ```

4. **Rebuild Caches**
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

### Database Connection Issues

1. **Check Database Credentials**

    ```env
    DB_CONNECTION=mysql
    DB_HOST=localhost  # or 127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pengadaan_barang
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

2. **Test Connection**

    ```bash
    php artisan migrate:status
    ```

3. **Clear Config Cache**
    ```bash
    php artisan config:clear
    php artisan config:cache
    ```

### File Permission Issues

1. **Set Storage Permissions**

    ```bash
    chmod -R 755 storage
    chmod -R 755 bootstrap/cache
    ```

2. **Set Web Server Ownership (if needed)**
    ```bash
    chown -R www-data:www-data storage
    chown -R www-data:www-data bootstrap/cache
    ```

### Composer Issues

1. **Clear Composer Cache**

    ```bash
    composer clear-cache
    ```

2. **Reinstall Dependencies**
    ```bash
    rm -rf vendor
    composer install --no-dev --optimize-autoloader
    ```

---

## ✅ Post-Deployment Verification

1. **Check Application Status**

    - Visit your domain: `https://yourdomain.com`
    - Verify login functionality
    - Test main features

2. **Default Login Credentials**

    ```
    Super Admin:
    Email: superadmin@mdgroup.id
    Password: Murahsetiaphari

    IT Staff:
    Email: staffit@mdgroup.id
    Password: password
    ```

3. **Check Logs**

    ```bash
    tail -f storage/logs/laravel.log
    ```

4. **Verify Database**
    ```bash
    php artisan migrate:status
    ```

---

## 🎯 Performance Optimization

1. **Enable OPcache** (in php.ini)

    ```ini
    opcache.enable=1
    opcache.memory_consumption=128
    opcache.max_accelerated_files=4000
    opcache.revalidate_freq=60
    ```

2. **Configure Redis** (optional)

    ```env
    CACHE_DRIVER=redis
    SESSION_DRIVER=redis
    QUEUE_CONNECTION=redis
    ```

3. **Enable Gzip Compression** (in .htaccess)
    ```apache
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/plain
        AddOutputFilterByType DEFLATE text/html
        AddOutputFilterByType DEFLATE text/xml
        AddOutputFilterByType DEFLATE text/css
        AddOutputFilterByType DEFLATE application/xml
        AddOutputFilterByType DEFLATE application/xhtml+xml
        AddOutputFilterByType DEFLATE application/rss+xml
        AddOutputFilterByType DEFLATE application/javascript
        AddOutputFilterByType DEFLATE application/x-javascript
    </IfModule>
    ```

---

## 📱 Mobile and Responsive Testing

1. **Test Responsive Design**

    - Test on mobile devices
    - Verify DataTables responsive behavior
    - Check modal functionality on mobile

2. **Performance Testing**
    - Use browser dev tools
    - Check page load times
    - Monitor network requests

---

## 🔒 Security Checklist

1. **Environment Security**

    - [ ] `APP_DEBUG=false` in production
    - [ ] Strong `APP_KEY` generated
    - [ ] Database credentials secure
    - [ ] Remove development packages

2. **File Security**

    - [ ] `.env` file not publicly accessible
    - [ ] `storage` folder proper permissions
    - [ ] Web server configured properly

3. **Application Security**
    - [ ] CSRF protection enabled
    - [ ] Input validation in place
    - [ ] SQL injection protection (Eloquent ORM)
    - [ ] XSS protection enabled

---

## 📞 Support

If you encounter issues during deployment:

1. **Check logs**: `storage/logs/laravel.log`
2. **Run diagnostics**: `php artisan about`
3. **Test components**: Use the troubleshooting commands above
4. **Contact support**: Include error logs and steps taken

**Common Commands for Quick Diagnosis:**

```bash
# Check Laravel version and environment
php artisan about

# Check routes
php artisan route:list

# Check configuration
php artisan config:show

# Check database connection
php artisan migrate:status

# Clear everything and restart
php artisan optimize:clear
php artisan optimize
```

---

**Happy Deploying! 🚀**
