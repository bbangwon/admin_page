<?
	$login_check_pg = 1;
	include "_da.php";

	session_destroy();
	goto_url("login.html");
?>