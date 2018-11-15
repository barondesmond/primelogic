<?php
include("_db_config.php");
include("_report.php");
include("_email.php");


$day = '';
$emp = '';
$dept = '';
$email = '';

if (isset($argv[1]))
{
	$day = $argv[1];
}
 if (isset($argv[2])
 {
	 $emp = $argv[2];
 }
 if (isset($argv[3])
 {
	 $dept = $argv[3];
 }

 if (isset($argv[4])
 {
	 $email = $argv[4];
 }

 report_basis($day, $emp, $dept, $email);


?>