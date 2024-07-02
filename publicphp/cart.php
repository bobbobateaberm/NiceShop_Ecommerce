<?php
session_start();
include 'db.php';

// Function to generate a unique guest ID
function generateGuestID() {
    return uniqid('guest_', true); // Generate a unique guest ID
}


// Check if addToCart action is triggered
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addToCart'])) {
    $product_id = $_POST['addToCart'];

    // Check if a guest ID is set in the session
    if (isset($_SESSION['guestID'])) {
        $guestID = $_SESSION['guestID'];
    } else {
        // Generate a guest ID if not already set
        $guestID = generateGuestID();
        $_SESSION['guestID'] = $guestID; // Store guest ID in session
    }

    // Check the current quantity in the cart for this product
    $result = $conn->query("SELECT quantity FROM cart WHERE product_id = '$product_id' AND guest_id = '$guestID'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentQuantityInCart = $row['quantity'];
    } else {
        $currentQuantityInCart = 0;
    }

    // Get the available stock quantity from the products table
    $result = $conn->query("SELECT quantity FROM products WHERE id = '$product_id'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $availableStock = $row['quantity'];
    } else {
        $availableStock = 0;
    }

    // Check if adding another unit exceeds the available stock
    if ($currentQuantityInCart < $availableStock) {
        // If not exceeded, proceed to add to cart
        if ($currentQuantityInCart > 0) {
            // Update quantity in the cart
            $newQuantity = $currentQuantityInCart + 1;
            $conn->query("UPDATE cart SET quantity = '$newQuantity' WHERE product_id = '$product_id' AND guest_id = '$guestID'");
        } else {
            // Insert new entry in the cart
            $conn->query("INSERT INTO cart (product_id, quantity, guest_id) VALUES ('$product_id', 1, '$guestID')");
        }

        // After adding/updating, check if the product has reached its available stock quantity
        if ($currentQuantityInCart + 1 >= $availableStock) {
            // If reached, echo 'stock_limit_reached'
            echo 'stock_limit_reached';
        } else {
            // Otherwise, echo 'success'
            echo 'success';
        }
    }
}







if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loadCart'])) {
    // Check if a guest ID is set in the session
    if (isset($_SESSION['guestID'])) {
        $guestID = $_SESSION['guestID'];
    } else {
        // Generate a guest ID if not already set
        $guestID = generateGuestID();
        $_SESSION['guestID'] = $guestID; // Store guest ID in session
    }

    $result = $conn->query("SELECT products.id, products.name, products.price, products.image_url, cart.quantity FROM cart JOIN products ON cart.product_id = products.id WHERE cart.guest_id = '$guestID'");
    $totalPrice = 0;
    while ($row = $result->fetch_assoc()) {
        echo "<div class='cart-item'>";
        echo "<img src='" . $row['image_url'] . "' alt='" . $row['name'] . "' class='cart-item-img' style='width: 80px; height: auto; margin-right: 20px; border-radius: 5px;'>";
        echo "<div >";
        echo "<h4 class='cart-item-name' style='margin: 0; font-size: 16px; font-weight: bold; display: inline-block;'>" . $row['name'] . " </h4>";

        echo "<form method='post' class='remove-form' style='display: inline-block; margin-left: auto;'>";
        echo "<input type='hidden' name='removeFromCart' value='" . $row['id'] . "'>";
        echo "<button type='button' class='remove-from-cart' style='background: none; border: none; color: red; cursor: pointer; font-size: 18px;'><i class='fas fa-trash-alt'></i></button>";
        echo "</form>";

        echo "<p class='cart-item-price' style='margin: 5px 0;'>Price: " . $row['price'] . " THB</p>";
        echo "<p class='cart-item-quantity' style='margin: 5px 0;'>Quantity: " . $row['quantity'] . "</p>";
        echo "</div>";
        echo "</div>";
        $totalPrice += $row['price'] * $row['quantity'];
    }
    echo "<div class='total-price' style='text-align: right; font-size: 18px; font-weight: bold; margin-top: 20px;'>";
    echo "<h4>Total Price: " . $totalPrice . " THB</h4>";
    echo "</div>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loadCart2'])) {
    // Check if a guest ID is set in the session
    if (isset($_SESSION['guestID'])) {
        $guestID = $_SESSION['guestID'];
    } else {
        // Generate a guest ID if not already set
        $guestID = generateGuestID();
        $_SESSION['guestID'] = $guestID; // Store guest ID in session
    }

    $result = $conn->query("SELECT products.id, products.name, products.price, products.image_url, cart.quantity FROM cart JOIN products ON cart.product_id = products.id WHERE cart.guest_id = '$guestID'");
    $totalPrice = 0;
    while ($row = $result->fetch_assoc()) {
        echo "<div class='cart-item'>";
        echo "<img src='" . $row['image_url'] . "' alt='" . $row['name'] . "' class='cart-item-img' style='width: 80px; height: auto; margin-right: 20px; border-radius: 5px;'>";
        echo "<div >";
        echo "<h4 class='cart-item-name' style='margin: 0; font-size: 16px; font-weight: bold; display: inline-block;'>" . $row['name'] . " </h4>";

        echo "<form method='post' class='remove-form2' style='display: inline-block; margin-left: auto;'>";
        echo "<input type='hidden' name='removeFromCart2' value='" . $row['id'] . "'>";
        echo "<button type='button' class='remove-from-cart2' style='background: none; border: none; color: red; cursor: pointer; font-size: 18px;'><i class='fas fa-trash-alt'></i></button>";
        echo "</form>";

        echo "<p class='cart-item-price' style='margin: 5px 0;'>Price: " . $row['price'] . " THB</p>";
        echo "<p class='cart-item-quantity' style='margin: 5px 0;'>Quantity: " . $row['quantity'] . "</p>";
        echo "</div>";
        echo "</div>";
        $totalPrice += $row['price'] * $row['quantity'];
    }
    echo "<div class='total-price' style='text-align: right; font-size: 18px; font-weight: bold; margin-top: 20px;'>";
    echo "<h4>Total Price: " . $totalPrice . " THB</h4>";
    echo "</div>";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['getTotalPrice'])) {
    // Check if a guest ID is set in the session
    if (isset($_SESSION['guestID'])) {
        $guestID = $_SESSION['guestID'];
    } else {
        // Generate a guest ID if not already set
        $guestID = generateGuestID();
        $_SESSION['guestID'] = $guestID; // Store guest ID in session
    }

    // Calculate the total price from the cart items
    $result = $conn->query("SELECT SUM(products.price * cart.quantity) AS total FROM cart JOIN products ON cart.product_id = products.id WHERE cart.guest_id = '$guestID'");
    $row = $result->fetch_assoc();
    echo $row['total'];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    // Check if a guest ID is set in the session
    if (isset($_SESSION['guestID'])) {
        $guestID = $_SESSION['guestID'];
    } else {
        // Generate a guest ID if not already set
        $guestID = generateGuestID();
        $_SESSION['guestID'] = $guestID; // Store guest ID in session
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['removeFromCart'])) {
    // Check if a guest ID is set in the session
    if (isset($_SESSION['guestID'])) {
        $guestID = $_SESSION['guestID'];
        $product_id = $_POST['removeFromCart'];

        // Delete the specific item from the cart
        $conn->query("DELETE FROM cart WHERE guest_id = '$guestID' AND product_id = '$product_id'");
        echo 'success'; // Respond with success after deletion
    } else {
        echo 'error'; // Handle session error if guestID is not set
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['removeFromCart2'])) {
    // Check if a guest ID is set in the session
    if (isset($_SESSION['guestID'])) {
        $guestID = $_SESSION['guestID'];
        $product_id = $_POST['removeFromCart2'];

        // Delete the specific item from the cart
        $conn->query("DELETE FROM cart WHERE guest_id = '$guestID' AND product_id = '$product_id'");
        echo 'success'; // Respond with success after deletion
    } else {
        echo 'error'; // Handle session error if guestID is not set
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['getCartCount'])) {
    // Check if a guest ID is set in the session
    if (isset($_SESSION['guestID'])) {
        $guestID = $_SESSION['guestID'];
    } else {
        // Generate a guest ID if not already set
        $guestID = generateGuestID();
        $_SESSION['guestID'] = $guestID; // Store guest ID in session
    }

    $result = $conn->query("SELECT IFNULL(SUM(quantity), 0) AS count FROM cart WHERE guest_id = '$guestID'");
    $row = $result->fetch_assoc();
    echo $row['count'];
}

?>

