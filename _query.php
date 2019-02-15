<?php

function dbn_table_column($db, $var2, $dl=array())
{
	$nd = '1';
	$sql3 = "USE " . $db['DBN'] . "\r\n";
	//echo $sql3;
	mssql_query($sql3);

	$sql3 = "SELECT COUNT(*) as total FROM " . $db['TableName'] . " WHERE " . $db['ColumnName'] . "= '$var2';";

			//echo "$sql3 \r\n";

	$res3 = @mssql_query($sql3);
	
		$db2 = @mssql_fetch_array($res3, MSSQL_ASSOC);
		if ($db2[total] > 0)
		{
			$dl[$db['DBN']][$db['TableName']][$db['ColumnName']] = $var2;
		}
return $dl;
}

?>