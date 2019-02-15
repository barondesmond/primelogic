<?php

function dbn_table_column($db, $var2, $dl=array())
{
	$nd = '1';
	$sql3 = "USE " . $db['DBN'] . "\r\n";
	//echo $sql3;
	mssql_query($sql3);

	$sql3 = "SELECT " . '[' . $db['ColumnName'] . ']' . "FROM " . '[' . $db['TableName'] . ']' . " WHERE " . '[' . $db['ColumnName'] . ']' . "= '$var2';";
	error_log($sql3);
			//echo "$sql3 \r\n";

	$res3 = @mssql_query($sql3);
	
		$db2 = @mssql_fetch_array($res3, MSSQL_ASSOC);
		if (isset($db2[$db['ColumnName']]) )
		{
			$dl[$db['DBN']][$db['TableName']][$db['ColumnName']] = $var2;
		}
return $dl;
}

?>