// Access the Images
let slideImages = document.querySelectorAll('.slides img');
// Access the next and prev buttons
let next = document.querySelector('.next');
let prev = document.querySelector('.prev');
// Access the indicators
let dots = document.querySelectorAll('.dot');

let counter = 0;
// Code for next button
next.addEventListener('click', slideNext);
function slideNext() {
    slideImages[counter].style.animation = 'next1 0.5s ease-in forwards';
    counter = (counter + 1) % slideImages.length; // Increment counter and loop back to 0 if it exceeds the image count
    slideImages[counter].style.animation = 'next2 0.5s ease-in forwards';
    indicators();
}

// Code for prev button
prev.addEventListener('click', slidePrev);
function slidePrev() {
    slideImages[counter].style.animation = 'prev1 0.5s ease-in forwards';
    counter = (counter - 1 + slideImages.length) % slideImages.length; // Decrement counter and loop back to the last image index if it goes below 0
    slideImages[counter].style.animation = 'prev2 0.5s ease-in forwards';
    indicators();
}

// Add and remove active class from the indicators
function indicators() {
    for (let i = 0; i < dots.length; i++) {
        dots[i].classList.remove('active');
    }
    dots[counter].classList.add('active');
}

// Initial button state
if (counter === 0) {
    prev.disabled = true; // Disable prev button initially
}

// auto sliding function
function autoSlide() {
  setInterval(slideNext, 3000); // Slide to the next image every 3 seconds (adjust as needed)
}

// auto sliding
autoSlide();










document.addEventListener('DOMContentLoaded', function() { //load data from local storage
    const items = JSON.parse(localStorage.getItem('shopItems')) || [];
    const itemSection = document.getElementById('item-section');

    items.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.className = 'item';
        itemElement.innerHTML = `
            <div class="content" style="overflow: hidden;">
                <img src="${item.imageDataUrl}" alt="${item.name}" style="float: left; margin-right: 10px; width: 30%;">
                <div style="float: left; width: 70%; margin-top: -40px; margin-left: 20px;">
                    <p>${item.name}</p>
                    <p>Price : ${item.price} THB</p>
                    <button class="button" style="float: right;" onclick="addToCart('${item.name}', ${item.price}, '${item.imageDataUrl}')">+</button>
                </div>
            </div>
        `;
        itemSection.appendChild(itemElement);
    });
});











 const cart = []; // cart sys
        let totalPrice = 0;

        function addToCart(product, price, imgSrc) {
            cart.push({ name: product, price: price, imgSrc: imgSrc });
            totalPrice += price;
            updateCartCount();
        }

        function updateCartCount() {
            document.getElementById('cart-count').textContent = cart.length;
        }

        function toggleCart() {
            const productsView = document.getElementById('products-view');
            const cartItems = document.getElementById('cart-items');
            if (cartItems.style.display === 'none') {
                displayCart();
                cartItems.style.display = 'block';
                productsView.style.display = 'none';
            } else {
                cartItems.style.display = 'none';
                productsView.style.display = 'block';
            }
        }

        function generateTransactionId() { // ID confirmation for each transaction
            return Math.floor(1000 + Math.random() * 9000);
        }


        function displayCart() {
            const cartList = document.getElementById('cart');

            cartList.innerHTML = '';
            if (cart.length === 0) {
                cartList.innerHTML = '<li>Your cart is empty.</li>';
            } else {
                cart.forEach((item) => {
                    const li = document.createElement('li');
                    li.innerHTML = `<img src="${item.imgSrc}" alt="${item.name}" style="width: 50px; height: auto; vertical-align: middle; margin-right: 10px;">${item.name} - ${item.price} THB`;
                    cartList.appendChild(li);
                });
            }
            document.getElementById('total-price').textContent = totalPrice;

        }

        function clearCart() {
            cart.length = 0; // Clear the cart array
            totalPrice = 0; // Reset total price
            updateCartCount(); // Update the cart count in the UI
            displayCart(); // Refresh the cart display
        }


        

        
        function checkout() { 
            if (cart.length === 0) {
                alert('Your cart is empty. Please add items before checking out.');
                return;
            }
        
            const productsView = document.getElementById('products-view');
            const checkoutSection = document.getElementById('checkout-section');
            const totalPriceElement = document.getElementById('checkout-total-price');
            const transactionIdElement = document.getElementById('transaction-id'); // Added this line
        
            const transactionId = generateTransactionId(); // Generate transaction ID
            toggleCart(); // Toggle cart to hide it
            productsView.style.display = 'none'; // Hide products view
            checkoutSection.style.display = 'block'; // Show checkout section
        
            totalPriceElement.textContent = totalPrice; // Display total price in the checkout section
            transactionIdElement.textContent = transactionId; // Display transaction ID in the checkout section
        }
        
        function goBackToShopping() {
            window.location.href = "./index.html";
        }
  













function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}




$


