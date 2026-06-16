<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$coupon_code = strtoupper(trim($input['coupon_code'] ?? ''));
$cart_total = (float)($input['cart_total'] ?? 0);

if (empty($coupon_code)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a coupon code']);
    exit();
}

if ($cart_total <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid cart total']);
    exit();
}

try {
    // Get coupon details
    $stmt = $pdo->prepare("
        SELECT * FROM coupons 
        WHERE code = ? AND status = 'active' 
        AND (expires_at IS NULL OR expires_at > NOW())
        AND (max_uses IS NULL OR used_count < max_uses)
    ");
    $stmt->execute([$coupon_code]);
    $coupon = $stmt->fetch();
    
    if (!$coupon) {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired coupon code']);
        exit();
    }
    
    // Check minimum order amount
    if ($cart_total < $coupon['min_order_amount']) {
        echo json_encode([
            'success' => false, 
            'message' => 'Minimum order amount of ' . formatPrice($coupon['min_order_amount']) . ' required for this coupon'
        ]);
        exit();
    }
    
    // Check if user already used this coupon
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM orders 
        WHERE user_id = ? AND coupon_code = ? AND status != 'cancelled'
    ");
    $stmt->execute([$_SESSION['user_id'], $coupon_code]);
    $usage_count = $stmt->fetchColumn();
    
    if ($usage_count > 0) {
        echo json_encode(['success' => false, 'message' => 'You have already used this coupon']);
        exit();
    }
    
    // Calculate discount
    $discount_amount = 0;
    if ($coupon['discount_type'] === 'percentage') {
        $discount_amount = ($cart_total * $coupon['discount_value']) / 100;
    } else {
        $discount_amount = $coupon['discount_value'];
    }
    
    // Ensure discount doesn't exceed cart total
    $discount_amount = min($discount_amount, $cart_total);
    
    // Store coupon in session
    $_SESSION['applied_coupon'] = [
        'code' => $coupon['code'],
        'discount_type' => $coupon['discount_type'],
        'discount_value' => $coupon['discount_value'],
        'discount_amount' => $discount_amount
    ];
    
    echo json_encode([
        'success' => true,
        'message' => 'Coupon applied successfully!',
        'discount_amount' => $discount_amount,
        'discount_text' => $coupon['discount_type'] === 'percentage' 
            ? $coupon['discount_value'] . '% off' 
            : formatPrice($coupon['discount_value']) . ' off'
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error applying coupon']);
}
?>