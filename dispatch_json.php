<?php
include("_db_config.php");
include("_location_api.php");
//api app


$js['title'] = 'Jobs List';
$js['description'] = 'Job Name, Job Location';
$sql = "SELECT Dispatch.Dispatch, Dispatch.Notes, Location.LocName, DispTech.Status, LocationApi.latitude, LocationApi.longitude FROM DispTech
INNER JOIN Dispatch ON DispTech.Dispatch = Dispatch.Dispatch
LEFT JOIN Location ON Dispatch.CustNo = Location.CustNo and Dispatch.LocNo = Location.LocNo
LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
WHERE DispTech.Complete != 'Y'  and ServiceMan = '0001'

ORDER BY DispTech.SortDate ASC ";
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