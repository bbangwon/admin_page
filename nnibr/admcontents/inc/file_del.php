<?
	include "_da.php";

	if(!$idx)exit;

	$file = $dbcon->getOneRow("select tb_id, f_ori_name from da_file where idx = ".$idx);
	@unlink($da_path."/uploaded/".$file[0]."/".$file[1]);
	$dbcon->doQuery("delete from da_file where idx = ".$idx);
	echo $idx;
?>