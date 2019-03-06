<?php

if (isset($_REQUEST['delete_job_group_employee']))
{
	if (isset($_REQUEST['JobGroup']))
	{
		foreach ($_REQUEST['JobGroup'] as $JobGroup)
		{
			if (isset($_REQUEST['Employee']))
			{
				foreach ($_REQUEST['Employee']	as $EmpNo)
				{
					$sql = "DELETE FROM JobGroupEmployee WHERE JobGroupID = '$JobGroup' and  EmpNo =  '$EmpNo' ";
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
					$sql = "DELETE FROM JobGroupEmployee WHERE JobGroupID = '$JobGroup' and  Job =  '$Job' ";
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
?>