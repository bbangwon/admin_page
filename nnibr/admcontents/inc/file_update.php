<?
	include "_da.php";
	include $da_path."/libs/file.lib.php";
	$path = $da_path."/uploaded/".$tb_id."/";
	$up_file = doUpload($path, $da['MaxUploadMaga'], $col, "", "");
	$idkey = file_insert($up_file);
	$dbcon->doQuery(" update da_file set tb_id = '".$tb_id."', tmp_no = '".$tmp_no."' where idx = ".$idkey);

	$rst = $up_file[0]. "(".$up_file[3]."kb) ";
	if( count($up_file[4][0]) )$rst .= " 사이즈: ".$up_file[4][0]."x".$up_file[4][1]." <a href='javascript:;' onclick='showPreview(\"".$idkey."\")'>[미리보기]</a>";
	echo $rst;
?>

