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

$jobs = jobs_active_query($argv[2]);
//print_r($jobs);

$html = jobs_summary_report($jobs);

if ($_GET['print'])
{
	echo $html;
}
if ($argv[1])
{

	email_job_report($argv[1], 'Jobs Active Summary Report', $html);
	echo "Report Emailed $argv[1];";
}
elseif ($_GET['email'] && email_validate($_GET['email']))
{
	email_job_report($_GET['email'], 'Jobs Active Summary Report', $html);
	echo "Report Emailed " . $_GET['email'];
}
elseif (!$_GET['print'])
{
	foreach ($emails as $email)
	{
		email_job_report($email, 'Jobs Active Summary Report', $html);
	}
}

?>