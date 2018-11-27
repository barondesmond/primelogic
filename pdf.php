<?php
require_once('../vendor/autoload.php');

function pdf_output($arrays)
{
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = K_PATH_IMAGES.'image_demo.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 051');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// --- example with background set on page ---

// remove default header
$pdf->setPrintHeader(false);

// add a page
$pdf->AddPage();


// -- set new background ---

// get the current page break margin
$bMargin = $pdf->getBreakMargin();
// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();
// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);
// set bacground image
//$img_file = K_PATH_IMAGES.'image_demo.jpg';
$img_file = '/var/www/html/primelogic/PL_INVOICE-service-1.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
// restore auto-page-break status
//$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content
$pdf->setPageMark();


// Print a text
//$html = '<span style="color:white;text-align:center;font-weight:bold;font-size:80pt;">PAGE 3</span>';
//$pdf->writeHTML($html, true, false, true, false, '');

	$dbs = invoice_init($dbs, $arrays[0]);
	$dbs = invoice_service_location($dbs, $arrays[0]);
	$dbs = invoice_billing($dbs, $arrays[0]);
	$border='1';
	//middletable
	$html = invoice_middletable($dbs);
	$pdf -> writeHTMLCell('150', '100', '0', '100', $html, $border);
	//Bottom Table
	$pdf -> writeHTMLCell('50', '50', '30', '235', '<b>' . $dbs['billing'] . '</b>', $border);
	$pdf->SetFont('helvetica', '', 8);
	$pdf -> writeHTMLCell('18', '5', '116', '255', $dbs['Invoice'], $border);
	$pdf -> writeHTMLCell('18', '5', '134', '255', $dbs['InvDate'], $border);
	$pdf -> writeHTMLCell('18', '5', '152', '254', '$' . money_format('%.2n', $dbs['InvAmt']) , $border, '', '', '', 'R');
	$pdf -> writeHTMLCell('18', '5', '175', '243', '$' . money_format('%.2n', $dbs['InvAmt']) , $border, '', '', '', 'R');
	$pdf -> writeHTMLCell('18', '5', '175', '223', '$' . money_format('%.2n', $dbs['InvAmt']) , $border, '', '', '', 'R');
 
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($file, 'I');
return $file;
}

function pdf_query($invoice='')
{

	if ($invoice == '')
	{
		$arrays = '';
	}
	$sql = "SELECT Sales.Invoice, CONVERT(varchar(10), Sales.InvDate, 101) as InvDate, CONVERT(varchar(10), Sales.EntDate, 101) as EntDate, Sales.ShipName, Sales.ShipAddr1, Sales.ShipAddr2, Sales.ShipCSZ, Sales.PONum, Sales.InvAmount, CONVERT(varchar(10), Sales.DueDate, 101) as DueDate, Paid, InvAmt, Tax1, SalesLed.*, Location.*
FROM Sales
INNER JOIN Receivab ON Sales.Invoice = Receivab.Invoice
INNER JOIN Location ON Receivab.LocNo = Location.LocNo and Receivab.CustNo = Location.CustNo
INNER JOIN SalesLed ON Sales.Invoice = SalesLed.Invoice
WHERE Sales.Invoice = '$invoice' and SalesLed.NoPrint = '0'";
	if ($invoice != '')
	{
		//echo $Invoice;
		//echo $sql;
		$res = mssql_query($sql);
		while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
		{
			$arrays[] = $db;
		}
	}
return $arrays;
}

function pdf_input($invoice='')
{
	$arrays = pdf_query($invoice);

	$file = pdf_output($arrays);
return $file;

}
?>