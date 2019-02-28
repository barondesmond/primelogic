<?php
include("_db_config.php");
include("_user_app_auth.php");

$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	echo json_encode($auth);
	exit;
}
include("_location_api.php");
//api app

if ($_REQUEST['dev'] == 'true')
{
	$dev = 'Dev';
}
$js['title'] = 'Jobs List';
$js['description'] = 'Job Name, Job Location';
$sql = "SELECT  Jobs.Name as Name, Location.LocName as LocName, CONCAT(Location.Add1, ' ,', Location.City, ' ,' , Location.State, ' ' , Location.Zip) as location, Location.Add1, Location.City, Location.State, Location.Zip,Jobs.JobNotes as JobNotes, Location.latitude, Location.longitude FROM Jobs$dev as Jobs
	INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
	WHERE JobStatus = '100' and Inactive = '0' and Location.Add1 != '' and Location.City != '' and Location.State != '' and Location.Zip != ''
	ORDER BY LocName ";
$res = mssql_query($sql);
$i=1;
while ($db = mssql_fetch_assoc($res))
{
	$db['id'] = $i;
	$db['latitude'] = location_int_gps($db['latitude']);
	$db['longitude'] = location_int_gps($db['longitude']);

	if ($db['latitude'] == '' || $db['latitude'] == '0')
	{
		$loc = location_api($db['LocName'], $db);
		$db['latitude'] = $loc['latitude'];
		$db['longitude'] = $loc['longitude'];

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