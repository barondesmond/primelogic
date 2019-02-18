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
	$tsmap =  array('PayItemId' => 'ItemID', 'JobClassID' => 'JobClassID', 'DeptID' => 'DeptID', 'WorkCompID' => 'ID');

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
	global $tsmap;
	$map = $tsmap[$key];
	$sql = "SELECT [$key] as $map, [$value] FROM $table";
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

function timesheet_add($timekey, $db, $dev='')
{

$sql = "INSERT INTO PRTimeEntry$dev";

return false;
}


$js = TimesheetConfig($timekey);
if (isset($_REQUEST['timesheet_add']) && $_REQUEST['TimeSheet'])
{
	$js['Timesheet'] = timesheet_add($_REQUEST['Timesheet'], $_REQUEST['dev']);
}



	header('Content-Type: application/json');
	echo json_encode($js);
	exit;


?>



