<?php
    require_once '../includes/config.php';

    // Destroy admin session
    unset($_SESSION['admin_logged_in']);
    session_destroy();

    // Redirect to admin login
    header('Location: index.php');
    exit();
?>