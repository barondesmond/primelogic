<?php
include("_pdf.php");
$html='<html><head></head><body style="margin: 0px;">
<style>


div.top1 {
	color: black;
    position: absolute;
	z-index: 1;
	top: 200;
	left: 200;
}
</style>

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