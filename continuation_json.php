<?php
//example api for spreadhsheet
//$spread[$row][$col];
//row a-g
//col 1-28
//config #pages, #rows
//header details
$rows = '29';
$cols = '13';
//print_r($_REQUEST);
if (isset($_REQUEST['sheet']['JobID']) && !isset($_REQUEST['sheet']['application']))
{
	$dir = '/var/www/html/primelogic/continuation/';
	$_REQUEST['sheet']['application'] = 1;
	$fo = $dir . $_REQUEST['sheet']['JobID'] . '.' . $_REQUEST['sheet']['application']. '.json';
	//echo $fo;
	$i=1;
	while (file_exists($fo) && $i<10)
	{
		$file = fopen($fo, 'r');
		$fr = fread($file,filesize($fo));

		$_REQUEST = json_decode($fr, true);
		$prev = $_REQUEST['sheet']['application'];
		$_REQUEST['sheet']['appliation']++;
		$fo = $dir . $_REQUEST['sheet']['JobID'] . '.' . $_REQUEST['sheet']['application']. '.json';
		fclose($file);
		$i++;
	}
	if (isset($prev))
	{
		$_REQUEST['sheet']['application'] = $prev;
	}
	//echo $fr;
	//print_r($_REQUEST);
	//exit;
	
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
	$sheet['project'] = '';
	$sheet['toowner'] = '';
	$sheet['periodto'] = '';
	$sheet['fromcontractor'] = "Prime Logic Inc.\r\n264 S Veterans Blvd\r\nTupelo MS 38804\r\n";
	$sheet['totalcompleted'] = '0';

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

for ($page = 2; $page <= $pages; $page++)
{
	for ($row = 1; $row <= $rows; $row++)
	{
		for ($col = 3; $col < 7; $col++)
		{
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
if (isset($_REQUEST['continuation']))
{
	$js['input'] = $_REQUEST['continuation'];
}

header('Content-Type: application/json');
$js['continuation'] = $db;
$js['sheet'] = $sheet;
$json = json_encode($js);
echo $json;
if ($sheet['application'] != '' && $sheet['JobID'] != '')
{

	$dir = '/var/www/html/primelogic/continuation/';

	$file = fopen($dir . $sheet['JobID'] . '.' . $sheet['application']. '.json', 'w');
	fwrite($file, $json);
	fclose($file);
}
exit;


?>