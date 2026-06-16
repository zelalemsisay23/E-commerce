<?php
require_once '../includes/config.php';
require_once '../includes/payment.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: index.php');
    exit();
}

$success = '';
$error = '';

// Handle order status updates
if ($_POST && isset($_POST['update_status'])) {
    $order_id = (int)($_POST['order_id'] ?? 0);
    $new_status = sanitize($_POST['status'] ?? '');
    
    if ($order_id && $new_status) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$new_status, $order_id]);
            
            // Send email notification
            $email_notifier = new EmailNotifier($pdo);
            $email_notifier->sendOrderStatusUpdate($order_id, $new_status);
            
            $success = 'Order status updated successfully';
        } catch (Exception $e) {
            $error = 'Error updating order status';
        }
    }
}

// Get orders with pagination
$page = (int)($_GET['page'] ?? 1);
$per_page = 20;
$offset = ($page - 1) * $per_page;

$search = sanitize($_GET['search'] ?? '');
$status_filter = sanitize($_GET['status'] ?? '');

$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(o.id LIKE ? OR u.full_name LIKE ? OR u.email LIKE ?)";
    $search_term = "%{$search}%";
    $params = array_merge($params, [$search_term, $search_term, $search_term]);
}

if ($status_filter) {
    $where_conditions[] = "o.status = ?";
    $params[] = $status_filter;
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count
$count_sql = "SELECT COUNT(*) FROM orders o JOIN users u ON o.user_id = u.id {$where_clause}";
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total_orders = $stmt->fetchColumn();
$total_pages = ceil($total_orders / $per_page);

// Get orders
$sql = "
    SELECT o.*, u.full_name, u.email, COUNT(oi.id) as item_count
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    LEFT JOIN order_items oi ON o.id = oi.order_id
    {$where_clause}
    GROUP BY o.id
    ORDER BY o.created_at DESC 
    LIMIT {$per_page} OFFSET {$offset}
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - ElectroMart Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h2><i class="fas fa-bolt"></i> ElectroMart Admin</h2>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                    <li><a href="orders.php" class="active"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                    <li><a href="reviews.php"><i class="fas fa-star"></i> Reviews</a></li>
                    <li><a href="coupons.php"><i class="fas fa-ticket-alt"></i> Coupons</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>Orders Management</h1>
                <div class="admin-user">
                    <i class="fas fa-user-circle"></i>
                    <span>Admin</span>
                </div>
            </header>
            
            <div class="admin-content">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <!-- Filters -->
                <div class="admin-filters">
                    <form method="GET" style="display: flex; gap: 1rem; align-items: end; margin-bottom: 2rem;">
                        <div>
                            <label>Search Orders</label>
                            <input type="text" name="search" placeholder="Order ID, customer name, email..." 
                                   value="<?php echo htmlspecialchars($search); ?>" style="width: 300px;">
                        </div>
                        
                        <div>
                            <label>Status</label>
                            <select name="status">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $status_filter === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $status_filter === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $status_filter === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="orders.php" class="btn">Clear</a>
                    </form>
                </div>
                
                <!-- Orders Table -->
                <div class="admin-card">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                        <td>
                                            <div><?php echo htmlspecialchars($order['full_name']); ?></div>
                                            <small style="color: #666;"><?php echo htmlspecialchars($order['email']); ?></small>
                                        </td>
                                        <td><?php echo $order['item_count']; ?> item<?php echo $order['item_count'] != 1 ? 's' : ''; ?></td>
                                        <td><?php echo formatPrice($order['total_amount']); ?></td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <select name="status" onchange="this.form.submit()" class="status-select">
                                                    <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                    <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                    <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                    <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                        </td>
                                        <td><?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-small btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>" 
                                   class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <style>
        .admin-layout { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 250px; background: #2c3e50; color: white; }
        .admin-logo { padding: 2rem 1.5rem; border-bottom: 1px solid #34495e; }
        .admin-logo h2 { margin: 0; color: #ffd700; }
        .admin-nav ul { list-style: none; padding: 0; margin: 0; }
        .admin-nav a { display: block; padding: 1rem 1.5rem; color: #bdc3c7; text-decoration: none; transition: all 0.3s; }
        .admin-nav a:hover, .admin-nav a.active { background: #34495e; color: white; }
        .admin-nav i { margin-right: 0.5rem; width: 20px; }
        .admin-main { flex: 1; background: #f8f9fa; }
        .admin-header { background: white; padding: 1.5rem 2rem; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .admin-content { padding: 2rem; }
        .admin-card { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th, .admin-table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
        .admin-table th { background: #f8f9fa; font-weight: bold; color: #333; }
        .status-select { padding: 0.25rem; border: 1px solid #ddd; border-radius: 3px; }
        .pagination { display: flex; gap: 0.5rem; justify-content: center; margin-top: 2rem; }
        .pagination a { padding: 0.5rem 1rem; border: 1px solid #ddd; text-decoration: none; color: #333; border-radius: 3px; }
        .pagination a.active { background: #667eea; color: white; border-color: #667eea; }
        .alert { padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</body>
</html>