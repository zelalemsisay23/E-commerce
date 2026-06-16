<?php
require_once '../includes/config.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: index.php');
    exit();
}

$success = '';
$error = '';

// Handle product actions
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $category_id = (int)($_POST['category_id'] ?? 0);
        $stock_quantity = (int)($_POST['stock_quantity'] ?? 0);
        $brand = sanitize($_POST['brand'] ?? '');
        $model = sanitize($_POST['model'] ?? '');
        $warranty = sanitize($_POST['warranty'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');
        
        if (empty($name) || empty($description) || $price <= 0 || !$category_id) {
            $error = 'Please fill in all required fields';
        } else {
            try {
                if ($action === 'add') {
                    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category_id, stock_quantity, brand, model, warranty, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $description, $price, $category_id, $stock_quantity, $brand, $model, $warranty, $status]);
                    $success = 'Product added successfully';
                } else {
                    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, stock_quantity = ?, brand = ?, model = ?, warranty = ?, status = ? WHERE id = ?");
                    $stmt->execute([$name, $description, $price, $category_id, $stock_quantity, $brand, $model, $warranty, $status, $id]);
                    $success = 'Product updated successfully';
                }
            } catch (Exception $e) {
                $error = 'Error saving product: ' . $e->getMessage();
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $success = 'Product deleted successfully';
            } catch (Exception $e) {
                $error = 'Error deleting product: ' . $e->getMessage();
            }
        }
    }
}

// Get products with pagination
$page = (int)($_GET['page'] ?? 1);
$per_page = 20;
$offset = ($page - 1) * $per_page;

$search = sanitize($_GET['search'] ?? '');
$category_filter = (int)($_GET['category'] ?? 0);
$status_filter = sanitize($_GET['status'] ?? '');

$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(p.name LIKE ? OR p.brand LIKE ? OR p.model LIKE ?)";
    $search_term = "%{$search}%";
    $params = array_merge($params, [$search_term, $search_term, $search_term]);
}

if ($category_filter) {
    $where_conditions[] = "p.category_id = ?";
    $params[] = $category_filter;
}

if ($status_filter) {
    $where_conditions[] = "p.status = ?";
    $params[] = $status_filter;
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count
$count_sql = "SELECT COUNT(*) FROM products p {$where_clause}";
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total_products = $stmt->fetchColumn();
$total_pages = ceil($total_products / $per_page);

// Get products
$sql = "
    SELECT p.*, c.name as category_name
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    {$where_clause}
    ORDER BY p.created_at DESC 
    LIMIT {$per_page} OFFSET {$offset}
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get categories for dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$page_title = 'Product Management';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - ElectroMart Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .admin-header { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .admin-nav { display: flex; gap: 1rem; margin-bottom: 2rem; }
        .admin-nav a { padding: 0.5rem 1rem; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }
        .admin-nav a.active { background: #333; }
        .filters { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .product-table { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .product-table table { width: 100%; border-collapse: collapse; }
        .product-table th, .product-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        .product-table th { background: #f8f9fa; font-weight: bold; }
        .product-table tr:hover { background: #f8f9fa; }
        .status-active { color: #28a745; font-weight: bold; }
        .status-inactive { color: #dc3545; font-weight: bold; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal-content { background: white; margin: 2rem auto; padding: 2rem; border-radius: 10px; max-width: 600px; max-height: 90vh; overflow-y: auto; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group.full-width { grid-column: 1 / -1; }
        .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem; }
        .pagination a, .pagination span { padding: 0.5rem 1rem; border: 1px solid #ddd; text-decoration: none; }
        .pagination .current { background: #667eea; color: white; }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Admin Header -->
        <div class="admin-header">
            <h1><i class="fas fa-box"></i> Product Management</h1>
            <div class="admin-nav">
                <a href="orders.php">Orders</a>
                <a href="products.php" class="active">Products</a>
                <a href="users.php">Users</a>
                <a href="../index.php">View Store</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Filters and Add Button -->
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 2rem;">
            <button onclick="openAddModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </button>
            
            <form method="GET" class="filters" style="margin-left: auto;">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                
                <select name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $category_filter == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <select name="status">
                    <option value="">All Status</option>
                    <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
                
                <button type="submit" class="btn">Filter</button>
            </form>
        </div>

        <!-- Products Table -->
        <div class="product-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                                No products found. <a href="#" onclick="openAddModal()">Add your first product</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($product['brand'] . ' ' . $product['model']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($product['category_name'] ?? 'No Category'); ?></td>
                                <td><?php echo formatPrice($product['price']); ?></td>
                                <td>
                                    <?php if ($product['stock_quantity'] <= 0): ?>
                                        <span style="color: #dc3545;">Out of Stock</span>
                                    <?php elseif ($product['stock_quantity'] < 10): ?>
                                        <span style="color: #ffc107;"><?php echo $product['stock_quantity']; ?> (Low)</span>
                                    <?php else: ?>
                                        <span style="color: #28a745;"><?php echo $product['stock_quantity']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-<?php echo $product['status']; ?>">
                                        <?php echo ucfirst($product['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)" class="btn btn-small">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteProduct(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')" class="btn btn-small" style="background: #dc3545;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>&status=<?php echo urlencode($status_filter); ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Add New Product</h2>
            <form id="productForm" method="POST">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="productId" value="">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="brand">Brand *</label>
                        <input type="text" id="brand" name="brand" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" id="model" name="model">
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price ($) *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity *</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="warranty">Warranty</label>
                        <input type="text" id="warranty" name="warranty" placeholder="e.g., 1 Year">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                    </div>
                </div>
                
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Save Product</button>
                    <button type="button" onclick="closeModal()" class="btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Product';
            document.getElementById('formAction').value = 'add';
            document.getElementById('productId').value = '';
            document.getElementById('productForm').reset();
            document.getElementById('productModal').style.display = 'block';
        }

        function editProduct(product) {
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('productId').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('brand').value = product.brand;
            document.getElementById('model').value = product.model;
            document.getElementById('category_id').value = product.category_id;
            document.getElementById('price').value = product.price;
            document.getElementById('stock_quantity').value = product.stock_quantity;
            document.getElementById('warranty').value = product.warranty;
            document.getElementById('status').value = product.status;
            document.getElementById('description').value = product.description;
            document.getElementById('productModal').style.display = 'block';
        }

        function deleteProduct(id, name) {
            if (confirm(`Are you sure you want to delete "${name}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal() {
            document.getElementById('productModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>