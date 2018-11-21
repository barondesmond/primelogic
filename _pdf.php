<?php
require ('tcpdf/tcpdf.php');

function htmlpdf($html, $pdf)
{


// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('Prime Logic');
$pdf->SetAuthor('Baron Desmond');
$pdf->SetTitle('pdf');
$pdf->SetSubject('pdf');
$pdf->SetKeywords('pdf');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001'
, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$pdf->SetFont('helvetica', '', 14, '', true);
$pdf->AddPage();

$html = '<<<EOD' . $html . 'EOD;'

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

$file = '/var/www/pdf/' . $pdf;
$pdf->Output($file, 'F');

return $pdf;

}
?>