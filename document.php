<?php
include("_db_config.php");


if ($_REQUEST['file'] && file_exists($file))
{
	    $filename = $_REQUEST['file'];

    $fileinfo = pathinfo($filename);
    $sendname = $fileinfo['filename'] . '.' . strtoupper($fileinfo['extension']);

    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"$sendname\"");
    header('Content-Length: ' . filesize($filename));
    readfile($filename);
}
elseif ($_REQUEST['file'])
{
	echo "error " . $_REQUEST['file'];
}
if ($_REQUEST['Name'])
{
 $sql = "SELECT * FROM DocAttach WHERE Name = '" . $_REQUEST['Name'] . "'";
 $res = mssql_query($sql);
 $db = mssql_fetch_array($res, MSSQL_ASSOC);
	if ($db['Extension'])
	{
	     //header("Content-type:application/pdf");
		 //header("Content-Disposition:attachment;filename='" . $db['Name'] . $db['Extension'] . "'");
		 $fd = "/var/www/pdf/" . $db['Name'] . $db['Extension'];
		 $file = fopen($fd, 'w');
		 fwrite($file, $db['Document']);
		 echo "Written $fd";
		 fclose($file);

		 exit;
	}
 }