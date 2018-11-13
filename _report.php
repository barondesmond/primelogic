<?php


function report($sql, $subject = '')
{
	$html = "<html>
<head>
  <title>$subject</title>
</head>
<body>";
	$table = '';
	$row = '';
	$head = '';
	$hdr = '';

    $res = mssql_query($sql);
    $table = "<table>";
    while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
    {
	$row = "<tr>";
	if (!$hdr)
	{
		$head = "<tr>";
	}	

	foreach ($db as $key=> $value)
	{
		if (!$hdr)
		{
			$head .= "<td>$key</td>";
		}	
		$row .= "<td>" . htmlentities($value) . "</td>";
	}
	if (!$hdr)
	{
		$head .= "</tr>";
	}
	$row .= "</tr>";
	if ($head && !$hdr)
	{
		$hdr = $head;
		$table .= $hdr;
		$table .= "\r\n";
	}
	$table .= $row . "\r\n";
	unset($row);

    }
     $table .= "</table>\r\n";

	$html .= $table;
	$html .= "</body>";
return $html;
}     

