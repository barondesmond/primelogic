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
		$query = "SELECT TransType, TransDesc as Document, CONCAT( CONVERT(varchar(10), Sales.InvDate, 101) , ' ', TransType) as Type, Amount as Amount, Units as Units, Account, Source, CostType, CASE WHEN CostType = '0' THEN 'Income' WHEN CostType = '200' THEN 'Labor' WHEN CostType = '100' THEN 'Material' WHEN CostType = '150' THEN 'Equipment' WHEN CostType='300' THEN 'Other300' WHEN CostType='500' THEN 'Other'  ELSE ''END as CostGroup, [DESC]
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

function job_sum_array($gr,$sua, &$rows='')
{
	$alt = array('Estimate'=> 'Amount', 'JobToDate' => 'Amount', 'Act Units' => 'Units', 'Est Units' => 'Units');
	foreach ($sua as $key => $su)
	{

		if (is_array($su))
		{
			$sum[$key] = '0.00';
			foreach ($gr[$su['Account']][$su['Source']][$su['CostType']] as $db)
			{
				$sum[$key] = $sum[$key] + $db[$su['SUM']];
				$sum[$key] = number_format((float)$sum[$key], 2, '.', '');
				if ($su['Account'] == '58010'  && $su['Source'] == '700' && $su['CostType'] == '200')
				{
					$db['JobToDate'] = '0';
					$db['Amount'] = '0';
					$db['Act Units'] = $db['Units'];
					//print_r($db);
					//exit;
				}
				$db[$key] = number_format((float)$db[$su['SUM']], 2, '.', '');
	
					
				$rows[] = $db;
			}
		}
		else
		{
			$sum[$key] = $su;
		}
	}
	
return $sum;
}

function job_details_array($gr, $sua)
{
	
	$row = job_sum_array($gr, $sua, $details);
	$details[] = $row;

return $details;
}

function job_summary($gr)
{
	//Income row

	$row['Type'] = 'Contract';
	$row['Document'] = ' ';
	$row['Est Units'] = '0.00';
	$row['Act Units'] = '0.00';
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'40006', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'11000', 'Source' => '400', 'CostType' => '0');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	if ($row['Estimate'] < 0)
	{
		$row['Estimate'] = $row['Estimate'] * -1;
	}
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');
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
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$sum[] = $row;
	
	$row['Type'] = 'Equipment Freight';
	$row['Estimate'] = '0'; 
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'57501', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	//print_r($row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$row1 = $row;

	$row['Type'] = 'Equipment Sales';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$row2 = $row;
	$row['Type'] = 'Equipment';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = $row2['JobToDate'] + $row1['JobToDate']; //array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$sum[] = $row2;
	$row['Type'] = 'Inventory';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'12000', 'Source' => '200', 'CostType' => '100');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$sum[] = $row;
	$row['Type'] = 'Other';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'', 'Source' => '100', 'CostType' => '200');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'58007', 'Source' => '300', 'CostType' => '500');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

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
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;

	$row['Type'] = 'Labor';
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '200');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;


return $tb;
}

function jobs_active_query($Name = '')
{
	if ($Name != '')
	{
		$nas = " and Jobs.Name = '$Name' ";
	}
	$sql = "SELECT Jobs.Name, Customer.LastName FROM Jobs
	INNER JOIN Customer ON Jobs.CustNo = Customer.CustNo
	WHERE Inactive = '0' $nas";
	
	$res = mssql_query($sql);
	while ($db = mssql_fetch_assoc($res))
	{
		$jobs[] = $db;
	}

return $jobs;

}


function job_details($gr)
{
	
	$row['Type'] = 'Contract';
	$row['Document'] = ' ';
	$row['Est Units'] = '0.00';
	$row['Act Units'] = '0.00';
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'40006', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'11000', 'Source' => '400', 'CostType' => '0');
	//print_r($row);
	$row = job_sum_array($gr,$row, $tb);
	if ($row['Estimate'] < 0)
	{
		$row['Estimate'] = $row['Estimate'] * -1;
	}
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');
	//print_r($row);
	$tb[] = $row;
	//show_data($row);
	//Equipment/Material/Inventory
	$row['Type'] = 'Material Total';
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '100');
	//print_r($row);
	$row = job_sum_array($gr,$row, $tb);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;
	$sum[] = $row;
	
	$row['Type'] = 'Equipment Freight';
	$row['Estimate'] = '0'; 
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'57501', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row, $tb);
	//print_r($row);
	
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;
	$row1 = $row;

	$row['Type'] = 'Equipment Sales';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row, $tb);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;
	$row2 = $row;
	$row['Type'] = 'Equipment';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = $row2['JobToDate'] + $row1['JobToDate']; //array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row, $tb);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;
	$sum[] = $row2;
	$row['Type'] = 'Inventory';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'12000', 'Source' => '200', 'CostType' => '100');
	//print_r($row);
	$row = job_sum_array($gr,$row, $tb);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;
	$sum[] = $row;
	$row['Type'] = 'Other';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'', 'Source' => '100', 'CostType' => '200');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'58007', 'Source' => '300', 'CostType' => '500');
	//print_r($row);
	$row = job_sum_array($gr,$row, $tb);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;
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
	$row = job_sum_array($gr,$row, $tb);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;

	$row['Type'] = 'Labor';
	//$row['Act Units'] = array('SUM'=> 'Units', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200'); 
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '200');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200');
	//print_r($row);
	$row = job_sum_array($gr,$row, $tb);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;

return $tb;
}


?>