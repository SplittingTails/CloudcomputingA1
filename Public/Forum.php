<?php
require_once '../bootstrap/bootstrap.php';
$pageTitle = 'Forum';
top_module($pageTitle);
nav_module($pageTitle);
?>
<?php if (isset ($_SESSION['user'])) { ?>
    <div style="text-align: center;">
        <h1> Welcome to the Forum</h1>
        User Name:
        <a href="/useradmin">
            <?php echo $_SESSION['user']['ID'] ?>
        </a>
        <a href="/useradmin"><img style="width: 120px; height: 120px;" src="<?php echo $_SESSION['user']['image_path'] ?>"
                alt="User_image"></a>
        <h1>Post a message</h1>
        <div class="postmessagearea">
            <form class="formtable center" action="Post-validation" method="post" enctype="multipart/form-data">

                <span class="formrow">
                    <label class="formcell" for="Subject">Subject:</label>
                    <input class="formcell" type="text" name="Subject" id="Subject"><br>
                </span>
                <?php if (isset ($_SESSION['alerts']['Subject_error']))
                    echo '<p class="error">' . $_SESSION['alerts']['Subject_error'] . '</p>'; ?>
                <span class="formrow">
                    <label class="formcell" for="MessageText">Message:</label>
                    <textarea class="formcell" name="MessageText" id="MessageText" rows="4" cols="20
                    "></textarea><br>
                </span>
                <?php if (isset ($_SESSION['alerts']['MessageText_error']))
                    echo '<p class="error">' . $_SESSION['alerts']['MessageText_error'] . '</p>'; ?>
                <span class="formrow">
                    <label class="formcell" for="MessageImage">Message Image:</label>
                    <input class="formcell" type="file" name="MessageImage" id="MessageImage"><br>
                </span>
                <?php if (isset ($_SESSION['alerts']['UserImage_Error']))
                    echo '<p class="error">' . $_SESSION['alerts']['UserImage_Error'] . '</p>'; ?>
                <input type="hidden" id="ID" name="ID" value="<?php echo $_SESSION['user']['ID'] ?>">
                <?php if (isset ($_SESSION['alerts']['ID_error']))
                    echo '<p class="error">' . $_SESSION['alerts']['ID_error'] . '</p>'; ?>
                <button type="submit" value="Message" id="Message" name='Message'>Submit Message</button>
            </form>
        </div>
        <h1>messages</h1>
        <div class="messagearea">
            <?php $count = 0;
            $messageData = data_query('Message', 'timestamp', 'DESC', 10, '', '');
            $userData = data_query('UserAccount', '', '', 0, '', '');
            if ($messageData->rows() > 0) {
                foreach ($messageData as $message) {
                    if ($message->exists()) {
                        echo '<table>' . PHP_EOL;
                        echo '<tr>' . PHP_EOL;
                        echo '<td>Message Info:</td>' . PHP_EOL;
                        echo '<td>' . date('j F Y H:i', strtotime($message['timestamp'])) . '' . PHP_EOL;
                        foreach ($userData as $user) {
                            if ($message['user_id'] === $user->id()) {
                                echo '' . $user['user_name'] . '<img class="messageuserimg" src="' . $user['image_path'] . '" alt="User_image"></td>' . PHP_EOL;
                            }
                        }

                        echo '</tr>' . PHP_EOL;
                        echo '<tr>' . PHP_EOL;
                        echo '<td>Subject:</td>' . PHP_EOL;
                        echo '<td>' . $message['subject'] . '</td>' . PHP_EOL;
                        echo '</tr>' . PHP_EOL;
                        echo '<tr>' . PHP_EOL;
                        echo '<td>Message:</td>' . PHP_EOL;
                        echo '<td>' . $message['message_Text'] . '</td>' . PHP_EOL;
                        echo '</tr>' . PHP_EOL;
                        echo '<tr>' . PHP_EOL;
                        if ($message['image_path'] !== NULL) {
                            echo '<td>Image</td>' . PHP_EOL;
                            echo '<td><img class="messageareaimg" src="' . $message['image_path'] . '" alt="Message_image"></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }

                        echo '</table>' . PHP_EOL;
                    }
                }
            } else {
                echo 'No Messages';
            }
            ?>
        </div>
    </div>
<?php } else {
    header('Location: /');
} ?>
<?php
end_module($pageTitle)
    ?>