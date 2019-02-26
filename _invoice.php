<?php

function invoice_db_key($dbs = '', $db = '', $key, $dk='')
{

		$dbs[$dk] = '';
		foreach ($key as $k)
		{
			if ($k != 'City' && $k != 'State')
			{
				$dbs[$dk] .= $db[$k] . '<BR>';
			}
			else
			{
				$dbs[$dk] .= $db[$k] . ' ';
			}
		}

return $dbs;
}

function invoice_service_location($dbs='', $db='')
{
	$key = array('LocName', 'Add1', 'Add2', 'City', 'State', 'Zip');
	if ($db == '')
	{
		$dbs['loc'] = 'Lafayette Co. Chancery Clerk<BR>300 North Lamar Street<BR>PO BOX 1240<BR>Oxford MS 38555';
	}
	else
	{
		$dbs = invoice_db_key($dbs, $db, $key, 'loc');
	}

return $dbs;
}

function invoice_billing($dbs='', $db='')
{
	$key = array('BillName', 'BillAddr1', 'BillAddr2', 'BillCSZ');
	if ($db == '')
	{
		$dbs['billing'] = 'Lafayette Co. Chancery Clerk<BR>300 North Lamar Street<BR>PO BOX 1240<BR>Oxford MS 38555';
	}
	else
	{
		$dbs = invoice_db_key($dbs, $db, $key, 'billing');
	}
return $dbs;
}
function invoice_office($dbs='', $db='')
{
	if (substr($dbs['LastName'], 0, 1)  == '#')
	{
		$dbs['Office'] = 'Office: 662.841.1390<BR>Email: service@plisolutions.com';
	}
	elseif (substr($dbs['LastName'], 0, 1)  == '*')
	{
		$dbs['Office'] = 'Office: 662.841.7722<BR>Email: service@plisolutions.com';

	}
	else
	{
		$dbs['Office'] = 'Office: 662.841.1390<BR>Email: service@plisolutions.com';
	}
return $dbs;
}
function invoice_row($db = '', $key = '')
{
	if ($db == '')
	{
		$row = '<tr><td width="360" align="left">Service Labor Lead Tech</td>
		<td width="90" align="right">2.5</td>
		<td width="90" align="right">79.50</td>
		<td width="90" align="right">198.75</td>
		</tr>' . "\r\n";
	}
	else
	{
		if ($key == '')
		{
			$db2 = array_values($db);
		}
		else
		{
			foreach ($key as $kd)
			{
				if ($db[$kd] != '0')
				{
					if (is_numeric($db[$kd]))
					{				
						$db2[] = number_format($db[$kd], '2');
					}
					else
					{
						$db2[] = $db[$kd];
					}
				}
				else
				{
					$db2[] = '';
				}
				
			}
		}

		$row = '<tr><td width="360" align="left">' . $db2['0'] . '</td>
		<td width="90" align="right">' . $db2['1'] . '</td>
		<td width="110" align="right">' . $db2['2'] . '</td>
		<td width="90" align="right">' . $db2['3'] . '</td></tr>' . "\r\n";

	}
return $row;
}

//Not Used
function invoice_tax_due($db = '')
{
	$key = array('', '','', 'Tax1');

	if ($db== '')
	{
		$db[0] = '';
		$db[1] = '';
		$db[2] = '';
		$db[3] = '$0.00';
	}
	if (is_array($key) && is_array($db))
	{
		foreach ($key as $k)
		{
			$db2[] = $db[$k];
		}
		$db = $db2;
	}
	return invoice_row($db);
}

///Not Used
function invoice_total_due_by($db = '')
{
	$key = array('', '', 'DueDate', 'InvAmt');

	if ($db== '')
	{
		$db[0] = '';
		$db[1] = '';
		$db[2] = '10/28/2013';
		$db[3] = '$313.75';
	}
	if (is_array($key) && is_array($db))
	{
		foreach ($key as $k)
		{
			if ($k == 'InvAmt')
			{
				$db2[] = money_format('%.2n', $db[$k]);
			}
			else
			{
				$db2[] = $db[$k];
			}
		}
		$db = $db2;
	}
	return invoice_row($db);
}

function invoice_blank()
{
	$db[0] = ' ';
	$db[1] = ' ';
	$db[2] = ' ';
	$db[3] = ' ';
	return invoice_row($db);
}

function invoice_init($dbs='', $db='')
{

	$key = array('Invoice', 'InvDate', 'DueDate', 'ServiceDate', 'PONum', 'InvAmt', 'LastName', 'Tax', 'AmtCharge');
		$dbs['LastName'] = '#Lafayette Co. CHancery Clerk';
		$dbs['Invoice'] = '000000';
		$dbs['InvDate'] = '12/12/1970';
		$dbs['DueDate'] = '12/12/12';
		$dbs['ServiceDate'] = '';
		$dbs['PONum'] = '324234';
	if ($db != '' && is_array($db))
	{
		foreach($key as $k)
		{
			if ($db[$k] != '')
			{
				$dbs[$k] = $db[$k];
			}
		}
	}
	$dbs = invoice_service_location($dbs, $db);
	$dbs = invoice_billing($dbs, $db);
	$dbs = invoice_office($dbs, $db);

return $dbs;
}

function invoice_toptable($dbs)
{
$html = '<table class="toptable">
 <tr>
  <td width="200" align="left" color="grey"><BR><BR><BR><BR><BR><BR><BR>' . $dbs['Office'] . '</td>
  <td width="200" align="center"><BR><BR><BR><BR><BR></td>
  <td width="250" align="right">
  <table cellpadding="4" class="first">
  <tr><td>' . $dbs['Invoice'] . '</td></tr>
  <tr><td>' . $dbs['InvDate'] . '</td></tr>
  <tr><td>' . $dbs['DueDate'] . '</td></tr>
  <tr><td>' . $dbs['ServiceDate'] . '</td></tr>
  <tr><td>' . $dbs['PONum'] . '</td></tr></table></td>
  <td width="50" align="right"><BR><BR><BR><BR><BR></td>
 </tr>
 <tr>
  <td width="50" align="center"></td>
  <td width="350" ><color="grey">Billing Address:</color><BR><b>' . $dbs['billing'] . '</b><BR><BR></td>
  <td width="90" align="left"></td>
  <td width="210" align="left">Service Location<BR><b>' . $dbs['loc'] . '</b></td>

 </tr></table>';
return $html;
}

function invoice_header($dbs)
{

	$html='<html><head></head><body style="margin: 0px;">
<style>
 table.toptable {
        color: black;
        font-family: helvetica;
        font-size: 10pt;
		table-layout: fixed;
		max-width: 750px;
		max-height: 250px;
		border-collapse: collapse;
		border-spacing: 0;

    }
 table.middletable {
        color: black;
        font-family: helvetica;
        font-size: 10pt;
		table-layout: fixed;
		max-width: 650px;
		max-height: 250px;
		border-collapse: collapse;
		border-spacing: 0;
		width: 750;
		height: 250;
    }
 table.bottomtable {
        color: black;
        font-family: helvetica;
        font-size: 9pt;
		table-layout: fixed;
		max-width: 450px;
		max-height: 350px;
		border-collapse: collapse;
		border-spacing: 0;
		width: 450;
		height: 350;
    }
</style>';
return $html;
}

function invoice_middletable($arrays)
{
		
 
	
	$html ='<table class="middletable">';
	$max = 20;
	//print_r($arrays);
	if ($arrays != '' && is_array($arrays))
	{
		$i=0;
		$key = array('Desc', 'Quan', 'Price', 'Amount');
		foreach ($arrays as $db)
		{
			$i++;
			$html .= invoice_row($db, $key); 
			//print_r($db);
			//exit;
		}
		for ($j=$i; $j < $max; $j++)
		{
			//$html .= invoice_blank();
		}
	}
	else
	{
		for ($i=0; $i < $max; $i++)
		{
			$html .= invoice_row();
		}
	}
	$html .= '</table>';
return $html;
}
function invoice_bottomtable($dbs)
{
	$html = '<table class="bottomtable">
	<tr>
		<td width="400">' . $dbs['billing'] . '</td>
		<td width="200"> </td>
		<td width="150">' . $dbs['InvAmt'] . '</td>
	</tr>
	<tr>
		<td width="400"> </td>
		<td width="200">' . $dbs['Invoice'] . ' ' . $dbs['InvDate'] . ' ' . $dbs['InvAmt']. '</td>
		<td width="150"> </td>
	</tr>
	</table>';
return $html;
}

function invoice_footer()
{
	$html = '</body></html>';

return $html;
}

function invoice_html($arrays = '')
{
	//print_r($arrays);
	print_r($arrays[0]);
	exit;
	$dbs = invoice_init($dbs, $arrays[0]);
	$html .= invoice_header($dbs);
	$html .= invoice_toptable($dbs);
	$html .= invoice_middletable($arrays);
	$html .= invoice_bottomtable($dbs);
	$html .= invoice_footer();
	
return $html;
}

function invoice($invoice = '')
{
	if ($invoice == '')
	{
		//$arrays = '';
	}
	$sql = "SELECT Sales.Invoice, CONVERT(varchar(10), Sales.InvDate, 101) as InvDate, CONVERT(varchar(10), Sales.EntDate, 101) as EntDate, 
	Customer.LastName as BillName, Customer.Add1 as BillAddr1, Customer.Add2 as BillAddr2, CONCAT(Customer.City, ' ' , Customer.State, ' ' , Customer.Zip) as BillCSZ,

	Sales.ShipName, Sales.ShipAddr1, Sales.ShipAddr2, Sales.ShipCSZ, Sales.PONum, Sales.InvAmount, CONVERT(varchar(10), Sales.DueDate, 101) as DueDate, Paid, InvAmt, Tax1, SalesLed.*, Location.*, CONVERT(varchar(10), Dispatch.Complete, 101) as ServiceDate, AmtCharge-InvAmount as Tax
FROM Sales
INNER JOIN Customer ON Sales.CustNo = Customer.CustNo
INNER JOIN Receivab ON Sales.Invoice = Receivab.Invoice
INNER JOIN Location ON Receivab.LocNo = Location.LocNo and Receivab.CustNo = Location.CustNo
INNER JOIN SalesLed ON Sales.Invoice = SalesLed.Invoice
LEFT JOIN Dispatch ON Sales.Invoice = Dispatch.Invoice
WHERE Sales.Invoice = '$invoice' and SalesLed.NoPrint = '0'";
	if ($invoice != '')
	{
		//echo $Invoice;
		//echo $sql;
		$res = mssql_query($sql);
		while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
		{
			$arrays[] = $db;
		}
	}
return invoice_html($arrays);

}

?>