<?php
include("_db_config.php");

unset($sql);
$sql[] = "DROP TABLE JobsDev";
$sql[] = "DROP TABLE DispatchDev"; 
$sql[] = "DROP TABLE DispTechDev";
$sql[] = "DROP TABLE PRTimeEntryDev";
$sql[] = "SELECT * INTO DispTechDev FROM DispTech"; 
$sql[] = "SELECT * INTO DispatchDev FROM Dispatch";
$sql[] = "SELECT * INTO JobsDev FROM Jobs";
$sql[] = "SELECT * INTO PRTimeEntryDev FROM PRTimeEntry";
$sql[] = "UPDATE DispTechDev SET ServiceMan = '0195' WHERE ServiceMan = '0173'";
$sql[] = "DELETE FROM TimeClockApp WHERE EmpNo = '0195'"; 
$sql[] = "DELETE FROM DispTechDev WHERE Status IN ( 'Traveling', 'Working') and ServiceMan = '0195'";
$sql[] = "DELETE FROM PRHours WHERE EmpNo = '0195'";


	function get_period_bounds($offset = -1) 
	{

	  $secondhalf  = ($offset % 2) == 0 xor (int) date('j') >= 15;
	 $monthnumber = ceil((int) date('n') + $offset / 2);;

	    $period_begin = mktime(0, 0, 0, // 00:00:00
                           $monthnumber,
                           $secondhalf ? 16 : 1);
	  $period_end   = mktime(0, 0, 0, // 00:00:00
                           $secondhalf ? $monthnumber + 1 : $monthnumber,
                           $secondhalf ? 0 : 15);

		return array($period_begin, $period_end);
	}

$ar = get_period_bounds();
$st = $ar[0];
$sp = $ar[1];
$day = 86400;
$work = 86400/8;
for ($i=$st; $i<$sp; $i = $i + $day)
{

$tc = array('EmpNo'=>'0195', 'installationId'=> 'Test', 'event'=>'Working', 'EmpActive'=>'0', 'Screen'=>'Employee', 'StartTime'=>$i, 'StopTime'=>$i+$work);
	$k = '';
	$v = '';

	foreach ($tc as $key=>$val)
	{
		$k .= "$key,";
		$v .= "'$val',";
	}
	$k = substr($k, 0, strlen($k)-1);
	$v = substr($v, 0, strlen($v)-1);
	$sql[] = "INSERT INTO TimeCLockApp ($k) VALUES ($v)";
	
}	

foreach ($sql as $id=>$q)
{
	mssql($q);
}
		
