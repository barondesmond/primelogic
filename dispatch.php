<?php
require ('_pdf.php');
require ('_email.php');
require ('_db_config.php');
require ('_report.php');
include("_dispatch.php");
include("dispatchpdf.php");




if ($argv[1])
{
	$_GET['Dispatch'] = $argv[1];
}

if ($_GET['Dispatch'])
{
	$file = pdf_input($_GET['Dispatch']);

//$file = htmlpdf($html, 'test.pdf');
echo $file;

}
//email_report("barondesmond@gmail.com", "test pdf", $html, $ll['filename'], $ll['cid'], $ll['name'], $file);

?>