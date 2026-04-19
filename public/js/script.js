/**
 * Elixira storefront scripts — mobile nav, scroll reveals, optional localStorage cart helpers.
 * Checkout uses server session cart; localStorage cart is only for legacy / static pages.
 */
const productsData = {};

window.updateCartCount = () => {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.querySelectorAll('.cart-badge').forEach((badge) => {
        badge.textContent = totalItems;
        badge.style.display = totalItems > 0 ? 'inline-flex' : 'none';
    });
};

window.addToCart = (name, price, image, quantity = 1) => {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItemIndex = cart.findIndex((item) => item.name === name);

    if (existingItemIndex > -1) {
        cart[existingItemIndex].quantity += quantity;
    } else {
        cart.push({ name, price, image, quantity });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showToast(`Added ${name} to your bag`);
};

function showToast(message) {
    let toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', () => {
    const mobileBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');

    if (mobileBtn && navLinks) {
        mobileBtn.addEventListener('click', () => {
            mobileBtn.classList.toggle('active');
            navLinks.classList.toggle('active');
            document.body.classList.toggle('no-scroll');
        });
    }

    document.querySelectorAll('.nav-links a, .nav-links .nav-login-btn, .nav-links .nav-register-link').forEach((link) => {
        link.addEventListener('click', () => {
            if (!mobileBtn || !navLinks) return;
            mobileBtn.classList.remove('active');
            navLinks.classList.remove('active');
            document.body.classList.remove('no-scroll');
        });
    });

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px',
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.scroll-reveal').forEach((el) => observer.observe(el));

    const navbar = document.querySelector('.navbar-custom');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
                navbar.style.padding = '0.5rem 0';
            } else {
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
                navbar.style.padding = '1rem 0';
            }
        });
    }

    const initFavorites = () => {
        const favBtns = document.querySelectorAll('.fav-btn');
        const favorites = JSON.parse(localStorage.getItem('favorites')) || [];

        favBtns.forEach((btn) => {
            const itemName = btn.dataset.name;
            if (favorites.includes(itemName)) {
                btn.classList.add('active');
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                }
            }

            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const name = btn.dataset.name;
                const index = favorites.indexOf(name);
                if (index === -1) {
                    favorites.push(name);
                    btn.classList.add('active');
                } else {
                    favorites.splice(index, 1);
                    btn.classList.remove('active');
                }
                localStorage.setItem('favorites', JSON.stringify(favorites));
            });
        });
    };
    initFavorites();

    updateCartCount();

    if (window.location.pathname.includes('product-details.html')) {
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id');

        if (productId && productsData[productId]) {
            const product = productsData[productId];

            const imgEl = document.getElementById('product-image');
            const nameEl = document.getElementById('product-name');
            const priceEl = document.getElementById('product-price');
            const descEl = document.getElementById('product-description');

            if (imgEl) imgEl.src = product.image;
            if (nameEl) nameEl.textContent = product.name;
            if (priceEl) priceEl.textContent = '$' + Number(product.price).toFixed(2);
            if (descEl) descEl.textContent = product.desc;

            window.addToCartFromDetails = () => {
                const qtyInput = document.getElementById('product-qty');
                const quantity = parseInt(qtyInput.value, 10) || 1;
                addToCart(product.name, product.price, product.image, quantity);
            };
        }
    }
});
