<?php
include("_db_config.php");
echo "test";

$version  = mssql_query('SELECT @@VERSION');
$row = mysql_fetch_array($version);

echo $row[0];

?>
