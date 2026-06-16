<?php
require_once 'includes/config.php';

$compare_products = [];
if (isset($_SESSION['compare_products']) && !empty($_SESSION['compare_products'])) {
    $product_ids = implode(',', array_map('intval', $_SESSION['compare_products']));
    $stmt = $pdo->query("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.id IN ({$product_ids}) AND p.status = 'active'
    ");
    $compare_products = $stmt->fetchAll();
}

$page_title = 'Compare Products';
include 'includes/header.php';
?>

<div class="container">
    <h1 style="margin-bottom: 2rem;">Compare Products</h1>
    
    <?php if (empty($compare_products)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <i class="fas fa-balance-scale" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
            <h2 style="color: #666; margin-bottom: 1rem;">No products to compare</h2>
            <p style="color: #999; margin-bottom: 2rem;">Add products to comparison from product pages to see detailed comparisons.</p>
            <a href="index.php" class="btn btn-primary">Browse Products</a>
        </div>
    <?php else: ?>
        <div style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="overflow-x: auto;">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th style="width: 200px;">Specification</th>
                            <?php foreach ($compare_products as $product): ?>
                                <th style="min-width: 250px;">
                                    <div style="text-align: center; padding: 1rem;">
                                        <?php $image = $product['image'] ? "images/products/{$product['image']}" : "images/placeholder.jpg"; ?>
                                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; margin-bottom: 1rem;"
                                             onerror="this.src='images/placeholder.jpg'">
                                        <div style="font-weight: bold; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($product['name']); ?></div>
                                        <div style="color: #667eea; font-size: 1.2rem; font-weight: bold;"><?php echo formatPrice($product['price']); ?></div>
                                        <button onclick="removeFromComparison(<?php echo $product['id']; ?>)" 
                                                style="background: #dc3545; color: white; border: none; padding: 0.25rem 0.5rem; border-radius: 3px; margin-top: 0.5rem; cursor: pointer;">
                                            Remove
                                        </button>
                                    </div>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Brand</strong></td>
                            <?php foreach ($compare_products as $product): ?>
                                <td><?php echo htmlspecialchars($product['brand']); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        
                        <tr>
                            <td><strong>Model</strong></td>
                            <?php foreach ($compare_products as $product): ?>
                                <td><?php echo htmlspecialchars($product['model']); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        
                        <tr>
                            <td><strong>Category</strong></td>
                            <?php foreach ($compare_products as $product): ?>
                                <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        
                        <tr>
                            <td><strong>Price</strong></td>
                            <?php foreach ($compare_products as $product): ?>
                                <td style="font-weight: bold; color: #667eea; font-size: 1.1rem;"><?php echo formatPrice($product['price']); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        
                        <tr>
                            <td><strong>Warranty</strong></td>
                            <?php foreach ($compare_products as $product): ?>
                                <td><?php echo htmlspecialchars($product['warranty']); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        
                        <tr>
                            <td><strong>Stock Status</strong></td>
                            <?php foreach ($compare_products as $product): ?>
                                <td>
                                    <?php if ($product['stock_quantity'] > 0): ?>
                                        <span style="color: #28a745; font-weight: bold;">
                                            <i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock_quantity']; ?>)
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #dc3545; font-weight: bold;">
                                            <i class="fas fa-times-circle"></i> Out of Stock
                                        </span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        
                        <tr>
                            <td><strong>Description</strong></td>
                            <?php foreach ($compare_products as $product): ?>
                                <td style="font-size: 0.9rem; line-height: 1.4;">
                                    <?php echo htmlspecialchars(substr($product['description'], 0, 150)) . '...'; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        
                        <tr>
                            <td><strong>Actions</strong></td>
                            <?php foreach ($compare_products as $product): ?>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-small btn-primary">View Details</a>
                                        <?php if ($product['stock_quantity'] > 0): ?>
                                            <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-small">Add to Cart</button>
                                        <?php else: ?>
                                            <button class="btn btn-small" disabled>Out of Stock</button>
                                        <?php endif; ?>
                                        <?php if (isLoggedIn()): ?>
                                            <button onclick="addToWishlist(<?php echo $product['id']; ?>)" class="btn btn-small" style="background: white; color: #ff4757; border: 2px solid #ff4757;">
                                                <i class="fas fa-heart"></i> Wishlist
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="?clear=1" class="btn" style="background: #dc3545; color: white;">Clear All Comparisons</a>
            <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

<script>
function removeFromComparison(productId) {
    fetch('ajax/remove_from_comparison.php', {
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
            location.reload();
        } else {
            showNotification(data.message || 'Error removing from comparison', 'error');
        }
    })
    .catch(error => {
        showNotification('Error removing from comparison', 'error');
    });
}

function addToWishlist(productId) {
    <?php if (isLoggedIn()): ?>
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
        } else {
            showNotification(data.message || 'Error adding to wishlist', 'error');
        }
    })
    .catch(error => {
        showNotification('Error adding to wishlist', 'error');
    });
    <?php else: ?>
    window.location.href = 'login.php';
    <?php endif; ?>
}
</script>

<style>
.comparison-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.comparison-table th,
.comparison-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
    vertical-align: top;
}

.comparison-table th {
    background: #f8f9fa;
    font-weight: bold;
    color: #333;
    position: sticky;
    top: 0;
    z-index: 10;
}

.comparison-table tr:nth-child(even) {
    background: #f9f9f9;
}

.comparison-table tr:hover {
    background: #f0f8ff;
}

.comparison-table td:first-child {
    background: #f8f9fa;
    font-weight: bold;
    position: sticky;
    left: 0;
    z-index: 5;
}

@media (max-width: 768px) {
    .comparison-table th,
    .comparison-table td {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    
    .comparison-table th {
        min-width: 200px;
    }
}
</style>

<?php
// Handle clear comparison
if (isset($_GET['clear'])) {
    unset($_SESSION['compare_products']);
    header('Location: compare.php');
    exit();
}

include 'includes/footer.php';
?>