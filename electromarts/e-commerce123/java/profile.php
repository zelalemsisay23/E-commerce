<?php
require_once 'includes/config.php';
requireLogin();

$success = '';
$error = '';

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_POST) {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($full_name) || empty($email)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        try {
            // Check if email is already taken by another user
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $_SESSION['user_id']]);
            if ($stmt->fetch()) {
                $error = 'Email address is already in use';
            } else {
                // Update basic info
                $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
                $stmt->execute([$full_name, $email, $phone, $address, $_SESSION['user_id']]);
                
                // Update password if provided
                if (!empty($new_password)) {
                    if (empty($current_password)) {
                        $error = 'Please enter your current password';
                    } elseif (!password_verify($current_password, $user['password'])) {
                        $error = 'Current password is incorrect';
                    } elseif (strlen($new_password) < 6) {
                        $error = 'New password must be at least 6 characters long';
                    } elseif ($new_password !== $confirm_password) {
                        $error = 'New passwords do not match';
                    } else {
                        $hashed_password = password_hash($new_password, HASH_ALGO);
                        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmt->execute([$hashed_password, $_SESSION['user_id']]);
                    }
                }
                
                if (!$error) {
                    $success = 'Profile updated successfully!';
                    // Refresh user data
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $user = $stmt->fetch();
                }
            }
        } catch (Exception $e) {
            $error = 'Error updating profile. Please try again.';
        }
    }
}

$page_title = 'My Profile';
include 'includes/header.php';
?>

<div class="container">
    <h1 style="margin-bottom: 2rem;">My Profile</h1>
    
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <!-- Profile Sidebar -->
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); height: fit-content;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: bold;">
                    <?php echo strtoupper(substr($user['full_name'], 0, 2)); ?>
                </div>
                <h3 style="color: #333; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p style="color: #666;">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="profile.php" class="btn btn-primary" style="text-align: center;">
                    <i class="fas fa-user"></i> Edit Profile
                </a>
                <a href="orders.php" class="btn" style="text-align: center;">
                    <i class="fas fa-shopping-bag"></i> My Orders
                </a>
                <a href="cart.php" class="btn" style="text-align: center;">
                    <i class="fas fa-shopping-cart"></i> Shopping Cart
                </a>
            </div>
        </div>
        
        <!-- Profile Form -->
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <h2 style="margin-bottom: 2rem;">Edit Profile Information</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" data-validate>
                <h3 style="margin-bottom: 1rem; color: #667eea;">Personal Information</h3>
                
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" required 
                           value="<?php echo htmlspecialchars($user['full_name']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3" 
                              placeholder="Enter your full address..."><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>
                
                <h3 style="margin: 2rem 0 1rem; color: #667eea;">Change Password</h3>
                <p style="color: #666; margin-bottom: 1rem; font-size: 0.9rem;">Leave blank if you don't want to change your password</p>
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password">
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password">
                    <small style="color: #666;">Minimum 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .container > div:nth-child(2) {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>