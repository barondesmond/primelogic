<?php
include("_email.php");

if (validate_email($_GET['email']))
{
	echo "Email sent to $_GET['email']";
}
else
{
	echo "Emai not validated $_GET['email']";
}
?>