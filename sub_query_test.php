<?php
include("_db_config.'php");

/*
searching for %job% columns where %job%  column in table = 'J-0001907'
*/
$var1 = 'job';
$var2 = 'J-0001907';

$sql = "SELECT
  sys.columns.name AS ColumnName,
  tables.name AS TableName
FROM
  sys.columns
JOIN sys.tables ON
  sys.columns.object_id = tables.object_id
WHERE
  sys.columns.name LIKE '%$var1%'";
echo $sql;
$res = mssql_query($sql);
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
	$sql2 = "USE Service; 
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES
    WHERE TABLE_NAME = '$db['TableName'])
BEGIN 
  SELECT * FROM $db['TableName'] WHERE $db['ColumnName'] = '$var2'
END

ELSE
BEGIN
  USE OCA
  SELECT * FROM $db['TableName'] WHERE $db['ColumnName'] = '$var2'
END;";

	echo $sql2;
	$res2 = mssql_query($sql2);
	if (mssql_num_rows($res2)>0)
	{
		while ($db =mssql_fetch_assoc($res2, MSSQL_ASSOC))
		{
			print_r($db);
		}
	}
}

  ?>