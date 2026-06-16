<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php">
                        <i class="fas fa-bolt"></i>
                        <span>ElectroMart</span>
                    </a>
                </div>
                
                <nav class="nav">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">Categories <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <?php
                                $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
                                while ($category = $stmt->fetch()) {
                                    echo "<li><a href='category.php?id={$category['id']}'>{$category['name']}</a></li>";
                                }
                                ?>
                            </ul>
                        </li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <div class="search-box">
                        <form action="search.php" method="GET">
                            <input type="text" name="q" placeholder="Search products..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                    
                    <div class="user-menu">
                        <?php if (isLoggedIn()): ?>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle">
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="profile.php">Profile</a></li>
                                    <li><a href="orders.php">My Orders</a></li>
                                    <li><a href="wishlist.php">Wishlist</a></li>
                                    <li><a href="track-order.php">Track Order</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (isLoggedIn()): ?>
                        <div class="wishlist-icon">
                            <a href="wishlist.php">
                                <i class="fas fa-heart"></i>
                                <?php
                                try {
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
                                    $stmt->execute([$_SESSION['user_id']]);
                                    $wishlist_count = $stmt->fetchColumn();
                                    if ($wishlist_count > 0):
                                ?>
                                    <span class="wishlist-count"><?php echo $wishlist_count; ?></span>
                                <?php 
                                    endif;
                                } catch (PDOException $e) {
                                    // Wishlist table doesn't exist yet - ignore error
                                }
                                ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="cart-icon">
                        <a href="cart.php">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo getCartCount(); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <main class="main-content">