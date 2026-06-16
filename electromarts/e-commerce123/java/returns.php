<?php
require_once 'includes/config.php';
$page_title = 'Returns & Refunds';
include 'includes/header.php';
?>

<div class="container">
    <!-- Hero Section -->
    <section style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; padding: 4rem 2rem; border-radius: 15px; margin-bottom: 3rem;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">Returns & Refunds</h1>
        <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto;">Easy returns and hassle-free refunds. Your satisfaction is our priority.</p>
    </section>

    <!-- Return Policy Overview -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="color: #333; margin-bottom: 1rem;">30-Day Return Policy</h2>
            <p style="color: #666; font-size: 1.1rem;">We want you to be completely satisfied with your purchase.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-calendar-alt" style="font-size: 3rem; color: #28a745; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">30 Days</h3>
                <p style="color: #666;">Return window from delivery date</p>
            </div>
            
            <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-shipping-fast" style="font-size: 3rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Free Returns</h3>
                <p style="color: #666;">On defective or damaged items</p>
            </div>
            
            <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-money-bill-wave" style="font-size: 3rem; color: #ffc107; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Full Refund</h3>
                <p style="color: #666;">Original payment method within 5-7 days</p>
            </div>
            
            <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-headset" style="font-size: 3rem; color: #17a2b8; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Easy Process</h3>
                <p style="color: #666;">Simple online return request system</p>
            </div>
        </div>
    </section>

    <!-- Return Process -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #333;">How to Return an Item</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">1</div>
                <div>
                    <h3 style="color: #333; margin-bottom: 1rem;">Start Return Request</h3>
                    <p style="color: #666; line-height: 1.6;">Log into your account and go to "My Orders". Find your order and click "Return Item" to start the process.</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">2</div>
                <div>
                    <h3 style="color: #333; margin-bottom: 1rem;">Select Return Reason</h3>
                    <p style="color: #666; line-height: 1.6;">Choose the reason for your return and provide any additional details. This helps us improve our service.</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">3</div>
                <div>
                    <h3 style="color: #333; margin-bottom: 1rem;">Print Return Label</h3>
                    <p style="color: #666; line-height: 1.6;">We'll email you a prepaid return shipping label. Print it and attach it to your package.</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">4</div>
                <div>
                    <h3 style="color: #333; margin-bottom: 1rem;">Package & Ship</h3>
                    <p style="color: #666; line-height: 1.6;">Pack the item in its original packaging (if available) and drop it off at any authorized shipping location.</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">5</div>
                <div>
                    <h3 style="color: #333; margin-bottom: 1rem;">Receive Refund</h3>
                    <p style="color: #666; line-height: 1.6;">Once we receive and process your return, your refund will be issued within 5-7 business days.</p>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <?php if (isLoggedIn()): ?>
                <a href="orders.php" class="btn btn-primary">View My Orders</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary">Login to Start Return</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Return Conditions -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #333;">Return Conditions</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            <!-- Returnable Items -->
            <div>
                <h3 style="color: #28a745; margin-bottom: 1.5rem;"><i class="fas fa-check-circle"></i> Returnable Items</h3>
                <ul style="color: #666; line-height: 1.8;">
                    <li>Items in original condition with all accessories</li>
                    <li>Unopened software and media (unless defective)</li>
                    <li>Electronics in original packaging</li>
                    <li>Items with original tags and labels</li>
                    <li>Defective or damaged items (any condition)</li>
                    <li>Wrong item sent by mistake</li>
                </ul>
                
                <div style="background: #d4edda; padding: 1.5rem; border-radius: 10px; margin-top: 2rem; border-left: 4px solid #28a745;">
                    <p style="margin: 0; color: #155724;"><strong>Free Return Shipping</strong></p>
                    <p style="margin: 0.5rem 0 0 0; color: #155724;">We cover return shipping costs for defective, damaged, or incorrectly sent items.</p>
                </div>
            </div>
            
            <!-- Non-Returnable Items -->
            <div>
                <h3 style="color: #dc3545; margin-bottom: 1.5rem;"><i class="fas fa-times-circle"></i> Non-Returnable Items</h3>
                <ul style="color: #666; line-height: 1.8;">
                    <li>Personalized or customized products</li>
                    <li>Opened software, games, or digital downloads</li>
                    <li>Items damaged by misuse or normal wear</li>
                    <li>Products without original packaging (unless defective)</li>
                    <li>Items returned after 30 days</li>
                    <li>Gift cards and promotional items</li>
                </ul>
                
                <div style="background: #f8d7da; padding: 1.5rem; border-radius: 10px; margin-top: 2rem; border-left: 4px solid #dc3545;">
                    <p style="margin: 0; color: #721c24;"><strong>Customer Pays Return Shipping</strong></p>
                    <p style="margin: 0.5rem 0 0 0; color: #721c24;">For returns due to change of mind or ordering error, customer covers return shipping costs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Refund Information -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #333;">Refund Information</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px;">
                <h3 style="color: #667eea; margin-bottom: 1rem;"><i class="fas fa-credit-card"></i> Credit Card Refunds</h3>
                <ul style="color: #666; line-height: 1.6;">
                    <li>Processed within 5-7 business days</li>
                    <li>Refunded to original payment method</li>
                    <li>May take 1-2 billing cycles to appear</li>
                    <li>Email confirmation sent when processed</li>
                </ul>
            </div>
            
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px;">
                <h3 style="color: #667eea; margin-bottom: 1rem;"><i class="fab fa-paypal"></i> PayPal Refunds</h3>
                <ul style="color: #666; line-height: 1.6;">
                    <li>Processed within 3-5 business days</li>
                    <li>Appears in PayPal account immediately</li>
                    <li>Automatic email notification from PayPal</li>
                    <li>Can be transferred to bank account</li>
                </ul>
            </div>
            
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px;">
                <h3 style="color: #667eea; margin-bottom: 1rem;"><i class="fas fa-money-bill"></i> Cash on Delivery</h3>
                <ul style="color: #666; line-height: 1.6;">
                    <li>Refunded via bank transfer or check</li>
                    <li>Requires bank account information</li>
                    <li>Processed within 7-10 business days</li>
                    <li>Additional verification may be required</li>
                </ul>
            </div>
        </div>
        
        <div style="background: #fff3cd; padding: 1.5rem; border-radius: 10px; margin-top: 2rem; border-left: 4px solid #ffc107;">
            <p style="margin: 0; color: #856404;"><strong>Partial Refunds</strong></p>
            <p style="margin: 0.5rem 0 0 0; color: #856404;">Items returned in used condition, without original packaging, or after 30 days may receive a partial refund at our discretion.</p>
        </div>
    </section>

    <!-- Exchange Policy -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #333;">Exchange Policy</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
            <div>
                <h3 style="color: #667eea; margin-bottom: 1rem;">When Exchanges Are Available</h3>
                <ul style="color: #666; line-height: 1.8; margin-bottom: 2rem;">
                    <li>Defective items (same model replacement)</li>
                    <li>Wrong size or color (if available)</li>
                    <li>Damaged during shipping</li>
                    <li>Different model (price difference applies)</li>
                </ul>
                
                <h3 style="color: #667eea; margin-bottom: 1rem;">Exchange Process</h3>
                <ol style="color: #666; line-height: 1.8;">
                    <li>Contact customer service</li>
                    <li>Verify product availability</li>
                    <li>Return original item</li>
                    <li>Receive replacement item</li>
                </ol>
            </div>
            
            <div style="text-align: center;">
                <i class="fas fa-exchange-alt" style="font-size: 6rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Quick Exchanges</h3>
                <p style="color: #666; margin-bottom: 2rem;">For defective items, we can often send a replacement before receiving the return.</p>
                <a href="contact.php" class="btn btn-primary">Contact for Exchange</a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section style="background: #f8f9fa; padding: 3rem; border-radius: 15px; margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #333;">Return FAQ</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            <div>
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">What if I lost my receipt or order confirmation?</h4>
                <p style="margin-bottom: 2rem; color: #666;">No problem! Log into your account to view your order history, or contact us with your email address and we can look up your order.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">Can I return a gift?</h4>
                <p style="margin-bottom: 2rem; color: #666;">Yes, gifts can be returned for store credit or exchanged. The original purchaser can also process the return for a refund to their payment method.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">What if my return is lost in shipping?</h4>
                <p style="margin-bottom: 2rem; color: #666;">We provide tracking for all return shipments. If your return is lost, we'll work with the carrier to locate it or process your refund based on the tracking information.</p>
            </div>
            
            <div>
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">How long does the return process take?</h4>
                <p style="margin-bottom: 2rem; color: #666;">Once we receive your return, processing takes 1-2 business days. Refunds are then issued within 5-7 business days depending on your payment method.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">Can I return an item purchased with a coupon?</h4>
                <p style="margin-bottom: 2rem; color: #666;">Yes, but the refund amount will be the actual amount paid after the discount. The coupon cannot be reissued unless it was for a defective item.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">What if I need to return multiple items from one order?</h4>
                <p style="margin-bottom: 2rem; color: #666;">You can return multiple items in the same package using one return label, or process separate returns for each item if needed.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
        <h2 style="margin-bottom: 1rem; color: #333;">Need Help with Your Return?</h2>
        <p style="color: #666; margin-bottom: 2rem;">Our customer service team is ready to assist you with any return questions or issues.</p>
        
        <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-bottom: 2rem;">
            <div>
                <i class="fas fa-phone" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4 style="margin-bottom: 0.5rem;">Call Us</h4>
                <p style="margin: 0; color: #666;">+1 (555) 123-4567</p>
                <p style="margin: 0; color: #666; font-size: 0.9rem;">Mon-Fri 9AM-6PM PST</p>
            </div>
            
            <div>
                <i class="fas fa-envelope" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4 style="margin-bottom: 0.5rem;">Email Us</h4>
                <p style="margin: 0; color: #666;">returns@electromart.com</p>
                <p style="margin: 0; color: #666; font-size: 0.9rem;">Response within 24 hours</p>
            </div>
            
            <div>
                <i class="fas fa-comments" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4 style="margin-bottom: 0.5rem;">Live Chat</h4>
                <p style="margin: 0; color: #666;">Available on website</p>
                <p style="margin: 0; color: #666; font-size: 0.9rem;">Instant assistance</p>
            </div>
        </div>
        
        <a href="contact.php" class="btn btn-primary">Contact Support</a>
    </section>
</div>

<style>
@media (max-width: 768px) {
    .container section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
    
    .container section div[style*="display: flex"] {
        flex-direction: column !important;
        text-align: center !important;
    }
}
</style>

<?php include 'includes/footer.php'; ?>