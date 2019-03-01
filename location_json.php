<?php
include("_db_config.php");
include("_location_api.php");


if (isset($_REQUEST['LocName']))
{
$db = location_api($_REQUEST['LocName']);
//echo TCM;
if (isset($_REQUEST['array']))
{
	print_r($db);
	exit;
}
else
{
	header('Content-Type: application/json');
	echo json_encode($db);
	exit;
}
}
else
{
include("_user_app_auth.php");
$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	echo json_encode($auth);
	exit;
}


	$js = jobs_query();

	foreach ($js['location'] as $id=> $location)
	{
		if (isset($js[$location][$id]))
		{
			$db = $js[$location][$id];
		}
		if (isset($js['locationapi'][$location]))
		{
			$db2 = $js['locationapi'][$location];
		}
		if (isset($db['latitude']) && isset($db['longitude']) && isset($db2['latitude']) && isset($db2['longitude']))
		{
			$db['distance'] = distance($db['latitude'], $db['longitude'], $db2['latitude'], $db2['longitude']);
			$js['locrow'][$location][] = $db;
		}
		unset($db);
		unset($db2);
	}
	header('Content-Type: application/json');
	echo json_encode($js);
	exit;

}










