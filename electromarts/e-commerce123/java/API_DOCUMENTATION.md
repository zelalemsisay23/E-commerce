# ElectroMart API Documentation

## 📡 AJAX API Endpoints

This document describes all AJAX endpoints available in the ElectroMart e-commerce platform.

---

## 🛒 Cart Operations

### Add to Cart
**Endpoint**: `POST /ajax/add_to_cart.php`

**Description**: Adds a product to the user's shopping cart

**Authentication**: Required (User must be logged in)

**Request Body**:
```json
{
    "product_id": 123,
    "quantity": 2
}
```

**Response**:
```json
{
    "success": true,
    "message": "Product added to cart successfully",
    "cart_count": 5,
    "cart_total": 299.99
}
```

**Error Response**:
```json
{
    "success": false,
    "message": "Product not found or out of stock"
}
```

### Update Cart Item
**Endpoint**: `POST /ajax/update_cart.php`

**Description**: Updates the quantity of an item in the cart

**Authentication**: Required

**Request Body**:
```json
{
    "product_id": 123,
    "quantity": 3
}
```

**Response**:
```json
{
    "success": true,
    "message": "Cart updated successfully",
    "cart_count": 6,
    "cart_total": 399.99,
    "item_total": 149.97
}
```

### Remove from Cart
**Endpoint**: `POST /ajax/remove_from_cart.php`

**Description**: Removes an item from the shopping cart

**Authentication**: Required

**Request Body**:
```json
{
    "product_id": 123
}
```

**Response**:
```json
{
    "success": true,
    "message": "Item removed from cart",
    "cart_count": 4,
    "cart_total": 250.02
}
```

### Get Cart Count
**Endpoint**: `GET /ajax/get_cart_count.php`

**Description**: Returns the current number of items in the cart

**Authentication**: Required

**Response**:
```json
{
    "success": true,
    "cart_count": 5
}
```

---

## ❤️ Wishlist Operations

### Add/Remove Wishlist Item
**Endpoint**: `POST /ajax/add_to_wishlist.php`

**Description**: Toggles a product in the user's wishlist (add if not present, remove if present)

**Authentication**: Required

**Request Body**:
```json
{
    "product_id": 123
}
```

**Response (Added)**:
```json
{
    "success": true,
    "message": "Added to wishlist!",
    "action": "added",
    "wishlist_count": 8
}
```

**Response (Removed)**:
```json
{
    "success": true,
    "message": "Removed from wishlist",
    "action": "removed",
    "wishlist_count": 7
}
```

---

## ⚖️ Product Comparison

### Add to Comparison
**Endpoint**: `POST /ajax/add_to_comparison.php`

**Description**: Adds a product to the comparison list (session-based)

**Authentication**: Not required

**Request Body**:
```json
{
    "product_id": 123
}
```

**Response**:
```json
{
    "success": true,
    "message": "Added to comparison!",
    "comparison_count": 3
}
```

**Error Response**:
```json
{
    "success": false,
    "message": "Maximum 4 products can be compared"
}
```

### Remove from Comparison
**Endpoint**: `POST /ajax/remove_from_comparison.php`

**Description**: Removes a product from the comparison list

**Authentication**: Not required

**Request Body**:
```json
{
    "product_id": 123
}
```

**Response**:
```json
{
    "success": true,
    "message": "Removed from comparison",
    "comparison_count": 2
}
```

---

## ⭐ Reviews System

### Add Product Review
**Endpoint**: `POST /ajax/add_review.php`

**Description**: Adds a review for a product

**Authentication**: Required

**Request Body**:
```json
{
    "product_id": 123,
    "rating": 5,
    "title": "Excellent product!",
    "comment": "This product exceeded my expectations. Great quality and fast shipping."
}
```

**Validation Rules**:
- `rating`: Integer between 1-5
- `title`: Required, max 200 characters
- `comment`: Required, max 1000 characters

**Response**:
```json
{
    "success": true,
    "message": "Review submitted successfully!",
    "review_id": 456
}
```

**Error Response**:
```json
{
    "success": false,
    "message": "You have already reviewed this product"
}
```

---

## 📧 Newsletter System

### Subscribe to Newsletter
**Endpoint**: `POST /ajax/newsletter_subscribe.php`

**Description**: Subscribes an email address to the newsletter

**Authentication**: Not required

**Request Body**:
```json
{
    "email": "user@example.com"
}
```

**Response**:
```json
{
    "success": true,
    "message": "Thank you for subscribing! You will receive our latest updates and offers."
}
```

**Error Responses**:
```json
{
    "success": false,
    "message": "You are already subscribed to our newsletter"
}
```

```json
{
    "success": false,
    "message": "Please enter a valid email address"
}
```

---

## 🎫 Coupon System

### Apply Coupon
**Endpoint**: `POST /ajax/apply_coupon.php`

**Description**: Applies a discount coupon to the cart

**Authentication**: Required

**Request Body**:
```json
{
    "coupon_code": "WELCOME10"
}
```

**Response**:
```json
{
    "success": true,
    "message": "Coupon applied successfully!",
    "discount_amount": 29.99,
    "coupon_code": "WELCOME10",
    "new_total": 269.99
}
```

**Error Responses**:
```json
{
    "success": false,
    "message": "Invalid coupon code"
}
```

```json
{
    "success": false,
    "message": "Coupon has expired"
}
```

```json
{
    "success": false,
    "message": "Minimum order amount not met"
}
```

### Remove Coupon
**Endpoint**: `POST /ajax/remove_coupon.php`

**Description**: Removes the applied coupon from the cart

**Authentication**: Required

**Request Body**: Empty or `{}`

**Response**:
```json
{
    "success": true,
    "message": "Coupon removed",
    "new_total": 299.99
}
```

---

## 🔍 Search API

### Product Search
**Endpoint**: `GET /search.php`

**Description**: Searches for products (not AJAX, but API-like)

**Parameters**:
- `q`: Search query (string)
- `category`: Category ID (integer)
- `min_price`: Minimum price (float)
- `max_price`: Maximum price (float)
- `sort`: Sort order (name, price_low, price_high, newest)

**Example**:
```
GET /search.php?q=iphone&category=1&min_price=500&max_price=1000&sort=price_low
```

---

## 📊 Analytics Endpoints (Admin)

### Get Dashboard Stats
**Endpoint**: `GET /admin/ajax/dashboard_stats.php`

**Description**: Returns dashboard statistics for admin panel

**Authentication**: Admin required

**Response**:
```json
{
    "success": true,
    "data": {
        "total_orders": 156,
        "total_revenue": 45678.90,
        "total_users": 234,
        "total_products": 89,
        "pending_orders": 12,
        "low_stock_products": 5
    }
}
```

---

## 🔒 Authentication

### Session-Based Authentication
Most endpoints require user authentication through PHP sessions:

```php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in']);
    exit();
}
```

### Admin Authentication
Admin endpoints require admin session:

```php
// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Admin access required']);
    exit();
}
```

---

## 📝 Request/Response Format

### Request Headers
```
Content-Type: application/json
X-Requested-With: XMLHttpRequest (for AJAX detection)
```

### Standard Response Format
All AJAX endpoints follow this response structure:

```json
{
    "success": boolean,
    "message": "Human readable message",
    "data": {
        // Additional response data
    }
}
```

### Error Handling
```json
{
    "success": false,
    "message": "Error description",
    "error_code": "OPTIONAL_ERROR_CODE"
}
```

---

## 🚀 JavaScript Usage Examples

### Adding to Cart
```javascript
function addToCart(productId, quantity = 1) {
    fetch('ajax/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count in UI
            document.querySelector('.cart-count').textContent = data.cart_count;
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Error adding to cart', 'error');
    });
}
```

### Newsletter Subscription
```javascript
function subscribeNewsletter(email) {
    fetch('ajax/newsletter_subscribe.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('newsletter-message');
        if (data.success) {
            messageDiv.innerHTML = '<span style="color: #28a745;">' + data.message + '</span>';
            document.getElementById('newsletter-email').value = '';
        } else {
            messageDiv.innerHTML = '<span style="color: #dc3545;">' + data.message + '</span>';
        }
    });
}
```

### Wishlist Toggle
```javascript
function toggleWishlist(productId) {
    fetch('ajax/add_to_wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update wishlist count
            const wishlistCount = document.querySelector('.wishlist-count');
            if (wishlistCount) {
                wishlistCount.textContent = data.wishlist_count;
            }
            
            // Update button state
            const button = event.target.closest('button');
            if (data.action === 'added') {
                button.innerHTML = '<i class="fas fa-heart"></i> Remove from Wishlist';
                button.style.background = '#ff4757';
            } else {
                button.innerHTML = '<i class="fas fa-heart"></i> Add to Wishlist';
                button.style.background = 'white';
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    });
}
```

---

## 🔧 Rate Limiting

### Current Implementation
- No rate limiting implemented
- Recommended for production: 100 requests per minute per IP

### Future Implementation
```php
// Example rate limiting code
$redis = new Redis();
$key = 'rate_limit:' . $_SERVER['REMOTE_ADDR'];
$requests = $redis->incr($key);
if ($requests === 1) {
    $redis->expire($key, 60); // 1 minute window
}
if ($requests > 100) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Rate limit exceeded']);
    exit();
}
```

---

## 📈 Performance Considerations

### Optimization Tips
1. **Database Indexing**: Ensure proper indexes on frequently queried columns
2. **Caching**: Implement Redis/Memcached for session data
3. **Connection Pooling**: Use persistent database connections
4. **Response Compression**: Enable GZIP compression
5. **CDN**: Use CDN for static assets

### Monitoring
- Monitor response times
- Track error rates
- Log slow queries
- Monitor memory usage

---

## 🧪 Testing

### Manual Testing
Use browser developer tools or Postman to test endpoints:

```bash
# Example using curl
curl -X POST http://localhost/java/ajax/add_to_cart.php \
  -H "Content-Type: application/json" \
  -d '{"product_id": 1, "quantity": 2}' \
  --cookie "PHPSESSID=your_session_id"
```

### Automated Testing
Consider implementing PHPUnit tests for API endpoints:

```php
public function testAddToCart() {
    $response = $this->post('/ajax/add_to_cart.php', [
        'product_id' => 1,
        'quantity' => 2
    ]);
    
    $this->assertTrue($response['success']);
    $this->assertEquals('Product added to cart successfully', $response['message']);
}
```

---

**API Documentation Complete! 🎉**

This covers all AJAX endpoints and API functionality in the ElectroMart platform.