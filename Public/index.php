<?php

/**
 * This is an example of a front controller for a flat file PHP site. Using a
 * Static list provides security against URL injection by default. See README.md
 * for more examples.
 */
# [START gae_simple_front_controller]

switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/':
        require 'Homepage.php';
        break;
    case '/register':
        require '../public/register.php';
        break;
    case '/Post-validation':
        require '../public/Helper/Post-validation.php';
        break;
    case '/Forum':
        require '../public/Forum.php';
        break;
    case '/useradmin':
        require '../public/useradmin.php';
        break;
    case '/Logout':
        require '../public/Helper/logout.php';
        break;
    default:
        http_response_code(404);
        exit ('Not Found');
}


# [END gae_simple_front_controller]