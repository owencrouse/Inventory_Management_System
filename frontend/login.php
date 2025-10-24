<?php
require '../backend/DatabaseHelper.php';
$dbHelp = new DatabaseHelper();
$GLOBAL['invalid'] = 0;
if (isset($_POST['email'])){
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $isLogged = $dbHelp->checkLogin($email, $password);
  if ($isLogged){
    $GLOBAL['invalid'] = 0;
    setcookie('login', "$email $password", time() + (3600 * 60), "/");
    header('Location: /frontend/product.php');
  } else {
    $GLOBAL['invalid'] = 1;
  }
}
$_POST['login'] = NULL;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="../Styles/base.css">
    <link rel="stylesheet" href="../Styles/Login.css">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body class='login'>
    <div class='login-container'>
      <h1>Log In</h1>

      <form method="POST" class='form-container'>
        <div class="login-input">
          <label for="email">Email</label>
          <input type="text" name="email" required/>
        </div>
        <div class="login-input">
        <label for="password">Password</label>
        <input type="text" name="password" required/>
        </div>

        <div style="<?php echo $GLOBAL['invalid'] === 1 ? "display:block;" : "display:none;" ?>" class="invalid">
          <p>Invalid Username or Password</p>
        </div>
        <button type="submit" class='submit-button'> Login </button>
      </form>
    </div>
  </body>
</html>
