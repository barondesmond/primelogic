<?php
require_once("/var/www/html/vendor/autoload.php");

function pdf_background($LocName='')
{

	if (substr($LocName, 0, 1)  == '#')
	{		
		$ll['filename'] .= '/var/www/html/images/PL_INVOICE-construction-1.png';
		$ll['cid'] = 'my-attach';
		$ll['name'] = 'PLIClogo';

	}
	elseif (substr($LocName, 0, 1)  == '*')
	{		
		$ll['filename'] = '/var/www/html/images/PL_INVOICE-NMT-1.png';
		$ll['cid'] = 'my-attach';
		$ll['name'] = 'NMTlogo';
	}
	else
	{
		$ll['filename'] = '/var/www/html/images/PL_INVOICE-service-1.png';
		$ll['cid'] = 'my-attach';
		$ll['name'] = 'PLISlogo';
	}
return $ll;
}

function pdf_output($arrays, $file)
{
// Extend the TCPDF class to create custom Header and Footer


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
//$img_file = '/var/www/html/primelogic/PL_INVOICE-service-1.png';
$ll = pdf_background($arrays['0']['LastName']);

$pdf->Image($ll['filename'], 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
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
	$border='0';
	//middletable
	$html = invoice_header($dbs);
	$html .= invoice_toptable($dbs);

	$html .= invoice_middletable($arrays);
	$pdf->writeHTML($html, true, false, true, false, '');

	//$pdf -> writeHTMLCell('200', '120', '0', '0', $html, $border);

	//Bottom Table
	$pdf -> writeHTMLCell('50', '50', '30', '235', '<b>' . $dbs['billing'] . '</b>', $border);
	$pdf->SetFont('helvetica', '', 8);
	$pdf -> writeHTMLCell('18', '5', '116', '255', $dbs['Invoice'], $border);
	$pdf -> writeHTMLCell('18', '5', '134', '255', $dbs['InvDate'], $border);
	$pdf -> writeHTMLCell('18', '5', '152', '254', money_format('%.2n', $dbs['InvAmt']) , $border, '', '', '', 'R');
	$pdf -> writeHTMLCell('18', '5', '175', '243', money_format('%.2n', $dbs['InvAmt']) , $border, '', '', '', 'R');
	$pdf->SetFont('helvetica', '', 10);
	$pdf -> writeHTMLCell('20', '5', '179', '209', '<b>' . money_format('%.2n', $dbs['Tax']) . '</b>' , $border, '', '', '', 'R');

	$pdf -> writeHTMLCell('20', '5', '159', '215', '<b>' . $dbs['InvDate'] . '</b>' , $border, '', '', '', 'R');
	$pdf -> writeHTMLCell('20', '5', '179', '215', '<b>' . money_format('%.2n', $dbs['Tax']) . '</b>' , $border, '', '', '', 'R');
	$pdf->SetFont('helvetica', '', 11);

	$pdf -> writeHTMLCell('22', '5', '165', '273', '<b>' . $dbs['InvDate'] . '<b>', $border);
 
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($file, 'F');
return $file;
}

function pdf_query($invoice='')
{

	if ($invoice == '')
	{
		$arrays = '';
	}
	$sql = "SELECT LastName, Sales.Invoice, CONVERT(varchar(10), Sales.InvDate, 101) as InvDate, CONVERT(varchar(10), Sales.EntDate, 101) as EntDate, 
	Customer.LastName as BillName, Customer.Add1 as BillAddr1, Customer.Add2 as BillAddr2, CONCAT(Customer.City, ' ' , Customer.State, ' ' , Customer.Zip) as BillCSZ,
	Sales.ShipName, Sales.ShipAddr1, Sales.ShipAddr2, Sales.ShipCSZ, Sales.PONum, Sales.InvAmount, CONVERT(varchar(10), Sales.DueDate, 101) as DueDate, Paid, InvAmt, Tax1, SalesLed.*, Location.*, CONVERT(varchar(10), Dispatch.RecDate, 101) as ServiceDate, Sales.AmtCharge -  Sales.InvAmount as Tax
FROM Sales
INNER JOIN Receivab ON Sales.Invoice = Receivab.Invoice
INNER JOIN Location ON Receivab.LocNo = Location.LocNo and Receivab.CustNo = Location.CustNo
INNER JOIN SalesLed ON Sales.Invoice = SalesLed.Invoice
INNER JOIN Customer ON Sales.CustNo = Customer.CustNo
LEFT JOIN Dispatch ON Sales.Invoice = Dispatch.Invoice
WHERE Sales.Invoice = '$invoice' and SalesLed.NoPrint = '0';";
	if ($invoice != '')
	{
		//echo $Invoice;
		//echo $sql;
		$res = mssql_query($sql);
		if (mssql_num_rows($res) == 0)
		{
			echo $sql;
			exit;
		}
		while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
		{
			$arrays[] = $db;
		}
	}
return $arrays;
}

function pdf_input($invoice='')
{
	//echo $invoice;

	$arrays = pdf_query($invoice);
		//print_r($arrays);
	$file = '/var/www/pdf/' . $invoice . '.pdf';
	//echo $file;
	//exit;
	if (!file_exists($file))
	{
		$file = pdf_output($arrays, $file);
	}	
return $file;

}
?>