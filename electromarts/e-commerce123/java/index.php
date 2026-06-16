<?php
require_once 'includes/config.php';
$page_title = 'Home';
include 'includes/header.php';
?>

<div class="container">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Welcome to ElectroMart</h1>
            <p>Discover the latest electronics, gadgets, and tech accessories at unbeatable prices. Quality products with fast shipping and excellent customer service.</p>
            <a href="#featured-products" class="btn">Shop Now</a>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="categories-section">
        <div class="section-title">
            <h2>Shop by Category</h2>
            <p>Explore our wide range of electronic products</p>
        </div>
        
        <div class="categories-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM categories ORDER BY name LIMIT 6");
            $category_icons = [
                'Smartphones' => 'fas fa-mobile-alt',
                'Laptops' => 'fas fa-laptop',
                'Tablets' => 'fas fa-tablet-alt',
                'Audio' => 'fas fa-headphones',
                'Gaming' => 'fas fa-gamepad',
                'Accessories' => 'fas fa-plug'
            ];
            
            while ($category = $stmt->fetch()) {
                $icon = $category_icons[$category['name']] ?? 'fas fa-microchip';
                echo "
                <a href='category.php?id={$category['id']}' class='category-card'>
                    <i class='{$icon}'></i>
                    <h3>{$category['name']}</h3>
                    <p>{$category['description']}</p>
                </a>";
            }
            ?>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured-products" class="featured-products">
        <div class="section-title">
            <h2>Featured Products</h2>
            <p>Check out our most popular electronics</p>
        </div>
        
        <div class="product-grid">
            <?php
            // Specific products in the order you want
            $featured_products = [
                'iPhone 17',
                'iPhone 17 Plus', 
                'Samsung Galaxy S24',
                'Samsung Galaxy S25',
                'Samsung Galaxy S25+',
                'Dell XPS 13',
                'MacBook Pro 14',
                'PlayStation 5',
                'SteelSeries Apex Pro'
            ];
            
            // Build the query to get products in specific order
            $product_names = "'" . implode("','", array_map('addslashes', $featured_products)) . "'";
            $stmt = $pdo->query("
                SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' AND p.name IN ({$product_names})
                ORDER BY FIELD(p.name, {$product_names})
                LIMIT 9
            ");
            
            while ($product = $stmt->fetch()) {
                // Fix image path - remove 'products/' if it exists in database
                $image_name = str_replace('products/', '', $product['image']);
                $image = $image_name ? "images/products/{$image_name}" : "images/placeholder.jpg";
                echo "
                <div class='product-card'>
                    <div class='product-image'>
                        <img src='{$image}' alt='{$product['name']}' onerror=\"this.src='images/placeholder.jpg'\">
                        " . ($product['stock_quantity'] < 10 ? "<span class='product-badge'>Low Stock</span>" : "") . "
                    </div>
                    <div class='product-info'>
                        <div class='product-brand'>{$product['brand']}</div>
                        <div class='product-name'>{$product['name']}</div>
                        <div class='product-price'>" . formatPrice($product['price']) . "</div>
                        <div class='product-actions'>
                            <a href='product.php?id={$product['id']}' class='btn btn-small btn-primary'>View Details</a>
                            <button onclick='addToCart({$product['id']})' class='btn btn-small'>Add to Cart</button>
                        </div>
                        " . (isLoggedIn() ? "<div style='margin-top: 0.5rem;'><button onclick='toggleWishlistFromGrid({$product['id']})' class='btn btn-small' style='width: 100%; background: white; color: #ff4757; border: 2px solid #ff4757;'><i class='fas fa-heart'></i> Add to Wishlist</button></div>" : "") . "
                    </div>
                </div>";
            }
            ?>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="features-section">
        <div class="section-title">
            <h2>Why Choose ElectroMart?</h2>
            <p>We're committed to providing the best shopping experience</p>
        </div>
        
        <div class="categories-grid">
            <div class="category-card">
                <i class="fas fa-shipping-fast"></i>
                <h3>Fast Shipping</h3>
                <p>Free shipping on orders over $50. Express delivery available.</p>
            </div>
            <div class="category-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Secure Shopping</h3>
                <p>Your data is protected with industry-standard encryption.</p>
            </div>
            <div class="category-card">
                <i class="fas fa-undo"></i>
                <h3>Easy Returns</h3>
                <p>30-day return policy. No questions asked.</p>
            </div>
            <div class="category-card">
                <i class="fas fa-headset"></i>
                <h3>24/7 Support</h3>
                <p>Our customer service team is always here to help.</p>
            </div>
        </div>
    </section>
</div>

<script>
// Add to cart functionality
function addToCart(productId) {
    <?php if (isLoggedIn()): ?>
    fetch('ajax/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            document.querySelector('.cart-count').textContent = data.cart_count;
            showNotification('Product added to cart!', 'success');
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        showNotification('Error adding to cart', 'error');
    });
    <?php else: ?>
    window.location.href = 'login.php';
    <?php endif; ?>
}

// Wishlist functionality for product grid
function toggleWishlistFromGrid(productId) {
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
            showNotification('Added to wishlist!', 'success');
            // Update wishlist count if element exists
            const wishlistCount = document.querySelector('.wishlist-count');
            if (wishlistCount) {
                wishlistCount.textContent = data.wishlist_count;
            }
        } else {
            showNotification(data.message || 'Error adding to wishlist', 'error');
        }
    })
    .catch(error => {
        showNotification('Error adding to wishlist', 'error');
    });
}

// Show notification
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'error'}`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>

<?php include 'includes/footer.php'; ?>