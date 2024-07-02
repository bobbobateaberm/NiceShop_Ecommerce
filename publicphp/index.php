<?php
session_start();
include 'db.php';

// Function to generate a guest ID
function generateGuestID() {
    return uniqid('guest_id', true); // Generate a unique guest ID
}

// Generate guest ID if the login button is clicked
if (isset($_POST['login'])) {
    $guestID = generateGuestID();
    $_SESSION['guestID'] = $guestID; // Store guest ID in session
    // Redirect to the same page to avoid resubmission
    header("Location: index.php");
    exit();
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'filter') {
        $tag = $_GET['tag'];
        $sql = "SELECT * FROM products";
        if (!empty($tag) && $tag != 'all') {
            $sql .= " WHERE tag = '$tag'";
        }
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='item' data-tag='" . $row['tag'] . "'>";
                echo "<div class='content'>";
                echo "<img src='" . $row['image_url'] . "' alt='" . $row['name'] . "'>";
                echo "<div class='details'>";
                echo "<p>Name: " . $row['name'] . "</p>";
                echo "<p>Price: " . $row['price'] . " THB</p>";
                echo "<p>Tag: " . $row['tag'] . "</p>";
                echo "<button class='button add-to-cart' data-id='" . $row['id'] . "'>+</button>";
                echo "</div></div></div>";
            }
        } else {
            echo "<p>No products found.</p>";
        }
        exit();
    }
}

// Default query to fetch initial products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Function to fetch distinct tags from the database
function fetchDistinctTags($conn) {
    $tags = [];
    $tagResult = $conn->query("SELECT DISTINCT tag FROM products");
    if ($tagResult && $tagResult->num_rows > 0) {
        while ($tagRow = $tagResult->fetch_assoc()) {
            $tags[] = $tagRow['tag'];
        }
    }
    return $tags;
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Page</title>
    <link href="./style.css?v=2.5" rel="stylesheet" />
    <link href="./indexstyle.css?v=2.2" rel="stylesheet" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
    <link rel="icon" type="image/x-icon" href="./pics1/Shop.ico">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>

        
  /* General Styles */
  body {
    font-family: Rubik;
  }





    </style>

</head>
<body>


<!-- HTML code -->
<div class="overlay" id="overlay"></div>
<div class="popup" id="popup">
    <h2>Login as Guest</h2>
    <form id="loginForm" method="post">
        <button type="submit" name="login" id="loginButton">Login as Guest</button>
        
    </form>
</div>


  
<div class="navbar1">
    <img src="./pics1/logo.png" style="width: 300px; max-width: 50%;">

    <button id="toggleCart" class="cart_button">
        <img style="width: 150px; max-width: 40%;" src="./pics1/cart.png">
        <span id="cartCount">0</span>
    </button>
</div>




<div id="product-view">
      



<div class="slide-container">
	
    <div class="slides">
      <img src="./pics1/1.png" class="active">
      <img src="./pics1/2.png">
      <img src="./pics1/3.png">
      <img src="./pics1/4.png">
      <img src="./pics1/5.png">
    </div>
  
    <div class="buttons">
      <span class="next">&#10095;</span>
      <span class="prev">&#10094;</span>
    </div>
  
    <div class="dotsContainer">
      <div class="dot active" attr='0' onclick="switchImage(this)"></div>
      <div class="dot" attr='1' onclick="switchImage(this)"></div>
      <div class="dot" attr='2' onclick="switchImage(this)"></div>
      <div class="dot" attr='3' onclick="switchImage(this)"></div>
      <div class="dot" attr='4' onclick="switchImage(this)"></div>
    </div>
  
  </div>





<div style="display: flex;justify-content: center;">

  <img class="ads" src="./pics1/ads.png">
</div>












<!-- Location -->
<div style="margin-top: 50px; margin-bottom: 50px;" class="container marketing">
    <p style="margin-bottom: 30px; font-size: 28px;" class="font1"><img style="width: 40px;" src="../pics1/pin.png"> Location </p>
    <div class="row featurette">
        <div class="col-md-7 order-md-2">
            <ul style="margin-top: 20px; font-size: 20px;" class="font2">
                <li>Location : <a href="https://maps.app.goo.gl/HEaVMD5k5AP418MeA">โรงอาหารสวัสดิการ โรงพยาบาลราชวิถี</a></li>
                <li>Telephone : 085-801-7888</li>
                <li>Line : nicehd2549 (no @) </li>
            </ul>
        </div>
        <div class="col-md-5 order-md-1">
            <div class="embed-responsive embed-responsive-16by9" style="border: 7px dashed #ea0006; margin-top: 20px;">
                <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.2598327215355!2d100.5352162!3d13.763198299999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29eb3ffc25aad%3A0xfd44f3590b65777e!2z4LmC4Lij4LiH4Lit4Liy4Lir4Liy4Lij4Liq4Lin4Lix4Liq4LiU4Li04LiB4Liy4LijIOC5guC4o-C4h-C4nuC4ouC4suC4muC4suC4peC4o-C4suC4iuC4p-C4tOC4luC4tQ!5e0!3m2!1sth!2sth!4v1716214146740!5m2!1sth!2sth" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>


 
   <!--location-->















   

<div style="background-color: rgb(247, 247, 247);width: 100%;padding: 1px;margin-top: 100px;">


<div style=" width: 68%; margin-left: auto; margin-right: auto;margin-top: 200px;margin-bottom: 200px;" id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">   


     
<!-- Section -->
<div>
    <!-- Tag Filter Buttons -->
    <div class="tag-buttons mb-3">
        <button  class="tag-button" onclick="filterItems('all')">All</button>
        <?php
        // PHP code to fetch distinct tags from the database
        $tagResult = $conn->query("SELECT DISTINCT tag FROM products");
        if ($tagResult->num_rows > 0) {
            while ($tagRow = $tagResult->fetch_assoc()) {
                echo "<button class='tag-button' onclick='filterItems(\"" . $tagRow['tag'] . "\")'>" . $tagRow['tag'] . "</button>";
            }
        }
        ?>
    </div>
  <!-- Search Bar and Sorting Buttons -->
  <div class="search-and-sort-section">
    <!-- Search Bar -->
    <input type="text" id="itemNameSearchInput" placeholder="Search item names..." style="font-size: 16px; padding: 8px; margin: 5px; flex: 1;">

    <!-- Sorting Buttons -->
    <div class="sorting-buttons">
        <button id="sortByName">
            Sort by Name <i class="fas fa-sort-alpha-down"></i>
        </button>
        <button id="sortByPrice">
            Sort by Price <i class="fas fa-sort-amount-down"></i>
        </button>
    </div>
</div>

<div id="product-list">
<?php if ($result->num_rows > 0) : ?>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <div class="item" data-tag="<?php echo $row['tag']; ?>">
            <div class="content">
                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                <div class="details">
                <p class="product-name" ><?php echo $row['name']; ?></p>
                    <p class="product-price">Price: <?php echo $row['price']; ?> THB</p>
                    <p>Tag: <?php echo $row['tag']; ?></p>
                    <p>Available Stock: <?php echo $row['quantity']; ?></p>
                    <!-- Only display the "Add to Cart" button if quantity is greater than 0 -->
                    <?php if ($row['quantity'] > 0) : ?>
                        <button class="button add-to-cart" data-id="<?php echo $row['id']; ?>">+</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else : ?>
    <p>No products found.</p>
<?php endif; ?>

</div>

    
</div>
<!-- End of Section -->


</div>       
</div>




    </div>

    

</div>
   

<!-- Cart view container (initially hidden) -->
<div id="cart-view" class="cart-popup">
    <button id="close-cart">x</button>
    <div class="cart-content">
    <img style="width: 40px;display : inline-block;margin-top: -10px;margin-right: 10px;" src="./pics1/cart-item.png">
        <h3 style="display : inline-block;">Your Cart</h3>
        <ul id="cart-items" style="margin-top: 30px;">
            <!-- Cart items will be dynamically loaded here -->
        </ul>
        <div class="cart-actions">
            <button id="confirm">Confirm</button>
        </div>
    </div>
    <!-- Overlay for cart view -->
    <div class="overlay" id="overlay"></div>
</div>






<!-- Cart view container (initially hidden) -->
<div id="confirmation-box" class="cart-popup">
       <button id="close-confirm">x</button>
    <div class="cart-content">
    <img style="width: 40px;display : inline-block;margin-top: -10px;margin-right: 10px;" src="./pics1/cart-item.png">
        <h3 style="display : inline-block;">Your Cart</h3>
        <ul id="cart-items2" style="margin-top: 30px;">
            <!-- Cart items will be dynamically loaded here -->
        </ul>
        <div class="cart-actions">
           <button id="checkout">Checkout</button>
        </div>
    </div>
    <!-- Overlay for cart view -->
    <div class="overlay" id="overlay"></div>
</div>






<div id="checkout-section" style="width: 100%; text-align: center; margin: 150px auto;display: none">
    <div style="text-align: left; display: inline-block;">
        <div class="card rounded-3 shadow-sm border-primary">
            <div class="card-body" style="display: flex; align-items: center; padding: 20px;">
                <img src="./pics1/qrcode.jpg" alt="QR Code" style="width: 100%; max-width: 250px; margin-right: 20px;">
                <div style="flex: 1;">
                    <h4 class="font1" style="color: #333; margin-bottom: 10px;">Checkout</h4>
                    <ul style="list-style: none; margin: 0; padding: 0; font-size: 22px;">
                        <li><strong>Account Name:</strong> พันธ์นิพัทธ์ เทียนแก้ว</li>
                        <li><strong>Account Number:</strong> 088-0-900527</li>
                        <li><strong>Total Price:</strong> <span id="checkout-total-price">0</span> THB</li>
                        <li><strong>Order ID:</strong> <span id="order-id"></span></li>
                        <li style="color: rgb(204, 45, 5); font-size: 22px;">**Please save your Order ID**</li>
                    </ul>
                    <button style="margin-top: 20px;" class="but3" onclick="goBackToShopping()">Back to Shopping</button>
                </div>
            </div>
        </div>
    </div>
</div>







<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>


$(document).ready(function() {
    // Event delegation for dynamically added buttons
    $(document).on('click', '.add-to-cart', function() {
        var productId = $(this).data('id'); // Get the product ID from data-id attribute
        var button = $(this); // Reference to the button element clicked
        
        $.ajax({
            type: 'POST',
            url: 'cart.php',
            data: { addToCart: productId },
            success: function(response) {
                console.log('Product added to cart successfully.');
                updateCartCount();
                showItemAddedBox();
                
                // Check if the response indicates stock limit reached
                var stockLimitReached = response.trim() === 'stock_limit_reached';
                
                // Hide the button if stock limit is reached
                if (stockLimitReached) {
                    button.hide(); // Hide the button clicked
                }
            },
            error: function(xhr, status, error) {
                console.error('Error adding product to cart:', error);
            }
        });
    });
});



function showItemAddedBox() {
    // Create the box element
    var $itemAddedBox = $('<div class="item-added-box">Item added successfully!</div>');
    
    // Append the box to the body
    $('body').append($itemAddedBox);
    
    // Animate the box
    $itemAddedBox.slideDown('fast').delay(2000).slideUp('fast', function() {
        // Remove the box after animation completes
        $(this).remove();
    });
    
}
function showCheckoutFailed() {
    // Create the box element
    var $itemAddedBox = $('<div style=" background-color: #d41e11; " class="item-added-box">Please add the product!</div>');
    
    // Append the box to the body
    $('body').append($itemAddedBox);
    
    // Animate the box
    $itemAddedBox.slideDown('fast').delay(2000).slideUp('fast', function() {
        // Remove the box after animation completes
        $(this).remove();
    });
    
}
function showCheckoutOk() {
    // Create the box element
    var $itemAddedBox = $('<div class="item-added-box">Checkout Successfully!</div>');
    
    // Append the box to the body
    $('body').append($itemAddedBox);
    
    // Animate the box
    $itemAddedBox.slideDown('fast').delay(2000).slideUp('fast', function() {
        // Remove the box after animation completes
        $(this).remove();
    });
    
}
$('#toggleCart').click(function() {
    $('#cart-view').toggle();
    $('#overlay').toggle(); // Toggle overlay along with the cart view
    loadCart();
});

$('#confirm').click(function() {
    loadCart2();
});





$(document).ready(function() {
    $('#checkout').click(function() {
        $.post('checkout.php', { checkout: true }, function(response) {
            if (response !== 'error') {
                $('#cart-items').html('');
                var truncatedOrderID = response.substring(0, 4);
                $('#order-id').text(truncatedOrderID);
                updateCartCount();

            } else {
                showCheckoutFailed();
            }
        });
    });
});





function loadCart() {
    $.post('cart.php', { loadCart: true }, function(data) {
        $('#cart-items').html(data);
    });
}



function loadCart2() {
    $.post('cart.php', { loadCart2: true }, function(data) {
        $('#cart-items2').html(data);
    });
}









document.getElementById('checkout').addEventListener('click', function() {
    var cartItemCount = parseInt(document.getElementById('cartCount').innerText);
    if (cartItemCount === 0) {
        showCheckoutFailed();
        return;
    }
    

    document.getElementById('confirmation-box').style.display = 'none';

    document.getElementById('overlay').style.display = 'none';
  
    document.getElementById('checkout-section').style.display = 'block';

    document.getElementById('product-view').style.display = 'none';


});

function getTotalPrice() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Update the total price in the checkout section
            document.getElementById('checkout-total-price').textContent = xhr.responseText;
        } else {
            console.error(xhr.responseText);
        }
    };
    xhr.send('getTotalPrice=true');
}



function goBackToShopping() {
    // Hide the checkout section
    document.getElementById('checkout-section').style.display = 'none';
    // Show the product view 
    document.getElementById('product-view').style.display = 'block';
    location.reload();
}









function filterItems(tag) {
    var items = document.getElementsByClassName('item');
    for (var i = 0; i < items.length; i++) {
        var item = items[i];
        if (tag === 'all' || item.getAttribute('data-tag') === tag) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    }

    const buttons = document.querySelectorAll('.tag-button');
    buttons.forEach(button => {
        if (button.innerText.toLowerCase() === tag.toLowerCase()) {
            button.classList.add('active'); // Activate the button
        } else {
            button.classList.remove('active'); // Deactivate other buttons
        }
    });
}

    





// jQuery for simplicity
$(document).ready(function() {
    // Open cart popup
    $('#openCart').click(function() {
        $('#overlay').fadeIn();
        $('#cartPopup').addClass('show');
        $('body').addClass('cart-open'); // Disable scroll on body
    });

    // Close cart popup
    $('#close-cart').click(function() {
    $('#cart-view').hide();
    $('#overlay').hide(); // Hide overlay when closing the cart
});

   
});




$(document).ready(function() {
    // Check if guestID is not set in session, then show the popup
    <?php if (!isset($_SESSION['guestID'])) : ?>
        $('#overlay').show();
        $('#popup').show();
    <?php endif; ?>

    // Hide the pop-up and overlay when the login button is clicked
    $('#loginButton').click(function(event) {
        event.preventDefault(); // Prevent form submission
        $('#overlay').hide();
        $('#popup').hide();
        $('#loginForm').submit(); // Submit the form after hiding the popup
    });
});






$(document).ready(function() {
    // Initialize cart from session or storage
    let cart = JSON.parse(localStorage.getItem('cart')) || {};

    // Add to Cart functionality
    $('.add-to-cart').on('click', function() {
        let productId = $(this).data('id');
        let button = $(this);
        let stockElement = button.closest('.details').find('.available-stock');
        let stock = parseInt(stockElement.text());

        if (stock > 0) {
            // Decrease the available stock
            stock--;
            stockElement.text(stock);

            // Add to cart logic
            if (cart[productId]) {
                cart[productId]++;
            } else {
                cart[productId] = 1;
            }
            localStorage.setItem('cart', JSON.stringify(cart));

            // Hide the button if stock is zero
            if (stock === 0) {
                button.hide();
            }
        }
    });
});





$(document).ready(function() {
    // Event delegation for dynamically added remove buttons
    $(document).on('click', '.remove-from-cart', function() {
        var productId = $(this).closest('.remove-form').find('input[name="removeFromCart"]').val(); // Get product ID from the form

        $.ajax({
            type: 'POST',
            url: 'cart.php',
            data: { removeFromCart: productId },
            success: function(response) {
                if (response.trim() === 'success') {
                    loadCart(); // Reload cart items after successful removal
                    updateCartCount();
                    showItemRemovedBox(); 
              
                } else {
                    console.error('Failed to remove item from cart.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error removing product from cart:', error);
            }
        });
    });
});
$(document).ready(function() {
    // Event delegation for dynamically added remove buttons
    $(document).on('click', '.remove-from-cart2', function() {
        var productId = $(this).closest('.remove-form2').find('input[name="removeFromCart2"]').val(); // Get product ID from the form

        $.ajax({
            type: 'POST',
            url: 'cart.php',
            data: { removeFromCart: productId },
            success: function(response) {
                if (response.trim() === 'success') {
                    loadCart2(); // Reload cart items after successful removal
                    updateCartCount();
                    showItemRemovedBox(); 
              
                } else {
                    console.error('Failed to remove item from cart.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error removing product from cart:', error);
            }
        });
    });
});




function showItemRemovedBox() {
    var $itemRemovedBox = $('<div class="item-added-box">Item removed successfully!</div>');

    // Append and animate the removal box
    $('body').append($itemRemovedBox);
    $itemRemovedBox.slideDown('fast').delay(2000).slideUp('fast', function() {
        $(this).remove();
    });
}


// Function to filter items based on item name search input
$('#itemNameSearchInput').on('input', function() {
    var searchQuery = $(this).val().trim().toLowerCase();
    $('.item').each(function() {
        var itemName = $(this).find('.product-name').text().trim().toLowerCase();
        if (itemName.includes(searchQuery) || searchQuery === '') {
            $(this).show(); // Show matching items
        } else {
            $(this).hide(); // Hide non-matching items
        }
    });
});

// Function to clear search input and reset item visibility
function clearItemNameSearch() {
    $('#itemNameSearchInput').val('');
    $('.item').show();
}


var nameSortOrder = 'asc'; // Default sort order for name
var priceSortOrder = 'asc'; // Default sort order for price

// Sort by Name button click handler
$('#sortByName').click(function() {
    toggleNameSortOrder();
    sortItemsByName();
});

// Sort by Price button click handler
$('#sortByPrice').click(function() {
    togglePriceSortOrder();
    sortItemsByPrice();
});

function toggleNameSortOrder() {
    if (nameSortOrder === 'asc') {
        nameSortOrder = 'desc';
        $('#sortByName').html('Sort by Name <i class="fas fa-sort-alpha-up"></i>');
    } else {
        nameSortOrder = 'asc';
        $('#sortByName').html('Sort by Name <i class="fas fa-sort-alpha-down"></i>');
    }
}

function togglePriceSortOrder() {
    if (priceSortOrder === 'asc') {
        priceSortOrder = 'desc';
        $('#sortByPrice').html('Sort by Price <i class="fas fa-sort-amount-up"></i>');
    } else {
        priceSortOrder = 'asc';
        $('#sortByPrice').html('Sort by Price <i class="fas fa-sort-amount-down"></i>');
    }
}

function sortItemsByName() {
    var items = $('.item');
    items.sort(function(a, b) {
        var nameA = $(a).find('.product-name').text().trim().toLowerCase();
        var nameB = $(b).find('.product-name').text().trim().toLowerCase();
        if (nameSortOrder === 'desc') {
            return nameB.localeCompare(nameA);
        } else {
            return nameA.localeCompare(nameB);
        }
    });
    
    $('#product-list').empty().append(items);
}

// Function to sort items by price
function sortItemsByPrice() {
    var items = $('.item');
    items.sort(function(a, b) {
        var priceA = parseFloat($(a).find('.product-price').text().replace('Price: ', '').trim()); // Adjusted to match the actual text format
        var priceB = parseFloat($(b).find('.product-price').text().replace('Price: ', '').trim()); // Adjusted to match the actual text format

        if (priceSortOrder === 'desc') {
            return priceB - priceA; // Sort descending
        } else {
            return priceA - priceB; // Sort ascending
        }
    });

    $('#product-list').empty().append(items);
}



function updateCartCount() {
    $.post('cart.php', { getCartCount: true }, function(count) {
        $('#cartCount').text(count); // Update cart count in navbar button
    });
}

// Call updateCartCount on page load to initialize cart count
$(document).ready(function() {
    updateCartCount();
});






// Get the Confirm button
const confirmButton = document.getElementById('confirm');
// Get the cart view container
const cartView = document.getElementById('cart-view');
// Get the confirmation box
const confirmationBox = document.getElementById('confirmation-box');

// Add click event listener to Confirm button
confirmButton.addEventListener('click', function() {
    // Hide the cart view
    cartView.style.display = 'none';
    // Show the confirmation box
    confirmationBox.style.display = 'block';
});






// JavaScript code to handle close functionality
document.addEventListener('DOMContentLoaded', function() {
    const confirmationBox = document.getElementById('confirmation-box');
    const closeConfirmButton = document.getElementById('close-confirm');

    // Function to toggle confirmation box visibility
    function toggleConfirmationBox() {
        confirmationBox.style.display = confirmationBox.style.display === 'none' ? 'block' : 'none';
        
    document.getElementById('overlay').style.display = 'none';
    }

    // Event listener for close button
    closeConfirmButton.addEventListener('click', function() {
        toggleConfirmationBox();
    });

    // Event listener for checkout button (for example)
    const checkoutButton = document.getElementById('checkout');
    checkoutButton.addEventListener('click', function() {
        // Handle checkout logic here
        console.log('Checkout button clicked');
        // You can add more functionality here for the checkout process
    });

    // You might have other event listeners or functions related to your app
});







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







 
</script>
</body>
</html>
