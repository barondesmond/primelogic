<?php
if ($_REQUEST['Invoice'])
{
	$file = '/var/www/pdf/' . $_REQUEST['Invoice'] . '.pdf';
	header("Content-type:application/pdf");
	header("Content-Disposition:inline;filename='$file'");
	readfile($file);	
}