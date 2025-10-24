<?php
$host = "localhost";
$user = "root";
$pass = "StudentCard2018";
$db = "myDB";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS myDB";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
} 
    


?>

<h3>Delete Product</h3>
<form method="POST">
    Delete Product (Enter Inventory ID): <input type="number" name="delete_id" required>
    <input type="submit" name="delete_submit" value="Delete">
</form>


<h3>Update Quantity</h3>
<form method="POST">
    Update Quantity of a Product (Enter Inventory ID): <input type="number" name="update_id" required>
    New Quantity: <input type="number" name="new_quantity" required>
    <input type="submit" name="update_submit" value="Update">
</form>

<h3>Search Inventory</h3>
<form method="GET">
    Search: <input type="text" name="search_query" required>
    <input type="submit" value="Search">
</form>

<?php



// DELETE row
if (isset($_POST['delete_submit'])) {
    $delete_id = $_POST['delete_id'];

    // check if product id exists
    $check_stmt = $conn->prepare("SELECT 1 FROM InventoryTable WHERE inventory_id = ?");
    $check_stmt->bind_param("i", $delete_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $stmt = $conn->prepare("DELETE FROM InventoryTable WHERE inventory_id = ?");
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            echo "<p style='color:red;'>Product with inventory ID $delete_id deleted successfully.</p>";
        } else {
            echo "<p>Error deleting product: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:orange;'>No inventory item matching ID $delete_id found in the inventory.</p>";
    }
    $check_stmt->close();
}

// UPDATE table
if (isset($_POST['update_submit'])) {
    $update_id = $_POST['update_id'];
    $new_quantity = $_POST['new_quantity'];

    // check product id
    $check_stmt = $conn->prepare("SELECT 1 FROM InventoryTable WHERE inventory_id = ?");
    $check_stmt->bind_param("i", $update_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE InventoryTable SET quantity = ? WHERE inventory_id = ?");
        $stmt->bind_param("ii", $new_quantity, $update_id);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Inventory ID $update_id quantity updated to $new_quantity.</p>";
        } else {
            echo "<p>Error updating quantity: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:orange;'>No inventory item matching ID $update_id found in the inventory.</p>";
    }
    $check_stmt->close();
}

//search 
$search_condition = "";
$params = [];
$param_types = "";

if (isset($_GET['search_query'])) {
    $search_term = "%" . $_GET['search_query'] . "%";
    $search_condition = "WHERE product_name LIKE ? OR supplier_name LIKE ? OR product_id LIKE ?";
    $params = [$search_term, $search_term, $_GET['search_query']];
    $param_types = "sss";
}

$sql = "SELECT * FROM InventoryTable $search_condition ORDER BY product_id ASC";
$stmt = $conn->prepare($sql);

if (!empty($search_condition)) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='8'>
            <tr>
                <th>Inventory ID</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Status</th>
                <th>Supplier Name</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['inventory_id']}</td>
                <td>{$row['product_id']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['quantity']}</td>
                <td>\${$row['price']}</td>
                <td>{$row['status']}</td>
                <td>{$row['supplier_name']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No inventory items found.</p>";
}

$stmt->close();

$conn->close();
?>

