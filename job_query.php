<?php
include("_db_config.php");
include("_query.php");
include("_job.php");



$gr = job_query($_GET['val'], 'details');
if ($_GET[action] == 'summary')
{
	job_summary($gr);
}
elseif ($_GET['action'] == 'details')
{
	job_details($gr);
}
else
{
	job_summary($gr);
}
?>