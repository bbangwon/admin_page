<?
$basefields = array("idx", "pidx", "category", "wname", "title", "contents", "hit", "good", "evil", "secret", "in_date", "in_id", "in_ip", "up_date", "up_id", "up_ip", "d_date", "d_id", "d_ip");

//게시판 설정
function getBBSCount($where){
	// 모든 게시판수 가져오기
	global $dbcon;
	
	$cnt = $dbcon->getOneCol("SELECT count(*) FROM da_bbs_info WHERE (1) ".$where );
	return $cnt;
}

function getBBSList($col="*", $where, $order="idx DESC", $from=0, $rows=10){
	// 모든 게시판 정보 가져오기
	global $dbcon;

	$data = $dbcon->getList("SELECT ".$col." FROM da_bbs_info WHERE (1) ".$where." ORDER BY ".$order." LIMIT ".$from." , ".$rows );
	return $data;
}

function getBBSinfo($bbsid){
	// 게시판 정보
	global $dbcon;
	$sql = "SELECT * FROM da_bbs_info WHERE bbsid='".$bbsid."';";
	$data = $dbcon->getOneRow($sql);
	return $data;
}

function getSearchWhere( $skind, $stext ){
	switch($skind){
		case "WID":
			// 작성자 ID로 검색
			$where = " AND ( in_id LIKE '%".$stext."%' ) ";
			break;
		case "WNM":
			// 작성자 이름으로 검색
			$where = " AND ( wname LIKE '%".$stext."%' ) ";
			break;
		case "INN":
			// 작성자 ID&이름으로 검색
			$where = " AND ( in_id LIKE '%".$stext."%' OR wname LIKE '%".$stext."%' ) ";
			break;
		case "TIT":
			// 제목으로 검색
			$where = " AND ( title LIKE '%".$stext."%' ) ";
			break;
		case "CNT":
			// 내용으로 검색
			$where = " AND ( contents LIKE '%".$stext."%' ) ";
			break;
		case "TNC":
			// 제목&내용으로 검색
			$where = " AND ( title LIKE '%".$stext."%' OR contents LIKE '%".$stext."%' ) ";
			break;
			
		default:
			// 전체에서 검색
			$where = " AND ( mnu_id LIKE '%".$stext."%' OR mnu_nm LIKE '%".$stext."%' OR  in_id LIKE '%".$stext."%' OR wname LIKE '%".$stext."%' OR title LIKE '%".$stext."%' OR contents LIKE '%".$stext."%' ) ";
			break;
	}
	return $where;
}

function instBBSInfo($cols){
	global $dbcon;
	$nidx = $dbcon->insert("da_bbs_info", $cols);
	if( $nidx ){ 
		return $nidx; 
	} else {
		return false;
	}
}

function setBBSInfo( $cols, $idx ){
	global $dbcon;
	$uidx = $dbcon->update("da_bbs_info", $cols, $idx);
	if( $uidx ){ 
		return true; 
	} else {
		return false;
	}
}

//게시판별
function getBoardCount( $bbsid, $where ){
	// 게시판 게시물 수 가져오기
	global $dbcon;
	$sql = "SELECT count(*) FROM da_board_$bbsid WHERE 1=1 ".$where.";";
	$cnt = $dbcon->getOneCol( $sql );
	return $cnt;
}

function getBoardList( $bbsid, $col="*", $where, $order="idx DESC", $from=0, $rows=10 ){
	// 게시판 게시물 가져오기
	global $dbcon;
	$sql = "SELECT ".$col." FROM da_board_$bbsid WHERE 1=1 ".$where." ORDER BY ".$order." LIMIT ".$from." , ".$rows.";";
	$data = $dbcon->getList( $sql );
	$len = count($data);
	for($i=0; $i<$len; $i++){
		$row = $data[$i];
		if(!$row["wname"] && $row["in_id"] ){
			$sql = "SELECT m_nm FROM da_member WHERE m_id='".$row["in_id"]."'";
			$row["wname"] = $dbcon->getOneCol($sql);
		}
		if(!$row["wname"] && $row["in_id"] ){
			$sql = "SELECT adm_nm FROM da_admin WHERE adm_id='".$row["in_id"]."'";
			$row["wname"] = $dbcon->getOneCol($sql);
			$row["wname"] = '<span class="ico-mem-admin">a</span>'.$row["wname"];
		}
		if(!$row["wname"] && $row["up_id"] ){
			$sql = "SELECT m_nm FROM da_member WHERE m_id='".$row["up_id"]."'";
			$row["wname"] = $dbcon->getOneCol($sql);
		}
		if(!$row["wname"] && $row["up_id"] ){
			$sql = "SELECT adm_nm FROM da_admin WHERE adm_id='".$row["up_id"]."'";
			$row["wname"] = $dbcon->getOneCol($sql);
			$row["wname"] = '<span class="ico-mem-admin">a</span>'.$row["wname"];
		}
		
		$row["wid"] = $row["up_id"]?$row["up_id"]:$row["in_id"];
		$sql = "SELECT COUNT(idx) FROM da_comment WHERE tb_id='board_".$bbsid."' AND tb_idx=".$row["idx"].";";
		$row["cmtcnt"] = $dbcon->getOneCol($sql);
		$data[$i] = $row;
	}
	return $data;
}

function instBoard( $bbsid, $cols ){
	// 신규 게시물 등록
	global $dbcon;
	$idkey = $dbcon->insert( "da_board_".$bbsid, $cols );
	$cols2 = array("pidx"=>$idkey);
	$dbcon->update( "da_board_".$bbsid, $cols2, $idkey );
	return $idkey;
}

function replyBoard( $bbsid, $cols ){
	// 답변 게시물 등록
	global $dbcon;
	$idkey = $dbcon->insert( "da_board_".$bbsid, $cols );
	return $idkey;
}

function editBoard( $bbsid, $cols, $idx ){
	// 게시물 수정
	global $dbcon;
	$idkey = $dbcon->update( "da_board_".$bbsid, $cols, $idx );
	return $idkey;
}

function delBoardIdx( $bbsid, $cols, $idx) {
	// 게시물 삭제
	global $dbcon;
	$idkey = $dbcon->update( "da_board_".$bbsid, $cols, $idx );
	// 자식 게시물이 있으면 같이 삭제
	$sql = "UPDATE da_board_".$bbsid." SET d_date=NOW(), d_id='".$cols["d_id"]."', d_ip='".$cols["d_ip"]."' WHERE d_id='' AND pidx=".$idx;
	$dbcon->doQuery($sql);
	return $idkey;
}

function getBoardIdx($bbsid, $idx){
	global $dbcon;
	global $basefields;
	$data = $dbcon->getOneRow("SELECT * FROM da_board_".$bbsid." WHERE idx=$idx;");
	
	if( count($data) > 0 ){
		$i=0;
		foreach($data as $key=>$val){
			if( in_array($key, $basefields) ) continue;
			if( intval($key) > 0 ) continue;
			$etclst[$i]["key"] = $key;
			$etclst[$i]["val"] = $val;
			
			$etclst[$i]["cmt"] = getFieldComment($bbsid, $key);
			$i++;
		}
		$data["etclst"] = $etclst;
	}
	$data["catnm"] = $data["category"];

	$bbsinfo = getBBSinfo($bbsid);
	if( $bbsinfo["bbskind"] == "bs0003" ){
		// 질답 게시판의 경우 답변 목록을 가져옴
		$anslst = getBoardList( $bbsid, "*", " AND pidx!=idx AND pidx=$idx AND d_id=''", $order="idx DESC", 0, 10 );
		if( count($anslst) > 0 ){
			$data["anslst"] = $anslst;
			$data["anscnt"] = count($anslst);
		}
	}
	
	return $data;	
}

function getBoardEtcField($bbsid){
	global $dbcon;
	global $basefields;
	$notinwhere = implode("','", $basefields);
	$sql = "SELECT TABLE_SCHEMA,TABLE_NAME,COLUMN_NAME,ORDINAL_POSITION,COLUMN_DEFAULT,DATA_TYPE,COLUMN_TYPE,COLUMN_KEY,EXTRA,COLUMN_COMMENT
					FROM INFORMATION_SCHEMA.COLUMNS
					WHERE TABLE_SCHEMA='".$dbcon->getDBname()."' AND TABLE_NAME='da_board_".$bbsid."' AND COLUMN_NAME NOT IN ('".$notinwhere."');";
	$list = $dbcon->getList($sql);
	$etclst = array();
	for($i=0; $i<count($list); $i++){
		$etclst[$i]["key"] = $list[$i]["COLUMN_NAME"];
		$etclst[$i]["val"] = "";
		$etclst[$i]["cmt"] = getFieldComment($bbsid, $list[$i]["COLUMN_NAME"]);
	}
	return $etclst;
}

function getFieldComment($bbsid, $fieldnm){
	global $dbcon;
	$sql = "SELECT TABLE_SCHEMA,TABLE_NAME,COLUMN_NAME,ORDINAL_POSITION,COLUMN_DEFAULT,DATA_TYPE,COLUMN_TYPE,COLUMN_KEY,EXTRA,COLUMN_COMMENT
					FROM INFORMATION_SCHEMA.COLUMNS
					WHERE TABLE_SCHEMA='".$dbcon->getDBname()."' AND TABLE_NAME='da_board_".$bbsid."' AND COLUMN_NAME='".$fieldnm."';";
	$fieldinfo = $dbcon->getOneRow($sql);
	return $fieldinfo["COLUMN_COMMENT"];
}


function setBoardFile($tb_id, $tb_idx, $tmp_no){
	global $dbcon;
	
	$sql = "UPDATE da_file SET tb_idx=".$tb_idx." WHERE tb_id='".$tb_id."' AND tmp_no='".$tmp_no."';";
	$dbcon->doQuery($sql);
	return true;
}



?>





