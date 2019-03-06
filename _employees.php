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
$sql = "SELECT * FROM JobGroup WHERE JobGroup != '' ORDER BY JobGroupID ASC ";
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

function jobgroupemployees_query($dev='', $ServiceMan='')
{

	if ($ServiceMan != '')
	{
		$sel = " WHERE ge.EmpNo = '$ServiceMan' and je.Job != '' ";
	}


$js['title'] = 'Group List';
$js['description'] = 'JobGroupID, EmpNo, Job';
$sql = "SELECT ge.JobGroupID, ge.EmpNo, je.Job, JobGroup, Location.LocName FROM JobGroupEmployee as ge INNER JOIN JobGroupEmployee as je ON ge.JobGroupID = je.JobGroupID
INNER JOIN JobGroup ON je.JobGroupID = JobGroup.JobGroupID
INNER JOIN Jobs ON je.Job = Jobs.Name 
INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
$sel ORDER BY ge.JobGroupID ASC ";
$res = mssql_query($sql);
$i=1;
$js['numEmp'] = 0;
while ($db = mssql_fetch_assoc($res))
{
	$db['id'] = $i;
	$js['jobgroupemployees'][] = $db;
	$js['numEmp'] = $i;
	$i++;
}

return $js;
}

function jobgroupemployee_selected($key, $id, $jobgroupemployees='')
{
	foreach ($jobgroupemployees as $jobgroupemployee)
	{
	
		if (is_array($jobgroupemployee))
		{
			
			if ($jobgroupemployee[$key] == $id)
			{
				if ($_REQUEST['debug'])
				{
					echo "jge = $jobgroupemployee[$key] $id $key <BR>\r\n";
				}
				return true;
			}
		}
		else
		{
			if ($jobgroupemployee->$key==$id)
			{
				return true;
			}
		}
	
	}
return false;
}

