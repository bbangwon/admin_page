<?
	include "_da.php";

	$img = $dbcon->getOneRow("select tb_id, f_ori_name from da_file where idx = ".$idx);
	echo $img[0]."/".$img[1];
?>