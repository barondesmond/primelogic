<?php
include("_db_config.php");
include("_location_api.php");





	$map = location_dispatch();


	header('Content-Type: image/jpg');
	$image =file_get_contents($map);
	echo $image;
?>	