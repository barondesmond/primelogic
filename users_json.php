<?php
include("_db_config.php");
include("_employees.php");



$js = employee_user();
header('Content-Type: application/json');
echo json_encode($js);
?>	