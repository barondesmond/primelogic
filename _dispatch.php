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
return $db;
}

function dispatch_init($dbs, $db)
{


	$key = array('Dispatch', 'DispDate', 'LocName', 'LocNo', 'Priority', 'Contact', 'Phone', 'Contact2', 'Phone2', 'Add1', 'City', 'State', 'Zip', 'Notes', 'signature' );

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
return $dbs;
}

function dispatch_header($dbs = '')
{

	$html =  '<html><header></header><table border=1><tr><td><table><tr><td><b>' . $dbs['DispDate'] . '</b></td><td><b>Prime Logic, Inc</b></td><td></td></tr>';
	$html .= '<tr><td></td><td><b>Dispatch Ticket</b></td><td></td></tr></table>';
	$html .= '<table border=0><tr><td>Dispatch</td><td>Cust #</td><td>Loc #</td><td>Priority</td></tr>';
	$html .= '<tr><td>' . $dbs['Dispatch'] . '</td><td>' . $dbs['CustNo'] . '</td><td>' . $dbs['LocNo'] . '</td><td>' . $dbs['Priority'] . '</td></tr></table>';
	$html .= '<table><tr><td><b>Customer/Location Address</b></td></tr>';
	$html .= '<tr><td>' . $dbs['LocName'] . '</td><td><b>' . $dbs['Contact'] . '</b></td><td>' . $dbs['Phone'] . '</td></tr>';
	$html .= '<tr><td>' . $dbs['Add1'] . '</td><td>' . $dbs['Contact2'] . '</td><td>' . $dbs['Phone2'] . '</td></tr>';
	$html .= '<tr><td>' . $dbs['City'] . ' ' . $dbs['State'] . ' ' . $dbs['Zip'] . '</td>/tr></table>';

	$html .= '</table>';
	return $html;
}

function dispatch_scope($dbs = '')
{
	$html = '<hr size="6" width="80%" align="left" color="black">';
	$html .= '<table border=0><tr><td>Scope of Work</td></tr>';
	$html .= '<tr><td>' . $dbs['Notes'] . '</td></tr></table>';

return $html;
}

function dispatch_status($dbs = '')
{
	$html = '<hr size="6" width="80%" align="left" color="black">';

	$html .= '<table><tr><td>';
	$html .= '<table border=1><tr><td>Status</td></tr></table>';
	$html .= '</td><td>';
	$html .= '<img src="' . $dbs['signature'] . '">';
	$html .= '</td></tr></table>';

return $html;
}

function dispatch_footer($dbs = '')
{
	$html = '</body></html>';
	return $html;
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
WHERE DispTech.Complete != 'Y' and (DispTech.Status = 'Traveling' or DispTech.Status = 'Working' or DispTech.Status = 'Pending') and Location.Add1!= '' and Location.City!='' and Location.State!='' and Location.Zip!=''  $sel
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