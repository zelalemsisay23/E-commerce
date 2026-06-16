<?php
require_once 'includes/config.php';
$page_title = 'Help Center';
include 'includes/header.php';
?>

<div class="container">
    <!-- Hero Section -->
    <section style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; padding: 4rem 2rem; border-radius: 15px; margin-bottom: 3rem;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">Help Center</h1>
        <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto;">Find answers to common questions and get the help you need.</p>
    </section>

    <!-- Search FAQ -->
    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <div style="max-width: 600px; margin: 0 auto;">
            <h2 style="text-align: center; margin-bottom: 2rem;">Search FAQ</h2>
            <div style="position: relative;">
                <input type="text" id="faq-search" placeholder="Search for help topics..." 
                       style="width: 100%; padding: 1rem 1rem 1rem 3rem; border: 2px solid #eee; border-radius: 25px; font-size: 1.1rem;">
                <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #666;"></i>
            </div>
        </div>
    </div>

    <!-- FAQ Categories -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
        <div class="faq-category" data-category="orders">
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: transform 0.3s;" onclick="showCategory('orders')">
                <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3>Orders & Shipping</h3>
                <p style="color: #666;">Questions about placing orders, tracking, and shipping information.</p>
            </div>
        </div>
        
        <div class="faq-category" data-category="payments">
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: transform 0.3s;" onclick="showCategory('payments')">
                <i class="fas fa-credit-card" style="font-size: 3rem; color: #28a745; margin-bottom: 1rem;"></i>
                <h3>Payments & Billing</h3>
                <p style="color: #666;">Payment methods, billing issues, and refund information.</p>
            </div>
        </div>
        
        <div class="faq-category" data-category="products">
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: transform 0.3s;" onclick="showCategory('products')">
                <i class="fas fa-box" style="font-size: 3rem; color: #ffc107; margin-bottom: 1rem;"></i>
                <h3>Products & Warranty</h3>
                <p style="color: #666;">Product information, specifications, and warranty details.</p>
            </div>
        </div>
        
        <div class="faq-category" data-category="account">
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: transform 0.3s;" onclick="showCategory('account')">
                <i class="fas fa-user" style="font-size: 3rem; color: #17a2b8; margin-bottom: 1rem;"></i>
                <h3>Account & Profile</h3>
                <p style="color: #666;">Managing your account, profile settings, and security.</p>
            </div>
        </div>
    </div>

    <!-- FAQ Content -->
    <div id="faq-content" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 2rem;">Frequently Asked Questions</h2>
        
        <!-- Orders & Shipping -->
        <div class="faq-section" data-category="orders">
            <h3 style="color: #667eea; margin-bottom: 1.5rem;">Orders & Shipping</h3>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>How do I track my order?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>You can track your order by logging into your account and visiting the "My Orders" section. You'll receive an email with tracking information once your order ships. You can also use the tracking number provided to check status on our shipping partner's website.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>What are your shipping options and costs?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>We offer free standard shipping on orders over $50. For orders under $50, standard shipping costs $9.99. We also offer express shipping for $19.99. Standard shipping takes 3-5 business days, while express shipping takes 1-2 business days.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>Can I change or cancel my order?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>You can change or cancel your order within 1 hour of placing it, as long as it hasn't been processed yet. After that, please contact our customer service team for assistance. Once an order has shipped, it cannot be cancelled, but you can return it following our return policy.</p>
                </div>
            </div>
        </div>
        
        <!-- Payments & Billing -->
        <div class="faq-section" data-category="payments" style="display: none;">
            <h3 style="color: #28a745; margin-bottom: 1.5rem;">Payments & Billing</h3>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>What payment methods do you accept?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>We accept all major credit cards (Visa, MasterCard, American Express, Discover), PayPal, and cash on delivery for eligible orders. All payments are processed securely using industry-standard encryption.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>How do refunds work?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Refunds are processed back to your original payment method within 5-7 business days after we receive your returned item. For PayPal payments, refunds may take up to 10 business days. You'll receive an email confirmation once the refund is processed.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>Do you offer payment plans or financing?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Currently, we don't offer payment plans or financing options. However, you can use PayPal Pay in 4 or other third-party financing services at checkout if available for your order.</p>
                </div>
            </div>
        </div>
        
        <!-- Products & Warranty -->
        <div class="faq-section" data-category="products" style="display: none;">
            <h3 style="color: #ffc107; margin-bottom: 1.5rem;">Products & Warranty</h3>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>Are all products brand new and authentic?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, all products sold on ElectroMart are 100% authentic and brand new. We work directly with manufacturers and authorized distributors to ensure authenticity. All products come with original packaging and manufacturer warranties.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>What is your return policy?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>We offer a 30-day return policy for most items. Products must be in original condition with all accessories and packaging. Electronics must be unopened unless defective. Return shipping is free for defective items, otherwise customer pays return shipping.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>How do I claim warranty service?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>For warranty claims, contact our customer service team with your order number and description of the issue. We'll guide you through the warranty process, which may involve manufacturer direct service or replacement through us, depending on the product and issue.</p>
                </div>
            </div>
        </div>
        
        <!-- Account & Profile -->
        <div class="faq-section" data-category="account" style="display: none;">
            <h3 style="color: #17a2b8; margin-bottom: 1.5rem;">Account & Profile</h3>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>How do I create an account?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Click "Register" in the top navigation, fill out the required information, and verify your email address. You can also create an account during checkout. Having an account allows you to track orders, save addresses, and manage your wishlist.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>I forgot my password. How do I reset it?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Click "Login" then "Forgot Password" and enter your email address. We'll send you a password reset link. If you don't receive the email within a few minutes, check your spam folder or contact customer service.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h4>How do I update my account information?</h4>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Log into your account and go to "My Profile" to update your personal information, shipping addresses, and password. Make sure to save your changes before leaving the page.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Support -->
    <div style="background: #f8f9fa; padding: 3rem 2rem; border-radius: 15px; margin-top: 3rem; text-align: center;">
        <h2 style="margin-bottom: 1rem;">Still Need Help?</h2>
        <p style="color: #666; margin-bottom: 2rem;">Can't find what you're looking for? Our customer service team is here to help.</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div>
                <i class="fas fa-envelope" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4>Email Support</h4>
                <p style="color: #666;">seidali6427@gmail.com</p>
                <p style="color: #666; font-size: 0.9rem;">Response within 24 hours</p>
            </div>
            
            <div>
                <i class="fas fa-phone" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4>Phone Support</h4>
                <p style="color: #666;">+251-989463293</p>
                <p style="color: #666; font-size: 0.9rem;">Mon-Fri 9AM-6PM PST</p>
            </div>
            
            <div>
                <i class="fas fa-comments" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4>Live Chat</h4>
                <p style="color: #666;">Available on our website</p>
                <p style="color: #666; font-size: 0.9rem;">Mon-Fri 9AM-6PM PST</p>
            </div>
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="contact.php" class="btn btn-primary">Contact Us</a>
        </div>
    </div>
</div>

<script>
// FAQ Search functionality
document.getElementById('faq-search').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question h4').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = searchTerm ? 'none' : 'block';
        }
    });
    
    // Show all sections if searching
    if (searchTerm) {
        document.querySelectorAll('.faq-section').forEach(section => {
            section.style.display = 'block';
        });
    }
});

// Category filtering
function showCategory(category) {
    document.querySelectorAll('.faq-section').forEach(section => {
        section.style.display = section.dataset.category === category ? 'block' : 'none';
    });
    
    // Clear search
    document.getElementById('faq-search').value = '';
    
    // Highlight selected category
    document.querySelectorAll('.faq-category div').forEach(cat => {
        cat.style.transform = 'scale(1)';
        cat.style.boxShadow = '0 5px 20px rgba(0,0,0,0.1)';
    });
    
    const selectedCategory = document.querySelector(`[data-category="${category}"] div`);
    selectedCategory.style.transform = 'scale(1.05)';
    selectedCategory.style.boxShadow = '0 10px 30px rgba(102, 126, 234, 0.3)';
}

// FAQ toggle functionality
function toggleFAQ(element) {
    const faqItem = element.parentElement;
    const answer = faqItem.querySelector('.faq-answer');
    const icon = element.querySelector('i');
    
    if (answer.style.display === 'block') {
        answer.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    } else {
        // Close all other FAQ items
        document.querySelectorAll('.faq-answer').forEach(ans => {
            ans.style.display = 'none';
        });
        document.querySelectorAll('.faq-question i').forEach(ic => {
            ic.style.transform = 'rotate(0deg)';
        });
        
        // Open clicked item
        answer.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    }
}

// Category hover effects
document.querySelectorAll('.faq-category div').forEach(category => {
    category.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
    });
    
    category.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});
</script>

<style>
.faq-item {
    border: 1px solid #eee;
    border-radius: 10px;
    margin-bottom: 1rem;
    overflow: hidden;
}

.faq-question {
    padding: 1.5rem;
    background: #f8f9fa;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s;
}

.faq-question:hover {
    background: #e9ecef;
}

.faq-question h4 {
    margin: 0;
    color: #333;
}

.faq-question i {
    transition: transform 0.3s;
    color: #667eea;
}

.faq-answer {
    padding: 1.5rem;
    display: none;
    background: white;
}

.faq-answer p {
    margin: 0;
    line-height: 1.6;
    color: #666;
}

@media (max-width: 768px) {
    .faq-question {
        padding: 1rem;
    }
    
    .faq-answer {
        padding: 1rem;
    }
    
    .faq-question h4 {
        font-size: 1rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>