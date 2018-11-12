<?php
include("_db_config.php");
echo "test";
$con = db_connect();

$version  = mssql_query('SELECT @@VERSION');
$row = mysql_fetch_array($version);

echo $row[0];

mssql_free_result($version);
?>
