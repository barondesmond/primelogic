<?php
include("_db_config.php");
include("_email.php");

$subject = "Timeclock App Primelogic Employees";
$html = "Greetings {EmpName},  <BR>\r\n";
$html .= "EmpName: {EmpName}<BR>\r\n";
$html .= "Email: {Email}<BR>\r\n";
$html .= "<A HREF='https://online.miradore.com/enroll/
'>Enroll Phone</A><BR>\r\n";
$html .= "IOS App Primelogic Timeclock is available via Miradore Online.  Please enroll at miradore online .  Your email account that is registered on your phone needs to be your Email listed above or we need your email on your phone. If your email is different on your phone than above please let me know what the email is.  After logging in it will ask to download a certificate which you need to instal in General->Settings and then trust.  Then we can deploy the app to your phone which you will also have to accept to install timeclock app. <BR>\r\n";
$html .= "After entering your Employee Name and Email you will be alerted that you are not authorized and will be sent an email to authorize the app installation.  click on the link and then login again.  You can only have one app install authorized at a time<BR>\r\n";
$html .= "<P>Please send any problems to administrator@plisolutions.com or come see me if you do not understand the purpose of this email<BR>\r\n";
$html .= "If you should not be receiving this email please let me know so I can remove you from the Employee list <BR>\r\n";
$html .= "<P>The Administrator<BR>\r\nBaron Desmond<BR>\r\n";

email_employees($subject, $html);
?>
