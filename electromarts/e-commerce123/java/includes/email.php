<?php
// Enhanced Email notification system

class EmailNotifier {
    private $pdo;
    private $from_email;
    private $from_name;
    
    public function __construct($database) {
        $this->pdo = $database;
        $this->from_email = ADMIN_EMAIL;
        $this->from_name = SITE_NAME;
    }
    
    // Send welcome email to new users
    public function sendWelcomeEmail($user_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            if (!$user) return false;
            
            $subject = "Welcome to " . SITE_NAME . "!";
            $message = $this->getWelcomeEmailTemplate($user);
            
            return $this->sendEmail($user['email'], $subject, $message);
        } catch (Exception $e) {
            error_log("Welcome email error: " . $e->getMessage());
            return false;
        }
    }
    
    // Send order confirmation email
    public function sendOrderConfirmation($order_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT o.*, u.full_name, u.email 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?
            ");
            $stmt->execute([$order_id]);
            $order = $stmt->fetch();
            
            if (!$order) return false;
            
            // Get order items
            $stmt = $this->pdo->prepare("
                SELECT oi.*, p.name, p.brand 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?
            ");
            $stmt->execute([$order_id]);
            $items = $stmt->fetchAll();
            
            $subject = "Order Confirmation #" . $order_id;
            $message = $this->getOrderConfirmationTemplate($order, $items);
            
            return $this->sendEmail($order['email'], $subject, $message);
        } catch (Exception $e) {
            error_log("Order confirmation email error: " . $e->getMessage());
            return false;
        }
    }
    
    // Send order status update email
    public function sendOrderStatusUpdate($order_id, $new_status) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT o.*, u.full_name, u.email 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?
            ");
            $stmt->execute([$order_id]);
            $order = $stmt->fetch();
            
            if (!$order) return false;
            
            $subject = "Order #" . $order_id . " Status Update";
            $message = $this->getStatusUpdateTemplate($order, $new_status);
            
            return $this->sendEmail($order['email'], $subject, $message);
        } catch (Exception $e) {
            error_log("Status update email error: " . $e->getMessage());
            return false;
        }
    }
    
    // Send contact form notification
    public function sendContactNotification($name, $email, $subject, $message) {
        try {
            $email_subject = "New Contact Form Submission: " . $subject;
            $email_message = $this->getContactNotificationTemplate($name, $email, $subject, $message);
            
            return $this->sendEmail(ADMIN_EMAIL, $email_subject, $email_message);
        } catch (Exception $e) {
            error_log("Contact notification error: " . $e->getMessage());
            return false;
        }
    }
    
    // Core email sending function
    private function sendEmail($to, $subject, $message) {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . $this->from_name . ' <' . $this->from_email . '>',
            'Reply-To: ' . $this->from_email,
            'X-Mailer: PHP/' . phpversion()
        ];
        
        return mail($to, $subject, $message, implode("\r\n", $headers));
    }
    
    // Email templates
    private function getWelcomeEmailTemplate($user) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: white; padding: 30px; border: 1px solid #ddd; }
                .footer { background: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; color: #666; }
                .btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Welcome to ' . SITE_NAME . '!</h1>
                </div>
                <div class="content">
                    <h2>Hello ' . htmlspecialchars($user['full_name']) . ',</h2>
                    <p>Thank you for joining ' . SITE_NAME . '! We\'re excited to have you as part of our community.</p>
                    
                    <p>Your account details:</p>
                    <ul>
                        <li><strong>Username:</strong> ' . htmlspecialchars($user['username']) . '</li>
                        <li><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</li>
                    </ul>
                    
                    <p>Start exploring our products and enjoy shopping!</p>
                    
                    <p style="text-align: center;">
                        <a href="' . SITE_URL . '" class="btn">Start Shopping Now</a>
                    </p>
                    
                    <p>Happy shopping!<br>The ' . SITE_NAME . ' Team</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ' . SITE_NAME . '. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getOrderConfirmationTemplate($order, $items) {
        $items_html = '';
        foreach ($items as $item) {
            $items_html .= '
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">' . htmlspecialchars($item['name']) . '</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: center;">' . $item['quantity'] . '</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">$' . number_format($item['price'], 2) . '</td>
                </tr>';
        }
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: white; padding: 30px; border: 1px solid #ddd; }
                .footer { background: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; color: #666; }
                .order-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .order-table th { background: #f8f9fa; padding: 10px; text-align: left; }
                .btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Order Confirmation</h1>
                    <h2>Order #' . $order['id'] . '</h2>
                </div>
                <div class="content">
                    <h2>Thank you, ' . htmlspecialchars($order['full_name']) . '!</h2>
                    <p>Your order has been received and is being processed.</p>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                        <p><strong>Order #:</strong> ' . $order['id'] . '</p>
                        <p><strong>Date:</strong> ' . date('F j, Y', strtotime($order['created_at'])) . '</p>
                        <p><strong>Total:</strong> $' . number_format($order['total_amount'], 2) . '</p>
                    </div>
                    
                    <h3>Order Items</h3>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th style="text-align: center;">Quantity</th>
                                <th style="text-align: right;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $items_html . '
                        </tbody>
                    </table>
                    
                    <p style="text-align: center;">
                        <a href="' . SITE_URL . '/order-details.php?id=' . $order['id'] . '" class="btn">Track Your Order</a>
                    </p>
                    
                    <p>Thank you for shopping with us!</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ' . SITE_NAME . '. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getStatusUpdateTemplate($order, $new_status) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: white; padding: 30px; border: 1px solid #ddd; }
                .footer { background: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; color: #666; }
                .btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Order Status Update</h1>
                    <h2>Order #' . $order['id'] . '</h2>
                </div>
                <div class="content">
                    <h2>Hello ' . htmlspecialchars($order['full_name']) . ',</h2>
                    
                    <p>Your order status has been updated to: <strong>' . ucfirst($new_status) . '</strong></p>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                        <p><strong>Order #:</strong> ' . $order['id'] . '</p>
                        <p><strong>Total:</strong> $' . number_format($order['total_amount'], 2) . '</p>
                        <p><strong>Status:</strong> ' . ucfirst($new_status) . '</p>
                    </div>
                    
                    <p style="text-align: center;">
                        <a href="' . SITE_URL . '/order-details.php?id=' . $order['id'] . '" class="btn">View Order Details</a>
                    </p>
                    
                    <p>Thank you for choosing ' . SITE_NAME . '!</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ' . SITE_NAME . '. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getContactNotificationTemplate($name, $email, $subject, $message) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #667eea; color: white; padding: 20px; text-align: center; }
                .content { background: white; padding: 20px; border: 1px solid #ddd; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>New Contact Form Submission</h1>
                </div>
                <div class="content">
                    <p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>
                    <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
                    <p><strong>Subject:</strong> ' . htmlspecialchars($subject) . '</p>
                    
                    <h3>Message:</h3>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
                        <p>' . nl2br(htmlspecialchars($message)) . '</p>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }
}
?>