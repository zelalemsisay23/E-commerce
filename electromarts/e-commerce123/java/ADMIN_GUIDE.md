# ElectroMart Admin Guide

## 🔐 Admin Panel Documentation

This comprehensive guide covers all administrative functions and features of the ElectroMart e-commerce platform.

---

## 📋 Table of Contents

1. [Admin Access](#admin-access)
2. [Dashboard Overview](#dashboard-overview)
3. [Product Management](#product-management)
4. [User Management](#user-management)
5. [Order Management](#order-management)
6. [Email System](#email-system)
7. [Reports & Analytics](#reports--analytics)
8. [System Maintenance](#system-maintenance)

---

## 🔐 Admin Access

### Default Admin Credentials
- **Username**: `admin`
- **Password**: `admin123`
- **Email**: `admin@electromart.com`

### Accessing Admin Panel
1. **URL**: `/admin/`
2. **Login Page**: Enter credentials
3. **Dashboard**: Redirected after successful login

### Security Recommendations
1. **Change Default Password**: Immediately after first login
2. **Use Strong Password**: Minimum 12 characters with mixed case, numbers, symbols
3. **Regular Updates**: Change password every 90 days
4. **Secure Connection**: Always use HTTPS in production
5. **Limited Access**: Only authorized personnel

---

## 📊 Dashboard Overview

### Admin Navigation Menu
- **Orders**: Order management and processing
- **Products**: Product catalog management
- **Users**: Customer account management
- **View Store**: Quick link to frontend
- **Logout**: Secure session termination

### Quick Statistics (Future Feature)
- Total orders today/week/month
- Revenue statistics
- New user registrations
- Low stock alerts
- Pending order count

---

## 📦 Product Management

### Accessing Product Management
**URL**: `/admin/products.php`

### Product List View

#### Features
- **Pagination**: 20 products per page
- **Search**: Find products by name, brand, model
- **Filters**: 
  - Category filter
  - Status filter (Active/Inactive)
- **Sorting**: By creation date (newest first)

#### Product Information Displayed
- **ID**: Unique product identifier
- **Product**: Name, brand, and model
- **Category**: Product category
- **Price**: Current selling price
- **Stock**: Quantity available
- **Status**: Active or Inactive
- **Actions**: Edit and Delete buttons

### Adding New Products

#### Step 1: Click "Add New Product"
Opens the product creation modal

#### Step 2: Fill Product Information
**Required Fields**:
- **Product Name**: Full product title
- **Brand**: Manufacturer name
- **Category**: Select from dropdown
- **Price**: Selling price in USD
- **Stock Quantity**: Available inventory
- **Description**: Detailed product description

**Optional Fields**:
- **Model**: Product model number
- **Warranty**: Warranty period (e.g., "1 Year")
- **Status**: Active (default) or Inactive

#### Step 3: Save Product
- Click "Save Product"
- Product appears in list immediately
- Success message confirms creation

### Editing Products

#### Access Edit Mode
- Click edit icon (pencil) next to product
- Modal opens with current product data
- All fields are pre-populated

#### Modify Information
- Update any field as needed
- Changes are saved to database
- Stock levels can be adjusted here

#### Save Changes
- Click "Save Product"
- Confirmation message appears
- Updated information reflects immediately

### Deleting Products

#### Delete Process
1. Click delete icon (trash) next to product
2. Confirmation dialog appears
3. Confirm deletion to proceed
4. Product removed from database

#### Important Notes
- **Permanent Action**: Cannot be undone
- **Order History**: Past orders retain product info
- **Related Data**: Reviews and wishlist entries removed

### Stock Management

#### Stock Status Indicators
- **Green**: Adequate stock (10+ items)
- **Yellow**: Low stock (1-9 items)
- **Red**: Out of stock (0 items)

#### Stock Updates
- Edit product to update quantities
- Automatic stock reduction on orders
- Manual adjustments for inventory corrections

### Product Categories

#### Available Categories
1. **Smartphones**: Mobile devices and accessories
2. **Laptops**: Portable computers
3. **Tablets**: Tablet devices and accessories
4. **Audio**: Headphones, speakers, audio equipment
5. **Gaming**: Gaming consoles and accessories
6. **Accessories**: Cables, chargers, cases

#### Category Management
- Categories are pre-defined in database
- Contact developer to add new categories
- Each product must have a category assigned

---

## 👥 User Management

### Accessing User Management
**URL**: `/admin/users.php`

### User Statistics Dashboard

#### Key Metrics
- **Total Users**: All registered customers
- **New Today**: Users registered today
- **Active Customers**: Users who have placed orders

### User List View

#### User Information Displayed
- **User Avatar**: Generated from initials
- **Full Name**: Customer's complete name
- **Username**: Login identifier
- **Contact**: Email and phone number
- **Orders**: Total number of orders placed
- **Total Spent**: Lifetime customer value
- **Last Order**: Date of most recent purchase
- **Joined**: Account creation date
- **Actions**: View orders, delete user

#### Search Functionality
- Search by name, email, or username
- Real-time filtering as you type
- Clear search to show all users

### User Actions

#### View User Orders
- Click shopping bag icon
- Redirects to orders page filtered by user
- Shows complete order history for customer

#### Delete User Account
1. Click delete icon (trash)
2. Confirmation dialog with user name
3. **Warning**: Deletes all user data including orders
4. Permanent action - cannot be undone

### Customer Insights

#### User Activity Tracking
- Registration date and source
- Order frequency and patterns
- Total lifetime value
- Last activity date

#### Customer Segmentation
- **New Customers**: Recent registrations
- **Active Customers**: Regular purchasers
- **VIP Customers**: High-value customers
- **Inactive Customers**: No recent activity

---

## 📋 Order Management

### Accessing Order Management
**URL**: `/admin/orders.php`

### Order List View

#### Order Information
- **Order ID**: Unique identifier
- **Customer**: Name and email
- **Items**: Number of products ordered
- **Total**: Order value
- **Status**: Current order status
- **Date**: Order placement date
- **Actions**: View details, update status

#### Order Filtering
- **Search**: By order ID, customer name, or email
- **Status Filter**: Filter by order status
- **Date Range**: Filter by order date (future feature)

### Order Status Management

#### Available Statuses
1. **Pending**: New order, payment processing
2. **Processing**: Order confirmed, being prepared
3. **Shipped**: Order dispatched to customer
4. **Delivered**: Order successfully delivered
5. **Cancelled**: Order cancelled, refund processed

#### Updating Order Status
1. **Select Order**: Click on order in list
2. **Change Status**: Select new status from dropdown
3. **Update**: Click "Update Status"
4. **Email Notification**: Automatic email sent to customer

### Order Details View

#### Complete Order Information
- **Customer Details**: Name, email, contact info
- **Order Items**: Products, quantities, prices
- **Pricing Breakdown**: Subtotal, tax, shipping, total
- **Shipping Address**: Delivery location
- **Payment Method**: How customer paid
- **Order Timeline**: Status change history

#### Order Actions
- **Update Status**: Change order progress
- **View Customer**: Access customer profile
- **Print Order**: Generate printable order summary
- **Contact Customer**: Send direct email (future feature)

### Bulk Operations (Future Feature)
- **Bulk Status Updates**: Update multiple orders
- **Export Orders**: Download order data
- **Print Shipping Labels**: Generate labels
- **Order Analytics**: Performance metrics

---

## 📧 Email System

### Email Notification Types

#### Automated Emails
1. **Welcome Email**: New user registration
2. **Order Confirmation**: Purchase confirmation
3. **Status Updates**: Order progress notifications
4. **Contact Notifications**: Admin alerts for inquiries

#### Email Templates
- **Professional Design**: Branded HTML templates
- **Responsive Layout**: Mobile-friendly emails
- **Dynamic Content**: Personalized information
- **Consistent Branding**: ElectroMart styling

### Email Configuration

#### SMTP Settings (Production)
```php
// Recommended for production
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_username = 'your-email@gmail.com';
$smtp_password = 'your-app-password';
```

#### Testing Email System
1. **Register Test User**: Create account with your email
2. **Check Welcome Email**: Verify delivery and formatting
3. **Place Test Order**: Confirm order emails work
4. **Update Order Status**: Test status notification emails

### Email Management

#### Monitoring Email Delivery
- Check server mail logs
- Monitor bounce rates
- Track delivery success
- Handle undeliverable emails

#### Email Best Practices
- **Clear Subject Lines**: Descriptive and relevant
- **Professional Content**: Well-formatted messages
- **Unsubscribe Options**: Required for newsletters
- **Spam Compliance**: Follow email regulations

---

## 📊 Reports & Analytics

### Current Analytics (Basic)

#### User Statistics
- Total registered users
- New registrations per day
- Active vs inactive users
- Customer lifetime value

#### Order Analytics
- Total orders processed
- Revenue by time period
- Average order value
- Order status distribution

#### Product Performance
- Best-selling products
- Low stock alerts
- Category performance
- Product review ratings

### Future Analytics Features

#### Advanced Reporting
- **Sales Dashboard**: Real-time sales metrics
- **Customer Insights**: Behavior analysis
- **Inventory Reports**: Stock movement tracking
- **Financial Reports**: Profit/loss analysis

#### Data Export
- **CSV Export**: Download data for analysis
- **PDF Reports**: Formatted business reports
- **API Access**: Integration with external tools
- **Scheduled Reports**: Automated report generation

---

## 🔧 System Maintenance

### Regular Maintenance Tasks

#### Daily Tasks
- **Monitor Orders**: Check for new orders
- **Review Emails**: Ensure notifications are working
- **Check Stock**: Monitor inventory levels
- **Customer Support**: Respond to inquiries

#### Weekly Tasks
- **Database Backup**: Backup all data
- **Performance Check**: Monitor site speed
- **Security Review**: Check for issues
- **Content Updates**: Add new products

#### Monthly Tasks
- **Analytics Review**: Analyze performance metrics
- **Security Updates**: Update software
- **Inventory Audit**: Verify stock levels
- **Customer Feedback**: Review and respond

### Database Management

#### Backup Procedures
```sql
-- Create database backup
mysqldump -u username -p electromart > backup_YYYY-MM-DD.sql

-- Restore from backup
mysql -u username -p electromart < backup_YYYY-MM-DD.sql
```

#### Database Optimization
```sql
-- Optimize tables for better performance
OPTIMIZE TABLE products, orders, users, order_items;

-- Check table status
SHOW TABLE STATUS;

-- Repair tables if needed
REPAIR TABLE table_name;
```

### Security Management

#### Security Checklist
- [ ] Regular password updates
- [ ] HTTPS enabled
- [ ] File permissions correct
- [ ] Backup system working
- [ ] Error logs monitored
- [ ] Software updates applied

#### Access Control
- **Admin Accounts**: Limit number of admin users
- **Strong Passwords**: Enforce password policies
- **Session Management**: Automatic logout
- **IP Restrictions**: Limit admin access by IP (future)

### Performance Monitoring

#### Key Metrics to Monitor
- **Page Load Times**: Frontend performance
- **Database Query Speed**: Backend performance
- **Server Resources**: CPU, memory, disk usage
- **Error Rates**: Application errors

#### Optimization Tips
- **Image Compression**: Optimize product images
- **Database Indexing**: Ensure proper indexes
- **Caching**: Implement caching strategies
- **CDN**: Use content delivery network

---

## 🚨 Troubleshooting

### Common Admin Issues

#### Can't Access Admin Panel
**Symptoms**: Login page not loading or credentials rejected
**Solutions**:
1. Check admin credentials in database
2. Verify admin_users table exists
3. Reset admin password in database
4. Check file permissions
5. Review error logs

#### Products Not Displaying
**Symptoms**: Products not showing on frontend
**Solutions**:
1. Check product status (must be "active")
2. Verify category assignment
3. Check stock quantity
4. Review database connection
5. Clear any caches

#### Orders Not Processing
**Symptoms**: Orders stuck in pending status
**Solutions**:
1. Check payment processing
2. Verify email system working
3. Review order workflow
4. Check database constraints
5. Monitor error logs

#### Email System Not Working
**Symptoms**: Emails not being sent
**Solutions**:
1. Check PHP mail configuration
2. Verify SMTP settings
3. Test with simple mail script
4. Check spam folders
5. Review mail server logs

### Error Log Monitoring

#### Log Locations
- **PHP Errors**: `/var/log/php_errors.log`
- **Apache Errors**: `/var/log/apache2/error.log`
- **MySQL Errors**: `/var/log/mysql/error.log`
- **Application Logs**: Custom logging in code

#### Common Error Patterns
- Database connection failures
- File permission issues
- Memory limit exceeded
- Session handling problems
- Email delivery failures

---

## 📈 Growth & Scaling

### Performance Optimization

#### Database Optimization
- **Indexing**: Add indexes for frequently queried columns
- **Query Optimization**: Optimize slow queries
- **Connection Pooling**: Use persistent connections
- **Partitioning**: Split large tables (future)

#### Application Optimization
- **Caching**: Implement Redis or Memcached
- **Code Optimization**: Optimize PHP code
- **Asset Optimization**: Compress CSS/JS
- **Image Optimization**: Use WebP format

#### Infrastructure Scaling
- **Load Balancing**: Multiple web servers
- **Database Replication**: Master-slave setup
- **CDN Integration**: Global content delivery
- **Cloud Hosting**: Scalable infrastructure

### Feature Expansion

#### Immediate Enhancements
- **Real Payment Gateway**: Stripe/PayPal integration
- **Advanced Analytics**: Detailed reporting
- **Inventory Management**: Advanced stock control
- **Customer Support**: Live chat system

#### Long-term Roadmap
- **Mobile Apps**: Native iOS/Android apps
- **Multi-vendor**: Marketplace functionality
- **AI Integration**: Recommendation engine
- **International**: Multi-currency/language

---

## 📞 Admin Support

### Getting Help

#### Documentation Resources
- **Installation Guide**: Setup instructions
- **API Documentation**: Technical reference
- **User Manual**: Customer-facing features
- **This Admin Guide**: Administrative functions

#### Technical Support
- **Error Logs**: Check logs first
- **Database Issues**: Verify database integrity
- **Performance Problems**: Monitor system resources
- **Security Concerns**: Review access logs

#### Best Practices
- **Regular Backups**: Daily database backups
- **Security Updates**: Keep software updated
- **Performance Monitoring**: Track key metrics
- **User Training**: Train staff on admin functions

---

**Admin Guide Complete! 🎉**

This guide covers all current administrative functions. As new features are added, this documentation will be updated accordingly. For technical support or questions about admin functionality, refer to the troubleshooting section or contact the development team.