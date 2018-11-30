<?php
include("_db_config.php");
include("_query.php");
if ($argv[1])
{
	$query = $argv[1];
}
if ($_GET['query'])
{
	$query = $_GET['query'];
}	echo '<form method="GET" action="' . $_SERVER['PHP_SELF'] . '">';
	echo '<input type=textarea name="query" width="100" height="100">';
	
	echo $query;

	echo '</textarea><input type=submit></form>';

	
	$res = mssql_query($query);
	$x=0;
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		show_data($db);
	}
	show_data(array(), '1');




?>