<?php
include("_db_config.php");
include("_email.php");
include("_report.php");
define('SPOOLWRITE', 'write');

/*
LocationInactive 0
CustomerInactive 0
ReceiveNotifications -1
EmailTasks(1-6) 2,255
*/

if (!$argv[1])
{
	define(EMAIL_SEND, '');
}
elseif ($argv[1])
{
	define(EMAIL_SEND, $argv[1]);
}

$sql = "SELECT Customer.LastName, Customer.CustNo, Location.LocNo FROM Location
INNER JOIN Customer ON Location.CustNo = Customer.CustNo
 WHERE ReceivesNotifications = '-1' and CustomerInactive = '0' and LocationInactive = '0' and

(EmailTasks1 != '2' and EmailTasks2 != '2' and EmailTasks3 != '2'and EmailTasks4 != '2' and EmailTasks5 != '2' and EmailTasks6 != '2'
or

(CASE 
WHEN ((PATINDEX('%[^A-z0-9._-]%@%.%',Email)=0) and Email like '%_@_%_.__%') and (EmailTasks1 = '2' )  THEN Email
 WHEN ((PATINDEX('%[^A-z0-9._-]%@%.%',Email2)=0) and Email2 like '%_@_%_.__%') and (EmailTasks2 = '2' ) THEN Email2
 WHEN ((PATINDEX('%[^A-z0-9._-]%@%.%',Email3)=0) and Email3 like '%_@_%_.__%') and (EmailTasks3 = '2' ) THEN Email3
 WHEN ((PATINDEX('%[^A-z0-9._-]%@%.%',Email4)=0) and Email4 like '%_@_%_.__%') and (EmailTasks4 = '2' ) THEN Email4
 WHEN ((PATINDEX('%[^A-z0-9._-]%@%.%',Email5)=0) and Email5 like '%_@_%_.__%') and (EmailTasks5 = '2' ) THEN Email5
 WHEN ((PATINDEX('%[^A-z0-9._-]%@%.%',Email6)=0) and Email6 like '%_@_%_.__%') and (EmailTasks6 = '2'  ) THEN Email6
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

if (EMAIL_SEND == '')
{
	foreach ($emails as $send)
	{
		echo "Email = $send \n";
		if (EMAIL_SEND == '')
		{
			email_report($send, "Fix Location Email Report", $html);
		}
	}

}
if (EMAIL_SEND != '')
{
	email_report(EMAIL_SEND, "Fix Location Email Report", $html);
}


?>

