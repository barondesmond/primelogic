<?php

function email_report($to, $subject, $message, $from = 'administrator@plisolutions.com', $reply = 'barondesmond@gmail.com', $bcc = 'barondesmond@gmail.com')
{

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From:  ' . $fromName . ' <' . $fromEmail .'>' . " \r\n" .
            'Reply-To: '.  $fromEmail . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = "To: $argv[2]";
		$headers[] = "From: $from";
		$headers[] = "Reply-To: $reply";
		$headers[] = "Bcc: $bcc";
		mail($to, $subject, $message, implode("\r\n", $headers));

}

function email_alias($days='', $dept = '', $salesman = '')
{
	//days (0 current, 60 = 60-75, 90 = 90+)
	$da[0] = 'service@plisolutions.com, dispatch@plisolutions.com';
	$da[60] = 'gwen@plisolutions.com, nicole@plisolutions.com';
	$day[90] = 'gwen@plisolutions.com, shannon@plisolutions.com, arthur@plisolutions.com';
	//Salesman
	$sm[0003] = 'david@plisolutions.com';
	$sm[0057] = 'beau@plisolutions.com';
	//Dept
	$dp[30] = 'arthur@plisolutions.com';
	$dp[40] = 'arthur@plisolutions.com';
	$dp[50] = 'arthur@plisolutions.com';
	$dp[60] = 'clint@plisolutions.com, shannon@plisolutions.com';
	$dp[70] = 'arthur@plisolutions.com';
	

	if (isset($da[$days])
	{
		return $da[$days];
	}

	if (isset($sm[$salesman])
	{
		return $sm[$salesman];
	}
	if (isset($dp[$dept])
	{
		return $dp[$dept];
	}

//Undefined Email
return 'barondesmond@gmail.com';

}


	