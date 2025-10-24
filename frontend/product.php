<?php
require '../backend/DatabaseHelper.php';

$dbHelp = new DatabaseHelper();
if (isset($_COOKIE['login'])){
    $usernameAndPassword = explode(" ", $_COOKIE['login']);
    $isLogged = $dbHelp->checkLogin($usernameAndPassword[0], $usernameAndPassword[1]);
    // logout if not logged in
    if (!$isLogged){
        echo $usernameAndPassword[0] + " " + $usernameAndPassword[1];
        header('Location: /frontend/unauthorized.php');
    }
} else {
    header('Location: /frontend/unauthorized.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $products = $dbHelp->searchProducts($_GET['query']);
} else {
    $products = $dbHelp->searchProducts('');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../Styles/header.css">
    <link rel="stylesheet" href="../Styles/table.css">
    <link rel="stylesheet" href="../Styles/base.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Dashboard</title>
</head>
<body>
<?php include './header.php';?>
<main class='table'>
    <div>
        <h1>Product Dashboard</h1>
    </div>

    <!-- Search Form -->
    <form method="GET" class="search-form">
        <input type="text" name="query" class="search-input" placeholder="Search products..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
        <button type="submit" class="search-button">
            Search
        </button>
    </form>

    <?php
        if (isset($_GET['deleteSuccess'])){
            echo "<div class='success'>Successfully Deleted Item</div>";
        } else if (isset($_GET['deleteError'])){
            echo "<div class='error'>An error occurred when Deleting the item</div>";
        } else if (isset($_GET['updateSuccess'])){
            echo "<div class='success'>Successfully Updated Item</div>";
        } else if (isset($_GET['updateError'])){
            echo "<div class='error'>An error occurred when updating the item</div>";
        }
    ?>

    <!-- Products Table -->
    <table>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Supplier ID</th>
            <th>Actions </th>
        </tr>
        <?php if ($products->num_rows > 0): ?>
            <?php while ($row = $products->fetch_assoc()): ?>
            <tr>
                <td><?= $row['product_id'] ?></td>
                <td><?= $row['product_name'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= $row['status'] ?></td>
                <td><?= $row['supplier_id'] ?></td>
                <td class="actions">
                <form method="POST" action="editProduct.php">
                        <input type="hidden" name="id" value="<?= $row['product_id'] ?>"/>
                        <button type="submit">Edit</button>
                </form>
                <form method="POST" action="deleteProduct.php">
                    <input type="hidden" name="id" value="<?= $row['product_id'] ?>"/>
                    <button type="submit">Delete</button>
                </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align: center;">No products found</td>
            </tr>
        <?php endif; ?>
    </table>
</main>
</body>
</html>