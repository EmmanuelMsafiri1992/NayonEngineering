/**
 * Nayon Engineering - Laravel Version
 * Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initProductTabs();
    initViewModes();
});

/**
 * Navigation Toggle for Mobile
 */
function initNavigation() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
            }
        });
    }
}

/**
 * Product Tabs
 */
function initProductTabs() {
    const tabs = document.querySelectorAll('.product-tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.dataset.tab;

            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => {
                c.classList.remove('active');
                c.style.display = 'none';
            });

            this.classList.add('active');
            const content = document.getElementById(tabId);
            if (content) {
                content.classList.add('active');
                content.style.display = 'block';
            }
        });
    });
}

/**
 * View Mode Toggle (Grid/List)
 */
function initViewModes() {
    const viewButtons = document.querySelectorAll('.view-mode');
    const productGrid = document.querySelector('.product-grid');

    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;

            viewButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            if (productGrid) {
                if (view === 'list') {
                    productGrid.style.gridTemplateColumns = '1fr';
                } else {
                    productGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(280px, 1fr))';
                }
            }
        });
    });
}

/**
 * Add to Cart
 */
function addToCart(productId, quantity = 1) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateCartCount(data.cartCount);
        } else {
            showToast(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error adding to cart', 'error');
    });
}

/**
 * Add to Wishlist
 */
function addToWishlist(productId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch('/wishlist/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        showToast(data.message, data.success ? 'success' : 'info');
        if (data.success) {
            updateWishlistCount(data.wishlistCount);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error adding to wishlist', 'error');
    });
}

/**
 * Update Cart Count in Header
 */
function updateCartCount(count) {
    const cartAction = document.querySelector('.header-action[href*="cart"]');
    if (cartAction) {
        let countSpan = cartAction.querySelector('.count');
        if (count > 0) {
            if (!countSpan) {
                countSpan = document.createElement('span');
                countSpan.className = 'count';
                const icon = cartAction.querySelector('i');
                icon.after(countSpan);
            }
            countSpan.textContent = count;
        } else if (countSpan) {
            countSpan.remove();
        }
    }
}

/**
 * Update Wishlist Count in Header
 */
function updateWishlistCount(count) {
    const wishlistAction = document.querySelector('.header-action[href*="wishlist"]');
    if (wishlistAction) {
        let countSpan = wishlistAction.querySelector('.count');
        if (count > 0) {
            if (!countSpan) {
                countSpan = document.createElement('span');
                countSpan.className = 'count';
                const icon = wishlistAction.querySelector('i');
                icon.after(countSpan);
            }
            countSpan.textContent = count;
        } else if (countSpan) {
            countSpan.remove();
        }
    }
}

/**
 * Show Toast Notification
 */
function showToast(message, type = 'info') {
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : 'fa-info-circle'}"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 100);

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Newsletter form handler
const newsletterForm = document.getElementById('newsletterForm');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('Thank you for subscribing!', 'success');
        this.reset();
    });
}
