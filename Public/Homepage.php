<?php if (!isset($_SESSION)) {
    session_start();
  }?>

<!DOCTYPE html>
<html>
<header>
</header>

<body class="content">
<h1>Login</h1>
<form action="Post-validation" method="post">
<label for="ID">ID</label>
<input type="text" id="ID" name="ID"><br>
<?php if (isset($_SESSION['alerts']['Username_error'])) echo '<p class="error">' . $_SESSION['alerts']['Username_error'] . '</p>';?>
<label for="Password">Password</label>
<input type="password" id="Password" name="Password"><br>
<?php if (isset($_SESSION['alerts']['Password_error'])) echo '<p class="error">' . $_SESSION['alerts']['Password_error'] . '</p>';?>
<input type="submit" value="Login" id="Login" name='Login'>  
<?php if (isset($_SESSION['alerts']['Login_Error'])) echo '<p class="error">' . $_SESSION['alerts']['Login_Error'] . '</p>';?>
</form>
<a href="/register">
    <button>Register</button>
  </a>



</body>

</html>