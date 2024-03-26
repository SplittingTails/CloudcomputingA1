<?php
require_once '../bootstrap/bootstrap.php';
$pageTitle = 'HomePage';
top_module($pageTitle);
nav_module($pageTitle)
  ?>
<div class="login">
  <h1>Login</h1>
  <form class="formtable center" action="Post-validation" method="post">
    <span class="formrow">
      <label class="formcell" for="ID">ID</label>
      <input class="formcell" type="text" id="ID" name="ID">
    </span>
    <?php if (isset ($_SESSION['alerts']['Username_error']))
      echo '<p class="error">' . $_SESSION['alerts']['Username_error'] . '</p>'; ?>
    <span class="formrow">
      <label class="formcell" for="Password">Password</label>
      <input class="formcell" type="password" id="Password" name="Password"><br>
    </span>
    <?php if (isset ($_SESSION['alerts']['Password_error']))
      echo '<p class="error">' . $_SESSION['alerts']['Password_error'] . '</p>'; ?>

    <span style="display: table; margin: 0 auto;">
      <input type="submit" value="Login" id="Login" name='Login'>
      <?php if (isset ($_SESSION['alerts']['Login_Error']))
        echo '<p class="error">' . $_SESSION['alerts']['Login_Error'] . '</p>'; ?>


  </form>
  <button type="button" onclick="window.location.href='/register'">register</button>
  </span>
</div>

<?php
end_module()
  ?>