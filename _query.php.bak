<?php
//must show data too end table
////show_data(array(), '1');
function show_data($db, $nd='', $nt = '')
{
static $hd;
static $rows;
static $hdr;

	if ($nd != '')
	{
		unset($hd);
		echo "</table>";
		echo "<BR><BR>\r\n\r\n";
		if ($nt != '')
		{
			unset($hdr);
			return false;
		}
	}

if (count($db) == 0)
	{
	return false;
	}
if (!$hd)
{
	$hdr = '';
	$vl = '';
}
foreach ($db as $k => $v)
{
	//echo "DB, Table, Column, Value<BR>\r\n";
	if (!$hd)
	{
		$hdr .= "<td>$k</td>";
	}
	$vl .= "<td>$v</td>";
	
}
$rows++;
if (!isset($hd))
{
	echo "<table border=1>";

	echo "<TR>$hdr</TR>\r\n";
	$hd = '1';
}

echo "<tr>$vl</tr>";
if ($rows>100)
	{
		echo "</table>";
		//exit;
		echo "<table border=1><TR>$hdr</TR>\r\n";
		$rows=0;
	}
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