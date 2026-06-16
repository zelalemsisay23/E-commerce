// ElectroMart JavaScript Functions

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function () {
    // Initialize dropdown menus
    initDropdowns();

    // Initialize search functionality
    initSearch();

    // Initialize cart functionality
    initCart();

    // Initialize form validation
    initFormValidation();
});

// Dropdown menu functionality
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        if (toggle && menu) {
            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target)) {
                    menu.style.opacity = '0';
                    menu.style.visibility = 'hidden';
                    menu.style.transform = 'translateY(-10px)';
                }
            });
        }
    });
}

// Search functionality
function initSearch() {
    const searchForm = document.querySelector('.search-box form');
    const searchInput = document.querySelector('.search-box input');

    if (searchForm && searchInput) {
        // Add search suggestions (can be enhanced with AJAX)
        searchInput.addEventListener('input', function () {
            const query = this.value.trim();
            if (query.length > 2) {
                // Implement search suggestions here
                showSearchSuggestions(query);
            } else {
                hideSearchSuggestions();
            }
        });
    }
}

// Search suggestions
function showSearchSuggestions(query) {
    // This can be enhanced with AJAX calls to get real suggestions
    const suggestions = [
        'iPhone 15 Pro',
        'Samsung Galaxy S24',
        'MacBook Pro',
        'iPad Air',
        'AirPods Pro'
    ].filter(item => item.toLowerCase().includes(query.toLowerCase()));

    // Create suggestions dropdown (simplified version)
    let suggestionsBox = document.querySelector('.search-suggestions');
    if (!suggestionsBox) {
        suggestionsBox = document.createElement('div');
        suggestionsBox.className = 'search-suggestions';
        suggestionsBox.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
        `;
        document.querySelector('.search-box').appendChild(suggestionsBox);
    }

    suggestionsBox.innerHTML = suggestions.map(suggestion =>
        `<div class="suggestion-item" style="padding: 0.5rem 1rem; cursor: pointer; border-bottom: 1px solid #eee;" 
         onclick="selectSuggestion('${suggestion}')">${suggestion}</div>`
    ).join('');
}

function hideSearchSuggestions() {
    const suggestionsBox = document.querySelector('.search-suggestions');
    if (suggestionsBox) {
        suggestionsBox.remove();
    }
}

function selectSuggestion(suggestion) {
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.value = suggestion;
        document.querySelector('.search-box form').submit();
    }
}

// Cart functionality
function initCart() {
    // Update cart count on page load
    updateCartCount();

    // Initialize quantity controls
    const quantityControls = document.querySelectorAll('.quantity-controls');
    quantityControls.forEach(control => {
        const minusBtn = control.querySelector('.qty-minus');
        const plusBtn = control.querySelector('.qty-plus');
        const input = control.querySelector('.qty-input');

        if (minusBtn && plusBtn && input) {
            minusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value) || 1;
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    updateCartItem(input.dataset.productId, input.value);
                }
            });

            plusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value) || 1;
                const maxStock = parseInt(input.dataset.maxStock) || 999;
                if (currentValue < maxStock) {
                    input.value = currentValue + 1;
                    updateCartItem(input.dataset.productId, input.value);
                }
            });

            input.addEventListener('change', () => {
                const value = parseInt(input.value) || 1;
                const maxStock = parseInt(input.dataset.maxStock) || 999;
                if (value < 1) input.value = 1;
                if (value > maxStock) input.value = maxStock;
                updateCartItem(input.dataset.productId, input.value);
            });
        }
    });
}

// Add to cart function
function addToCart(productId, quantity = 1) {
    fetch('ajax/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                showNotification('Product added to cart!', 'success');
            } else {
                showNotification(data.message || 'Error adding to cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error adding to cart', 'error');
        });
}

// Update cart item quantity
function updateCartItem(productId, quantity) {
    fetch('ajax/update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                updateCartTotal();
            } else {
                showNotification(data.message || 'Error updating cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating cart', 'error');
        });
}

// Remove from cart
function removeFromCart(productId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        fetch('ajax/remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Refresh the cart page
                } else {
                    showNotification(data.message || 'Error removing item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error removing item', 'error');
            });
    }
}

// Update cart count in header
function updateCartCount() {
    fetch('ajax/get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.count || 0;
            }
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
}

// Update cart total
function updateCartTotal() {
    fetch('ajax/get_cart_total.php')
        .then(response => response.json())
        .then(data => {
            const totalElement = document.querySelector('.cart-total');
            if (totalElement) {
                totalElement.textContent = '$' + parseFloat(data.total || 0).toFixed(2);
            }
        })
        .catch(error => {
            console.error('Error updating cart total:', error);
        });
}

// Form validation
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
}

function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'This field is required');
            isValid = false;
        } else {
            clearFieldError(field);
        }

        // Email validation
        if (field.type === 'email' && field.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                showFieldError(field, 'Please enter a valid email address');
                isValid = false;
            }
        }

        // Password validation
        if (field.type === 'password' && field.value.trim()) {
            if (field.value.length < 6) {
                showFieldError(field, 'Password must be at least 6 characters long');
                isValid = false;
            }
        }
    });

    return isValid;
}

function showFieldError(field, message) {
    clearFieldError(field);

    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.cssText = 'color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;';
    errorDiv.textContent = message;

    field.parentNode.appendChild(errorDiv);
    field.style.borderColor = '#dc3545';
}

function clearFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    field.style.borderColor = '';
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    const notification = document.createElement('div');
    notification.className = `notification alert alert-${type === 'success' ? 'success' : 'error'}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 400px;
        padding: 1rem;
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        animation: slideIn 0.3s ease-out;
    `;

    // Add animation keyframes
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }

    notification.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: inherit; margin-left: 1rem;">&times;</button>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Image lazy loading
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}

// Initialize lazy loading if supported
if ('IntersectionObserver' in window) {
    initLazyLoading();
}