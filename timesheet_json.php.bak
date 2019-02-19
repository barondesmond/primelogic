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
	$sql = "SELECT [$key] , [$value] FROM $table";
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

function timeclock_post($dbs, $dev='')
{
	foreach ($dbs as $db)
	{
		$sql = "UPDATE TimeClockApp SET posted = 'Y' WHERE TimeClockID = '" . $db['TimeClockID'] . "'";
		$res = mssql_query($sql);
		$error[] = mssql_get_last_message();
	}
return $error;
}


$js = TimesheetConfig($timekey);
if (isset($_REQUEST['timesheet_add']) && $_REQUEST['TimeSheet'] && $_REQUEST['TimeClock'])
{
	$js['error'][] = timesheet_add($timesheet, $_REQUEST['TimeSheet'], $_REQUEST['dev']);
	$js['error'][] = timeclock_post($_REQUEST['TimeClock'], $_REQUEST['dev']);
}
if (!$_REQUEST['StartTime'])
{
	$_REQUEST['StartTime'] = 0;
	$_REQUEST['StopTime'] = time();
}

$sql = "SELECT TImeClockApp.*, Employee.EmpNo as EmpNo, Employee.EmpName, Employee.Email  FROM Employee
INNER JOIN UserAppAuth ON Employee.EmpNo = UserAppAuth.EmpNo 
LEFT JOIN TimeClockApp ON Employee.EmpNo = TimeClockApp.EmpNo 
WHERE Posted is NULL and TimeClockApp.StartTime > " . $_REQUEST['StartTime'] . " and TimeClockApp.StopTime < " . $_REQUEST['StopTime'];
$res = mssql_query($sql);
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
	if ($db['StopTime'] && $db['StartTime'])
	{
		$db['Date'] = date("M d Y", $db['StartTime']) . '12:00:00:00AM';
		$db['Hours'] = round($db['StopTime'] - $db['StartTime'] / (60*60), 2);
		$js['TimeSheet'][$_REQUEST['EmpNo']][] = $db;
	}
}	

	header('Content-Type: application/json');
	echo json_encode($js);
	exit;


?>



