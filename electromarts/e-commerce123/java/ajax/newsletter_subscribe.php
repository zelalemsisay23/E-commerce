<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$email = sanitize($input['email'] ?? '');

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Please enter your email address']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit();
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id, status FROM newsletter WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        if ($existing['status'] === 'active') {
            echo json_encode(['success' => false, 'message' => 'You are already subscribed to our newsletter']);
            exit();
        } else {
            // Reactivate subscription
            $stmt = $pdo->prepare("UPDATE newsletter SET status = 'active', subscribed_at = NOW() WHERE email = ?");
            $stmt->execute([$email]);
            echo json_encode(['success' => true, 'message' => 'Welcome back! Your subscription has been reactivated.']);
            exit();
        }
    }
    
    // Add new subscription
    $stmt = $pdo->prepare("INSERT INTO newsletter (email) VALUES (?)");
    $stmt->execute([$email]);
    
    echo json_encode(['success' => true, 'message' => 'Thank you for subscribing! You will receive our latest updates and offers.']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error subscribing to newsletter. Please try again.']);
}
?>