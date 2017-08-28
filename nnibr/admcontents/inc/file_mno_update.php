<?
	include "_da.php";
	
	if(!$idx || !$mno) exit;
	$dbcon->doQuery("update da_file set f_mno = '".$mno."' where idx = ".$idx);
	echo 1;
?>