<?php

function query_head($db)
{

		$table = '<table border=1><tr>';
		foreach ($db as $key=>$val)
		{
			$table .= '<td>' . $key . '</td>';
		}
		$table .= '</tr>';	
		return $table;
	
return $table;
}

function query_foot($db)
{
	$table = '</table>';
return $table;
}

function query_row($db, $reset='0')
{
		$row = '<tr>';
		foreach ($db as $key=>$val)
		{
			$row .= '<td>' . $val . '</td>';
		}
		$row .= '</tr>';
return $row;
}

function query_table($tdb)
{

	$table = query_head($tdb[0]);



	for ($i=0; $i <= count($tdb); $i++)
	{
		$table .= query_row($tdb[$i]);

	}

	
	$table .= query_foot($tdb[$i]);
return $table;
}

function _query_table($tdb, $cur=0)
{

	$table = query_head($tdb[$cur]);
	for ($i=$cur; $i <= count($tdb); $i++)
	{
		$table .= query_row($tdb[$i]);
		if ($i%10 == 0 && $i != $cur)
		{
			$table .= query_foot($tdb[$i]);
			return $table;
		}

	}
	$table .= query_foot($tdb[$i]);
return $table;
}

function csv_format($tdb)
{
	$str = '';
	foreach ($tdb as $id=> $db)
	{
		//print_r($db);
		foreach ($db as $val)
		{
			$str .= "$val,";
		}
		$str .= "\n";
	}
return $str;
	
}

function query_export($query)
{

		$res = mssql_query($query);
	//echo mssql_get_last_message();
	if ((!$res || mssql_num_rows($res) == 0))
	{
		echo "$mes";
	}

	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		$tdb[] = $db;
	}
return $tdb;
}

function query($query, $cur=0)
{
	if (strpos($query, 'ORDER BY') > 0)
	{
		$query2 = $query . " OFFSET $cur ROWS FETCH NEXT 10 ROWS ONLY ";
		$order = 1;
	}
	else
	{
		$query2 = $query;
	}
		$res = mssql_query($query2);
	echo mssql_get_last_message();
	if ((!$res || mssql_num_rows($res) == 0))
	{
		echo "$mes";
	}

	while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
	{
		$tdb[] = $db;
	}
	if (isset($order))
	{
		$table = query_table($tdb);
	}
	else
	{
		$table = _query_table($tdb, $cur);
	}

return $table;
}


function dbn_table_column($db, $var2, $dl=array())
{
	$nd = '1';
	$sql3 = "USE " . $db['DBN'] . "\r\n";
	//echo $sql3;
	mssql_query($sql3);

	$sql3 = "SELECT " . '[' . $db['ColumnName'] . ']' . " FROM " . '[' . $db['TableName'] . ']' . " WHERE " . '[' . $db['ColumnName'] . ']' . "= '$var2';";
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