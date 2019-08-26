<?php
include("_db_config.php");
include("_query.php");
if ($argv[1])
{
	$query = $argv[1];
}
if ($_GET['query'])
{
	$query = $_GET['query'];	
}	
	echo '<form method="GET" action="' . $_SERVER['PHP_SELF'] . '">';
	echo '<textarea name="query" rows="12" cols="100" value="' . $query . '">';
	echo $query;	

	echo '</textarea><input type=submit name="Query" value="Query"><input type=submit name="Export" value="Export"></form>';
	if (!isset($_REQUEST['cur']))
	{
		$_REQUEST['cur'] = 0;
	}
	if (isset($_REQUEST['cur']))
	{
		$cur = $_REQUEST['cur'];
	}
	unset($_REQUEST['cur']);
	if (isset($_REQUEST['Query']))
	{
	$table = query($query, $cur);
	echo '<p>';
			if ($cur >9)
			{
				$prev = $cur - 10;
				echo "<A HREF=" . $_SERVER[PHP_SELF] . "?" . http_build_query($_REQUEST) . "&cur=" . $prev . ">Prev</A> ";
			}
			$next = $cur + 10;
			echo "<A HREF=" . $_SERVER[PHP_SELF] . "?" . http_build_query($_REQUEST) . "&cur=" . $next . ">Next</A> "; 

	

	echo $table;
	}
	elseif (isset($_REQUEST['Export']))
	{
		$exp = query_export($query);
		$csv = csv_format($exp);

		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=file.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $csv;
		exit;
	}


?>