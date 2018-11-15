<?php

function email_report($to='', $subject='', $message='', $from = 'administrator@plisolutions.com', $reply = 'barondesmond@gmail.com', $bcc = 'barondesmond@gmail.com')
{

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = "To: $to";
		$headers[] = "From: $from";
		$headers[] = "Reply-To: $reply";
		$headers[] = "Bcc: $bcc";
        $headers[] = 'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, implode("\r\n", $headers));

}




	