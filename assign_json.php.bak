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
include("_job.php");
include("_employees.php");


//api app

if ($_REQUEST['add_job_group'])
{
	$sql = "INSERT INTO JobGroup (JobGroup) VALUES('" . $_REQUEST['JobGroup'] . "')";
	$res = mssql_query($sql);
	$js['sql'] = $sql;
	$js['error'] = mssql_get_last_message();
	header('Content-Type: application/json');
	echo json_encode($js);
	exit;
}


$js = jobs_query($_REQUEST['dev']);
$js = array_merge($js, employees_query($_REQUEST['dev']));
$js = array_merge($js, jobgroups_query($_REQUEST['dev']));


header('Content-Type: application/json');

echo json_encode($js);

?>