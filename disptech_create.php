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
	$_REQUEST['Dispatch'] = $argv[1];
	$_REQUEST['ServiceMan'] = $argv[2];
	if ($argv[3])
	{
		$_REQUEST['Dev'] = $argv[3];
	}
	$er = disptech_create($_REQUEST, $_REQUEST['Dev']);
	print_r($er);
}