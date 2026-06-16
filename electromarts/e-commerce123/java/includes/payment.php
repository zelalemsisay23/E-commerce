<?php
// Payment Processing Functions

class PaymentProcessor {
    private $pdo;
    
    public function __construct($database) {
        $this->pdo = $database;
    }
    
    // Process Credit Card Payment (Simulation)
    public function processCreditCard($cardData, $amount, $orderId) {
        // In a real application, you would integrate with payment gateways like:
        // Stripe, PayPal, Square, etc.
        
        // Simulate payment processing
        $cardNumber = preg_replace('/\s+/', '', $cardData['number']);
        $expiryDate = $cardData['expiry'];
        $cvv = $cardData['cvv'];
        
        // Basic validation
        if (!$this->validateCreditCard($cardNumber, $expiryDate, $cvv)) {
            return [
                'success' => false,
                'message' => 'Invalid credit card information'
            ];
        }
        
        // Simulate processing delay
        sleep(1);
        
        // Simulate success/failure (90% success rate)
        $success = rand(1, 10) <= 9;
        
        if ($success) {
            // Generate transaction ID
            $transactionId = 'TXN_' . time() . '_' . rand(1000, 9999);
            
            // Log transaction
            $this->logTransaction($orderId, 'credit_card', $amount, $transactionId, 'completed');
            
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'message' => 'Payment processed successfully'
            ];
        } else {
            // Log failed transaction
            $this->logTransaction($orderId, 'credit_card', $amount, null, 'failed');
            
            return [
                'success' => false,
                'message' => 'Payment processing failed. Please try again.'
            ];
        }
    }
    
    // Process PayPal Payment (Simulation)
    public function processPayPal($amount, $orderId) {
        // Simulate PayPal processing
        sleep(1);
        
        $success = rand(1, 10) <= 9;
        
        if ($success) {
            $transactionId = 'PP_' . time() . '_' . rand(1000, 9999);
            $this->logTransaction($orderId, 'paypal', $amount, $transactionId, 'completed');
            
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'message' => 'PayPal payment completed successfully'
            ];
        } else {
            $this->logTransaction($orderId, 'paypal', $amount, null, 'failed');
            
            return [
                'success' => false,
                'message' => 'PayPal payment failed. Please try again.'
            ];
        }
    }
    
    // Validate Credit Card
    private function validateCreditCard($cardNumber, $expiryDate, $cvv) {
        // Basic validation
        if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
            return false;
        }
        
        if (!preg_match('/^\d{2}\/\d{2}$/', $expiryDate)) {
            return false;
        }
        
        if (strlen($cvv) < 3 || strlen($cvv) > 4) {
            return false;
        }
        
        // Check expiry date
        list($month, $year) = explode('/', $expiryDate);
        $currentYear = date('y');
        $currentMonth = date('m');
        
        if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
            return false;
        }
        
        return true;
    }
    
    // Log Transaction
    private function logTransaction($orderId, $method, $amount, $transactionId, $status) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO payment_transactions 
                (order_id, payment_method, amount, transaction_id, status, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$orderId, $method, $amount, $transactionId, $status]);
        } catch (Exception $e) {
            error_log("Payment logging error: " . $e->getMessage());
        }
    }
    
    // Apply Coupon
    public function applyCoupon($couponCode, $orderAmount) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM coupons 
                WHERE code = ? AND status = 'active' 
                AND (expires_at IS NULL OR expires_at > NOW())
                AND (max_uses IS NULL OR used_count < max_uses)
                AND min_order_amount <= ?
            ");
            $stmt->execute([$couponCode, $orderAmount]);
            $coupon = $stmt->fetch();
            
            if (!$coupon) {
                return [
                    'success' => false,
                    'message' => 'Invalid or expired coupon code'
                ];
            }
            
            // Calculate discount
            if ($coupon['discount_type'] === 'percentage') {
                $discount = ($orderAmount * $coupon['discount_value']) / 100;
            } else {
                $discount = $coupon['discount_value'];
            }
            
            // Ensure discount doesn't exceed order amount
            $discount = min($discount, $orderAmount);
            
            return [
                'success' => true,
                'discount' => $discount,
                'coupon_id' => $coupon['id'],
                'message' => 'Coupon applied successfully'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error applying coupon'
            ];
        }
    }
    
    // Update coupon usage
    public function updateCouponUsage($couponId) {
        try {
            $stmt = $this->pdo->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?");
            $stmt->execute([$couponId]);
        } catch (Exception $e) {
            error_log("Coupon usage update error: " . $e->getMessage());
        }
    }
}

// Email Notification Functions
class EmailNotifier {
    private $pdo;
    private $fromEmail;
    private $fromName;
    
    public function __construct($database) {
        $this->pdo = $database;
        $this->fromEmail = ADMIN_EMAIL;
        $this->fromName = SITE_NAME;
    }
    
    // Send Order Confirmation Email
    public function sendOrderConfirmation($orderId) {
        try {
            // Get order details
            $stmt = $this->pdo->prepare("
                SELECT o.*, u.full_name, u.email 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?
            ");
            $stmt->execute([$orderId]);
            $order = $stmt->fetch();
            
            if (!$order) return false;
            
            // Get order items
            $stmt = $this->pdo->prepare("
                SELECT oi.*, p.name, p.image 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?
            ");
            $stmt->execute([$orderId]);
            $items = $stmt->fetchAll();
            
            $subject = "Order Confirmation - Order #" . str_pad($orderId, 6, '0', STR_PAD_LEFT);
            $message = $this->buildOrderConfirmationEmail($order, $items);
            
            return $this->sendEmail($order['email'], $subject, $message);
            
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return false;
        }
    }
    
    // Send Order Status Update
    public function sendOrderStatusUpdate($orderId, $newStatus) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT o.*, u.full_name, u.email 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?
            ");
            $stmt->execute([$orderId]);
            $order = $stmt->fetch();
            
            if (!$order) return false;
            
            $subject = "Order Update - Order #" . str_pad($orderId, 6, '0', STR_PAD_LEFT);
            $message = $this->buildOrderStatusEmail($order, $newStatus);
            
            return $this->sendEmail($order['email'], $subject, $message);
            
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return false;
        }
    }
    
    // Build Order Confirmation Email
    private function buildOrderConfirmationEmail($order, $items) {
        $orderNumber = str_pad($order['id'], 6, '0', STR_PAD_LEFT);
        $itemsList = '';
        
        foreach ($items as $item) {
            $itemsList .= "
                <tr>
                    <td>{$item['name']}</td>
                    <td>{$item['quantity']}</td>
                    <td>" . formatPrice($item['price']) . "</td>
                    <td>" . formatPrice($item['price'] * $item['quantity']) . "</td>
                </tr>
            ";
        }
        
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .order-details { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background: #f5f5f5; }
                .total { font-weight: bold; font-size: 1.2em; }
                .footer { text-align: center; padding: 20px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Order Confirmation</h1>
                    <p>Thank you for your order!</p>
                </div>
                
                <div class='content'>
                    <p>Dear {$order['full_name']},</p>
                    <p>We've received your order and it's being processed. Here are your order details:</p>
                    
                    <div class='order-details'>
                        <h3>Order #{$orderNumber}</h3>
                        <p><strong>Order Date:</strong> " . date('F j, Y g:i A', strtotime($order['created_at'])) . "</p>
                        <p><strong>Payment Method:</strong> " . ucwords(str_replace('_', ' ', $order['payment_method'])) . "</p>
                        
                        <h4>Items Ordered:</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$itemsList}
                            </tbody>
                        </table>
                        
                        <p class='total'>Total Amount: " . formatPrice($order['total_amount']) . "</p>
                        
                        <h4>Shipping Address:</h4>
                        <p>" . nl2br(htmlspecialchars($order['shipping_address'])) . "</p>
                    </div>
                    
                    <p>We'll send you another email when your order ships. You can track your order status in your account.</p>
                </div>
                
                <div class='footer'>
                    <p>Thank you for shopping with ElectroMart!</p>
                    <p>If you have any questions, please contact us at " . ADMIN_EMAIL . "</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    // Build Order Status Email
    private function buildOrderStatusEmail($order, $status) {
        $orderNumber = str_pad($order['id'], 6, '0', STR_PAD_LEFT);
        $statusMessages = [
            'processing' => 'Your order is being processed and will ship soon.',
            'shipped' => 'Great news! Your order has been shipped and is on its way.',
            'delivered' => 'Your order has been delivered. We hope you enjoy your purchase!',
            'cancelled' => 'Your order has been cancelled. If you have any questions, please contact us.'
        ];
        
        $message = $statusMessages[$status] ?? 'Your order status has been updated.';
        
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .status-update { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; text-align: center; }
                .status { font-size: 1.5em; font-weight: bold; color: #667eea; margin: 10px 0; }
                .footer { text-align: center; padding: 20px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Order Update</h1>
                </div>
                
                <div class='content'>
                    <p>Dear {$order['full_name']},</p>
                    
                    <div class='status-update'>
                        <h3>Order #{$orderNumber}</h3>
                        <div class='status'>" . ucfirst($status) . "</div>
                        <p>{$message}</p>
                    </div>
                    
                    <p>You can view your complete order details and track your order in your account.</p>
                </div>
                
                <div class='footer'>
                    <p>Thank you for shopping with ElectroMart!</p>
                    <p>If you have any questions, please contact us at " . ADMIN_EMAIL . "</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    // Send Email (using PHP mail function - in production use proper email service)
    private function sendEmail($to, $subject, $message) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: {$this->fromName} <{$this->fromEmail}>" . "\r\n";
        
        // In production, use proper email service like SendGrid, Mailgun, etc.
        return mail($to, $subject, $message, $headers);
    }
}
?>