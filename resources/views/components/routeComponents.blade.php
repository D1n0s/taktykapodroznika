<style>
    #product-container {
        position: relative;
        float:left;
        width: 30vh;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #f0f0f0;
    }
    #cart-container {
        position: relative;
        float:left;
        width: 50vh;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        margin-left: 20px;
    }
    .draggable {
        padding: 3vh;
        margin: 5px;
        background-color: #fff;
        border: 1px solid #ccc;
        cursor: pointer;
    }
    .draggable:hover {
        background-color: #e0e0e0;
    }
    .cart {
        padding: 2vh;
        margin: 5px;
        background-color: #dff0d8;
        border: 1px solid #4cae4c;
    }
    .remove-button {
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 3px 8px;
        cursor: pointer;
        margin-left: 5px;
    }
    /* Animacja po dodaniu do koszyka */
    @keyframes addToCart {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* Animacja po usunięciu z koszyka */
    @keyframes removeFromCart {
        0% { transform: scale(1); }
        50% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
</style>
<div class="box">
<div id="product-container">
    <div id="product1" class="draggable" draggable="true" data-cartid="">Product 1</div>
    <div id="product2" class="draggable" draggable="true" data-cartid="">Product 2</div>
<div id="product3" class="draggable" draggable="true" data-cartid="">Product 3</div>
</div>

<div id="cart-container">
    <div id="cart1" class="cart" data-cartid="1">Cart 1</div>
    <div id="cart2" class="cart" data-cartid="2">Cart 2</div>
</div>
</div>
<script>
    const products = document.querySelectorAll('.draggable');
    const carts = document.querySelectorAll('.cart');

    products.forEach(product => {
        product.addEventListener('dragstart', (event) => {
            event.dataTransfer.setData('text/plain', event.target.id);
        });
    });

    carts.forEach(cart => {
        cart.addEventListener('dragover', (event) => {
            event.preventDefault();
        });

        cart.addEventListener('drop', (event) => {
            event.preventDefault();
            const productId = event.dataTransfer.getData('text/plain');
            const product = document.getElementById(productId);
            const cartId = cart.getAttribute('data-cartid');

            if (cartHasProduct(cartId)) {
                return;
            }

            product.setAttribute('data-cartid', cartId);
            cart.appendChild(product);
            addRemoveButton(product);

            // Dodanie klasy animacji po dodaniu do koszyka
            product.style.animation = 'addToCart 0.5s';
            product.addEventListener('animationend', () => {
                product.style.animation = '';
            });
        });
    });

    function addRemoveButton(product) {
        if (!product.querySelector('.remove-button')) {
            const removeButton = document.createElement('button');
            removeButton.className = 'remove-button';
            removeButton.innerText = 'Remove';
            removeButton.addEventListener('click', () => {
                const cartId = product.getAttribute('data-cartid');
                const productContainer = document.getElementById('product-container');
                product.removeAttribute('data-cartid');
                productContainer.appendChild(product);

                // Dodanie klasy animacji po usunięciu z koszyka
                product.style.animation = 'removeFromCart 0.5s';
                product.addEventListener('animationend', () => {
                    product.style.animation = '';
                });

                removeButton.remove();
            });
            product.appendChild(removeButton);
        }
    }

    function cartHasProduct(cartId) {
        const productsInCart = document.querySelectorAll(`.draggable[data-cartid="${cartId}"]`);
        return productsInCart.length > 0;
    }
</script>
