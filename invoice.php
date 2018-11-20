<?php
require ('_phpToPDF.php');


/*

invoice tables
SELECT COLUMN_NAME, TABLE_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE COLUMN_NAME = 'Invoice'

Invoice	Dispatch Notes
Invoice	XLogRec useless
Invoice	Equip empty
Invoice	EquipAtt empty
Invoice	HistLed history notes
Invoice	History useless
Invoice	RecDel empty
Invoice	Receivab 
Invoice	RecLed empty
Invoice	Sales CustNo, LocNo, Invoice,InvDate, EntDate, ShipName, ShipAddr1, ShipAddr2, ShipCSZ, PONum, InvAmt, DueDate
Invoice	SalesEmp useless
Invoice	SalesFRP empty
Invoice	SalesLed sales notes
Invoice	ViewListDispatches dispatch notes
Invoice	ViewListEquipment empty
Invoice	ViewListInvoices  Department, SalesSort
Invoice	ViewListHistory more notes
Invoice	ViewReportJobReportQuotes empty
Invoice	ViewReportJobReportIncome empty
Invoice	ViewListDispatchesNoDetail more sales info
Invoice	InvSign empty
Invoice	ViewTableSales sales stuff
Invoice	EmailInv
Invoice	ViewTableSalesEmp nothing useful
Invoice	ViewEquipmentDispatchInvoiceHistory
Invoice	RecAge

Description/Notes



SELECT Sales.Invoice, Sales.InvDate, Sales.EntDate, Sales.ShipName, Sales.ShipAddr1, Sales.ShipAddr2, Sales.ShipCSZ, Sales.PONum, Sales.InvAmount, Sales.DueDate
FROM Sales
WHERE Invoice = '0000011928';

SELECT Paid, InvAmt-Paid as TotalDue
FROM  Receivab
WHERE Invoice = '0000019928';

SELECT * FROM SalesLed WHERE Invoice = '0000019928' and NoPrint='0';
*/


require("phpToPDF.php"); 

// PUT YOUR HTML IN A VARIABLE
$my_html="<HTML>
<h2>Test HTML 01</h2><br><br>
<div style=\"display:block; padding:20px; border:2pt solid:#FE9A2E; background-color:#F6E3CE; font-weight:bold;\">
phpToPDF is pretty cool!
</div><br><br>
For more examples, visit us here --> http://phptopdf.com/examples/
</HTML>";

// SET YOUR PDF OPTIONS
// FOR ALL AVAILABLE OPTIONS, VISIT HERE:  http://phptopdf.com/documentation/
$pdf_options = array(
  "source_type" => 'html',
  "source" => $my_html,
  "action" => 'save',
  "save_directory" => '/var/www/html/pdf/',
  "file_name" => 'html_01.pdf');

// CALL THE phpToPDF FUNCTION WITH THE OPTIONS SET ABOVE
phptopdf($pdf_options);

// OPTIONAL - PUT A LINK TO DOWNLOAD THE PDF YOU JUST CREATED
echo ("<a href='html_01.pdf'>Download Your PDF</a>");

?>