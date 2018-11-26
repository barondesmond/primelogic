<?php
include("_pdf.php");
include("_invoice.php");

$html = invoice($invoice);
if ($_GET['debug'])
{
	echo $html;
	exit;
}
//$html = "<html><head></head><body>This is a test</body></html>";
$file = htmlpdf($html, 'test.pdf');
//echo $file;
?>