<style>
    #product-container {
        position: sticky;
        top:20px;
        flex-direction: row;
        float:left;
        padding: 10px;
        border: 1px solid #ccc;
        width:50%;
        min-height:80vh;
        max-height: 80vh;
        overflow: auto;
        border-left:none;
        border-right:none;

    }
    #cart-container {
        display: flex;
        align-items: center;
        flex-direction: column;
        float:left;
        width: 50%;
        min-height:10%;
        padding: 10px;
        border: 1px solid #ccc;
        min-height:80vh;
        max-height: 80vh;
        overflow: auto;
    }
    .draggable {
        float: left;
        width: 30vh;
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
        width: 50vh;
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

    div.stickyy {
        position: sticky;
        top: 0px;
        background-color: yellow;
        padding: 50px;
        font-size: 20px;
    }

</style>
<div class="box">

<div id="product-container" class="sticky-element">
    @forelse($markerData as $mark)
    <div id="{{$mark['id']}}" class="draggable " draggable="true" data_queue="{{$mark['queue']}}">{{$mark['name']}}</div>
    @empty
    @endforelse
</div>
<div id="cart-container">
    @foreach($markerData as $index => $mark)
    <div class="cart" data_queue="{{$index + 1}}">Punkt nr. {{$index + 1 }}</div>
    @endforeach
</div>
</div>

<script>
    const products = document.querySelectorAll('.draggable');
    const carts = document.querySelectorAll('.cart');

    Echo.private('privateTrip.{{$trip->trip_id}}')
        .listen('TripEvent', (e) => {

            const productContainer = document.getElementById('product-container');
            const cartContainer = document.getElementById('cart-container');

            fetch(location.href)
                .then(response => response.json())
                .then(data => {
                    if (data.productContainer) {
                        productContainer.innerHTML = data.productContainer;
                    }
                    if (data.cartContainer) {
                        cartContainer.innerHTML = data.cartContainer;
                    }
                    console.info('Divs refreshed successfully.');
                })
                .catch(error => {
                    console.error('Error fetching updated divs:', error);
                });
        });

    move();
    function move() {
        products.forEach(product => {
            const cartId = product.getAttribute('data_queue');
            if (cartId) {
                const cart = document.querySelector(`.cart[data_queue="${cartId}"]`);
                if (cart) {
                    cart.appendChild(product);
                    addRemoveButton(product);
                }
            }
        });
    }

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
            const cartId = cart.getAttribute('data_queue');

            if (cartHasProduct(cartId)) {
                return;
            }

            const data = {
                productId: productId,
                cartId: cartId
            };

            axios.post('{{route('addQueue')}}', data)
                .then(response => {
                    console.log(response.data);

                })
                .catch(error => {
                    console.error(error);

                });

            product.setAttribute('data_queue', cartId);
            cart.appendChild(product);
            addRemoveButton(product);

            product.style.animation = 'addToCart 0.5s';
            product.addEventListener('animationend', () => {
                product.style.animation = '';
            });
        });
    });

    function addRemoveButton(product) {
        if (!product.querySelector('.remove-button')) {
            const cartId = product.getAttribute('data_queue');
            const productContainer = document.getElementById('product-container');
            const productId = product.id;
            const removeButton = document.createElement('button');
            removeButton.className = 'remove-button';
            removeButton.innerText = 'Remove';
            removeButton.id = `rmbtn_${productId}`;
            removeButton.addEventListener('click', () => {

                axios.post('{{route('delQueue')}}', { productId: productId })
                    .then(response => {
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.error(error);
                    });

                product.setAttribute('data_queue', "");
                productContainer.appendChild(product);

                product.style.animation = 'removeFromCart 0.5s';
                product.addEventListener('animationend', () => {
                    product.style.animation = '';
                });

                removeButton.remove();
            });
            product.appendChild(removeButton);
        }
    }

    function cartHasProduct(queueId) {
        const productsInCart = document.querySelectorAll(`.draggable[data_queue="${queueId}"]`);
        return productsInCart.length > 0;
    }
    Echo.private('privateTrip.{{$trip->trip_id}}')
        .listen('AddQueueEvent', (e) => {
            const mark = e.mark;
            const markId = e.mark.mark_id;
            const markElement = document.getElementById(markId);
            const cartId = mark.queue;

            // Sprawdź, czy koszyk już ma produkt
            if (cartHasProduct(cartId)) {
                return;
            }

            // Jeśli nie, to możesz przenieść produkt do właściwego koszyka
            const cart = document.querySelector(`.cart[data_queue="${cartId}"]`);
            markElement.setAttribute('data_queue', cartId);
            cart.appendChild(markElement);
            addRemoveButton(markElement);
            markElement.style.animation = 'addToCart 0.5s';
            markElement.addEventListener('animationend', () => {
                markElement.style.animation = '';
            });
        });

    Echo.private('privateTrip.{{$trip->trip_id}}')
        .listen('DelQueueEvent', (e) => {
            const mark = e.mark;
            const markId = e.mark.mark_id;
            const markElement = document.getElementById(markId);
            const MarkQueue = markElement.getAttribute('data_queue');
            const RemoveButton = document.getElementById(`rmbtn_${markId}`);
            const cartId = mark.queue;
            const MarkContainer = document.getElementById('product-container');

            if (MarkQueue > 0) {
                markElement.setAttribute('data_queue', "");
                MarkContainer.appendChild(markElement);
                RemoveButton.remove();
                markElement.style.animation = 'removeFromCart 0.5s';
                markElement.addEventListener('animationend', () => {
                    markElement.style.animation = '';
                });
            }
        });


</script>
