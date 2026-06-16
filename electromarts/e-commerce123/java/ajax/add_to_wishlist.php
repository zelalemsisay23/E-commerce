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
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
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
    
    // Check if already in wishlist
    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $product_id]);
    
    if ($stmt->fetch()) {
        // Remove from wishlist if already exists
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        
        // Get updated wishlist count
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $wishlist_count = $stmt->fetchColumn();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Removed from wishlist',
            'action' => 'removed',
            'wishlist_count' => $wishlist_count
        ]);
    } else {
        // Add to wishlist
        $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        
        // Get updated wishlist count
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $wishlist_count = $stmt->fetchColumn();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Added to wishlist',
            'action' => 'added',
            'wishlist_count' => $wishlist_count
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error updating wishlist']);
}
?>