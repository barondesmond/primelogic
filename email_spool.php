<?php
//email spool cron
<?php

include("_email.php");

define('SPOOLING', 'read');

if ($handle = opendir('.')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            echo "$entry\n";
        }
    }
    closedir($handle);
}

?>