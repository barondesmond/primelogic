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
include("_job.php");
include("_location_api.php");
include("_employees.php");
//api app

if ($_REQUEST['dev'] == 'true')
{
	$dev = 'Dev';
}
else
{
	$dev = '';
}
if (!isset($_REQUEST['ServiceMan']))
{
	$_REQUEST['ServiceMan'] = '';
}
if (!isset($_REQUEST['order']))
{
	$_REQUEST['order'] = 'LocName';
}
$js = jobs_query($dev, $_REQUEST['ServiceMan'], $_REQUEST['order']);

header('Content-Type: application/json');

echo json_encode($js);

?>