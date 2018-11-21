<?php
include("_pdf.php");
$html='<html><head></head><body style="margin: 0px;">
<style>
 table.first {
        color: black;
        font-family: helvetica;
        font-size: 10pt;

    }
</style>
<table class="first">
 <tr>
  <td width="200" align="left" color="grey"><BR><BR><BR><BR><BR><BR<BR><BR>>Office: 662.841.1390<BR>Email: service@plisolutions.com</td>
  <td width="200" align="center"><BR><BR><BR><BR><BR></td>
  <td width="250" align="right"><table border="0" cellpadding="4" class="first"><tr><td>0000000</td></tr><tr><td>12/12/1970</td></tr><tr><td>12/12/12</td></tr><tr><td>11/11/11</td></tr><tr><td>324234</td></tr></table></td>
  <td width="50" align="right"><BR><BR><BR><BR><BR></td>
 </tr>
 <tr>
  <td width="50" align="center"></td>
  <td width="350" ><color="grey">Billing Address:</color><BR><b>Lafayette Co. Chancery Clerk<BR>300 North Lama Street<BR>PO BOX 1240<BR>Oxford MS 38655<BR></b><BR><BR></td>
  <td width="90" align="left"></td>
  <td width="210" align="left">Service Location<BR><b>Lafayette Co. Chancery Clerk<BR>300 North Lamar Street<BR>PO BOX 1240<BR>Oxford MS 38555</b></td>

 </tr>
 <tr><td width="1"><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR></td><td colspan="3" width="699">
 <table class="first"><tr><td width="400">Service Labor Lead Tech</td><td width="100">2.5</td>79.50<td width="100"></td><td width="100">198.75</td></tr></table></td></tr>
 </table>

</body></html>';
//echo $html;

$file = htmlpdf($html, 'test.pdf');
//echo $file;
?>