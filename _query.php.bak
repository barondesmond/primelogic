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
	if (!isset($_REQUEST['cur']))
	{
		$_REQUEST['cur'] = 0;
	}
	if ($_REQUEST['cur'])
	{
		$cur = $_REQUEST['cur'];
	}
	unset($_REQUEST['cur']);
	$table = query_head($tdb[0]);
	
	for ($i=$cur; $i <= count($tdb); $i++)
	{
		$table .= query_row($tdb[$i]);
		if ($i%10 == 0 && $i != $cur)
		{
			$table .= query_foot($tdb[$i]);
			if ($cur >0)
			{
				$prev = $cur - 10;
				echo "<A HREF=" . $_SERVER[PHP_SELF] . "?" . http_build_query($_REQUEST) . "&cur=" . $prev . ">Prev</A> ";
			}
			$next = $cur + 10;
			if ($next <= count($tdb))
			{
				echo "<A HREF=" . $_SERVER[PHP_SELF] . "?" . http_build_query($_REQUEST) . "&cur=" . $next . ">Next</A> ";
			} 
			return $table;
		}

	}
	$table .= query_foot($tdb[$i]);
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