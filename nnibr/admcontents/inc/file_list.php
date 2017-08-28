<?
	include "_da.php";
?>
<script>
$(document).ready(function(){
	$('.mnotxt').on('blur',function(){ 
		var idx = $(this).attr('idx');
		var mno = $(this).val();
		if( idx && mno ){
			$.post('../inc/file_mno_update.php',{'idx':idx, 'mno':mno},function(v){ 
				if(v==1)showTmsg("등록되었습니다.",1000);
				else alert(v);
			});
		}
	});
});
</script>
<?
	$tit_msg = "메모";

	$rst = "";
	$list = $dbcon->getList("select idx, tb_id, f_ori_name, f_save_name, f_size, f_width, f_height, f_mno from da_file where tmp_no = '".$tmp_no."' ");
	$len = count($list);
	for($i=0; $i<$len; $i++){
		$row = $list[$i];
		
		if($row[tb_id]=="gallery")$tit_msg = "Youtube";

		$rst .= "<span class='".$row[idx]."'>";
		$rst .= $row[f_save_name]. "(".$row[f_size]."kb) ";
		if( $row[f_width] )
			$rst .= " 사이즈: ".$row[f_width]."x".$row[f_height]." <a href='javascript:preview(".$row[idx].");' >[미리보기]</a> ";
	    
		$rst .= "<a href=\"javascript:fileDown('".$row[tb_id]."','".$row[f_ori_name]."');\">[다운]</a> <a href='javascript:fileDel(".$row[idx].");' >[삭제]</a><br>";
		$rst .= $tit_msg.": <input type='text' name='mno' class='w250 mnotxt' idx='".$row[idx]."' value='".$row[f_mno]."'><br>";
		$rst .= "</span>";
	}
	echo $rst;
?>

