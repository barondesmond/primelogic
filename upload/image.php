<?php
include("../_db_config.php");
$dis = 'Dispatch:' . $_REQUEST['Dispatch'];

  $query = "select Image, Extension, ID from DocAttach where SourcText ='" . $dis . "'";
     $r = mssql_query($query);
     $data = mssql_result($r, 0, 'Image');
     $ext = mssql_result($r, 0, 'Extension');
     $id = mssql_result($r, 0, 'ID');
$filepath = '/var/www/html/upload/' . $id . $ext;
	
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer
	echo $data;
	exit;
