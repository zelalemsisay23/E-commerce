<?php
require_once 'includes/config.php';
requireLogin();

// Get user's orders
$stmt = $pdo->prepare("
    SELECT o.*, COUNT(oi.id) as item_count 
    FROM orders o 
    LEFT JOIN order_items oi ON o.id = oi.order_id 
    WHERE o.user_id = ? 
    GROUP BY o.id 
    ORDER BY o.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

$page_title = 'My Orders';
include 'includes/header.php';
?>

<div class="container">
    <h1 style="margin-bottom: 2rem;">My Orders</h1>
    
    <?php if (empty($orders)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-shopping-bag" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
            <h2 style="color: #666; margin-bottom: 1rem;">No orders yet</h2>
            <p style="color: #999; margin-bottom: 2rem;">Start shopping to see your orders here.</p>
            <a href="index.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <?php foreach ($orders as $order): ?>
                <div style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="padding: 1.5rem; border-bottom: 1px solid #eee;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                            <div>
                                <h3 style="margin-bottom: 0.5rem;">Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h3>
                                <p style="color: #666; margin-bottom: 0.5rem;">
                                    Placed on <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                                </p>
                                <p style="color: #666;">
                                    <?php echo $order['item_count']; ?> item<?php echo $order['item_count'] != 1 ? 's' : ''; ?>
                                </p>
                            </div>
                            
                            <div style="text-align: right;">
                                <div style="font-size: 1.2rem; font-weight: bold; margin-bottom: 0.5rem;">
                                    <?php echo formatPrice($order['total_amount']); ?>
                                </div>
                                <div style="margin-bottom: 1rem;">
                                    <?php
                                    $status_colors = [
                                        'pending' => '#ffc107',
                                        'processing' => '#17a2b8',
                                        'shipped' => '#fd7e14',
                                        'delivered' => '#28a745',
                                        'cancelled' => '#dc3545'
                                    ];
                                    $status_color = $status_colors[$order['status']] ?? '#6c757d';
                                    ?>
                                    <span style="background: <?php echo $status_color; ?>; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: bold;">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </div>
                                <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-small btn-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Items Preview -->
                    <div style="padding: 1rem 1.5rem;">
                        <?php
                        // Get first 3 items for preview
                        $stmt = $pdo->prepare("
                            SELECT oi.*, p.name, p.image, p.brand 
                            FROM order_items oi 
                            JOIN products p ON oi.product_id = p.id 
                            WHERE oi.order_id = ? 
                            LIMIT 3
                        ");
                        $stmt->execute([$order['id']]);
                        $items = $stmt->fetchAll();
                        ?>
                        
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <?php foreach ($items as $item): ?>
                                <?php $image = $item['image'] ? "images/products/{$item['image']}" : "images/placeholder.jpg"; ?>
                                <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 200px;">
                                    <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;"
                                         onerror="this.src='images/placeholder.jpg'">
                                    <div>
                                        <div style="font-size: 0.9rem; font-weight: bold;"><?php echo htmlspecialchars($item['name']); ?></div>
                                        <div style="font-size: 0.8rem; color: #666;">Qty: <?php echo $item['quantity']; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if ($order['item_count'] > 3): ?>
                                <div style="display: flex; align-items: center; color: #666; font-size: 0.9rem;">
                                    +<?php echo $order['item_count'] - 3; ?> more item<?php echo ($order['item_count'] - 3) != 1 ? 's' : ''; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
@media (max-width: 768px) {
    .container > div:nth-child(2) > div > div:first-child > div {
        flex-direction: column;
        text-align: center;
    }
    
    .container > div:nth-child(2) > div > div:first-child > div:last-child {
        text-align: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>