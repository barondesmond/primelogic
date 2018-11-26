<?php
include("_pdf.php");
include("_invoice.php");

$html = invoice($invoice);
if ($_GET['debug'])
{
	echo $html;
	exit;
}
$file = htmlpdf($html, 'test.pdf');
//echo $file;
?>