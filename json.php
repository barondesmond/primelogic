<?php
include("_db_config.php");
include("_query.php");
if ($argv[1])
{
	$query = $argv[1];

}
if (isset($_REQUEST['query'])
{
	$query = $_GET['query'];	
	
	$exp = query_export($query);
	if (isset($_REQUEST['json']))
	{
		header('Content-Type: application/json');
		echo json_encode($exp);
	}
}	





?>