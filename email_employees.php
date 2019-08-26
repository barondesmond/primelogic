<?php
include("_db_config.php");
include("_email.php");

$subject = "Timeclock App MDM Distribution via Miradore Update for Primelogic Employees";
$html = "Greetings Employee,  <BR>\r\n";
$html .= "<P>The timeclock app is live on android you can get it at <a href='https://play.google.com/store/apps/details?id=com.plisolutions.timeclock'>https://play.google.com/store/apps/details?id=com.plisolutions.timeclock</a><BR>\r\n";
$html .= "Login via your Employee Name when prompted and Email<BR>\r\n";
$html .= "EmpName: {EmpName}<BR>\r\n";
$html .= "Email: {Email}<BR>\r\n";
$html .= "IOS App is available via Miradore Online MDM Management.  Corporate distribution required do to ios appstore requirements of app.  If you have not received an email from Miradore let me know<BR>\r\n";
$html .= "After entering your Employee Name and Email you will be alerted that you are not authorized and will be sent an email to authorize the app installation.  click on the link and then login again.  You can only have one app install authorized at a time<BR>\r\n";
$html .= "<P>Please send any problems to administrator@plisolutions.com<BR>\r\n";
$html .= "If you should not be receiving this email please let me know so I can remove you from the Employee list <BR>\r\n";
$html .= "<P>The Administrator<BR>\r\n";

email_employees($subject, $html);
?>
