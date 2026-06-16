<?php
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_POST) {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Redirect to intended page or home
            $redirect = $_GET['redirect'] ?? 'index.php';
            header('Location: ' . $redirect);
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    }
}

$page_title = 'Login';
include 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2 style="text-align: center; margin-bottom: 2rem; color: #333;">Login to ElectroMart</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" data-validate>
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </div>
        </form>
        
        <div style="text-align: center; margin-top: 2rem;">
            <p>Don't have an account? <a href="register.php" style="color: #667eea;">Register here</a></p>
            <p><a href="forgot-password.php" style="color: #666;">Forgot your password?</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>