<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'electromart');

// Site configuration
define('SITE_NAME', 'ElectroMart');
define('SITE_URL', 'http://localhost/electromart');
define('ADMIN_EMAIL', 'admin@electromart.com');

// Security
define('HASH_ALGO', PASSWORD_DEFAULT);
define('SESSION_TIMEOUT', 3600); // 1 hour

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Helper functions
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function formatPrice($price) {
    return '$' . number_format($price, 2);
}

function getCartCount() {
    global $pdo;
    if (!isLoggedIn()) return 0;
    
    $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetchColumn() ?: 0;
}
?>