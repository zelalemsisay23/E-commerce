# ElectroMart - Online Electronics E-Commerce Store

A complete e-commerce solution built with HTML, CSS, JavaScript, and PHP without frameworks.

## Features

### Core Functionality
- **Product Catalog**: Browse products by categories with detailed product pages
- **Search & Filter**: Advanced search with price filters and sorting options
- **Shopping Cart**: Add, update, and remove items with real-time updates
- **User Authentication**: Secure registration and login system
- **Checkout Process**: Complete order processing with multiple payment options
- **Order Management**: Track orders and view order history

### Additional Features
- **Responsive Design**: Mobile-friendly interface
- **Admin Panel**: Basic admin functionality for order management
- **Stock Management**: Real-time stock tracking
- **Price Calculations**: Automatic tax and shipping calculations
- **Security**: Password hashing and SQL injection protection
- **AJAX Integration**: Smooth user experience without page reloads

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Setup Instructions

1. **Clone/Download** the project files to your web server directory

2. **Database Setup**:
   ```sql
   -- Import the database structure
   mysql -u your_username -p < database/setup.sql
   ```

3. **Configuration**:
   - Edit `includes/config.php` with your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'electromart');
   ```

4. **File Permissions**:
   - Ensure the `images/` directory is writable for product uploads

5. **Access the Application**:
   - Store: `http://your-domain.com/`
   - Admin Panel: `http://your-domain.com/admin/`
   - Admin Credentials: username: `admin`, password: `admin123`

## Project Structure

```
ElectroMart/
├── index.php              # Homepage with featured products
├── product.php            # Product details page
├── search.php             # Search and filter products
├── category.php           # Category product listing
├── cart.php               # Shopping cart
├── checkout.php           # Checkout process
├── login.php              # User login
├── register.php           # User registration
├── orders.php             # User order history
├── order-success.php      # Order confirmation
├── logout.php             # User logout
├── about.php              # About us page
├── contact.php            # Contact us page
├── profile.php            # User profile management
├── includes/
│   ├── config.php         # Database and site configuration
│   ├── header.php         # Common header template
│   └── footer.php         # Common footer template
├── css/
│   └── style.css          # Main stylesheet
├── js/
│   └── main.js            # JavaScript functionality
├── ajax/
│   ├── add_to_cart.php    # Add items to cart
│   ├── update_cart.php    # Update cart quantities
│   ├── remove_from_cart.php # Remove items from cart
│   └── get_cart_count.php # Get cart item count
├── admin/
│   └── index.php          # Admin login
├── images/
│   ├── products/          # Product images
│   └── placeholder.jpg    # Default product image
└── database/
    └── setup.sql          # Database structure and sample data
```

## Database Schema

### Tables
- **users**: Customer accounts and profiles
- **categories**: Product categories
- **products**: Product catalog with specifications
- **cart**: Shopping cart items
- **orders**: Order information
- **order_items**: Individual order items

## Key Features Implementation

### Security
- Password hashing using PHP's `password_hash()`
- SQL injection prevention with prepared statements
- Input sanitization and validation
- Session management for user authentication

### User Experience
- Responsive design for all devices
- AJAX-powered cart operations
- Real-time stock validation
- Smooth animations and transitions
- Form validation with user feedback

### Performance
- Optimized database queries
- Efficient image handling
- Minimal JavaScript for fast loading
- Clean, semantic HTML structure

## Customization

### Adding Products
1. Access the admin panel
2. Use the database directly to add products
3. Upload product images to `images/products/`

### Styling
- Modify `css/style.css` for design changes
- Update color scheme in CSS variables
- Customize layout in template files

### Functionality
- Extend `js/main.js` for additional features
- Add new PHP pages following the existing structure
- Implement additional payment gateways in checkout

## Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Security Notes
- Change default admin credentials in production
- Use HTTPS in production environment
- Implement proper backup procedures
- Regular security updates recommended

## License
This project is open source and available under the MIT License.

## Support
For issues and questions, please refer to the code comments and documentation within the files.