<?php
include("_db_config.php");
include("_location_api.php");
/*
LocName MSU Partnership School
locations=33.465871,-88.810235
Locations=33.44923,-88.8003



http://www.mapquestapi.com/geocoding/v1/address?key=MAPQUEST_KEY=MSU Partnership School, Starkville, MS
LocName,
latitude,
longitude,
1801 E Main Street, Tupelo, MS 38804
$tcm['results']['locations'][$i]['street'] == $loc['add1'] ? match : ambiguous
$loc = $tcm['results']['locations'][$i];
$loc['street']

*/
define('TCM', '
{
  "info": {
    "statuscode": 0,
    "copyright": {
      "text": " 2019 MapQuest, Inc.",
      "imageUrl": "http://api.mqcdn.com/res/mqlogo.gif",
      "imageAltText": " 2019 MapQuest, Inc."
    },
    "messages": []
  },
  "options": {
    "maxResults": -1,
    "thumbMaps": false,
    "ignoreLatLngInput": false
  },
  "results": [
    {
      "providedLocation": {
        "location": "1801 E Main Street, Tupelo, MS 38804"
      },
      "locations": [
        {
          "street": "1801 E Main St",
          "adminArea6": "",
          "adminArea6Type": "Neighborhood",
          "adminArea5": "Tupelo",
          "adminArea5Type": "City",
          "adminArea4": "Lee",
          "adminArea4Type": "County",
          "adminArea3": "MS",
          "adminArea3Type": "State",
          "adminArea1": "US",
          "adminArea1Type": "Country",
          "postalCode": "38804-2934",
          "geocodeQualityCode": "L1AAA",
          "geocodeQuality": "ADDRESS",
          "dragPoint": false,
          "sideOfStreet": "L",
          "linkId": "rnr3734152|i32549237",
          "unknownInput": "",
          "type": "s",
          "latLng": {
            "lat": 34.257765,
            "lng": -88.667338
          },
          "displayLatLng": {
            "lat": 34.257965,
            "lng": -88.667341
          }
        }
      ]
    }
  ]
}');

function parse_file($file)
{
	$key = array('time', 'EmpNo', 'Desc', 'LocName', 'latitude1', 'latitude2', 'longitude1', 'longitude2', 'ext');
	$exp = explode('.', $file);
	if (count($exp) != count($key))
	{
		//print_r($exp);
		
		return false;
	}
	//print_r($exp);
	for ($i=0;$i< count($exp); $i++)
	{
		$db[$key[$i]] = $exp[$i];
	}
	$db['latitude'] = $db['latitude1'] . '.' . $db['latitude2'];
	$db['longitude'] = $db['longitude1'] . '.' . $db['longitude2'];
$db['file'] = $file;
return $db;

}


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

$dir = '/var/www/html/primelogic/upload/';
$files = scandir($dir);
$js['files'] = $files;
	foreach ($files as $id=>$file)
	{
		if ($db = parse_file($file))
		{
			$sql = "SELECT * FROM Location WHERE LocName = '" . $db['LocName'] .  "'";
			$res = @mssql_query($sql);
			$lc = @mssql_fetch_array($res, MSSQL_ASSOC);
			if (isset($lc['LocName']))
			{
				$js[$db['LocName']][$id] = $db;
				$js['LocName'][$id] = $db['LocName'];
				$js['location'][$db['LocName']] = $lc;
			}
		}
	}
	foreach ($js['location'] as $location=>$lc)
	{

			$db = location_api($location);
			if ($db)
			{
				$js['locationapi'][$location] = $db;	
			}
	}
	foreach ($js['LocName'] as $id=> $LocName)
	{
		if (isset($js[$LocName][$id]))
		{
			$db = $js[$locname][$id];
		}
		if (isset($js['locationapi'][$LocName]))
		{
			$db2 = $js['locationapi'][$LocName];
		}
		if (isset($db['latitude']) && isset($db['longitude']) && isset($db2['latitude']) && isset($db2['longitude']))
		{
			$db['distance'] = distance($db['latitude'], $db['longitude'], $db2['latitude'], $db2['longitude']);
			$js[$locname][$id] = $db;
		}
		else
		{
			print_r($db);
			print_r($db2);
			exit;
		}
		unset($db);
		unset($db2);
	}
	header('Content-Type: application/json');
	echo json_encode($js);
	exit;
}














