<html>
<body>
<form method=POST ACTION="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type=text name=var>
<input type=submit name=post>
</form>

<?php
var_dump($_REQUEST);
var_dump($_POST);
$input = file_get_contents('php://input');
var_dump($input);
?>