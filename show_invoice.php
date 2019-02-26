<?php
if ($_REQUEST['Invoice'])
{
	$file = '/var/www/pdf/' . $_REQUEST['Invoice'] . '.pdf';
	$pdf = file_get_contents($file);
	echo $pdf;
}