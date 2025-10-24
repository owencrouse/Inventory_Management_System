<?php
require '../backend/DatabaseHelper.php';
$db = new DatabaseHelper();

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

// Fetch existing product data
$product = null;
if (isset($_POST['id'])) {
    $result = $db->getItem($_POST['id'], 'ProductTable');
    $product = $result->fetch_assoc();
}

if (isset($_POST['product_name'])){
    try {
        $db->updateProduct($_POST['product_id'],$_POST['product_name'],$_POST['description'],$_POST['price'],$_POST['quantity'],$_POST['status'], $_POST['supplier_id']);
        header('Location: product.php?updateSuccess=""');
    } catch(Exception $e) {
        header('Location: product.php?updateError=""');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="../Styles/header.css">
  <link rel="stylesheet" href="../Styles/base.css">
  <link rel="stylesheet" href="../Styles/edit.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
    <?php include './header.php'; ?>
    <main class="edit">
    <div>
    <h1>Edit Product</h1>
    <?php if ($product): ?>
    <form method="POST">
        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>" required>
        <input type="hidden" name="supplier_id" value="<?= $product['supplier_id'] ?>" required>
        <div class="form-group">
            <label>Product Id:</label>
            <span><?= $product['product_id'] ?> </span>
        </div>
        <div class="form-group">
            <label>Product Name:</label>
            <input type="text" name="product_name" value="<?= $product['product_name'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Description:</label>
            <input type="text" name="description" value="<?= $product['description'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Price:</label>
            <input type="text" name="price" value="<?= $product['price'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Quantity:</label>
            <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Status:</label>
            <input type="text" name="status" value="<?= $product['status'] ?>" required>
        </div>
        <div class="form-group">
            <label>Supplier Id:</label>
            <span><?= $product['product_id'] ?> </span>
        </div>
        <div class="form-group">
        <button type="submit" class="btn">Update</button>
        <a href="product.php" class="btn">Cancel</a>
        </div>
    </form>
    <?php else: ?>
    <p>Product not found.</p>
    <?php endif; ?>
    </div>
</main>
</body>
</html>