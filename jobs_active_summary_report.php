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
$key = array('Type', 'Document', 'Est Units','Act Units', 'Estimate', 'JobToDate', 'Variance');
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
		$table = job_head($hd, $key);
		$table .= job_hd($key);
		$table .= job_bar($key);
	}
	$table .= job_title($job, $key);
	for ($t=0; $t< count($td); $t++)
	{
		$table .= job_row($td[$t], $key);
	}
		$table .= job_bar($key);
	
	
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