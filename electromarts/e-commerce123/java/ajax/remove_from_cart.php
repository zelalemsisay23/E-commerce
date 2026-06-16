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

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit();
}

try {
    // Remove cart item
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $product_id]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit();
    }
    
    // Get updated cart count
    $cart_count = getCartCount();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Item removed from cart',
        'cart_count' => $cart_count
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error removing item']);
}
?>