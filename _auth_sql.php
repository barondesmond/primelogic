<?php
//mmodel

function auth_employee_email_sql()
{
$sql = "SELECT EmpName, Email FROM Employee WHERE Email != '' and Inactive = '0'";
$res = mssql_query($sql);
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
	$emails[] = $db;
}

return $emails
}



?>