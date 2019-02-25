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

	echo '</textarea><input type=submit></form>';
	if (!isset($_REQUEST['cur']))
	{
		$_REQUEST['cur'] = 0;
	}
	if (isset($_REQUEST['cur']))
	{
		$cur = $_REQUEST['cur'];
	}
	unset($_REQUEST['cur']);
	$table = query($query, $cur);
			if ($cur >9)
			{
				$prev = $cur - 10;
				echo "<A HREF=" . $_SERVER[PHP_SELF] . "?" . http_build_query($_REQUEST) . "&cur=" . $prev . ">Prev</A> ";
			}
			$next = $cur + 10;
			echo "<A HREF=" . $_SERVER[PHP_SELF] . "?" . http_build_query($_REQUEST) . "&cur=" . $next . ">Next</A> "; 

	

	echo $table;



?>