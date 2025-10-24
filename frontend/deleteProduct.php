<?php
require '../backend/DatabaseHelper.php';
$db = new DatabaseHelper();
echo $_POST['id'];
if (isset($_POST['id'])) {
    $delete = $db->deleteItem($_POST['id'], 'ProductTable');
    if ($delete) {
        header("Location: product.php?deleteSuccess=''");
    } else {

        header("Location: product.php?deleteSuccess=''");
    }
}
?>