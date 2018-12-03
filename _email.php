<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once("/var/www/html/vendor/autoload.php");
define('SPOOLING', 'write');


	//days (0 current, 60 = 60-75, 90 = 90+)
	$da[0][] = 'service@plisolutions.com';
	$da[0][] = 'dispatch@plisolutions.com';
	$da[60][] = 'gwen@plisolutions.com';
	$da[60][] = 'nicole@plisolutions.com';
	$da[90][] = 'gwen@plisolutions.com';
	$da[90][] = 'shannon@plisolutions.com';
	$da[90][] = 'arthur@plisolutions.com';
	//Salesman
	$sm['0003'][] = 'david@plisolutions.com';
	$sm['0057'][] = 'beau@plisolutions.com';
	//Dept
	$dp[30][] = 'arthur@plisolutions.com';
	$dp[40][] = 'arthur@plisolutions.com';
	$dp[50][] = 'arthur@plisolutions.com';
	$dp[60][] = 'clint@plisolutions.com';
	$dp[60][] = 'shannon@plisolutions.com';
	$dp[70][] = 'arthur@plisolutions.com';


function email_report($email, $subject, $body, $filename='', $cid='', $name='', $pdf = '' )
{
	if (isset(SPOOLING) && SPOOLING=='write')
	{
		$db['email'] = $email;
		$db['subject'] = $subject;
		$db['body'] = $body];
		$db['filename'] = $filename;
		$db['name'] = $name;
		$db['pdf'] = $pdf;
		$enc =json_encode($db);
		$file = '/var/www/email/'$email.$subject.time();
		$stream = fopen($file, 'w');
		fwrite($stream, $enc);
		fclose($stream);
		return $file;
	}


	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = EMAIL_USERNAME;                 // SMTP username
    $mail->Password = EMAIL_PASSWORD;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom(EMAIL_USERNAME, EMAIL_USERNAME_FROM);
    $mail->addAddress($email);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('administrator@plisolutions.com');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');
	if ($filename!= '' && $cid!='' && $name!='')
	{

		$mail->AddEmbeddedImage($filename, $cid, $name);
		echo "$filename $cid $name";

	}
	//$mail->Body = 'Your <b>HTML</b> with an embedded Image: <img src="cid:my-attach"> Here is an image!';
    //Attachments
	if ($pdf != "")
	{
		if (is_array($pdf))
		{
			foreach ($pdf as $p)
			{
				$mail->addAttachment($p);         // Add attachments
			}
		}
	}
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

}




	