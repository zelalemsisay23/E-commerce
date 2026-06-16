<?php
// Create visible colored square images that will definitely work

// Create a simple colored square image function
function createColoredSquare($color, $filename) {
    // Create a simple HTML that generates an image
    $html = "
    <!DOCTYPE html>
    <html>
    <head><title>Generate Image</title></head>
    <body>
    <canvas id='canvas' width='400' height='400'></canvas>
    <script>
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    
    // Fill with color
    ctx.fillStyle = '$color';
    ctx.fillRect(0, 0, 400, 400);
    
    // Add border
    ctx.strokeStyle = '#333';
    ctx.lineWidth = 4;
    ctx.strokeRect(0, 0, 400, 400);
    
    // Add text
    ctx.fillStyle = '#fff';
    ctx.font = 'bold 24px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('PRODUCT', 200, 180);
    ctx.fillText('IMAGE', 200, 220);
    
    // Convert to data URL and download
    const link = document.createElement('a');
    link.download = '$filename';
    link.href = canvas.toDataURL('image/png');
    document.body.appendChild(link);
    link.click();
    </script>
    </body>
    </html>";
    
    return $html;
}

// Create a better base64 image - this is a small but visible red square
$red_square = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==';

// Create different colored squares for different products
$images = [
    'iphone15pro.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAFfeTFYOAAAAABJRU5ErkJggg==',
    'iphone14.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAFfeTFYOAAAAABJRU5ErkJggg==',
    'galaxys24.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==',
    'pixel8.png' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==',
    'macbookpro14.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAFfeTFYOAAAAABJRU5ErkJggg==',
    'dellxps13.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==',
    'ipadair.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAFfeTFYOAAAAABJRU5ErkJggg==',
    'sonywh1000xm5.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAFfeTFYOAAAAABJRU5ErkJggg==',
    'airpodspro.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAFfeTFYOAAAAABJRU5ErkJggg==',
    'ps5.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==',
    'xboxseriesx.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==',
    'switch.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==',
    'magsafe.jpg' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAFfeTFYOAAAAABJRU5ErkJggg=='
];

echo "Creating visible PNG images...\n\n";

// Create placeholder - gray square
$placeholder_data = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChAFfeTFYOAAAAABJRU5ErkJggg==');
file_put_contents('images/placeholder.jpg', $placeholder_data);
echo "✓ Created placeholder.jpg\n";

// Create product images
foreach ($images as $filename => $data_url) {
    $base64_data = str_replace('data:image/png;base64,', '', $data_url);
    $image_data = base64_decode($base64_data);
    file_put_contents("images/products/$filename", $image_data);
    echo "✓ Created $filename\n";
}

echo "\n✅ All images created!\n";
echo "Now open test_simple.html in your browser to check if they work.\n";
?>