<?php
// Final solution - create actual working images

// This is a valid 1x1 pixel PNG image (transparent)
$png_data = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIHWNgAAIAAAUAAY27m/MAAAAASUVORK5CYII=');

// This is a valid 1x1 pixel JPEG image (white)
$jpg_data = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwDVAA==');

echo "Creating final working images...\n\n";

// Create placeholder
file_put_contents('images/placeholder.jpg', $jpg_data);
echo "✓ Created placeholder.jpg (" . filesize('images/placeholder.jpg') . " bytes)\n";

// List of all product images
$products = [
    'iphone15pro.jpg', 'iphone14.jpg', 'galaxys24.jpg', 'pixel8.png',
    'macbookpro14.jpg', 'dellxps13.jpg', 'ipadair.jpg', 'sonywh1000xm5.jpg',
    'airpodspro.jpg', 'ps5.jpg', 'xboxseriesx.jpg', 'switch.jpg', 'magsafe.jpg'
];

// Create all product images
foreach ($products as $filename) {
    if (strpos($filename, '.png') !== false) {
        file_put_contents("images/products/$filename", $png_data);
    } else {
        file_put_contents("images/products/$filename", $jpg_data);
    }
    echo "✓ Created $filename (" . filesize("images/products/$filename") . " bytes)\n";
}

echo "\n✅ SUCCESS! All images are now proper binary files.\n";
echo "\n🔍 QUICK TEST:\n";
echo "Open your browser and go to: http://localhost/toni/test_simple.html\n";
echo "You should see small colored squares instead of broken image icons.\n";
echo "\n📱 THEN TEST YOUR STORE:\n";
echo "Go to: http://localhost/toni/index.php\n";
echo "Product images should now display (even if small).\n";
?>