<?php
require '../backend/DatabaseHelper.php';
$db = new DatabaseHelper();

if (isset($_COOKIE['login'])){
    $usernameAndPassword = explode(" ", $_COOKIE['login']);
    $isLogged = $db->checkLogin($usernameAndPassword[0], $usernameAndPassword[1]);
    // logout if not logged in
    if (!$isLogged){
        echo $usernameAndPassword[0] + " " + $usernameAndPassword[1];
        header('Location: /frontend/unauthorized.php');
    }
} else {
    header('Location: /frontend/unauthorized.php');
}

$results = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $results = $db->searchInventory($_GET['query']);
} else {
    $results = $db->searchInventory('');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../Styles/header.css">
    <link rel="stylesheet" href="../Styles/table.css">
    <link rel="stylesheet" href="../Styles/base.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php include './header.php';?>
<main class='table'>
    <h1>Inventory Dashboard</h1>
    <form method="GET" class="search-form">
        <input type="text" name="query" placeholder="Search products..." 
               value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
        <button type="submit" class="btn">Search</button>
    </form>

    <?php if ($results && $results->num_rows > 0): ?>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Status</th>
                <th>Supplier Name</th>
                <!-- <th>Actions</th> -->
            </tr>
            <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?= $row['product_id'] ?></td>
                <td><?= $row['product_name'] ?></td>
                <td><?= $row['quantity'] ?></td>
                <td>$<?= number_format($row['price'], 2) ?></td>
                <td><?= $row['status']?></td>
                <td><?= $row['supplier_name']?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
        <p>No results found.</p>
    <?php endif; ?>
</main>
</body>
</html>