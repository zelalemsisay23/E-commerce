<?php
require_once 'includes/config.php';
$page_title = 'Shipping Information';
include 'includes/header.php';
?>

<div class="container">
    <!-- Hero Section -->
    <section style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; padding: 4rem 2rem; border-radius: 15px; margin-bottom: 3rem;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">Shipping Information</h1>
        <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto;">Fast, reliable shipping to get your electronics delivered safely and on time.</p>
    </section>

    <!-- Shipping Options -->
    <section style="margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 2rem; color: #333;">Shipping Options</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <!-- Standard Shipping -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
                <i class="fas fa-truck" style="font-size: 3rem; color: #28a745; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Standard Shipping</h3>
                <div style="font-size: 1.5rem; font-weight: bold; color: #28a745; margin-bottom: 1rem;">FREE</div>
                <p style="color: #666; margin-bottom: 1rem;">On orders over $50</p>
                <ul style="text-align: left; color: #666; margin-bottom: 1.5rem;">
                    <li>3-5 business days delivery</li>
                    <li>Tracking included</li>
                    <li>Signature not required</li>
                    <li>$9.99 for orders under $50</li>
                </ul>
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px;">
                    <small style="color: #666;">Most popular option</small>
                </div>
            </div>

            <!-- Express Shipping -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center; border: 2px solid #667eea;">
                <i class="fas fa-shipping-fast" style="font-size: 3rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Express Shipping</h3>
                <div style="font-size: 1.5rem; font-weight: bold; color: #667eea; margin-bottom: 1rem;">$19.99</div>
                <p style="color: #666; margin-bottom: 1rem;">All orders</p>
                <ul style="text-align: left; color: #666; margin-bottom: 1.5rem;">
                    <li>1-2 business days delivery</li>
                    <li>Priority handling</li>
                    <li>Real-time tracking</li>
                    <li>Signature required</li>
                </ul>
                <div style="background: #e3f2fd; padding: 1rem; border-radius: 8px;">
                    <small style="color: #1976d2;">Fastest option</small>
                </div>
            </div>

            <!-- Overnight Shipping -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
                <i class="fas fa-rocket" style="font-size: 3rem; color: #ff6b35; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Overnight Shipping</h3>
                <div style="font-size: 1.5rem; font-weight: bold; color: #ff6b35; margin-bottom: 1rem;">$39.99</div>
                <p style="color: #666; margin-bottom: 1rem;">Next business day</p>
                <ul style="text-align: left; color: #666; margin-bottom: 1.5rem;">
                    <li>Next business day delivery</li>
                    <li>Order by 2 PM EST</li>
                    <li>Premium tracking</li>
                    <li>Signature required</li>
                </ul>
                <div style="background: #fff3e0; padding: 1rem; border-radius: 8px;">
                    <small style="color: #f57c00;">Premium service</small>
                </div>
            </div>
        </div>
    </section>

    <!-- Shipping Zones -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 2rem; color: #333;">Shipping Zones & Delivery Times</h2>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">Zone</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">Standard</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">Express</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">Overnight</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;"><strong>Zone 1</strong><br><small>CA, NV, AZ, OR, WA</small></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">2-3 days</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">1 day</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Next day</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;"><strong>Zone 2</strong><br><small>TX, CO, UT, ID, MT</small></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">3-4 days</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">1-2 days</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Next day</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;"><strong>Zone 3</strong><br><small>Midwest & East Coast</small></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">4-5 days</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">2 days</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Next day</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;"><strong>Alaska & Hawaii</strong></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">7-10 days</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">3-5 days</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">2-3 days</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div style="background: #e3f2fd; padding: 1.5rem; border-radius: 10px; margin-top: 2rem;">
            <p style="margin: 0; color: #1976d2;"><strong>Note:</strong> Delivery times are business days and exclude weekends and holidays. Times may vary during peak seasons.</p>
        </div>
    </section>

    <!-- International Shipping -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 2rem; color: #333;">International Shipping</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
            <div>
                <h3 style="color: #667eea; margin-bottom: 1rem;">Available Countries</h3>
                <p style="margin-bottom: 1.5rem;">We currently ship to over 50 countries worldwide including:</p>
                <ul style="margin-bottom: 2rem; columns: 2; column-gap: 2rem;">
                    <li>Canada</li>
                    <li>United Kingdom</li>
                    <li>Germany</li>
                    <li>France</li>
                    <li>Australia</li>
                    <li>Japan</li>
                    <li>South Korea</li>
                    <li>Singapore</li>
                    <li>And many more...</li>
                </ul>
                
                <h3 style="color: #667eea; margin-bottom: 1rem;">Important Notes</h3>
                <ul style="margin-bottom: 1.5rem;">
                    <li>Customs duties and taxes may apply</li>
                    <li>Delivery times: 7-21 business days</li>
                    <li>Tracking available for all shipments</li>
                    <li>Some products may have shipping restrictions</li>
                </ul>
            </div>
            
            <div style="text-align: center;">
                <i class="fas fa-globe" style="font-size: 6rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Worldwide Delivery</h3>
                <p style="color: #666;">Bringing technology to your doorstep, wherever you are.</p>
            </div>
        </div>
    </section>

    <!-- Shipping Policies -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 2rem; color: #333;">Shipping Policies</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div>
                <h3 style="color: #667eea; margin-bottom: 1rem;"><i class="fas fa-clock"></i> Processing Time</h3>
                <ul>
                    <li>Orders placed before 2 PM EST ship same day</li>
                    <li>Orders placed after 2 PM EST ship next business day</li>
                    <li>No processing on weekends or holidays</li>
                    <li>Custom orders may require additional processing time</li>
                </ul>
            </div>
            
            <div>
                <h3 style="color: #667eea; margin-bottom: 1rem;"><i class="fas fa-box"></i> Packaging</h3>
                <ul>
                    <li>All items are carefully packaged for protection</li>
                    <li>Eco-friendly packaging materials when possible</li>
                    <li>Fragile items receive extra padding</li>
                    <li>Discreet packaging available upon request</li>
                </ul>
            </div>
            
            <div>
                <h3 style="color: #667eea; margin-bottom: 1rem;"><i class="fas fa-map-marker-alt"></i> Address Requirements</h3>
                <ul>
                    <li>Complete and accurate address required</li>
                    <li>We cannot deliver to P.O. boxes for some items</li>
                    <li>Apartment/suite numbers must be included</li>
                    <li>Address changes may not be possible after shipping</li>
                </ul>
            </div>
            
            <div>
                <h3 style="color: #667eea; margin-bottom: 1rem;"><i class="fas fa-search"></i> Order Tracking</h3>
                <ul>
                    <li>Tracking number provided via email</li>
                    <li>Real-time tracking updates</li>
                    <li>SMS notifications available</li>
                    <li>Track orders in your account dashboard</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section style="background: #f8f9fa; padding: 3rem; border-radius: 15px; margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 2rem; color: #333;">Shipping FAQ</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">Can I change my shipping address after placing an order?</h4>
                <p style="margin-bottom: 1.5rem; color: #666;">Address changes are possible within 1 hour of placing your order. After that, please contact customer service immediately.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">What if my package is damaged during shipping?</h4>
                <p style="margin-bottom: 1.5rem; color: #666;">Contact us immediately with photos of the damage. We'll work with the carrier to resolve the issue and send a replacement if needed.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">Do you ship to military addresses (APO/FPO)?</h4>
                <p style="margin-bottom: 1.5rem; color: #666;">Yes, we ship to APO/FPO addresses using standard shipping rates. Delivery times may vary.</p>
            </div>
            
            <div>
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">What happens if I'm not home for delivery?</h4>
                <p style="margin-bottom: 1.5rem; color: #666;">The carrier will leave a delivery notice and attempt redelivery. You can also arrange pickup at a local facility.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">Can I expedite an order that's already shipped?</h4>
                <p style="margin-bottom: 1.5rem; color: #666;">Once shipped, we cannot change the shipping method. However, you may be able to upgrade through the carrier directly.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">Do you offer Saturday delivery?</h4>
                <p style="margin-bottom: 1.5rem; color: #666;">Saturday delivery is available for Express and Overnight shipping in select areas for an additional fee.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
        <h2 style="margin-bottom: 1rem; color: #333;">Need Help with Shipping?</h2>
        <p style="color: #666; margin-bottom: 2rem;">Our customer service team is here to help with any shipping questions or concerns.</p>
        
        <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
            <div>
                <i class="fas fa-phone" style="font-size: 1.5rem; color: #667eea; margin-bottom: 0.5rem;"></i>
                <p style="margin: 0; font-weight: bold;">Call Us</p>
                <p style="margin: 0; color: #666;">+1 (555) 123-4567</p>
            </div>
            
            <div>
                <i class="fas fa-envelope" style="font-size: 1.5rem; color: #667eea; margin-bottom: 0.5rem;"></i>
                <p style="margin: 0; font-weight: bold;">Email Us</p>
                <p style="margin: 0; color: #666;">shipping@electromart.com</p>
            </div>
            
            <div>
                <i class="fas fa-comments" style="font-size: 1.5rem; color: #667eea; margin-bottom: 0.5rem;"></i>
                <p style="margin: 0; font-weight: bold;">Live Chat</p>
                <p style="margin: 0; color: #666;">Available 24/7</p>
            </div>
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="contact.php" class="btn btn-primary">Contact Support</a>
        </div>
    </section>
</div>

<style>
@media (max-width: 768px) {
    .container section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
    
    table {
        font-size: 0.9rem;
    }
    
    th, td {
        padding: 0.5rem !important;
    }
}
</style>

<?php include 'includes/footer.php'; ?>