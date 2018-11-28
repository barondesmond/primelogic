<?php

echo "test";
$server_instance = 'rds01.plis.local\esc';
$user = 'Reports';
$pass = 'Reports123!';
$con = @mssql_connect($server_instance, $user, $pass) or die('Error connecting to ' . $server_instance);

?>