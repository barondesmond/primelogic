<?php
include("_db_config.php");

include("_email.php");

print_r($argv);
$email = $argv[1];
$subject = $argv[2];
$body = $argv[3];
$pdf = $argv[4];
$filename = '';
$cid = '';
$name = '';
$func = '';

email_report($email, $subject, $body, $filename, $cid, $name, $pdf, $func);
