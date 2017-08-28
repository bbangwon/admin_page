<?
	include "_da.php";

	echo "<BR><BR><BR><BR><pre>";
	print_r($_POST);

	$len = count($idxs);
	if($len==0){
		echo "<script>alert('������ ������ �������ּ���.');</script>";
		exit;
	}

	for($i=0; $i<$len; $i++){
		$dbcon->doQuery("delete from da_data where idx = ".$idxs[$i]);
	}
	echo "<script>top.location.reload();</script>";
?>