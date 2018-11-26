<?php

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
				$db2[] = $db[$key];
			}
		}

		$row = '<tr><td width="360" align="left">' . $db['0'] . '</td>
		<td width="90" align="right">' . $db2['1'] . '</td>
		<td width="90" align="right">' . $db2['3'] . '</td>
		<td width="90" align="right">' . $db2['4'] . '</td></tr>' . "\r\n";

	}
return $row;
}

function invoice_tax_due($db = '')
{
	$db[0] = '';
	$db[1] = '';
	$db[2] = '';
	$db[3] = '';
	$db[4] = '$0.00';
	return invoice_row($db);
}

function invoice_total_due_by($db = '')
{
	$db[0] = '';
	$db[1] = '';
	$db[2] = '';
	$db[3] = '';
	$db[4] = '$0.00';
	return invoice_row($db);
}

function invoice_blank()
{
	$db[0] = '';
	$db[1] = '';
	$db[2] = '';
	$db[3] = '';
	$db[4] = '';
	return invoice_row($db);
}

function invoice_html($arrays = '')
{
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
  <td width="250" align="right"><table cellpadding="4" class="first"><tr><td>0000000</td></tr><tr><td>12/12/1970</td></tr><tr><td>12/12/12</td></tr><tr><td>11/11/11</td></tr><tr><td>324234</td></tr></table></td>
  <td width="50" align="right"><BR><BR><BR><BR><BR></td>
 </tr>
 <tr>
  <td width="50" align="center"></td>
  <td width="350" ><color="grey">Billing Address:</color><BR><b>Lafayette Co. Chancery Clerk<BR>300 North Lama Street<BR>PO BOX 1240<BR>Oxford MS 38655<BR></b><BR><BR></td>
  <td width="90" align="left"></td>
  <td width="210" align="left">Service Location<BR><b>Lafayette Co. Chancery Clerk<BR>300 North Lamar Street<BR>PO BOX 1240<BR>Oxford MS 38555</b></td>

 </tr>';
 
 $html .= '<tr>
	<td width="1"></td>
	<td colspan="3" width="699">';
	
$html .='<table class="first">';
	$max = 24;
	if ($arrays != '' && is_array($arrays))
	{
		foreach ($arrays as $db)
		{
			$i++;
			$html .= invoice_row($db); 
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
	$html .= invoice_total_due_by();
	$html .= '</table>';
	
	$html .= '</td></tr>';
	$html .= '</table>
	</body></html>';

return $html;
}

function invoice($invoice = '')
{

return invoice_html($invoice);

}

?>