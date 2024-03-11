<?php

/***** HEADER OF WEBSITE ******/
function top_module($pageTitle)
{
  session_start();

  /*session is started if you don't write this line can't use $_Session  global variable*/

  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  //change page title based on active page
  ($pageTitle == "HomePage") ? $Home = "class=\"active\"" : $Home = "";
  ($pageTitle == "About") ? $Register = "class=\"active\"" : $Register = "";
  ($pageTitle == "Shop") ? $Shop = "class=\"active\"" : $Shop = "";

  $html = <<<"OUTPUT"
    <!DOCTYPE html>
    <html lang='en'>
       <head>
          <title>$pageTitle</title>
       </head>
       <body>
       <div>
             <header>
                <h1 id="title">HomePage</h1>
             </header>
             <nav>
                <ul>
                   <li $Home><a href="homepage.php">Home</a></li>
                   <li $Register><a href="register.php">Register</a></li>
                   <li $Shop><a href="../a2/shop.php">Shop</a></li>
                </ul>
             </nav>
  OUTPUT;
  echo $html;
}
?>