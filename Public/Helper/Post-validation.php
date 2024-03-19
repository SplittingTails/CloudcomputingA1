<?php
session_start();
require_once ("../public/Helper/googlefirestore.php");
require_once ("../public/Helper/googlecloudstorage.php");
require_once ("../public/Helper/helper.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //clear alert error array
    unset($_SESSION['alerts']);
    //make alert error array
    $Alerts = array();

    /*** Register form ***/
    if ($_POST['Register'] == "Register") {
        debug_to_console($_POST['Register']);
        $documents = data_query('UserAccount');
        if (empty ($_POST['ID'])) {
            $Alerts['ID_error'] = "ID is required";
        } else {
            foreach ($documents as $document) {
                if ($document->exists()) {
                    if ($_POST["ID"] === $document->id()) {
                        $Alerts['ID_error'] = "The ID already exists";
                    } else {
                        $_POST['ID'] = test_input($_POST["ID"]);
                    }
                }
            }
        }
        if (empty ($_POST['username'])) {
            $Alerts['username_error'] = "username is required";
        } else {
            foreach ($documents as $document) {
                if ($document->exists()) {
                    if ($_POST["username"] === $document['user_name']) {
                        $Alerts['username_error'] = "The Username already exists";
                    } else {
                        $_POST['username'] = test_input($_POST["username"]);
                    }
                }
            }
        }
        if (empty ($_POST['password'])) {
            $Alerts['password_error'] = "Password is required";
        } else {
            $_POST['password'] = test_input($_POST["password"]);
            #$_POST['password'] = password_hash($_POST["password"], PASSWORD_DEFAULT);
        }
        if ($_FILES['UserImage']['error'] === 0) {

            print_r($_FILES);
            $check = getimagesize($_FILES['UserImage']["tmp_name"]);

            if ($check !== false) {
                debug_to_console("File is an image - " . $check["mime"] . ".");
                $imageFileType = strtolower(pathinfo(basename($_FILES["UserImage"]["name"]), PATHINFO_EXTENSION));
                $_POST['UploadPath'] = upload_object($_POST["ID"] . '_UserImage.' . $imageFileType, $_FILES['UserImage']["tmp_name"]);
            } else {
                $Alerts['UserImage_Error'] = "File is not an image.";

            }
        } else {
            $Alerts['UserImage_Error'] = "File upload failed please, try again";
        }
        if (count($Alerts) > 0) {
            $_SESSION['alerts'] = $Alerts;
            debug_to_console('alert > 0');
            header('Location: /register');
            exit();
        } else {
            debug_to_console($_POST);
            data_set_from_map($_POST, 'UserAccount');
            header('Location: /');
            exit();
        }

    }
    /*** Login form ***/
    if ($_POST['Login'] == "Login") {

        if (empty ($_POST['ID'])) {
            $Alerts['Username_error'] = "ID is required";
        } else {
            $_POST['ID'] = test_input($_POST["ID"]);
        }
        if (empty ($_POST['Password'])) {
            $Alerts['Password_error'] = "Password is required";
        } else {
            $_POST['Password'] = test_input($_POST["Password"]);
        }
        if (count($Alerts) > 0) {
            $_SESSION['alerts'] = $Alerts;
            header('Location: /');
            exit();
        } else {
            $documents = data_query('UserAccount');

            foreach ($documents as $document) {
                if ($document->exists()) {
                    if ($_POST["ID"] === $document->id() && $_POST["Password"] === $document['password']) {
                        $_SESSION['user']['ID'] = $document->id();
                        $_SESSION['user']['image_path'] = $document['image_path'];
                        header("Location: /Forum");
                    } else {
                        $Alerts['Login_Error'] = "ID or password is invalid";
                        $_SESSION['alerts'] = $Alerts;
                        header("Location: /");
                    }
                }
            }
            exit();
        }
    }
}


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}