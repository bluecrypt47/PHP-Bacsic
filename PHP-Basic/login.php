<!DOCTYPE html>
<html>
<style>
  .form {
    width: 300px;
    border: 1px solid green;
    padding: 20px;
    margin: 0 auto;
    font-weight: 700px;
  }

  .form input {
    width: 100%;
    padding: 10px 0;
  }
</style>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>

  <form method="post" action="login.php" class="form">

    <h2>Login</h2>

    Email: <input type="email" name="email" value="" required />

    Password: <input type="password" name="password" value="" required />

    <input type="submit" name="login" value="Login" />
    <a href="./register.php">Register</a>
    <?php require 'handle.php'; ?>
  </form>

</body>

</html>