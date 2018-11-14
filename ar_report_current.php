<?php
include("_db_config.php");
include("_report.php");
include("_email.php");
/*
$sql = "SELECT Sales.Invoice, Sales.CustNo, Sales.Dept, Sales.Salesman, Employee.EmpName, Sales.DueDate, Receivab.Paid, InvAmt, Customer.LastName, phone1  FROM Sales, Receivab, Customer, Employee
WHERE Sales.Invoice = Receivab.Invoice and Customer.CustNo= Sales.CustNo and DueDate < getdate() and DueDate > DATEADD(DD, -30, getdate()) and PaidOff is NULL and Sales.Salesman = Employee.EmpNo;
";
*/
//CONVERT(decimal(10,2), R) AS decimal;
//date time eliminate date
//subject header in table
//if phone1 null add phone2
//move last name between inv and cust


$sql2 = "SELECT CONVERT(decimal(12,2), SUM(InvAmt-Paid)) as Amt  FROM Sales, Receivab, Customer, Employee 
WHERE Sales.Invoice = Receivab.Invoice and Customer.CustNo= Sales.CustNo and DueDate < getdate() and DueDate > DATEADD(DD, -30, getdate()) and PaidOff is NULL and Sales.Salesman = Employee.EmpNo";
$res2 = mssql_query($sql2);
$db = mssql_fetch_array($res2);

	setlocale(LC_MONETARY, 'en_US.UTF-8');

$subject = "Ar Report 0-30 " . money_format('%.2n', $db[Amt]);


$sql = "SELECT Sales.Invoice, Customer.LastName, Sales.CustNo, Sales.Dept, Terms, CONVERT(varchar(10), Sales.DueDate, 101) as DueDates , CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts,  ISNULL(phone1, phone2) as phone  
FROM Sales, Receivab, Customer
WHERE Sales.Invoice = Receivab.Invoice and Customer.CustNo= Sales.CustNo 
and DueDate < getdate() and DueDate > DATEADD(DD, -30, getdate()) and PaidOff is NULL 
ORDER BY Ssales.CustNo;";

/*
SELECT Customer.CustNo, Customer.LastName, Collectn.*, CONVERT(varchar(10), Sales.DueDate, 101) as DueDates , CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts,  ISNULL(phone1, phone2) as phone  FROM Sales
INNER JOIN Receivab ON Sales.Invoice = Receivab.Invoice
INNER JOIN Customer ON Sales.CustNo = Customer.CustNo
LEFT OUTER JOIN Collectn ON Sales.CustNo = Collectn.CustNo and Date > DATEADD(DD, -30, getdate()) and Date < getdate()
WHERE  DueDate < getdate() and DueDate > DATEADD(DD, -30, getdate()) and PaidOff is NULL  
ORDER BY Customer.CustNo

SELECT Customer.CustNo, Customer.LastName, Collectn.*, CONVERT(varchar(10), Receivab.DueDate, 101) as DueDates , CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts,  ISNULL(phone1, phone2) as phone  
FROM Receivab 
INNER JOIN Customer ON Sales.CustNo = Customer.CustNo
WHERE  DueDate < getdate() and DueDate > DATEADD(DD, -30, getdate()) and PaidOff is NULL  
ORDER BY Customer.CustNo
*/
$html = report($sql, $subject);

$email = email_alias('0');

if ($argv[1])
{
	$email = $argv[1];
}

email_report($email, $subject, $html);

?>