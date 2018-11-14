<?php


function report($sql, $subject = '')
{
	setlocale(LC_MONETARY, 'en_US.UTF-8');

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
    $table = "<table><tr><td colspan='100'><h1>$subject</h1></td></tr>";
    while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
    {
	$row = "<tr>";
	if (!$hdr)
	{
		$head = "<tr>";
		$cushead = "<tr>";
	}	
	$CCusNo = '';
$cus = array('CusNo', 'LastName', 'phone');
	foreach ($db as $key=> $value)
	{
		if (!$hdr && !in_array($key, $cus))
		{
			$head .= "<td>$key</td>";
		
		}
		if (in_array($key, $cus))
		{
	
			//$cushead .= "<td>$key</td>";
			$cushead .= "<td>" . htmlentities($value) . "</td>";
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
		$table .= $hdr;
		$table .= "\r\n";
	}
	if ($db['CustNo'] != $CCusNo)
	{
		$table .= $cushead . "</tr>\r\n";
		//$table .= $cusrow . "</tr>\r\n";
		$CCusNo = $db['CustNo'];
		$cushead = "<tr>";
	}
	$table .= $row . "\r\n";
	unset($row);

    }
     $table .= "</table>\r\n";

	$html .= $table;
	$html .= "</body>";
return $html;
}     

