<?php
require_once ("../public/Helper/googlefirestore.php");
if (!isset ($_SESSION)) {
    session_start();
} ?>

<!DOCTYPE html>
<html>
<header>
    <script src='/static/javascript/script.js'></script>
</header>

<body class="content">
    <?php if (isset ($_SESSION['user'])) { ?>
        <div>
            <form action="Post-validation" method="post">
                <h1>Edit Password</h1>
                <label for="OldPassword">Old Password</label>
                <input type="password" id="OldPassword" name="OldPassword"><br>
                <?php if (isset ($_SESSION['alerts']['OldPassword_error']))
                    echo '<p class="error">' . $_SESSION['alerts']['OldPassword_error'] . '</p>'; ?>
                <label for="NewPassword">New Password</label>
                <input type="password" id="NewPassword" name="NewPassword"><br>
                <?php if (isset ($_SESSION['alerts']['NewPassword_error']))
                    echo '<p class="error">' . $_SESSION['alerts']['NewPassword_error'] . '</p>'; ?>
                <button type="submit" value="ChangePassword" id="ChangePassword" name='ChangePassword'>Change</button>
            </form>
        </div>
        <?php $count = 0;
        echo 'user_id,==,' . $_SESSION['user']['ID'];

        $messageData = data_query('Message', 'timestamp', 'DESC', 10, '', 'user_id,==,' . $_SESSION['user']['ID']);


        foreach ($messageData as $message) {
            if ($message->exists()) {
                echo '<div id="review' . $count . '" >' . PHP_EOL;
                echo '</br></br>Message ' . $count . '</br>' . PHP_EOL;
                echo '' . $message['subject'] . '</br>' . PHP_EOL;
                echo '' . $message['message_Text'] . '</br>' . PHP_EOL;
                if ($message['image_path'] !== NULL) {
                    echo '<img style="width: 120px; height: 120px;" src="' . $message['image_path'] . '" alt="Message_image">' . '</br>' . PHP_EOL;
                }
                echo '' . $message['timestamp'] . '</br>' . PHP_EOL;
                echo '<button onclick="myFunction(' . $count . ')">Edit</button>' . PHP_EOL;
                echo '</div>' . PHP_EOL;

                echo '<div id="edit' . $count . '" style="display: none">' . PHP_EOL;
                echo '</br></br>Message ' . $count . '</br>' . PHP_EOL;
                echo '<form action="Post-validation" method="post" enctype="multipart/form-data">' . PHP_EOL;
                echo '<label for="Subject">Subject:</label>' . PHP_EOL;
                echo '<input type="text" name="Subject" id="Subject" value="' . $message['subject'] . '"><br>' . PHP_EOL;
                echo '<label for="MessageText">Message:</label>' . PHP_EOL;
                echo '<input type="text" name="MessageText" id="MessageText" value="' . $message['message_Text'] . '"><br>' . PHP_EOL;
                echo '<input type="file" name="MessageImage" id="MessageImage"><br>' . PHP_EOL;
                if ($message['image_path'] !== NULL) {
                    echo '<img style="width: 120px; height: 120px;" src="' . $message['image_path'] . '" alt="Message_image">' . '</br>' . PHP_EOL;
                }
                echo '<input type="hidden" name="MessageID" id="MessageID" value="' . $message->id() . '"><br>' . PHP_EOL;
                echo '<input type="hidden" name="UserID" id="UserID" value="' . $_SESSION['user']['ID'] . '"><br>' . PHP_EOL;
                echo '<button type="submit" value="UpdateMessage" id="UpdateMessage" name="UpdateMessage">Update</button>' . PHP_EOL;
                echo '</form>' . PHP_EOL;
                echo '<button onclick="myFunction(' . $count . ')">Cancel</button>' . PHP_EOL;
                echo '</div>' . PHP_EOL;
                $count++;
            } else {
                echo 'No messages';
            }
        } ?>
    <?php } else {
        header('Location: /');
    } ?>
</body>

</html>