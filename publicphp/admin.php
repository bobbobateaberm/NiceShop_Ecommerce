<?php
include 'db.php';

function uploadImage($file) {
    if (isset($file) && $file['error'] === 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if ($file["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            return false;
        }

        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            return false;
        }

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return false;
        }
    } else {
        echo "Error uploading file.";
        return false;
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add new item
    if (isset($_POST['itemName'], $_POST['itemPrice'], $_POST['itemQuantity'], $_POST['itemTag'], $_POST['itemDescription'], $_FILES['itemImage'])) {
        $name = $conn->real_escape_string($_POST['itemName']);
        $price = $conn->real_escape_string($_POST['itemPrice']);
        $quantity = $conn->real_escape_string($_POST['itemQuantity']);
        $tag = $conn->real_escape_string($_POST['itemTag']);
        $description = $conn->real_escape_string($_POST['itemDescription']);
        $image_url = uploadImage($_FILES['itemImage']);

        if ($image_url) {
            $sql = $conn->prepare("INSERT INTO products (name, price, description, image_url, quantity, tag) VALUES (?, ?, ?, ?, ?, ?)");
            $sql->bind_param("ssssis", $name, $price, $description, $image_url, $quantity, $tag);
            if ($sql->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql->error;
            }
        } else {
            echo "Error uploading image.";
        }
    }

    // Remove item
    elseif (isset($_POST['removeId'])) {
        $id = $conn->real_escape_string($_POST['removeId']);
        if ($conn->query("DELETE FROM products WHERE id = $id")) {
            echo "Item removed successfully";
        } else {
            echo "Error removing item";
        }
    }

    // Remove order
    elseif (isset($_POST['orderId'])) {
        $orderId = $conn->real_escape_string($_POST['orderId']);
        if ($conn->query("DELETE FROM orders WHERE id = $orderId")) {
            echo "Order cleared successfully";
        } else {
            echo "Error clearing order";
        }
    }

    // Edit item
    elseif (isset($_POST['editId'], $_POST['editName'], $_POST['editPrice'], $_POST['editQuantity'], $_POST['editTag'])) {
        $id = $conn->real_escape_string($_POST['editId']);
        $name = $conn->real_escape_string($_POST['editName']);
        $price = $conn->real_escape_string($_POST['editPrice']);
        $quantity = $conn->real_escape_string($_POST['editQuantity']);
        $tag = $conn->real_escape_string($_POST['editTag']);

        if ($conn->query("UPDATE products SET name = '$name', price = $price, quantity = $quantity, tag = '$tag' WHERE id = $id")) {
            echo "Item updated successfully";
        } else {
            echo "Error updating item";
        }
    }
}
    $result = $conn->query("SELECT * FROM products");
    $tags = [];
    if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!in_array($row['tag'], $tags)) {
            $tags[] = $row['tag'];
        }
    }
}
    



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nice Shop | Admin Panel</title>
    <link href="./style.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="./pics1/Shop.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        .item-container {
            display: inline-block;
            width: 200px;
            margin: 50px;
            padding: 20px;
            box-shadow: 2px 2px 12px 4px rgba(0, 0, 0, 0.12);
            position: relative;
            text-align: center;
        }

        .item-name, .item-price {
            display: block;
            margin-bottom: 10px;
        }

        .item-image {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .item-remove-button {
            bottom: 10px;
        }
            /* Center the table */
    .order-table-container {
        text-align: center;
        margin: 0 auto;
        display: flex;
  justify-content: center;
  width : 100%;
  margin-bottom: 300px;
    }

    /* Style the table */
    .order-table {
        border-collapse: collapse;
        width: 100%; /* Adjust width as needed */
        margin-top: 20px;
    }

    .order-table th,
    .order-table td {
        border: 1px solid #dddddd;
        padding: 8px;
    }

    .order-table th {
        background-color: #f2f2f2;
    }

    .order-remove-button {
        padding: 5px 10px;
    }
/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 400px;
    text-align: center;
}



.admin-items-table {
    border-collapse: collapse;
    width: 100%; /* Adjust width as needed */
    margin: 20px auto;
    text-align: center;
}

.admin-items-table th,
.admin-items-table td {
    border: 1px solid #dddddd;
    padding: 8px;
}

.admin-items-table th {
    background-color: #f2f2f2;
}

.admin-item-row:hover {
    background-color: #f9f9f9;
}

.admin-item-row:nth-child(even) {
    background-color: #f2f2f2;
}

.admin-item-row .item-image {
    max-width: 100px; /* Adjust image size as needed */
    max-height: 100px; /* Adjust image size as needed */
    margin-bottom: 10px;
}

.admin-item-row .item-remove-button {
    padding: 5px 10px;
}


.tag-buttons {
            margin-bottom: 20px;
            text-align: center;
        }
        .tag-button {
            margin: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .tag-button.active {
            background-color: #007bff;
            color: white;
        }
        .navbar1 {
  display: flex;
  justify-content: space-between; /* Align items to the left and right */
  align-items: center; /* Vertically center items */
}

.nav-links {
  display: inline-block; /* Display links inline */
}

.nav-links a {
  margin-left: 20px; /* Add some space between links */
}

.sortable {
            cursor: pointer;
        }
        .sort-icon {
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="navbar1">
        <img src="./pics1/adminlogo.png" style="width: 15%;">
    </div>




  

    
    <div class="container">

        <h1 class="mt-5">Admin Page</h1>
        <form id="item-form" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="itemName" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="itemName" required>
            </div>
            <div class="mb-3">
                <label for="itemPrice" class="form-label">Item Price</label>
                <input type="number" class="form-control" id="itemPrice" required>
            </div>
            <div class="mb-3">
                        <label for="itemTag" class="form-label">Tag</label>
                        <input type="text" class="form-control" id="itemTag" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="itemDescription" required></textarea>
                    </div>       
            <div class="mb-3">
            <div class="mb-3">
                        <label for="itemQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="itemQuantity" required>
                    </div>
                <label for="itemImage" class="form-label">Item Image <span style="color:#6e6e6e;">( 200 x 300 )</span></label>
                <input type="file" class="form-control" id="itemImage" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Item</button>
        </form>
    



        <div id="adminItems" class="mt-4">


          
<div id="adminItems" class="mt-4">
      <!-- Tag Filter Buttons -->
<div class="tag-buttons mb-3">
    <button class="btn btn-outline-secondary tag-button active" onclick="filterItems('all')">All</button>
    <?php
    // Fetch distinct tags from the database
    $tagResult = $conn->query("SELECT DISTINCT tag FROM products");
    if ($tagResult->num_rows > 0) {
        while ($tagRow = $tagResult->fetch_assoc()) {
            echo "<button class='btn btn-outline-secondary tag-button' onclick=\"filterItems('" . htmlspecialchars($tagRow['tag']) . "')\">" . htmlspecialchars($tagRow['tag']) . "</button>";
        }
    }
    ?>
</div>

        <table class="admin-items-table table table-striped">
        <thead>
    <tr>
        <th class="sortable" data-sort-direction="asc">Item Name</th>
        <th class="sortable" data-sort-direction="asc">Price</th>
        <th class="sortable" data-sort-direction="asc">Tag</th>
        <th class="sortable" data-sort-direction="asc">Quantity</th>
        <th>Image</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <!-- PHP-generated items will be dynamically added here -->
    <?php
    // Fetch products from the database
    $result = $conn->query("SELECT * FROM products");

    // Check if there are any products
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr class='admin-item-row' data-id='" . $row['id'] . "' data-original-name='" . htmlspecialchars($row['name']) . "' data-original-price='" . $row['price'] . "' data-original-tag='" . htmlspecialchars($row['tag']) . "' data-original-quantity='" . $row['quantity'] . "'>";
            echo "<td contenteditable='true'>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td contenteditable='true'>" . $row['price'] . "</td>";
            echo "<td contenteditable='true'>" . htmlspecialchars($row['tag']) . "</td>";
            echo "<td contenteditable='true'>" . $row['quantity'] . "</td>";
            echo "<td><img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['name']) . "' class='item-image'></td>";
            echo "<td><button class='btn btn-danger item-remove-button' onclick='removeItem(" . $row['id'] . ")'><i class='fas fa-trash'></i></button></td>";
            echo "</tr>";
        }
    } else {
        // Display a message when there are no products
        echo "<tr>";
        echo "<td colspan='6' class='empty-message'>No items yet.</td>"; // colspan='6' to span across all columns
        echo "</tr>";
    }
    ?>
</tbody>

        </table>

        <!-- Save All Changes Button -->
        <button class="btn btn-success btn-save-all" onclick="saveAllChanges()">Save All Changes</button>
    </div>



    <!-- Display Orders -->
    <div class="order-table-container">
    <table class="order-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Tag</th>
                <th>Quantity</th>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch orders from the database
            include 'db.php';

            // Modify the SQL query to order the results by order date in ascending order
            $result = $conn->query("SELECT * FROM orders ORDER BY order_date ASC");

            // Check if there are any orders
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr id='order-" . $row['id'] . "'>";
                    echo "<td>" . $row['product_name'] . "</td>";
                    echo "<td>" . $row['product_price'] . "</td>";
                    echo "<td>" . $row['tag'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>" . $row['order_id'] . "</td>";
                    echo "<td>" . $row['guest_id'] . "</td>";

                    // Retrieve the order date from the database
                    $orderDate = $row['order_date'];

                    // Convert the order date to a UNIX timestamp
                    $orderTimestamp = strtotime($orderDate);

                    // Adjust the timestamp if necessary (if you don't need to adjust it, you can remove this part)
                    $orderTimestampAdjusted = $orderTimestamp + 39600; // Adjusted by adding 11 hours and 30 minutes (39600 seconds)

                    // Format the adjusted timestamp as a readable date and time
                    $orderDateAdjusted = date('Y-m-d H:i:s', $orderTimestampAdjusted);

                    echo "<td>" . $orderDateAdjusted . "</td>";
                    echo "<td><button class='btn btn-success order-remove-button' onclick='removeOrder(" . $row['id'] . ")'>Order Cleared</button></td>";
                    echo "</tr>";
                }
            } else {
                // Display a message when there are no orders
                echo "<tr>";
                echo "<td colspan='8' class='empty-message'>No orders yet.</td>"; // colspan='6' to span across all columns
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    </div>

 </div>
</div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
  <script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('item-form').addEventListener('submit', function (event) {
        event.preventDefault();

        const itemName = document.getElementById('itemName').value;
        const itemTag = document.getElementById('itemTag').value; // Retrieve item tag
        const itemPrice = document.getElementById('itemPrice').value;
        const itemQuantity = document.getElementById('itemQuantity').value; // Get quantity value
        const itemImage = document.getElementById('itemImage').files[0];

        const formData = new FormData();
        formData.append('itemName', itemName);
        formData.append('itemTag', itemTag); // Append item tag
        formData.append('itemPrice', itemPrice);
        formData.append('itemQuantity', itemQuantity); // Append quantity to form data
        formData.append('itemImage', itemImage);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                location.reload();
            } else {
                console.error(xhr.responseText);
            }
        };
        xhr.send(formData);
    });
});

function removeItem(itemId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.querySelector('tr[data-id="' + itemId + '"]').remove();
            } else {
                console.error(xhr.responseText);
            }
        };
        xhr.send('removeId=' + itemId);
    }

    function removeOrder(orderId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('order-' + orderId).remove();
        } else {
            console.error(xhr.responseText);
        }
    };
    xhr.send('orderId=' + orderId);
}

function editItem(itemId) {
        const row = document.querySelector('tr[data-id="' + itemId + '"]');
        const name = row.cells[0].innerText.trim();
        const price = parseFloat(row.cells[1].innerText.trim());
        const tag = row.cells[2].innerText.trim();
        const quantity = parseInt(row.cells[3].innerText.trim());

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
            } else {
                console.error(xhr.responseText);
            }
        };
        xhr.send('editId=' + itemId + '&editName=' + encodeURIComponent(name) + '&editPrice=' + price + '&editQuantity=' + quantity + '&editTag=' + encodeURIComponent(tag));
    }




    function filterItems(tag) {
    const rows = document.querySelectorAll('.admin-item-row');
    rows.forEach(row => {
        const rowTag = row.getAttribute('data-original-tag'); // Get the data-original-tag attribute
        if (tag === 'all' || rowTag === tag) {
            row.style.display = ''; // Show the row
        } else {
            row.style.display = 'none'; // Hide the row
        }
    });

    const buttons = document.querySelectorAll('.tag-button');
    buttons.forEach(button => {
        if (button.innerText.toLowerCase() === tag.toLowerCase()) {
            button.classList.add('active'); // Activate the button
        } else {
            button.classList.remove('active'); // Deactivate other buttons
        }
    });
}



function saveAllChanges() {
        const rows = document.querySelectorAll('.admin-item-row');
        rows.forEach(row => {
            const itemId = row.getAttribute('data-id');
            const originalName = row.getAttribute('data-original-name');
            const originalPrice = row.getAttribute('data-original-price');
            const originalTag = row.getAttribute('data-original-tag');
            const originalQuantity = row.getAttribute('data-original-quantity');

            const newName = row.cells[0].innerText.trim();
            const newPrice = parseFloat(row.cells[1].innerText.trim());
            const newTag = row.cells[2].innerText.trim();
            const newQuantity = parseInt(row.cells[3].innerText.trim());

            if (newName !== originalName || newPrice !== originalPrice || newTag !== originalTag || newQuantity !== originalQuantity) {
                editItem(itemId);
                
            }
           
        });
    }






    document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('.admin-items-table');
    const headers = table.querySelectorAll('th.sortable');
    let rows = Array.from(table.querySelectorAll('tbody tr'));

    headers.forEach(header => {
        // Initialize sort icons
        const sortIcon = document.createElement('i');
        sortIcon.classList.add('sort-icon', 'fas', 'fa-sort');
        header.appendChild(sortIcon);

        header.addEventListener('click', function () {
            const columnIndex = Array.from(headers).indexOf(header);
            const sortDirection = header.dataset.sortDirection === 'asc' ? 'desc' : 'asc';
            header.dataset.sortDirection = sortDirection;

            sortTable(rows, columnIndex, sortDirection);
            updateSortIcons(headers, header, sortDirection);
        });
    });

    function sortTable(rows, columnIndex, sortDirection) {
        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[columnIndex].innerText.trim();
            const cellB = rowB.cells[columnIndex].innerText.trim();

            if (columnIndex === 1 || columnIndex === 3) {
                // Column index 1 (Price) and 3 (Quantity) - numeric sorting
                return sortDirection === 'asc' ? parseFloat(cellA) - parseFloat(cellB) : parseFloat(cellB) - parseFloat(cellA);
            } else {
                // Column index 0 (Item Name) and 2 (Tag) - alphabetical sorting
                return sortDirection === 'asc' ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
            }
        });

        const tbody = table.querySelector('tbody');
        tbody.innerHTML = ''; // Clear existing rows
        rows.forEach(row => tbody.appendChild(row)); // Append sorted rows
    }

    function updateSortIcons(headers, activeHeader, sortDirection) {
        headers.forEach(header => {
            const sortIcon = header.querySelector('.sort-icon');
            sortIcon.classList.remove('fa-sort-up', 'fa-sort-down');
            sortIcon.classList.add('fa-sort');

            if (header === activeHeader) {
                sortIcon.classList.add(sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
            }
        });
    }
});







</script>
</body>
</html>


