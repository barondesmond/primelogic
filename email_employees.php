<?php
include("_email.php");

$subject = "Timeclock App Update for Primelogic Employees";
$html = "Greetings Employee,  <BR>\r\n";
$html .= "<P>The timeclock app is live on android you can get it at <a href='https://play.google.com/store/apps/details?id=com.plisolutions.timeclock'>https://play.google.com/store/apps/details?id=com.plisolutions.timeclock</a><BR>\r\n";
$html .= "Login via your Employee Name when prompted and Email<BR>\r\n";
$html .= "EmpName: {EmpName}<BR>\r\n";
$html .= "Email: {Email}<BR>\r\n";
$html .= "IOS App is delayed in approval process and will be available soon<BR>\r\n";
$html .= "Please send any problems to administrator@plisolutions.com<BR>\r\n";
$html .= "<P>The Administrator<BR>\r\n";

email_employees($subject, $html);
?>
