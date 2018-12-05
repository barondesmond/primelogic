<?php
include("_db_config.php");
include("_query.php");

/*
--------------------------------------------------------------------------Income/Labor----------------------------
*/
$query = "

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

/*
--------------------------------------------------------------------------Income/Labor-------------------------------

*/


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

?>
