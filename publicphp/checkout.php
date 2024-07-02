<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    // Generate a random ID for the order
    $orderID = generateOrderID();

    // Check if a guest ID is set in the session
    if (isset($_SESSION['guestID'])) {
        $guestID = $_SESSION['guestID'];
    } else {
        // Generate a guest ID if not already set
        $guestID = generateGuestID();
        $_SESSION['guestID'] = $guestID; // Store guest ID in session
    }

    // Retrieve cart items from the database
    $result = $conn->query("SELECT products.id, products.name, products.price, products.tag, cart.quantity FROM cart JOIN products ON cart.product_id = products.id");
    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    // Insert cart items into orders table with the generated order ID
    foreach ($cartItems as $item) {
        $productID = $conn->real_escape_string($item['id']);
        $productName = $conn->real_escape_string($item['name']);
        $productPrice = $conn->real_escape_string($item['price']);
        $tag = $conn->real_escape_string($item['tag']);
        $quantity = $conn->real_escape_string($item['quantity']);
        
        // Deduct the quantity from the product inventory
        $conn->query("UPDATE products SET quantity = quantity - $quantity WHERE id = '$productID'");
        
        // Insert into orders table with the same order ID for all items
        $conn->query("INSERT INTO orders (order_id, product_name, product_price, quantity, tag, guest_id) VALUES ('$orderID', '$productName', '$productPrice', $quantity, '$tag', '$guestID')");
    }

    // Clear cart after successful checkout
    $conn->query("DELETE FROM cart");

    // Send the success response with the order ID
    echo $orderID;
} else {
    // Send error response
    echo 'error';
}

// Function to generate a random order ID
function generateOrderID() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = 8;
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function generateGuestID() {
    return uniqid(); // Generate a unique guest ID without prefix
}
?>
