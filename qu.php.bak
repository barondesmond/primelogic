<?php
include("_db_config.php");

if ($argv[1])
{
	$query = $argv[1];
}
if ($_GET['query'])
{
	$query = $_GET['query'];
}
	
	
	$res = mssql_query($query);
	$x=0;
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		show_data($db);
	}
	show_data(array(), '1');




?>