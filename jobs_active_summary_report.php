<?php
include("_db_config.php");
include("_query.php");
include("_job.php");


$jobs = job_active_query();

foreach ($jobs as $job->$db)
{
	$gr = job_query($job);
	$td = job_sommary($gr);
}

?>