<?php
include("_db_config.php");
include("_location_api.php");
//api app
if ($_REQUEST['dev'] == 'true')
{
	$sel = " and ServiceMan = '0001'";
}
else
{
	 $sel = " and ServiceMan = '" . $_REQUEST['ServiceMan'] . "'";
}

$js['title'] = 'Dispatch List';
$js['description'] = 'Dispatch Name, Dispatch Location';
$sql = "SELECT TPromDate, DispTech.Priority, Dispatch.Dispatch, Dispatch.Notes as DispatchNotes, Location.LocName as DispatchName, DispTech.Status, LocationApi.latitude, LocationApi.longitude, ServiceMan FROM DispTech
INNER JOIN Dispatch ON DispTech.Dispatch = Dispatch.Dispatch
LEFT JOIN Location ON Dispatch.CustNo = Location.CustNo and Dispatch.LocNo = Location.LocNo
LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
WHERE DispTech.Complete != 'Y'  $sel
ORDER BY ServiceMan, DispTech.TPromDate DESC, DispTech.Priority ";

$res = mssql_query($sql);
$i=1;
$js['dispatchs'] = null;
while ($db = mssql_fetch_assoc($res))
{
	$db['id'] = $i;
	if ($db[latitude] == '')
	{
		location_api($db['DispatchName']);
	}
	if ($_REQUEST['latitude']!='null' && $_REQUEST['latitude'] != '' &&  $db['latitude'] != '' && $db['latitude'] != 'null')
	{
		$db['distance'] = distance($_REQUEST['latitude'], $_REQUEST['longitude'], $db['latitude'], $db['longitude']);
	}
	$js['dispatchs'][] = $db;

	$i++;

}

header('Content-Type: application/json');

echo json_encode($js);

?>