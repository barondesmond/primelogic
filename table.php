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
<table class="first" cellpadding="0" cellspacing="0" border="0" >
 <tr>
  <td width="200" align="left" color="grey"><BR><BR><BR><BR>Office: 662.841.1390<BR>Email: service@plisolutions.com</td>
  <td width="200" align="center"><BR><BR><BR><BR><BR></td>
  <td width="250" align="right">0000000<BR>12/12/1970<BR>12/12/12<BR>11/11/11<BR>324234<BR></td>
  <td width="50" align="right"><BR><BR><BR><BR><BR></td>
 </tr>
 <tr>
  <td width="50" align="center"></td>
  <td width="350" ><color="grey">Billing Address:</color><BR><b>Lafayette Co. Chancery Clerk<BR>
300 North Lama Street<BR>
PO BOX 1240<BR>
Oxford MS 38655<BR></b></td>
  <td width="200">XXXX<br />XXXX</td>
  <td width="100">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="200" align="center" rowspan="3">2.</td>
  <td width="200" rowspan="3">XXXX<br />XXXX</td>
  <td width="200">XXXX<br /></td>
  <td width="100">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="200">XXXX<br />XXXX<br />XXXX<br />XXXX</td>
  <td width="200">XXXX<br />XXXX</td>
  <td align="center" width=300" colspan="2">XXXX<br />XXXX</td>
 </tr>
</table>


</div>

</body></html>';
//echo $html;

$file = htmlpdf($html, 'test.pdf');
//echo $file;
?>