<?php
require_once '../bootstrap/bootstrap.php';
require_once ("../public/Helper/googlefirestore.php");
$pageTitle = 'User admin';
top_module($pageTitle);
nav_module($pageTitle)
    ?>

<!DOCTYPE html>
<html>
<header>

</header>

<body class="content">
    <?php if (isset ($_SESSION['user'])) { ?>
        <div class="changepassword">
        <h1>Edit Password</h1>
            <form class="formrow" action="Post-validation" method="post">
            <span class="formrow">
                <label class="formcell" for="OldPassword">Old Password</label>
                <input class="formcell" type="password" id="OldPassword" name="OldPassword"><br>
            </span>
                <?php if (isset ($_SESSION['alerts']['OldPassword_error']))
                    echo '<p class="error">' . $_SESSION['alerts']['OldPassword_error'] . '</p>'; ?>
                    <span class="formrow">
                <label class="formcell" for="NewPassword">New Password</label>
                <input class="formcell" type="password" id="NewPassword" name="NewPassword"><br>
                    </span>
                <?php if (isset ($_SESSION['alerts']['NewPassword_error']))
                    echo '<p class="error">' . $_SESSION['alerts']['NewPassword_error'] . '</p>'; ?>
                <button type="submit" value="ChangePassword" id="ChangePassword" name='ChangePassword'>Change</button>
            </form>
        </div>
        <?php $count = 0;
        $messageData = data_query('Message', 'timestamp', 'DESC', 10, '', 'user_id,==,' . $_SESSION['user']['ID']);
        if ($messageData->rows() > 0) {
            echo '<h1>Edit Posted messages</h1>' . PHP_EOL;
            foreach ($messageData as $message) {
                if ($message->exists()) {
                    echo '<div id="review' . $count . '" >' . PHP_EOL;
                    echo '<table>' . PHP_EOL;
                    echo '<tr>' . PHP_EOL;
                        echo '<td>Message Date:</td>' . PHP_EOL;
                        echo '<td>' . date('j F Y H:i', strtotime($message['timestamp'])) . '' . PHP_EOL;
                    echo '</tr>' . PHP_EOL;
                        echo '<tr>' . PHP_EOL;
                        echo '<td>Subject:</td>' . PHP_EOL;
                        echo '<td>' . $message['subject'] . '</td>' . PHP_EOL;
                        echo '</tr>' . PHP_EOL;
                        echo '<tr>' . PHP_EOL;
                        echo '<td>Message:</td>' . PHP_EOL;
                        echo '<td>' . $message['message_Text'] . '</td>' . PHP_EOL;
                        echo '</tr>' . PHP_EOL;
                        if ($message['image_path'] !== NULL) {
                            echo '<td>Image</td>' . PHP_EOL;
                            echo '<td><img class="messageareaimg" src="' . $message['image_path'] . '" alt="Message_image"></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        echo '<tr>' . PHP_EOL;
                    echo '<td colspan="2" style="width:100%"><button onclick="myFunction(' . $count . ')">Edit</button></td>' . PHP_EOL;
                    echo '</tr>' . PHP_EOL;
                    echo '</table>' . PHP_EOL;
                    echo '</div>' . PHP_EOL;

                    echo '<div id="edit' . $count . '" style="display: none">' . PHP_EOL;
                    echo '<form action="Post-validation" method="post" enctype="multipart/form-data">' . PHP_EOL;
                    echo '<table>' . PHP_EOL;
                    echo '<tr>' . PHP_EOL;
                    echo '<td><label for="Subject">Subject:</label></td>' . PHP_EOL;
                    echo '<td><input type="text" name="Subject" id="Subject" value="' . $message['subject'] . '"></td>' . PHP_EOL;
                    echo '</tr>' . PHP_EOL;
                    echo '<tr>' . PHP_EOL;
                    echo '<td><label for="MessageText">Message:</label></td>' . PHP_EOL;
                    echo '<td><input type="text" name="MessageText" id="MessageText" value="' . $message['message_Text'] . '"></td>' . PHP_EOL;
                    echo '</tr>' . PHP_EOL;
                    echo '<tr>' . PHP_EOL;
                    echo '<td><label for="MessageImage">Image:</label></td>' . PHP_EOL;
                    echo '<td><input type="file" name="MessageImage" id="MessageImage">' . PHP_EOL;
                    if ($message['image_path'] !== NULL) {
                        echo '<img class="messageareaimg" src="' . $message['image_path'] . '" alt="Message_image">' . '' . PHP_EOL;
                    }
                    echo '</td>' . PHP_EOL;
                    echo '</tr>' . PHP_EOL;
                    echo '<input type="hidden" name="MessageID" id="MessageID" value="' . $message->id() . '"><br>' . PHP_EOL;
                    echo '<input type="hidden" name="UserID" id="UserID" value="' . $_SESSION['user']['ID'] . '"><br>' . PHP_EOL;
                    echo '<tr>' . PHP_EOL;
                    echo '<td colspan="2" style="width:100%"><button type="submit" value="UpdateMessage" id="UpdateMessage" name="UpdateMessage">Update</button>' . PHP_EOL;                 
                    echo '</form>' . PHP_EOL;
                    echo '<button type="button" onclick="myFunction(' . $count . ')">Cancel</button></td>' . PHP_EOL;
                    echo '</tr>' . PHP_EOL;
                    echo '</table>' . PHP_EOL;
                    echo '</div>' . PHP_EOL;
                    $count++;
                }
            }
        } else {
            echo 'No messages <br><br>'; ?>
        <?php }
    } else {
        header('Location: /');
    } ?>
</body>
<?php
end_module()
    ?>