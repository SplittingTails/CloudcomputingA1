<?php
require_once '../bootstrap/bootstrap.php';
$pageTitle = 'Register';
top_module($pageTitle);
nav_module($pageTitle)
?>
<form class="formtable center" action="Post-validation" method="post" enctype="multipart/form-data">
    <h1>Sign Up</h1>
    <span class="formrow">
    <label class="formcell" for="ID">ID:</label>
    <input class="formcell" type="text" name="ID" id="ID"><br>
    </span>
    <?php if (isset ($_SESSION['alerts']['ID_error']))
        echo '<p class="error">' . $_SESSION['alerts']['ID_error'] . '</p>'; ?>
        <span class="formrow">
    <label class="formcell" for="username">Username:</label>
    <input class="formcell" type="text" name="username" id="username"><br>
        </span>
    <?php if (isset ($_SESSION['alerts']['username_error']))
        echo '<p class="error">' . $_SESSION['alerts']['username_error'] . '</p>'; ?>
        <span class="formrow">
    <label class="formcell" for="password">Password:</label>
    <input class="formcell" type="password" name="password" id="password"><br>
        </span>
    <?php if (isset ($_SESSION['alerts']['password_error']))
        echo '<p class="error">' . $_SESSION['alerts']['password_error'] . '</p>'; ?>
        <span class="formrow">
    <label class="formcell" for="UserImage">User Image:</label>
    <input class="formcell" type="file" name="UserImage" id="UserImage"><br>
        </span>
    <?php if (isset ($_SESSION['alerts']['UserImage_Error']))
        echo '<p class="error">' . $_SESSION['alerts']['UserImage_Error'] . '</p>'; ?>

    <button class="formcell" type="submit" value="Register" id="Register" name='Register'>Register</button>
</form>
<?php
end_module()
    ?>