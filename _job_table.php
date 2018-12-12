<?php

function job_head($hd)
{
	$table = '<table class="job" ><tr><td colspan="7" align="center"><b>' . $hd['title'] . '</td></tr>' . "\r\n";
	return $table;
}

function job_title($hd)
{
	$table ='<tr>
		  <td colspan="2">Job <a href=' . $_SERVER['HOSTNAME'] . '/primelogic/job_detail_report.php?Name=' . $hd['Name'] . '&Email={EMAIL}>' . $hd['Name'] . '</href></td>
		  <td colspan="5">' . $hd['LastName'] . '</td>
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
	$table = '<tr>';
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
	$table = '<tr><td colspan="' . $ct . '" style="border-bottom-style: solid"></tr>';
return $table;
}

function job_bar_dot($key)
{
	$ct = count($key);
	$table = '<tr><td colspan="' . $ct . '" style="border-bottom-style: dotted"></tr>';
return $table;
}	
	
function job_row($row, $key)
{
	$table = '<tr>';
	foreach ($key as $k)
	{
		if ($k == 'Type')
		{
			$table .= '<td align="left">' . $row[$k] . '</td>';
		}
		elseif ($k == 'Document')
		{
			 $table .= '<td align="left">' . $row[$k] . ' ' . date("Y-m-d", time($row['TransDate']) . '</td>';
		}
		else
		{
			$table .= '<td align="right">' . number_format((float)$row[$k], 2, '.', '') . '</td>';
		}
	}
	$table .= '</tr>';
return $table;
}



?>
		