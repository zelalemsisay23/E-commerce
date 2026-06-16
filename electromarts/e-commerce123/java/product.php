<?php
require_once 'includes/config.php';

$product_id = (int)($_GET['id'] ?? 0);

if (!$product_id) {
    header('Location: index.php');
    exit();
}

// Get product details
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ? AND p.status = 'active'
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit();
}

$page_title = $product['name'];
include 'includes/header.php';
?>

<div class="container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
        <!-- Product Image -->
        <div class="product-image-section">
            <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <?php 
                $image = $product['image'] ? "images/products/{$product['image']}" : "images/placeholder.jpg";
                ?>
                <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     style="width: 100%; height: 400px; object-fit: cover; border-radius: 5px;"
                     onerror="this.src='images/placeholder.jpg'">
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="product-details">
            <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="color: #666; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($product['brand']); ?></div>
                <h1 style="font-size: 2rem; margin-bottom: 1rem; color: #333;"><?php echo htmlspecialchars($product['name']); ?></h1>
                <div style="font-size: 2rem; font-weight: bold; color: #667eea; margin-bottom: 1rem;">
                    <?php echo formatPrice($product['price']); ?>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <p style="color: #666; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                
                <!-- Product Specifications -->
                <div style="margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1rem; color: #333;">Specifications</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand']); ?></div>
                        <div><strong>Model:</strong> <?php echo htmlspecialchars($product['model']); ?></div>
                        <div><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></div>
                        <div><strong>Warranty:</strong> <?php echo htmlspecialchars($product['warranty']); ?></div>
                    </div>
                </div>
                
                <!-- Stock Status -->
                <div style="margin-bottom: 2rem;">
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <div style="color: #28a745; font-weight: bold;">
                            <i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock_quantity']; ?> available)
                        </div>
                    <?php else: ?>
                        <div style="color: #dc3545; font-weight: bold;">
                            <i class="fas fa-times-circle"></i> Out of Stock
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Add to Cart -->
                <?php if ($product['stock_quantity'] > 0): ?>
                    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                        <div class="quantity-controls" style="display: flex; align-items: center; gap: 0.5rem;">
                            <label for="quantity" style="font-weight: bold;">Quantity:</label>
                            <button type="button" class="qty-minus" style="width: 35px; height: 35px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 3px;">-</button>
                            <input type="number" id="quantity" class="qty-input" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                   style="width: 60px; text-align: center; border: 1px solid #ddd; padding: 0.5rem; border-radius: 3px;">
                            <button type="button" class="qty-plus" style="width: 35px; height: 35px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 3px;">+</button>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 1rem;">
                        <button onclick="addToCartFromProduct()" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button onclick="buyNow()" class="btn" style="flex: 1;">
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    </div>
                    
                    <?php if (isLoggedIn()): ?>
                        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                            <button onclick="toggleWishlist(<?php echo $product_id; ?>)" class="btn" style="flex: 1; background: white; color: #ff4757; border: 2px solid #ff4757;">
                                <i class="fas fa-heart"></i> Add to Wishlist
                            </button>
                            <button onclick="addToComparison(<?php echo $product_id; ?>)" class="btn" style="flex: 1; background: white; color: #667eea; border: 2px solid #667eea;">
                                <i class="fas fa-balance-scale"></i> Compare
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Product Reviews Section -->
    <section class="product-reviews" style="margin-bottom: 3rem;">
        <div class="section-title">
            <h2>Customer Reviews</h2>
        </div>
        
        <?php
        // Get review statistics
        $stmt = $pdo->prepare("
            SELECT 
                COUNT(*) as total_reviews,
                AVG(rating) as avg_rating
            FROM reviews 
            WHERE product_id = ?
        ");
        $stmt->execute([$product_id]);
        $review_stats = $stmt->fetch();
        
        // Get recent reviews
        $stmt = $pdo->prepare("
            SELECT r.*, u.full_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ? 
            ORDER BY r.created_at DESC 
            LIMIT 3
        ");
        $stmt->execute([$product_id]);
        $recent_reviews = $stmt->fetchAll();
        ?>
        
        <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <?php if ($review_stats['total_reviews'] > 0): ?>
                <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; font-weight: bold; color: #667eea;">
                            <?php echo number_format($review_stats['avg_rating'], 1); ?>
                        </div>
                        <div class="star-rating" style="font-size: 1.2rem; margin: 0.5rem 0;">
                            <?php
                            $avg_rating = $review_stats['avg_rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $avg_rating) {
                                    echo '<i class="fas fa-star" style="color: #ffd700;"></i>';
                                } elseif ($i - 0.5 <= $avg_rating) {
                                    echo '<i class="fas fa-star-half-alt" style="color: #ffd700;"></i>';
                                } else {
                                    echo '<i class="far fa-star" style="color: #ddd;"></i>';
                                }
                            }
                            ?>
                        </div>
                        <div style="color: #666; font-size: 0.9rem;">
                            <?php echo $review_stats['total_reviews']; ?> review<?php echo $review_stats['total_reviews'] != 1 ? 's' : ''; ?>
                        </div>
                    </div>
                    
                    <div style="flex: 1;">
                        <?php if (!empty($recent_reviews)): ?>
                            <?php foreach ($recent_reviews as $review): ?>
                                <div style="border-bottom: 1px solid #eee; padding: 1rem 0;">
                                    <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 0.5rem;">
                                        <div style="font-weight: bold;"><?php echo htmlspecialchars($review['full_name']); ?></div>
                                        <div class="star-rating" style="margin-left: auto;">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="<?php echo $i <= $review['rating'] ? 'fas' : 'far'; ?> fa-star" 
                                                   style="color: <?php echo $i <= $review['rating'] ? '#ffd700' : '#ddd'; ?>; font-size: 0.8rem;"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div style="font-weight: bold; font-size: 0.9rem; margin-bottom: 0.25rem;">
                                        <?php echo htmlspecialchars($review['title']); ?>
                                    </div>
                                    <div style="color: #666; font-size: 0.9rem;">
                                        <?php echo htmlspecialchars(substr($review['comment'], 0, 100)) . (strlen($review['comment']) > 100 ? '...' : ''); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div style="text-align: center;">
                    <a href="reviews.php?product_id=<?php echo $product_id; ?>" class="btn">
                        View All Reviews (<?php echo $review_stats['total_reviews']; ?>)
                    </a>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 2rem; color: #666;">
                    <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; color: #ddd;"></i>
                    <h4>No Reviews Yet</h4>
                    <p>Be the first to review this product!</p>
                    <?php if (isLoggedIn()): ?>
                        <a href="reviews.php?product_id=<?php echo $product_id; ?>" class="btn btn-primary">Write a Review</a>
                    <?php else: ?>
                        <a href="login.php?redirect=<?php echo urlencode('reviews.php?product_id=' . $product_id); ?>" class="btn btn-primary">Login to Review</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Related Products -->
    <section class="related-products">
        <div class="section-title">
            <h2>Related Products</h2>
            <p>You might also like these products</p>
        </div>
        
        <div class="product-grid">
            <?php
            $stmt = $pdo->prepare("
                SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = ? AND p.id != ? AND p.status = 'active' 
                ORDER BY RAND() 
                LIMIT 4
            ");
            $stmt->execute([$product['category_id'], $product_id]);
            
            while ($related = $stmt->fetch()) {
                $image = $related['image'] ? "images/products/{$related['image']}" : "images/placeholder.jpg";
                echo "
                <div class='product-card'>
                    <div class='product-image'>
                        <img src='{$image}' alt='{$related['name']}' onerror=\"this.src='images/placeholder.jpg'\">
                    </div>
                    <div class='product-info'>
                        <div class='product-brand'>{$related['brand']}</div>
                        <div class='product-name'>{$related['name']}</div>
                        <div class='product-price'>" . formatPrice($related['price']) . "</div>
                        <div class='product-actions'>
                            <a href='product.php?id={$related['id']}' class='btn btn-small btn-primary'>View Details</a>
                            <button onclick='addToCart({$related['id']})' class='btn btn-small'>Add to Cart</button>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </section>
</div>

<script>
// Quantity controls
document.addEventListener('DOMContentLoaded', function() {
    const minusBtn = document.querySelector('.qty-minus');
    const plusBtn = document.querySelector('.qty-plus');
    const input = document.querySelector('.qty-input');
    const maxStock = <?php echo $product['stock_quantity']; ?>;
    
    if (minusBtn && plusBtn && input) {
        minusBtn.addEventListener('click', () => {
            const currentValue = parseInt(input.value) || 1;
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        });
        
        plusBtn.addEventListener('click', () => {
            const currentValue = parseInt(input.value) || 1;
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
            }
        });
        
        input.addEventListener('change', () => {
            const value = parseInt(input.value) || 1;
            if (value < 1) input.value = 1;
            if (value > maxStock) input.value = maxStock;
        });
    }
});

// Add to cart from product page
function addToCartFromProduct() {
    <?php if (isLoggedIn()): ?>
    const quantity = parseInt(document.querySelector('.qty-input').value) || 1;
    addToCart(<?php echo $product_id; ?>, quantity);
    <?php else: ?>
    window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
    <?php endif; ?>
}

// Buy now function
function buyNow() {
    <?php if (isLoggedIn()): ?>
    const quantity = parseInt(document.querySelector('.qty-input').value) || 1;
    
    // Add to cart first
    fetch('ajax/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: <?php echo $product_id; ?>,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to checkout
            window.location.href = 'checkout.php';
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        showNotification('Error processing request', 'error');
    });
    <?php else: ?>
    window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
    <?php endif; ?>
}

// Wishlist functionality
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
            const btn = event.target.closest('button');
            if (data.action === 'added') {
                btn.innerHTML = '<i class="fas fa-heart"></i> Remove from Wishlist';
                btn.style.background = '#ff4757';
                btn.style.color = 'white';
                btn.style.border = '2px solid #ff4757';
                showNotification('Added to wishlist!', 'success');
            } else {
                btn.innerHTML = '<i class="fas fa-heart"></i> Add to Wishlist';
                btn.style.background = 'white';
                btn.style.color = '#ff4757';
                btn.style.border = '2px solid #ff4757';
                showNotification('Removed from wishlist', 'success');
            }
            
            // Update wishlist count in header
            const wishlistCount = document.querySelector('.wishlist-count');
            if (wishlistCount) {
                wishlistCount.textContent = data.wishlist_count;
            }
        } else {
            showNotification(data.message || 'Error updating wishlist', 'error');
        }
    })
    .catch(error => {
        showNotification('Error updating wishlist', 'error');
    });
}

// Comparison functionality
function addToComparison(productId) {
    fetch('ajax/add_to_comparison.php', {
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
            showNotification('Added to comparison!', 'success');
        } else {
            showNotification(data.message || 'Error adding to comparison', 'error');
        }
    })
    .catch(error => {
        showNotification('Error adding to comparison', 'error');
    });
}
</script>

<style>
@media (max-width: 768px) {
    .container > div:first-child {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>