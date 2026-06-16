# ElectroMart E-Commerce Platform - Complete Documentation

## 📋 Table of Contents
1. [Project Overview](#project-overview)
2. [Installation Guide](#installation-guide)
3. [Features & Functionality](#features--functionality)
4. [File Structure](#file-structure)
5. [Database Schema](#database-schema)
6. [Admin Panel Guide](#admin-panel-guide)
7. [User Guide](#user-guide)
8. [API Documentation](#api-documentation)
9. [Troubleshooting](#troubleshooting)
10. [Future Enhancements](#future-enhancements)

---

## 🎯 Project Overview

**ElectroMart** is a complete e-commerce platform built with PHP, MySQL, HTML5, CSS3, and JavaScript. It provides a full-featured online store for electronics with advanced admin management, user accounts, and professional email notifications.

### Key Highlights
- **Technology Stack**: PHP 7.4+, MySQL 5.7+, HTML5, CSS3, JavaScript
- **Architecture**: MVC-style without frameworks
- **Security**: Password hashing, SQL injection protection, input sanitization
- **Design**: Responsive, mobile-friendly interface
- **Features**: 25+ products, 6 categories, complete admin panel, email system

---

## 🚀 Installation Guide

### Prerequisites
- **Web Server**: Apache or Nginx
- **PHP**: Version 7.4 or higher
- **MySQL**: Version 5.7 or higher
- **Extensions**: PDO, MySQLi, Mail

### Step-by-Step Installation

#### 1. Download & Setup
```bash
# Place files in your web server directory
# Example: C:\xampp\htdocs\java (for XAMPP)
```

#### 2. Database Configuration
1. Open **phpMyAdmin** (`http://localhost/phpmyadmin`)
2. Create database `electromart`
3. Import `database/setup.sql`
4. Verify all tables are created

#### 3. Configuration
Edit `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'electromart');
define('SITE_URL', 'http://localhost/java');
```

#### 4. File Permissions
- Ensure `images/` directory is writable
- Set proper permissions for upload directories

#### 5. Test Installation
- Visit: `http://localhost/java`
- Admin Panel: `http://localhost/java/admin`
- Default Admin: `admin` / `admin123`

---

## ✨ Features & Functionality

### 🛍️ Customer Features
- **Product Browsing**: 6 categories, 25+ products
- **Advanced Search**: Search by name, brand, category
- **Shopping Cart**: Add, update, remove items
- **Wishlist**: Save products for later
- **User Accounts**: Registration, login, profile management
- **Order Management**: Place orders, track status, view history
- **Product Reviews**: 5-star rating system
- **Product Comparison**: Compare multiple products
- **Responsive Design**: Mobile and tablet friendly

### 🔧 Admin Features
- **Product Management**: Add, edit, delete products
- **User Management**: View users, manage accounts
- **Order Management**: Process orders, update status
- **Inventory Control**: Stock tracking, low stock alerts
- **Analytics**: Basic sales and user statistics
- **Email System**: Automated notifications

### 📧 Email System
- **Welcome Emails**: New user registration
- **Order Confirmations**: Purchase confirmations
- **Status Updates**: Order progress notifications
- **Contact Notifications**: Admin notifications for inquiries

### 🔒 Security Features
- **Password Hashing**: Secure password storage
- **SQL Injection Protection**: Prepared statements
- **Input Sanitization**: XSS protection
- **Session Management**: Secure user sessions

---

## 📁 File Structure

```
java/
├── admin/                      # Admin panel files
│   ├── index.php              # Admin login
│   ├── products.php           # Product management
│   ├── users.php              # User management
│   ├── orders.php             # Order management
│   └── logout.php             # Admin logout
├── ajax/                       # AJAX endpoints
│   ├── add_to_cart.php        # Cart operations
│   ├── add_to_wishlist.php    # Wishlist operations
│   ├── newsletter_subscribe.php # Newsletter signup
│   └── ...                    # Other AJAX handlers
├── css/
│   └── style.css              # Main stylesheet
├── database/
│   └── setup.sql              # Database schema & data
├── images/
│   ├── placeholder.jpg        # Default product image
│   └── products/              # Product images
├── includes/
│   ├── config.php             # Database & site config
│   ├── header.php             # Common header
│   ├── footer.php             # Common footer
│   ├── email.php              # Email notification system
│   └── payment.php            # Payment processing
├── js/
│   └── main.js                # JavaScript functions
├── index.php                  # Homepage
├── product.php                # Product details
├── category.php               # Category listings
├── cart.php                   # Shopping cart
├── checkout.php               # Checkout process
├── login.php                  # User login
├── register.php               # User registration
├── profile.php                # User dashboard
├── orders.php                 # Order history
├── wishlist.php               # User wishlist
├── reviews.php                # Product reviews
├── search.php                 # Search results
├── about.php                  # About page
├── contact.php                # Contact form
├── privacy.php                # Privacy policy
├── terms.php                  # Terms of service
├── shipping.php               # Shipping information
├── returns.php                # Returns policy
├── warranty.php               # Warranty information
└── track-order.php            # Order tracking
```

---

## 🗄️ Database Schema

### Core Tables

#### users
```sql
- id (INT, PRIMARY KEY)
- username (VARCHAR(50), UNIQUE)
- email (VARCHAR(100), UNIQUE)
- password (VARCHAR(255))
- full_name (VARCHAR(100))
- phone (VARCHAR(20))
- address (TEXT)
- created_at (TIMESTAMP)
```

#### products
```sql
- id (INT, PRIMARY KEY)
- name (VARCHAR(200))
- description (TEXT)
- price (DECIMAL(10,2))
- category_id (INT, FOREIGN KEY)
- stock_quantity (INT)
- image (VARCHAR(255))
- brand (VARCHAR(100))
- model (VARCHAR(100))
- warranty (VARCHAR(50))
- status (ENUM: active/inactive)
- created_at (TIMESTAMP)
```

#### orders
```sql
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- total_amount (DECIMAL(10,2))
- status (ENUM: pending/processing/shipped/delivered/cancelled)
- shipping_address (TEXT)
- payment_method (VARCHAR(50))
- created_at (TIMESTAMP)
```

### Additional Tables
- **categories**: Product categories
- **cart**: Shopping cart items
- **order_items**: Individual order items
- **wishlist**: User wishlists
- **reviews**: Product reviews
- **newsletter**: Email subscribers
- **coupons**: Discount codes
- **admin_users**: Admin accounts
- **payment_transactions**: Payment records

---

## 👨‍💼 Admin Panel Guide

### Accessing Admin Panel
1. Navigate to `/admin/`
2. Login with: `admin` / `admin123`
3. Access admin dashboard

### Product Management (`/admin/products.php`)
- **Add Products**: Click "Add New Product"
- **Edit Products**: Click edit icon in product list
- **Delete Products**: Click delete icon (with confirmation)
- **Search/Filter**: Use search bar and filters
- **Stock Management**: Update quantities directly

### User Management (`/admin/users.php`)
- **View Users**: See all registered users
- **User Statistics**: Orders, spending, activity
- **Search Users**: Find specific users
- **Delete Users**: Remove user accounts

### Order Management (`/admin/orders.php`)
- **View Orders**: All customer orders
- **Update Status**: Change order status
- **Order Details**: View complete order information
- **Email Notifications**: Automatic status update emails

---

## 👤 User Guide

### Registration & Login
1. **Register**: Click "Register" → Fill form → Receive welcome email
2. **Login**: Use username/email and password
3. **Profile**: Update personal information and password

### Shopping Process
1. **Browse**: Visit categories or search products
2. **Product Details**: Click product for full information
3. **Add to Cart**: Select quantity and add to cart
4. **Checkout**: Review cart → Enter shipping → Select payment
5. **Confirmation**: Receive order confirmation email

### Account Features
- **Dashboard**: View order statistics and quick actions
- **Order History**: Track all past orders
- **Wishlist**: Save products for later purchase
- **Reviews**: Rate and review purchased products
- **Profile**: Update personal information

---

## 🔌 API Documentation

### AJAX Endpoints

#### Cart Operations
```javascript
// Add to Cart
POST /ajax/add_to_cart.php
{
    "product_id": 123,
    "quantity": 2
}

// Update Cart
POST /ajax/update_cart.php
{
    "product_id": 123,
    "quantity": 3
}

// Remove from Cart
POST /ajax/remove_from_cart.php
{
    "product_id": 123
}
```

#### Wishlist Operations
```javascript
// Add/Remove Wishlist
POST /ajax/add_to_wishlist.php
{
    "product_id": 123
}
```

#### Newsletter
```javascript
// Subscribe to Newsletter
POST /ajax/newsletter_subscribe.php
{
    "email": "user@example.com"
}
```

### Response Format
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {
        "cart_count": 3,
        "wishlist_count": 5
    }
}
```

---

## 🔧 Troubleshooting

### Common Issues

#### Database Connection Error
**Problem**: "Connection failed" error
**Solution**: 
1. Check database credentials in `includes/config.php`
2. Ensure MySQL service is running
3. Verify database exists

#### Missing Tables Error
**Problem**: "Table doesn't exist" error
**Solution**:
1. Import `database/setup.sql` in phpMyAdmin
2. Check all tables are created
3. Verify table names match code

#### Email Not Sending
**Problem**: Welcome/notification emails not received
**Solution**:
1. Check PHP mail configuration
2. Verify SMTP settings
3. Check spam folder
4. Test with local mail server

#### Image Upload Issues
**Problem**: Product images not displaying
**Solution**:
1. Check `images/products/` directory permissions
2. Verify image file paths
3. Ensure proper file extensions

#### Session Issues
**Problem**: Login not persisting
**Solution**:
1. Check session configuration
2. Verify session directory permissions
3. Clear browser cookies

### Performance Optimization
- Enable PHP OPcache
- Optimize database queries
- Compress images
- Use CDN for static files
- Enable GZIP compression

---

## 🚀 Future Enhancements

### High Priority
1. **Real Payment Gateway**: Stripe/PayPal integration
2. **Advanced Analytics**: Sales reports, customer insights
3. **Inventory Alerts**: Low stock notifications
4. **Multi-language Support**: Internationalization
5. **Mobile App**: Native iOS/Android apps

### Medium Priority
1. **Advanced Search**: Filters, faceted search
2. **Recommendation Engine**: Personalized suggestions
3. **Loyalty Program**: Points and rewards system
4. **Live Chat**: Customer support integration
5. **Social Login**: Facebook/Google authentication

### Low Priority
1. **Multi-vendor Support**: Marketplace functionality
2. **Advanced Reporting**: Business intelligence
3. **API Development**: RESTful API for integrations
4. **Progressive Web App**: PWA features
5. **AI Integration**: Chatbots, smart recommendations

---

## 📞 Support & Maintenance

### Regular Maintenance Tasks
- **Database Backup**: Weekly automated backups
- **Security Updates**: Keep PHP/MySQL updated
- **Log Monitoring**: Check error logs regularly
- **Performance Monitoring**: Monitor page load times
- **Content Updates**: Add new products regularly

### Security Best Practices
- Use HTTPS in production
- Regular security audits
- Update dependencies
- Monitor for vulnerabilities
- Implement rate limiting

### Backup Strategy
- **Database**: Daily automated backups
- **Files**: Weekly file system backups
- **Images**: Cloud storage backup
- **Configuration**: Version control for code

---

## 📝 Changelog

### Version 1.0.0 (Current)
- ✅ Complete e-commerce functionality
- ✅ Admin panel with product/user management
- ✅ Professional email system
- ✅ Responsive design
- ✅ Security implementations
- ✅ Policy pages (Privacy, Terms, etc.)

### Planned Updates
- **v1.1.0**: Real payment gateway integration
- **v1.2.0**: Advanced analytics dashboard
- **v1.3.0**: Mobile app development
- **v2.0.0**: Multi-vendor marketplace

---

## 📄 License & Credits

### License
This project is proprietary software developed for ElectroMart.

### Credits
- **Developer**: Custom development
- **Icons**: Font Awesome 6.0
- **Fonts**: System fonts
- **Framework**: Custom PHP/MySQL implementation

### Third-Party Libraries
- Font Awesome (Icons)
- Modern browsers compatibility
- Responsive CSS Grid/Flexbox

---

**© 2024 ElectroMart. All rights reserved.**

For technical support or questions, contact the development team.