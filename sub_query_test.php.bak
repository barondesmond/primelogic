<?php
include("_db_config.php");

/*
searching for %job% columns where %job%  column in table = 'J-0001907'
*/

$var1 = 'J-0001907';
//define('SHOW_DATA', '');

if (isset($argv[1]))
{
	$var1 = $argv[1];
}
if (isset($argv[2]) && isset($argv[1]))
{
	define('SHOW_DATA', $argv[2]);
}
elseif (isset($argv[1]))
{
	define('SHOW_DATA', '');
}
if (isset($_GET['val']))
{
	$var1 = $_GET['val'];
}
if (isset($_GET['SHOW_DATA']))
{
	define('SHOW_DATA', $_GET['SHOW_DATA']);
}
elseif (isset($_GET['val']))
{
	define('SHOW_DATA', '');
}

$sql = "USE Service;

SELECT
  sys.columns.name AS ColumnName,
  tables.name AS TableName, ist.TABLE_CATALOG as DBN
FROM
  sys.columns
JOIN sys.tables ON
  sys.columns.object_id = tables.object_id
  INNER JOIN INFORMATION_SCHEMA.TABLES as ist ON tables.name = ist.TABLE_NAME

";


$res = @mssql_query($sql);
$dl = array();
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{

	$dl = dbn_table_column($db, $var1, $dl);

}


foreach ($dl as $k1 => $db2)
{
	echo "DB, Table, Column, Value<BR>\r\n";
	foreach ($db2 as $k2 => $db3)
	{
		foreach ($db3 as $k3 => $val)
		{
			echo "$k1, $k2, $k3 = $val <BR>\r\n";
		}
	}

}

function show_data($db, $nd='')
{
static $hd;
	if ($nd != '')
	{
		unset($hd);
		echo "<BR><BR>\r\n\r\n";

	}
foreach ($db as $k => $v)
{
	//echo "DB, Table, Column, Value<BR>\r\n";
	if (!$hd)
	{
		$hdr .= "$k, ";
	}
	$vl .= "$v, ";
}
if (!isset($hd))
{
	echo "$hdr <BR>\r\n";
	$hd = '1';
}
echo $vl . " <BR>\r\n";

}

function dbn_table_column($db, $var2, $dl=array())
{
	$nd = '1';
	$sql3 = "USE " . $db['DBN'] . "\r\n";
	//echo $sql3;
	mssql_query($sql3);

	$sql3 = "SELECT * FROM " . $db['TableName'] . " WHERE " . $db['ColumnName'] . "= '$var2';";

			//echo "$sql3 \r\n";

	$res3 = @mssql_query($sql3);
	
		while ($db2 = @mssql_fetch_array($res3, MSSQL_ASSOC))
		{
			//echo "$sql3 \r\n";
			//print_r($db2);
			if (SHOW_DATA != '')
			{
				if ($nd != '')
				{
					show_data($db, $nd);
				}
				show_data($db2, $nd);
				$nd = '';
			}

			$dl[$db['DBN']][$db['TableName']][$db['ColumnName']] = $var2;
		}
	
return $dl;
}
  ?>