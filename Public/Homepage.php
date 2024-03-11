<?php
require_once("../bootstrap/bootstrap.php");
$pageTitle = 'HomePage';
top_module($pageTitle);
debug_to_console("Test");
?>
<div>
    <form action="/action_page.php" method="get">
        <label for="email">Email:</label>
        <input type="email">
        <label for="fname">Password:</label>
        <input type="password">
        <input type="submit">
    </form>
</div>
<?php
end_module()
    ?>