# ElectroMart User Manual

## 👋 Welcome to ElectroMart!

This comprehensive user manual will guide you through all features and functionality of the ElectroMart e-commerce platform.

---

## 📋 Table of Contents

1. [Getting Started](#getting-started)
2. [Account Management](#account-management)
3. [Shopping Guide](#shopping-guide)
4. [Cart & Checkout](#cart--checkout)
5. [Order Management](#order-management)
6. [Wishlist & Reviews](#wishlist--reviews)
7. [Customer Support](#customer-support)
8. [Troubleshooting](#troubleshooting)

---

## 🚀 Getting Started

### Creating Your Account

1. **Visit the Registration Page**
   - Click "Register" in the top navigation
   - Or visit: `/register.php`

2. **Fill Out Registration Form**
   - **Full Name**: Your complete name
   - **Username**: Unique identifier (cannot be changed later)
   - **Email**: Valid email address (for order confirmations)
   - **Phone**: Contact number (optional)
   - **Password**: Minimum 6 characters

3. **Complete Registration**
   - Click "Create Account"
   - Check your email for welcome message
   - You can now log in with your credentials

### Logging In

1. **Access Login Page**
   - Click "Login" in the top navigation
   - Or visit: `/login.php`

2. **Enter Credentials**
   - Username or Email
   - Password

3. **Stay Logged In**
   - Check "Remember Me" for convenience
   - Your session will remain active

---

## 👤 Account Management

### User Dashboard

Access your dashboard by clicking your username in the top navigation after logging in.

#### Dashboard Overview
- **Order Statistics**: Total orders, amount spent
- **Wishlist Count**: Items saved for later
- **Cart Items**: Current shopping cart contents
- **Quick Actions**: Links to orders, wishlist, cart

#### Profile Management

1. **Update Personal Information**
   - Full Name
   - Email Address
   - Phone Number
   - Shipping Address

2. **Change Password**
   - Enter current password
   - Set new password (minimum 6 characters)
   - Confirm new password

3. **Account Information**
   - View member since date
   - Check account status
   - See customer level (Bronze/Silver/Gold/VIP)

### Security Features

- **Secure Password Storage**: All passwords are encrypted
- **Session Management**: Automatic logout after inactivity
- **Email Notifications**: Alerts for account changes

---

## 🛍️ Shopping Guide

### Browsing Products

#### Homepage
- **Featured Products**: Latest and popular items
- **Categories**: Browse by product type
- **Search Bar**: Find specific products

#### Category Pages
- **Filter Options**:
  - Price range (minimum/maximum)
  - Sort by: Name, Price (low to high), Price (high to low), Newest
- **Product Grid**: Visual display of all products
- **Product Information**: Name, brand, price, stock status

#### Product Details Page
- **Product Images**: High-quality product photos
- **Detailed Information**:
  - Brand and model
  - Full description
  - Specifications
  - Warranty information
  - Price and stock status
- **Customer Reviews**: Ratings and comments from other buyers
- **Related Products**: Similar items you might like

### Search Functionality

#### Basic Search
- Use the search bar in the header
- Enter product name, brand, or keywords
- Results show matching products

#### Advanced Search
- Visit the search page for more options
- Filter by category, price range
- Sort results by various criteria

### Product Information

#### Understanding Product Details
- **Brand**: Manufacturer name
- **Model**: Specific product model
- **Price**: Current selling price
- **Stock Status**: 
  - "In Stock" (green): Available for purchase
  - "Low Stock" (yellow): Limited quantity
  - "Out of Stock" (red): Currently unavailable
- **Warranty**: Coverage period and terms

---

## 🛒 Cart & Checkout

### Shopping Cart Management

#### Adding Items to Cart
1. **From Product Page**:
   - Select quantity
   - Click "Add to Cart"
   - Item appears in cart with confirmation

2. **From Category/Search Pages**:
   - Click "Add to Cart" on product cards
   - Default quantity of 1 is added

#### Cart Operations
- **View Cart**: Click cart icon in header
- **Update Quantities**: Change item quantities
- **Remove Items**: Delete unwanted products
- **Continue Shopping**: Return to browse more products

#### Cart Information
- **Item Details**: Product name, price, quantity
- **Subtotal**: Total before taxes and shipping
- **Tax Calculation**: 8% tax on subtotal
- **Shipping**: Free on orders over $50, otherwise $9.99
- **Total**: Final amount to pay

### Checkout Process

#### Step 1: Review Cart
- Verify all items and quantities
- Apply coupon codes if available
- Check totals are correct

#### Step 2: Shipping Information
- **Shipping Address**: Enter complete delivery address
- **Address Format**:
  ```
  Full Name
  Street Address
  City, State ZIP Code
  Country
  ```

#### Step 3: Payment Method
Choose from available options:
- **Credit Card**: Visa, MasterCard, American Express
- **PayPal**: Secure PayPal payment
- **Cash on Delivery**: Pay when order arrives (if available)

#### Step 4: Order Confirmation
- Review all order details
- Confirm shipping address
- Verify payment method
- Click "Place Order"

#### Step 5: Order Completion
- Receive order confirmation number
- Email confirmation sent automatically
- Order appears in your order history

---

## 📦 Order Management

### Order History

Access your orders through:
- Dashboard → "View All Orders"
- User menu → "My Orders"
- Direct link: `/orders.php`

#### Order Information
Each order displays:
- **Order Number**: Unique identifier
- **Order Date**: When order was placed
- **Items**: Products and quantities
- **Total Amount**: Final price paid
- **Status**: Current order progress
- **Actions**: View details, track order

### Order Status Tracking

#### Status Meanings
- **Pending**: Order received, payment processing
- **Processing**: Order confirmed, being prepared
- **Shipped**: Order dispatched, in transit
- **Delivered**: Order successfully delivered
- **Cancelled**: Order cancelled (refund processed)

#### Order Details Page
- **Complete Item List**: All products ordered
- **Pricing Breakdown**: Subtotal, tax, shipping, total
- **Shipping Information**: Delivery address
- **Payment Details**: Method used
- **Status Timeline**: Progress tracking
- **Actions**: Contact support, reorder items

### Order Tracking

#### Track Your Order
1. **From Order History**: Click "Track" on any order
2. **Guest Tracking**: Use order number and email
3. **Email Links**: Click tracking links in order emails

#### Tracking Information
- **Current Status**: Where your order is now
- **Estimated Delivery**: Expected arrival date
- **Shipping Carrier**: Delivery company information
- **Tracking Number**: For carrier website tracking

---

## ❤️ Wishlist & Reviews

### Wishlist Management

#### Adding to Wishlist
- **Product Pages**: Click "Add to Wishlist" button
- **Category Pages**: Click heart icon on product cards
- **Search Results**: Heart icon on each product

#### Wishlist Features
- **Save for Later**: Keep products for future purchase
- **Easy Access**: Wishlist icon in header shows count
- **Quick Actions**: 
  - View product details
  - Add to cart
  - Remove from wishlist
  - Add to comparison

#### Managing Your Wishlist
- **View All Items**: Click wishlist icon or visit `/wishlist.php`
- **Remove Items**: Click X button on unwanted items
- **Clear Wishlist**: Remove all items at once
- **Share Wishlist**: Send to friends/family (future feature)

### Product Reviews

#### Writing Reviews
1. **Purchase Required**: Only for products you've bought
2. **Access Review Form**: 
   - From order history
   - Product page (if purchased)
   - Direct link in order emails

3. **Review Components**:
   - **Star Rating**: 1-5 stars (required)
   - **Review Title**: Brief summary (required)
   - **Detailed Comment**: Your experience (required)

#### Review Guidelines
- **Be Honest**: Share genuine experiences
- **Be Helpful**: Focus on product features and quality
- **Be Respectful**: Avoid inappropriate language
- **Be Specific**: Mention specific pros and cons

#### Reading Reviews
- **Product Pages**: See all reviews for each product
- **Review Statistics**: Average rating and distribution
- **Helpful Reviews**: Most recent and relevant reviews
- **Review Filters**: Sort by rating, date, helpfulness

---

## 🔍 Product Comparison

### Using Comparison Feature

#### Adding Products to Compare
- **Product Pages**: Click "Compare" button
- **Category Pages**: Click comparison icon
- **Maximum**: Compare up to 4 products at once

#### Comparison Page
- **Side-by-Side View**: All products in table format
- **Specifications**: Compare features directly
- **Pricing**: See price differences
- **Actions**: Add to cart, view details, remove from comparison

#### Comparison Features
- **Product Images**: Visual comparison
- **Key Specifications**: Brand, model, category, price
- **Stock Status**: Availability for each product
- **Warranty Information**: Coverage comparison
- **Quick Actions**: Purchase or save to wishlist

---

## 📞 Customer Support

### Contact Methods

#### Contact Form
- **Location**: `/contact.php`
- **Information Required**:
  - Full name
  - Email address
  - Subject category
  - Detailed message
- **Response Time**: Within 24 hours

#### Support Categories
- **General Inquiry**: Basic questions
- **Product Support**: Product-specific help
- **Order Issue**: Problems with orders
- **Return/Refund**: Return requests
- **Technical Support**: Website issues
- **Partnership**: Business inquiries

#### Contact Information
- **Email**: support@electromart.com
- **Phone**: +1 (555) 123-4567
- **Hours**: Mon-Fri 9AM-6PM PST
- **Address**: 123 Tech Street, Silicon Valley, CA 94000

### Help Resources

#### Help Center
- **Location**: `/help.php`
- **Features**:
  - Searchable FAQ
  - Category-based help topics
  - Step-by-step guides
  - Video tutorials (future feature)

#### Policy Pages
- **Privacy Policy**: How we handle your data
- **Terms of Service**: Usage terms and conditions
- **Shipping Information**: Delivery details and costs
- **Returns Policy**: Return process and conditions
- **Warranty Information**: Product warranty details

### Order Support

#### Common Issues
- **Order Status**: Check order progress
- **Shipping Delays**: Tracking and updates
- **Product Issues**: Defects or damage
- **Payment Problems**: Billing questions
- **Returns**: Return process assistance

#### Self-Service Options
- **Order Tracking**: Real-time status updates
- **FAQ Section**: Common questions answered
- **Account Management**: Update information yourself
- **Reorder**: Quickly reorder previous purchases

---

## 🔧 Troubleshooting

### Common Issues & Solutions

#### Login Problems
**Issue**: Can't log in to account
**Solutions**:
1. Check username/email spelling
2. Verify password (case-sensitive)
3. Clear browser cookies and cache
4. Try different browser
5. Contact support for password reset

#### Cart Issues
**Issue**: Items not adding to cart
**Solutions**:
1. Ensure you're logged in
2. Check product is in stock
3. Refresh the page
4. Clear browser cache
5. Try different browser

#### Checkout Problems
**Issue**: Can't complete purchase
**Solutions**:
1. Verify all required fields filled
2. Check payment information
3. Ensure sufficient funds/credit
4. Try different payment method
5. Contact support if issue persists

#### Email Issues
**Issue**: Not receiving emails
**Solutions**:
1. Check spam/junk folder
2. Verify email address in profile
3. Add our domain to safe senders
4. Check email filters
5. Contact support to resend

#### Website Performance
**Issue**: Slow loading or errors
**Solutions**:
1. Check internet connection
2. Clear browser cache
3. Disable browser extensions
4. Try incognito/private mode
5. Use different browser or device

### Browser Compatibility

#### Supported Browsers
- **Chrome**: Version 80+
- **Firefox**: Version 75+
- **Safari**: Version 13+
- **Edge**: Version 80+
- **Mobile**: iOS Safari, Chrome Mobile

#### Recommended Settings
- **JavaScript**: Must be enabled
- **Cookies**: Must be enabled
- **Pop-ups**: Allow for our domain
- **Cache**: Clear regularly for best performance

### Mobile Usage

#### Mobile Features
- **Responsive Design**: Optimized for all screen sizes
- **Touch-Friendly**: Easy navigation on mobile
- **Fast Loading**: Optimized for mobile networks
- **Full Functionality**: All features available

#### Mobile Tips
- **Portrait Mode**: Best viewing experience
- **Zoom**: Pinch to zoom on product images
- **Navigation**: Use hamburger menu on small screens
- **Forms**: Use device keyboard for easy input

---

## 📊 Account Benefits

### Customer Levels

#### Bronze (Default)
- **Requirements**: New customers
- **Benefits**: 
  - Standard shipping rates
  - Basic customer support
  - Access to sales and promotions

#### Silver ($100+ spent)
- **Requirements**: $100+ total purchases
- **Benefits**:
  - Priority customer support
  - Exclusive member discounts
  - Early access to sales

#### Gold ($500+ spent)
- **Requirements**: $500+ total purchases
- **Benefits**:
  - Free shipping on all orders
  - Extended return period (45 days)
  - Birthday discounts
  - VIP customer support

#### VIP ($1000+ spent)
- **Requirements**: $1000+ total purchases
- **Benefits**:
  - Personal shopping assistant
  - Exclusive VIP products
  - Special event invitations
  - Premium support hotline

### Loyalty Benefits

#### Points System (Future Feature)
- Earn points on every purchase
- Redeem points for discounts
- Bonus points on reviews and referrals

#### Member Exclusive Features
- **Early Access**: New products first
- **Special Pricing**: Member-only discounts
- **Free Services**: Extended warranties, premium support

---

## 📱 Newsletter & Updates

### Newsletter Subscription

#### How to Subscribe
- **Footer Form**: Enter email in footer
- **Account Settings**: Enable in profile
- **Checkout**: Opt-in during purchase

#### Newsletter Content
- **New Products**: Latest arrivals
- **Sales & Promotions**: Exclusive discounts
- **Tech News**: Industry updates
- **Tips & Guides**: Product usage tips

#### Managing Subscription
- **Unsubscribe**: Link in every email
- **Update Preferences**: Choose content types
- **Frequency**: Weekly or monthly options

---

## 🎯 Tips for Best Experience

### Shopping Tips
1. **Create Account**: Save time on future purchases
2. **Use Wishlist**: Save items for later consideration
3. **Read Reviews**: Learn from other customers
4. **Compare Products**: Make informed decisions
5. **Check Stock**: Popular items sell quickly

### Security Tips
1. **Strong Password**: Use unique, complex passwords
2. **Secure Connection**: Look for HTTPS in URL
3. **Log Out**: Always log out on shared computers
4. **Monitor Account**: Check order history regularly
5. **Report Issues**: Contact support for suspicious activity

### Performance Tips
1. **Clear Cache**: Regularly clear browser cache
2. **Update Browser**: Use latest browser version
3. **Stable Connection**: Ensure good internet connection
4. **Close Tabs**: Reduce browser memory usage
5. **Restart Browser**: If experiencing issues

---

**Happy Shopping! 🛒**

Thank you for choosing ElectroMart. We're committed to providing you with the best online shopping experience. If you need any assistance, our customer support team is always ready to help!