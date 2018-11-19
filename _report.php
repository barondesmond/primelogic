<?php

function finchg($CustNo, $day1='0', $day2 = '-1096')
{

	$sql = "SELECT Invoice, '' as JB, Dept, '' as Terms, CONVERT(varchar(10), InvDate, 101) as InvDates, CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts FROM Receivab WHERE CustNo=" . $CustNo . " and Type = 'F'
	and InvDate < DATEADD(DD, " . $day1 . ", getdate()) and InvDate > DATEADD(DD, " . $day2 . ", getdate()) and Paid != InvAmt
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

function report($sql, $subject = '', $day1, $day2)
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
				$cushead .= '<td><b>' . $value . '</b></td>';
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

		$table .= finchg($cc, $day1, $day2);
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

	if (isset($emp) && $emp != '' )
	{
		$empsql = " and Salesman = '$emp' ";
	}
	if (isset($dept) && $dept != '')
	{
		$deptsql = " and Receivab.Dept = '$dept' ";
	}

$sql2 = "SELECT CONVERT(decimal(12,2), SUM(InvAmt-Paid)) as Amt  FROM Sales, Receivab 
WHERE Sales.Invoice = Receivab.Invoice and  DueDate < DATEADD(DD, " . $day1 . ", getdate()) and DueDate > DATEADD(DD, " . $day2 . ", getdate())  and PaidOff is NULL and Paid != InVAmt $empsql $deptsql";
echo $sql2;
$res2 = mssql_query($sql2);
$db = mssql_fetch_array($res2);

$sql = "SELECT Customer.CustNo, Sales.Invoice, CASE WHEN Sales.JobNumber != '' THEN Sales.JobNumber ELSE Sales.Dispatch END  as JobDispatch, CONCAT(Customer.LastName, '<BR>', ISNULL(phone1, phone2)) as LastName , Sales.Dept, Terms, CONVERT(varchar(10), Sales.DueDate, 101) as DueDates , CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts  
FROM Sales, Receivab, Customer
WHERE Sales.Invoice = Receivab.Invoice and Customer.CustNo=Sales.CustNo 
and DueDate < DATEADD(DD, " . $day1 . ", getdate()) and DueDate > DATEADD(DD, " . $day2 . ", getdate()) and PaidOff is NULL and Paid != InvAmt $empsql $deptsql
ORDER BY Sales.CustNo ASC;";
//echo $sql;


	setlocale(LC_MONETARY, 'en_US.UTF-8');

$subject = "Ar Report " . $day . $day2 . " $emp $dept " . money_format('%.2n', $db[Amt]);
$subject2 = "<td align=left colspan'3'><h1>Ar Report " . $day .  $day2 . " $emp $dept " . "</H1></td><td align=right colspan='4'><h1>" . money_format('%.2n', $db[Amt]) . "</h1></td>";



$html = report($sql, $subject2, $day1, $day2);

email_report($email, $subject, $html);

return $html;
}



function location_basis()
{

	//$noemail = array('');
	$curCustNo = '';
	$curEmailer = '';
	$curLocNo = '';
	$ik = array('Invoice', 'JobDispatch', 'Dept', 'Terms', 'DueDates', 'DaysPastDue', 'Paids', 'InvAmts');
	$ci = '';
	$pi = '';
	$noe = '';

$sql = "
SELECT  Customer.CustNo, Location.LocNo, Customer.LastName, Location.LocName, 
 Case
 WHEN (Email not like '%[^a-z,0-9,@,.]%' and Email like '%_@_%_.__%') and (EmailTasks1 = '2' or EmailTasks2 = '255')  THEN Email
 WHEN (Email2 not like '%[^a-z,0-9,@,.]%' and Email2 like '%_@_%_.__%') and (EmailTasks2 = '2' or EmailTasks2 = '255') THEN Email2
 WHEN (Email3 not like '%[^a-z,0-9,@,.]%' and Email3 like '%_@_%_.__%') and (EmailTasks3 = '2' or EmailTasks3 = '255') THEN Email3
 WHEN (Email4 not like '%[^a-z,0-9,@,.]%' and Email4 like '%_@_%_.__%') and (EmailTasks4 = '2' or EmailTasks4 = '255') THEN Email4
 WHEN (Email5 not like '%[^a-z,0-9,@,.]%' and Email5 like '%_@_%_.__%') and (EmailTasks5 = '2' or EmailTasks5 = '255') THEN Email5
 WHEN (Email6 not like '%[^a-z,0-9,@,.]%' and Email6 like '%_@_%_.__%') and (EmailTasks6 = '2' or EmailTasks6 = '255') THEN Email6
 ELSE 'No Email' 
 END
 as Emailer, Receivab.Invoice, CASE WHEN Sales.JobNumber != '' THEN Sales.JobNumber ELSE Dispatch END as JobDispatch, Sales.Dept, Terms, CONVERT(varchar(10), Sales.DueDate, 101) as DueDates, 
 DATEDIFF ( dd , DueDate , getdate() ) as DaysPastDue, CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts 

FROM Customer
INNER JOIN Sales ON Customer.CustNo = Sales.CustNo
INNER JOIN Receivab ON Sales.Invoice = Receivab.Invoice
INNER JOIN Location ON Receivab.CustNo = Location.CustNo and Receivab.LocNo = Location.LocNo
WHERE 
(EmailTasks1 = '2' 
or EmailTasks2 = '2' 
or EmailTasks3 = '2'
or EmailTasks4 = '2'
or EmailTasks5 = '2'
or EmailTasks6 = '2'
or EmailTasks1 = '255'
or EmailTasks2 = '255'
or EmailTasks3 = '255'
or EmailTasks4 = '255'
or EmailTasks5 = '255'
or EmailTasks6 = '255'
)
and LocationInactive = '0'
and CustomerInactive = '0'
and Paid < InvAmt and InvAmt > 0 and PaidOff is NULL
ORDER BY Customer.CustNo, Emailer, Location.LocNo;";

$res = mssql_query($sql);

	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		print_r($db);
		if ($db['Emailer'] == 'No Email')
		{
			if ($noe == '')
			{
				$noe = table_hd($db);
			}
			$noe .= table_row($db);
		}
		else
		{	
			if ($db['CustNo'] != $curCustNo || ($db['CustNo'] == $curCustNo && $db['Emailer'] != $curEmailer))
			{
				//queue email if exists
				//start email
				//logo
				//finance charge
				$table = '<table>';

				if ($curCustNo == '')
				{
					$cl = substr($db['LastName'], 0,1);
					$html = html_head($cl);
					$t['fnchg'] = 'Finance Charges';
					$html .= table_hd($t, '', '', count($ik));				
					$html .= finchg($db['CustNo']);
					//unset($t);					
				}
				
				$curCustNo = $db['CustNo'];
				$curEmailer = $db['Emailer'];
				$curLocNo = $db['LocNo'];

			}
	
			if ($db['DaysPastDue'] >0)
			{
				$pi .= table_row($db, $ik);
			}
			else
			{
				$ci .= table_row($db, $ik);
			}
			if ($curLocNo != $db['LocNo'] && ($pi !='' || $ci != ''))
			{
				$html .= table_hd($x['Loc'] = $db['LastName'] . "<BR>" . $db[Location], '', '', count($ik));
				if ($pi != '')
				{
					$p['pd'] = 'Past Due Invoices';
					$html .= table_hd($p, '', '#red', count($ik));
					$html .= table_hd($ik, $ik, '#green');
					$html .= $pi;
				}
				if ($ci != '')
				{
					$c['ci'] = 'Current Invoices';
					$html .= table_hd($c, '', '#yellow', count($ik));
					$html .= table_hd($id, $ik, '#green');
					$html .= $ci;
				}

			}
		}
	}

return $noe;
}
function html_head($cl='*')
{
	$html = '<html><head><body>';
	
	if ($cl = '*')
	{		
		$html .= '<img src="/var/www/html/primelogic/PLIClogo.png">'; 
	}
	

return $html;
}
function html_foot()
{
	$html = '<a href=#>Unsubscribe</a>';

return $html;
}

function table_row($arr, $keys='')
{

	if (!$keys)
	{
		print_r($keys);
		$keys = array_keys($arr);
		print_r($keys);
		//exit;
	}
	$row = '<tr>';
	foreach ($keys as $num => $key)
	{
		if ($key == 'InvAmts' || $key == 'Paids')
		{
			$arr[$key] = money_format('%.2n', $arr[$key]);
		}
		$row .= "<td align=right>" . htmlentities($arr[$key]) . "</td>";
	}
	$row .= '</tr>';
return $row;
}


function table_hd($arr, $keys= '', $color = '', $colspan = '')
{
	if (!$keys)
	{
		$keys = array_keys($arr);
	}
	if (isset($color))
	{
		$col = 'color="' . $color . '"';
	}
	$row = "<tr $col>";
	foreach ($keys as $num =>$key)
	{
		if (isset($colspan))
		{
			$col2 = 'colspan="' . $colspan . '"';
		}
			
		$row .= "<td align=right $col2>" . $key . "</td>";
	}
	$row .= '</tr>';

return $row;
}

?>