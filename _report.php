<?

function report($sql)
{

    $res = mssql_query($sql);
    $table = "<table>";
    while ($db = mssql_fetch_array($res))
    {
	$row .= "<tr>";
	if (!$hdr)
	{
		$head .= "<tr>";
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
	}
	unset($row);

    }
     $table .= "</table>";

return $table;
}     

if ($argv[1])
{
	include("_db_config.php");
	echo report($argv[1]);
}