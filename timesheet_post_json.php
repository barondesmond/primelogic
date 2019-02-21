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
				$k .= ",'$key'";
				$v .= ",'" . $db[$key] . "'";				
			}
		}
		$db['ID'] = md5(time() . microtime() . $v);
	}
$sql = "INSERT INTO PRTimeEntry$dev ('ID' $k) VALUES ('" . $db['ID'] . " $v)";
$res = @mssql_query($sql);

return mssql_get_last_message();
}

	header('Content-Type: application/json');
	json_encode($_REQUEST);
exit;
?>