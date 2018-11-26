<?php
include("_pdf.php");
include("_invoice.php");

$html = invoice($invoice);
		
	
$html = '<html><body><table class="first">
 <tr>
  <td width="200" align="left" color="grey"><BR><BR><BR><BR><BR><BR<BR><BR>Office: 662.841.1390<BR>Email: service@plisolutions.com</td>
  <td width="200" align="center"><BR><BR><BR><BR><BR></td>
  <td width="250" align="right"><table border="0" cellpadding="4" class="first"><tr><td>0000000</td></tr><tr><td>12/12/1970</td></tr><tr><td>12/12/12</td></tr><tr><td>11/11/11</td></tr><tr><td>324234</td></tr></table></td>
  <td width="50" align="right"><BR><BR><BR><BR><BR></td>
 </tr>
 <tr></table></body></html>';
$file = htmlpdf($html, 'test.pdf');
//echo $file;
?>