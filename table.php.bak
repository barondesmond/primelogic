<?php
include("_pdf.php");
$html='<html><head></head><body>
<style>
@page {
    margin: 0.0cm;
}
#my-bg {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1;
}

div.top1 {
	color: black;
    position: relative;
	z-index: 2;
	top: 280;
	left: 125;
}
</style>
<img src="PL_INVOICE-service-1.png" id="my-bg" width="1200" height="1542" />
<div class="top1">
<b>
Lafayette Co. Chancery Clerk<BR>
300 North Lama Street<BR>
PO BOX 1240<BR>
Oxford MS 38655<BR>
</b>
</div>

</body></html>';
//echo $html;

$file = htmlpdf($html, 'test.pdf');
//echo $file;
?>