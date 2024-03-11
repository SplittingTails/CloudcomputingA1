<?php
/**** FOOTER OF WEBSITE ****/
function end_module()
{
   $FileDate = date("Y F d  H:i", filemtime($_SERVER['SCRIPT_FILENAME']));
   $html = <<<"OUTPUT"
    <footer>
    <div>
        &copy;
        <script>
            document.write(new Date().getFullYear());
        </script> Ryan Bullock, S3273504. 14/06/2021
        $FileDate
    </div>
    <div>Git Repo Link: <a href="https://github.com/SplittingTails/wp">https://github.com/SplittingTails/wp</a></div>
    <div>Disclaimer: This website is not a real website and is being developed as part of a School of Science Web Programming course at RMIT University in Melbourne, Australia.</div>
    <div><button id='toggleWireframeCSS' onclick='toggleWireframe()'>Toggle Wireframe CSS</button></div>
   </footer>
   </body>
   </html>
  OUTPUT;
   echo $html;
   echo "<div class=\"debug\">";
   echo "</div>";
}