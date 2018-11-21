<?php
require_once('../tcpdf/tcpdf.php');


function htmlpdf($html, $fpdf)
{

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->SetFont('helvetica', '', 9);
$pdf->AddPage();
$pdf->writeHTML($html, true, 0, true, 0);
$pdf->lastPage();
$file = '/var/www/pdf/' . $fpdf;
$pdf->Output($file, 'I');

return $file;

}
?>