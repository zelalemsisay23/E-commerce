<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

$count = 0;
if (isLoggedIn()) {
    $count = getCartCount();
}

echo json_encode(['count' => $count]);
?>