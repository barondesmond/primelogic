<?php
include("../_db_config.php");
$dis = 'Dispatch:' . $_REQUEST['Dispatch'];

  $query = "select Document as Image, Extension, ID from DocAttach where SourceText ='" . $dis . "'";
     $r = mssql_query($query);
     $data = mssql_result($r, 0, 'Image');
     $ext = mssql_result($r, 0, 'Extension');
     $id = mssql_result($r, 0, 'ID');
$filepath = '/var/www/html/upload/' . $id . $ext;
	$fp = file_put_contents($filepath, $data);

 	$file_extension = strtolower(substr(strrchr($filepath,"."),1));

	switch( $file_extension ) {
	 case "gif": $ctype="image/gif"; break;
	 case "png": $ctype="image/png"; break;
	 case "jpeg":
	 case "jpg": $ctype="image/jpeg"; break;
	 default:
	}
	header('Content-type: ' . $ctype);
		readfile($filepath);
	exit;
