<?php

function job_head($hd)
{
	$table = '<table class="job" ><tr><td colspan="7"><b>' . $hd['title'] . '</td></tr>' . "\r\n";
	return $table;
}

function job_title($hd)
{
	$table ='<tr>
		  <td colspan="2">Job ' . $hd['Name'] . '</td>
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
	
function job_row($row, $key)
{
	$table = '<tr>';
	foreach ($key as $k)
	{
		$table .= '<td>' . $row[$k] . '</td>';
	}
	$table .= '</tr>';
return $table;
}



?>
		