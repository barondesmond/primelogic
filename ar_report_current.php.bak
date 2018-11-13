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

$sql = "SELECT Sales.Invoice, Sales.CustNo, Sales.Dept, Terms, Sales.DueDate, CONVERT(decimal(10,2), Receivab.Paid) as Paids, CONVERT(decimal(10,2), InvAmt) as InvAmts, Customer.LastName, phone1  FROM Sales, Receivab, Customer, Employee
WHERE Sales.Invoice = Receivab.Invoice and Customer.CustNo= Sales.CustNo and DueDate < getdate() and DueDate > DATEADD(DD, -30, getdate()) and PaidOff is NULL and Sales.Salesman = Employee.EmpNo";

$subject = "Ar Report Current";

$html = report($sql, $subject);

$email = email_alias('0');

if ($argv[1])
{
	$email = $argv[1];
}

email_report($email, $subject, $html);

?>