<?php

function finchg($CustNo, $day)
{

	$day1 = $day * -1;
	$day2 = $day1 - 30;
	$sql = "SELECT Invoice, '' as JB, Dept, '' as Terms, CONVERT(varchar(10), InvDate, 101) as InvDates, CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts FROM Receivab WHERE CustNo=" . $CustNo . " and Type = 'F'
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

	//$day1 = $day * -1;
	//$day2 = $day1 - 30;
	$day1 = '0';
	$day2 = '-60';
	$sql = "SELECT note FROM Collectn WHERE CustNo=" . $CustNo . "
	
	and [Date] < DATEADD(DD, " . $day1 . ", getdate()) and [Date] > DATEADD(DD, " . $day2 . ", getdate()) 
	
	ORDER BY [Date] DESC;";
	//echo $sql;
	
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
return wordwrap($row, 65, "<BR>\r\n");
}

function report($sql, $subject = '', $day = '0')
{
	setlocale(LC_MONETARY, 'en_US.UTF-8');

	$html = '<html>
<head>
  
</head>
<body><font size="-1">';
	$table = '';
	$row = '';
	$head = '';
	$hdr = '';
	$CCusNo = '';
	$chdr = '';
    $res = mssql_query($sql);
    $table = "<table><tr>$subject</td></tr>";
    while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
    {
	$row = "<tr>";
	if (!$hdr)
	{
		$head = "<tr>";
		$cushead = "<tr>";
	
	}	
$cus = array('CustNo', 'LastName', 'phone');
	foreach ($db as $key=> $value)
	{
		if (!$hdr && !in_array($key, $cus))
		{
			$head .= "<td align=right>$key</td>";
		
		}
		if ($key == 'CustNo')
		{
			$cc = $value;
		}
		if (in_array($key, $cus) && $cc != $CCusNo)
		{
			
			//$cushead .= "<td>$key</td>";
			if ($key != 'CustNo')
			{
				$cushead .= '<b>' . $value . '</b></td>';
			}
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
		$table .= '<tr><td colspan="7"><div style="border: solid 0 #060; border-top-width:2px; "></td></tr>';
		$table .= $cushead . "<td colspan='6'>"  . notes($cc, $day) . "</td></tr>\r\n";
		//$table .= $cusrow . "</tr>\r\n";
		$CCusNo = $cc;
		$cushead = '<tr>';
		//$table .= "</table><table>";

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

function report_basis($day='0', $day2='30', $emp='', $dept='', $email = '')
{

$day1 = $day * -1;
$day2 = $day2 * -1;

$sql2 = "SELECT CONVERT(decimal(12,2), SUM(InvAmt-Paid)) as Amt  FROM Sales, Receivab 
WHERE Sales.Invoice = Receivab.Invoice and  DueDate < DATEADD(DD, " . $day1 . ", getdate()) and DueDate > DATEADD(DD, " . $day2 . ", getdate())  and PaidOff is NULL ";
echo $sql2;
$res2 = mssql_query($sql2);
$db = mssql_fetch_array($res2);

$sql = "SELECT Customer.CustNo, Sales.Invoice, ISNULL(Receivab.JobNumber, Dispatch) as JobDispatch, CONCAT(Customer.LastName, '<BR>', ISNULL(phone1, phone2)) as LastName , Sales.Dept, Terms, CONVERT(varchar(10), Sales.DueDate, 101) as DueDates , CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts  
FROM Sales, Receivab, Customer
WHERE Sales.Invoice = Receivab.Invoice and Customer.CustNo=Sales.CustNo 
and DueDate < DATEADD(DD, " . $day . ", getdate()) and DueDate > DATEADD(DD, " . $day2 . ", getdate()) and PaidOff is NULL 
ORDER BY Sales.CustNo ASC;";
echo $sql;


	setlocale(LC_MONETARY, 'en_US.UTF-8');

$subject = "Ar Report " . $day1 . '-' . $day2 . " $emp $dept " . money_format('%.2n', $db[Amt]);
$subject2 = "<td align=left colspan'3'><h1>Ar Report " . $day . '-' . $day2 . " $emp $dept " . "</H1></td><td align=right colspan='4'><h1>" . money_format('%.2n', $db[Amt]) . "</h1></td>";



$html = report($sql, $subject2[$day], $day);

if (isset($email))
{
	$email = email_alias($day, $emp, $dept);
}

email_report($email, $subject, $html);

return $html;
}