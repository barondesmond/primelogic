<?php
include("_db_config.php");

if ($argv[1])
{
	echo $argv[1] . "\r\n";
	$res = mssql_query($argv[1]);
	while ($db = mssql_fetch_array($res))
	{
		print_r($db);
		if (!$hd)
		{
			foreach ($db as $key=> $value)
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