<?php
require_once 'includes/config.php';

$success = '';
$error = '';

if ($_POST) {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        // In a real application, you would send an email or save to database
        // Send email notification to admin
        require_once 'includes/email.php';
        $email_notifier = new EmailNotifier($pdo);
        $email_notifier->sendContactNotification($name, $email, $subject, $message);
        
        $success = 'Thank you for your message! We will get back to you within 24 hours.';
        
        // Clear form data
        $_POST = [];
    }
}

$page_title = 'Contact Us';
include 'includes/header.php';
?>

<div class="container">
    <!-- Hero Section -->
    <section style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; padding: 4rem 2rem; border-radius: 15px; margin-bottom: 3rem;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">Contact Us</h1>
        <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto;">We'd love to hear from you. Get in touch with our team for any questions or support.</p>
    </section>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; margin-bottom: 4rem;">
        <!-- Contact Form -->
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h2 style="color: #333; margin-bottom: 2rem;">Send us a Message</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" data-validate>
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="General Inquiry" <?php echo ($_POST['subject'] ?? '') === 'General Inquiry' ? 'selected' : ''; ?>>General Inquiry</option>
                        <option value="Product Support" <?php echo ($_POST['subject'] ?? '') === 'Product Support' ? 'selected' : ''; ?>>Product Support</option>
                        <option value="Order Issue" <?php echo ($_POST['subject'] ?? '') === 'Order Issue' ? 'selected' : ''; ?>>Order Issue</option>
                        <option value="Return/Refund" <?php echo ($_POST['subject'] ?? '') === 'Return/Refund' ? 'selected' : ''; ?>>Return/Refund</option>
                        <option value="Technical Support" <?php echo ($_POST['subject'] ?? '') === 'Technical Support' ? 'selected' : ''; ?>>Technical Support</option>
                        <option value="Partnership" <?php echo ($_POST['subject'] ?? '') === 'Partnership' ? 'selected' : ''; ?>>Partnership</option>
                        <option value="Other" <?php echo ($_POST['subject'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="6" required 
                              placeholder="Please describe your inquiry in detail..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Contact Information -->
        <div>
            <!-- Contact Details -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <h3 style="color: #333; margin-bottom: 1.5rem;">Get in Touch</h3>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <i class="fas fa-map-marker-alt" style="font-size: 1.5rem; color: #667eea; width: 30px;"></i>
                    <div>
                        <strong style="color: #333;">Address</strong><br>
                        <span style="color: #666;">123 Tech Street<br>Ethiopia AddisAbeba, kombolcha</span>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <i class="fas fa-phone" style="font-size: 1.5rem; color: #667eea; width: 30px;"></i>
                    <div>
                        <strong style="color: #333;">Phone</strong><br>
                        <span style="color: #666;">+251-989463293</span>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <i class="fas fa-envelope" style="font-size: 1.5rem; color: #667eea; width: 30px;"></i>
                    <div>
                        <strong style="color: #333;">Email</strong><br>
                        <span style="color: #666;">seidali6427@gmail.com</span>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <i class="fas fa-clock" style="font-size: 1.5rem; color: #667eea; width: 30px;"></i>
                    <div>
                        <strong style="color: #333;">Business Hours</strong><br>
                        <span style="color: #666;">Mon-Fri: 9AM-6PM PST<br>Sat-Sun: 10AM-4PM PST</span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <h3 style="color: #333; margin-bottom: 1.5rem;">Quick Help</h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <a href="#" style="display: flex; align-items: center; gap: 1rem; text-decoration: none; color: #666; padding: 0.5rem; border-radius: 5px; transition: background 0.3s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-question-circle" style="color: #667eea;"></i>
                        <span>FAQ</span>
                    </a>
                    
                    <a href="#" style="display: flex; align-items: center; gap: 1rem; text-decoration: none; color: #666; padding: 0.5rem; border-radius: 5px; transition: background 0.3s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-shipping-fast" style="color: #667eea;"></i>
                        <span>Shipping Info</span>
                    </a>
                    
                    <a href="#" style="display: flex; align-items: center; gap: 1rem; text-decoration: none; color: #666; padding: 0.5rem; border-radius: 5px; transition: background 0.3s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-undo" style="color: #667eea;"></i>
                        <span>Returns</span>
                    </a>
                    
                    <a href="#" style="display: flex; align-items: center; gap: 1rem; text-decoration: none; color: #666; padding: 0.5rem; border-radius: 5px; transition: background 0.3s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-shield-alt" style="color: #667eea;"></i>
                        <span>Warranty</span>
                    </a>
                    
                    <a href="#" style="display: flex; align-items: center; gap: 1rem; text-decoration: none; color: #666; padding: 0.5rem; border-radius: 5px; transition: background 0.3s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-truck" style="color: #667eea;"></i>
                        <span>Track Order</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Support Options -->
    <section style="background: #f8f9fa; padding: 3rem 2rem; border-radius: 15px; margin-bottom: 4rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="color: #333; margin-bottom: 1rem;">Other Ways to Reach Us</h2>
            <p style="color: #666;">Choose the method that works best for you</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="background: white; padding: 2rem; border-radius: 10px; text-align: center; box-shadow: 0 3px 15px rgba(0,0,0,0.1);">
                <i class="fas fa-comments" style="font-size: 2.5rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4 style="color: #333; margin-bottom: 1rem;">Live Chat</h4>
                <p style="color: #666; margin-bottom: 1rem;">Get instant help from our support team</p>
                <button class="btn btn-primary" style="width: 100%;">Start Chat</button>
            </div>
            
            <div style="background: white; padding: 2rem; border-radius: 10px; text-align: center; box-shadow: 0 3px 15px rgba(0,0,0,0.1);">
                <i class="fab fa-whatsapp" style="font-size: 2.5rem; color: #25d366; margin-bottom: 1rem;"></i>
                <h4 style="color: #333; margin-bottom: 1rem;">WhatsApp</h4>
                <p style="color: #666; margin-bottom: 1rem;">Message us on WhatsApp for quick support</p>
                <button class="btn" style="width: 100%; background: #25d366; color: white;">Message Us</button>
            </div>
            
            <div style="background: white; padding: 2rem; border-radius: 10px; text-align: center; box-shadow: 0 3px 15px rgba(0,0,0,0.1);">
                <i class="fas fa-video" style="font-size: 2.5rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4 style="color: #333; margin-bottom: 1rem;">Video Call</h4>
                <p style="color: #666; margin-bottom: 1rem;">Schedule a video call with our experts</p>
                <button class="btn btn-primary" style="width: 100%;">Schedule Call</button>
            </div>
        </div>
    </section>
</div>

<style>
@media (max-width: 768px) {
    .container > div:nth-child(2) {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .container section:first-child h1 {
        font-size: 2rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>