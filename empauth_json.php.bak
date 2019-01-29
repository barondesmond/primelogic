<?php
include("_db_config.php");
include("_auth_email.php");
include("_email.php");

/* create*
CREATE TABLE UserAppAuth (
EmpNo INT PRIMARY KEY NOT NULL,
installationID varchar(255),
authorized INT NOT NULL DEFAULT '0'
)
*/

/*
INSERT INTO UserAppAuth (EmpNo, installationID) VALUES ('0195', 'askdfhahlkjsdhfladf');
*/


if ($_REQUEST['EmpName']   && $_REQUEST['Email'])
{
	$sel = " and EmpName = '" . $_REQUEST['EmpName'] . "' and Email = '" . $_REQUEST['Email'] . "'";
}



$sql = "SELECT Employee.EmpNo as EmpNo, EmpName, Email, phone, UserAppAuth.installationId, UserAppAuth.authorized, UserAppAuth.EmpNo as UAA FROM Employee
LEFT JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo
WHERE Email != '' and Inactive = '0' $sel";
$sa[] = $sql;


$res = mssql_query($sql);
$error[] = mssql_get_last_message();

$i=1;
$db = mssql_fetch_array($res, MSSQL_ASSOC);


if (!isset($db) || $db['EmpNo'] == '' || $_REQUEST['installationId'] == '' || $_REQUEST['Email'] == '' || $_REQUEST['EmpName'] == '')
{
	$db['error'] = 'Not Authorized';
	$db['authorized'] = 0;
	header('Content-Type: application/json');
	//$db['error'] = $error;
	//$db['sql'] = $sa;
	echo json_encode($db);
	exit;
}

if ($db['UAA'] == '')
{
	$sql = "SELECT * FROM UserAppAuth WHERE EmpNo = '" . $db['EmpNo'] . "'";
	$res = mssql_query($sql);
	if (!mssql_num_rows($res))
	{
		$sql = "INSERT INTO UserAppAuth (EmpNo, installationId, authorized) VALUES('" . $db['EmpNo'] . "','" . $_REQUEST['installationId'] . "', '0')";
		@mssql_query($sql);
		$errors[] = mssql_get_last_message();
		$sa[] = $sql;
	}
}

if ($db['installationId'] != $_REQUEST['installationId'] && $db['authorized'] == 1)
{
	$db['authorized'] = 0;
	$sql = "UPDATE UserAppAuth SET authorized = '0', installationId = '" . $_REQUEST['installationId'] . "' WHERE EmpNo = '" . $db['EmpNo'] . "'";
	@mssql_query($sql);
	$error[] = mssql_get_last_message();
		$sa[] = $sql;

}
if ($db['authorized'] == 0)
{
	$db2 = auth_email_send($_REQUEST['Email'], $_REQUEST['installationId']);
	$db = array_merge($db, $db2);
}
header('Content-Type: application/json');
$db['error'] = $error;
$db['sql'] = $sa;
echo json_encode($db);

?>