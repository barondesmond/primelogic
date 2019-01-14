<?php
include("_db_config.php");

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


function mapquest_api($loc)
{

	return json_decode(TCM, true);
	$url = MAPQUEST_GEO_URL . '&key=' . MAPQUEST_KEY . '&location=' . $loc;
	
	$respJson = file_get_contents($url);
	echo $respJson;
	$resp = json_decode($respJson, 1);
	//print_r($resp);
	//exit;
return $resp;
}

function mapquest_match($resp, $db)
{
	//print_r($resp);
	$loc = $resp['results']['0']['locations']['0'];
	if ($loc['street'] == $db['add1'])
	{
			//match
		$match = $loc;
			//print_r($match);
			//exit;
		return $match;
	}
	else
	{
		return false;
	}

return false;
}
		


function distance($lat1, $lon1, $lat2, $lon2) {

    $pi80 = M_PI / 180;
    $lat1 *= $pi80;
    $lon1 *= $pi80;
    $lat2 *= $pi80;
    $lon2 *= $pi80;

    $r = 6372.797; // mean radius of Earth in km
    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;
    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $km = $r * $c;

    //echo '<br/>'.$km;
    return $km;
}

if ( $_REQUEST['LocName'])
{
	$_REQUEST['LocName'] = str_replace("'", "''", $_REQUEST['LocName']);

	$sql = "SELECT Add1, City,State,Zip FROM Location WHERE LocName = '" . $_REQUEST['LocName'] . "'";
	//echo $sql;
	$res = mssql_query($sql);
	$error[] = mssql_get_last_message();
	$loc = mssql_fetch_array($res, MSSQL_ASSOC);

	$loca = $loc['Add1'] . ',' .  $loc['City'] . ',' . $loc['State'] . ' ' . $loc['Zip'];

	$resp = mapquest_api($loca);
	//echo TCM;
	//var_dump(json_decode(TCM));
	//var_dump(json_decode(TCM, true));
	//exit;
	//print_r($loc);
	if ($match = mapquest_match($resp, $loc))
	{
		$resp = $match;
	}	

}
$db = array_merge($_REQUEST, $resp);
$db['error'] = $error;
header('Content-Type: application/json');
//echo TCM;
echo json_encode($db);

