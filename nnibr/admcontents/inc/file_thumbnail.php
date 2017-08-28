<?
include "_da.php";
$resJSON = array( "success"=>"false", "msg"=>"" );

if( !$tb_id || !$tb_idx || !$fnm ) {
	$resJSON["msg"] = "썸네일을 지정하기 위한 정보가 부족합니다.";
	echo json_encode($resJSON);
	exit;
}

$sql = "SELECT * FROM da_".$tb_id." WHERE idx=".$tb_idx." AND isDel='0' ORDER BY idx DESC LIMIT 1;";
$orow = $dbcon->getOneRow($sql);

if( !$orow["idx"] ){
	$resJSON["msg"] = "대상 게시물을 찾을 수 없거나 삭제된 게시물입니다.";
	echo json_encode($resJSON);
	exit;
}

$isthumb = false;
foreach( $orow as $key=>$val ){
//	echo $key."<br />";
	if( $key."" == "thumbnail" ){
		$isthumb = true;
		break;
	}
}

if( $isthumb == false ){
	$resJSON["msg"] = $tb_id." 테이블에 thumbnail 필드가 없습니다.";
	echo json_encode($resJSON);
	exit;	
}

if( !$thumbW ) $thumbW = 140;
if( !$thumbH ) $thumbH = 160;
$srcfnm = $fnm;
$dstfnm = "thumb_".$fnm;
$srcDir = $DOCUMENT_ROOT."/uploaded/".$tb_id."/";
$dstDir = $DOCUMENT_ROOT."/uploaded/thumb/".$tb_id."/";
makeThumbnail($srcfnm,  $thumbW, $thumbH, $dstfnm, $srcDir, $dstDir);

if( $dstfnm == $orow["thumbnail"] ){
	$resJSON["msg"] = "썸네일로 등록된 파일입니다.";
	$resJSON["thumbnail"] = "true";	
	echo json_encode($resJSON);
	exit;	
}

$sql = "UPDATE da_".$tb_id." SET thumbnail='".$dstfnm."' WHERE idx=".$tb_idx;
$ures = $dbcon->doQuery($sql);
if( $ures < 1 ){
	$resJSON["msg"] = "썸네일 정보를 업데이트 하는데 실패하였습니다.";
	echo json_encode($resJSON);
	exit;	
}

$resJSON["success"] = "true";
echo json_encode($resJSON);
exit;
?>







