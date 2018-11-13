<?php

function report($sql)
{
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
		$row .= "<td>$value</td>";
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

return $table;
}     

if ($argv[1])
{
	include("_db_config.php");
	
	$rep =  report($argv[1]);
	if ($argv[2])
	{
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = "To: $argv[2] <$argv[2]>";
		$headers[] = 'From: baron@desmond.com';
		$headers[] = 'Bcc: baron@desmond.com';
		mail($argv[2], $argv[1], $rep, implode("\r\n", $headers));

	}
	else
	{
		echo $rep;
	}
}