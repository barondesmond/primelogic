<?php


function timeclock_db($db)
{


$time = time();

if ($db['checkinStatus'] == 'Stop')
{
	if ($db['Complete'] == 'Y')
	{
		$complete = ", customer = '" . $db['customer'] . "', customerimage = '" . $db['customerimage'] . "'";
	}
	$sql1 = "UPDATE TimeClockApp SET StopTime = '$time', EmpActive = '0' $complete WHERE EmpNo = '" . $db['EmpNo'] .  "' and installationId = '" . $db['installationId'] . "' and EmpActive = '1'";
	@mssql_query($sql1);
	$error[] = mssql_get_last_message();
	$error[] = $sql1;
}
elseif ($db['checkinStatus'] == 'Start')
{
	$db['StartTime'] = $time;
	$db['EmpActive'] = '1';
	$array = array('EmpNo', 'installationId', 'Name', 'Dispatch', 'latitude', 'longitude', 'event', 'StartTime', 'EmpActive', 'violation', 'image', 'Screen');
	foreach ($array as $key)
	{
		if (isset($db[$key]) && $db[$key] != '')
		{
			$k .= $key . ',';
			$v .= "'" . str_replace("'", "''", $db[$key]) . "',";
		}

	}
		$k = substr($k, 0, strlen($k) - 1);
		$v = substr($v, 0, strlen($v) - 1);
	$sql2 = "INSERT INTO TimeClockApp ($k) VALUES ($v)";
	
    @mssql_query($sql2);
	$error[] = mssql_get_last_message();
	$error[] = $sql2;
}
return $error;

}

?>