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
$product_id = (int)($input['product_id'] ?? 0);
$quantity = (int)($input['quantity'] ?? 1);

if (!$product_id || $quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
    exit();
}

try {
    // Check if product exists and has stock
    $stmt = $pdo->prepare("SELECT stock_quantity FROM products WHERE id = ? AND status = 'active'");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }
    
    if ($product['stock_quantity'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
        exit();
    }
    
    // Check if item already in cart
    $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $product_id]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        $new_quantity = $existing['quantity'] + $quantity;
        if ($new_quantity > $product['stock_quantity']) {
            echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
            exit();
        }
        
        // Update existing cart item
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$new_quantity, $_SESSION['user_id'], $product_id]);
    } else {
        // Add new cart item
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $product_id, $quantity]);
    }
    
    // Get updated cart count
    $cart_count = getCartCount();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Product added to cart',
        'cart_count' => $cart_count
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error adding to cart']);
}
?>