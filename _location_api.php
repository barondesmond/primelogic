<?php


function mapquest_reverse_geocode($lat,$long)
{
//$url = "http://open.mapquestapi.com/geocoding/v1/reverse?key=" . MAPQUEST_KEY . "&location=" . $lat . ',' . $long . '&includeRoadMetadata=true&includeNearestIntersection=true' ;
$url = "https://www.mapquestapi.com/geocoding/v1/reverse?key=" . MAPQUEST_KEY . "&location=" . $lat . '%2C' . $long . '&outFormat=json&thumbMaps=true&includeNearestIntersection=true&includeRoadMetadata=true';

$respJson = file_get_contents($url);

$resp = json_decode($respJson, 1);

return $resp;
}


function mapquest_api($loc)
{

	$fd = '/var/www/html/primelogic/json/' . $loc . '.json'; 
	if (!file_exists($fd))
	{
		$url = MAPQUEST_GEO_URL . '?key=' . MAPQUEST_KEY . '&location=' . urlencode($loc);
		$respJson = file_get_contents($url);
		$file = fopen($fd, "w");
		fwrite($file, $respJson);
		fclose($file);
	}
	else
	{
		$respJson = file_get_contents($fd);
		//echo $respJson;
		
	}
	$resp = json_decode($respJson, 1);
	//print_r($resp);
	//exit;
return $resp;
}

function mapquest_match($resp, $db)
{
	//print_r($resp);
	$loc = $resp['results']['0']['locations']['0'];
	$loc['latitude'] = $loc['latLng']['lat'];
	$loc['longitude'] = $loc['latLng']['lng']; 
	return $loc;
	//print_r($loc);
	//print_r($db);
	if (trim($loc['street']) == trim($db['Add1']) || $loc['geocodeQualityCode'] == 'P1AAA')
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
function location_api_insert($resp, $loc)
{

	$sql = "INSERT INTO LocationApi (LocName, Add1, City, State, Zip, latitude, longitude, location) VALUES(";
	$sql .= "'" . str_replace("'", "''", $resp['LocName']) . "',";
	$sql .= "'" . str_replace("'", "''", $resp['Add1']) . "',";
	$sql .= "'" . str_replace("'", "''", $resp['City']) . "',";
	$sql .= "'" . str_replace("'", "''", $resp['State']) . "',";
	$sql .= "'" . str_replace("'", "''", $resp['Zip']) . "',";
	$sql .= "'" . $resp['latLng']['lat'] . "',";
	$sql .= "'" . $resp['latLng']['lng'] . "',";
	$sql .= "'" . str_replace("'", "''", $loc) . "'";
	$sql .= ");";

	mssql_query($sql);

	return mssql_get_last_message();
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

function location_int_gps($int)
{
	if (strpos('.', $int) === true)
	{
		return $int;
	}
	if (substr($int, 0, 1) == '-')
	{
		$lat1 = substr($int,0,3);
		$lat2 = substr($int,3, strlen($int));
	}
	else
	{
		$lat1 = substr($int, 0, 2);
		$lat2 = substr($int, 2, strlen($int));
	}
		$int = $lat1 . '.' . $lat2;	

return $int;
}

function location_parse_file($file)
{
	$key = array('time', 'date', 'EmpNo', 'Screen', 'reference', 'location', 'Desc', 'latitude1', 'latitude2', 'longitude1', 'longitude2', 'ext');
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
	unset ($db['latitude1']);
	unset($db['latitude2']);
	unset($db['longitude1']);
	unset($db['longitude2']);

$db['file'] = $file;
return $db;

}

function location_file_update($db)
{
	$file = '';
	$update = array('time', 'date', 'EmpNo', 'Screen', 'reference', 'location', 'Desc', 'latitude', 'longitude', 'AcceptDeny', 'ext');


	foreach ($update as $num=>$key)
	{
		if (isset($db[$key]))
		{
			$file .= $db[$key] . '.';
		}
		else
		{
			$db['error'] = 'missing ' . $key;
			return $db;
		}
	}
	$file = substr($file, 0, strlen($file) - 1);
	$dir = '/var/www/html/primelogic/upload/';

	if  (rename($dir . $db['file'], $dir . $file))
	{
		$db['newfile'] = $file;
		$db['error'] = 'Success';
	}
	else
	{
		$db['error'] = 'file ' . $db['file'] . ' failed to move ' . $file;
	}
return $db;
}

function location_update($db)
{
	if (isset($db['file']))
	{
		if ($lp = location_parse_file($db['file']))
		{
			if (isset($db['Accept']))
			{
				$lp['AcceptDeny'] = $db['Accept'];
			}
			elseif (isset($db['Deny']))
			{
				$lp['AcceptDeny'] = $db['Deny'];
			}
			else
			{
				$lp['error'] = 'Missing Parameter Accept/Deny';
			}
			$lp = location_file_update($lp);
		}
		else
		{
			$lp['error'] = 'Missing File Parse Error';
			$lp['file'] = $db['file'];
		}
	}
	else
	{
		$lp['error'] = 'Missing File';
		$lp['file'] = $db['file'];
	}
return $lp;

}

function  location_lookup($lc)
{

			$sql = "SELECT CustNo, LocNo, LocName, CONCAT(Location.Add1, ',', Location.City, ',' , Location.State, ' ' , Location.Zip) as location,  Location.Add1, Location.City, Location.State, Location.Zip FROM Location WHERE CONCAT(Location.Add1, ',', Location.City, ',' , Location.State, ' ' , Location.Zip) = '" . $lc['location'] . "'";
			$res = mssql_query($sql);
			$db = @mssql_fetch_array($res, MSSQL_ASSOC);
			//$db['latitude'] = location_int_gps($db['latitude']);
			//$db['longitude'] = location_int_gps($db['longitude']);
			$db = location_api($db['LocName'], $db);

	return $db;
}

function mapquest_address($map)
{
	//$map['results']['locations']
	/*
	       "street": "12714 Ashley Melisse Blvd",
          "adminArea6": "",
          "adminArea6Type": "Neighborhood",
          "adminArea5": "Jacksonville",
          "adminArea5Type": "City",
          "adminArea4": "Duval",
          "adminArea4Type": "County",
          "adminArea3": "FL",
          "adminArea3Type": "State",
          "adminArea1": "US",
          "adminArea1Type": "Country",
          "postalCode": "32225",
	*/
	$loc = $map['results']['0']['locations']['0'];

	$ar = array('street', 'adminArea5', 'adminArea3', 'postalCode');
	$address = $loc['street'] . ',' . $loc['adminArea5'] . ',' . $loc['adminArea3'] . ' ' . $loc['postalCode'];
return $address;
}

function mapquest_map($map)
{

return 	$map['results']['0']['locations']['0']['mapUrl'];
}

function location_details($file)
{
	if ($lc = location_parse_file($file))
	{
		$db = location_lookup($lc);

		$map = mapquest_reverse_geocode($db['latitude'],$db['longitude']);
	
		$map2 = mapquest_reverse_geocode($lc['latitude'], $lc['longitude']);
		$ld['Date'] = date("Y-m-d H:i:s", $lc['time']);
		$ld['EmpNo'] = $lc['EmpNo'];
		$ld['Desc'] = $lc['Desc'];
		$ld['location'] = $lc['location'];
		$ld['location_gps'] = mapquest_address($map);
		$ld['location_map'] = mapquest_map($map);
		$ld['location_latitude'] = $db['latitude'];
		$ld['location_longitude'] = $db['longitude'];

		$ld['override_gps'] = mapquest_address($map2);
		$ld['override_map'] = mapquest_map($map2); 
		$ld['override_latitude'] = $lc['latitude'];
		$ld['override_longitude'] = $lc['longitude'];
		$ld['file'] = $file;

		return $ld;
	}
return false;
}

function location_files()
{
static $files;
	
	if (!isset($files))
	{
		$dir = '/var/www/html/primelogic/upload/';
		$files = scandir($dir);
	}
return $files;
}

function location_query()
{

	$files = location_files();
	$js['files'] = $files; 
	foreach ($files as $id=>$file)
	{
		if ($lc = location_parse_file($file))
		{
			$db = location_lookup($lc);

			if (isset($lc['location']) && isset($db['location']) && $db['location'] == $lc['location'])
			{
				$js[$lc['location']][$id] = $lc;
				$js['location'][$id] = $db['location'];
				$js['locationapi'][$db['location']] = $db;
			}
		}
	}
return $js;
}

function viewer_query()
{

	$files = location_files();
	$js['files'] = $files; 
	$ord = array('reference', 'Screen', 'location', 'EmpNo', 'Desc');
	foreach ($files as $id=>$file)
	{
		if ($lc = location_parse_file($file))
		{
			foreach ($ord as $k)
			{
				$js['document'][$id] = $lc;
				$js[$k][$id] = $lc[$k];
			}
		}
	}
return $js;
}



function location_override($location, $db, &$js)
{
static $files;
	
	if (!isset($files))
	{
		$dir = '/var/www/html/primelogic/upload/';
		$files = scandir($dir);
	}
	$js['files'] = $files;
	foreach ($files as $id=>$file)
	{
		if ($lc = location_parse_file($file))
		{
			if (isset($lc['location']) && $location == $lc['location'])
			{
				$js[$lc['location']][$id] = $lc;
				$js['location'][$id] = $db['location'];
				$js['locationapi'][$db['location']] = $db;
			}
		}
	}
return $js;
}

function location_api($location, $db = '')
{
if ( $location != '')
{
	if ($db == '')
	{
		$db['LocName'] = str_replace("'", "''", $location);

		$sql = "SELECT Location. LocName, Location.Add1, Location.City, Location.State, Location.Zip, LocationApi.latitude, LocationApi.longitude FROM Location LEFT JOIN LocationApi ON Location.LocName = LocationApi.LocName
		WHERE 
		Location.LocName = '" . $db['LocName'] . "' and Location.Add1 != '' and Location.City != '' and Location.State != '' and Location.Zip != ''";
		//echo $sql;
		$res = mssql_query($sql);
		$error[] = mssql_get_last_message();
		$loc = mssql_fetch_array($res, MSSQL_ASSOC);

		$loca = $loc['Add1'] . ',' .  $loc['City'] . ',' . $loc['State'] . ' ' . $loc['Zip'];
	}
	else
	{
		$loc = $db;
		$loca = $loc['Add1'] . ',' .  $loc['City'] . ',' . $loc['State'] . ' ' . $loc['Zip'];
	}
	if (($loc['latitude'] == '0' || $loc['latitude'] == '' || !isset($loc['latitide'])) && $loca != '')
	{
		unset($loc['latitude']);
		unset($loc['longitude']);
		$resp = mapquest_api($loca);
		
		
		if ($match = mapquest_match($resp, $loc))
		{
			//$resp = $match;
			$db = array_merge($match, $loc);
			//$error = location_api_insert($db, $loca);
			//$db['error'] = $error;
		}
		else
		{

			$resp['nomatch']['location'] = $location;
			$resp['nomatch']['street'] = $resp['results']['0']['locations']['0']['street'];
			$resp['nomatch']['Add1'] = $loc['Add1'];
			$db = $resp;
		}
	}
	else
	{
		$db = $loc;
		$db[error] = $error;
	}

return $db;
}
}

