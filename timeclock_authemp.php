<?php
//timeclock authemp load authempst replace REQUEST with timeclock_authemp

$_REQUEST['installationId'] = $_REQUEST['timeclock']['installationId'];
$_REQUEST['EmpNo'] = $_REQUEST['timeclock']['EmpNo'];


include("authempinst_json.php");
?>
