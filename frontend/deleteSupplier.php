<?php
require '../backend/DatabaseHelper.php';
$db = new DatabaseHelper();
if (isset($_POST['id'])) {
    $delete = $db->deleteItem($_POST['id'], 'SupplierTable');
    if ($delete) {
        header("Location: supplier.php?deleteSuccess=''");
        exit();
    } else {
        header("Location: supplier.php?keyError=''");
    }
}
?>