<?php
include("_db_config.php");

function continuation_parse_file($file)
{


	$key = array('JobID', 'application', 'ext');
	$exp = explode('.', $file);
	if (count($exp) != count($key) || $file == '.' || $file == '..')
	{
		//print_r($exp);
		
		return false;
	}
	//print_r($exp);
	for ($i=0;$i< count($exp); $i++)
	{
		$db[$key[$i]] = $exp[$i];
	}

$db['file'] = $file;
return $db;

}

function continuation_files()
{

static $files;
	
	if (!isset($files))
	{
		$dir = '/var/www/html/primelogic/continuation/';
		$files = scandir($dir);
	}
	foreach ($files as $id=>$file)
	{
		if ($cp = continuation_parse_file($file))
		{
			$js[$cp['JobID']][$cp['application']] = $cp['file'];
		}
	}
return $js;
}





function project($JobID)
{
$sql = "SELECT CONCAT(LocName, '<BR>', Add1, '<BR>', City ,' ' , State, ' ', Zip) as project FROM Jobs
INNER JOIN Location ON Jobs.CustNo = Location.CustNo and Jobs.Location = Location.LocNo
 WHERE JobID = '$JobID'";

 $res = mssql_query($sql);
	 $db = mssql_fetch_array($res, MSSQL_ASSOC);
 return $db['project'];
}

function toowner($JobID)
{
$sql = "SELECT CONCAT(LastName, '<BR>', Add1, '<BR>', City ,' ' , State, ' ', Zip) as toowner FROM Jobs
INNER JOIN Customer ON Jobs.CustNo = Customer.CustNo
 WHERE JobID = '$JobID'";

 $res = mssql_query($sql);
	 $db = mssql_fetch_array($res, MSSQL_ASSOC);


 return $db['toowner'];
}

$rows = '29';
$cols = '13';
$dir = '/var/www/html/primelogic/continuation/';
$cf = continuation_files();

//print_r($_REQUEST);
if (isset($_REQUEST['sheet']['JobID']) && isset($_REQUEST['sheet']['application']) && !isset($cf[$_REQUEST['sheet']['JobID']][$_REQUEST['sheet']['application']]))
{

	$prev = $_REQUEST['sheet']['application']-1;
	if (isset($cf[$_REQUEST['sheet']['JobID']][$prev]))
	{
		$fo = $dir . $cf[$_REQUEST['sheet']['JobID']][$prev];
		$file = fopen($fo, 'r');
		$fr = fread($file,filesize($fo));

		$_REQUEST = json_decode($fr, true);
		$_REQUEST['sheet']['application'] = $prev+1;
		$_REQUEST['totaladditions'] += $_REQUEST['monthadditions'];
		$_REQUEST['totaldeductions'] += $_REQUEST['monthdeductions'];
		$_REQUEST['monthadditions'] = 0;
		$_REQUEST['monthdeductions'] = 0;
		for ($page = 2; $page <= $_REQUEST['sheet']['pages']; $page++)
		{
			for ($row=1; $row <= $_REQUEST['sheet']['lastrow']; $row++)
			{
				$_REQUEST['continuation'][$page][$row][4] += $_REQUEST['continuation'][$page][$row][5];
				$_REQUEST['continuation'][$page][$row][5] = 0;
			}
		}
	}
	else
	{

		unset($_REQUEST['sheet']['application']);
	}

	unset($prev);
}
if (isset($_REQUEST['sheet']['JobID']) && isset($_REQUEST['sheet']['application']) && isset($cf[$_REQUEST['sheet']['JobID']][$_REQUEST['sheet']['application']]))
{
		$fo = $dir . $cf[$_REQUEST['sheet']['JobID']][$_REQUEST['sheet']['application']];
		$file = fopen($fo, 'r');
		$fr = fread($file,filesize($fo));

		$db = json_decode($fr, true);
		if ($db['sheet']['application'] == '1')
		{
			$db['sheet']['originalcontract'] = '0';
		}
		elseif ($db['application'] > '1')
		{
			$db['sheet']['monthadditions'] = '0';
			$db['sheet']['monthdeductions'] = '0';	
		}

		$_REQUEST['sheet'] = $db['sheet'];
}
if (isset($_REQUEST['sheet']['JobID']) && !isset($_REQUEST['sheet']['application']) && isset($cf[$_REQUEST['sheet']['JobID']]))
{
	//echo $fo;

	$app = count($cf[$_REQUEST['sheet']['JobID']]);
	$fo = $dir . $cf[$_REQUEST['sheet']['JobID']][$app];
	if (file_exists($fo))
	{
		$file = fopen($fo, 'r');
		$fr = fread($file,filesize($fo));
		fclose($file);
		$_REQUEST = json_decode($fr, true);

	}

	
}
if (!isset($_REQUEST['sheet']['pages']))
{
	$pages = '2';
	$sheet['JobID'] = $_REQUEST['sheet']['JobID'];
	$sheet['pages'] = $pages;
	$sheet['rows'] = $rows;
	$sheet['cols'] = $cols;
	$sheet['application'] = '1';
	$sheet['applicationdate'] = '';
	$sheet['project'] = project($sheet['JobID']);
	$sheet['toowner'] = toowner($sheet['JobID']);
	$sheet['periodto'] = '';
	$sheet['fromcontractor'] = "Prime Logic Inc.<BR>\r\n264 S Veterans Blvd<BR>\r\nTupelo MS 38804";
	$sheet['totalcompleted'] = '0';
	$sheet['monthadditions'] = 0;
	$sheet['monthdeductions'] = 0;
	$sheet['originalcontract'] = 0;

}
else
{
	foreach ($_REQUEST['sheet'] as $key=>$val)
	{
		$sheet[$key] = $val;
		${$key} = $val;
	}
}
for ($page = 2; $page <= $pages; $page++)
{
	for ($row = 1; $row <= $rows; $row++)
	{
		for ($col = 1; $col < $cols; $col++)
		{
			if (isset($_REQUEST['continuation'][$page][$row][$col]))
			{
				$db[$page][$row][$col] = $_REQUEST['continuation'][$page][$row][$col];
			}
			else
			{
				$db[$page][$row][$col] = '';
			}

		}
	}
}
$rownum=0;
for ($page = 2; $page <= $pages; $page++)
{
	for ($row = 1; $row <= $rows; $row++)
	{

		for ($col = 3; $col < 7; $col++)
		{
			if ($col==3)
			{
				if ($db[$page][$row][$col] != 0 && $db[$page][$row][$col] != '' && $row != '29')
				{
						$rownum++;
						$db[$page][$row][1] = $rownum;
						if ($sheet['application'] == '1')
						{
							$sheet['originalcontract'] += $db[$page][$row][3];
							$sheet['originalcontractrow'] = $rownum;
						}
						elseif ($sheet['application'] > 1 && $sheet['lastrow'] < $rownum && $rownum > $sheet['originalcontractrow'])
						{
							if ($db[$page][$row][3]>0)
							{
								$sheet['monthadditions'] += $db[$page][$row][3];
							}
							elseif ($db[$page][$row][3] < 0)
							{
								$sheet['monthdeductions'] += $db[$page][$row][3] * -1;
							}
						}
				}
			}
			if ($row != '29')
			{
				$db[$page][29][$col] += $db[$page][$row][$col];
			}
		}

		for ($col = 7; $col < $cols; $col++)
		{
	

			if ($col == '7') //Column G
			{
				$db[$page][$row][$col] = $db[$page][$row][4] + $db[$page][$row][5] + $db[$page][$row][6];
	
			}
			elseif ($col =='8')
			{
				$db[$page][$row][$col] = round($db[$page][$row][7] / $db[$page][$row][3], 2);

			}
			elseif ($col =='9' ) // Column H
			{
				$db[$page][$row][$col] = $db[$page][$row][3] - $db[$page][$row][7];

			}
			elseif ($col =='10'  && $row != '29') // Column I
			{
				if ($db[$page][$row][12] != '')
				{
					$temp = str_replace('%', '', $db[$page][$row][12]/100);
				}
	
				$db[$page][$row][$col] = round($db[$page][$row][7] * $temp);
	
			}
			if ($db[$page][$row][$col] == '0')
			{
					$db[$page][$row][$col] = '';
			}
			elseif ($col=='8')
			{
				$db[$page][$row][$col] = '%' . $db[$page][$row][$col] ;
			}


		}
		if ($row != '29')
		{
			$db[$page][29][10] += $db[$page][$row][10];
		}
	}
	$sheet['totalcompleted'] += $db[$page][29][7];
	$sheet['completedwork'] += $db[$page][29][4]+$db[$page][29][5];
	$sheet['storedmaterial'] += $db[$page][29][6];

	if ($db[$page][29][10] != '')
	{
		$sheet['totalretainage'] += $db[$page][29][10];
	}
	else
	{
		$tempcom = str_replace('%', '', $sheet['percentcompleted']/100);
		$tempstor = str_replace('%', '', $sheet['percentstored']/100);

		$sheet['totalretainage'] += round($sheet['completedwork']*$tempcom + $sheet['storedmaterial']*$tempstor);
	}
}
$sheet['lessretainage'] = $sheet['totalcompleted'] - $sheet['totalretainage'];
$sheet['paymentdue'] = $sheet['lessretainage'] - $sheet['priorcertificate'];
$sheet['totalsadditions'] = $sheet['totaladditions'] + $sheet['monthadditions'];
$sheet['totalsdeductions'] = $sheet['totaldeductions'] + $sheet['monthdeductions'];
$sheet['netchange'] = $sheet['totalsadditions'] - $sheet['totalsdeductions'];
$sheet['contractsum'] = $sheet['originalcontract'] + $sheet['netchange'];
$sheet['balancetofinish'] = $sheet['contractsum'] - $sheet['lessretainage'];
$sheet['lastrow'] = $rownum;
if (isset($_REQUEST['continuation']))
{
	$js['input'] = $_REQUEST['continuation'];
}

header('Content-Type: application/json');
$js['continuation'] = $db;
$js['sheet'] = $sheet;
$js['cf'] = $cf;
$json = json_encode($js);
echo $json;
if ($sheet['application'] != '' && $sheet['JobID'] != '')
{


	$file = fopen($dir . $sheet['JobID'] . '.' . $sheet['application']. '.json', 'w');
	fwrite($file, $json);
	fclose($file);
}
exit;


?>