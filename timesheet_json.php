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

	$timekey['PRPayItem']['ItemID'] = 'Name';
	$timekey['JobClass']['JobClassID'] = 'Name';
	$timekey['SlDept']['DeptID'] = 'Desc';
	$timekey['PRWorkComp']['ID'] = 'Desc';

	$timesheet = array('ID', 'EmpNo', 'Date', 'Hours', 'PayItemID', 'Dispatch', 'JobID', 'JobClassID', 'DeptID', 'ItemID', 'Desc', 'Billable', 'Invoided', 'TimesheetOrder', 'Processed', 'WorkCompID');
	$tsmap =  array('ItemID' => 'PayItemID', 'JobClassID' => 'JobClassID', 'DeptID' => 'DeptID', 'ID' => 'WorkCompID');

/*

SELECT PRTimeEntry.ID, TimesheetID, PRTimeEntry.EmpNo, PRTimeEntry.Date, PRTimeEntry.Hours, Jobs.Name as Jobs, Dispatch.Dispatch, PRPayItem.Name as PRPayItem, JobClass.Name as JobClass, SlDept.[Desc] as SlDept, LedgerTrans.TransDesc as LedgerTransDesc, LedgerTrans.TransMemo as LedgerTransMemo, LedgerEntry.[TransDesc] as LedgerEntryDesc, LedgerEntry.TransMemo as LedgerEntryMemo, PRWorkComp.Desc as PRWorkComp FROM PRTimeEntry 
LEFT JOIN PRPayItem ON PRTimeEntry.PayItemID = PRPayItem.ItemID
LEFT JOIN Dispatch ON PRTimeEntry.Dispatch = Dispatch.Dispatch
LEFT JOIN Jobs ON PRTimeEntry.JobID = Jobs.JobID
LEFT JOIN JobClass ON PRTimeEntry.JobClassID = JobClass.JobClassID
LEFT JOIN SlDept ON PRTimeEntry.DeptID = SlDept.DeptID
LEFT JOIN FinLedger as LedgerTrans ON PRTimeEntry.LedgerTransID = LedgerTrans.TransID
LEFT JOIN PRWorkComp ON PRTimeEntry.WorkCompID = PRWorkComp.ID
LEFT JOIN FinLedger as LedgerEntry ON PRTimeEntry.LedgerEntryID = LedgerEntry.EntryID

WHERE Date > DATEADD(month, -1, getdate()) ORDER BY Date DESC
*/


function TimeKeyTable($table, $key, $value)
{
	$where['PRPayItem'] = " WHERE ItemType = '100' and PayType = '100' ";

	$sql = "SELECT [$key] , [$value] FROM $table";
	if (isset($where[$table]))
	{
		$sql .= $where['PRPayItem'];
	}
	$res = mssql_query($sql);
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		$js[$table][$db[$key]] = $db[$value];
	}
return $js;
}

function TimesheetConfig($timekey)
{


	foreach ($timekey as $table=>$keydb)
	{
		foreach ($keydb as $key=>$value)
		{
			$db = TimeKeyTable($table, $key, $value);
			
			$js[$table] = $db[$table];
		}
	}
return $js;
}






$js = TimesheetConfig($timekey);

if (!isset($_REQUEST['TSEmpNo']))
{
	$_REQUEST['TSEmpNo'] = '0';
}

$sql2 = "SELECT PRPayItem.Name, ItemID, PayItemID, PRHours.PRHoursID, Hours, EmpNo, StartTime, StopTime FROM PRPayItem
LEFT JOIN Time.dbo.PRHours ON PRPayItem.ItemID = PRHours.PayItemID and StartTime = '" . $_REQUEST['StartTime'] . "' and StopTime = '" . $_REQUEST['StopTime'] . "'  and EmpNo = '" . $_REQUEST['TSEmpNo'] . "'  
WHERE Name IN ('Regular Payroll', 'Sick/Personal Day', 'Vacation Day', 'Over Time Pay'); ";
$res2 = mssql_query($sql2);
$error[] = mssql_get_last_message();
$error[] = $sql2;
//print_r($error);
while ($pr = mssql_fetch_array($res2, MSSQL_ASSOC))
{

	$js['PRHours'][$pr['ItemID']] = $pr;
	
}

if (!$_REQUEST['StartTime'])
{
	$_REQUEST['StartTime'] = 0;
	$_REQUEST['StopTime'] = time();
}

$sql = "SELECT TImeClockApp.*, Employee.EmpNo as EmpNo, Employee.EmpName, Employee.Email, Employee.WorkComp, Jobs.JobID, Jobs.DefaultDeptID as DeptID FROM Employee
INNER JOIN Time.dbo.UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo 
INNER JOIN Time.dbo.TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo 
LEFT JOIN Jobs ON TimeClockApp.Name = Jobs.Name
WHERE Posted is NULL and TimeClockApp.StartTime > " . $_REQUEST['StartTime'] . " and TimeClockApp.StopTime < " . $_REQUEST['StopTime'];
$res = mssql_query($sql);
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
	$sql2 = "SELECT * FROM PRPayItem WHERE Name = 'Regular Payroll'";
	$res2 = mssql_query($sql2);
	$db2 = mssql_fetch_array($res2, MSSQL_ASSOC);
	$db['ItemID'] = $db2['ItemID'];
	if ($db['JobID'] != '' && $db['JobID'] != 'null')
	{
		$sql3 = "SELECT JobClassID FROM FinLedger WHERE JobID= '" .$db['JobID'] . "' and JobClassID != ''";
		$res3 = mssql_query($sql3);
		$db3 = mssql_fetch_array($res3, MSSQL_ASSOC);
		$db['JobClassID'] = $db3['JobClassID'];
	}
	if ($db['StopTime'] && $db['StartTime'])
	{
		$js['TimeSheet'][] = $db;
	}
}	

$sql3 = "SELECT * FROM Time.dbo.PRHours WHERE  StartTime = '" . $_REQUEST['StartTime'] . "' and StopTime = '" . $_REQUEST['StopTime'] . "'  and EmpNo = '" . $_REQUEST['TSEmpNo'] . "' and PayItemID = 'TCHours' ";
$res3 = @mssql_query($sql3);
$post = @mssql_fetch_array($res3, MSSQL_ASSOC);
if (isset($post['PayItemID']))
{
	$js['Post'][$_REQUEST['TSEmpNo']] = $post;
}

	header('Content-Type: application/json');
	echo json_encode($js);
	exit;


?>



