<?php

function UserAppAuth($db)
{

	$sql = "SELECT * FROM Time.dbo.UserAppAuth WHERE EmpNo = '" . $db['EmpNo'] . "' and installationId = '" . $db['installationId'] . "'";
	$res = mssql_query($sql);
	$db = mssql_fetch_array($res, MSSQL_ASSOC);

if (!isset($db['authorized']))
{
	$db['authorized'] = '0';
	$db['sql'] = $sql;
}

return $db;

}


	