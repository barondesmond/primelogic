<?php

function timeclock_state($db, $TimeOn, $TimeOff, $dev='')
{

	if ($db['StartTime'] = convert_date_time($db['DispDate'], $TimeOn))
	{
			$db['violation'] = 'Sync Error';
			$db['checkinStatus'] = 'Start';
			$db['installationId'] = $db['installationID'];
	
			$resp = timeclock_db($db, $db['StartTime']);

			if ($db['Status'] == 'Complete' && $db['StopTime'] = convert_date_time($db['DateOff'], $TimeOff))
			{
				$db['checkinStatus'] = 'Stop';
	
				$resp = timeclock_db($db, $db['StopTime']);

			}
		
	}
return $resp;
}


function convert_date_time($date, $time)
{
	$expday = explode(' ', $date);
	//print_r($expday);
	$fields = 0;
	$i=0;
	$day = '';
	while ($i < 3 && isset($expday[$fields]) && $fields < count($expday))
	{
		if (trim($expday[$fields]) != '')
		{
			$day .= $expday[$fields] . ' ';
			$i++;
		}
		$fields++;
	}
	$day = $day . ' ' . $time;
	//echo $day;
	$StartTime = strtotime($day);
	//echo "Start Date " . date("Y:m:d H:i:s", $StartTime);
	if ($StartTime > 0)
	{
		return $StartTime;
	}
return false;
}


function timeclock_add($db, $dev)
{
	$sql = "SELECT * FROM UserAppAuth WHERE EmpNo = '" . $db['EmpNo'] . "'";
	$res = mssql_query($sql);
	$uaa = mssql_fetch_array($res, MSSQL_ASSOC);
	if (!isset($uaa))
	{
		$error[] = 'Missing UserAppAuth ' . $db['EmpNo'];
		return $error;
	}
	if (validate_timeclock_update('0', $db['EmpNo'], $db['StartDate'], $db['StopDate']))
	{
		$db = array_merge($_REQUEST, $uaa);

		$db['StartTime'] = strtotime($db['StartDate']);
		$db['StopTime'] = strtotime($db['StopDate']);
		$db['EmpActive'] = '0';
		if ($db['Screen'] == 'Job' && $db['JD'] != '')
		{
			$exp = explode(':', $db['JD']);
			
			$db['Name'] = $exp[0];
			$db['JobID'] = $exp[1];
		}
		elseif ($db['Screen'] == 'Job')
		{
			$error[] = 'Missing Job';
			return $error;
		}
		if ($db['Screen'] == 'Dispatch' && $db['JD'] != '')
		{
			$exp = explode(':', $db['JD']);

			$db['Dispatch'] = $exp[0];
			$db['Counter'] = $exp[1];
			$resp = dispatch_add($db, $dev);
			if (!isset($resp['Dispatch']))
			{
				$error[] = 'Dispatch Error';
				return $error;
			}
		}
		elseif ($db['Screen'] == 'Dispatch')
		{
			$error[] = 'Mising Dispatch';
			return $error;
		}

		$array = array('EmpNo', 'installationId', 'Name', 'Dispatch', 'event', 'StartTime', 'StopTime', 'EmpActive',  'Screen', 'Counter', 'JobID');
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
	else
	{
		$error[] = 'Invalid parameters for timeclock_add';
		$error[] = var_export($db);
	}
return $error;

}


//tc is TimeClockID array 
//tk is TimeClockID key
//tv is TimeClock Start/Stop array
function validate_timeclock_update($TimeClockID, $EmpNo, $StartDate, $StopDate)
{
	$r1 = time() - 86400*30;
	$r2 = time() + 86400*30;

	$t1 = strtotime($StartDate);
	$t2 = strtotime($StopDate);

	if ($t1 > $t2 || $t1 < $r1 || $t2 > $r2 || $t1 > $r2 || $t2 < $r1 || (($t2-$t1) > 86400))
	{
		return false;
	}
	if (!isset($EmpNo))
	{
		return false;
	}
	$sql = "SELECT * FROM TimeClockApp WHERE ((StartTime < '$t1' and StopTime > '$t1') or (StartTime < '$t2' and StopTime > '$t2'))  and EmpNo = '$EmpNo' and TimeClockID != '$TimeClockID'";
	$res = mssql_query($sql);
	$db = mssql_fetch_array($res, MSSQL_ASSOC);
	if (isset($db['EmpNo']))
	{
		return false;
	}

return true;

}

function timeclock_dispatch_update($tc, $dev='')
{
	//Traveling DispTime TimeOn
	//Working TimeOn TimeOff
	$sql = "SELECT * FROM DispTech$dev as DispTech WHERE DispTech.Dispatch = '" .  $tc['Dispatch'] . "' and Counter = '" . $tc['Counter'] . "' and ServiceMan = '" . $tc['EmpNo'] . "'";
	$res = mssql_query($sql);
	if ($dis = mssql_fetch_assoc($res))
	{
		$StartHour = date("H:i:s", $tc['StartTime']);
		$StopHour = date("H:i:s", $tc['StopTime']);

		if ($tc['event'] == 'Traveling')
		{
			
			$sql = "UPDATE DispTech$dev SET DispTime = '$StartHour', TimeOn = '$StopHour' WHERE DispTech.Dispatch = '" . $dis['Dispatch'] .  "' and Counter = '" . $dis['Counter'] . "' and ServiceMan = '" . $dis['ServiceMan'] . "'";
			$res = mssql_query($sql);
			$mes = mssql_get_last_message();
	
				$error[] = $mes;
				$error[] = $sql;

			return $error;
		}
		if ($tc['event'] == 'Working')
		{
			
			$sql = "UPDATE DispTech$dev SET TimeOn = '$StartHour', TimeOff = '$StopHour' WHERE DispTech.Dispatch = '" . $dis['Dispatch'] . "' and Counter = '" . $dis['Counter'] . "' and ServiceMan = '" . $dis['ServiceMan'] . "'";
			$res = mssql_query($sql);
			$mes = mssql_get_last_message();
			if ($mes != '')
			{
				$error[] = $mes;
				$error[] = $sql;
			}
			return $error;
		}
		$error[] = 'No Valid Event at DispTech' . $dev . ' ' . $tc['event'] . ' ' . $tc['Dispatch'] . ' ' . $tc['Counter'] . ' ' . $tc['EmpNo'] . ' ' . $tc['TimeClockID'];
		return $error;
	}
	else
	{
		$error[] = 'No Valid DispTech' . $dev . ' ' . $tc['Dispatch'] . ' ' . $tc['Counter'] . ' ' . $tc['EmpNo'] . ' ' . $tc['TimeClockID'];
		$error[] = $sql;
		return $error;
	}
//not valid or possible
return false;
}

function timeclock_update($tc, $dev='')
{


	foreach ($tc as $tk => $tv)
	{
		$sql = "SELECT * FROM TimeClockApp WHERE TimeClockID = '$tk'";
		$res = mssql_query($sql);
		$tca = mssql_fetch_array($res, MSSQL_ASSOC);

		if (isset($tk) && isset($tv['StartDate']) && isset($tv['StopDate']) && validate_timeclock_update($tk, $tca['EmpNo'], $tv['StartDate'], $tv['StopDate']))
		{

			$sql = "UPDATE TimeClockApp SET StartTime = '" . strtotime($tv['StartDate']) . "', StopTime = '" . strtotime($tv['StopDate']) . "' WHERE TimeClockID = '" . $tk . "'";
			$res = mssql_query($sql);
			$mes = mssql_get_last_message();

				$error[] = $mes;
				$error[] = $sql;
			if ($tca['Screen'] == 'Dispatch')
			{
				$error2 = timeclock_dispatch_update($tca, $dev);
				if (isset($error2))
				{
					$error = array_merge($error, $error2);
				}
			}
		}
		else
		{
			$error[] = 'Invalid Parameters timeclock_update TimeClockID ' . $tk .'EmpNo ' .  $tca['EmpNo'] . ' StartDate ' . $tv['StartDate'] . ' StopDate ' . $tv['StopDate'];
		}
	}
if (!isset($error))
	{
	$error[] = 'error timclock update';
	$error[] = $tc;
	}
return $error;

}

function dispatch_add($db ,$dev='')
{

	

	$array  = array('Dispatch', 'ServiceMan', 'Counter', 'Status', 'Dispatcher', 'PromDate', 'TPromDate', 'TPromTime', 'Zone', 'Priority', 'Terms', 'TechTime', 'SortDate', 'SortTime', 'Mobile', 'POReceived', 'TimeEntryCreated', 'HoursPayed');
	$q = '';
	$blank = array('DispTime', 'TimeOn', 'TimeOff', 'Complete');

	for ($i=0; $i < count($array); $i++)
	{
		$q .= $array[$i] . ',';
	}
	$q = substr($q, 0, strlen($q) - 1);

	$sel = "SELECT $q FROM DispTech$dev WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan = '" . $db['EmpNo'] . "' and Status = 'Pending' and Counter = '" . $db['Counter'] . "' ";
	$res_sel = mssql_query($sel);
	if (!mssql_num_rows($res_sel))
	{
		$error[]  = "Invalid DispTech$dev state";
		return $error;
	}
	$sdb = mssql_fetch_array($res_sel, MSSQL_ASSOC);
	if (!$sdb)
	{
		$error[] = 'Missing DispTech' . $dev;
		return $error;
	}




	$where = " WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan='" . $db['EmpNo'] . "' and Status = 'Pending' and Counter = '" . $sdb['Counter'] . "' ";

		$up = "UPDATE DispTech$dev SET Status = 'Complete' ";
		//Dec 5 2018 12:00:00:000AM
		$dispdate =  date("M d Y ", $db['StartTime']) . '12:00:00:000AM';
		$TimeOn = date("H:i:s", $db['StartTime']);
		$TimeOff = date("H:i:s", $db['StopTime']);
		if ($db['event'] == 'Traveling' && $sdb['Status'] == 'Pending')
		{
			$dd = ", DispDate = '" . $dispdate . "' , DispTime = '"  . $TimeOn . "', TimeOn = '" . $TimeOff . "', TimeOff = '" . $TimeOff . "', DateOff = '" . $dispdate . "' ";
		}
		elseif ($db['event'] == 'Working' && $sdb['Status'] == 'Pending')
		{
			$dd = ", DispDate = '" . $dispdate . "', DispTime = '" . $TimeOn . "', TimeOn = '" . $TimeOn . "', TimeOff = '" . $TimeOff . "', DateOff = '" . $dispdate . "' ";
		}
		else
		{
			$error[]  = 'missing event';
			return $error;
		}

	if ($up != '' && $dd != '' && $where != '')
	{
		$sql = $up . $dd . $where;
		$res = @mssql_query($sql);
		$error[] = mssql_get_last_message();
		$error[] = $sql;
	}

		for ($i=0; $i< count($array); $i++)
		{
			if ($array[$i] == 'Counter')
			{
				$sdb[$array[$i]] = (int) $sdb[$array[$i]];
				$sdb[$array[$i]]++;
				$sdb[$array[$i]] = str_pad($sdb[$array[$i]], 3, "0", STR_PAD_LEFT);
			}
			if ($array[$i] == 'Status')
			{
				$sdb['Status'] = 'Pending';
			}
			$v .= "'" . $sdb[$array[$i]] . "',";
		}
		$v = substr($v, 0, strlen($v) - 1);		

		for ($i=0;$i<count($blank); $i++)
		{
			$q .= " ," . $blank[$i] . " ";
			$v .= " ,'' ";
		}

		$ins = "INSERT INTO DispTech$dev ($q) VALUES($v)";
		$res2 = mssql_query($ins);
		$error[] = mssql_get_last_message();
		$error[] = $ins;

	$resp = array_merge($db, $error);
	$resp = array_merge($resp, $sdb);
return $resp;
}


function dispatch_db($db, $dev='', $time = '')
{

	if ($time == '')
	{
		$time = time();
	}
	if ($db['checkinStatus'] != 'Start' && $db['checkinStatus'] != 'Stop')
	{
		return false;
	}
	$up = '';
	$dd = '';
	$where = '';
	//UPDATE DispTechDev SET DispDate = getdate()  WHERE Dispatch = '6555775' and Counter = '000' and Status = 'Traveling' and ServiceMan = '0195'
	//..DateOff
    //UPDATE DispTechDev SET DispTime = '" . date("H:i:s", time()) . "' WHERE Dispatch = '6555775' and Counter = '000' and Status = 'Traveling' and ServiceMan = '0195'
    //..TimeOn, TimeOff
	$array  = array('Dispatch', 'ServiceMan', 'Counter', 'Status', 'Dispatcher', 'PromDate', 'TPromDate', 'TPromTime', 'Zone', 'Priority', 'Terms', 'TechTime', 'SortDate', 'SortTime', 'Mobile', 'POReceived', 'TimeEntryCreated', 'HoursPayed');
	$q = '';
	$blank = array('DispTime', 'TimeOn', 'TimeOff', 'Complete');

	for ($i=0; $i < count($array); $i++)
	{
		$q .= $array[$i] . ',';
	}
	$q = substr($q, 0, strlen($q) - 1);

	$sel = "SELECT $q FROM DispTech$dev WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan = '" . $db['EmpNo'] . "' and Status IN ('Traveling', 'Working', 'Pending')";
	$res_sel = mssql_query($sel);
	if (!mssql_num_rows($res_sel))
	{
		$db['error'] = "Invalid DispTech$dev state";
		return $db;
	}
	$sdb = mssql_fetch_array($res_sel, MSSQL_ASSOC);
	if (!$sdb)
	{
		$db['error'][] = 'Missing DispTech' . $dev;
		$db['error'][] = $sel;
		$db['error'][] = $sdb;
		return $db;
	}
	$where = " WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan='" . $db['EmpNo'] . "' and Status IN ('Traveling', 'Working', 'Pending') and Counter = '" . $sdb['Counter'] . "' ";

	if ($db['checkinStatus'] == 'Start')
	{
		$up = "UPDATE DispTech$dev SET Status = '" . $db['event'] . "' ";
		if ($db['event'] == 'Traveling' && $sdb['Status'] == 'Pending')
		{
			$dd = ", DispDate = getdate(), DispTime = '"  . date("H:i:s", $time) . "' ";
		}
		elseif ($db['event'] == 'Working' && $sdb['Status'] == 'Traveling')
		{
			$dd = ", TimeOn = '" . date("H:i:s", time()) . "' ";
			$tcq = TimeClockQuery($db, $dev);
			$tsql = "UPDATE TimeClockApp SET event = '" . $db['event'] . "' WHERE TimeClockID = '" . $tcq['TimeClockID'] . "'";
			@mssql_query($tsql);
			$error[] = mssql_get_last_message();
			$error[] = $tsql;
			if ($up != '' && $dd != '' && $where != '')
			{
				$sql = $up . $dd . $where;
				$res = @mssql_query($sql);
				$error[] = mssql_get_last_message();
				$error[] = $sql;
				$error['error'] = 'Changed Event Updated TimeClockApp';
				return $error;
			}
			else
			{
				$error['error'] = "missing $up $dd $where ";
				return $error;
			}
			
		}
		elseif ($db['event'] == 'Working' && $sdb['Status'] == 'Pending')
		{
			$dd = ", DispDate = getdate(), DispTime = '" . date("H:i:s", $time) . "', TimeOn = '" . date("H:i:s", $time) . "' ";
		}
		else
		{
			$db['error'][] = 'missing event';
			$db['error'][] = $sdb;
			$db['error'][] = $db;
			return $db;
		}
	
	}
	if ($db['checkinStatus'] == 'Stop' )
	{
		$dd = ", DateOff = DispDate , TimeOff = '" . date("H:i:s", $time) . "' ";
		$up = "UPDATE DispTech$dev SET Status = 'Complete' ";
		$dd .= " , Complete = 'Y' ";

		if ($sdb['Status'] == 'Traveling')
		{
			$dd .= " , TimeOn = '" . date("H:i:s", $time) . "' ";
		}
	}
	if ($up != '' && $dd != '' && $where != '')
	{
		$sql = $up . $dd . $where;
		$res = @mssql_query($sql);
		$error[] = mssql_get_last_message();
		$error[] = $sql;
	}
	else
	{
		$db['error'] = "missing $up $dd $where ";
		return $db;
	}
	if ($db['checkinStatus'] == 'Stop' && $db['Complete'] != 'Y')
	{
		for ($i=0; $i< count($array); $i++)
		{
			if ($array[$i] == 'Counter')
			{
				$sdb[$array[$i]] = (int) $sdb[$array[$i]];
				$sdb[$array[$i]]++;
				$sdb[$array[$i]] = str_pad($sdb[$array[$i]], 3, "0", STR_PAD_LEFT);
			}
			if ($array[$i] == 'Status')
			{
				$sdb['Status'] = 'Pending';
			}
			$v .= "'" . $sdb[$array[$i]] . "',";
		}
		$v = substr($v, 0, strlen($v) - 1);		

		for ($i=0;$i<count($blank); $i++)
		{
			$q .= " ," . $blank[$i] . " ";
			$v .= " ,'' ";
		}

		$ins = "INSERT INTO DispTech$dev ($q) VALUES($v)";
		$res2 = mssql_query($ins);
		$error[] = mssql_get_last_message();
		$error[] = $ins;
	}
	elseif ($db['checkinStatus'] == 'Stop' && $db['Complete'] == 'Y')
	{
		//dont insert new disptech
		$sql = "UPDATE Dispatch$dev SET Complete = getdate() WHERE Dispatch = '" . $db['Dispatch'] . "'";
		$res = mssql_query($sql);
		$error[] = mssql_get_last_message();
		$error[] = $sql;
	}

return $error;
}

function timeclock_db($db, $time = '')
{

	if ($time == '')
	{
		$time = time();
	}

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
	$array = array('EmpNo', 'installationId', 'Name', 'Dispatch', 'latitude', 'longitude', 'event', 'StartTime', 'EmpActive', 'violation', 'image', 'Screen', 'Counter', 'JobID');
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