<?php
//timeclock authemp load authempst replace REQUEST with timeclock_authemp

$_REQUEST['installationId'] = $_REQUEST['timeclock_installationId'];
$_REQUEST['EmpNo'] = $_REQUEST['timeclock_EmpNo'];
error_log(json_encode($_REQUEST);

include("authempinst_json.php");
?>
