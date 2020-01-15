<?php
require ('_pdf.php');
require ('_email.php');
require ('_db_config.php');
require ('_report.php');
include("_invoice.php");
include("pdf.php");

if ($argv[1])
{
	$_GET['Invoice'] = $argv[1];
}

 
//$html = invoice($_GET['Invoice']);
$file = pdf_input($_GET['Invoice']);
 if ($_GET[debug])
 {
	 echo $html;
	exit;
 }

//$file = htmlpdf($html, 'test.pdf');
echo $file;

//email_report("barondesmond@gmail.com", "test pdf", $html, $ll['filename'], $ll['cid'], $ll['name'], $file);

?>