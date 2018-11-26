<?php
include("_pdf.php");
include("_invoice.php");

$html = invoice($invoice);

//echo $html;

$file = htmlpdf($html, 'test.pdf');
//echo $file;
?>