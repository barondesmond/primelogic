<?php

/*
receive email web authorization and return textg
*/
function auth_email_confirm($email, $installationId, $hash)
{
	if ($hash == md5(SALTEMAIL . $email . $installationId))
	{
		$sql = "SELECT Employee.EmpNo FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo
WHERE Email = '" . $email . "'  and installationId = '" . $installationId . "'";
		$res = mssql_query($sql);
		if ($db = @mssql_fetch_array($res, MSSQL_ASSOC))
		{
			$sql = "UPDATE UserAppAuth SET authorized = '1' WHERE EmpNo = '" . $db['EmpNo'] . "' and installationId = '" . $installationId . "'";
			$res = @mssql_query($sql) or die('Not Authorized');
			$sql = "SELECT * FROM UserAppAuth WHERE EmpNo = '" . $db['EmpNo'] . "' and installationId = '" . $installationId . "'";
			$res2 = @mssql_query($sql);
			$db = @mssql_fetch_array($res2, MSSQL_ASSOC);
			if ($db['authorized'] == 1)
			{
				return "You have been authorized $email";
			}
		}
	}
	return "You have not been authorized";
}
			

/*
send email and json response for email authorization
*/

function auth_email_send($email, $installationId)
{
	$hash = md5(SALTEMAIL . $email . $installationId);
	
	$sub = "Confirm TimeClock installation";
	$body = "Please Confirm installation of TimeClock App for $email <A HREF=https://" . HOST . "/primelogic/auth_email.php?email=$email&installationId=$installationId&hash=$hash>here</A>";
	email_report($email, $sub, $body);
	$db['auth'] = "Email Authorization sent to $email";
	$db['email'] = $email;
	$db['installationId'] = $installationId;
	return $db;
	
}

