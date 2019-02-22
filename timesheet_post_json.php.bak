<?php
include("_db_config.php");
include("_user_app_auth.php");

$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	echo json_encode($auth);
	exit;
}

function timesheet_add($id, $row, $Dates, $dev='')
{

	$timesheet = array('TimeSheetID'=>'', 'EmpNo' => '', 'Date' => '', 'Hours' => '0', 'PayItemID'=>'',  'Dispatch'=>'', 'JobID'=>'', 'JobClassID'=>'', 'DeptID'=>'', 'ItemID'=>'', 'Desc'=>'', 'Billable'=>'0', 'Invoiced' =>'0', 'TimesheetOrder'=>'0', 'Processed'=>'0', 'LedgerTransID'=>'', 'WorkCompID'=>'', 'RateOverride'=>'0', 'LedgerEntryID'=>'');

		$k = '';
		$v = '';
	
	foreach ($timesheet as $key=> $def)
	{
		if (isset($time[$key]))
		{
			$k .= ",$key";
			$v .= ",'" . $db[$key] . "'";				
		}
		else
		{
			$k .= ",$key";
			$v .= ",'$def'";
		}
	}
	$db['ID'] = md5(time() . microtime() . $v);
	$sql = "INSERT INTO PRTimeEntry$dev ('ID' $k) VALUES ('" . $db['ID'] . " $v)";
	//$res = @mssql_query($sql);
	$mes = mssql_get_last_message();
	if ($mes != '')
	{
		$error[] = $mes;
		$error[] = $sql;
	}
	

return $error;

}

function timesheet_prhours($req, $PRHours)
{
	$error = '';
	$keys = array('EmpNo', 'StartTime', 'StopTime', 'PayItemID', 'Hours');
	foreach ($PRHours as $PayItemID=>$Hours)
	{
			$k = '';
			$v = '';
			$req['Hours'] = $Hours;
			$req['PayItemID'] = $PayItemID;
			foreach ($keys as $key)
			{
				if (isset($req[$key]))
				{
					$k .= "$key,";
					$v .= "'$req[$key]',";
				}
	
			}
		$k = substr($k, 0, strlen($k)-1);
		$v = substr($v, 0, strlen($v)-1);
	$sql = "INSERT INTO PRHours ($k) VALUES ($v)";
	//$res = @mssql_query($sql);
	$mes = mssql_get_last_message();
		if ($mes != '')
		{
			$error[] = $mes;
			$error[] = $sql;
		}
	}
return $error;
}
	$_REQUEST['error'] = array();
	$error1 = timesheet_prhours($_REQUEST, $_REQUEST['PRHours'], $_REQUEST['Dev']);
	if (is_array($error1))
	{
		$_REQUEST['error'] = array_merge($_REQUEST['error'], $error1);
	}	
	print_r($_REQUEST);
	
	if (isset($_REQUEST['ids']) && isset($_REQUEST['Dates']))
	{
		foreach($_REQUEST['ids'] as $i=>$id)
		{
			foreach ($_REQUEST['Dates'] as $j=>$Date)
			{
				if (isset($_REQUEST[$id][urlencode($Date)]))
				{
					print_r($_REQUEST[$id][urlencode($Date)];
					$_REQUEST[$id]['Date'] = $Date;
					$_REQUEST[$id]['Hours'] = $_REQUEST[$id][urlencode($Date)];
					$error2 = timesheet_add($id, $_REQUEST[$id]);
					if (is_array($error2))
					{
						$_REQUEST['error'] = array_merge($_REQUEST['error'], $error2);
					}		
				}
			}
		}
	}
	exit;
	header('Content-Type: application/json');
	echo json_encode($_REQUEST);
exit;
?>