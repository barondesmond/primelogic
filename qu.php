<?php
include("_db_config.php");

if ($argv[1])
{
	$x=0;
	echo $argv[1] . "\r\n";
	$res = mssql_query($argv[1]);
	while ($db = mssql_fetch_array($res))
	{
		//print_r($db);
		if (!isset($hd))
		{
			foreach ($db as $key=> $value)
			{
				if (is_numeric($key))
				{

					$hd[] = $value;
					$x++;
					echo "$value,";
				}
			}		
			echo "\r\n";
		
		}
		if (!isset($argv[2]))
		{
			for ($i=0;$i< count($hd); $i++)
			{
				echo "$db[$i],";
			}
			echo "\r\n";
		}
	}

}	

?>