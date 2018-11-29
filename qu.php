<?php
include("_db_config.php");

if ($argv[1])
{
	$res = mssql_query($argv[1]);
	$x=0;
	while ($db = mssql_fetch_array($res))
	{
		//print_r($db);
		if (!isset($hd))
		{
			if ($argv[1])
			{
				print_r($db);
			}
			foreach ($db as $key=> $value)
			{
				if (is_numeric($key))
				{

					$hd[$x] = $value;
					$x++;
					echo "$value,";
				}
			}		
			echo "\r\n";
		
		}
	}

}	

?>