<?php
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
Invoice	Receivab invoice received
Invoice	RecLed empty
Invoice	Sales original sales, DueDate
Invoice	SalesEmp useless
Invoice	SalesFRP empty
Invoice	SalesLed sales notes
Invoice	ViewListDispatches dispatch notes
Invoice	ViewListEquipment empty
Invoice	ViewListInvoices  Department, SalesSort
Invoice	ViewListHistory
Invoice	ViewReportJobReportQuotes
Invoice	ViewReportJobReportIncome
Invoice	ViewListDispatchesNoDetail
Invoice	InvSign
Invoice	ViewTableSales
Invoice	EmailInv
Invoice	ViewTableSalesEmp
Invoice	ViewEquipmentDispatchInvoiceHistory
Invoice	RecAge

Description/Notes
SELECT * FROM Dispatch WHERE Invoice = '0000019928'

SELECT * FROM RecDel WHERE Invoice = '0000019928'

*/