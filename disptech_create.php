<?php
include("_db_config.php");
include("_location_api.php");
include("_dispatch.php");
include('_report.php');
include ('_pdf.php');
include ('_email.php');
include("_user_app_auth.php");
include("dispatch_pdf.php");

include("_job.php");
include("_employees.php");

if ($argv)
{
	$db['Dispatch'] = $argv[1];
	$db['ServiceMan'] = $argv[2];
	$er = disptech_create($db);
	print_r($er);
}