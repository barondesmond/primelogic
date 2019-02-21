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

function timesheet_add($timesheet, $dbs, $dev='')
{

	foreach ($dbs as $db)
	{
		$k = '';
		$v = '';
	
		foreach ($timesheet as $key);
		{
			if (isset($db[$key]))
			{
				$k .= ",$key";
				$v .= ",'" . $db[$key] . "'";				
			}
		}
		$db['ID'] = md5(time() . microtime() . $v);
	}
$sql = "INSERT INTO PRTimeEntry$dev ('ID' $k) VALUES ('" . $db['ID'] . " $v)";
$res = @mssql_query($sql);

return mssql_get_last_message();
}

function timesheet_prhours($req, $PRHours, $dev = '')
{
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
	$sql = "INSERT INTO PRHours$dev ($k) VALUES ($v)";
	$res = @mssql_query($sql);
	$error[] = mssql_get_last_message();
	}
return $error;
}
				
	$error1 = timesheet_prhours($_REQUEST, $_REQUEST['PRHours'], $_REQUEST['Dev']);
	$_REQUEST['error'] = $error1;		

	header('Content-Type: application/json');
	echo json_encode($_REQUEST);
exit;
?>