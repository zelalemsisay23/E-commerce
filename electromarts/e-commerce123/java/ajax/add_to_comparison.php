<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

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
    // Check if product exists
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ? AND status = 'active'");
    $stmt->execute([$product_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }
    
    // Initialize comparison array if not exists
    if (!isset($_SESSION['compare_products'])) {
        $_SESSION['compare_products'] = [];
    }
    
    // Check if already in comparison
    if (in_array($product_id, $_SESSION['compare_products'])) {
        echo json_encode(['success' => false, 'message' => 'Product already in comparison']);
        exit();
    }
    
    // Limit to 4 products for comparison
    if (count($_SESSION['compare_products']) >= 4) {
        echo json_encode(['success' => false, 'message' => 'Maximum 4 products can be compared. Remove some products first.']);
        exit();
    }
    
    // Add to comparison
    $_SESSION['compare_products'][] = $product_id;
    
    echo json_encode([
        'success' => true, 
        'message' => 'Product added to comparison',
        'compare_count' => count($_SESSION['compare_products'])
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error adding to comparison']);
}
?>