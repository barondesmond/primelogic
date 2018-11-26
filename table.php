<?php
include("_pdf.php");
include("_invoice.php");

$html = invoice($invoice);
		
	
$html = '<html><body><table>
 <tr>
  <td width="200" align="left" color="grey"><BR><BR><BR><BR><BR><BR<BR><BR>Office: 662.841.1390<BR>Email: service@plisolutions.com</td>
  <td width="200" align="center"><BR><BR><BR><BR><BR></td>
  <td width="250" align="right"></td>
  <td width="50" align="right"><BR><BR><BR><BR><BR></td>
 </tr>
 </table></body></html>';
 //$html = '<html><body><table><tr><td>This is a test</td></tr></table></body></html>';
$file = htmlpdf($html, 'test.pdf');
//echo $file;
?>