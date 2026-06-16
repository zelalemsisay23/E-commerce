<?php
require_once 'includes/config.php';
$page_title = 'Warranty Information';
include 'includes/header.php';
?>

<div class="container">
    <!-- Hero Section -->
    <section style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; padding: 4rem 2rem; border-radius: 15px; margin-bottom: 3rem;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">Warranty Information</h1>
        <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto;">Comprehensive warranty coverage and support for all your electronics purchases.</p>
    </section>

    <!-- Warranty Overview -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="color: #333; margin-bottom: 1rem;">Warranty Coverage</h2>
            <p style="color: #666; font-size: 1.1rem;">All products come with manufacturer warranties plus our additional protection.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-shield-alt" style="font-size: 3rem; color: #28a745; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Manufacturer Warranty</h3>
                <p style="color: #666;">Full manufacturer warranty included with every product</p>
            </div>
            
            <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-tools" style="font-size: 3rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Free Repairs</h3>
                <p style="color: #666;">No-cost repairs for manufacturing defects</p>
            </div>
            
            <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-sync-alt" style="font-size: 3rem; color: #ffc107; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Replacement Service</h3>
                <p style="color: #666;">Quick replacement for defective items</p>
            </div>
            
            <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                <i class="fas fa-headset" style="font-size: 3rem; color: #17a2b8; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Expert Support</h3>
                <p style="color: #666;">Technical support throughout warranty period</p>
            </div>
        </div>
    </section>

    <!-- Warranty Periods by Category -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #333;">Warranty Periods by Product Category</h2>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">Product Category</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">Manufacturer Warranty</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">ElectroMart Protection</th>
                        <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">Total Coverage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;"><strong>Smartphones</strong></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">1 Year</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">30 Days DOA</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">1 Year + 30 Days</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;"><strong>Laptops</strong></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">1-3 Years</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">30 Days DOA</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Up to 3 Years + 30 Days</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;"><strong>Tablets</strong></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">1 Year</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">30 Days DOA</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">1 Year + 30 Days</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;"><strong>Audio Equipment</strong></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">1-2 Years</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">30 Days DOA</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Up to 2 Years + 30 Days</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;"><strong>Gaming Consoles</strong></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">1 Year</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">30 Days DOA</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">1 Year + 30 Days</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;"><strong>Accessories</strong></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">90 Days - 1 Year</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">30 Days DOA</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Up to 1 Year + 30 Days</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div style="background: #e3f2fd; padding: 1.5rem; border-radius: 10px; margin-top: 2rem;">
            <p style="margin: 0; color: #1976d2;"><strong>DOA (Dead on Arrival):</strong> If your product doesn't work right out of the box, we'll replace it immediately within 30 days of purchase.</p>
        </div>
    </section>

    <!-- What's Covered -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #333;">What's Covered Under Warranty</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            <!-- Covered -->
            <div>
                <h3 style="color: #28a745; margin-bottom: 1.5rem;"><i class="fas fa-check-circle"></i> Covered Defects</h3>
                <ul style="color: #666; line-height: 1.8;">
                    <li>Manufacturing defects and faulty components</li>
                    <li>Hardware failures under normal use</li>
                    <li>Software issues (for devices with pre-installed software)</li>
                    <li>Battery defects (not normal wear)</li>
                    <li>Display issues (dead pixels, backlight failure)</li>
                    <li>Audio/video output problems</li>
                    <li>Charging port and connector issues</li>
                    <li>Button and switch malfunctions</li>
                </ul>
                
                <div style="background: #d4edda; padding: 1.5rem; border-radius: 10px; margin-top: 2rem; border-left: 4px solid #28a745;">
                    <p style="margin: 0; color: #155724;"><strong>Free Warranty Service</strong></p>
                    <p style="margin: 0.5rem 0 0 0; color: #155724;">All warranty repairs and replacements are provided at no cost to you, including shipping.</p>
                </div>
            </div>
            
            <!-- Not Covered -->
            <div>
                <h3 style="color: #dc3545; margin-bottom: 1.5rem;"><i class="fas fa-times-circle"></i> Not Covered</h3>
                <ul style="color: #666; line-height: 1.8;">
                    <li>Physical damage (drops, impacts, liquid damage)</li>
                    <li>Normal wear and tear</li>
                    <li>Damage from misuse or abuse</li>
                    <li>Unauthorized repairs or modifications</li>
                    <li>Cosmetic damage that doesn't affect function</li>
                    <li>Damage from power surges or electrical issues</li>
                    <li>Software issues caused by user modifications</li>
                    <li>Accessories (cables, cases) unless specifically covered</li>
                </ul>
                
                <div style="background: #f8d7da; padding: 1.5rem; border-radius: 10px; margin-top: 2rem; border-left: 4px solid #dc3545;">
                    <p style="margin: 0; color: #721c24;"><strong>Extended Protection Available</strong></p>
                    <p style="margin: 0.5rem 0 0 0; color: #721c24;">Consider our extended warranty plans for coverage beyond manufacturer warranty.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Warranty Claim Process -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #333;">How to File a Warranty Claim</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">1</div>
                <div>
                    <h3 style="color: #333; margin-bottom: 1rem;">Contact Support</h3>
                    <p style="color: #666; line-height: 1.6;">Reach out to our customer service team with your order number and description of the issue. We'll help determine if it's covered under warranty.</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">2</div>
                <div>
                    <h3 style="color: #333; margin-bottom: 1rem;">Provide Information</h3>
                    <p style="color: #666; line-height: 1.6;">We'll need your purchase details, product serial number, and photos/videos of the issue if applicable. This helps us process your claim quickly.</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">3</div>
                <div>
                    <h3 style="color: #333; margin-bottom: 1rem;">Get Authorization</h3>
                    <p style="color: #666; line-height: 1.6;">Once approved, we'll provide a warranty claim number and instructions for sending your item for repair or replacement.</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">4</div>
                <div>
                    <h3 style="color: #333; margin-bottom: 1rem;">Ship Your Item</h3>
                    <p style="color: #666; line-height: 1.6;">Pack your item securely and ship it using the prepaid label we provide. We'll handle the rest and keep you updated on the progress.</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">5</div>
                <div>
                    <h3 style="color: #333; margin-bottom: 1rem;">Receive Resolution</h3>
                    <p style="color: #666; line-height: 1.6;">Get your repaired item back or receive a replacement. Most warranty claims are resolved within 7-14 business days.</p>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <a href="contact.php" class="btn btn-primary">Start Warranty Claim</a>
        </div>
    </section>

    <!-- Extended Warranty Options -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #333;">Extended Warranty Plans</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <!-- Basic Plan -->
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 15px; text-align: center; border: 2px solid transparent;">
                <h3 style="color: #333; margin-bottom: 1rem;">Basic Protection</h3>
                <div style="font-size: 2rem; font-weight: bold; color: #667eea; margin-bottom: 1rem;">+1 Year</div>
                <p style="color: #666; margin-bottom: 2rem;">Extends manufacturer warranty by 1 additional year</p>
                <ul style="text-align: left; color: #666; margin-bottom: 2rem;">
                    <li>Manufacturing defect coverage</li>
                    <li>Hardware failure protection</li>
                    <li>Free repairs and replacements</li>
                    <li>Technical support included</li>
                </ul>
                <div style="font-size: 1.2rem; font-weight: bold; color: #333;">Starting at $29.99</div>
            </div>

            <!-- Premium Plan -->
            <div style="background: white; padding: 2rem; border-radius: 15px; text-align: center; border: 2px solid #667eea; position: relative;">
                <div style="position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: #667eea; color: white; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.8rem;">POPULAR</div>
                <h3 style="color: #333; margin-bottom: 1rem;">Premium Protection</h3>
                <div style="font-size: 2rem; font-weight: bold; color: #667eea; margin-bottom: 1rem;">+2 Years</div>
                <p style="color: #666; margin-bottom: 2rem;">Comprehensive coverage for 2 additional years</p>
                <ul style="text-align: left; color: #666; margin-bottom: 2rem;">
                    <li>All Basic Protection features</li>
                    <li>Accidental damage coverage (2 incidents)</li>
                    <li>Battery replacement included</li>
                    <li>Priority repair service</li>
                    <li>Loaner device program</li>
                </ul>
                <div style="font-size: 1.2rem; font-weight: bold; color: #333;">Starting at $79.99</div>
            </div>

            <!-- Ultimate Plan -->
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 15px; text-align: center; border: 2px solid transparent;">
                <h3 style="color: #333; margin-bottom: 1rem;">Ultimate Protection</h3>
                <div style="font-size: 2rem; font-weight: bold; color: #667eea; margin-bottom: 1rem;">+3 Years</div>
                <p style="color: #666; margin-bottom: 2rem;">Maximum protection for 3 additional years</p>
                <ul style="text-align: left; color: #666; margin-bottom: 2rem;">
                    <li>All Premium Protection features</li>
                    <li>Unlimited accidental damage</li>
                    <li>Theft and loss protection</li>
                    <li>Annual maintenance service</li>
                    <li>24/7 technical support</li>
                </ul>
                <div style="font-size: 1.2rem; font-weight: bold; color: #333;">Starting at $149.99</div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <p style="color: #666; margin-bottom: 1rem;">Extended warranty plans must be purchased within 30 days of your original purchase.</p>
            <a href="contact.php" class="btn btn-primary">Learn More About Extended Warranties</a>
        </div>
    </section>

    <!-- Warranty FAQ -->
    <section style="background: #f8f9fa; padding: 3rem; border-radius: 15px; margin-bottom: 3rem;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: #333;">Warranty FAQ</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            <div>
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">Do I need to register my product for warranty coverage?</h4>
                <p style="margin-bottom: 2rem; color: #666;">No registration required! Your warranty coverage begins automatically from the date of purchase. Keep your receipt or order confirmation as proof of purchase.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">What if I bought a refurbished product?</h4>
                <p style="margin-bottom: 2rem; color: #666;">Refurbished products come with a 90-day ElectroMart warranty covering the same defects as new products, plus our 30-day DOA protection.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">Can I transfer my warranty to someone else?</h4>
                <p style="margin-bottom: 2rem; color: #666;">Manufacturer warranties typically transfer with the product, but extended warranties purchased through ElectroMart are non-transferable.</p>
            </div>
            
            <div>
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">What happens if my product is discontinued?</h4>
                <p style="margin-bottom: 2rem; color: #666;">If your exact model is no longer available, we'll provide a comparable replacement of equal or greater value at no additional cost.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">How long does warranty repair take?</h4>
                <p style="margin-bottom: 2rem; color: #666;">Most warranty repairs are completed within 7-14 business days. For complex issues or parts availability, it may take longer, but we'll keep you informed.</p>
                
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">What if my warranty claim is denied?</h4>
                <p style="margin-bottom: 2rem; color: #666;">If a claim is denied, we'll explain why and provide options such as paid repair services or trade-in programs for a new device.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
        <h2 style="margin-bottom: 1rem; color: #333;">Warranty Support</h2>
        <p style="color: #666; margin-bottom: 2rem;">Our warranty specialists are here to help with claims, questions, and technical support.</p>
        
        <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap; margin-bottom: 2rem;">
            <div>
                <i class="fas fa-phone" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4 style="margin-bottom: 0.5rem;">Warranty Hotline</h4>
                <p style="margin: 0; color: #666;">+1 (555) 123-4567</p>
                <p style="margin: 0; color: #666; font-size: 0.9rem;">Mon-Fri 8AM-8PM PST</p>
            </div>
            
            <div>
                <i class="fas fa-envelope" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4 style="margin-bottom: 0.5rem;">Email Support</h4>
                <p style="margin: 0; color: #666;">warranty@electromart.com</p>
                <p style="margin: 0; color: #666; font-size: 0.9rem;">Response within 4 hours</p>
            </div>
            
            <div>
                <i class="fas fa-comments" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h4 style="margin-bottom: 0.5rem;">Live Chat</h4>
                <p style="margin: 0; color: #666;">Warranty specialists online</p>
                <p style="margin: 0; color: #666; font-size: 0.9rem;">Available during business hours</p>
            </div>
        </div>
        
        <a href="contact.php" class="btn btn-primary">Contact Warranty Support</a>
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
    
    table {
        font-size: 0.9rem;
    }
    
    th, td {
        padding: 0.5rem !important;
    }
}
</style>

<?php include 'includes/footer.php'; ?>