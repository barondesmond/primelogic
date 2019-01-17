<?php
include("_db_config.php");
include("_auth_email.php");


if ($_REQUEST['email'] && $_REQUEST['hash'] && $_REQUEST['installationId'])
{
	$resp = auth_email_confirm($_REQUEST['email'], $_REQUEST['installationId'], $_REQUEST['hash']);
	echo $resp;
}