<?php
session_start();
require_once ("../public/Helper/googlefirestore.php");
require_once ("../public/Helper/googlecloudstorage.php");
require_once ("../public/Helper/helper.php");

use Google\Cloud\Firestore\FieldValue;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //clear alert error array
    unset($_SESSION['alerts']);
    //make alert error array
    $Alerts = array();

    /*** Register form ***/
    if ($_POST['Register'] == "Register") {

        $documents = data_query('UserAccount', '', '', 0, '', '');
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
            #$_POST['password'] = test_input($_POST["password"]);
            $_POST['password'] = password_hash($_POST["password"], PASSWORD_DEFAULT);
        }
        if ($_FILES['UserImage']['error'] === 0) {

            $check = getimagesize($_FILES['UserImage']["tmp_name"]);

            if ($check !== false) {
                $imageFileType = strtolower(pathinfo(basename($_FILES["UserImage"]["name"]), PATHINFO_EXTENSION));
                $_POST['UserUploadPath'] = upload_object('s3273504userimagev2', $_POST["ID"] . '_UserImage.' . $imageFileType, $_FILES['UserImage']["tmp_name"]);
            } else {
                $Alerts['UserImage_Error'] = "File is not an image.";

            }
        } elseif ($_FILES['UserImage']['error'] !== 4) {
            $Alerts['UserImage_Error'] = "File upload failed please, try again";
        }
        if (count($Alerts) > 0) {
            $_SESSION['alerts'] = $Alerts;
            header('Location: /register');
            exit();
        } else {

            $upload = [
                'password' => $_POST['password'],
                'user_name' => $_POST['username'],
                'image_path' => $_POST['UserUploadPath'],
                'ID' => $_POST['ID']
            ];
            data_set_from_map($upload, 'UserAccount');
            header('Location: /');
            exit();
        }

    }
    /*** Login form ***/
    if ($_POST['Login'] == "Login") {
        $documents = data_query('UserAccount', '', '', 0, $_POST["ID"], '');
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
        if (!$documents->snapshot()->exists()) {
           $Alerts['Login_Error'] = "ID or password is invalid";
        }
        if (count($Alerts) > 0) {
            $_SESSION['alerts'] = $Alerts;
            header('Location: /');
            exit();
        } else {
            if ($_POST["ID"] === $documents->snapshot()->id() && password_verify($_POST["Password"], $documents->snapshot()->data()['password'])) {
                $_SESSION['user']['ID'] = $documents->snapshot()->id();
                $_SESSION['user']['image_path'] = $documents->snapshot()->data()['image_path'];
                header("Location: /Forum");
            } else {
                $Alerts['Login_Error'] = "ID or password is invalid";
                $_SESSION['alerts'] = $Alerts;
                #header("Location: /");
            }
        }
        exit();
    }

    /*** Message form ***/
    if ($_POST['Message'] == "Message") {
        $uploadimage = false;
        if (empty ($_POST['Subject'])) {
            $Alerts['Subject_error'] = "Subject is required";
        } else {
            $_POST['Subject'] = test_input($_POST["Subject"]);
        }
        if (empty ($_POST['MessageText'])) {
            $Alerts['MessageText_error'] = "Message Text is required";
        } else {
            $_POST['MessageText'] = test_input($_POST["MessageText"]);
        }
        if (empty ($_POST['ID'])) {
            $Alerts['ID_error'] = "ID is required";
        } else {
            $_POST['ID'] = test_input($_POST["ID"]);
        }
        if ($_FILES['MessageImage']['error'] === 0) {
            $check = getimagesize($_FILES['MessageImage']["tmp_name"]);
            if ($check !== false) {

                $imageFileType = strtolower(pathinfo(basename($_FILES["MessageImage"]["name"]), PATHINFO_EXTENSION));
                $uploadimage = true;

            } else {
                $Alerts['UserImage_Error'] = "File is not an image.";
                $uploadimage = false;
            }
        }
        if (count($Alerts) > 0) {
            $_SESSION['alerts'] = $Alerts;
            header('Location: /Forum');
            exit();
        } else {

            $upload = [
                'subject' => $_POST['Subject'],
                'message_Text' => $_POST['MessageText'],
                'timestamp' => FieldValue::serverTimestamp(),
                'user_id' => $_POST['ID']

            ];


            $DocID = get_random_docid($upload, 'Message');
            if ($uploadimage) {
                $_POST['MessageUploadPath'] = upload_object('s3273504messageimagev2', $DocID . '_MessageImage.' . $imageFileType, $_FILES['MessageImage']["tmp_name"]);
            }
            $upload = [
                ['path' => 'image_path', 'value' => $_POST['MessageUploadPath']]
            ];
            data_update_document($upload, 'Message', $DocID);
            header('Location: /Forum');
        }

    }

    /*** Login form ***/
    if ($_POST['ChangePassword'] == "ChangePassword") {

        if (empty ($_POST['OldPassword'])) {
            $Alerts['OldPassword_error'] = "Old password is required";
        } else {
            $_POST['OldPassword'] = test_input($_POST["OldPassword"]);
        }
        if (empty ($_POST['NewPassword'])) {
            $Alerts['NewPassword_error'] = "New password is required";
        } else {
            #$_POST['NewPassword'] = test_input($_POST["NewPassword"]);
            $_POST['NewPassword'] = password_hash($_POST["NewPassword"], PASSWORD_DEFAULT);
        }
        if (count($Alerts) > 0) {
            $_SESSION['alerts'] = $Alerts;
            header('Location: /');
            exit();
        } else {
            $documents = data_query('UserAccount', '', '', 0, $_SESSION['user']['ID'], '');
            $snapshot = $documents->snapshot();
            if ($snapshot->exists()) {
                if ($_SESSION['user']['ID'] === $snapshot->id() && password_verify($_POST["OldPassword"], $snapshot->data()['password'])) {

                    $upload = [
                        ['path' => 'password', 'value' => $_POST['NewPassword']]
                    ];
                    data_update_document($upload, 'UserAccount', $_SESSION['user']['ID']);
                    $Alerts['OldPassword_error'] = "Change password Successfully";
                    $_SESSION['alerts'] = $Alerts;
                    header("Location: /Logout");
                } else {
                    $Alerts['OldPassword_error'] = "The old password is incorrect";
                    $_SESSION['alerts'] = $Alerts;
                    header("Location: /useradmin");
                }
            } else {
                $Alerts['OldPassword_error'] = "No ID exists";
                $_SESSION['alerts'] = $Alerts;
                header("Location: /useradmin");

            }
            exit();
        }
    }

    /*** UpdateMessage form ***/

    if ($_POST['UpdateMessage'] == "UpdateMessage") {
        /*NEED TO FIX BUG WERE IMAGE IS NULL DELETING IMAGE*/
        $uploadimage = false;
        if (empty ($_POST['Subject'])) {
            $Alerts['Subject_error'] = "Subject is required";
        } else {
            $_POST['Subject'] = test_input($_POST["Subject"]);
        }
        if (empty ($_POST['MessageText'])) {
            $Alerts['MessageText_error'] = "Message Text is required";
        } else {
            $_POST['MessageText'] = test_input($_POST["MessageText"]);
        }
        if (empty ($_POST['UserID'])) {
            $Alerts['UserID_error'] = "UserID is required";
        } else {
            $_POST['UserID'] = test_input($_POST["UserID"]);
        }
        if (empty ($_POST['MessageID'])) {
            $Alerts['MessageID_error'] = "MessageID is required";
        }
        if ($_FILES['MessageImage']['error'] === 0) {
            $check = getimagesize($_FILES['MessageImage']["tmp_name"]);
            if ($check !== false) {

                $imageFileType = strtolower(pathinfo(basename($_FILES["MessageImage"]["name"]), PATHINFO_EXTENSION));
                $uploadimage = true;

            } else {
                $Alerts['UserImage_Error'] = "File is not an image.";
                $uploadimage = false;
            }
        }
        if (count($Alerts) > 0) {
            $_SESSION['alerts'] = $Alerts;
            header('Location: /useradmin');
            exit();
        } else {

            if ($uploadimage) {
                $_POST['MessageUploadPath'] = upload_object('s3273504messageimagev2', $_POST['MessageID'] . '_MessageImage.' . $imageFileType, $_FILES['MessageImage']["tmp_name"]);

            }

            if (!isset ($_POST['MessageUploadPath'])) {
                $upload = [
                    ['path' => 'subject', 'value' => $_POST['Subject']],
                    ['path' => 'message_Text', 'value' => $_POST['MessageText']],
                    ['path' => 'timestamp', 'value' => FieldValue::serverTimestamp()],
                    ['path' => 'user_id', 'value' => $_POST['UserID']]
                ];
            } else {
                $upload = [
                    ['path' => 'subject', 'value' => $_POST['Subject']],
                    ['path' => 'message_Text', 'value' => $_POST['MessageText']],
                    ['path' => 'image_path', 'value' => $_POST['MessageUploadPath']],
                    ['path' => 'timestamp', 'value' => FieldValue::serverTimestamp()],
                    ['path' => 'user_id', 'value' => $_POST['UserID']]
                ];
            }
            data_update_document($upload, 'Message', $_POST['MessageID']);
            header('Location: /Forum');
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
