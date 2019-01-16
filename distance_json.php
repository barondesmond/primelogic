<?
include("_location_api.php");

if ($_REQUEST[latitude1] && $_REQUEST[longitude1] && $_REQUEST[latitude2] && $_REQUEST[longitude2])
{
	$db = $_REQUEST;
	$db[distance] = distance($db[latitude1], $db[longitude1], $db[latitude2], $db[longitude2]);
}

header('Content-Type: application/json');
//echo TCM;
echo json_encode($db);