<?php
/***** HEADER OF WEBSITE ******/
function top_module($pageTitle)
{
   session_start();

   /*session is started if you don't write this line can't use $_Session  global variable*/

   $html = <<<"OUTPUT"
    <!DOCTYPE html>
    <html lang='en'>
       <head>
          <title>$pageTitle</title>
          <script src='/javascript/script.js'></script>
          <link type="text/css" rel="stylesheet" href="/stylesheet/styles.css">
       </head>
       <body class="content">
       <div>
             <header class="center">
                <h1 id="title">$pageTitle</title>
                </head></h1>
             </header>
  OUTPUT;
   echo $html;
}
?>