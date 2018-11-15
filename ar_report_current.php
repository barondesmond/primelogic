<?php
include("_db_config.php");
include("_report.php");
include("_email.php");


$day = '0';
$day2 = '30';
$emp = ' ';
$dept = ' ';
$email = 'barondesmond@gmail.com';

if (isset($argv[1]))
{
	$day = $argv[1];
}
 if (isset($argv[2]))
 {
	 $day2 = $argv[2];
 }
 if (isset($argv[3]))
 {
	 	 $emp = $argv[2];
 }

 if (isset($argv[4]))
 {
	 $dept = $argv[3];
 }

 report_basis($day, $day2, $emp, $dept, $email);


?>