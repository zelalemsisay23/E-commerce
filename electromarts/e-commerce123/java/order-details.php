<?php
require_once 'includes/config.php';
requireLogin();

$order_id = (int)($_GET['id'] ?? 0);

if (!$order_id) {
    header('Location: orders.php');
    exit();
}

// Get order details - ensure user owns this order
$stmt = $pdo->prepare("
    SELECT o.*, u.full_name, u.email 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $_SESSION['user_id']]);
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

$page_title = 'Order Details';
include 'includes/header.php';
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h1>
        <a href="orders.php" class="btn">← Back to Orders</a>
    </div>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Order Details -->
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 style="color: #667eea; margin-bottom: 1rem;">Order Information</h3>
                    <p><strong>Order Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
                    <p><strong>Status:</strong> 
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
                        <span style="background: <?php echo $status_color; ?>; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.9rem; font-weight: bold;">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </p>
                    <p><strong>Payment Method:</strong> <?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></p>
                </div>
                
                <div>
                    <h3 style="color: #667eea; margin-bottom: 1rem;">Shipping Address</h3>
                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 5px;">
                        <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                    </div>
                </div>
            </div>
            
            <h3 style="color: #667eea; margin-bottom: 1rem;">Order Items</h3>
            <div style="border: 1px solid #eee; border-radius: 10px; overflow: hidden;">
                <?php foreach ($order_items as $index => $item): ?>
                    <?php $image = $item['image'] ? "images/products/{$item['image']}" : "images/placeholder.jpg"; ?>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; <?php echo $index > 0 ? 'border-top: 1px solid #eee;' : ''; ?>">
                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;"
                             onerror="this.src='images/placeholder.jpg'">
                        <div style="flex: 1;">
                            <div style="font-weight: bold; font-size: 1.1rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div style="color: #666; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($item['brand']); ?></div>
                            <div style="color: #666;">Quantity: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: bold; font-size: 1.1rem;"><?php echo formatPrice($item['price'] * $item['quantity']); ?></div>
                            <div style="color: #666; font-size: 0.9rem;"><?php echo formatPrice($item['price']); ?> each</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div>
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <h3 style="color: #667eea; margin-bottom: 1.5rem;">Order Summary</h3>
                
                <?php
                $subtotal = 0;
                foreach ($order_items as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }
                $tax = $subtotal * 0.08;
                $shipping = $subtotal > 50 ? 0 : 9.99;
                ?>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Subtotal:</span>
                    <span><?php echo formatPrice($subtotal); ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Tax (8%):</span>
                    <span><?php echo formatPrice($tax); ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <span>Shipping:</span>
                    <span><?php echo $shipping > 0 ? formatPrice($shipping) : 'FREE'; ?></span>
                </div>
                
                <hr style="margin: 1rem 0; border: none; border-top: 2px solid #eee;">
                
                <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold;">
                    <span>Total:</span>
                    <span><?php echo formatPrice($order['total_amount']); ?></span>
                </div>
            </div>
            
            <!-- Order Status Timeline -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <h3 style="color: #667eea; margin-bottom: 1.5rem;">Order Status</h3>
                
                <div class="status-timeline">
                    <?php
                    $statuses = [
                        'pending' => 'Order Placed',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered'
                    ];
                    
                    $current_status = $order['status'];
                    $status_order = array_keys($statuses);
                    $current_index = array_search($current_status, $status_order);
                    
                    foreach ($statuses as $status => $label):
                        $status_index = array_search($status, $status_order);
                        $is_completed = $status_index <= $current_index && $current_status !== 'cancelled';
                        $is_current = $status === $current_status;
                    ?>
                        <div class="status-item <?php echo $is_completed ? 'completed' : ''; ?> <?php echo $is_current ? 'current' : ''; ?>">
                            <div class="status-icon">
                                <?php if ($is_completed): ?>
                                    <i class="fas fa-check"></i>
                                <?php else: ?>
                                    <i class="fas fa-circle"></i>
                                <?php endif; ?>
                            </div>
                            <div class="status-label"><?php echo $label; ?></div>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if ($current_status === 'cancelled'): ?>
                        <div class="status-item cancelled current">
                            <div class="status-icon">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="status-label">Cancelled</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.status-timeline {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.5rem 0;
    position: relative;
}

.status-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 12px;
    top: 100%;
    width: 2px;
    height: 1rem;
    background: #ddd;
}

.status-item.completed::after {
    background: #28a745;
}

.status-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #ddd;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.status-item.completed .status-icon {
    background: #28a745;
}

.status-item.current .status-icon {
    background: #667eea;
}

.status-item.cancelled .status-icon {
    background: #dc3545;
}

.status-label {
    font-weight: 500;
    color: #666;
}

.status-item.completed .status-label,
.status-item.current .status-label {
    color: #333;
    font-weight: bold;
}

@media (max-width: 768px) {
    .container > div:nth-child(2) {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>