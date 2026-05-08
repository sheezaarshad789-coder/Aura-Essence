<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <h3>Aura & Essence</h3>
            <p>Crafting intangible moments of beauty through the ancient art of perfumery. Each fragrance is a meditation on memory, nature, and the passage of time.</p>
        </div>
        <div class="footer-col">
            <h4>Explore</h4>
            <ul>
                <li><a href="products.php">Full Collection</a></li>
                <li><a href="products.php?vibe=Fresh">Fresh Scents</a></li>
                <li><a href="products.php?vibe=Romantic">Romantic Scents</a></li>
                <li><a href="products.php?vibe=woody">Woody Scents</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>About</h4>
            <ul>
                <li><a href="index.php#our-philosophy">Our Philosophy</a></li>
                <li><a href="index.php#ingredients">Ingredients</a></li>
                <li><a href="index.php#sustainability">Sustainability</a></li>
                <li><a href="index.php#journal">Journal</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> 2023 Aura & Essence. All rights reserved.</p>
    </div>
</footer>
<script>
function toggleMobileMenu() {
    document.getElementById('navLinks').classList.toggle('open');
}

function openCartDrawer() {
    document.getElementById('cartOverlay').classList.add('open');
    document.getElementById('cartDrawer').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeCartDrawer() {
    document.getElementById('cartOverlay').classList.remove('open');
    document.getElementById('cartDrawer').classList.remove('open');
    document.body.style.overflow = '';
}

function openPyramid(name, top, middle, base) {
    document.getElementById('pyramidName').textContent = name;
    document.getElementById('pyramidTop').textContent = top;
    document.getElementById('pyramidMiddle').textContent = middle;
    document.getElementById('pyramidBase').textContent = base;
    document.getElementById('pyramidOverlay').classList.add('open');
}

function closePyramid(e) {
    if (e.target === e.currentTarget) {
        document.getElementById('pyramidOverlay').classList.remove('open');
    }
}
function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2500);
}

// Scroll fade-in
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

async function syncCartUIFromServer() {
    const response = await fetch(window.location.href, { cache: 'no-store' });
    const html = await response.text();
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');

    const currentDrawerBody = document.getElementById('cartDrawerBody');
    const nextDrawerBody = doc.getElementById('cartDrawerBody');
    if (currentDrawerBody && nextDrawerBody) {
        currentDrawerBody.innerHTML = nextDrawerBody.innerHTML;
    }

    const currentDrawer = document.getElementById('cartDrawer');
    const nextDrawer = doc.getElementById('cartDrawer');
    if (currentDrawer && nextDrawer) {
        const currentFooter = currentDrawer.querySelector('.cart-drawer-footer');
        const nextFooter = nextDrawer.querySelector('.cart-drawer-footer');
        if (nextFooter) {
            if (currentFooter) {
                currentFooter.replaceWith(nextFooter);
            } else {
                currentDrawer.appendChild(nextFooter);
            }
        } else if (currentFooter) {
            currentFooter.remove();
        }
    }

    const currentNavCart = document.querySelector('.nav-cart');
    const nextBadge = doc.querySelector('.nav-cart .cart-badge');
    if (currentNavCart) {
        const currentBadge = currentNavCart.querySelector('.cart-badge');
        if (nextBadge) {
            if (currentBadge) {
                currentBadge.textContent = nextBadge.textContent;
            } else {
                currentNavCart.appendChild(nextBadge);
            }
        } else if (currentBadge) {
            currentBadge.remove();
        }
    }
}

document.addEventListener('submit', async (e) => {
    const form = e.target;
    if (!(form instanceof HTMLFormElement)) return;
    if (form.getAttribute('action') !== 'cart.php') return;

    e.preventDefault();
    const formData = new FormData(form);
    if (e.submitter && e.submitter.name) {
        formData.set(e.submitter.name, e.submitter.value);
    }

    try {
        await fetch('cart.php', {
            method: 'POST',
            body: formData
        });
        await syncCartUIFromServer();
        if (typeof showToast === 'function') {
            showToast('Cart updated');
        }
    } catch (error) {
        if (typeof showToast === 'function') {
            showToast('Unable to update cart. Please try again.');
        }
    }
});
</script>
</body>
</html>