<?php
include("_db_config.php");
include("_location_api.php");
//api app


$js['title'] = 'Jobs List';
$js['description'] = 'Job Name, Job Location';
$sql = "SELECT  Jobs.Name as Name, Location.LocName as LocName, Jobs.JobNotes as JobNotes, LocationApi.latitude, LocationApi.longitude FROM Jobs
	INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
	LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
	WHERE JobStatus = '100' and Inactive = '0' and Location.Add1 != '' and Location.City != '' and Location.State != '' and Location.Zip != ''
	ORDER BY LocName ";
$res = mssql_query($sql);
$i=1;
while ($db = mssql_fetch_assoc($res))
{
	$db['id'] = $i;
	if ($db[latitude] == '')
	{
		location_api($db);
	}
	if ($_REQUEST['latitude']!='null' && $_REQUEST['latitude'] != '' &&  $db['latitude'] != '' && $db['latitude'] != 'null')
	{
		$db['distance'] = distance($_REQUEST['latitude'], $_REQUEST['longitude'], $db['latitude'], $db['longitude']);
	}
	$js['jobs'][] = $db;

	$i++;

}

header('Content-Type: application/json');

echo json_encode($js);

?>