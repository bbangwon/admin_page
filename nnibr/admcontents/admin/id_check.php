<?
	include "_da.php";

	if(!$id)exit;
	
	$data = $dbcon->getOneCol("select idx from da_admin where adm_id = '".$id."'" );
	echo $data;
?>