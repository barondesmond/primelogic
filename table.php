<?php
include("_pdf.php");
include("_invoice.php");

$html = invoice($invoice);
		
/*	
$html = '<html><body><table border="1">
 <tr>
  <td width="200" align="center">x xx xx xx xx xx</td>
  <td width="250" align="right">xx xx xx xx xx xx</td>
  <td width="50" align="right">xx xx xx xx xx xx</td>
 </tr>
 </table></body></html>';
 */
 //$html = '<html><body><table><tr><td>This is a test</td></tr></table></body></html>';
 if ($_GET[debug])
 {
	 echo $html;
	exit;
 }
$file = htmlpdf($html, 'test.pdf');
//echo $file;
?>