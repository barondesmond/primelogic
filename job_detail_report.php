<?php
include("_db_config.php");
include("_email.php");
include("_query.php");
include("_job.php");
include("_job_table.php");

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

$jobs = jobs_active_query();
//print_r($jobs);
$i=0;
$key = array('Type', 'Document', 'Est Units','Act Units', 'Estimate', 'JobToDate', 'Variance');
for ($i=0; $i < count($jobs); $i++)
{
	$job = $jobs[$i];

	$gr = job_query($job['Name'], 'Details');
	$td = job_details($gr);
	//print_r($td);
	if (!$hd)
	{
		$hd = $job;
		$hd['title'] = 'Job Details Report';
		$table = job_head($hd);
		$table .= job_hd($key);
		$table .= job_bar($key);
	}
	$table .= job_title($job);
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
echo $table;

?>