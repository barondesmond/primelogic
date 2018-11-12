<?php
include("_db_config.php");
echo "test";

$version  = mssql_query('SELECT @@VERSION');
$row = mssql_fetch_array($version);
echo $row[0];

mssql_free_result($version);
?>
