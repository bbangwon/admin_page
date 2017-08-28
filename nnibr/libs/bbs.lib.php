<?
//게시판 설정
function getBbsCount($where){
	global $dbcon;
	
	$cnt = $dbcon->getOneCol("select count(*) from da_bbs_info where (1) ".$where );
	return $cnt;
}

function getBbsList($col="*", $where, $order="idx desc", $from=0, $rows=10){
	global $dbcon;

	$data = $dbcon->getList("select ".$col." from da_bbs_info where (1) ".$where." order by ".$order." limit ".$from." , ".$rows );
	return $data;
}

function insertBbs($pic){
	global $dbcon, $_POST;
	
	$_POST['b_tit_img'] = $pic;
	$col_ary = array("b_id","b_nm","b_type","b_category","b_use_thumb","b_w_lev","b_v_lev","b_l_lev","b_tit_img","w_date","w_id");
	$idkey = $dbcon->in("da_bbs_info",$col_ary);
	return $idkey;
}

function updateBbs($idx,$pic){
	global $dbcon, $_POST;

	$_POST['b_tit_img'] = $pic;
	$col_ary = array("b_id","b_nm","b_type","b_category","b_use_thumb","b_w_lev","b_v_lev","b_l_lev","b_tit_img","u_date","u_id");
	$idkey = $dbcon->up("da_bbs_info",$col_ary,$idx);
	return $idkey;
}


//게시판별
function getBoardCount($b_id, $where){
	global $dbcon;
	
	$cnt = $dbcon->getOneCol("select count(*) from da_board where b_id = '".$b_id."' ".$where );
	return $cnt;
}

function getBoardList($col="*", $b_id, $where, $order="idx desc", $from=0, $rows=10){
	global $dbcon;

	$data = $dbcon->getList("select ".$col." from da_board where b_id = '".$b_id."' ".$where." order by ".$order." limit ".$from." , ".$rows );
	return $data;
}

function insertBoard($thumb){
	global $dbcon, $_POST;
	
	$_POST['b_thumb'] = $thumb;
	$col_ary = array("b_type","b_title","b_cont","cat_id","b_thumb","b_secret","b_notice","b_hp","b_tel","b_email","b_url","b_r_title","b_r_cont","w_date","w_id");
	$idkey = $dbcon->in("da_board",$col_ary);
	return $idkey;
}

function updateBoard($idx,$thumb){
	global $dbcon, $_POST;

	$_POST['b_thumb'] = $thumb;
	$col_ary = array("b_type","b_title","b_cont","cat_id","b_thumb","b_secret","b_notice","b_hp","b_tel","b_email","b_url","b_r_title","b_r_cont","u_date","u_id");
	$idkey = $dbcon->up("da_board",$col_ary,$idx);
	return $idkey;
}


?>