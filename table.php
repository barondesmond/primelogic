<?php
include("_pdf.php");
$html='<html><head></head><body style="margin: 0px;">
<style>
 div.test {
        color: #CC0000;
        background-color: #FFFF66;
        font-family: helvetica;
        font-size: 10pt;
        border-style: solid solid solid solid;
        border-width: 2px 2px 2px 2px;
        border-color: green #FF00FF blue red;
        text-align: center;
    }

div.top1 {
	color: black;
}
</style>
<div class="test" width="200" height="200"></div>
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