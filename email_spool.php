<?php
//email spool cron


include("_email.php");

define('SPOOLING', 'read');

if ($handle = opendir('/var/www/email')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            echo "$entry\n";
        }
    }
    closedir($handle);
}

?>