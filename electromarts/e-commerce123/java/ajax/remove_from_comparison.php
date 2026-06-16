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
    // Initialize comparison array if not exists
    if (!isset($_SESSION['compare_products'])) {
        $_SESSION['compare_products'] = [];
    }
    
    // Remove from comparison
    $key = array_search($product_id, $_SESSION['compare_products']);
    if ($key !== false) {
        unset($_SESSION['compare_products'][$key]);
        $_SESSION['compare_products'] = array_values($_SESSION['compare_products']); // Reindex array
        
        echo json_encode([
            'success' => true, 
            'message' => 'Product removed from comparison',
            'compare_count' => count($_SESSION['compare_products'])
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not in comparison']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error removing from comparison']);
}
?>