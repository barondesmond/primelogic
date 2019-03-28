<?php
include("_db_config.php");
include("_location_api.php");



include("_user_app_auth.php");
$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	echo json_encode($auth);
	exit;
}

if (isset($_REQUEST['file']))
{
	$js = $_REQUEST;
	$resp = location_details($_REQUEST['file']);
	$js['Details'] = $resp;

	header('Content-Type: application/json');
	echo json_encode($js);
	exit;

}










