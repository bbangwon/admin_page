<?
	include "_da.php";
	include $da_path."/libs/admin.lib.php";

	$sql_common = "
		adm_id = '".$adm_id."',
		adm_nm = '".$adm_nm."',
		adm_enm = '".$adm_enm."',
		adm_level = '".$adm_level."',
		adm_part = '".$adm_part."',
		adm_position = '".$adm_position."',
		adm_mno = '".$adm_mno."',
		adm_hp = '".$adm_hp."',
		adm_email = '".$adm_email."',
		enter_date = '".$enter_date."',
		leave_date = '".$leave_date."'
	";

	$sql2 = " delete from da_admin_auth where m_id='".$adm_id."'";
	$dbcon->doQuery($sql2);

	for($i=0;$i<count($check_menu);$i++)
	{
		$sql2 = "insert into da_admin_auth set m_id='".$adm_id."', mnu_pid='".$check_menu[$i]."'";
		$dbcon->doQuery($sql2);
	}
	
	if( $act=="" ){

		$sql = " insert into da_admin set ".$sql_common." , adm_pw = '".$adm_pw."', w_date = NOW() ";
		$idkey = $dbcon->doQuery($sql);
	
		//권한등록
		//setAuth($adm_id, $auth);

		if( $idkey )alert("등록되었습니다.","adm_list.html?mnu_id=".$mnu_id);
		else alert("등록 중 오류발생! ");

	}else if($act=="u"){
		
		if($adm_pw)$sql_common .= " ,adm_pw = '".$adm_pw."'";
		$sql = " update da_admin set ".$sql_common." where idx = ".$idx;
		$idkey = $dbcon->doQuery($sql);
		
		//권한등록
		//setAuth($adm_id, $auth);
		
		alert("수정되었습니다. ","adm_list.html?mnu_id=".$mnu_id."&skey=".$skey."&page=".$page);

	}else if($act=="d"){
		
		$sql = " update da_admin set idDel='1', leave_date = now() where idx = ".$idx;
		$idkey = $dbcon->doQuery( $sql );
		if( $idkey ){
			alert("퇴사처리 되었습니다.","adm_list.html?mnu_id=".$mnu_id."&skey=".$skey."&page=".$page);
		}else{
			alert("삭제 중 오류발생! ");
		}
	}else if($act=="d2"){
		
		$sql = " delete from da_admin where idx = ".$idx;
		$idkey = $dbcon->doQuery( $sql );
		if( $idkey ){
			alert("삭제되었습니다.","out_list.html?mnu_id=".$mnu_id."&skey=".$skey."&page=".$page);
		}else{
			alert("삭제 중 오류발생! ");
		}
	}else{
		goto_url($da_adm_path);
	}
?>