<?php
require_once 'includes/config.php';
$page_title = 'About Us';
include 'includes/header.php';
?>

<div class="container">
    <!-- Hero Section -->
    <section style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; padding: 4rem 2rem; border-radius: 15px; margin-bottom: 3rem;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">About ElectroMart</h1>
        <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto;">Your trusted destination for cutting-edge electronics and innovative technology solutions.</p>
    </section>

    <!-- Our Story -->
    <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 4rem; align-items: center;">
        <div>
            <h2 style="color: #333; margin-bottom: 1.5rem; font-size: 2.5rem;">Our Story</h2>
            <p style="color: #666; line-height: 1.8; margin-bottom: 1.5rem;">
                Founded in 2020, ElectroMart began as a small startup with a big vision: to make the latest technology accessible to everyone. What started as a passion project has grown into a trusted online destination for electronics enthusiasts worldwide.
            </p>
            <p style="color: #666; line-height: 1.8; margin-bottom: 1.5rem;">
                We believe that technology should enhance lives, not complicate them. That's why we carefully curate our product selection, ensuring every item meets our high standards for quality, innovation, and value.
            </p>
        </div>
        <div style="text-align: center;">
            <div style="background: #f8f9fa; padding: 3rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <i class="fas fa-store" style="font-size: 4rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Since 2020</h3>
                <p style="color: #666;">Serving customers worldwide with premium electronics.</p>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section style="margin-bottom: 4rem;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="color: #333; font-size: 2.5rem; margin-bottom: 1rem;">Our Values</h2>
            <p style="color: #666; font-size: 1.1rem;">The principles that guide everything we do</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
                <i class="fas fa-award" style="font-size: 3rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Quality First</h3>
                <p style="color: #666; line-height: 1.6;">We partner only with trusted brands and rigorously test every product.</p>
            </div>
            
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
                <i class="fas fa-users" style="font-size: 3rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Customer Focused</h3>
                <p style="color: #666; line-height: 1.6;">Your satisfaction is our priority. We provide personalized support.</p>
            </div>
            
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
                <i class="fas fa-rocket" style="font-size: 3rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="color: #333; margin-bottom: 1rem;">Innovation</h3>
                <p style="color: #666; line-height: 1.6;">We stay ahead of technology trends to bring you the latest innovations.</p>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>