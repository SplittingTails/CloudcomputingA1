<?php
require_once("../Public/Helper/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //clear alert error array
    unset($_SESSION['alerts']);
    //make alert error array
    $Alerts = array();
    //check add to cart post feilds 
    debug_to_console('Post_Validation');
    print_r($_POST);
    debug_to_console($_POST);
    if ($_POST['submit'] == "Register") {
        debug_to_console($_POST['submit']);
        $sql = 'INSERT INTO users(username, email, password, is_admin)
                    VALUES(:username, :email, :password, :is_admin)';

        $statement = db()->prepare($sql);

        $statement->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $statement->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $statement->bindValue(':password', password_hash($_POST['password'], PASSWORD_BCRYPT), PDO::PARAM_STR);
        $statement->bindValue(':is_admin', (int) 0, PDO::PARAM_INT);

         $statement->execute();

         header("Location: /");
         exit();
    }
}