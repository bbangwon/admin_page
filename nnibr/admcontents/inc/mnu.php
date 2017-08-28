<?
	//관리자 세션체크
	if( !get_session("da_ss_adm_id") && !$login_check_pg )goto_url($da_adm_path."/login.html");
	//$m_sql = "select mnu_pid, mnu_pnm, mnu_id, mnu_dir, mnu_file from da_menu where mnu_pseq > 0 and mnu_seq = 1 order by mnu_pseq";

	$m_sql = "select da_menu.mnu_pid, da_menu.mnu_pnm, da_menu.mnu_id, da_menu.mnu_dir, da_menu.mnu_file ";
	$m_sql .= "from da_menu ";
	$m_sql .= "join da_admin_auth on da_menu.mnu_pid = da_admin_auth.mnu_pid ";
	$m_sql .= "where da_menu.mnu_pseq > 0 and da_menu.mnu_seq = 1 and da_admin_auth.m_id = '".get_session("da_ss_adm_id")."'";
	$m_sql .= "order by da_menu.mnu_pseq ";


	$_topMnu = $dbcon->getList($m_sql);
	
	if( !$mnu_id ){ //처음 로그인 시 

		if( get_session("da_ss_adm_level")=="100" ){
			$_pMnuId = "bettladmin";
			$admin_on = "on";
			$adm01_on = "on";
			$_MnuTitle = "관리자 관리";
		}else{

			$auth = get_session("da_ss_adm_auth");
			$tmp = explode("|",$auth);
			$mnu_id = $dbcon->getOneCol("select mnu_id from da_menu where mnu_pid = '".$tmp[1]."' and mnu_seq = '1' ");
			
			$_Mnu = $dbcon->getOneRow("select * from da_menu where mnu_id = '".$mnu_id."' ");
			$_pMnuId = $_Mnu[mnu_pid];
			${$_Mnu[mnu_pid]."_on"} = "on";
			${$_Mnu[mnu_id]."_on"} = "on";
			$_MnuTitle = $_Mnu[mnu_pnm];
		}
	}else{
		$_Mnu = $dbcon->getOneRow("select * from da_menu where mnu_id = '".$mnu_id."' ");
		$_pMnuId = $_Mnu[mnu_pid];
		${$_Mnu[mnu_pid]."_on"} = "on";
		${$_Mnu[mnu_id]."_on"} = "on";
		$_MnuTitle = $_Mnu[mnu_pnm];
	}
	
	//if( strpos(get_session("da_ss_adm_auth"), $_pMnuId)=="" && !$login_check_pg && get_session("da_ss_adm_level")!="100" && $mnu_id!="main" )alert("해당메뉴의 권한이 없습니다.");

	$_leftMnu = $dbcon->getList(" select mnu_pid, mnu_pnm, mnu_id, mnu_nm, mnu_dir, mnu_file from da_menu where mnu_pid = '".$_pMnuId."' and mnu_view = '1' order by mnu_seq ");
?>