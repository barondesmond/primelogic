<?php
include("_db_config.php");
include("_email.php");

$subject = "Timeclock App MDM Distribution via Miradore Update for Primelogic Employees";
$html = "Greetings Employee,  <BR>\r\n";
$html .= "EmpName: {EmpName}<BR>\r\n";
$html .= "Email: {Email}<BR>\r\n";
$html .= "IOS App is available via Miradore Online MDM Management.  Please enroll at miradore online via email sent to you.  Your email account with Ios on your phone has to be registered to the email listed above.   After logging in it will ask to download a certificate which you need to instal in General->Settings and then trust.  Then we can deploy the app to your phone which you will also have to accept to install timeclock app.  Corporate distribution required do to ios appstore requirements of app.  If you have not received an email from Miradore let me know<BR>\r\n";
$html .= "After entering your Employee Name and Email you will be alerted that you are not authorized and will be sent an email to authorize the app installation.  click on the link and then login again.  You can only have one app install authorized at a time<BR>\r\n";
$html .= "<P>Please send any problems to administrator@plisolutions.com<BR>\r\n";
$html .= "If you should not be receiving this email please let me know so I can remove you from the Employee list <BR>\r\n";
$html .= "<P>The Administrator<BR>\r\nBaron Desmond<BR>\r\n";

email_employees($subject, $html);
?>
