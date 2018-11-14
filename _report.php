<?php

function finchg($CustNo, $day)
{

	$day1 = $day * -1;
	$day2 = $day1 - 30;
	$sql = "SELECT Invoice, Dept, '' as Terms, CONVERT(varchar(10), InvDate, 101) as InvDates, CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts FROM Receivab WHERE CustNo=" . $CustNo . " and Type = 'F'
	and InvDate < DATEADD(DD, " . $day1 . ", getdate()) and InvDate > DATEADD(DD, " . $day2 . ", getdate()) 
	ORDER BY InvDate DESC;";
	echo $sql;
	
	//exit;
	$res = mssql_query($sql);

	$table = '';
	$row = '';
	$head = '';
	$hdr = '';
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		$row  = '<tr>';
		if (!$hdr)
		{
			$head = '<tr>';
		}
		foreach ($db as $key=> $value)
		{
			if (!$hdr)
			{
				$head .= "<td>$key</td>";
			}
			if ($key == 'InvAmts' || $key == 'Paids')
			{
				$value = money_format('%.2n', $value);
			}
			$row .= "<td align=right>" . htmlentities($value) . "</td>";
			//$row .= $value . "<BR>\r\n";
		}
		if (!$hdr)
		{
			$head .= "</tr>";
		}
		$row .= "</tr>";
	
		if ($head && !$hdr)
		{
			$hdr = $head;
			//$table .= $head;
		}
		$table .= $row;
		

	}
	//if ($table != '')
	//{
		//$table = "<table>" . $table . "</table>";
			//echo $table;
		//exit;
	//}

return $table;
}

function notes($CustNo, $day)
{

	$day1 = $day * -1;
	$day2 = $day1 - 30;
	$sql = "SELECT TOP 1 note FROM Collectn WHERE CustNo=" . $CustNo . " ORDER BY [DATE] DESC;";
	/*
	and [Date] < DATEADD(DD, " . $day1 . ", getdate()) and [Date] > DATEADD(DD, " . $day2 . ", getdate()) 
	
	ORDER BY [Date] DESC;";
	echo $sql;
	*/
	//exit;
	$res = mssql_query($sql);

	$table = '';
	$row = '';
	$head = '';
	$hdr = '';
	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		//$row  = '<tr>';
		if (!$hdr)
		{
			$head = '<tr>';
		}
		foreach ($db as $key=> $value)
		{
			if (!$hdr)
			{
				$head .= "<td>$key</td>";
			}

			//$row .= "<td align=right>" . htmlentities($value) . "</td>";
			$row .= $value . "<BR>\r\n";
		}
		if (!$hdr)
		{
			$head .= "</tr>";
		}
		//$row .= "</tr>";
	
		if ($head && !$hdr)
		{
			$hdr = $head;
			//$table .= $head;
		}
		//$table .= $row;

	}
	if ($table != '')
	{
		//$table = "<table>" . $table . "</table>";
			//echo $table;
		//exit;
	}
return $row;
}

function report($sql, $subject = '', $day = '0')
{
	setlocale(LC_MONETARY, 'en_US.UTF-8');

	$html = '<html>
<head>
  <title>' . $subject . '</title>
</head>
<body><font size="-1">';
	$table = '';
	$row = '';
	$head = '';
	$hdr = '';
	$CCusNo = '';
	$chdr = '';
    $res = mssql_query($sql);
    $table = "<table><tr><td colspan='3'><h1>$subject</h1></td></tr>";
    while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
    {
	$row = "<tr>";
	if (!$hdr)
	{
		$head = "<tr>";
		$cushead = "<th>";
	
	}	
$cus = array('CustNo', 'LastName', 'phone');
	foreach ($db as $key=> $value)
	{
		if (!$hdr && !in_array($key, $cus))
		{
			$head .= "<td>$key</td>";
		
		}
		if ($key == 'CustNo')
		{
			$cc = $value;
		}
		if (in_array($key, $cus) && $cc != $CCusNo)
		{
	
			//$cushead .= "<td>$key</td>";
			$cushead .= "<td>" . htmlentities($value) . "</td>";
		}
		if ($key == 'InvAmts' || $key == 'Paids')
		{
			$value = money_format('%.2n', $value);
		}
		if (!in_array($key, $cus))
		{		
			$row .= "<td align=right>" . htmlentities($value) . "</td>";
		}
	}
	if (!$hdr)
	{
		$head .= "</tr>";
	}
	$row .= "</tr>";
	
	if ($head && !$hdr)
	{
		$hdr = $head;
	
	}
	if ($cc != $CCusNo && $cc != '')
	{
		//echo $cushead
		echo "CC = $cc and CCusNo = $CCusNo\r\n";
		$table .= $cushead . "<td colspan='3'>" . notes($cc, $day) . "</td></th>\r\n";
		//$table .= $cusrow . "</tr>\r\n";
		$CCusNo = $cc;
		$cushead = "<th>";
		$table .= $hdr;
		$table .= "\r\n";
		$table .= finchg($cc, $day);
	}
	$table .= $row . "\r\n";
	unset($row);

    }
     $table .= "</table>\r\n";

	$html .= $table;
	$html .= "</font></body>";
	//echo $html;
return $html;
}     

