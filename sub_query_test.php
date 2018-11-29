<?php
include("_db_config.php");

/*
searching for %job% columns where %job%  column in table = 'J-0001907'
*/
$var1 = 'job';
$var2 = 'J-0001907';

$sql = "USE Service;

SELECT
  sys.columns.name AS ColumnName,
  tables.name AS TableName, ist.TABLE_CATALOG as DBN
FROM
  sys.columns
JOIN sys.tables ON
  sys.columns.object_id = tables.object_id
  INNER JOIN INFORMATION_SCHEMA.TABLES as ist ON tables.name = ist.TABLE_NAME
WHERE
";

$sql2 = "
USE OCA;

SELECT
  sys.columns.name AS ColumnName,
  tables.name AS TableName, ist.TABLE_CATALOG as DBN
FROM
  sys.columns
JOIN sys.tables ON
  sys.columns.object_id = tables.object_id
  INNER JOIN INFORMATION_SCHEMA.TABLES as ist ON tables.name = ist.TABLE_NAME
;";

$res = @mssql_query($sql);
$dl = array();
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{

	$dl = dbn_table_column($db, $var2, $dl);

}

$res = @mssql_query($sql2);
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{

	$dl = dbn_table_column($db, $var2, $dl);

}

print_r($dl);
function dbn_table_column($db, $var2, $dl=array())
{

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

			$dl[$db['DBN']][$db['TableName']][$db['ColumnName']] = $var2;
		}
	
return $dl;
}
  ?>