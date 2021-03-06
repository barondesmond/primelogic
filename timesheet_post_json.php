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

if ($_REQUEST['EmpNo'] && $_REQUEST['TSEmpNo'])
{
	$_REQUEST['EmpNo'] = $_REQUEST['TSEmpNo'];
}

function timesheet_add($row, $dev='')
{
	$error = '';
	$timesheet = array('TimeSheetID'=>'', 'EmpNo' => '', 'Date' => '', 'Hours' => '0', 'PayItemID'=>'',  'Dispatch'=>'', 'JobID'=>'', 'JobClassID'=>'', 'DeptID'=>'', 'ItemID'=>'', 'Desc'=>'', 'Billable'=>'0', 'Invoiced' =>'0', 'TimesheetOrder'=>'0', 'Processed'=>'0', 'LedgerTransID'=>'', 'WorkCompID'=>'', 'RateOverride'=>'0', 'LedgerEntryID'=>'');

		$k = '';
		$v = '';
	
	foreach ($timesheet as $key=> $def)
	{
		$k .= ',[' . $key . ']';
		if (isset($row[$key]))
		{
			$v .= ",'" . $row[$key] . "'";				
		}
		else
		{
			$v .= ",'$def'";
		}
	}
	$row['ID'] = md5(time() . microtime() . $v);
	$sql = "INSERT INTO PRTimeEntry$dev (ID $k) VALUES ('" . $row['ID'] . "' $v)";
	//echo $sql;
	$res = @mssql_query($sql);
	$mes = mssql_get_last_message();
	if ($mes != '')
	{
		$error[] = $mes;
		$error[] = $sql;
	}
	

return $error;

}

function timesheet_prhours($req, $PRHours, $dev='')
{
	$error = '';
	$keys = array('EmpNo', 'StartTime', 'StopTime', 'PayItemID', 'Hours');
	foreach ($PRHours as $PayItemID=>$Hours)
	{
			$k = '';
			$v = '';
			$req['Hours'] = $Hours;
			$req['PayItemID'] = $PayItemID;
			$req['Date'] = date("M d Y", $req['StartTime']) . ' 12:00:00:00 AM';

			foreach ($keys as $key)
			{
				if (isset($req[$key]))
				{
					$k .= '[' . $key . '],';
					$v .= "'$req[$key]',";
				}
	
			}
		$k = substr($k, 0, strlen($k)-1);
		$v = substr($v, 0, strlen($v)-1);
	if ($Hours > 0)
	{
	$sql = "INSERT INTO Time.dbo.PRHours ($k) VALUES ($v)";
//	echo $sql;
	$res = @mssql_query($sql);
	$mes = mssql_get_last_message();

		if ($mes != '')
		{
			$error[] = $mes;
			$error[] = $sql;
		}
		if ($req['PayItemID'] != 'TCHours')
		{
			$errors3 =timesheet_add($req, $dev);
			if (is_array($errors3))
			{
				$error = array_merge($error, $errors3);
			}
		}
	}
	}
return $error;
}

	$_REQUEST['error'] = array();
	
	if (isset($_REQUEST['ids']) && isset($_REQUEST['Dates']) && isset($_REQUEST['TSEmpNo']))
	{
		$tchours = 0;
		foreach($_REQUEST['ids'] as $i=>$id)
		{
			foreach ($_REQUEST['Dates'] as $j=>$Date)
			{
				if (isset($_REQUEST[$id][urlencode($Date)]))
				{
					//print_r($_REQUEST[$id][urlencode($Date)]);
					$_REQUEST[$id]['Date'] = $Date;
					$_REQUEST[$id]['Hours'] = $_REQUEST[$id][urlencode($Date)];
					$_REQUEST[$id]['EmpNo'] = $_REQUEST['TSEmpNo'];
					$error2 = timesheet_add($_REQUEST[$id], $_REQUEST['Dev']);
					if (!is_array($error2))
					{
						$tchours = $tchours + $_REQUEST[$id]['Hours'];
					}
					if (is_array($error2))
					{
						$_REQUEST['error'] = array_merge($_REQUEST['error'], $error2);
					}		
				}
			}
		}
	}
		$error1 = timesheet_prhours($_REQUEST, $_REQUEST['PRHours'], $_REQUEST['Dev']);
		if (is_array($error1))
		{
			$_REQUEST['error'] = array_merge($_REQUEST['error'], $error1);
		}
	//print_r($_REQUEST);
	
	header('Content-Type: application/json');
	echo json_encode($_REQUEST);
exit;
?>