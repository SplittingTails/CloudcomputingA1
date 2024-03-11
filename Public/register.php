<?php
require_once("../bootstrap/bootstrap.php");
$pageTitle = 'HomePage';
top_module($pageTitle);
?>
<form action="Post-validation.php" method="post">
    <h1>Sign Up</h1>

    <label for="username">Username:</label>
    <input type="text" name="username" id="username">

    <label for="email">Email:</label>
    <input type="email" name="email" id="email">

    <label for="password">Password:</label>
    <input type="password" name="password" id="password">

    <label for="password2">Confirm Password:</label>
    <input type="password" name="password2" id="password2">
    <button type="submit" value="Register">Register</button>
</form>
<?php
end_module()
    ?>