<?php
//include("main_include.php");

echo "test";

$con = mssql_connect('rds01\esc', 'Reports', 'Reports123!');
$sql = "SHOW DATABASES";
$res = mssql_query($sql);
$db = mssql_fetch_array($res);
print_r($db);

?>