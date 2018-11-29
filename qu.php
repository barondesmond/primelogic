<?php
include("_db_query.php");

if ($argv[1])
{
	$res = db_query($sql);
	while ($db = mssql_fetch_array($res))
	{
		if (!$hd)
		{
			foreach ($db, $key=> $value)
			{
				if (is_numeric($key))
				{
					$hd[] = $value;
					echo "$value,";
				}
			}		
			echo "\r\n";
		$hd = '1';
		}
		for ($i=0;$i< count($hd); $i++)
		{
			echo "$db[$i],";
		}
		echo "\r\n";

	}

}	

?>