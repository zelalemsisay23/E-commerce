<?php
require_once 'includes/config.php';
require_once 'includes/payment.php';
requireLogin();

// Get cart items
$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.image, p.stock_quantity 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

// Calculate totals
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.08;
$shipping = $subtotal > 50 ? 0 : 9.99;
$discount = 0;
$coupon_id = null;

// Handle coupon application
if (isset($_POST['apply_coupon'])) {
    $coupon_code = sanitize($_POST['coupon_code'] ?? '');
    if (!empty($coupon_code)) {
        $payment_processor = new PaymentProcessor($pdo);
        $coupon_result = $payment_processor->applyCoupon($coupon_code, $subtotal);
        
        if ($coupon_result['success']) {
            $discount = $coupon_result['discount'];
            $coupon_id = $coupon_result['coupon_id'];
            $_SESSION['applied_coupon'] = [
                'code' => $coupon_code,
                'discount' => $discount,
                'coupon_id' => $coupon_id
            ];
            $success = $coupon_result['message'];
        } else {
            $error = $coupon_result['message'];
        }
    }
}

// Check for applied coupon in session
if (isset($_SESSION['applied_coupon'])) {
    $discount = $_SESSION['applied_coupon']['discount'];
    $coupon_id = $_SESSION['applied_coupon']['coupon_id'];
}

$total = $subtotal + $tax + $shipping - $discount;

$error = '';
$success = '';

if ($_POST && !isset($_POST['apply_coupon'])) {
    $shipping_address = sanitize($_POST['shipping_address'] ?? '');
    $payment_method = sanitize($_POST['payment_method'] ?? '');
    
    if (empty($shipping_address) || empty($payment_method)) {
        $error = 'Please fill in all required fields';
    } else {
        try {
            $pdo->beginTransaction();
            
            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $total, $shipping_address, $payment_method]);
            $order_id = $pdo->lastInsertId();
            
            // Add order items
            foreach ($cart_items as $item) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
                
                // Update stock
                $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $stmt->execute([$item['quantity'], $item['product_id']]);
            }
            
            // Process payment
            $payment_processor = new PaymentProcessor($pdo);
            $payment_result = null;
            
            if ($payment_method === 'credit_card') {
                $card_data = [
                    'number' => sanitize($_POST['card_number'] ?? ''),
                    'expiry' => sanitize($_POST['card_expiry'] ?? ''),
                    'cvv' => sanitize($_POST['card_cvv'] ?? '')
                ];
                $payment_result = $payment_processor->processCreditCard($card_data, $total, $order_id);
            } elseif ($payment_method === 'paypal') {
                $payment_result = $payment_processor->processPayPal($total, $order_id);
            } else {
                // Cash on delivery - no payment processing needed
                $payment_result = ['success' => true, 'message' => 'Order placed successfully'];
            }
            
            if ($payment_result['success']) {
                // Update coupon usage if applied
                if ($coupon_id) {
                    $payment_processor->updateCouponUsage($coupon_id);
                    unset($_SESSION['applied_coupon']);
                }
                
                // Clear cart
                $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                
                // Send confirmation email
                $email_notifier = new EmailNotifier($pdo);
                $email_notifier->sendOrderConfirmation($order_id);
                
                $pdo->commit();
                
                header('Location: order-success.php?order=' . $order_id);
                exit();
            } else {
                $pdo->rollBack();
                $error = $payment_result['message'];
            }
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Error processing order. Please try again.';
        }
    }
}

$page_title = 'Checkout';
include 'includes/header.php';
?>

<div class="container">
    <h1 style="margin-bottom: 2rem;">Checkout</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Checkout Form -->
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <form method="POST" data-validate>
                <h3 style="margin-bottom: 1.5rem;">Shipping Information</h3>
                
                <div class="form-group">
                    <label for="shipping_address">Shipping Address *</label>
                    <textarea id="shipping_address" name="shipping_address" rows="4" required 
                              placeholder="Enter your full shipping address..."><?php echo htmlspecialchars($_POST['shipping_address'] ?? ''); ?></textarea>
                </div>
                
                <h3 style="margin-bottom: 1.5rem; margin-top: 2rem;">Payment Information</h3>
                
                <div class="form-group">
                    <label for="payment_method">Payment Method *</label>
                    <select id="payment_method" name="payment_method" required onchange="togglePaymentFields()">
                        <option value="">Select Payment Method</option>
                        <option value="credit_card" <?php echo ($_POST['payment_method'] ?? '') === 'credit_card' ? 'selected' : ''; ?>>Credit Card</option>
                        <option value="paypal" <?php echo ($_POST['payment_method'] ?? '') === 'paypal' ? 'selected' : ''; ?>>PayPal</option>
                        <option value="cash_on_delivery" <?php echo ($_POST['payment_method'] ?? '') === 'cash_on_delivery' ? 'selected' : ''; ?>>Cash on Delivery</option>
                    </select>
                </div>
                
                <div id="credit-card-fields" style="display: none;">
                    <div class="form-group">
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" 
                               maxlength="19" value="<?php echo htmlspecialchars($_POST['card_number'] ?? ''); ?>">
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="card_expiry">Expiry Date</label>
                            <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" 
                                   maxlength="5" value="<?php echo htmlspecialchars($_POST['card_expiry'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="card_cvv">CVV</label>
                            <input type="text" id="card_cvv" name="card_cvv" placeholder="123" 
                                   maxlength="4" value="<?php echo htmlspecialchars($_POST['card_cvv'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                        <i class="fas fa-lock"></i> Place Order - <?php echo formatPrice($total); ?>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Order Summary -->
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); height: fit-content;">
            <h3 style="margin-bottom: 1.5rem;">Order Summary</h3>
            
            <!-- Cart Items -->
            <div style="margin-bottom: 1.5rem;">
                <?php foreach ($cart_items as $item): ?>
                    <?php $image = $item['image'] ? "images/products/{$item['image']}" : "images/placeholder.jpg"; ?>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;">
                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"
                             onerror="this.src='images/placeholder.jpg'">
                        <div style="flex: 1;">
                            <div style="font-weight: bold; font-size: 0.9rem;"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div style="color: #666; font-size: 0.8rem;">Qty: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div style="font-weight: bold;"><?php echo formatPrice($item['price'] * $item['quantity']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Totals -->
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
                <span><?php echo formatPrice($total); ?></span>
            </div>
        </div>
    </div>
</div>

<script>
function togglePaymentFields() {
    const paymentMethod = document.getElementById('payment_method').value;
    const creditCardFields = document.getElementById('credit-card-fields');
    
    if (paymentMethod === 'credit_card') {
        creditCardFields.style.display = 'block';
        // Make credit card fields required
        document.getElementById('card_number').required = true;
        document.getElementById('card_expiry').required = true;
        document.getElementById('card_cvv').required = true;
    } else {
        creditCardFields.style.display = 'none';
        // Remove required attribute
        document.getElementById('card_number').required = false;
        document.getElementById('card_expiry').required = false;
        document.getElementById('card_cvv').required = false;
    }
}

// Format card number input
document.getElementById('card_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// Format expiry date input
document.getElementById('card_expiry').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    e.target.value = value;
});

// Only allow numbers for CVV
document.getElementById('card_cvv').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});

// Initialize payment fields based on current selection
document.addEventListener('DOMContentLoaded', function() {
    togglePaymentFields();
});
</script>

<style>
@media (max-width: 768px) {
    .container > div:nth-child(2) {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>