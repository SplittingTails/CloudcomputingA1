<?php
/***** HEADER OF WEBSITE ******/
function nav_module($pageTitle)
{

    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    //change page title based on active page
    ($pageTitle == "HomePage") ? $Home = "class=\"active\"" : $Home = "";
    ($pageTitle == "Register") ? $Register = "class=\"active\"" : $Register = "";
    ($pageTitle == "User admin") ? $useradmin = "class=\"active\"" : $useradmin = "";
    ($pageTitle == "Forum") ? $Forum = "class=\"active\"" : $Forum = "";


    echo '<nav>' . PHP_EOL;
    echo '<ul>' . PHP_EOL;
    if (!isset ($_SESSION['user'])) {
        echo '<li ' . $Home . '><a href="/">Home</a></li>' . PHP_EOL;
        echo '<li ' . $Register . '><a href="register">Register</a></li>' . PHP_EOL;
    }
    ;
    if (isset ($_SESSION['user'])) {
        echo '<li ' . $useradmin . '><a href="Forum">Forum</a></li>' . PHP_EOL;
        echo '<li ' . $Forum . '><a href="useradmin"> User admin</a></li>' . PHP_EOL;
        echo '<li><a href="Logout">Logout</a></li>' . PHP_EOL;
        if (isset ($_SESSION['user'])) {
            echo '<li class="navusersection"><a href="/useradmin">' . $_SESSION['user']['ID'] . '</a>' . PHP_EOL;
            echo '<a href="/useradmin"><img class="navuserimage"  src="' . $_SESSION['user']['image_path'] . '";alt="User_image"></a></li>' . PHP_EOL;
        }
    }
    echo '</ul>' . PHP_EOL;
    echo '</nav>' . PHP_EOL;

}
?>