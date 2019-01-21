<?php
include("_db_config.php");
include("_location_api.php");
//api app
if ($_REQUEST['dev'] == 'true')
{

}
else
{
	 $sel = " and ServiceMan = '" . $_REQUEST['ServiceMan'] . "'";
}

$js['title'] = 'Dispatch List';
$js['description'] = 'Dispatch Name, Dispatch Location';
$sql = "SELECT TPromDate, DispTech.Priority, Dispatch.Dispatch, Dispatch.Notes, Location.LocName, DispTech.Status, LocationApi.latitude, LocationApi.longitude, ServiceMan FROM DispTech
INNER JOIN Dispatch ON DispTech.Dispatch = Dispatch.Dispatch
LEFT JOIN Location ON Dispatch.CustNo = Location.CustNo and Dispatch.LocNo = Location.LocNo
LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
WHERE DispTech.Complete != 'Y'  and ServiceMan = '0001' $sel
ORDER BY ServiceMan, DispTech.TPromDate DESC, DispTech.Priority ";

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