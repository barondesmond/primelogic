<?php
require ('_phpToPDF.php');

function htmlpdf($html, $pdf)
{

$pdf_options = array(
  "source_type" => 'html',
  "source" => $html,
  "action" => 'save',
  "save_directory" => '/var/www/pdf/',
  "file_name" => $pdf);

phptopdf($pdf_options);

return $pdf_options['save_directory'] . $pdf_options['file_name'];

}
?>