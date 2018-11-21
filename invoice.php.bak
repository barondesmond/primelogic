<?php
require ('_pdf.php');
require ('_email.php');
require ('_db_config.php');
require ('_report.php');
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
 

// PUT YOUR HTML IN A VARIABLE
$ll = location_logo();
print_r($ll);
$html='<html><head></head><body>';
/*
<style>
body {

background-image: url("cid:my-attach");

background-repeat: repeat-y no-repeat;

background-color: #333;

margin: 0;

padding: 0;

}
</style>
*/
$html .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" background="cid:my-attach"> <tr><td>First column</td><td>Second column</td><td>Third column</td></tr> <tr><td>First column</td><td>Second column</td><td>Third column</td></tr> <tr><td>First column</td><td>Second column</td><td>Third column</td></tr> </table><img src="cid:my-attach"></body></html>';
//$file = htmlpdf($html, 'test.pdf');
echo $file;

email_report("barondesmond@gmail.com", "test pdf", $html, $ll['filename'], $ll['cid'], $ll['name'], $file);

?>