# ElectroMart Installation Guide

## 🚀 Quick Start Installation

### Prerequisites Checklist
- [ ] Web Server (Apache/Nginx)
- [ ] PHP 7.4 or higher
- [ ] MySQL 5.7 or higher
- [ ] phpMyAdmin (recommended)

---

## 📋 Step-by-Step Installation

### Step 1: Download & Extract Files
1. Download the ElectroMart project files
2. Extract to your web server directory:
   - **XAMPP**: `C:\xampp\htdocs\java`
   - **WAMP**: `C:\wamp64\www\java`
   - **Linux**: `/var/www/html/java`

### Step 2: Database Setup

#### Option A: Using phpMyAdmin (Recommended)
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click "New" to create database
3. Database name: `electromart`
4. Click "Create"
5. Select the `electromart` database
6. Click "Import" tab
7. Choose file: `database/setup.sql`
8. Click "Go" to import

#### Option B: Using MySQL Command Line
```bash
mysql -u root -p
CREATE DATABASE electromart;
USE electromart;
SOURCE /path/to/java/database/setup.sql;
```

### Step 3: Configuration
Edit `includes/config.php` with your settings:

```php
<?php
// Database configuration
define('DB_HOST', 'localhost');        // Your MySQL host
define('DB_USER', 'root');             // Your MySQL username
define('DB_PASS', '');                 // Your MySQL password
define('DB_NAME', 'electromart');      // Database name

// Site configuration
define('SITE_NAME', 'ElectroMart');
define('SITE_URL', 'http://localhost/java');  // Your site URL
define('ADMIN_EMAIL', 'admin@electromart.com');
?>
```

### Step 4: File Permissions
Set proper permissions for upload directories:

**Windows (XAMPP):**
- Right-click `images` folder → Properties → Security
- Give "Full Control" to web server user

**Linux:**
```bash
chmod 755 images/
chmod 755 images/products/
chown -R www-data:www-data images/
```

### Step 5: Test Installation
1. **Frontend**: Visit `http://localhost/java`
2. **Admin Panel**: Visit `http://localhost/java/admin`
3. **Default Admin Login**:
   - Username: `admin`
   - Password: `admin123`

---

## ✅ Verification Checklist

### Database Verification
- [ ] Database `electromart` created
- [ ] 12 tables imported successfully
- [ ] Sample data loaded (categories, products, admin user)
- [ ] No import errors in phpMyAdmin

### File Structure Verification
```
java/
├── ✅ admin/
├── ✅ ajax/
├── ✅ css/
├── ✅ database/
├── ✅ images/
├── ✅ includes/
├── ✅ js/
└── ✅ All PHP files present
```

### Functionality Tests
- [ ] Homepage loads without errors
- [ ] Product categories display
- [ ] User registration works
- [ ] User login works
- [ ] Admin panel accessible
- [ ] Products display correctly
- [ ] Shopping cart functions
- [ ] Email system configured

---

## 🔧 Configuration Options

### Email Configuration
For email notifications to work, configure PHP mail settings:

**Windows (XAMPP):**
Edit `php.ini`:
```ini
[mail function]
SMTP = localhost
smtp_port = 25
sendmail_from = admin@electromart.com
```

**Linux:**
Install and configure sendmail or postfix:
```bash
sudo apt-get install sendmail
sudo service sendmail start
```

### Security Configuration
1. **Change Default Admin Password**:
   - Login to admin panel
   - Go to admin settings
   - Update password

2. **Update Site URL**:
   ```php
   define('SITE_URL', 'https://yourdomain.com');
   ```

3. **Enable HTTPS** (Production):
   ```php
   // Force HTTPS
   if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
       header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
       exit();
   }
   ```

---

## 🚨 Troubleshooting

### Common Installation Issues

#### Issue: "Connection failed" Error
**Cause**: Database connection problem
**Solution**:
1. Check MySQL service is running
2. Verify database credentials in `config.php`
3. Test database connection manually

#### Issue: "Table doesn't exist" Error
**Cause**: Database not imported properly
**Solution**:
1. Re-import `database/setup.sql`
2. Check for import errors in phpMyAdmin
3. Verify all 12 tables exist

#### Issue: Blank Page or PHP Errors
**Cause**: PHP configuration or syntax errors
**Solution**:
1. Check PHP error logs
2. Enable error display:
   ```php
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   ```
3. Check PHP version compatibility

#### Issue: Images Not Loading
**Cause**: File permissions or path issues
**Solution**:
1. Check `images/` directory permissions
2. Verify image file paths
3. Check web server configuration

#### Issue: Admin Panel Not Accessible
**Cause**: Missing admin user or incorrect credentials
**Solution**:
1. Check if admin user exists in database
2. Reset admin password in database:
   ```sql
   UPDATE admin_users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'admin';
   ```
   (Password: admin123)

---

## 🔄 Post-Installation Setup

### 1. Admin Configuration
1. Login to admin panel
2. Update admin profile
3. Change default password
4. Configure site settings

### 2. Content Setup
1. **Add Products**: Use admin panel to add your products
2. **Update Categories**: Modify or add product categories
3. **Configure Policies**: Update privacy, terms, shipping policies
4. **Test Orders**: Place test orders to verify functionality

### 3. Email Testing
1. Register a test user account
2. Check if welcome email is received
3. Place a test order
4. Verify order confirmation email

### 4. Security Hardening
1. **Remove Default Data**: Delete sample products if not needed
2. **Update Passwords**: Change all default passwords
3. **File Permissions**: Set restrictive permissions
4. **Backup Setup**: Configure regular backups

---

## 📊 Performance Optimization

### PHP Optimization
```ini
; php.ini optimizations
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
```

### MySQL Optimization
```sql
-- Optimize database tables
OPTIMIZE TABLE products, orders, users;

-- Add indexes for better performance
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
```

### Web Server Optimization
**Apache (.htaccess):**
```apache
# Enable compression
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

# Browser caching
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
</IfModule>
```

---

## 🔐 Security Checklist

### Pre-Production Security
- [ ] Change all default passwords
- [ ] Enable HTTPS
- [ ] Update file permissions
- [ ] Remove development files
- [ ] Configure firewall
- [ ] Set up SSL certificate
- [ ] Enable security headers
- [ ] Configure backup system

### Security Headers
Add to `.htaccess`:
```apache
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
```

---

## 📞 Support

### Getting Help
1. **Check Documentation**: Review all documentation files
2. **Error Logs**: Check PHP and web server error logs
3. **Database Logs**: Check MySQL error logs
4. **Community**: Search for similar issues online

### Maintenance Schedule
- **Daily**: Monitor error logs
- **Weekly**: Database backup
- **Monthly**: Security updates
- **Quarterly**: Performance review

---

**Installation Complete! 🎉**

Your ElectroMart e-commerce platform is now ready for use. Visit the admin panel to start adding your products and configuring your store.