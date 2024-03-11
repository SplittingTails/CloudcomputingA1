<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //clear alert error array
    unset($_SESSION['alerts']);
    //make alert error array
    $Alerts = array();
    //check add to cart post feilds 
    if ($_POST['submit'] == "Register") {
        debug_to_console($_POST['submit']);
        $sql = 'INSERT INTO users(username, email, password, is_admin)
                    VALUES(:username, :email, :password, :is_admin)';

        $statement = db()->prepare($sql);

        $statement->bindValue(':username', $username, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
        $statement->bindValue(':is_admin', (int) $is_admin, PDO::PARAM_INT);

        return $statement->execute();

    }
}