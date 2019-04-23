<?php
//example api for spreadhsheet
//$spread[$row][$col];
//row a-g
//col 1-28
//config #pages, #rows
//header details
$pages = '2';
$rows = '29';
$cols = '13';
for ($page = 1; $page < $pages; $page++)
{
	for ($row = 1; $row < $rows; $row++)
	{
		for ($col = 1; $col < $cols; $col++)
		{
			if ($col == '7' && (isset($_REQUEST['continuation'][$page][$row][4]) || isset($_REQUEST['continuation'][$page][$row][5]) || isset($_REQUEST['continuation'][$page][$row][6])))
			{
				$db[$page][$row][$col] = $_REQUEST['continuation'][$page][$row][4] + $_REQUEST['continuation'][$page][$row][5] + $_REQUEST['continuation'][$page][$row][6];
				if ($db[$page][$row][$col] == '0')
				{
					$db[$page][$row][$col] = '';
				}
			}
			elseif ($col =='8' && isset($_REQUEST['continuation'][$page][$row][3]) && isset($_REQUEST['continuation'][$page][$row][7]))
			{
				$db[$page][$row][$col] = $_REQUEST['continuation'][$page][$row][7] / $_REQUEST['continuation'][$page][$row][3];
				if ($db[$page][$row][$col] == '0')
				{
					$db[$page][$row][$col] = '';
				}
			}
			elseif ($col =='9' && isset($_REQUEST['continuation'][$page][$row][3]) && isset($_REQUEST['continuation'][$page][$row][7]))
			{
				$db[$page][$row][$col] = $_REQUEST['continuation'][$page][$row][3] - $_REQUEST['continuation'][$page][$row][7];
				if ($db[$page][$row][$col] == '0')
				{
					$db[$page][$row][$col] = '';
				}
			}
			elseif (isset($_REQUEST['continuation'][$page][$row][$col]))
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
if (isset($_REQUEST['continuation']))
{
	$js['input'] = $_REQUEST['continuation'];
}

header('Content-Type: application/json');
$js['continuation'] = $db;
echo json_encode($js);
exit;


?>