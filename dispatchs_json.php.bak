<?php
include("_db_config.php");
include("_user_app_auth.php");


$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	$auth['dispatchs'] = null;
	echo json_encode($auth);
	exit;
}

include("_location_api.php");



//api app

	 $sel = " and ServiceMan = '" . $_REQUEST['ServiceMan'] . "'";

if ($_REQUEST['dev'] == 'true')
{
	$d = 'Dev';
}
$js['title'] = 'Dispatch List';
$js['description'] = 'Dispatch Name, Dispatch Location';
$sql = "SELECT TPromDate, DispTech.Priority, Dispatch.Dispatch, Dispatch.Notes as DispatchNotes, Location.LocName as DispatchName, DispTech.Status, LocationApi.latitude, LocationApi.longitude, ServiceMan, Location.Add1, Location.Add2, Location.City, Location.State,Location.Zip, Location.Phone1 FROM DispTech" . $d . " as DispTech
INNER JOIN Dispatch" . $d . " as Dispatch ON DispTech.Dispatch = Dispatch.Dispatch
LEFT JOIN Location ON Dispatch.CustNo = Location.CustNo and Dispatch.LocNo = Location.LocNo
LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
WHERE DispTech.Complete != 'Y' and (DispTech.Status = 'Traveling' or DispTech.Status = 'Working' or DispTech.Status = 'Pending') and Location.Add1!= '' and Location.City!='' and Location.State!='' and Location.Zip!='' and Location.Phone1 != '' $sel
ORDER BY ServiceMan, DispTech.TPromDate DESC, DispTech.Priority ";

$res = mssql_query($sql);
$i=1;
$js['dispatchs'] = null;
while ($db = mssql_fetch_assoc($res))
{
	$db['id'] = $i;
	if ($db['latitude'] == '')
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
$js['sql'] = $sql;
header('Content-Type: application/json');

echo json_encode($js);

?>