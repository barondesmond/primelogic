<?php
include("_db_config.php");
include("_user_app_auth.php");


$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	echo json_encode($data);
	exit;
}
//api app
include("_employees.php");
employees_query();


header('Content-Type: application/json');

echo json_encode($js);

?>