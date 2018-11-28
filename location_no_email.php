<?php
include("_db_config.php");
include("_email.php");
include("_report.php");

/*
LocationInactive 0
CustomerInactive 0
ReceiveNotifications -1
EmailTasks(1-6) 2,255
*/


$sql = "SELECT Customer.LastName, Location.LocNo FROM Location
INNER JOIN Customer ON Location.CustNo = Customer.CustNo
 WHERE ReceivesNotifications = '-1' and CustomerInactive = '0' and LocationInactive = '0' and

(EmailTasks1 != '2' and EmailTasks2 != '2' and EmailTasks3 != '2'and EmailTasks4 != '2' and EmailTasks5 != '2' and EmailTasks6 != '2'
or

(CASE 
WHEN (Email not like '%[^a-z,0-9,@,.]%' and Email like '%_@_%_.__%') and (EmailTasks1 = '2' )  THEN Email
 WHEN (Email2 not like '%[^a-z,0-9,@,.]%' and Email2 like '%_@_%_.__%') and (EmailTasks2 = '2' ) THEN Email2
 WHEN (Email3 not like '%[^a-z,0-9,@,.]%' and Email3 like '%_@_%_.__%') and (EmailTasks3 = '2' ) THEN Email3
 WHEN (Email4 not like '%[^a-z,0-9,@,.]%' and Email4 like '%_@_%_.__%') and (EmailTasks4 = '2' ) THEN Email4
 WHEN (Email5 not like '%[^a-z,0-9,@,.]%' and Email5 like '%_@_%_.__%') and (EmailTasks5 = '2' ) THEN Email5
 WHEN (Email6 not like '%[^a-z,0-9,@,.]%' and Email6 like '%_@_%_.__%') and (EmailTasks6 = '2'  ) THEN Email6
 ELSE 'No Email'
 END) = 'No Email'
) ORDER BY Location.CustNo, Location.LocNo
";
$res = mssql_query($sql);
while ($db = mssql_fetch_array($res, MSSQL_ASSOC))
{
			$no = array('LastName', 'LocNo');

			if ($noe == '')
			{
				$noe = table_hd($db, $no);
			}
			//if (!isset($y[$db[CustNo]][$db[LocNo]]))
			//{
				$noe .= table_row($db, $no);
				$y[$db['CustNo']][$db['LocNo']] = $db;
			//}
}
			$html = html_head() . '<table>';
			$html .= $noe;
			$html .= "</table></body>";
$ll = location_logo();

foreach ($sm as $emp => $emails)
{
	$day = '31';
	$day2 = '60';
	if (!isset($email))
	{
		$email_send = $emails;
	}
	else
	{
		$email_send = $email;
	}
	foreach ($email_send as $send)
	{
		echo "Email = $send \n";
		email_report($send, "Fix Location Email Report", $html);
	}
}
?>

