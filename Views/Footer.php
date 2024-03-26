<?php
/**** FOOTER OF WEBSITE ****/
function end_module($pageTitle)
{
    if (isset ($_SESSION['user']) || $pageTitle === 'Homepage' || $pageTitle === 'Register') { 
    $FileDate = date("Y F d  H:i", filemtime($_SERVER['SCRIPT_FILENAME']));
    $html = <<<"OUTPUT"
    <footer>
    <div>
        &copy;
        <script>
            document.write(new Date().getFullYear());
        </script> Ryan Bullock, S3273504. 26/03/2024
    </div>
    <div>Git Repo Link: <a href="https://github.com/SplittingTails/CloudcomputingA1"</a>https://github.com/SplittingTails/CloudcomputingA1</div>
   </footer>
   </body>
   </html>
  OUTPUT;
    echo $html;
    }
}