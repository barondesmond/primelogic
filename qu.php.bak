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
}	
	echo '<form method="GET" action="' . $_SERVER['PHP_SELF'] . '">';
	echo '<textarea name="query" rows="12" cols="100" value="' . $query . '">';
	echo $query;	

	echo '</textarea><input type=submit></form>';



	
	$res = mssql_query($query);
	echo	mssql_get_last_message();
	if (!$res && $mes = mssql_get_last_message($res))
	{
		echo "$mes";
	}
	$x=0;
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		$table[] = $db;
	}
	query_table($table);




?>