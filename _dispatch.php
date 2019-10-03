<?php

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
	$workpos = strpos($dbs['Notes'], $workday, $smstart);
	$scopes = substr($dbs['Notes'], 0, $workpos);

	//$scopes = substr($dbs['Notes'], 0, strpos($dbs['Notes'], $dbs['ServiceMan']) - 21);

	if (strlen($scopes) > 20)
	{
		$dbs['Notes'] = $scopes;
	}
	$exp = explode("\r\n", $dbs['Notes']);
	$lim = '12';
	$char = '1800';
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

		$scope = '';
		$workday = date('Y-m-d', strtotime($dbs['Complete']));
	$works = $dbs['Notes'];
	$str1 = strpos($works, $workday);
	$char = '1800';

	$dbs['Notes'] = substr($works, $str1, $char + $str1);
	//echo $dbs['Notes'];

	$exp = explode("\r\n", $dbs['Notes']);
	$lim = '15';

	$i = 0;
	foreach ($exp as $line)
	{
		if (strpos($line, '**') !== false)
		{
			//skip
		}
		elseif ((strpos($line, $dbs['ServiceMan']) !== false || ($i > 0 )) && $i < $lim && strlen($work) < $char)
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