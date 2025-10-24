<?php
setcookie('login', '', time() - (3600 * 60), "/");
header('Location: /frontend/login.php');
?>