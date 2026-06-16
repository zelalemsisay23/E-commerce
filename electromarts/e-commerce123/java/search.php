<?php
require_once 'includes/config.php';

$query = sanitize($_GET['q'] ?? '');
$category_id = (int)($_GET['category'] ?? 0);
$min_price = (float)($_GET['min_price'] ?? 0);
$max_price = (float)($_GET['max_price'] ?? 0);
$sort = sanitize($_GET['sort'] ?? 'name');

$page_title = $query ? "Search Results for '{$query}'" : 'Search Products';
include 'includes/header.php';

// Build search query
$sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'active'";
$params = [];

if ($query) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ? OR p.model LIKE ?)";
    $search_term = "%{$query}%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
}

if ($category_id) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_id;
}

if ($min_price > 0) {
    $sql .= " AND p.price >= ?";
    $params[] = $min_price;
}

if ($max_price > 0) {
    $sql .= " AND p.price <= ?";
    $params[] = $max_price;
}

// Add sorting
switch ($sort) {
    case 'price_low':
        $sql .= " ORDER BY p.price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY p.price DESC";
        break;
    case 'newest':
        $sql .= " ORDER BY p.created_at DESC";
        break;
    default:
        $sql .= " ORDER BY p.name ASC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<div class="container">
    <!-- Search Header -->
    <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <h1 style="margin-bottom: 1rem;">
            <?php if ($query): ?>
                Search Results for "<?php echo htmlspecialchars($query); ?>"
            <?php else: ?>
                All Products
            <?php endif; ?>
        </h1>
        <p style="color: #666; margin-bottom: 2rem;">Found <?php echo count($products); ?> products</p>
        
        <!-- Advanced Search Form -->
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="q">Search Products</label>
                <input type="text" id="q" name="q" placeholder="Enter product name, brand, or model..." 
                       value="<?php echo htmlspecialchars($query); ?>">
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All Categories</option>
                    <?php
                    $cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
                    while ($category = $cat_stmt->fetch()) {
                        $selected = $category_id == $category['id'] ? 'selected' : '';
                        echo "<option value='{$category['id']}' {$selected}>{$category['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label for="min_price">Min Price</label>
                <input type="number" id="min_price" name="min_price" placeholder="0" step="0.01" 
                       value="<?php echo $min_price > 0 ? $min_price : ''; ?>">
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label for="max_price">Max Price</label>
                <input type="number" id="max_price" name="max_price" placeholder="No limit" step="0.01" 
                       value="<?php echo $max_price > 0 ? $max_price : ''; ?>">
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label for="sort">Sort By</label>
                <select id="sort" name="sort">
                    <option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                    <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="search.php" class="btn">Clear</a>
            </div>
        </form>
    </div>
    
    <!-- Search Results -->
    <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-search" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
            <h2 style="color: #666; margin-bottom: 1rem;">No products found</h2>
            <p style="color: #999; margin-bottom: 2rem;">Try adjusting your search criteria or browse our categories.</p>
            <a href="index.php" class="btn btn-primary">Browse All Products</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php
                $image = $product['image'] ? "images/products/{$product['image']}" : "images/placeholder.jpg";
                ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             onerror="this.src='images/placeholder.jpg'">
                        <?php if ($product['stock_quantity'] < 10): ?>
                            <span class="product-badge">Low Stock</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="product-price"><?php echo formatPrice($product['price']); ?></div>
                        <div class="product-actions">
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-small btn-primary">View Details</a>
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-small">Add to Cart</button>
                            <?php else: ?>
                                <button class="btn btn-small" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.form-group label {
    font-size: 0.9rem;
    font-weight: bold;
    color: #333;
}

.form-group input,
.form-group select {
    padding: 0.5rem;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .container form {
        grid-template-columns: 1fr;
    }
    
    .container form > div:last-child {
        justify-self: stretch;
    }
    
    .container form > div:last-child .btn {
        flex: 1;
    }
}
</style>

<?php include 'includes/footer.php'; ?>