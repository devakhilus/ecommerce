let offset = 0, limit = 6, currentSearch = '';

function fetchProducts(reset = false) {
    if (reset) offset = 0;
    document.getElementById('loading-spinner').style.display = 'block';

    fetch(`${BACKEND_URL}/api/products-api?limit=${limit}&offset=${offset}&search=${encodeURIComponent(currentSearch)}`)
        .then(res => res.json())
        .then(products => {
            if (reset) document.getElementById('product-list').innerHTML = '';
            products.forEach(product => {
                const image = product.image_url ?? 'https://via.placeholder.com/300x200';
                const col = document.createElement('div');
                col.className = 'col-md-4 col-sm-6';
                col.innerHTML = `
                    <div class="card product-card h-100">
                        <img src="${image}" class="card-img-top" alt="${product.name}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text flex-grow-1">${(product.description ?? '').substring(0, 100)}</p>
                            <p class="fw-bold">₹${parseFloat(product.price).toFixed(2)}</p>
                            <div class="d-flex justify-content-between mt-auto gap-2">
                                <a href="/product/${product.id}" class="btn btn-primary w-50">Buy Now</a>
                                <button class="btn btn-outline-secondary w-50" onclick='addToCart(${JSON.stringify(product)})'>Add to Cart</button>
                            </div>
                        </div>
                    </div>`;
                document.getElementById('product-list').appendChild(col);
            });
            offset += limit;
            markAddedProducts();
        })
        .finally(() => {
            document.getElementById('loading-spinner').style.display = 'none';
        });
}

document.addEventListener('DOMContentLoaded', () => {
    updateCartDisplay();
    fetchProducts();

    document.getElementById('searchInput').addEventListener('input', () => {
        clearTimeout(window.searchTimer);
        window.searchTimer = setTimeout(() => {
            currentSearch = document.getElementById('searchInput').value.trim();
            fetchProducts(true);
        }, 300);
    });

    document.getElementById('load-more').onclick = () => fetchProducts();

    document.getElementById('cart-toggle').onclick = () => {
        const panel = document.getElementById('cart-panel');
        panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
        updateCartDisplay();
    };

    document.getElementById('checkout-btn').onclick = () => {
        if (confirm('Proceed to checkout?')) alert('Checkout logic here');
    };

    // Theme toggle
    const htmlEl = document.documentElement;
    const toggleBtn = document.getElementById('theme-toggle');
    const icon = document.getElementById('theme-icon');

    function applyTheme(theme) {
        htmlEl.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);
        icon.textContent = theme === 'dark' ? '🌞' : '🌙';
    }

    applyTheme(localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
    toggleBtn.addEventListener('click', () => {
        const current = htmlEl.getAttribute('data-bs-theme');
        applyTheme(current === 'dark' ? 'light' : 'dark');
    });
});
