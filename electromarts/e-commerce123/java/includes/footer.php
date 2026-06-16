    </main>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>ElectroMart</h3>
                    <p>Your trusted destination for the latest electronics and gadgets. Quality products, competitive prices, and excellent customer service.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Newsletter</h4>
                    <p style="margin-bottom: 1rem;">Subscribe to get updates on new products and exclusive offers!</p>
                    <form id="newsletter-form" style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                        <input type="email" id="newsletter-email" placeholder="Enter your email" 
                               style="flex: 1; padding: 0.5rem; border: 1px solid #555; border-radius: 3px; background: #34495e; color: white;">
                        <button type="submit" style="padding: 0.5rem 1rem; background: #ffd700; color: #333; border: none; border-radius: 3px; cursor: pointer; font-weight: bold;">Subscribe</button>
                    </form>
                    <div id="newsletter-message" style="font-size: 0.9rem;"></div>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Categories</h4>
                    <ul>
                        <li><a href="category.php?id=1">Smartphones</a></li>
                        <li><a href="category.php?id=2">Laptops</a></li>
                        <li><a href="category.php?id=3">Tablets</a></li>
                        <li><a href="category.php?id=4">Audio</a></li>
                        <li><a href="category.php?id=5">Gaming</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Customer Service</h4>
                    <ul>
                        <li><a href="help.php">Help Center</a></li>
                        <li><a href="shipping.php">Shipping Info</a></li>
                        <li><a href="returns.php">Returns</a></li>
                        <li><a href="warranty.php">Warranty</a></li>
                        <li><a href="track-order.php">Track Order</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> ElectroMart. All rights reserved.</p>
                <p>Designed with <i class="fas fa-heart"></i> for electronics enthusiasts</p>
            </div>
        </div>
    </footer>
    
    <script src="js/main.js"></script>
    
    <script>
    // Newsletter subscription
    document.addEventListener('DOMContentLoaded', function() {
        const newsletterForm = document.getElementById('newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.getElementById('newsletter-email').value.trim();
                const messageDiv = document.getElementById('newsletter-message');
                
                if (!email) {
                    messageDiv.innerHTML = '<span style="color: #ff4757;">Please enter your email address</span>';
                    return;
                }
                
                fetch('ajax/newsletter_subscribe.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageDiv.innerHTML = '<span style="color: #28a745;">' + data.message + '</span>';
                        document.getElementById('newsletter-email').value = '';
                    } else {
                        messageDiv.innerHTML = '<span style="color: #ff4757;">' + data.message + '</span>';
                    }
                })
                .catch(error => {
                    messageDiv.innerHTML = '<span style="color: #ff4757;">Error subscribing. Please try again.</span>';
                });
            });
        }
    });
    </script>
</body>
</html>