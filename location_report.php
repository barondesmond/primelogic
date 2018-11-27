<?php
include("_db_config.php");
include("_email.php");
include("_report.php");
include("pdf.php");
include("_invoice.php");
/*
LocationInactive 0
CustomerInactive 0
ReceiveNotifications -1
EmailTasks(1-6) 2,255
*/

$html = location_basis();
//echo $html;
define('EMAIL_SEND', 'barondesmond@gmail.com');
email_report("barondesmond@gmail.com", "Priority Location Invoice Email Need Fixing", $html);
?>

