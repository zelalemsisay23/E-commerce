<?php
require_once '../includes/config.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: index.php');
    exit();
}

$order_id = (int)($_GET['id'] ?? 0);

if (!$order_id) {
    header('Location: orders.php');
    exit();
}

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, u.full_name, u.email, u.phone 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = ?
");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

if (!$order) {
    header('Location: orders.php');
    exit();
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.image, p.brand 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

// Get payment transactions
$stmt = $pdo->prepare("
    SELECT * FROM payment_transactions 
    WHERE order_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$order_id]);
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - ElectroMart Admin</title>
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
                <h1>Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h1>
                <div class="admin-user">
                    <a href="orders.php" class="btn">← Back to Orders</a>
                </div>
            </header>
            
            <div class="admin-content">
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                    <!-- Order Details -->
                    <div class="admin-card">
                        <h3>Order Information</h3>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                            <div>
                                <h4>Customer Details</h4>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                                <?php if ($order['phone']): ?>
                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <h4>Order Details</h4>
                                <p><strong>Order Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
                                <p><strong>Status:</strong> 
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </p>
                                <p><strong>Payment Method:</strong> <?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></p>
                                <p><strong>Total Amount:</strong> <?php echo formatPrice($order['total_amount']); ?></p>
                            </div>
                        </div>
                        
                        <h4>Shipping Address</h4>
                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
                            <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                        </div>
                        
                        <h4>Order Items</h4>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 1rem;">
                                                <?php $image = $item['image'] ? "../images/products/{$item['image']}" : "../images/placeholder.jpg"; ?>
                                                <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"
                                                     onerror="this.src='../images/placeholder.jpg'">
                                                <div>
                                                    <div style="font-weight: bold;"><?php echo htmlspecialchars($item['name']); ?></div>
                                                    <small style="color: #666;"><?php echo htmlspecialchars($item['brand']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo formatPrice($item['price']); ?></td>
                                        <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Order Actions -->
                    <div>
                        <!-- Status Update -->
                        <div class="admin-card" style="margin-bottom: 2rem;">
                            <h4>Update Status</h4>
                            <form method="POST" action="orders.php">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                                <button type="submit" name="update_status" value="1" class="btn btn-primary" style="width: 100%;">
                                    Update Status
                                </button>
                            </form>
                        </div>
                        
                        <!-- Payment Transactions -->
                        <?php if (!empty($transactions)): ?>
                            <div class="admin-card">
                                <h4>Payment Transactions</h4>
                                <?php foreach ($transactions as $transaction): ?>
                                    <div style="border: 1px solid #eee; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                            <strong><?php echo formatPrice($transaction['amount']); ?></strong>
                                            <span class="status-badge status-<?php echo $transaction['status']; ?>">
                                                <?php echo ucfirst($transaction['status']); ?>
                                            </span>
                                        </div>
                                        <div style="font-size: 0.9rem; color: #666;">
                                            <div>Method: <?php echo ucwords(str_replace('_', ' ', $transaction['payment_method'])); ?></div>
                                            <?php if ($transaction['transaction_id']): ?>
                                                <div>Transaction ID: <?php echo htmlspecialchars($transaction['transaction_id']); ?></div>
                                            <?php endif; ?>
                                            <div>Date: <?php echo date('M j, Y g:i A', strtotime($transaction['created_at'])); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
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
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #f8d7da; color: #721c24; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-failed { background: #f8d7da; color: #721c24; }
        .form-control { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 3px; margin-bottom: 1rem; }
    </style>
</body>
</html>