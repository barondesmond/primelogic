<?php
include("_db_config.php");
include("_query.php");

/*
--------------------------------------------------------------------------Income/Labor----------------------------



SELECT '000000' as Account, 'Labor' as AcctDesc, LaborCost as Estimate, (
SELECT SUM(CASE WHEN JobClassID != '' and Account='58010' THEN Amount END) as Labor
FROM Sales
INNER JOIN Jobs ON Sales.Invoice = Jobs.Name
INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
WHERE Sales.TransID = '6f8c8d7a-9aa9-49df-b1af-595b7b57201a' and voided ='0' and Account = '58010'
GROUP BY Account, [COA].[DESC]) as JobToDate, (
 (
SELECT SUM(CASE WHEN JobClassID != '' and Account='58010' THEN Amount END) as Labor
FROM Sales
INNER JOIN Jobs ON Sales.Invoice = Jobs.Name
INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
WHERE Sales.TransID = '6f8c8d7a-9aa9-49df-b1af-595b7b57201a' and voided ='0' and Account = '58010'
GROUP BY Account, [COA].[DESC]) - LaborCost) 
as Variance
 FROM Sales WHERE TransID = '6f8c8d7a-9aa9-49df-b1af-595b7b57201a'
UNION
SELECT Account, [COA].[DESC] as AcctDesc, SUM(CASE WHEN JobClassID != '' THEN Amount * -1 END) as Estimate, SUM(CASE WHEN JobClassID = '' THEN Amount * -1 END)  as JobToDate,  SUM(CASE WHEN JobClassID = '' THEN Amount * -1 END) - (SUM(CASE WHEN JobClassID != '' THEN Amount * -1 END)) as Variance
FROM Sales
INNER JOIN Jobs ON Sales.Invoice = Jobs.Name
INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
WHERE Sales.TransID = '6f8c8d7a-9aa9-49df-b1af-595b7b57201a' and voided ='0' and Account = '40006' 
GROUP BY Account, [COA].[DESC]";

--------------------------------------------------------------------------Income/Labor-------------------------------

*/

/*
-----------------------------------------------------------------------Job Hrs/Person Month------------------------

SELECT SUM(Units) as Hrs, TransDesc, FORMAT(TransDate, 'yyy-MM') as TD
FROM Sales
INNER JOIN Jobs ON Sales.Invoice = Jobs.Name
INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
WHERE Sales.TransID = '6f8c8d7a-9aa9-49df-b1af-595b7b57201a' and voided ='0' and Account = '58010'
GROUP BY FORMAT(TransDate, 'yyy-MM') , TransDesc
------------------------------------------------Inventory Warehouse Transfer-------------------------------------------------------------------

SELECT SUM(InvRec.Quan) as Units, SUM(Cost) as JobToDate FROM Sales
INNER JOIN Jobs ON Sales.Invoice = Jobs.Name
INNER JOIN InvRec ON Jobs.Name = InvRec.Job
WHERE Sales.TransID = '6f8c8d7a-9aa9-49df-b1af-595b7b57201a'

SELECT SUM(mount), SUM(Units)
FROM Sales
INNER JOIN Jobs ON Sales.Invoice = Jobs.Name
INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
WHERE Sales.TransID = '6f8c8d7a-9aa9-49df-b1af-595b7b57201a' and voided ='0'  and Account = '12000'
ORDER BY Source, Account
-------------------------------------------------------------------------------------------------------------------------------
SELECT SUM(Amount), SUM(Units), Account, Source, CostType, CASE WHEN CostType = '0' THEN 'Income' WHEN CostType = '200' THEN 'Labor' WHEN CostType = '100' THEN 'Material' WHEN CostType = '150' THEN 'Equipment' WHEN CostType='300' THEN 'Other300' WHEN CostType='500' THEN 'Other'  ELSE ''END as CostGroup, [DESC]
FROM Sales
INNER JOIN Jobs ON Sales.Invoice = Jobs.Name
INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
WHERE Sales.TransID = '6f8c8d7a-9aa9-49df-b1af-595b7b57201a' and voided ='0'  
GROUP BY CostType,Account, Source, [DESC]
*/

$query = "SELECT SUM(Amount) as Amount, SUM(Units) as Units, Account, Source, CostType, CASE WHEN CostType = '0' THEN 'Income' WHEN CostType = '200' THEN 'Labor' WHEN CostType = '100' THEN 'Material' WHEN CostType = '150' THEN 'Equipment' WHEN CostType='300' THEN 'Other300' WHEN CostType='500' THEN 'Other'  ELSE ''END as CostGroup, [DESC], JobClass.Name
FROM Sales
INNER JOIN Jobs ON Sales.Invoice = Jobs.Name
INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
LEFT JOIN JobClass ON FinLedger.JobClassID = JobClass.JobClassID
WHERE Sales.TransID = '6f8c8d7a-9aa9-49df-b1af-595b7b57201a' and voided ='0'  
GROUP BY CostType,Account, Source, [DESC], JobClass.Name";



echo $query;
/*
	$res = mssql_query($query);
	echo	mssql_get_last_message();
	if (!$res && $mes = mssql_get_last_message($res))
	{
		echo "$mes";
	}
	$x=0;
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		show_data($db);
	}
	show_data(array(), '1');
*/
	$res = mssql_query($query);
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		$gr[$db['Account']][$db['Source']][$db['CostType']] = $db;
	}

job_sommary($gr);
function sum_array($gr,$sua, $sum)
{

	foreach ($sua as $su)
	{
		if (is_array($su))
		{
			$sum[$su['SUM']] = $sum[$su['SUM']] + $gr[$su['Account']][$su['Source']][$su['CostType']][$su['SUM']];
		}			
	}
return $sum[$su['SUM']];
}

function job_summary($gr)
{
	//Income row

	$sua['Estimate'] = array('SUM' => '0', 'Account'=>'40006', 'Source' => '100', 'CostType' => '100');
	$sua['JobToDate'] = array('SUM' => '0', 'Account'=>'11000', 'Source' => '400', 'CostType' => '0');
	$row['Type'] = 'Income';
	$row['Document'] = '';
	$row['Est Units'] = '';
	$row['Act Units'] = '';
	$row = sum_array($gr, $sua, $row);
	show_data($row);

	show_data(array(), '1');

}


?>