<?php if (!isset ($_SESSION)) {
    session_start();
} ?>

<!DOCTYPE html>
<html>
<header>
</header>

<body class="content">
    <?php if (isset ($_SESSION['user'])) {
        echo '<h1> Welcome to the Forum</h1>' . PHP_EOL;
        echo '<a href="https://www.w3schools.com">' . $_SESSION['user']['ID'] . '</a>' . PHP_EOL;
        echo '<a href="https://www.w3schools.com"><img src="' . $_SESSION['user']['image_path'] . '" alt="User_image"></a>' . PHP_EOL;
    } else {
        header('Location: /');
    } ?>
</body>

</html>