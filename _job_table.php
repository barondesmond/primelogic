<?php

setlocale(LC_MONETARY, 'en_US');

function job_disclaimer($cols)
{
	$table = '<tr><td colspan="' . $cols  .'" align="center"><b>
CONFIDENTIALITY DISCLAIMER: Privileged Confidential information is contained in this message and or attachments and is and exempt<BR>
from disclosure under applicable laws. Access to this e-mail by anyone other than the intended recipients, whether directly or by <BR>
indirect means, is unauthorized. If you do not consent to internet e-mail messages of this kind (or) do not agree to this <BR>
Non-Disclosure Agreement please notify us immediately.
</b></td></tr>';

return $table;
}

function job_head($hd, $key = '')
{
	$cols = count($key);
	$table = '     <style> 
            table, th, td {
			    border-collapse:collapse;
                text-align:center; 
            } 
            th, td { 
                padding: 20px; 
                background-color:none; 
            } 
			</style>
               ';
	$table .= '<table>';
	$table .=  job_disclaimer($cols);

	$table .= '<tr><td colspan="' . $cols . '" align="center"><b>' . $hd['title'] . '<BR>Report Generated ' . date("m/d/Y", time()) . '</td></tr>' . "\r\n";
	return $table;
}

function job_title($hd, $key= '', $color='grey')
{
	$cols = count($key);
	if ($cols == 0)
	{
		$cols = '9';
	}
	$first = '2';
	$second = $cols - $first;
	$table ='<tr style="background-color:' . $color . ';">
		  <td colspan="' . $first . '">Job <a href=' . $_SERVER['HOSTNAME'] . '/primelogic/job_detail_report.php?Name=' . $hd['Name'] . '&Email={EMAIL}>' . $hd['Name'] . '</href></td>
		  <td colspan="' . $second . '">' . $hd['LastName'] . '</td>
		</tr>' . "\r\n";
return $table;
}

function job_summary_title($hd, $key= '', $color='white')
{
	
	$table = job_title($hd, $key, $color);

return $table;
}
function job_foot($hd)
{
	$table = "</table>\r\n";
return $table;
}

function job_summary_hd($key, $color='white')
{
	return job_hd($key, $color);
}
function job_hd($key, $color='gray')
{
	$table = '<tr style="background-color:' . $color . ';">';
	foreach ($key as $k)
	{
		$table .= '<td>' . $k . '</td>';
	}
	$table .= '</tr>';
return $table;

}

function job_summary_bar($key, $color='white')
{
	return job_bar($key, $color);
}

function job_bar($key, $color='grey')
{
	$ct = count($key);
	$table = '<tr style="background-color:' . $color . ';"><td colspan="' . $ct . '" style="border-bottom-style: solid"></tr>';
return $table;
}

function job_summary_bar_dot($key, $color='white')
{
	return job_bar_dot($key, $color);
}

function job_bar_dot($key, $color='gray')
{
	$ct = count($key);
	$table = '<tr><td colspan="' . $ct . '" style="border-bottom: solid 1px black;background-color:' . $color . ';"></tr>';
return $table;
}	
/*	if ($row['Variance'] != '0')
	{
		$style = ' style="';
	}
	if ($row['Variance'] < 0)
	{
		$style .= 'color:red;';
	}
	if ($row['Variance'] != '0')
	{
		$style .= 'background-color:gray;"';
	}	
	*/

function job_row_detail_total($row, $key)
{

	$style = ' style="';
	$style .= 'background-color:gray;"';

		
	$table = '<tr ' . $style . '>';

	foreach ($key as $k)
	{
		if ($k == 'Document')
		{
			$table .= '<td align="left" >' . $row[$k] . '</td>';
		}
		elseif ($k == 'Type')
		{
			 $table .= '<td align="left" >';
			 if ($row['TransDate'] != '') 
		     {
				 $table .= date("m/d/Y", strtotime($row['TransDate']));
			 }	 
			 $table .=	 ' ' . $row[$k] . '</td>';
		}
		elseif ($k == 'Act Units')
		{
				$table .= '<td align="right" >' . number_format($row[$k], 2) . '</td>';
		}
		elseif ($k == 'Variance')
		{
			if ($row[$k] < 0)
			{
				$table .= '<td align="right" style="color:red">' . money_format('%.2n', $row[$k]) . '</td>';
			}
			else
			{
				$table .= '<td align="right" >' . money_format('%.2n', $row[$k]) . '</td>';
			}
		}
		else
		{
			$table .= '<td align="right" >' . money_format('%.2n', $row[$k]) . '</td>';
		}
	}
	$table .= '</tr>' . "\r\n";
return $table;
}


function job_row($row, $key)
{

	$table = '<tr ' . $style . '>';

	foreach ($key as $k)
	{
		if ($k == 'Document')
		{
			$table .= '<td align="left" >' . $row[$k] . '</td>';
		}
		elseif ($k == 'Type')
		{
			 $table .= '<td align="left" >';
			 if ($row['TransDate'] != '') 
		     {
				 $table .= date("m/d/Y", strtotime($row['TransDate']));
			 }	 
			 $table .=	 ' ' . $row[$k] . '</td>';
		}
		elseif ($k == 'Act Units' && $row[$k] != '0.00' && $row[$k] != '0' && $row[$k] != '')
		{
			$table .= '<td align="right" >' . number_format($row[$k], '2') . '</td>';
		}
		elseif ($k == 'Act Units')
		{
			$table .= '<td algin="right" ></td>';
		}
		elseif ($row[$k] != '0.00' && $row[$k] != '0' && $row[$k] != '')
		{
			//echo $k;
			//print_r($row);

			$table .= '<td align="right" >' . money_format('%.2n', $row[$k]) . '</td>';
		}
		else
		{
			$table .= '<td algin="right" ></td>';
		}
	}
	$table .= '</tr>' . "\r\n";
return $table;
}



?>
		