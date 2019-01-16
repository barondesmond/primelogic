<?php
include("_db_config.php");
//api app


$js['title'] = 'Jobs List';
$js['description'] = 'Job Name, Job Location';
$sql = "SELECT  Jobs.Name as Name, Location.LocName as LocName, Jobs.JobNotes as JobNotes, LocationApi.latitude, LocationApi.longitude FROM Jobs
	INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
	LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
	WHERE JobStatus = '100' and Inactive = '0' and Location.Add1 != '' and Location.City != '' and Location.State != '' and Location.Zip != ''
	ORDER BY Name ";
$res = mssql_query($sql);
$i=1;
while ($db = mssql_fetch_assoc($res))
{
	$db['id'] = $i;
	$js['jobs'][] = $db;
	$i++;

}

header('Content-Type: application/json');

echo json_encode($js);

?>