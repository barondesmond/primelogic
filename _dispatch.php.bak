<?php

function dispatch_counter($Dispatch = '', $dev = '')
{
	$sql = "SELECT * FROM DispTech$dev WHERE Dispatch = '$Dispatch' ORDER BY Counter DESC";
	$res = mssql_query($sql);
	if ($db = mssql_fetch_assoc($res))
	{
	
		return $db['Counter'];
	}
}

function dispatch_dispatcher($Dispatch = '', $dev = '')
{
	$sql = "SELECT * FROM Dispatch$dev WHERE Dispatch = 'Dispatch'";
	$res = mssql_query($sql);
	$db = mssql_fetch_assoc($res);
	return $db['Dispatcher'];
}

function disptech_create($sdb, $dev = '')
{
	if (!$sdb['Dispatch'] || !$sdb['ServiceMan'])
	{
		return false;
	}
	if (!$sdb['Counter'])
	{
		$sdb['Counter'] = dispatch_counter($sdb['Dispatch'], $dev);
	}
	if (!$sdb['Dispatcher'])
	{
		$sdb['Dispatcher'] = dispatch_dispatcher($sdb['Dispatch'], $dev);
	}
	$sql = "SELECT * FROM Dispatch$dev WHERE Dispatch = '" . $sdb['Dispatch'] . "'";
	$res = mssql_query($sql);
	$dis = mssql_fetch_assoc($res);
	if (!isset($dis))
	{
		$error['error'] = 'No Dispatch ' . $sdb['Dispatch'];
		return $error;
	}
	$sql2 = "SELECT * FROM DispTech$dev WHERE Dispatch = '" . $sdb['Dispatch'] . "' and ServiceMan = '" . $sdb['ServiceMan'] . "' and Status = 'Pending'";
	$res2 = mssql_query($sql);
	$dp = mssql_fetch_assoc($res2);
	if (isset($dp))
	{
		$error['error'] = 'Disptech Pending exists ' . $sdb['Dispatch'] ;
		return $error;
	}
		$array  = array('Dispatch', 'ServiceMan', 'Counter', 'Status', 'Dispatcher', 'PromDate', 'TPromDate', 'TPromTime', 'Zone', 'Priority', 'Terms', 'TechTime', 'SortDate', 'SortTime', 'Mobile', 'POReceived', 'TimeEntryCreated', 'HoursPayed');
		$blank = array('DispTime', 'TimeOn', 'TimeOff', 'Complete');
		$q = '';
		for ($i=0; $i < count($array); $i++)
		{
			$q .= $array[$i] . ',';
		}
		$q = substr($q, 0, strlen($q) - 1);

		$v = '';
		for ($i=0; $i< count($array); $i++)
		{
			if ($array[$i] == 'Counter')
			{
				$sdb[$array[$i]] = (int) $sdb[$array[$i]];
				$sdb[$array[$i]] = dispatch_counter($sdb['Dispatch'], $dev);
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
		$sql = "UPDATE Dispatch$dev SET Complete = NULL WHERE Dispatch = '" . $sdb['Dispatch'] . "'";
		$res3 = mssql_query($sql);
		$error[] = mssql_get_last_message();
		$error[] = $sql;
return $error;
}

function dispatch_db($db, $dev='')
{

	if ($db['checkinStatus'] != 'Start' && $db['checkinStatus'] != 'Stop')
	{
		$error['error'] = 'invalid checkinStatus';
		return $error;
	}
	if ($db['Dispatch'] == 'null' || !$db['Dispatch'] || $db['Dispatch'] == '')
	{
		$error['error'] = 'invalid Dispatch';
		return $error;		
	}
	if ($db['Counter'] == 'null' || !$db['Counter'] || $db['Counter'] == '')
	{
		$error['error'] = 'invalid Dispatch counter';
		return $error;	
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
	$sqll = "SELECT * FROM DispLock WHERE Dispatch = '" . $db['Dispatch'] . "'";
	$resl = mssql_query($sqll);
	if ($lock = mssql_fetch_array($resl, MSSQL_ASSOC))
	{
		$error['error'] = 'Dispatch ' . $db['Dispatch'] . ' is open by ' .$lock['User'];
		return $error;
	}

	if ($db['checkinStatus'] == 'Start')
	{
		$sel = "SELECT $q FROM DispTech$dev WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan = '" . $db['EmpNo'] . "' and Status = 'Pending' and Counter = '" . $db['Counter'] . "'";
		$res_sel = mssql_query($sel);
		if (!mssql_num_rows($res_sel))
		{
			$db['error'] = "Invalid DispTech$dev state " . json_encode($db);
			return $db;
		}
	}
	if ($db['checkinStatus'] == 'Stop')
	{
		$sel = "SELECT $q FROM DispTech$dev WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan = '" . $db['EmpNo'] . "' and Status = '" . $db['event'] . "' and Counter = '" . $db['Counter'] . "'";
		$res_sel = mssql_query($sel);
		if (!mssql_num_rows($res_sel))
		{
			$db['error'] = "Invalid DispTech$dev state " . $db['checkinStatus'];
			return $db;
		}
	}
	$sdb = mssql_fetch_array($res_sel, MSSQL_ASSOC);
	if (!$sdb)
	{
		$db['error'][] = 'Missing DispTech' . $dev;
		$db['error'][] = $sel;
		$db['error'][] = $sdb;
		return $db;
	}
	if ($sdb['checkinStatus'] == 'Stop' && $sdb['DispDate'] == '')
	{
		$db['error'][] = 'Missing DispDate on checkinStatus Stop';
		return $db;
	}
	$where = " WHERE Dispatch = '" . $db['Dispatch'] . "' and ServiceMan='" . $db['EmpNo'] . "' and Status IN ('Traveling', 'Working', 'Pending') and Counter = '" . $db['Counter'] . "' ";

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
			$tsql = "UPDATE Time.dbo.TimeClockApp SET event = '" . $db['event'] . "' WHERE TimeClockID = '" . $tcq['TimeClockID'] . "'";
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
		$rows = mssql_rows_affected(MSSQL_CONNECTION);
		$error[] = 'rows affected ' . $rows;
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
		$v = '';
		for ($i=0; $i< count($array); $i++)
		{
			if ($array[$i] == 'Counter')
			{
				$sdb[$array[$i]] = (int) $sdb[$array[$i]];
				$sdb[$array[$i]] = dispatch_counter($sdb['Dispatch'], $dev);
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
		$rows = mssql_rows_affected(MSSQL_CONNECTION);
		$error[] = 'rows affected ' . $rows;
		$error[] = mssql_get_last_message();
		$error[] = $sql;
		system("/usr/bin/php /var/www/html/primelogic/dispatch.php '$db[Dispatch]' >/dev/null &");


	}

return $error;
}

function dispatch_hours($db, $dev = '')
{
	if ($db['Dispatch']!='')
	{
		$sql = "SELECT Status, DispTime, TimeOn, TimeOff FROM DispTech$dev as DispTech WHERE Dispatch = '" . $db['Dispatch'] . "' ";
		$res = mssql_query($sql);
		$db['Working'] = 0;
		$db['Traveling'] = 0;
		while ($hr = mssql_fetch_array($res, MSSQL_ASSOC))
		{
			if ($hr['Status'] == 'Working' && $hr['TimeOff'] == '')
			{
				$hr['TimeOff'] = date("Y-m-d: H:i:s", time());
			}
			if ($hr['Status'] == 'Traveling' && $hr['TimeOn'] != '')
			{
				$hr['TimeOn'] == date("Y-m-d: H:i:s", time());
			}
			if ($hr['TimeOff'] != '' && $hr['TimeOn'] != '')
			{
				$db['Working'] = $db['Working'] + ((strtotime($hr['TimeOff']) - strtotime($hr['TimeOn'])) / (60*60));
			}
			if ($hr['TimeOn'] != '' && $hr['TimeOff'] != '')
			{
				$db['Traveling'] = $db['Traveling'] + ((strtotime($hr['TimeOn']) - strtotime($hr['DispTime'])) / (60*60));
			}
	
		}
	}
	$ar = array('Working' => '0.000', 'Traveling' => '0.50');
	foreach ($ar as $wt => $comp)
	{

		if ($db[$wt] - floor($db[$wt]) > $comp)
		{
			$db[$wt] = ceil($db[$wt]);
		}
		elseif ($db[$wt] - floor($db[$wt]) == 0)
		{
			//do nothing
		}
		elseif ($db[$wt] - floor($db[$wt]) <= $comp)
		{
			$db[$wt] = floor($db[$wt]) + $comp;
		}
	}
return $db;
}

function dispatch_key()
{

	$key = array('Dispatch', 'DispDate', 'LocName', 'CustNo','LocNo', 'Priority', 'Contact', 'Phone', 'Contact2', 'Phone2', 'Add1', 'City', 'State', 'Zip', 'Notes', 'signature', 
		'PromDate', 'Complete', 'ServiceMan', 'customername' );
return $key;
}


function dispatch_init($dbs, $db)
{

	$key = dispatch_key();

	if ($db != '' && is_array($db))
	{
		foreach($key as $k)
		{
			if ($db[$k] != '')
			{
				$dbs[$k] = $db[$k];
			}
		}
	}
	$db2 = dispatch_hours($dbs);
	$dbs = array_merge($dbs, $db2);
return $dbs;
}

function dispatch_header($dbs = '')
{

	$html =  '<table><tr><td><b>' . $dbs['PromDate'] . '</b></td><td><b>Prime Logic, Inc</b></td><td></td></tr>';
	$html .= '<tr><td></td><td><b>Dispatch Ticket</b></td><td></td></tr></table>';
	return $html;
}
function dispatch_priority($dbs)
{

	$html = '<table><tr><td><b>Dispatch</b></td><td><b>Cust #</b></td><td><b>Loc #</b></td><td><b>Priority</b></td></tr>';
	$html .= '<tr><td>' . $dbs['Dispatch'] . '</td><td>' . $dbs['CustNo'] . '</td><td>' . $dbs['LocNo'] . '</td><td>' . $dbs['Priority'] . '</td></tr></table>';
return $html;
}

function dispatch_customer($dbs)
{
	$html = '<table><tr><td><b>Customer/Location Address</b></td><td></td></tr>';
	$html .= '<tr><td>' . $dbs['LocName'] . '<BR>' . $dbs['Add1'] . '<BR>' .  $dbs['City'] . ' ' . $dbs['State'] . ' ' . $dbs['Zip'] . '</td><td><b>' . $dbs['Contact'] . '</b> ' . $dbs['Phone'] . '<BR><b>' .  $dbs['Contact2'] . '</b>' . $dbs['Phone2'] . '</td></tr>';
	$html .= '</table>';

	return $html;
}




function dispatch_scope($dbs = '')
{
	$scope = '';
	$workday = date('Y-m-d', strtotime($dbs['Complete']));
	$smpos = strpos($dbs['Notes'], $dbs['ServiceMan']);
	$smstart = $smpos - 21;
	if ($smstart > $workday)
	{
		$workpos = strpos($dbs['Notes'], $workday, $smstart);
	}
	else
	{
		$workpos = $workday;
	}
	$scopes = substr($dbs['Notes'], 0, $workpos);

	//$scopes = substr($dbs['Notes'], 0, strpos($dbs['Notes'], $dbs['ServiceMan']) - 21);

	if (strlen($scopes) > 20)
	{
		$dbs['Notes'] = $scopes;
	}
	$exp = explode("\r\n", $dbs['Notes']);
	$lim = '12';
	$char = '900';
	$i = 0;
	foreach ($exp as $line)
	{
	
		if (strpos($line, '**') !== false)
		{
			//skip
		}
		//elseif (strpos($line, $dbs['ServiceMan']) !== false)
		//{
			//skip work
		//}
		elseif ($i < $lim && strlen($scope) < $char)
		{
			$scope .= $line . "\r\n<BR>";
			$i++;
		}
		elseif ($i == $lim || strlen($scope) > $char)
		{
			$scope .= "<BR>\r\nADDITIONAL SCOPE AVAILABLE";

			$char = strlen($scope);
			$i = $lim;
			$i++;
		}
		else
		{
			//don't do anymore
		}
	}
		
	$html = '<hr size="6" width="100%" align="left" color="black">';
	$html .= '<table><tr><td><b>Scope of Work</b></td></tr>';
	$html .= '<tr><td>' . $scope . '</td></tr></table>';

return $html;
}

function dispatch_work($dbs = '')
{

	$workday = date('Y-m-d', strtotime($dbs['Complete']));
	$smpos = strpos($dbs['Notes'], $dbs['ServiceMan']);
	$smstart = $smpos - 21;
	if ($smstart > $workday)
	{
		$workpos = strpos($dbs['Notes'], $workday, $smstart);
	}
	else
	{
		$workpos = strpos($dbs['Notes'], $workday);
	}
	$works = substr($dbs['Notes'], $workpos, strlen($dbs['Notes']));
	error_log($works);
	if (strlen($works) > 10)
	{
		$dbs['Notes'] = $works;
	}
	//echo $dbs['Notes'];
	$work = '';
	$exp = explode("\r\n", $dbs['Notes']);
	error_log(json_encode($exp));
	$lim = '15';
	$char = '1800';

	$i = 0;
	foreach ($exp as $line)
	{
		if (strpos($line, '**') !== false)
		{
			//skip
		}
		elseif ($i < $lim && strlen($work) < $char)
		{
			$work .= $line . "\r\n<BR>";
			$i++;
		}
	
		elseif ($i == $lim || strlen($work) > $char)
		{
			$work .= 'ADDITIONAL NOTES AVAILABLE';
			$i = $lim;
			$char = strlen($work);
			$i++;
		}
		else
		{
			//no more
		}
	}
		
	$html = '<hr size="6" width="100%" align="left" color="black">';
	$html .= '<table><tr><td><b>Description of Work Completed</b></td></tr>';
	$html .= '<tr><td>' . $work . '</td></tr></table>';

return $html;
}

function dispatch_status($dbs = '')
{
	$html = '<hr size="6" width="80%" align="left" color="black">';

	$html .= '<table><tr><td>';
	$html .= '<table><tr><td>Status : Completed</td></tr><tr><td>Traveling: ' . $dbs['Traveling'] . '</td></tr><tr><td>Working: ' . $dbs['Working'] . '</td></tr></table>';
	$html .= '</td><td>';
	$html .= '';
	$html .= '</td></tr></table>';

return $html;
}

function dispatch_footer($dbs = '')
{

	$html = '<hr size="6" width="100%" align="left" color="black">';

	return $html;
}


function dispatch_note_counter_query($dispatch, $counter)
{
	$files = location_notes_files();
	foreach ($files as $id=>$file)
	{
		if ($db = location_notes_parse_file($file))
		{
			if (isset($db['Dispatch']) && $db['Dispatch'] == $dispatch && isset($db['Counter']) && $db['Counter'] == $counter  && strpos($file, 'addNote') > 0 )
			{
				return $file;
			}
		}
	}
}

function dispatch_signature_query($dispatch)
{


	$files = location_files();
	foreach ($files as $id=>$file)
	{
		if ($lc = location_parse_file($file))
		{

			if ($lc['reference'] == $dispatch && $lc['Screen'] == 'DispatchSignaure')
			{
				return $file;
			}
		}
	}
}

function dispatch_picture_query($Dispatch, $EmpNo = '')
{
	if ((!$EmpNo || $EmpNo == '') && isset($_REQUEST['EmpNo'])
	{
		$EmpNo = $_REQUEST['EmpNo'];
	}
	
	$files = location_files();
	foreach ($files as $id=>$file)
	{
		if ($lc = location_parse_file($file))
		{

			if ($lc['reference'] == $Dispatch && $lc['EmpNo'] == $EmpNo)
			{
				return $file;
			}
		}
	}
}


function dispatch_query($ServiceMan = '', $dev='')
{

	if ($ServiceMan != '')
	{
		$sel = " and ServiceMan = '$ServiceMan'";
	}
	if ($dev == 'true')
	{
		$d = 'Dev';
	}
	else
	{
		$d = '';
	}
$js['title'] = 'Dispatch List';
$js['description'] = 'Dispatch Name, Dispatch Location';
$sql = "SELECT TPromDate, DispTech.Priority, Dispatch.Dispatch, DispTech.Counter, Dispatch.Notes as DispatchNotes, Location.LocName as DispatchName, DispTech.Status, Location.latitude, Location.longitude, ServiceMan, CONCAT(Location.Add1, ',', Location.City, ',' , Location.State, ' ' , Location.Zip) as location, Location.Add1, Location.Add2, Location.City, Location.State,Location.Zip, Location.Phone1 FROM DispTech" . $d . " as DispTech
INNER JOIN Dispatch" . $d . " as Dispatch ON DispTech.Dispatch = Dispatch.Dispatch
LEFT JOIN Location ON Dispatch.CustNo = Location.CustNo and Dispatch.LocNo = Location.LocNo
WHERE DispTech.Complete != 'Y' and (DispTech.Status = 'Traveling' or DispTech.Status = 'Working' or DispTech.Status = 'Pending')  $sel
ORDER BY ServiceMan, DispTech.TPromDate DESC, DispTech.Priority ";

$res = mssql_query($sql);
$i=1;
$js['dispatchs'] = null;
while ($db = mssql_fetch_assoc($res))
{
	$db['id'] = $i;
	$db['latitude'] = location_int_gps($db['latitude']);
	$db['longitude'] = location_int_gps($db['longitude']);

	if ($db['latitude'] == '' || $db['latitude'] == '0')
	{
		$loc = location_api($db['DispatchName'], $db);
		$db['latitude'] = $loc['latitude'];
		$db['longitude'] = $loc['longitude'];

	}
	if ($_REQUEST['latitude']!='null' && $_REQUEST['latitude'] != '' &&  $db['latitude'] != '' && $db['latitude'] != 'null')
	{
		$db['distance'] = distance($_REQUEST['latitude'], $_REQUEST['longitude'], $db['latitude'], $db['longitude']);
	}
	$js['dispatchs'][] = $db;

	$i++;

}
$js['sql'] = $sql;
return $js;

}

?>