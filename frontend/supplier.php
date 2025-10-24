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
    $results = $db->searchSupplier($_GET['query']);
} else {
    $results = $db->searchSupplier('');
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
<main class="table">
    <h1>Supplier Dashboard</h1>
    <form method="GET" class="search-form">
        <input type="text" name="query" placeholder="Search products..." 
               value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
        <button type="submit" class="btn">Search</button>
    </form>

    <?php
        if (isset($_GET['deleteSuccess'])){
            echo "<div class='success'>Successfully Deleted Item</div>";
        } else if (isset($_GET['keyError'])){
            echo "<div class='error'>Unable to delete supplier, please delete corresponding product first</div>";
        } else if (isset($_GET['updateSuccess'])){
            echo "<div class='success'>Successfully Updated Item</div>";
        } else if (isset($_GET['updateError'])){
            echo "<div class='error'>An error occurred when updating the item</div>";
        }
    ?>

    <?php if ($results && $results->num_rows > 0): ?>
        <table>
            <tr>
                <th>Supplier ID</th>
                <th>Supplier Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?= $row['supplier_id'] ?></td>
                <td><?= $row['supplier_name'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td><?= $row['email'] ?></td>

                <td class='actions'>
                    <form method="POST" action="editSupplier.php">
                            <input type="hidden" name="id" value="<?= $row['supplier_id'] ?>"/>
                            <button type="submit">Edit</button>
                    </form>
                    <form method="POST" action="deleteSupplier.php">
                        <input type="hidden" name="id" value="<?= $row['supplier_id'] ?>"/>
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
        <p>No results found.</p>
    <?php endif; ?>
</main>
</body>
</html>