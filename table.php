<?php
include("_pdf.php");
include("_invoice.php");

$html = invoice($invoice);
		
	
$html = '<html><body><table>
 <tr>
  <td width="200" align="left" color="grey"><BR><BR><BR><BR><BR><BR<BR><BR>Office: 662.841.1390<BR>Email: service@plisolutions.com</td>
  <td width="200" align="center"><BR><BR><BR><BR><BR></td>
  <td width="250" align="right"><table border="0" cellpadding="4"><tr><td>0000000</td></tr><tr><td>12/12/1970</td></tr><tr><td>12/12/12</td></tr><tr><td>11/11/11</td></tr><tr><td>324234</td></tr></table></td>
  <td width="50" align="right"><BR><BR><BR><BR><BR></td>
 </tr>
 </table></body></html>';
 //$html = '<html><body>This is a test</body></html>';
$file = htmlpdf($html, 'test.pdf');
//echo $file;
?>