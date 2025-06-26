body {
background-color: var(--bs-body-bg);
color: var(--bs-body-color);
}

.hero {
background: url('https://images.unsplash.com/photo-1523275335684-37898b6baf30?fit=crop&w=1350&q=80') center/cover no-repeat;
padding: 60px 15px 40px;
color: white;
text-shadow: 0 2px 4px rgba(0, 0, 0, 0.7);
}

.search-box {
max-width: 600px;
margin: 0 auto;
}

.product-card {
border: none;
border-radius: 8px;
overflow: hidden;
box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
transition: transform 0.2s;
}

.product-card:hover {
transform: translateY(-5px);
}

.product-card img {
height: 200px;
width: 100%;
object-fit: cover;
}

#cart-panel {
position: fixed;
right: 0;
top: 60px;
width: 350px;
height: calc(100% - 60px);
background-color: var(--bs-body-bg);
color: var(--bs-body-color);
box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
z-index: 1050;
padding: 1rem;
overflow-y: auto;
display: none;
transition: background-color 0.3s ease, color 0.3s ease;
}

.cart-item {
border-bottom: 1px solid #ddd;
padding: 10px 0;
}

.cart-item:last-child {
border-bottom: none;
}

.qty-controls button {
padding: 2px 8px;
}

.remove-btn {
color: red;
cursor: pointer;
font-weight: bold;
}

@media (max-width: 768px) {
.product-card img {
height: 150px;
}

#cart-panel {
width: 100%;
top: 56px;
height: calc(100% - 56px);
}
}