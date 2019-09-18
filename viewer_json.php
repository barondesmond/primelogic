<?php
include("_db_config.php");
include("_location_api.php");


if (isset($_REQUEST['LocName']))
{
$db = location_api($_REQUEST['LocName']);
//echo TCM;
if (isset($_REQUEST['array']))
{
	print_r($db);
	exit;
}
else
{
	header('Content-Type: application/json');
	echo json_encode($db);
	exit;
}
}
else
{
include("_user_app_auth.php");
$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1' && !isset($_REQUEST['test']))
{
	header('Content-Type: application/json');
	echo json_encode($auth);
	exit;
}
	if (isset($_REQUEST['location_update']))
	{
		$js = location_update($_REQUEST);
		header('Content-Type: application/json');
		echo json_encode($js);
		exit;
	}

	$js = viewer_query();
	header('Content-Type: application/json');
	echo json_encode($js);
	exit;

}










