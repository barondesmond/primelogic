<?php
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
	$sql = "SELECT $key, $value FROM $table";
	$res = mssql_query($sql);
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		$js[$table][$db[$key]] = $db[$value];
	}
return $js;
}

function TimesheetConfig()
{

	$timekey['PRPayTable']['ItemID'] = 'Name';
	foreach ($timekey as $table)
	{
		foreach ($table as $key=>$value)
		{
			$db = TimeKeyTable($table, $key, $value);
			$js[$table] = $db[$table];
		}
	}
return $js;
}

$js = TimesheetConfig();

	header('Content-Type: application/json');
	echo json_encode($js);
	exit;


?>



