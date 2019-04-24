<?php
//example api for spreadhsheet
//$spread[$row][$col];
//row a-g
//col 1-28
//config #pages, #rows
//header details
if (!isset($_REQUEST['sheet']['pages']))
{
	$pages = '3';
	$rows = '29';
	$cols = '13';
	$sheet['pages'] = $pages;
	$sheet['application'] = '';
	$sheet['applicationdate'] = '';

}
else
{
	foreach ($_REQUEST['sheet'] as $key=>$val))
	{
		$sheet[$key] = $val;
	}
}
for ($page = 2; $page < $pages; $page++)
{
	for ($row = 1; $row < $rows; $row++)
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

for ($page = 2; $page < $pages; $page++)
{
	for ($row = 1; $row < $rows; $row++)
	{
		for ($col = 7; $col < $cols; $col++)
		{
	

			if ($col == '7')
			{
				$db[$page][$row][$col] = $db[$page][$row][4] + $db[$page][$row][5] + $db[$page][$row][6];
	
			}
			elseif ($col =='8')
			{
				$db[$page][$row][$col] = round($db[$page][$row][7] / $db[$page][$row][3], 2);
		
			}
			elseif ($col =='9' )
			{
				$db[$page][$row][$col] = $db[$page][$row][3] - $db[$page][$row][7];

			}
			elseif ($col =='10' )
			{
				$temp = str_replace('%', '', $db[$page][$row][12]/100);
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
	}
}
if (isset($_REQUEST['continuation']))
{
	$js['input'] = $_REQUEST['continuation'];
}

header('Content-Type: application/json');
$js['continuation'] = $db;
$js['sheet'] = $sheet;
echo json_encode($js);
exit;


?>