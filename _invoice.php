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
	$key = array('ShipName', 'ShipAddr1', 'ShipAddr2', 'ShipCSZ');
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
					$db2[] = $db[$kd];
				}
				else
				{
					$db2[] = '';
				}
				
			}
		}

		//print_r($db2);

		$row = '<tr><td width="360" align="left">' . $db2['0'] . '</td>
		<td width="90" align="right">' . $db2['1'] . '</td>
		<td width="90" align="right">' . $db2['2'] . '</td>
		<td width="90" align="right">' . $db2['3'] . '</td></tr>' . "\r\n";

	}
return $row;
}

function invoice_tax_due($db = '', $key='')
{
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

function invoice_total_due_by($db = '', $key='')
{
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
			$db2[] = $db[$k];
		}
		$db = $db2;
	}
	return invoice_row($db);
}

function invoice_blank()
{
	$db[0] = '';
	$db[1] = '';
	$db[2] = '';
	$db[3] = '';
	return invoice_row($db);
}

function invoice_init($dbs='', $db='')
{
	$key = array('Invoice', 'InvDate', 'DueDate', 'ServiceDate', 'PO');

		$dbs['Invoice'] = '000000';
		$dbs['InvDate'] = '12/12/1970';
		$dbs['DueDate'] = '12/12/12';
		$dbs['ServiceDate'] = '11/11/11';
		$dbs['PO'] = '324234';
	if ($db != '' && is_array($db))
	{
		foreach($key as $k)
		{
			if ($db[$k] != '' && $k != 'InvDate' && $k != 'DueDate')
			{
				$dbs[$k] = $db[$k];
			}
			elseif ($db[$k] != '')
			{
				$dbs[$k] = date('d/m/Y', $db[$k]);
			}
		}
	}
return $dbs;
}


function invoice_html($arrays = '')
{
	$dbs = invoice_init($dbs, $arrays[0]);
	$dbs = invoice_service_location($dbs, $arrays[0]);
	$dbs = invoice_billing($dbs, $arrays[0]);


	$html='<html><head></head><body style="margin: 0px;">
<style>
 table.first {
        color: black;
        font-family: helvetica;
        font-size: 10pt;
    }
</style>
<table class="first">
 <tr>
  <td width="200" align="left" color="grey"><BR><BR><BR><BR><BR><BR><BR>Office: 662.841.1390<BR>Email: service@plisolutions.com</td>
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

 </tr>';
 
 $html .= '<tr>
	<td width="1"></td>
	<td colspan="3" width="699">';
	
$html .='<table class="first">';
	$max = 24;
	if ($arrays != '' && is_array($arrays))
	{
		$key = array('Desc', 'Quan', 'Price', 'Amount');
		foreach ($arrays as $db)
		{
			$i++;
			$html .= invoice_row($db, $key); 
		}
		for ($j=$i; $j < $max; $j++)
		{
			$html .= invoice_blank();
		}
	}
	else
	{
		for ($i=0; $i < $max; $i++)
		{
			$html .= invoice_row();
		}
	}
	$html .= invoice_blank();

	$html .= '</table></td></tr>
	<tr>
		<td width="1"><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR></td>
		<td colspan="3" width="699">
			<table class="first">';
	$html .= invoice_tax_due();
	$html .= invoice_blank();
	$html .= invoice_total_due_by();
	$html .= '</table>';
	
	$html .= '</td></tr>';
	$thml .= '
	<tr>
		<td align="left" width="400" colspan="2">' . $dbs['billing'] . '</td>
		<td width="400" align="right" colspan="2">' . $dbs['TotalDue'] . '</td>
	</tr>';	
	$html .= '</table>
	</body></html>';

return $html;
}

function invoice($invoice = '')
{
	if ($invoice == '')
	{
		$arrays = '';
	}
	$sql = "SELECT Sales.Invoice, Sales.InvDate, Sales.EntDate, Sales.ShipName, Sales.ShipAddr1, Sales.ShipAddr2, Sales.ShipCSZ, Sales.PONum, Sales.InvAmount, Sales.DueDate, Paid, InvAmt-Paid as TotalDue, SalesLed.*, Location.*
FROM Sales
INNER JOIN Receivab ON Sales.Invoice = Receivab.Invoice
INNER JOIN Location ON Receivab.LocNo = Location.LocNo and Receivab.CustNo = Location.CustNo
INNER JOIN SalesLed ON Sales.Invoice = SalesLed.Invoice
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