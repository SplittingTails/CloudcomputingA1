<?php if (!isset ($_SESSION)) {
    session_start();
} ?>

<!DOCTYPE html>
<html>
<header>
</header>

<body class="content">
    <?php if (isset ($_SESSION['user'])) { ?>
        <h1> Welcome to the Forum</h1>
        <a href="https://www.w3schools.com">
            <?php echo $_SESSION['user']['ID'] ?>
        </a>
        <a href="https://www.w3schools.com"><img style="width: 120px; height: 120px;" src="<?php echo $_SESSION['user']['image_path'] ?>" alt="User_image"></a>
        <div>
            <form action="Post-validation" method="post" enctype="multipart/form-data">
                <h1>Sign Up</h1>

                <label for="Subject">Subject:</label>
                <input type="text" name="Subject" id="Subject"><br>
                <?php if (isset ($_SESSION['alerts']['Subject_error']))
                    echo '<p class="error">' . $_SESSION['alerts']['Subject_error'] . '</p>'; ?>
                <label for="MessageText">Message:</label>
                <input type="text" name="MessageText" id="MessageText"><br>
                <?php if (isset ($_SESSION['alerts']['MessageText_error']))
                    echo '<p class="error">' . $_SESSION['alerts']['MessageText_error'] . '</p>'; ?>
                <label for="MessageImage">Message Image:</label>
                <input type="file" name="MessageImage" id="MessageImage"><br>
                <?php if (isset ($_SESSION['alerts']['UserImage_Error']))
                    echo '<p class="error">' . $_SESSION['alerts']['UserImage_Error'] . '</p>'; ?>
                <input type="hidden" id="ID" name="ID" value="<?php echo $_SESSION['user']['ID'] ?>">
                <?php if (isset ($_SESSION['alerts']['ID_error']))
                    echo '<p class="error">' . $_SESSION['alerts']['ID_error'] . '</p>'; ?>
                <button type="submit" value="Message" id="Message" name='Message'>Submit Message</button>
            </form>
        </div>

    <?php } else {
        header('Location: /');
    } ?>
</body>

</html>