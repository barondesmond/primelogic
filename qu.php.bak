<?php
include("_db_config.php");

if ($argv[1])
{
	$x=0;
	echo $argv[1] . "\r\n";
	$ar = explode(';', $argv[1]);
	if (count($ar)>1)
	{
		$res = mssql_query($ar[0]);
		$argv[1] = $ar[1];
	}
	$res = mssql_query($argv[1]);
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