<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once("/var/www/html/vendor/autoload.php");
define('DIRD', '/var/www/email/');


	//days (0 current, 60 = 60-75, 90 = 90+)
	$da[0][] = 'service@plisolutions.com';
	$da[0][] = 'dispatch@plisolutions.com';
	$da[61][] = 'gwen@plisolutions.com';
	$da[61][] = 'nicole@plisolutions.com';
	$da[61][] = 'shannon@plisolutions.com';
	$da[91][] = 'gwen@plisolutions.com';
	$da[91][] = 'shannon@plisolutions.com';
	$da[91][] = 'arthur@plisolutions.com';
	
	//30-60 salesmen/dept group
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

	$emails = array('barondesmond@gmail.com', 'shannon@plisolutions.com', 'clint@plisolutions.com', 'gwen@plisolutions.com', 'david@plisolutions.com', 'beau@plisolutions.com', 
	'arthur@plisolutions.com', 'service@plisolutions.com','dispatch@plisolutions.com');


function get_calling_function() {
  // a funciton x has called a function y which called this
  // see stackoverflow.com/questions/190421
  $caller = debug_backtrace();
  $caller = $caller[2];
  $r = $caller['function'] . '()';
  if (isset($caller['class'])) {
    $r .= ' in ' . $caller['class'];
  }
  if (isset($caller['object'])) {
    $r .= ' (' . get_class($caller['object']) . ')';
  }
  return $r;
}

function email_from_gcf($func='')
{
	if ($func == '')
	{
		return EMAIL_USERNAME_FROM;
	}
	elseif ($func == 'location_no_email.php')
	{
		return 'Location Email';
	}
	elseif ($func == 'location_report.php')
	{
		return 'Priority Email';
	}
	elseif ($func == 'Account Overview')
	{
		return $func;
	}

return EMAIL_USERNAME_FROM;
}

function email_unsubscribe($email)
{
	$sql = "SELECT * FROM Time.dbo.EmailUnsubscribe WHERE Email = '$email'";
	$res = mssql_query($sql);
	$db = mssql_fetch_assoc($res);
	if ($db['Email'] == $email)
	{
		return true;
	}
return false;
}

function email_report($email, $subject, $body, $filename='', $cid='', $name='', $pdf = '', $func = '' )
{

	if ($func == '')
	{
		$func = trim($_SERVER['PHP_SELF']);
	}	
	if (email_unsubscribe($email))
	{
		return true;
	}
	if (SPOOLWRITE=='write')
	{
		$er_array = array('email', 'subject', 'body', 'filename', 'cid', 'name', 'pdf', 'func');
		for($i=0;$i<count($er_array);$i++)
		{
			$db[$er_array[$i]] = ${$er_array[$i]};
		}
		
		$enc =json_encode($db);
		echo "filesize = " . strlen($enc);
		$file = DIRD . time() . '.' . $email . '.' . urlencode($subject) .  '.email';
		$stream = fopen($file, 'w');
		fwrite($stream, $enc);
		fclose($stream);
		return $file;
		sleep(1);
	}
	$from = email_from_gcf($func);


	$mail = new PHPMailer(false);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = EMAIL_USERNAME;                 // SMTP username
    $mail->Password = EMAIL_PASSWORD;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom(EMAIL_USERNAME, $from);
    $mail->addAddress($email);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('dispatch@plisolutions.com');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');
	if ($filename!= '' && $cid!='' && $name!='')
	{

		$mail->AddEmbeddedImage($filename, $cid, $name);
		//echo "$filename $cid $name";

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
		else
		{
			//echo "attached file $pdf";
			$mail->addAttachment($pdf);
		}	
	}
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	error_log(json_encode($mail));

    $mail->send();
    //echo 'Message has been sent';
	return true;
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	return false;
}

return false;
}

function email_validate($email)
{
	global $emails;
	if (in_array($email, $emails))
	{
		return true;
	}
return false;
}
function email_job_report($email, $subject, $html)
{
	$html = str_replace('{EMAIL}', $email, $html);
	email_report($email, $subject, $html); 
}

function email_employees($subject, $html)
{
	$sql = "SELECT EmpName, Email FROM Employee WHERE Email != '' and Inactive = '0' ";
	$res = mssql_query($sql);
	while ($emp = mssql_fetch_assoc($res))
	{
		$html2 = str_replace('{Email}', $emp['Email'], $html);
		$html2 = str_replace('{EmpName}', $emp['EmpName'], $html2);
		print_r($emp);
		echo $html2;
		email_report($emp['Email'], $subject, $html2);

	}
}

	