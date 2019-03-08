<?php
include("_db_config.php");
include("_email.php");
include("_query.php");
include("_job.php");
include("_job_table.php");
define('SPOOLWRITE', 'write');
define('OVERHEAD', '0.28');
/*
	$row['Type'] = 'Contract';
	$row['Document'] = '';
	$row['Est Units'] = '';
	$row['Act Units'] = '';
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'40006', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'11000', 'Source' => '400', 'CostType' => '0');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Estimate'] = $row['Estimate'] * -1;
*/
if (isset($argv[3]))
{
	$Email = $argv[3];
}
if (isset($argv[1]))
{
	$Year = $argv[1];
}
if (isset($argv[2]))
{
	$Amount = $argv[2];
}
if (isset($_GET['Email']))
{
	$Email = $_GET['Email'];
}
if (isset($_GET['Amount']))
{
	$Amount = $_GET['Amount'];
}
if (isset($_GET['Year']))
{
	$Year = $_GET['Year'];
}

if (!isset($Year)
{
	$Year = '2018';
}
if (!isset($Amount))
{
	$Amount = '10000';
}
$jobs = jobs_year_query($Year, $Amount);
$title = 'Jobs Year ' . $Year . ' Amount ' . $Amount . ' Summary Report';

//print_r($jobs);

$html = jobs_summary_report($jobs, $title);

if ($_GET['print'] || !isset($argv))
{
	echo $html;
}
if ($Email != '' && email_validate($Email))
{

	email_job_report($Email, $title, $html);
	echo "Report Emailed $Email";
}
elseif (!isset($_GET['print']) && !isset($Email))
{
	foreach ($emails as $email)
	{
		email_job_report($email, $title, $html);
	}
}

?>