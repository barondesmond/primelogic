<?php

function finchg($CustNo, $day1='0', $day2 = '-1096')
{

	$sql = "SELECT Invoice, '' as JB, Dept, '' as Terms, CONVERT(varchar(10), InvDate, 101) as InvDates, CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts FROM Receivab WHERE CustNo=" . $CustNo . " and Type = 'F'
	and InvDate < DATEADD(DD, " . $day1 . ", getdate()) and InvDate > DATEADD(DD, " . $day2 . ", getdate()) and Paid != InvAmt
	ORDER BY InvDate DESC;";
	
	
	//exit;
	$res = mssql_query($sql);

	$table = '';
	$row = '';
	$head = '';
	$hdr = '';
	if (mssql_num_rows($res) == 0)
	{
		return '';
	}
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

$res2 = mssql_query($sql2);
$db = mssql_fetch_array($res2);

$sql = "SELECT Customer.CustNo, Sales.Invoice, CASE WHEN Sales.JobNumber != '' THEN Sales.JobNumber ELSE Sales.Dispatch END  as JobDispatch, CONCAT(Customer.LastName, '<BR>', ISNULL(phone1, phone2)) as LastName , Sales.Dept, Terms, CONVERT(varchar(10), Sales.DueDate, 101) as DueDates , CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts  
FROM Sales, Receivab, Customer
WHERE Sales.Invoice = Receivab.Invoice and Customer.CustNo=Sales.CustNo 
and DueDate < DATEADD(DD, " . $day1 . ", getdate()) and DueDate > DATEADD(DD, " . $day2 . ", getdate()) and PaidOff is NULL and Paid != InvAmt $empsql $deptsql
ORDER BY Sales.CustNo ASC;";



	setlocale(LC_MONETARY, 'en_US.UTF-8');

$subject = "Ar Report " . $day . $day2 . " $emp $dept " . money_format('%.2n', $db[Amt]);
$subject2 = "<td align=left colspan'3'><h1>Ar Report " . $day .  $day2 . " $emp $dept " . "</H1></td><td align=right colspan='4'><h1>" . money_format('%.2n', $db[Amt]) . "</h1></td>";



$html = report($sql, $subject2, $day1, $day2);

email_report($email, $subject, $html);

return $html;
}



function location_basis($Customer = '')
{

	//$noemail = array('');
	$curCustNo = '';
	$curEmailer = '';
	$curLocNo = '';
	$html = '';
	$ik = array('Invoice', 'JobDispatch', 'Dept', 'Terms', 'DueDates', 'DaysPastDue', 'Paids', 'InvAmts');
	$ci = '';
	$pi = '';
	$noe = '';
	for ($i=0; $i < count($ik); $i++)
	{
		$pt[$ik[$i]] = '';
		$ct[$ik[$i]] = '';
	}

	if ($Customer != '')
	{
		$sqlcus = " and Customer.CustNo = '$Customer' ";
	}
$sql = "
SELECT  Customer.CustNo, Location.LocNo, Customer.LastName, Location.LocName, 
 Case
 WHEN (Email LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email) = 0) and (EmailTasks1 = '2' or EmailTasks1 = '255')  THEN Email
 WHEN (Email2 LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email2) = 0) and (EmailTasks2 = '2' or EmailTasks2 = '255') THEN Email2
 WHEN (Email3 LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email3) = 0) and (EmailTasks3 = '2' or EmailTasks3 = '255') THEN Email3
 WHEN (Email4 LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email4) = 0) and (EmailTasks4 = '2' or EmailTasks4 = '255') THEN Email4
 WHEN (Email5 LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email5) = 0) and (EmailTasks5 = '2' or EmailTasks5 = '255') THEN Email5
 WHEN (Email6 LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email6) = 0) and (EmailTasks6 = '2' or EmailTasks6 = '255') THEN Email6
 ELSE 'No Email' 
 END
 as Emailer, Receivab.Invoice, CASE WHEN Sales.JobNumber != '' THEN Sales.JobNumber ELSE Dispatch END as JobDispatch, Sales.Dept, Terms, CONVERT(varchar(10), Sales.DueDate, 101) as DueDates, 
 DATEDIFF ( dd , DueDate , getdate() ) as DaysPastDue, CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts,
 Case WHEN (Email LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email) = 0) and (EmailTasks1 = '2' or EmailTasks1 = '255')  THEN Email ELSE '' END as Email1,
 Case WHEN (Email2 LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email2) = 0) and (EmailTasks2 = '2' or EmailTasks2 = '255')  THEN Email2 ELSE '' END as Email2,
 Case WHEN (Email3 LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email3) = 0) and (EmailTasks3 = '2' or EmailTasks3 = '255')  THEN Email3 ELSE '' END as Email3,
 Case WHEN (Email4 LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email4) = 0) and (EmailTasks4 = '2' or EmailTasks4 = '255')  THEN Email4 ELSE '' END as Email4,
 Case WHEN (Email5 LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email5) = 0) and (EmailTasks5 = '2' or EmailTasks5 = '255')  THEN Email5 ELSE '' END as Email5,
 Case WHEN (Email6 LIKE '%_@__%.__%' AND PATINDEX('%[^A-z0-9._-]%@%.%',Email) = 6) and (EmailTasks6 = '2' or EmailTasks2 = '255')  THEN Email6 ELSE '' END as Email6


  
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
$sqlcus
ORDER BY Customer.CustNo, Emailer, Location.LocNo;";

$res = mssql_query($sql);

	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		if ($db['Emailer'] == 'No Email')
		{
	
			$no = array('LastName', 'LocNo');

			if ($noe == '')
			{
				$noe = table_hd($db, $no);
			}
			if (!isset($y[$db['CustNo']][$db['LocNo']]))
			{
				$noe .= table_row($db, $no);
				$y[$db['CustNo']][$db['LocNo']] = $db;
			}
		}
		else
		{	
			//print_r($db);

			if ($db['CustNo'] != $curCustNo || ($db['CustNo'] == $curCustNo && $db['Emailer'] != $curEmailer))
			{
				//queue email if exists
				//start email
				//logo
				//finance charge
				if ($html != '')
				{
					$html .= '</table>';
					$html .= html_foot();
					
					$i++;
					if (EMAIL_SEND != '')
					{
						email_report(EMAIL_SEND, "Invoice $curEmailer $curCustNo $curLocNo", $html, $ll['filename'], $ll['cid'], $ll['name'], $pdf[$curCustNo][$curEmailer]);
					}
					else
					{
						//spam users
						invoice_email_report($curdb, $curEmailer, $html, $ll, $pdf[$curCustNo][$curEmailer]);
					}
					unset($pdf[$curCustNo][$curEmailer]);
					unset($html);
					unset($ll);
					$curCustNo = '';
					$curLocNo = '';
					$curEmailer = '';
					unset($loc);
					unset($x);

				}
				if ($curCustNo == '')
				{
					$finchrg = finchg($db['CustNo']);

					$html = html_head() . '<table width="%100">';
					$ll = location_logo($db['LastName']);

					if ($finchrg != '')
					{

						$t['fnchg'] = 'Finance Charges';
						$html .= table_hd($t, '', '', count($ik));				
						$html .= "\r\n";
					}
					$loc = '<b>' . $db['LastName'] .'</b>' . "<BR>Location: " . $db['LocName'];
					$x[$loc] = $loc;

					//unset($t);					
				}
				$curCustNo = $db['CustNo'];
				$curEmailer = $db['Emailer'];
				//$curLocNo = $db['LocNo'];
				$curdb = $db;

			}
	
			if ($db['DaysPastDue'] >0)
			{
				$pi .= table_row($db, $ik);
				$pt['DaysPastDue'] = 'Total Past Due';
				$pt['InvAmts'] = $pt['InvAmts'] + $db['InvAmts'] - $db['Paids'];
				$pdf[$curCustNo][$curLocNo][$curEmailer] = pdf_input($db['Invoice']);
			}
			else
			{
				$ci .= table_row($db, $ik);
				$ct['InvAmts'] = $ct['InvAmts'] + $db['InvAmts'] - $db['Paids'];
				$ct['DaysPastDue'] = 'Total Current Due';
				$pdf[$curCustNo][$curEmailer] = pdf_input($db['Invoice']);
				
			}
			if (($curLocNo != $db['LocNo'] && ($pi !='' || $ci != '')) && $db['CustNo'] == $curCustNo)
			{
	
				$html .= table_hd($x, $x, '', count($ik));
				if ($pi != '')
				{
					$p['Past Due Invoices'] = 'Past Due Invoices';
					$html .= table_hd($p, $p, 'red', count($ik));
					$html .= table_hd($ik, $ik, '#b3b3b3');
					$html .= $pi;
					$html .= '<tr><td colspan="' . count($ik) . '"><div style="border: solid 0 #060; border-top-width:2px; "></td></tr>';

					$html .= table_row($pt, $ik);
					$pi = '';
					$pt['InvAmts'] = '0';

				}
				if ($ci != '')
				{
					$c['Current Invoices'] = 'Current Invoices';
					$html .= table_hd($c, $c, '#4d7db3', count($ik));
					$html .= table_hd($ik, $ik, '#b3b3b3');
					$html .= $ci;
					$html .= '<tr><td colspan="' . count($ik) . '"><div style="border: solid 0 #060; border-top-width:2px; "></td></tr>';
	
					$html .= table_row($ct, $ik);
					$ci = '';
					$ct['InvAmts'] = '0';
				}
				$curLocNo = $db['LocNo'];

			}
		}
	}

			if ($html != '')
				{
					$html .= '</table>';
					$html .= html_foot();
					
					$i++;
					if (EMAIL_SEND != '')
					{
						email_report(EMAIL_SEND, "Invoice $curEmailer $curCustNo", $html, $ll['filename'], $ll['cid'], $ll['name'], $pdf[$curCustNo][$curEmailer]);
);
					}
					else
					{

						invoice_email_report($curdb, $curEmailer, $html, $ll, $pdf[$curCustNo][$curLocNo][$curEmailer]);

						//spam users
					}
					unset($pdf[$curCustNo][$curLocNo][$curEmailer]);
					unset($html);
					unset($ll);
					$curCustNo = '';
					$curLocNo = '';
					

				}
	
return $noe;
}
function html_head($cl='*')
{
	$html = '<html><head><body><img src="cid:my-attach" width=200>';
	
return $html;
}

function location_logo($LocName='')
{
	if (substr($LocName, 0, 1)  == '#')
	{		
		$ll['filename'] = '/var/www/html/images/PLIClogo.png';
		$ll['cid'] = 'my-attach';
		$ll['name'] = 'PLIClogo';

	}
	elseif (substr($LocName, 0, 1)  == '*')
	{		
		$ll['filename'] = '/var/www/html/images/NMT.jpg';
		$ll['cid'] = 'my-attach';
		$ll['name'] = 'NMTlogo';
	}
	else
	{
		$ll['filename'] = '/var/www/html/images/PLIS.png';
		$ll['cid'] = 'my-attach';
		$ll['name'] = 'PLISlogo';
	}
return $ll;
}
function html_foot()
{
	$html = 'Please contact Office at 662-841-1390 to no longer receive invoice email';

return $html;
}

function table_row($arr, $keys='')
{

	if (!$keys)
	{
		
		$keys = array_keys($arr);
		
		//exit;
	}
	$row = '<tr>';
	foreach ($keys as $num => $key)
	{
		if ($key == 'InvAmts' || $key == 'Paids')
		{
			setlocale(LC_MONETARY, 'en_US.UTF-8');
			$row .= "<td align=right>" . @money_format('%.2n', $arr[$key]) . "</td>";

		}
		else
		{
			$row .= "<td align=right>" . htmlentities($arr[$key]) . "</td>";
		}
	}
	$row .= '</tr>' . "\r\n";
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
		$col = 'bgcolor="' . $color . '"';
	}
	$row = "<tr $col>";
	foreach ($keys as $num =>$key)
	{
		if (isset($colspan))
		{
			$col2 = 'colspan="' . $colspan . '"';
		}
			
		$row .= "<td $col2>" . $key . "</td>";
	}
	$row .= '</tr>' . "\r\n";

return $row;
}

function invoice_email_report($dbs, $email, $html, $ll, $pdf)
{
	if (EMAIL_SEND != '')
	{
		email_report(EMAIL_SEND, "Invoice $curEmailer", $html, $ll['filename'], $ll['cid'], $ll['name'], $pdf);
	}
	else
	{
		//print_r($dbs);
		for ($i=1; $i < 7; $i++)
		{
			$var = "Email" . $i;
			if (trim($dbs[$var]) != '' && $dbs[$var] != $email && trim($email) != '')
			{
				//send email
				echo "Sending Email " . $dbs[$var] . "\r\n";
				email_report($dbs[$var], "Invoice Current and Past Due", $html, $ll['filename'], $ll['cid'], $ll['name'], $pdf);

			}
			elseif (trim($dbs[$var]) == trim($email) && trim($email) != '' && trim($dbs[$var]) != '')
			{
				echo "Origin email send ". $email . "\r\n";
				email_report($dbs[$var], "Invoice Current and Past Due", $html, $ll['filename'], $ll['cid'], $ll['name'], $pdf);

			}
		}
	}
}
?>