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
  sys.columns.name LIKE '%job%';";

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
WHERE
  sys.columns.name LIKE '%job%';";

$res = @mssql_query($sql);
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
	$sql3 = "USE " . $db['DBN'] . ";SELECT * FROM " . $db['TableName'] . " WHERE " . $db['ColumnName'] . "= '$var2'";

	$res3 = @mssql_query($sql3);
	if (@mssql_num_rows($res3)>0)
	{
		while ($db2 = @mssql_fetch_assoc($res3, MSSQL_ASSOC))
		{
			echo "$sql3 \r\n";
			print_r($db2);
		}
	}
}

$res = @mssql_query($sql2);

while ($db = @mssql_fetch_array($res, MSSQL_ASSOC))
{
	$sql3 = "USE " . $db['DBN'] . ";SELECT * FROM " . $db['TableName'] . " WHERE " . $db['ColumnName'] . "= '$var2'";

	$res3 = @mssql_query($sql3);
	if (@mssql_num_rows($res3)>0)
	{
		while ($db2 = @mssql_fetch_assoc($res3, MSSQL_ASSOC))
		{
			echo "$sql3 \r\n";
			print_r($db2);
		}
	}
}
  ?>