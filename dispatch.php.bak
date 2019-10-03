<?php
require ('_pdf.php');
require ('_email.php');
require ('_db_config.php');
require ('_report.php');
include("_dispatch.php");
include("dispatch_pdf.php");
include("_location_api.php");



if ($argv[1])
{
	$_GET['Dispatch'] = $argv[1];
	sleep(360);
}

if ($_GET['Dispatch'])
{
	$file = pdf_input($_GET['Dispatch']);

}

if (!$argv[1])
{
	header("Content-type:application/pdf");
	header("Content-Disposition:attachment;filename='downloaded.pdf'");
	readfile("$file");
}

if ($argv)
{
	email_report("barondesmond@gmail.com", "Dispatch Ticket " . $_GET['Dispatch'] , "Dispatch Ticket Attached " . $_GET['Dispatch'], $ll['filename'], $ll['cid'], $ll['name'], $file);
}
if ($argv)
{
	email_report("dispatch@plisolutions.com", "Dispatch Ticket " . $_GET['Dispatch'] , "Dispatch Ticket Attached " . $_GET['Dispatch'], $ll['filename'], $ll['cid'], $ll['name'], $file);
}

?>