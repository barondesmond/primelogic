<?php
//job
setlocale(LC_MONETARY, 'en_US');

//not used
function job_inventory_query($gr, $val='J-0001907')
{
	$i = array('SUM' => 'Amount', 'Account'=>'12000', 'Source' => '200', 'CostType' => '100', 'Type' => '610');

	$query = "SELECT " . $i['Type'] . " as Type, [Desc] as Document, RecDate as TransDate, Cost as Amount, Quan as Units," . $i['Account'] . " as Account, " . $i['Source'] . " as Source,
	" . $i['CostType'] . " as CostType, " . $i['CostType'] . " as CostGroup 
	FROM InvRec WHERE Job = '$val' 
	ORDER BY RecDate ";
	$res = mssql_query($query);
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{

		$gr[$db['Account']][$db['Source']][$db['CostType']][] = $db;
		//print_r($db);
		//show_data($db);

	}
	
return $gr;
}

function job_tax_query($gr, $val='J-0001907')
{

		$query = "SELECT TransType as Type, LEFT(CONCAT(TransDesc, ' ', TransMemo, ' Tax Deduction'), 50) as Document, TransDate, (Sales.InvAmount - Sales.AmtCharge) as Amount, '0' as Units , Account, Source, CostType, CASE WHEN CostType = '0' THEN 'Income' WHEN CostType = '200' THEN 'Labor' WHEN CostType = '100' THEN 'Material' WHEN CostType = '150' THEN 'Equipment' WHEN CostType='300' THEN 'Other300' WHEN CostType='500' THEN 'Other'  ELSE ''END as CostGroup, [DESC]
FROM Jobs INNER JOIN Sales ON Jobs.Name = Sales.JobNumber 
INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID and Sales.AmtCharge = FinLedger.Amount
INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
WHERE Jobs.Name = '$val' and CostType = '0' and Source = '400' and (Sales.InvAmount - Sales.AmtCharge) != 0
	ORDER BY CostType,Account, Source, [DESC], TransDate";

	//echo $query;
	$res = mssql_query($query);
	if (mssql_num_rows($res))
	{
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		//if ($db['Type'] != '610')
		//{
			$gr[$db['Account']][$db['Source']][$db['CostType']][] = $db;
		//}
			//print_r($db);
	}
	}
	//$gr = job_inventory_query($gr, $val);


return $gr;
}


function job_query($val='J-0001907', $action = '')
{

		$query = "SELECT TransType as Type, LEFT(CONCAT(TransDesc, ' ', TransMemo), 50) as Document, TransDate, Amount as Amount, Units as Units, Account, Source, CostType, CASE WHEN CostType = '0' THEN 'Income' WHEN CostType = '200' THEN 'Labor' WHEN CostType = '100' THEN 'Material' WHEN CostType = '150' THEN 'Equipment' WHEN CostType='300' THEN 'Other300' WHEN CostType='500' THEN 'Other'  ELSE ''END as CostGroup, [DESC]
		FROM Jobs 
		INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID
		INNER JOIN COA ON FinLedger.AccountID = COA.AccountID
		WHERE Jobs.Name = '$val' and voided ='0'  
		ORDER BY CostType,Account, Source, [DESC], TransDate";

	//echo $query;
	$res = mssql_query($query);
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		//if ($db['Type'] != '610')
		//{
			$gr[$db['Account']][$db['Source']][$db['CostType']][] = $db;
		//}

	}
	//$gr = job_inventory_query($gr, $val);
	$gr = job_tax_query($gr, $val);

return $gr;
}

function job_sum_array($gr,$sua, &$rows='')
{
	$mtd = date("Y-m", time());
	$mtd = $mtd . '-01 00:00:00';
	$tmtd = strtotime($mtd);
	$twtd = time()-86400*7;
	$alt = array('Estimate'=> 'Amount', 'JobToDate' => 'Amount', 'Act Units' => 'Units', 'Est Units' => 'Units', 'WeekToDate'=> 'Amount', 'MonthToDate' => 'Amount');
	foreach ($sua as $key => $su)
	{

		if (is_array($su))
		{
			$sum[$key] = '0';
			foreach ($gr[$su['Account']][$su['Source']][$su['CostType']] as $db)
			{
				if ($key == 'WeekToDate')
				{
					if (strtotime($db['TransDate']) > $twtd)
					{
						$sum['WeekToDate'] = $sum['WeekToDate'] + $db[$su['SUM']];
						//print_r($db);
					}

				}
				elseif ($key == 'MonthToDate')
				{
					if (strtotime($db['TransDate']) > $tmtd)
					{
						$sum['MonthToDate'] = $sum['MonthToDate'] + $db[$su['SUM']];

					}
				}
				else
				{
					$sum[$key] = $sum[$key] + $db[$su['SUM']];
					$sum[$key] = number_format((float)$sum[$key], 2, '.', '');
					$db[$key] = number_format((float)$db[$su['SUM']], 2, '.', '');

				}
				if ($su['Account'] == '58010'  && $su['Source'] == '700' && $su['CostType'] == '200')
				{
					$db['JobToDate'] = '0';
					$db['WeekToDate'] = '0';
					$db['MonthToDate'] = '0';
					$db['Amount'] = '0';
					$db['Act Units'] = $db['Units'];
					//print_r($db);
					//exit;
					//$rows[] = $db;

				}
	
			    if ($key == 'JobToDate' && $sum[$key] > 0)
				{
					if (strtotime($db['TransDate']) > $tmtd)
					{
						$db['MonthToDate'] = number_format((float)$db[$su['SUM']], 2, '.', '');
					}
					if (strtotime($db['TransDate']) > $twtd)
					{
						$db['WeekToDate'] = number_format((float)$db[$su['SUM']], 2, '.', '');
					}
					$rows[] = $db;
				}

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
	$row['Act Units'] = '0.00';
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'40006', 'Source' => '100', 'CostType' => '100');
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'11000', 'Source' => '400', 'CostType' => '0');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'11000', 'Source' => '400', 'CostType' => '0');

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
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '100');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '100');

	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '100');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$sum[] = $row;
	
	$row['Type'] = 'Equipment Freight';
	$row['Estimate'] = '0'; 
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'57501', 'Source' => '300', 'CostType' => '150');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'57501', 'Source' => '300', 'CostType' => '150');

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
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');

	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$row2 = $row;
	$row['Type'] = 'Equipment';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['WeekToDate'] = $row2['WeekToDate'] + $row1['WeekToDate']; //array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	$row['MonthToDate'] = $row2['MonthToDate'] + $row1['MonthToDate']; //array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');

	$row['JobToDate'] = $row2['JobToDate'] + $row1['JobToDate']; //array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$sum[] = $row2;
	$row['Type'] = 'Inventory';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'12000', 'Source' => '200', 'CostType' => '100');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'12000', 'Source' => '200', 'CostType' => '100');

	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'12000', 'Source' => '200', 'CostType' => '100');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$sum[] = $row;
	$row['Type'] = 'Other';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'', 'Source' => '100', 'CostType' => '200');
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'58007', 'Source' => '300', 'CostType' => '500');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'58007', 'Source' => '300', 'CostType' => '500');

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
	$row['WeekToDate'] = '0';
	$row['MonthToDate'] = '0';
	//print_r($sum);
	for ($i=0; $i < count($sum); $i++)
	{
		$row['Estimate'] =  $row['Estimate'] + $sum[$i]['Estimate'];
		$row['JobToDate'] = $row['JobToDate'] + $sum[$i]['JobToDate'];
		$row['MonthToDate'] = $row['MonthToDate'] + $sum[$i]['MonthToDate'];
		$row['WeekToDate'] = $row['WeekToDate'] + $sum[$i]['WeekToDate'];
	}
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['Estimate'] - $row['JobToDate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	$tb[] = $row;

	$row['Type'] = 'Labor';
	$row['Act Units'] = array('SUM'=> 'Units', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200'); 
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '200');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200');
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200');
	//print_r($row);
	$row = job_sum_array($gr,$row);
	$row['Variance'] = $row['Estimate']  - $row['JobToDate'];
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
	$sql = "SELECT Jobs.Name, Location.LocName as LastName FROM Jobs
	INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo

	WHERE JobStatus = '100' and Inactive = '0' $nas
	ORDER BY Name 
	";
	
	$res = mssql_query($sql);
	while ($db = mssql_fetch_assoc($res))
	{
		if (!isset($jb[$db['Name']]))
		{
			$jb[$db['Name']] = $db;
			$jobs[] = $db;
		}
	}

return $jobs;

}

function jobs_year_query($Year = '', $Amount = '0')
{
	$Year2 = $Year + 1;
	$sql = "SELECT Jobs.Name, Location.LocName as LastName FROM Jobs
	INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
	INNER JOIN FinLedger ON Jobs.JobID = FinLedger.JobID

	WHERE CostType = '0' and Amount > '$Amount' and Start > '$Year-01-01T00:00:00.000' and ProjEnd < '$Year2-01-01T00:00:00.000'
	ORDER BY Name 
	";
	
	$res = mssql_query($sql);
	while ($db = mssql_fetch_assoc($res))
	{
		if (!isset($jb[$db['Name']]))
		{
			$jb[$db['Name']] = $db;
			$jobs[] = $db;
		}
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
	$row['WeekToDate'] =  array('SUM' => 'Amount', 'Account'=>'11000', 'Source' => '400', 'CostType' => '0');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'11000', 'Source' => '400', 'CostType' => '0');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'11000', 'Source' => '400', 'CostType' => '0');

	//print_r($row);
	$row = job_sum_array($gr,$row, $contract);
	if ($row['Estimate'] < 0)
	{
		$row['Estimate'] = $row['Estimate'] * -1;
	}
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');
	//print_r($row);
	$tb[] = $row;
	$tb[$row['Type']] = $contract;

	//show_data($row);
	//Equipment/Material/Inventory
	$row['Type'] = 'Material Total';
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '100');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '100');
	//print_r($row);
	$row = job_sum_array($gr,$row, $meio);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$sum[] = $row;
	
	$row['Type'] = 'Equipment Freight';
	$row['Estimate'] = '0'; 
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'57501', 'Source' => '300', 'CostType' => '150');
	$row['MonthToDate'] =array('SUM' => 'Amount', 'Account'=>'57501', 'Source' => '300', 'CostType' => '150');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'57501', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row, $meio);
	//print_r($row);
	
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$row1 = $row;

	$row['Type'] = 'Equipment Sales';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row, $meio);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$row2 = $row;
	$row['Type'] = 'Equipment';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['WeekToDate'] = $row2['WeekToDate'] + $row1['WeekToDate'];
	$row['MonthToDate'] = $row2['MonthToDate'] + $row1['MonthToDate'];
	$row['JobToDate'] = $row2['JobToDate'] + $row1['JobToDate']; //array('SUM' => 'Amount', 'Account'=>'50001', 'Source' => '300', 'CostType' => '150');
	//print_r($row);
	$row = job_sum_array($gr,$row, $meio);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$sum[] = $row2;
	$row['Type'] = 'Inventory';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '100');
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'12000', 'Source' => '200', 'CostType' => '100');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'12000', 'Source' => '200', 'CostType' => '100');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'12000', 'Source' => '200', 'CostType' => '100');
	//print_r($row);
	$row = job_sum_array($gr,$row, $meio);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');
	
	//$tb[] = $row;
	$sum[] = $row;
	$row['Type'] = 'Other';
	$row['Estimate'] = '0'; //array('SUM' => 'Amount', 'Account'=>'', 'Source' => '100', 'CostType' => '200');
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'58007', 'Source' => '300', 'CostType' => '500');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'58007', 'Source' => '300', 'CostType' => '500');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'58007', 'Source' => '300', 'CostType' => '500');
	//print_r($row);
	$row = job_sum_array($gr,$row, $meio);
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');

	//$tb[] = $row;
	$sum[] = $row;

	$row['Type'] = 'MEIO Total';
	$row['Estimate'] = '0';
	$row['JobToDate'] = '0';
	$row['WeekToDate'] = '0';
	$row['MonthToDate'] = '0';
	//print_r($sum);
	for ($i=0; $i < count($sum); $i++)
	{
		$row['Estimate'] =  $row['Estimate'] + $sum[$i]['Estimate'];
		$row['JobToDate'] = $row['JobToDate'] + $sum[$i]['JobToDate'];
		$row['MonthToDate'] = $row['MonthToDate'] + $sum[$i]['MonthToDate'];
		$row['WeekToDate'] = $row['WeekToDate'] + $sum[$i]['WeekToDate'];
	}
	
	
	//print_r($row);
	$row = job_sum_array($gr,$row, $details);
	$row['Variance'] = $row['Estimate'] - $row['JobToDate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');
	//print_r($row);
	$tb[] = $row;
	$tb[$row['Type']] = $meio;
	$row['Type'] = 'Labor';
	$row['Act Units'] = array('SUM'=> 'Units', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200'); 
	$row['Estimate'] = array('SUM' => 'Amount', 'Account'=>'50003', 'Source' => '100', 'CostType' => '200');
	$row['WeekToDate'] = array('SUM' => 'Amount', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200');
	$row['MonthToDate'] = array('SUM' => 'Amount', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200');
	$row['JobToDate'] = array('SUM' => 'Amount', 'Account'=>'58010', 'Source' => '700', 'CostType' => '200');
	//print_r($row);
	$row = job_sum_array($gr,$row, $labor);
	$row['Variance'] = $row['Estimate'] - $row['JobToDate'];
	$row['Variance'] = number_format((float)$row['Variance'], 2,'.', '');
	//print_r($row);
	$tb[] = $row;
	$tb[$row['Type']] = $labor;

return $tb;
}

function jobs_summary_report($jobs, $title = 'Jobs Active Summary Report')
{

$i=0;
$key = array('Type',  'Estimate', "WeekToDate", "MonthToDate",  'JobToDate', 'Variance');
$table = '';

for ($i=0; $i < count($jobs); $i++)
{
	$job = $jobs[$i];

	$gr = job_query($job['Name']);
	$td = job_summary($gr);
	//print_r($td);
	if (!$hd)
	{
		$hd = $job;
		$hd['title'] = $title;
		$table .= job_head($hd, $key);
	}
	$table .= job_summary_title($job, $key);
	$table .= job_summary_hd($key);
	$table .= job_summary_bar($key);

	$ov['Type'] = 'Overhead/Burdens';
	$ov['Document'] = '';
	$ov['Estimate'] = $td['0']['Estimate'] * OVERHEAD;
	$row['WeekToDate'] = '';
	$row['MonthToDate'] = '';
	$ov['JobToDate'] = $td['0']['Estimate'] * OVERHEAD;
	$ov['Variance'] = '';
	$row['Type'] = 'Summary';
	$row['Document'] = '';

	$row['Estimate'] = '0.00';
	$row['WeekToDate'] = '0.00';
	$row['MonthToDate'] = '0.00';

	$row['JobToDate'] = '0.00';
	$row['Variance'] = '0.00';
	for ($t=0; $t< count($td); $t++)
	{
		$table .= job_row($td[$t], $key);
		if ($t==0)
		{
			$table .= job_bar_dotted($key);
		}
		//$row['Estimate'] = $row['Estimate'] + $td[$t]['Estimate'];
	

	}
		$table .= job_row($ov, $key);

	$row['Estimate'] = $td[0]['Estimate'] - $td[1]['Estimate'] - $td[2]['Estimate'] - $ov['Estimate'];
	$row['JobToDate'] = $td[0]['Estimate'] - $td[1]['JobToDate'] - $td[2]['JobToDate'] - $ov['Estimate'];
	$row['Variance'] = $row['JobToDate'] - $row['Estimate'];
		$table .= job_summary_bar($key, 'white');
		$table .= job_row($row, $key);
		$table .= job_summary_bar($key, 'white');
		unset($row);
	
	if ($i > 2)
	{
		//break;
		//$i = count($jobs);
	}
}
$table .= job_foot($key);
$html = '<html><body>' . $table . '</body></html>';
return $html;
}



function jobs_query($dev='', $ServiceMan='')
{

	if ($ServiceMan != '')
	{
		$jge = jobgroupemployees_query($dev, $ServiceMan);
		$js['jge'] = $jge;
	}
$js['numEmp'] = 0;
$js['title'] = 'Jobs List';
$js['description'] = 'Job Name, Job Location';
$sql = "SELECT  Jobs.Name as Name, Jobs.JobID, Location.CustNo, Location.LocNo, Location.LocName as LocName, CONCAT(Location.Add1, ',', Location.City, ',' , Location.State, ' ' , Location.Zip) as location, Location.Add1, Location.City, Location.State, Location.Zip,Jobs.JobNotes as JobNotes, Location.latitude, Location.longitude FROM Jobs$dev as Jobs
	INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
	WHERE JobStatus = '100' and Inactive = '0' and Location.Add1 != '' and Location.City != '' and Location.State != '' and Location.Zip != ''
	ORDER BY LocName ";
$res = mssql_query($sql);
$i=1;
while ($db = mssql_fetch_assoc($res))
{
	
	$db['id'] = $i;
	$db['latitude'] = location_int_gps($db['latitude']);
	$db['longitude'] = location_int_gps($db['longitude']);

	if ($db['latitude'] == '' || $db['latitude'] == '0')
	{
		$loc = location_api($db['LocName'], $db);
		$db['latitude'] = $loc['latitude'];
		$db['longitude'] = $loc['longitude'];

	}
	if ($_REQUEST['latitude']!='null' && $_REQUEST['latitude'] != '' &&  $db['latitude'] != '' && $db['latitude'] != 'null')
	{
		$db['distance'] = distance($_REQUEST['latitude'], $_REQUEST['longitude'], $db['latitude'], $db['longitude']);
	}
	if ($jge['numEmp']==0 || !isset($jge) || jobgroupemployee_selected('Job', $db['Name'], $jge['jobgroupemployees']))
	{
		$js['jobs'][] = $db;
		$js['numEmp']++;
		$i++;
	}
	else
	{
		$js['jobsnotauthorized'][] =$db;
	}
}	
	if ($js['numEmp'] == '0')
	{
		$js['jobs'] = $js['jobsnotauthorized'];
	}	
	if ($jge['numEmp'] > 0)
	{
		$js = array_merge($jge, $js);
	}

return $js;
}
?>