<?php
include("_db_config.php");
//echo password_hash('rasmuslerdorf', PASSWORD_DEFAULT)."\n";


if (isset($_REQUEST['username']) && isset($_REQUEST['password']) && isset($_REQUEST['EmpName']) & isset($_REQUEST['Email']))
{
	$sql = "SELECT Employee.EmpNo as EmpNo, EmpName, Email, phone, UserAppAuth.installationId, UserAppAuth.authorized, UserAppAuth.EmpNo as UAA FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo
WHERE Email != '' and Inactive = '0'  and EmpName = '" . $_REQUEST['EmpName'] . "' and Email = '" . $_REQUEST['Email'] . "'";
	$res = mssql_query($sql);
	$uaa = mssql_fetch_assoc($res);

	if (isset($uaa['installationId']))
	{
	print_r($uaa);

		$sql = "SELECT * FROM AdminUser WHERE username = '" . $_REQUEST['username'] . "'";
		$res = mssql_query($sql);
		$admin = mssql_fetch_assoc($res);
		print_r($admin);
		if (isset($admin['password']))
		{
			if (password_verify($admin['password'], $_REQUEST['password']))
			{
				$uaa['authorized'] = 1;
				header('Content-Type: application/json');
				echo json_encode($uaa);
				exit;
			}
			else
			{
				$not['authorized'] = 0;
				header('Content-Type: application/json');
				echo json_encode($not);
				exit;
			}
		}
		else
		{
			$sql = "INSERT INTO AdminUser (EmpNo, username, password) ('" . $uaa['EmpNo'] . "', '" . $_REQUEST['username'] . "','" . $_REQUEST['password'] . "')";
			$res = mssql_query($sql);
		}
		echo $sql;
	}
}
$sql = "SELECT * FROM AdminUser WHERE username = '" . $_REQUEST['username'] ."'";
$res = mssql_query($sql);
$user = mssql_fetch_assoc($res);

if (password_verify($user['password'], $_REQUEST['password']))
{

	$sql = "SELECT Employee.EmpNo as EmpNo, EmpName, Email, phone, UserAppAuth.installationId, UserAppAuth.authorized, UserAppAuth.EmpNo as UAA FROM Employee
			INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo WHERE EmpNo = '" . $user['EmpNo'] . "'";
	$res = mssql_query($sql);
	$app = mssql_fetch_assoc($res);
	$app['authorized'] = 1;
	header('Content-Type: application/json');
	echo json_encode($app);
	exit;
}
else
{

	$app['authorized'] = 0;
	header('Content-Type: application/json');
	echo json_encode($app);
	exit;
}
