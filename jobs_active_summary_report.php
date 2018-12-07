<?php
include("_db_config.php");
include("_query.php");
include("_job.php");


$jobs = jobs_active_query();
print_r($jobs);
foreach ($jobs as $job=>$db)
{
	$gr = job_query($job);
	$td = job_sommary($gr);
}

?>