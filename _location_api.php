<?php




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
	//print_r($loc);
	//print_r($db);
	if ($loc['street'] == $db['Add1'])
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

function location_api($location)
{
if ( $location != '')
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
	if ($loc['latitude'] == '' && $loc['longitude'] == '' && $loc['LocName'] != '')
	{
		$resp = mapquest_api($loca);
		if ($match = mapquest_match($resp, $loc))
		{
			//$resp = $match;
			$db = array_merge($loc, $match);
			$error = location_api_insert($db, $loca);
			$db['error'] = $error;
		}
		else
		{

			$resp['nomatch'] = $location .' ' . $resp['results']['0']['locations']['0']['street'] . ' ' .  $db['Add1'];
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

