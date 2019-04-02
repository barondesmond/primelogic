<?php
include("_db_config.php");
include("_user_app_auth.php");


$auth = UserAppAuth($_REQUEST);
if ($auth['authorized'] != '1')
{
	header('Content-Type: application/json');
	$auth['dispatchs'] = null;
	echo json_encode($auth);
	exit;
}

include("_location_api.php");
include("_job.php");
include("_employees.php");


//api app


if (isset($_REQUEST['add_job_group']))
{
	$sql = "INSERT INTO JobGroup (JobGroup) VALUES('" . $_REQUEST['JobGroup'] . "')";
	$res = mssql_query($sql);
	$js['sql'] = $sql;
	$js['error'] = mssql_get_last_message();
	header('Content-Type: application/json');
	echo json_encode($js);
	exit;
}

if (isset($_REQUEST['delete_job_group_employee']))
{
	if (isset($_REQUEST['JobGroup']) && (isset($_REQUEST['Job']) || isset($_REQUEST['Employee'])))
	{
		foreach ($_REQUEST['JobGroup'] as $JobGroup)
		{
			if (isset($_REQUEST['Employee']))
			{
				foreach ($_REQUEST['Employee']	as $EmpNo)
				{
					$sql = "DELETE FROM JobGroupEmployee WHERE JobGroupID = '" . $JobGroup . "' and  EmpNo =  '" . $EmpNo . "' ";
					$res = mssql_query($sql);
					$mes = mssql_get_last_message();
					if ($mes != '')
					{
						$js['error'][] = $mes; 
						$js['error'][] = $sql;						}
					}
				}
			if (isset($_REQUEST['Job']))
			{
				foreach ($_REQUEST['Job'] as $Job)
				{
					$sql = "DELETE FROM JobGroupEmployee WHERE JobGroupID = '" . $JobGroup . "' and  Job =  '" . $Job ."' ";
					$res = mssql_query($sql);
					$mes = mssql_get_last_message();
					if ($mes != '')
					{
						$js['error'][] = $mes;
						$js['error'][] = $sql;
					}
				}
			}
		}
		header('Content-Type: application/json');
		if (!isset($js['error']))
		{
			$js = $_REQUEST;
			$js['success'] = '1';
			
		}
		echo json_encode($js);
		exit;
	}

}

if (isset($_REQUEST['add_job_group_employee']))
{
	if (isset($_REQUEST['JobGroup']))
	{
		foreach ($_REQUEST['JobGroup'] as $JobGroup)
		{
			if (isset($_REQUEST['Employee']))
			{
				foreach ($_REQUEST['Employee']	as $EmpNo)
				{
					$sql = "INSERT INTO JobGroupEmployee (JobGroupID, EmpNo) VALUES ('$JobGroup', '$EmpNo')";
					$res = mssql_query($sql);
					$mes = mssql_get_last_message();
					if ($mes != '')
					{
						$js['error'][] = $mes; 
						$js['error'][] = $sql;						}
					}
				}
			if (isset($_REQUEST['Job']))
			{
				foreach ($_REQUEST['Job'] as $Job)
				{
					$sql = "INSERT INTO JobGroupEmployee (JobGroupID, Job) VALUES ('$JobGroup', '$Job')";
					$res = mssql_query($sql);
					$mes = mssql_get_last_message();
					if ($mes != '')
					{
						$js['error'][] = $mes;
						$js['error'][] = $sql;
					}
				}
			}
		}
		header('Content-Type: application/json');
		if (!isset($js['error']))
		{
			$js = $_REQUEST;
			$js['success'] = '1';
			
		}
		echo json_encode($js);
		exit;
	}
	else
	{
		$js['error'][] = 'No Job Group Selected';
		header('Content-Type: application/json');
		echo json_encode($js);
		exit;
	}
}

$js = jobs_query($_REQUEST['dev']);
$js = array_merge($js, employees_query($_REQUEST['dev']));
$js = array_merge($js, jobgroups_query($_REQUEST['dev']));
$js = array_merge($js, jobgroupemployees_query($_REQUEST['dev']));


header('Content-Type: application/json');

echo json_encode($js);

?>