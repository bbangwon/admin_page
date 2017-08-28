<?
	include "_da.php";
	
	if($m_id)$_POST['m_nm'] = $dbcon->getOneCol("select m_nm from da_member where m_id = '".$m_id."' ");

	if( $act=="" || $act=="n" ){
		
		$m_id = get_session("da_ss_adm_id");
		$m_nm = get_session("da_ss_adm_nm");
		if( $c_name ) $m_nm = $c_name;
//		if( get_session("da_ss_adm_level") == 100 ){
//			$m_nm = $c_name;
//		} else {
//			$m_nm = get_session("da_ss_adm_nm");
//		}

		$sql = "insert into da_comment set tb_id='".$tb_id."', tb_idx='".$tb_idx."', m_id='".$m_id."', m_nm='".$m_nm."', c_cont='".$c_cont."', c_use='1', in_date=now(), in_id='".$m_id."', in_ip='".$_SERVER['REMOTE_ADDR']."' ";
		$idkey = $dbcon->doQuery($sql);
		
		// tm인 경우에는 da_tm을 업데이트
//		if( $tb_id == "tm" ) $dbcon->doQuery("UPDATE da_tm SET up_date=NOW() WHERE idx=".$tb_idx);

		if( $idkey )parent_reload("등록되었습니다.");
		else parent_reload("등록 중 오류발생! ");

	}else if($act=="u"){
		
		$m_id = get_session("da_ss_adm_id");
		if( get_session("da_ss_adm_level") == 100 ){
			$m_nm = $c_name?$c_name:get_session("da_ss_adm_nm");
		} else {
			$m_nm = get_session("da_ss_adm_nm");
		}

		$sql = "update da_comment set tb_id='".$tb_id."', tb_id='".$tb_idx."', m_id='".$m_id."', m_nm='".$m_nm."', c_cont='".$c_cont."', c_use='1', up_date=now(), up_id='".$m_id."', up_ip='".$_SERVER['REMOTE_ADDR']."' where idx = ".$idx;
		$idkey = $dbcon->doQuery($sql);

		if( $idkey )parent_reload("수정되었습니다.");
		else parent_reload("수정 중 오류발생! ");

	}else if($act=="d"){
		
		if( $dbcon->del( "da_comment", $idx) ){
			echo $idx;
		}else{
			echo "0";
		}
	} else if( $act == "en" ){
		$sql = "SELECT m_nm FROM da_comment WHERE idx=$idx";
		$onm = $dbcon->getOneCol($sql);
		if( $onm == $cmtwnm ){
			echo "동일한 이름입니다.";
			exit;
		}
		$sql = "update da_comment set m_nm='".$cmtwnm."' where idx = ".$idx;
		$idkey = $dbcon->doQuery($sql);

		if( $idkey ) echo "수정되었습니다.";
		else echo "수정 중 오류발생! ";

	}else{
		goto_url($da_adm_path);
	}
?>