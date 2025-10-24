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

// Fetch existing supplier data
$supplier = null;
if (isset($_POST['id'])) {
    echo $_POST['id'];
    $result = $db->getItem($_POST['id'], 'SupplierTable');
    $supplier = $result->fetch_assoc();
}

if (isset($_POST['supplier_name'])){
    try {
        $db->updateSupplier($_POST['supplier_id'],$_POST['supplier_name'],$_POST['address'],$_POST['phone'],$_POST['email']);
        header('Location: supplier.php?updateSuccess=""');
    } 
    catch(Exception $e) {
        header('Location: supplier.php?updateError=""');
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
    <h1>Edit Supplier</h1>
    <?php if ($supplier): ?>
    <form method="POST">
        <input type="hidden" name="supplier_id" value="<?= $supplier['supplier_id'] ?>">
        <div class="form-group">
            <label>Supplier Id:</label>
            <span><?= $supplier['supplier_id'] ?> </span>
        </div>
        <div class="form-group">
            <label>Supplier Name:</label>
            <input type="text" name="supplier_name" value="<?= $supplier['supplier_name'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Address:</label>
            <input type="text" name="address" value="<?= $supplier['address'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Phone:</label>
            <input type="text" name="phone" value="<?= $supplier['phone'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Email:</label>
            <input type="text" name="email" value="<?= $supplier['email'] ?>" required>
        </div>
        
        
        <button type="submit" class="btn">Update</button>
        <a href="supplier.php" class="btn">Cancel</a>
    </form>
    <?php else: ?>
    <p>Product not found.</p>
    <?php endif; ?>
    </div>
</main>
</body>
</html>