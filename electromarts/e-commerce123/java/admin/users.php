<?php
require_once '../includes/config.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: index.php');
    exit();
}

$success = '';
$error = '';

// Handle user actions
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $success = 'User deleted successfully';
            } catch (Exception $e) {
                $error = 'Error deleting user: ' . $e->getMessage();
            }
        }
    }
}

// Get users with pagination
$page = (int)($_GET['page'] ?? 1);
$per_page = 20;
$offset = ($page - 1) * $per_page;

$search = sanitize($_GET['search'] ?? '');

$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(full_name LIKE ? OR email LIKE ? OR username LIKE ?)";
    $search_term = "%{$search}%";
    $params = array_merge($params, [$search_term, $search_term, $search_term]);
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count
$count_sql = "SELECT COUNT(*) FROM users {$where_clause}";
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total_users = $stmt->fetchColumn();
$total_pages = ceil($total_users / $per_page);

// Get users with order statistics
$sql = "
    SELECT u.*, 
           COUNT(o.id) as total_orders,
           COALESCE(SUM(o.total_amount), 0) as total_spent,
           MAX(o.created_at) as last_order_date
    FROM users u 
    LEFT JOIN orders o ON u.id = o.user_id
    {$where_clause}
    GROUP BY u.id
    ORDER BY u.created_at DESC 
    LIMIT {$per_page} OFFSET {$offset}
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();

$page_title = 'User Management';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - ElectroMart Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .admin-header { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .admin-nav { display: flex; gap: 1rem; margin-bottom: 2rem; }
        .admin-nav a { padding: 0.5rem 1rem; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }
        .admin-nav a.active { background: #333; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center; }
        .stat-number { font-size: 2rem; font-weight: bold; color: #667eea; }
        .user-table { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .user-table table { width: 100%; border-collapse: collapse; }
        .user-table th, .user-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        .user-table th { background: #f8f9fa; font-weight: bold; }
        .user-table tr:hover { background: #f8f9fa; }
        .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem; }
        .pagination a, .pagination span { padding: 0.5rem 1rem; border: 1px solid #ddd; text-decoration: none; }
        .pagination .current { background: #667eea; color: white; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Admin Header -->
        <div class="admin-header">
            <h1><i class="fas fa-users"></i> User Management</h1>
            <div class="admin-nav">
                <a href="orders.php">Orders</a>
                <a href="products.php">Products</a>
                <a href="users.php" class="active">Users</a>
                <a href="../index.php">View Store</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-grid">
            <?php
            $total_users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
            $new_users_today = $pdo->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()")->fetchColumn();
            $active_customers = $pdo->query("SELECT COUNT(DISTINCT user_id) FROM orders")->fetchColumn();
            ?>
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($total_users_count); ?></div>
                <div>Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($new_users_today); ?></div>
                <div>New Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($active_customers); ?></div>
                <div>Active Customers</div>
            </div>
        </div>

        <!-- Search -->
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 2rem;">
            <form method="GET" style="display: flex; gap: 1rem;">
                <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                <button type="submit" class="btn">Search</button>
                <?php if ($search): ?>
                    <a href="users.php" class="btn">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Users Table -->
        <div class="user-table">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Contact</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Last Order</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                                No users found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div class="user-avatar">
                                            <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($user['full_name']); ?></strong><br>
                                            <small>@<?php echo htmlspecialchars($user['username']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($user['email']); ?><br>
                                    <small><?php echo htmlspecialchars($user['phone'] ?? 'No phone'); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo $user['total_orders']; ?></strong> orders
                                </td>
                                <td>
                                    <strong><?php echo formatPrice($user['total_spent']); ?></strong>
                                </td>
                                <td>
                                    <?php if ($user['last_order_date']): ?>
                                        <?php echo date('M j, Y', strtotime($user['last_order_date'])); ?>
                                    <?php else: ?>
                                        <span style="color: #666;">Never</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                </td>
                                <td>
                                    <a href="orders.php?user_id=<?php echo $user['id']; ?>" class="btn btn-small" title="View Orders">
                                        <i class="fas fa-shopping-bag"></i>
                                    </a>
                                    <button onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')" class="btn btn-small" style="background: #dc3545;" title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function deleteUser(id, name) {
            if (confirm(`Are you sure you want to delete user "${name}"? This will also delete all their orders and data.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>