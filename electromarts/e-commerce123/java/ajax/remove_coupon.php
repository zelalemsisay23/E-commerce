<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

// Remove coupon from session
unset($_SESSION['applied_coupon']);

echo json_encode([
    'success' => true,
    'message' => 'Coupon removed successfully'
]);
?>