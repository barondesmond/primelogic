<?php
include("_db_config.php");
include("_user_app_auth.php");

$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	echo json_encode($auth);
	exit;
}
include("_location_api.php");
//api app

if ($_REQUEST['dev'] == 'true')
{
	$dev = 'Dev';
}
$js = jobs_query($dev);

header('Content-Type: application/json');

echo json_encode($js);

?>