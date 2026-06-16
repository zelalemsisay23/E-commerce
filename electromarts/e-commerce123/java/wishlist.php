<?php
require_once 'includes/config.php';
requireLogin();

$page_title = 'My Wishlist';
include 'includes/header.php';

// Get wishlist items
$stmt = $pdo->prepare("
    SELECT w.*, p.name, p.price, p.image, p.stock_quantity, p.brand, p.id as product_id
    FROM wishlist w 
    JOIN products p ON w.product_id = p.id 
    WHERE w.user_id = ? AND p.status = 'active'
    ORDER BY w.added_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$wishlist_items = $stmt->fetchAll();
?>

<div class="container">
    <h1 style="margin-bottom: 2rem;">My Wishlist</h1>
    
    <?php if (empty($wishlist_items)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-heart" style="font-size: 4rem; color: #ff4757; margin-bottom: 1rem;"></i>
            <h2 style="color: #666; margin-bottom: 1rem;">Your wishlist is empty</h2>
            <p style="color: #999; margin-bottom: 2rem;">Save products you love for later!</p>
            <a href="index.php" class="btn btn-primary">Browse Products</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($wishlist_items as $item): ?>
                <?php $image = $item['image'] ? "images/products/{$item['image']}" : "images/placeholder.jpg"; ?>
                <div class="product-card" id="wishlist-item-<?php echo $item['product_id']; ?>">
                    <div class="product-image">
                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             onerror="this.src='images/placeholder.jpg'">
                        <button onclick="removeFromWishlist(<?php echo $item['product_id']; ?>)" 
                                class="wishlist-remove-btn" title="Remove from wishlist">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="product-info">
                        <div class="product-brand"><?php echo htmlspecialchars($item['brand']); ?></div>
                        <div class="product-name"><?php echo htmlspecialchars($item['name']); ?></div>
                        <div class="product-price"><?php echo formatPrice($item['price']); ?></div>
                        
                        <div style="margin: 1rem 0;">
                            <?php if ($item['stock_quantity'] > 0): ?>
                                <span style="color: #28a745; font-size: 0.9rem;">
                                    <i class="fas fa-check-circle"></i> In Stock
                                </span>
                            <?php else: ?>
                                <span style="color: #dc3545; font-size: 0.9rem;">
                                    <i class="fas fa-times-circle"></i> Out of Stock
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-actions">
                            <a href="product.php?id=<?php echo $item['product_id']; ?>" class="btn btn-small btn-primary">View Details</a>
                            <?php if ($item['stock_quantity'] > 0): ?>
                                <button onclick="addToCart(<?php echo $item['product_id']; ?>)" class="btn btn-small">Add to Cart</button>
                            <?php else: ?>
                                <button class="btn btn-small" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </div>
                        
                        <div style="margin-top: 0.5rem;">
                            <button onclick="addToComparison(<?php echo $item['product_id']; ?>)" 
                                    class="btn btn-small" style="width: 100%; background: white; color: #667eea; border: 2px solid #667eea;">
                                <i class="fas fa-balance-scale"></i> Compare
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <button onclick="clearWishlist()" class="btn" style="background: #dc3545; color: white; margin-right: 1rem;">
                <i class="fas fa-trash"></i> Clear Wishlist
            </button>
            <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

<script>
function removeFromWishlist(productId) {
    if (confirm('Remove this item from your wishlist?')) {
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
                // Remove the item from the page
                const item = document.getElementById(`wishlist-item-${productId}`);
                if (item) {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.remove();
                        // Check if wishlist is empty
                        if (document.querySelectorAll('.product-card').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
                
                // Update wishlist count in header
                const wishlistCount = document.querySelector('.wishlist-count');
                if (wishlistCount) {
                    wishlistCount.textContent = data.wishlist_count;
                }
                
                showNotification('Removed from wishlist', 'success');
            } else {
                showNotification(data.message || 'Error removing from wishlist', 'error');
            }
        })
        .catch(error => {
            showNotification('Error removing from wishlist', 'error');
        });
    }
}

function clearWishlist() {
    if (confirm('Are you sure you want to clear your entire wishlist?')) {
        const productIds = Array.from(document.querySelectorAll('.product-card')).map(card => {
            return card.id.replace('wishlist-item-', '');
        });
        
        Promise.all(productIds.map(id => 
            fetch('ajax/add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: parseInt(id)
                })
            })
        )).then(() => {
            location.reload();
        });
    }
}

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
.product-card {
    position: relative;
    transition: opacity 0.3s, transform 0.3s;
}

.wishlist-remove-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 71, 87, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    z-index: 10;
}

.product-card:hover .wishlist-remove-btn {
    opacity: 1;
}

.wishlist-remove-btn:hover {
    background: #ff4757;
    transform: scale(1.1);
}
</style>

<?php include 'includes/footer.php'; ?>