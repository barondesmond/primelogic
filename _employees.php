<?php

function employees_query($dev='')
{

if ($_REQUEST['EmpName'] && $_REQUEST['Email'])
{
	$sel = " and EmpName = '" . $_REQUEST['EmpName'] . "' and Email = '" . $_REQUEST['Email'] . "'";
}
$js['title'] = 'Employee List';
$js['description'] = 'Employee Number, Email, Phone for authentication';
$sql = "SELECT EmpNo as EmpNo, EmpName, Email, phone FROM Employee WHERE Email != '' and Inactive = '0' $sel";
$res = mssql_query($sql);
$i=1;
$js['numEmp'] = 0;
while ($db = mssql_fetch_assoc($res))
{
	$db['id'] = $i;
	$js['employees'][] = $db;
	$js['numEmp'] = $i;
	$i++;
}

return $js;
}

function jobgroups_query($dev='')
{


$js['title'] = 'Group List';
$js['description'] = 'JobGroupID, JobGroup';
$sql = "SELECT * FROM JobGroup ";
$res = mssql_query($sql);
$i=1;
$js['numEmp'] = 0;
while ($db = mssql_fetch_assoc($res))
{
	$db['id'] = $i;
	$js['jobgroups'][] = $db;
	$js['numEmp'] = $i;
	$i++;
}

return $js;
}