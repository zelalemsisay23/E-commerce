<?php
require_once 'includes/config.php';
requireLogin();

$order_id = (int)($_GET['order'] ?? 0);

if (!$order_id) {
    header('Location: index.php');
    exit();
}

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, u.full_name, u.email 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
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

$page_title = 'Order Confirmation';
include 'includes/header.php';
?>

<div class="container">
    <div style="text-align: center; margin-bottom: 3rem;">
        <div style="background: #d4edda; color: #155724; padding: 2rem; border-radius: 10px; margin-bottom: 2rem;">
            <i class="fas fa-check-circle" style="font-size: 4rem; margin-bottom: 1rem;"></i>
            <h1 style="margin-bottom: 1rem;">Order Placed Successfully!</h1>
            <p style="font-size: 1.1rem;">Thank you for your purchase. Your order has been received and is being processed.</p>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Order Details -->
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <h2 style="margin-bottom: 1.5rem;">Order Details</h2>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h4 style="margin-bottom: 0.5rem; color: #667eea;">Order Information</h4>
                    <p><strong>Order ID:</strong> #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
                    <p><strong>Order Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
                    <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">Processing</span></p>
                    <p><strong>Payment Method:</strong> <?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></p>
                </div>
                
                <div>
                    <h4 style="margin-bottom: 0.5rem; color: #667eea;">Shipping Address</h4>
                    <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                </div>
            </div>
            
            <h3 style="margin-bottom: 1rem;">Order Items</h3>
            <div style="border: 1px solid #eee; border-radius: 5px; overflow: hidden;">
                <?php foreach ($order_items as $index => $item): ?>
                    <?php $image = $item['image'] ? "images/products/{$item['image']}" : "images/placeholder.jpg"; ?>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; <?php echo $index > 0 ? 'border-top: 1px solid #eee;' : ''; ?>">
                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;"
                             onerror="this.src='images/placeholder.jpg'">
                        <div style="flex: 1;">
                            <div style="font-weight: bold;"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div style="color: #666; font-size: 0.9rem;"><?php echo htmlspecialchars($item['brand']); ?></div>
                            <div style="color: #666; font-size: 0.9rem;">Quantity: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: bold;"><?php echo formatPrice($item['price'] * $item['quantity']); ?></div>
                            <div style="color: #666; font-size: 0.9rem;"><?php echo formatPrice($item['price']); ?> each</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); height: fit-content;">
            <h3 style="margin-bottom: 1.5rem;">Order Summary</h3>
            
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
            
            <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold; margin-bottom: 2rem;">
                <span>Total:</span>
                <span><?php echo formatPrice($order['total_amount']); ?></span>
            </div>
            
            <div style="background: #e3f2fd; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
                <h4 style="margin-bottom: 0.5rem; color: #1976d2;">What's Next?</h4>
                <ul style="margin: 0; padding-left: 1.5rem; color: #666;">
                    <li>You'll receive an email confirmation shortly</li>
                    <li>We'll notify you when your order ships</li>
                    <li>Track your order in "My Orders"</li>
                </ul>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="orders.php" class="btn btn-primary" style="text-align: center;">
                    <i class="fas fa-list"></i> View All Orders
                </a>
                <a href="index.php" class="btn" style="text-align: center;">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .container > div:nth-child(2) {
        grid-template-columns: 1fr;
    }
    
    .container > div:nth-child(2) > div:first-child > div:nth-child(2) {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>