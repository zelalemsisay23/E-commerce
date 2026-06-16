<?php
require_once 'includes/config.php';

$product_id = (int)($_GET['product_id'] ?? 0);

if (!$product_id) {
    header('Location: index.php');
    exit();
}

// Get product details
$stmt = $pdo->prepare("SELECT name FROM products WHERE id = ? AND status = 'active'");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit();
}

// Get reviews with user info
$stmt = $pdo->prepare("
    SELECT r.*, u.full_name, u.username
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.product_id = ? 
    ORDER BY r.created_at DESC
");
$stmt->execute([$product_id]);
$reviews = $stmt->fetchAll();

// Get review statistics
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_reviews,
        AVG(rating) as avg_rating,
        SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star,
        SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star,
        SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star,
        SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star,
        SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star
    FROM reviews 
    WHERE product_id = ?
");
$stmt->execute([$product_id]);
$stats = $stmt->fetch();

$page_title = 'Reviews - ' . $product['name'];
include 'includes/header.php';
?>

<div class="container">
    <div style="margin-bottom: 2rem;">
        <a href="product.php?id=<?php echo $product_id; ?>" style="color: #667eea; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Back to Product
        </a>
    </div>
    
    <h1 style="margin-bottom: 2rem;">Reviews for <?php echo htmlspecialchars($product['name']); ?></h1>
    
    <!-- Review Statistics -->
    <div style="background: white; border-radius: 15px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: center;">
            <div style="text-align: center;">
                <div style="font-size: 3rem; font-weight: bold; color: #667eea; margin-bottom: 0.5rem;">
                    <?php echo $stats['total_reviews'] > 0 ? number_format($stats['avg_rating'], 1) : '0.0'; ?>
                </div>
                <div class="star-rating" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <?php
                    $avg_rating = $stats['avg_rating'] ?? 0;
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $avg_rating) {
                            echo '<i class="fas fa-star" style="color: #ffd700;"></i>';
                        } elseif ($i - 0.5 <= $avg_rating) {
                            echo '<i class="fas fa-star-half-alt" style="color: #ffd700;"></i>';
                        } else {
                            echo '<i class="far fa-star" style="color: #ddd;"></i>';
                        }
                    }
                    ?>
                </div>
                <div style="color: #666;">
                    <?php echo $stats['total_reviews']; ?> review<?php echo $stats['total_reviews'] != 1 ? 's' : ''; ?>
                </div>
            </div>
            
            <div>
                <?php if ($stats['total_reviews'] > 0): ?>
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <?php 
                        $count = $stats[['', 'one_star', 'two_star', 'three_star', 'four_star', 'five_star'][$i]];
                        $percentage = ($count / $stats['total_reviews']) * 100;
                        ?>
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                            <span style="width: 60px;"><?php echo $i; ?> star</span>
                            <div style="flex: 1; background: #f0f0f0; height: 8px; border-radius: 4px; margin: 0 1rem; overflow: hidden;">
                                <div style="width: <?php echo $percentage; ?>%; height: 100%; background: #ffd700; border-radius: 4px;"></div>
                            </div>
                            <span style="width: 40px; text-align: right;"><?php echo $count; ?></span>
                        </div>
                    <?php endfor; ?>
                <?php else: ?>
                    <div style="text-align: center; color: #666;">
                        No reviews yet. Be the first to review this product!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Add Review Form -->
    <?php if (isLoggedIn()): ?>
        <?php
        // Check if user already reviewed
        $stmt = $pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        $user_reviewed = $stmt->fetch();
        ?>
        
        <?php if (!$user_reviewed): ?>
            <div style="background: white; border-radius: 15px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <h3 style="margin-bottom: 1.5rem;">Write a Review</h3>
                
                <form id="reviewForm">
                    <input type="hidden" id="productId" value="<?php echo $product_id; ?>">
                    
                    <div class="form-group">
                        <label>Rating *</label>
                        <div class="star-input" style="font-size: 2rem; margin: 0.5rem 0;">
                            <i class="far fa-star" data-rating="1"></i>
                            <i class="far fa-star" data-rating="2"></i>
                            <i class="far fa-star" data-rating="3"></i>
                            <i class="far fa-star" data-rating="4"></i>
                            <i class="far fa-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" id="rating" value="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="reviewTitle">Review Title *</label>
                        <input type="text" id="reviewTitle" maxlength="200" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="reviewComment">Your Review *</label>
                        <textarea id="reviewComment" rows="4" maxlength="1000" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            </div>
        <?php else: ?>
            <div style="background: #e3f2fd; border-radius: 10px; padding: 1.5rem; margin-bottom: 2rem; text-align: center;">
                <i class="fas fa-check-circle" style="color: #1976d2; font-size: 2rem; margin-bottom: 0.5rem;"></i>
                <p style="color: #1976d2; margin: 0;">You have already reviewed this product. Thank you for your feedback!</p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div style="background: white; border-radius: 15px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="margin-bottom: 1rem;">Want to write a review?</h3>
            <p style="color: #666; margin-bottom: 1.5rem;">Please log in to share your experience with this product.</p>
            <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-primary">Login to Review</a>
        </div>
    <?php endif; ?>
    
    <!-- Reviews List -->
    <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 2rem;">Customer Reviews</h3>
        
        <?php if (empty($reviews)): ?>
            <div style="text-align: center; padding: 2rem; color: #666;">
                <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; color: #ddd;"></i>
                <p>No reviews yet. Be the first to share your experience!</p>
            </div>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div style="border-bottom: 1px solid #eee; padding: 1.5rem 0;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <div style="font-weight: bold; margin-bottom: 0.25rem;">
                                <?php echo htmlspecialchars($review['full_name']); ?>
                            </div>
                            <div class="star-rating" style="margin-bottom: 0.25rem;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="<?php echo $i <= $review['rating'] ? 'fas' : 'far'; ?> fa-star" 
                                       style="color: <?php echo $i <= $review['rating'] ? '#ffd700' : '#ddd'; ?>;"></i>
                                <?php endfor; ?>
                            </div>
                            <div style="color: #666; font-size: 0.9rem;">
                                <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                    
                    <h4 style="margin-bottom: 0.5rem; color: #333;">
                        <?php echo htmlspecialchars($review['title']); ?>
                    </h4>
                    
                    <p style="color: #666; line-height: 1.6; margin: 0;">
                        <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
// Star rating input
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-input i');
    const ratingInput = document.getElementById('rating');
    
    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.dataset.rating);
            highlightStars(rating);
        });
        
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingInput.value = rating;
            highlightStars(rating);
        });
    });
    
    document.querySelector('.star-input').addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value);
        highlightStars(currentRating);
    });
    
    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.className = 'fas fa-star';
                star.style.color = '#ffd700';
            } else {
                star.className = 'far fa-star';
                star.style.color = '#ddd';
            }
        });
    }
});

// Submit review
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const productId = document.getElementById('productId').value;
    const rating = document.getElementById('rating').value;
    const title = document.getElementById('reviewTitle').value.trim();
    const comment = document.getElementById('reviewComment').value.trim();
    
    if (!rating || rating < 1) {
        showNotification('Please select a rating', 'error');
        return;
    }
    
    if (!title || !comment) {
        showNotification('Please fill in all required fields', 'error');
        return;
    }
    
    fetch('ajax/add_review.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: parseInt(productId),
            rating: parseInt(rating),
            title: title,
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Review submitted successfully!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification(data.message || 'Error submitting review', 'error');
        }
    })
    .catch(error => {
        showNotification('Error submitting review', 'error');
    });
});
</script>

<?php include 'includes/footer.php'; ?>