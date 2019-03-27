<?php
include("_db_config.php");
include("_user_app_auth.php");


$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	$auth['dispatchs'] = null;
	echo json_encode($auth);
	exit;
}

include("_location_api.php");
include("_dispatch.php");


//api app
if (isset($_REQUEST['Dev']) && $_REQUEST['Dev'] == 'Dev')
{
	$_REQUEST['dev'] = 'true';
}
$js = dispatch_query($_REQUEST['ServiceMan'], $_REQUEST['dev']);
header('Content-Type: application/json');

echo json_encode($js);

?>