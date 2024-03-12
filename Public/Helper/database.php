<?php
function db(): PDO
{

    static $conn;

    if (!isset($conn)) {

        $servername = "localhost";
        $database = 'Auth';
        $username = "root";
        $password = "Newpassword123";

        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $conn;
}


