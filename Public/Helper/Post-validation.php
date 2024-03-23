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
                $_POST['UserUploadPath'] = upload_object('s3273504userimages', $_POST["ID"] . '_UserImage.' . $imageFileType, $_FILES['UserImage']["tmp_name"]);
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
        $documents = data_query('UserAccount', '', '', 0, '', '');
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
        if ($documents->isEmpty()) {
            $Alerts['Login_Error'] = "ID or password is invalid";
        }
        if (count($Alerts) > 0) {
            $_SESSION['alerts'] = $Alerts;
            header('Location: /');
            exit();
        } else {
            foreach ($documents as $document) {
                if ($document->exists()) {
                    if ($_POST["ID"] === $document->id() && password_verify($_POST["Password"], $document['password'])) {
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
                'image_path' => $_POST['MessageUploadPath'],
                'timestamp' => FieldValue::serverTimestamp(),
                'user_id' => $_POST['ID']

            ];
            $DocID = get_random_docid($upload,'Message');
            if ($uploadimage) {
                $_POST['MessageUploadPath'] = upload_object('s3273504messageimage', $DocID . '_MessageImage.' . $imageFileType, $_FILES['MessageImage']["tmp_name"]);
            }
            $upload = [
                ['path' => 'image_path', 'value' => $_POST['MessageUploadPath']]
            ];
            data_update_document($upload, 'Message', $DocID);
            header('Location: /Forum');
        }

    }

    /*** Logout form ***/
    if ($_POST['Logout'] == "Logout") {
        kill_session();
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
            $documents = data_query('UserAccount', '', '', 0, $_SESSION['user']['ID'],'');
            $snapshot = $documents->snapshot();
            if ($snapshot->exists()) {
                if ($_SESSION['user']['ID'] === $snapshot->id() && password_verify($_POST["OldPassword"], $snapshot->data()['password'])) {

                    $upload = [
                        ['path' => 'password', 'value' => $_POST['NewPassword']]
                    ];
                    data_update_document($upload, 'UserAccount', $_SESSION['user']['ID']);
                    $Alerts['OldPassword_error'] = "Change password Successfully";
                    $_SESSION['alerts'] = $Alerts;
                    kill_session();
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
            get_random_docid('Message');
            if ($uploadimage) {
                $_POST['MessageUploadPath'] = upload_object('s3273504messageimage', $DocID . '_MessageImage.' . $imageFileType, $_FILES['MessageImage']["tmp_name"]);
            }
            $upload = [
                'subject' => $_POST['Subject'],
                'message_Text' => $_POST['MessageText'],
                'image_path' => $_POST['MessageUploadPath'],
                'timestamp' => FieldValue::serverTimestamp(),
                'user_id' => $_POST['ID']

            ];

            Set_DocID_Data($upload, 'Message', $DocID);
            header('Location: /Forum');
        }
    }
    /*** UpdateMessage form ***/

    if ($_POST['UpdateMessage'] == "UpdateMessage") {

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
        } else {
            $_POST['MessageID'] = test_input($_POST["MessageID"]);
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
                $_POST['MessageUploadPath'] = upload_object('s3273504messageimage', $_Post['MessageID'] . '_MessageImage.' . $imageFileType, $_FILES['MessageImage']["tmp_name"]);
            }

            $upload = [
                'subject' => $_POST['Subject'],
                'message_Text' => $_POST['MessageText'],
                'image_path' => $_POST['MessageUploadPath'],
                'timestamp' => FieldValue::serverTimestamp(),
                'user_id' => $_POST['UserID']

            ];
            Set_DocID_Data($upload, 'Message', $_POST['MessageID']);
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

function kill_session()
{
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    header('Location: /');
}