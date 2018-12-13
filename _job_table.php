<?php

function job_head($hd, $key = '')
{
	$cols = count($key);
	$table = '<table class="job" ><tr style="background-color:gray;"><td colspan="' . $cols . '" align="center"><b>' . $hd['title'] . '</td></tr>' . "\r\n";
	return $table;
}

function job_title($hd, $key= '')
{
	$cols = count($key);
	if ($cols == 0)
	{
		$cols = '9';
	}
	$first = '2';
	$second = $cols - $first;
	$table ='<tr style="background-color:gray;">
		  <td colspan="' . $first . '">Job <a href=' . $_SERVER['HOSTNAME'] . '/primelogic/job_detail_report.php?Name=' . $hd['Name'] . '&Email={EMAIL}>' . $hd['Name'] . '</href></td>
		  <td colspan="' . $second . '">' . $hd['LastName'] . '</td>
		</tr>' . "\r\n";
return $table;
}

function job_foot($hd)
{
	$table = "</table>\r\n";
return $table;
}

function job_hd($key)
{
	$table = '<tr style="background-color:gray;">';
	foreach ($key as $k)
	{
		$table .= '<td>' . $k . '</td>';
	}
	$table .= '</tr>';
return $table;

}
function job_bar($key)
{
	$ct = count($key);
	$table = '<tr style="background-color:gray;"><td colspan="' . $ct . '" style="border-bottom-style: solid"></tr>';
return $table;
}

function job_bar_dot($key)
{
	$ct = count($key);
	$table = '<tr><td colspan="' . $ct . '" style="border-bottom: solid 1px black;background-color:gray;"></tr>';
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
	
	if ($row['Variance'] < 0)
	{
		$style .= 'color:red;';
	}
	if ($row['Variance'] != '0')
	{
		$style .= 'background-color:gray;"';
	}	
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
		else
		{
			$table .= '<td align="right" >' . number_format((float)$row[$k], 2, '.', '') . '</td>';
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
		else
		{
			$table .= '<td align="right" >' . number_format((float)$row[$k], 2, '.', '') . '</td>';
		}
	}
	$table .= '</tr>' . "\r\n";
return $table;
}



?>
		