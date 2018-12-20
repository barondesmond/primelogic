<?php



//email to authenticate user
function auth_user_email()
{


}
//auth user table

function auth_user_table()
{

	$table = auth_user_table_header();
	$table .= auth_user_table_footer();

return $table;
}

//form to submit authentication email
function auth_user_form()
{
	$table = '<form method=post action=' . $_SERVER['PHP_SELF'] . '>';
	$table .= 'Employee Name<input type=text name=EmpName><BR>';
	$table .= 'Employee Email<input type=text name=Email><BR>';
	//picture?/thumb?
	$table .= '<input type=submit name="Authenticate"><<BR>';
	$table .= '</form>';

return $table;

}

function auth_user_table_header()
{

	$table .=  '<html><body>';

return $table;
}

function auth_user_table_footer()
{
	$table .= '</body></html>';

return $table;
}

?>