<?php
require_once 'includes/config.php';

$order_id = $_GET['order_id'] ?? '';
$order = null;
$order_items = [];

if ($order_id) {
    if (isLoggedIn()) {
        // Get order for logged-in user
        $stmt = $pdo->prepare("
            SELECT o.*, u.full_name, u.email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ? AND o.user_id = ?
        ");
        $stmt->execute([$order_id, $_SESSION['user_id']]);
        $order = $stmt->fetch();
    } else {
        // Allow tracking with order ID and email for guest users
        $email = $_GET['email'] ?? '';
        if ($email) {
            $stmt = $pdo->prepare("
                SELECT o.*, u.full_name, u.email 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = ? AND u.email = ?
            ");
            $stmt->execute([$order_id, $email]);
            $order = $stmt->fetch();
        }
    }
    
    if ($order) {
        // Get order items
        $stmt = $pdo->prepare("
            SELECT oi.*, p.name, p.image, p.brand 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order_id]);
        $order_items = $stmt->fetchAll();
    }
}

$page_title = 'Track Order';
include 'includes/header.php';
?>

<div class="container">
    <h1 style="margin-bottom: 2rem;">Track Your Order</h1>
    
    <?php if (!$order_id || !$order): ?>
        <!-- Order Tracking Form -->
        <div style="max-width: 500px; margin: 0 auto;">
            <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <h3 style="text-align: center; margin-bottom: 2rem;">Enter Order Details</h3>
                
                <form method="GET">
                    <div class="form-group">
                        <label for="order_id">Order ID *</label>
                        <input type="text" id="order_id" name="order_id" 
                               value="<?php echo htmlspecialchars($order_id); ?>" 
                               placeholder="Enter your order ID" required>
                    </div>
                    
                    <?php if (!isLoggedIn()): ?>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" 
                                   placeholder="Enter your email address" required>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-search"></i> Track Order
                    </button>
                </form>
                
                <?php if ($order_id && !$order): ?>
                    <div class="alert alert-error" style="margin-top: 1rem;">
                        Order not found. Please check your order ID<?php echo !isLoggedIn() ? ' and email address' : ''; ?>.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <!-- Order Details -->
        <div style="background: white; border-radius: 15px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 style="margin-bottom: 1rem;">Order Information</h3>
                    <div style="display: grid; gap: 0.5rem;">
                        <div><strong>Order ID:</strong> #<?php echo $order['id']; ?></div>
                        <div><strong>Order Date:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?></div>
                        <div><strong>Customer:</strong> <?php echo htmlspecialchars($order['full_name']); ?></div>
                        <div><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></div>
                        <div><strong>Total Amount:</strong> <?php echo formatPrice($order['total_amount']); ?></div>
                    </div>
                </div>
                
                <div>
                    <h3 style="margin-bottom: 1rem;">Shipping Address</h3>
                    <div style="color: #666; line-height: 1.6;">
                        <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Status Timeline -->
        <div style="background: white; border-radius: 15px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 2rem;">Order Status</h3>
            
            <div class="order-timeline">
                <?php
                $statuses = [
                    'pending' => ['icon' => 'fas fa-clock', 'title' => 'Order Placed', 'desc' => 'Your order has been received'],
                    'processing' => ['icon' => 'fas fa-cog', 'title' => 'Processing', 'desc' => 'We are preparing your order'],
                    'shipped' => ['icon' => 'fas fa-truck', 'title' => 'Shipped', 'desc' => 'Your order is on the way'],
                    'delivered' => ['icon' => 'fas fa-check-circle', 'title' => 'Delivered', 'desc' => 'Order has been delivered']
                ];
                
                $current_status = $order['status'];
                $status_order = ['pending', 'processing', 'shipped', 'delivered'];
                $current_index = array_search($current_status, $status_order);
                
                foreach ($status_order as $index => $status):
                    $is_completed = $index <= $current_index;
                    $is_current = $status === $current_status;
                    $is_cancelled = $current_status === 'cancelled';
                ?>
                    <div class="timeline-item <?php echo $is_completed && !$is_cancelled ? 'completed' : ''; ?> <?php echo $is_current ? 'current' : ''; ?>">
                        <div class="timeline-icon">
                            <i class="<?php echo $statuses[$status]['icon']; ?>"></i>
                        </div>
                        <div class="timeline-content">
                            <h4><?php echo $statuses[$status]['title']; ?></h4>
                            <p><?php echo $statuses[$status]['desc']; ?></p>
                            <?php if ($is_current): ?>
                                <small style="color: #667eea; font-weight: bold;">Current Status</small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if ($current_status === 'cancelled'): ?>
                    <div class="timeline-item cancelled">
                        <div class="timeline-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Order Cancelled</h4>
                            <p>This order has been cancelled</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Order Items -->
        <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 2rem;">Order Items</h3>
            
            <div style="display: grid; gap: 1rem;">
                <?php foreach ($order_items as $item): ?>
                    <?php $image = $item['image'] ? "images/products/{$item['image']}" : "images/placeholder.jpg"; ?>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid #eee; border-radius: 10px;">
                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;"
                             onerror="this.src='images/placeholder.jpg'">
                        
                        <div style="flex: 1;">
                            <div style="font-weight: bold; margin-bottom: 0.25rem;">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </div>
                            <div style="color: #666; font-size: 0.9rem; margin-bottom: 0.25rem;">
                                <?php echo htmlspecialchars($item['brand']); ?>
                            </div>
                            <div style="color: #666;">
                                Quantity: <?php echo $item['quantity']; ?> × <?php echo formatPrice($item['price']); ?>
                            </div>
                        </div>
                        
                        <div style="text-align: right;">
                            <div style="font-weight: bold; font-size: 1.1rem;">
                                <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="orders.php" class="btn btn-primary">View All Orders</a>
            <a href="index.php" class="btn">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

<style>
.order-timeline {
    position: relative;
    padding-left: 2rem;
}

.order-timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 3rem;
}

.timeline-icon {
    position: absolute;
    left: -2.5rem;
    top: 0;
    width: 40px;
    height: 40px;
    background: #f5f5f5;
    border: 2px solid #e0e0e0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}

.timeline-item.completed .timeline-icon {
    background: #28a745;
    border-color: #28a745;
    color: white;
}

.timeline-item.current .timeline-icon {
    background: #667eea;
    border-color: #667eea;
    color: white;
    animation: pulse 2s infinite;
}

.timeline-item.cancelled .timeline-icon {
    background: #dc3545;
    border-color: #dc3545;
    color: white;
}

.timeline-content h4 {
    margin: 0 0 0.5rem 0;
    color: #333;
}

.timeline-content p {
    margin: 0;
    color: #666;
}

.timeline-item.completed .timeline-content h4 {
    color: #28a745;
}

.timeline-item.current .timeline-content h4 {
    color: #667eea;
}

.timeline-item.cancelled .timeline-content h4 {
    color: #dc3545;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
    100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
}

@media (max-width: 768px) {
    .container > div:nth-child(2) {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>