<?php

function dispatch_db($db, $dev='')
{

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
			$dd = ", DispDate = getdate(), DispTime = '"  . date("H:i:s", time()) . "' ";
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
			$dd = ", DispDate = getdate(), DispTime = '" . date("H:i:s", time()) . "', TimeOn = '" . date("H:i:s", time()) . "' ";
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
		$dd = ", DateOff = getdate(), TimeOff = '" . date("H:i:s", time()) . "' ";
		$up = "UPDATE DispTech$dev SET Status = 'Complete' ";
		$dd .= " , Complete = 'Y' ";

		if ($sdb['Status'] == 'Traveling')
		{
			$dd .= " , TimeOn = '" . date("H:i:s", time()) . "' ";
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