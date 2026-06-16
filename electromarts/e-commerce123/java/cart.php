<?php
require_once 'includes/config.php';
requireLogin();

$page_title = 'Shopping Cart';
include 'includes/header.php';

// Get cart items
$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.image, p.stock_quantity, p.brand 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ? 
    ORDER BY c.added_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

// Calculate totals
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Apply coupon discount if exists
$discount_amount = 0;
$applied_coupon = null;
if (isset($_SESSION['applied_coupon'])) {
    $applied_coupon = $_SESSION['applied_coupon'];
    $discount_amount = $applied_coupon['discount_amount'];
}

$tax = ($subtotal - $discount_amount) * 0.08; // 8% tax after discount
$shipping = ($subtotal - $discount_amount) > 50 ? 0 : 9.99; // Free shipping over $50 after discount
$total = $subtotal - $discount_amount + $tax + $shipping;
?>

<div class="container">
    <h1 style="margin-bottom: 2rem;">Shopping Cart</h1>
    
    <?php if (empty($cart_items)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
            <h2 style="color: #666; margin-bottom: 1rem;">Your cart is empty</h2>
            <p style="color: #999; margin-bottom: 2rem;">Add some products to get started!</p>
            <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Cart Items -->
            <div class="cart-table">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <?php
                            $image = $item['image'] ? "images/products/{$item['image']}" : "images/placeholder.jpg";
                            $item_total = $item['price'] * $item['quantity'];
                            ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;"
                                             onerror="this.src='images/placeholder.jpg'">
                                        <div>
                                            <div style="font-weight: bold;"><?php echo htmlspecialchars($item['name']); ?></div>
                                            <div style="color: #666; font-size: 0.9rem;"><?php echo htmlspecialchars($item['brand']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo formatPrice($item['price']); ?></td>
                                <td>
                                    <div class="quantity-controls">
                                        <button type="button" class="qty-minus" onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                                        <input type="number" class="qty-input" value="<?php echo $item['quantity']; ?>" 
                                               min="1" max="<?php echo $item['stock_quantity']; ?>"
                                               data-product-id="<?php echo $item['product_id']; ?>"
                                               data-max-stock="<?php echo $item['stock_quantity']; ?>"
                                               onchange="updateQuantity(<?php echo $item['product_id']; ?>, this.value)">
                                        <button type="button" class="qty-plus" onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                                    </div>
                                </td>
                                <td style="font-weight: bold;"><?php echo formatPrice($item_total); ?></td>
                                <td>
                                    <button onclick="removeFromCart(<?php echo $item['product_id']; ?>)" 
                                            class="btn btn-small" style="background: #dc3545; color: white;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 style="margin-bottom: 1.5rem;">Order Summary</h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Subtotal:</span>
                    <span class="cart-subtotal"><?php echo formatPrice($subtotal); ?></span>
                </div>
                
                <?php if ($applied_coupon): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #28a745;">
                        <span>Discount (<?php echo $applied_coupon['code']; ?>):</span>
                        <span>-<?php echo formatPrice($discount_amount); ?></span>
                    </div>
                <?php endif; ?>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Tax (8%):</span>
                    <span class="cart-tax"><?php echo formatPrice($tax); ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <span>Shipping:</span>
                    <span class="cart-shipping">
                        <?php echo $shipping > 0 ? formatPrice($shipping) : 'FREE'; ?>
                    </span>
                </div>
                
                <!-- Coupon Section -->
                <?php if (!$applied_coupon): ?>
                    <div style="margin-bottom: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 5px;">
                        <h6 style="margin-bottom: 0.5rem;">Have a coupon?</h6>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="text" id="couponCode" placeholder="Enter coupon code" 
                                   style="flex: 1; padding: 0.5rem; border: 1px solid #ddd; border-radius: 3px;">
                            <button onclick="applyCoupon()" class="btn btn-small">Apply</button>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="margin-bottom: 1rem; padding: 1rem; background: #d4edda; border-radius: 5px; border: 1px solid #c3e6cb;">
                        <div style="display: flex; justify-content: between; align-items: center;">
                            <div>
                                <i class="fas fa-check-circle" style="color: #28a745;"></i>
                                <strong>Coupon Applied: <?php echo $applied_coupon['code']; ?></strong>
                            </div>
                            <button onclick="removeCoupon()" class="btn btn-small" style="background: #dc3545; color: white; margin-left: auto;">
                                Remove
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (($subtotal - $discount_amount) < 50 && ($subtotal - $discount_amount) > 0): ?>
                    <div style="background: #e3f2fd; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; font-size: 0.9rem;">
                        <i class="fas fa-info-circle" style="color: #1976d2;"></i>
                        Add <?php echo formatPrice(50 - ($subtotal - $discount_amount)); ?> more for free shipping!
                    </div>
                <?php endif; ?>
                
                <hr style="margin: 1rem 0; border: none; border-top: 2px solid #eee;">
                
                <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold; margin-bottom: 2rem;">
                    <span>Total:</span>
                    <span class="cart-total"><?php echo formatPrice($total); ?></span>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <a href="checkout.php" class="btn btn-primary" style="text-align: center;">
                        <i class="fas fa-credit-card"></i> Proceed to Checkout
                    </a>
                    <a href="index.php" class="btn" style="text-align: center;">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1) return;
    
    const input = document.querySelector(`input[data-product-id="${productId}"]`);
    const maxStock = parseInt(input.dataset.maxStock);
    
    if (newQuantity > maxStock) {
        showNotification(`Only ${maxStock} items available in stock`, 'error');
        return;
    }
    
    fetch('ajax/update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Refresh to update totals
        } else {
            showNotification(data.message || 'Error updating cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating cart', 'error');
    });
}

// Coupon functions
function applyCoupon() {
    const couponCode = document.getElementById('couponCode').value.trim();
    if (!couponCode) {
        showNotification('Please enter a coupon code', 'error');
        return;
    }
    
    const cartTotal = <?php echo $subtotal; ?>;
    
    fetch('ajax/apply_coupon.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            coupon_code: couponCode,
            cart_total: cartTotal
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Invalid coupon code', 'error');
        }
    })
    .catch(error => {
        showNotification('Error applying coupon', 'error');
    });
}

function removeCoupon() {
    fetch('ajax/remove_coupon.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification('Error removing coupon', 'error');
        }
    })
    .catch(error => {
        showNotification('Error removing coupon', 'error');
    });
}
</script>

<style>
@media (max-width: 768px) {
    .container > div:nth-child(2) {
        grid-template-columns: 1fr;
    }
    
    .cart-table {
        overflow-x: auto;
    }
    
    .cart-table table {
        min-width: 600px;
    }
}
</style>

<?php include 'includes/footer.php'; ?>