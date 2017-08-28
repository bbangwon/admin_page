<?
	$login_check_pg = 1;
	include "_da.php";

	$mem = $dbcon->getOneRow("SELECT idx, adm_nm FROM da_admin WHERE adm_id='$id' AND adm_pw='$pw' ");
	if( $mem[idx] ){
		set_session("da_ss_adm_id",$id);
		set_session("da_ss_adm_nm",$mem[adm_nm]);
		
		$dbcon->doQuery("update da_admin set latest_login = now() where idx = ".$mem[idx]);

		goto_url("home/");
	}else{
		alert("일치하는 정보가 없습니다.");
	}
?>