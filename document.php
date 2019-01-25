<?php
include("_db_config.php");

if ($_REQUEST['Name'])
{
 $sql = "SELECT * FROM DocAttach WHERE Name = '" . $_REQUEST['Name'] "'";
 $res = mssql_query($sql);
 $db = mssql_fetch_array($res, MSSQL_ASSOC);
 if ($db['Extension'])
 {
     header("Content-type:application/pdf");
	 header("Content-Disposition:attachment;filename='" . $db['Name'] . $db['Extension'] . "'");
	 echo $db['Document'];
	 exit;
 }
 

