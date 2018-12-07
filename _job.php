<?php
//job




function job_query($val='J-0001907', $action = '')
{
	if ($action == 'summary')
	{
		$query = "SELECT SUM(Amount) as Amount, SUM(Units) as Units, Account, Source, CostType, CASE WHEN CostType = '0' THEN 'Income' WHEN CostType = '200' THEN 'Labor' WHEN CostType = '100' THEN 'Material' WHEN CostType = '150' THEN 'Equipment' WHEN CostType='300' THEN 'Other300' WHEN CostType='500' THEN 'Other'  ELSE ''END as CostGroup, [DESC]
		FROM Jobs 
		INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
		INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
		LEFT JOIN JobClass ON FinLedger.JobClassID = JobClass.JobClassID
		WHERE Jobs.Name = '$val' and voided ='0'  
		GROUP BY CostType,Account, Source, [DESC]";
	}
	elseif ($action == 'details')
	{
		$query = "SELECT Amount as Amount, Units as Units, Account, Source, CostType, CASE WHEN CostType = '0' THEN 'Income' WHEN CostType = '200' THEN 'Labor' WHEN CostType = '100' THEN 'Material' WHEN CostType = '150' THEN 'Equipment' WHEN CostType='300' THEN 'Other300' WHEN CostType='500' THEN 'Other'  ELSE ''END as CostGroup, [DESC]
		FROM Jobs 
		INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
		INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
		LEFT JOIN JobClass ON FinLedger.JobClassID = JobClass.JobClassID
		WHERE Jobs.Name = '$val' and voided ='0'  
		ORDER BY CostType,Account, Source, [DESC]";
	}
	else
	{

		$query = "SELECT SUM(Amount) as Amount, SUM(Units) as Units, Account, Source, CostType, CASE WHEN CostType = '0' THEN 'Income' WHEN CostType = '200' THEN 'Labor' WHEN CostType = '100' THEN 'Material' WHEN CostType = '150' THEN 'Equipment' WHEN CostType='300' THEN 'Other300' WHEN CostType='500' THEN 'Other'  ELSE ''END as CostGroup, [DESC]
		FROM Jobs 
		INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
		INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
		LEFT JOIN JobClass ON FinLedger.JobClassID = JobClass.JobClassID
		WHERE Jobs.Name = '$val' and voided ='0'  
		GROUP BY CostType,Account, Source, [DESC]";
	}
	//echo $query;
	$res = mssql_query($query);
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		$gr[$db['Account']][$db['Source']][$db['CostType']][] = $db;
		//show_data($db);

	}
	//show_data(array(), '1', '1');

	//print_r($gr);
	//$td = job_summary($gr);

return $gr;
}

function job_sum_array($gr,$sua)
{

	foreach ($sua as $key => $su)
	{

		if (is_array($su))
		{
			foreach ($gr[$su['Account']][$su['Source']][$su['CostType']] as $db)
			{
				$sum[$key] = $sum[$key] + $db[$su['SUM']];
			}
		}
		else
		{
			$sum[$key] = $su;
		}
	}
	
return $sum;
}

function job_summary($gr)
{
	//Income row

	$row['Type'] = 'Contract';
	$row['Document'] = '';
	$row['Est Units'] = '';
	$row['Act Units'] = '';
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'40006', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'11000', 'Source' => '400', 'CostType' => '0');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Estimate'] = $row['Estimate'] * -1;
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	//print_r($row);
	$tb[] = $row;
	//show_data($row);
	//Equipment/Material/Inventory
	$row['Type'] = 'Material Total';
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '100');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	//$tb[] = $row;
	$sum[] = $row;
	
	$row['Type'] = 'Equipment Freight';
	$row['Estimate'] = '0'; 
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'57501', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	//print_r($row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	//$tb[] = $row;
	$row1 = $row;

	$row['Type'] = 'Equipment Sales';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	//$tb[] = $row;
	$row2 = $row;
	$row['Type'] = 'Equipment';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = $row2['JobToDate'] + $row1['JobToDate']; //array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];

	//$tb[] = $row;
	$sum[] = $row2;
	$row['Type'] = 'Inventory';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'12000', 'Source' => '200', 'CostType' => '100');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	//$tb[] = $row;
	$sum[] = $row;
	$row['Type'] = 'Other';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'', 'Source' => '100', 'CostType' => '200');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'58007', 'Source' => '300', 'CostType' => '500');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	//$tb[] = $row;
	$sum[] = $row;

	$row['Type'] = 'MEIO Total';
	$row['Estimate'] = '0';
	$row['JobToDate'] = '0';
	for ($i=0; $i < count($sum); $i++)
	{
		$row['Estimate'] =  $row['Estimate'] + $sum[$i]['Estimate'];
		$row['JobToDate'] = $row['JobToDate'] + $sum[$i]['JobToDate'];
	}
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$tb[] = $row;

	$row['Type'] = 'Labor';
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '200');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$tb[] = $row;



	//print_r($tb);
	/*
	foreach ($tb as $rob)
	{
		show_data($rob);
	}

	show_data(array(), '1');
	*/
return $tb;
}

function jobs_active_query()
{
	$sql = "SELECT Jobs.Name, Customer.LastName FROM Jobs
	INNER JOIN Customer ON Jobs.CustNo = Customer.CustNo
	WHERE Inactive = '0'";
	
	$res = mssql_query($sql);
	while ($db = mssql_fetch_assoc($res))
	{
		$jobs[$db['Name']] = $db;
	}

return $jobs;

}




?>