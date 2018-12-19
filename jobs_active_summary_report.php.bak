<?php
include("_db_config.php");
include("_email.php");
include("_query.php");
include("_job.php");
include("_job_table.php");
define('SPOOLWRITE', 'write');

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
$i=0;
$key = array('Type', 'Estimate', "MonthToDate", "WeekToDate", 'JobToDate', 'Variance');
$table = job_disclaimer();

for ($i=0; $i < count($jobs); $i++)
{
	$job = $jobs[$i];

	$gr = job_query($job['Name']);
	$td = job_summary($gr);
	//print_r($td);
	if (!$hd)
	{
		$hd = $job;
		$hd['title'] = 'Jobs Active Summary Report';
		$table .= job_head($hd, $key);
	}
	$table .= job_summary_title($job, $key);
	$table .= job_summary_hd($key);
	$table .= job_summary_bar($key);

	$row['Type'] = 'Summary';
	$row['Document'] = '';
	$row['Est Units'] = '';
	$row['Act Units'] = '';
	$row['Estimate'] = '0.00';
	$row['JobToDate'] = '0.00';
	$row['Variance'] = '0.00';
	for ($t=0; $t< count($td); $t++)
	{
		$table .= job_row($td[$t], $key);
		//$row['Estimate'] = $row['Estimate'] + $td[$t]['Estimate'];
	

	}
	$row['Estimate'] = $td[0]['Estimate'] - $td[1]['Estimate'] - $td[2]['Estimate'];
	$row['JobToDate'] = $td[0]['JobToDate'] - $td[1]['JobToDate'] - $td[2]['JobToDate'];
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
		$table .= job_summary_bar($key);
		$table .= job_row($row, $key);
		$table .= job_summary_bar($key);
		unset($row);
	
	if ($i > 2)
	{
		//break;
		//$i = count($jobs);
	}
}
$table .= job_foot($key);
$html = '<html><body>' . $table . '</body></html>';
//echo $html;
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
else
{
	foreach ($emails as $email)
	{
		email_job_report($email, 'Jobs Active Summary Report', $html);
	}
}

?>