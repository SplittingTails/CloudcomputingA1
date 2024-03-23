<?php
require_once '../bootstrap/bootstrap.php';
$pageTitle = 'Register';
top_module($pageTitle);
?>
<form action="Post-validation" method="post" enctype="multipart/form-data">
    <h1>Sign Up</h1>

    <label for="ID">ID:</label>
    <input type="text" name="ID" id="ID"><br>
    <?php if (isset ($_SESSION['alerts']['ID_error']))
        echo '<p class="error">' . $_SESSION['alerts']['ID_error'] . '</p>'; ?>
    <label for="username">Username:</label>
    <input type="text" name="username" id="username"><br>
    <?php if (isset ($_SESSION['alerts']['username_error']))
        echo '<p class="error">' . $_SESSION['alerts']['username_error'] . '</p>'; ?>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password"><br>
    <?php if (isset ($_SESSION['alerts']['password_error']))
        echo '<p class="error">' . $_SESSION['alerts']['password_error'] . '</p>'; ?>
    <label for="UserImage">User Image:</label>
    <input type="file" name="UserImage" id="UserImage"><br>
    <?php if (isset ($_SESSION['alerts']['UserImage_Error']))
        echo '<p class="error">' . $_SESSION['alerts']['UserImage_Error'] . '</p>'; ?>

    <button type="submit" value="Register" id="Register" name='Register'>Register</button>
</form>
<?php
end_module()
    ?>