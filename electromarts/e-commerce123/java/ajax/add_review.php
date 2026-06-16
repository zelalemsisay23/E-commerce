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
$rating = (int)($input['rating'] ?? 0);
$title = trim($input['title'] ?? '');
$comment = trim($input['comment'] ?? '');

// Validation
if (!$product_id || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID or rating']);
    exit();
}

if (empty($title) || empty($comment)) {
    echo json_encode(['success' => false, 'message' => 'Title and comment are required']);
    exit();
}

if (strlen($title) > 200 || strlen($comment) > 1000) {
    echo json_encode(['success' => false, 'message' => 'Title or comment too long']);
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
    
    // Check if user already reviewed this product
    $stmt = $pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $product_id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'You have already reviewed this product']);
        exit();
    }
    
    // Add review
    $stmt = $pdo->prepare("
        INSERT INTO reviews (user_id, product_id, rating, title, comment) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$_SESSION['user_id'], $product_id, $rating, $title, $comment]);
    
    // Get updated review stats
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_reviews,
            AVG(rating) as avg_rating
        FROM reviews 
        WHERE product_id = ?
    ");
    $stmt->execute([$product_id]);
    $stats = $stmt->fetch();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Review added successfully',
        'total_reviews' => $stats['total_reviews'],
        'avg_rating' => round($stats['avg_rating'], 1)
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error adding review']);
}
?>