<?php
include("_db_config.php");
include("_query.php");
/*
searching for %job% columns where %job%  column in table = 'J-0001907'
*/

$var1 = 'J-0001907';
define('SHOW_DATA', '');

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
if (SHOW_DATA != '')
{
	show_data(array(), '1');
}	
foreach ($dl as $k1 => $db2)
{
	echo "DB, Table, Column, Value<BR>\r\n";
	foreach ($db2 as $k2 => $db3)
	{
		foreach ($db3 as $k3 => $val)
		{
			$query = "SELECT * FROM $k1.$k2 WHERE $k3 = '$val'";
			$query = urlencode($query);
			echo "$k1, $k2, $k3 = <A HREF=qu.php?query=query target=_new>$val</A> <BR>\r\n";
		}
	}

}


  ?>