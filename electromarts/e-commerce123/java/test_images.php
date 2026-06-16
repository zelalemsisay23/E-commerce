<!DOCTYPE html>
<html>
<head>
    <title>Test Images - ElectroMart</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin: 20px 0; }
        .image-card { background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .image-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd; }
        .image-card h3 { margin: 10px 0 5px 0; font-size: 14px; color: #333; }
        .status { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; }
        .status.success { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
        h1 { color: #333; text-align: center; }
        .summary { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>ElectroMart - Image Display Test</h1>
        
        <div class="summary">
            <h2>Image Status Summary</h2>
            <p>This page tests all product images to ensure they display correctly after fixing the SVG data URI issue.</p>
        </div>

        <h2>Placeholder Image</h2>
        <div class="image-grid">
            <div class="image-card">
                <img src="images/placeholder.jpg" alt="Placeholder" onerror="this.nextElementSibling.innerHTML='<span class=\'status error\'>Failed to load</span>'; this.style.display='none';" onload="this.nextElementSibling.innerHTML='<span class=\'status success\'>Loaded successfully</span>';">
                <div></div>
                <h3>placeholder.jpg</h3>
            </div>
        </div>

        <h2>Product Images</h2>
        <div class="image-grid">
            <?php
            $products = [
                ['name' => 'iPhone 15 Pro', 'file' => 'iphone15pro.jpg'],
                ['name' => 'iPhone 14', 'file' => 'iphone14.jpg'],
                ['name' => 'Galaxy S24', 'file' => 'galaxys24.jpg'],
                ['name' => 'Pixel 8', 'file' => 'pixel8.png'],
                ['name' => 'MacBook Pro 14', 'file' => 'macbookpro14.jpg'],
                ['name' => 'Dell XPS 13', 'file' => 'dellxps13.jpg'],
                ['name' => 'iPad Air', 'file' => 'ipadair.jpg'],
                ['name' => 'Sony WH-1000XM5', 'file' => 'sonywh1000xm5.jpg'],
                ['name' => 'AirPods Pro', 'file' => 'airpodspro.jpg'],
                ['name' => 'PlayStation 5', 'file' => 'ps5.jpg'],
                ['name' => 'Xbox Series X', 'file' => 'xboxseriesx.jpg'],
                ['name' => 'Nintendo Switch', 'file' => 'switch.jpg'],
                ['name' => 'MagSafe Charger', 'file' => 'magsafe.jpg']
            ];

            foreach ($products as $product) {
                echo "
                <div class='image-card'>
                    <img src='images/products/{$product['file']}' alt='{$product['name']}' 
                         onerror=\"this.nextElementSibling.innerHTML='<span class=\\'status error\\'>Failed to load</span>'; this.style.display='none';\" 
                         onload=\"this.nextElementSibling.innerHTML='<span class=\\'status success\\'>Loaded successfully</span>';\">
                    <div></div>
                    <h3>{$product['name']}</h3>
                    <small>{$product['file']}</small>
                </div>";
            }
            ?>
        </div>

        <div class="summary">
            <h3>Instructions:</h3>
            <ul>
                <li>All images should show "Loaded successfully" status</li>
                <li>If any image shows "Failed to load", there's still an issue with that file</li>
                <li>Images should display as proper placeholders, not broken image icons</li>
                <li>Once verified, you can visit <a href="index.php">the main homepage</a> to see them in context</li>
            </ul>
        </div>
    </div>

    <script>
        // Count loaded vs failed images after page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                const successCount = document.querySelectorAll('.status.success').length;
                const errorCount = document.querySelectorAll('.status.error').length;
                const total = successCount + errorCount;
                
                console.log(`Image loading results: ${successCount}/${total} loaded successfully`);
                
                if (errorCount === 0) {
                    document.querySelector('.summary').innerHTML += '<div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-top: 15px;"><strong>✅ All images loaded successfully!</strong> The image display issue has been fixed.</div>';
                } else {
                    document.querySelector('.summary').innerHTML += `<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-top: 15px;"><strong>⚠️ ${errorCount} images failed to load.</strong> Some images may still need to be fixed.</div>`;
                }
            }, 1000);
        });
    </script>
</body>
</html>