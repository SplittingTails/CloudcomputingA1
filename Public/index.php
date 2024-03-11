<?php
require_once("../Helper/helper.php");
/**
 * This is an example of a front controller for a flat file PHP site. Using a
 * Static list provides security against URL injection by default. See README.md
 * for more examples.
 */
# [START gae_simple_front_controller]
debug_to_console(@parse_url($_SERVER['REQUEST_URI'])['path']);
switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/':
        require 'homepage.php';
        break;
    case '/Register.php':
        require 'Register.php';
        break;
    case '/Helper/Post-validation.php':
        require '/Helper/Post-validation.php';
        break;
    default:
        http_response_code(404);
        exit('Not Found');
}


# [END gae_simple_front_controller]