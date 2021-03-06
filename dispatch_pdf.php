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
$pdf->SetAuthor('Prime Logic');
$pdf->SetTitle('Prime Logic Invoice');
$pdf->SetSubject('Prime Logic Invoice');
$pdf->SetKeywords('TCPDF, PDF, invoice, prime, logic');

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
$pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);

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
//$bMargin = $pdf->getBreakMargin();
// get current auto-page-break mode
//$auto_page_break = $pdf->getAutoPageBreak();
// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);
// set bacground image
//$img_file = K_PATH_IMAGES.'image_demo.jpg';
//$ll = pdf_background($arrays['0']['LastName']);

// restore auto-page-break status
//$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content
$pdf->setPageMark();


// Print a text
//$html = '<span style="color:white;text-align:center;font-weight:bold;font-size:80pt;">PAGE 3</span>';
//$pdf->writeHTML($html, true, false, true, false, '');

	$dbs = dispatch_init($dbs, $arrays[0]);
$img_file = '/var/www/html/primelogic/upload/' . $dbs['signature'];

if (file_exists($img_file))
	{
	//$src = imagecreatefromjpeg($img_file);
	//$img = imagerotate($src, 90, 0);
	$time = time();
	$rot = '/var/www/html/primelogic/upload/rot-' . $time . '.jpg';
	//imagejpeg($img, $rot);
	$pdf->Image($img_file, 100, 240, 100,50 , '', '', '', false, 300, '', false, false, 0);

	}



	$border = '0';
	$html = dispatch_header($dbs);

	$pdf -> writeHTMLCell('200', '10', '5', '10', $html, 1);

	$html = dispatch_priority($dbs);
	$pdf -> writeHTMLCell('200', '20', '5', '20', $html, 0);

	$html = dispatch_customer($dbs);

	$pdf -> writeHTMLCell('200', '20', '5', '30', $html, $border);

	$html = dispatch_scope($dbs);
	$pdf -> writeHTMLCell('200', '60', '5', '60', $html, $border);

	$html = dispatch_work($dbs);
	$pdf -> writeHTMLCell('200', '60', '5', '140', $html, $border);

	$html = dispatch_status($dbs);
	$pdf -> writeHTMLCell('200', '40', '5', '240', $html, $border);

	if ($dbs['customername'] == '')
	{
		$dbs['customername'] = $dbs['Contact'];
	}
	$html = dispatch_footer($dbs);
	$pdf -> writeHTMLCell('100', '20', '105', '240', $html, $border);
	$pdf -> writeHTMLCell('100', '20', '105', '260', $html, $border);
	$pdf -> writeHTMLCell('100', '20', '105', '280', $html, $border);
	$pdf -> writeHTMLCell('100', '20', '105', '255', $dbs['customername'], $border);
	$pdf -> writeHTMLCell('100', '20', '105', '235', substr($dbs['Complete'], 0, 11), $border);
	$pdf -> writeHTMLCell('100', '20', '105', '240', 'Date', $border);
	$pdf -> writeHTMLCell('100', '20', '105', '260', 'Customer Name', $border);
	$pdf -> writeHTMLCell('100', '20', '105', '280', 'Customer Signature', $border);

	





	//Bottom Table

 
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($file, 'F');
return $file;
}
function dispatch_customer_name($dispatch)
{
	$sql = "SELECT customer FROM Time.dbo.TimeClockApp WHERE Dispatch = '$dispatch' and customer != ''";
	$res = @mssql_query($sql);
	$dc = mssql_fetch_array($res, MSSQL_ASSOC);
	if (isset($dc['customer']))
	{
		return $dc['customer'];
	}
}
function pdf_query($dispatch='')
{
	if ($dispatch == '')
	{
		$arrays = '';
	}

	$sql = "SELECT Dispatch.Dispatch as Dispatch, DispLoc.LocName as LocName, DispLoc.LocNo, Dispatch.Notes as Notes, DispLoc.Add1 as Add1, DispLoc.Add2 as Add2, DispLoc.City as City, DispLoc.State as State, DispLoc.Zip as Zip, DispLoc.Contact as Contact, DispLoc.Phone1 as Phone, DispLoc.Contact2 as Contact2, DispLoc.Phone2 as Phone2, Dispatch.CustNo as CustNo, Dispatch.Priority as Priority, DispTech.PromDate,Dispatch.Complete as Complete, DispTech.ServiceMan as ServiceMan FROM Dispatch as Dispatch 
INNER JOIN Location as DispLoc ON Dispatch.CustNo = DispLoc.CustNo and Dispatch.LocNo = DispLoc.LocNo 
INNER JOIN DispTech as DispTech ON Dispatch.Dispatch = DispTech.Dispatch
WHERE Dispatch.Dispatch = '" . $dispatch . "' and Dispatch.Complete != '' ";

	if ($dispatch != '')
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
			$db['signature'] = dispatch_signature_query($dispatch);
			$db['customername'] = dispatch_customer_name($dispatch);
			$arrays[] = $db;

		}
	}
return $arrays;
}

function pdf_input($dispatch='')
{
	//echo $invoice;

	$arrays = pdf_query($dispatch);
		//print_r($arrays);
	$file = '/var/www/dispatchpdf/' . $dispatch . '.pdf';
	//echo $file;
	//exit;
	//if (!file_exists($file))
	//{
		$file = pdf_output($arrays, $file);
	//}	
return $file;

}
?>