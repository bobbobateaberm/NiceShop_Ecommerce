<?php
$servername = "sql210.infinityfree.com";
$username = "if0_36658368";
$password = "Nice25493465";
$dbname = "if0_36658368_items";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
