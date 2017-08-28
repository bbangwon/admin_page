<?
	
	function writeCharSet($v="utf-8"){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=".$v."' />";
	}

	function getFileDownUrl($tb,$saveName,$fileName,$img=""){
		global $da_path;
		$viewName = $fileName;
		if($img)$viewName = $img;
		echo "<a href='".$da_path."/download.php?filepath=uploaded/".$tb."/".$saveName."&filename=".$fileName."'>".$viewName."</a>";
	}

	function doUpload($FileSaveDir, $MaxUploadMaga, $FormFieldName, $DeleteFile, $RenameFile) {

		if ($_FILES[$FormFieldName]['size'] > 0) {
		
			##########파일이름 변환#########
			$MaxFileSize=1024 * 1024 * $MaxUploadMaga;							
			$UpFileName = explode(".", $_FILES[$FormFieldName]['name']);
			$UpFileName[0] = time();
			$UpFileType =  strtolower($UpFileName[count($UpFileName) - 1]);
			$TargetFileName =  time().".".$UpFileType;

			$tmpArray	= Array("","","","");

			if ($RenameFile != "") {
				$TargetFileName = $RenameFile.".".$UpFileType;									
			} else {
				$i = 0;
				while(file_exists("$FileSaveDir$TargetFileName")) {
					$TargetFileName = $UpFileName[0]."_".$i++.".".$UpFileType;	
				}
			}

			if(preg_match("/^(php|html|php3|html|htm|asp|jsp|phtml|cgi|jhtml)$/i",$UpFileType))	{			
				echo " >>>>>>>>>>>업로드 할수 없는 파일 형식입니다. 업로드에 실패 하였습니다.<<<<<<<<<<<<<br>";
				
				$tmpArray[0] = $DeleteFile;
				return $tmpArray;											
			}  else {

				if($MaxFileSize < $_FILES[$FormFieldName]['size'])	{
					echo " >>>>>>>>>>파일  업로드 용량을 초과했습니다. 최대 업로드 용량은 ".$MaxUploadMaga."MB입니다.<<<<<<<<<<<<<br>";
				
					$tmpArray[0] = $DeleteFile;
					return $tmpArray;											

				} else {
					
					if (@move_uploaded_file($_FILES[$FormFieldName]['tmp_name'], "$FileSaveDir$TargetFileName")) { 
					 chmod("$FileSaveDir$TargetFileName",0777);
					//die();
						if ($DeleteFile != "" ) { 
							if ($DeleteFile != $TargetFileName) { 
								if (file_exists($FileSaveDir.$DeleteFile)) { 
									@unlink($FileSaveDir.$DeleteFile);
									@unlink($FileSaveDir."thumb/".$DeleteFile);
								}
							}
						}
					
						$tmpArray[0] = $_FILES[$FormFieldName]['name'];
						$tmpArray[1] = $TargetFileName;
						$tmpArray[2] = $UpFileType;
						$tmpArray[3] = $_FILES[$FormFieldName]['size'];
						$tmpArray[4] = @getimagesize($FileSaveDir.$TargetFileName);

						return $tmpArray;
					} else {
						echo  ">>>>>>>>>>임시 파일을 파일 저장 디렉토리로 옮기는 과정의 에러가 발생하여 업로드에 실패하였습니다.<<<<<<<<<<<<<br>";
						
						$tmpArray[0] = $DeleteFile;
						return $tmpArray;
					}
				}
			}
		} else {			
			$tmpArray[1] = $DeleteFile;
			return $tmpArray;
		}
	}	

	function fileInsert($ary,$table,$idx,$title=""){
		
		global $dbcon;
		
		if( !$ary[0] )return;
		$sql = " 
			insert into da_file set 
			df_title	= '{$title}',
			df_table	= '{$table}',
			df_table_idx= '{$idx}',
			df_ori_name	= '{$ary[0]}',
			df_real_name= '{$ary[1]}',
			df_file_size= '{$ary[3]}',
			df_file_ext	= '{$ary[2]}',
			in_id		= '{$_SESSION['tc_id']}',
			in_date		= now()
		";

		return $dbcon->execSqlUpdate($sql);

	}

	function fileUpdate($ary,$idx,$title=""){

		global $dbcon;
		
		$old = $dbcon->execSqlOneCol(" select df_real_name from da_file where df_idx = $idx ");
		@unlink("../uploaded/".$path."/thumb/".$old);

		$sql = " 
			update da_file set 
			df_title	= '{$title}',
			df_ori_name	= '{$ary[0]}',
			df_real_name= '{$ary[1]}',
			df_file_size= '{$ary[3]}',
			df_file_ext	= '{$ary[2]}',
			up_id		= '{$_SESSION['tc_id']}',
			up_date		= now()
			where df_idx = '{$idx}'
		";
		return $dbcon->execSqlUpdate($sql);

	}

	function fileDelete($idx,$path=""){

		global $dbcon;

		$old = $dbcon->execSqlOneCol(" select df_real_name from da_file where df_idx = $idx ");
		if( $path ){
			@unlink("../uploaded/".$path."/".$old);
			@unlink("../uploaded/".$path."/thumb/".$old);
		}
		
		$sql = " delete from da_file where df_idx = '{$idx}' ";
		return $dbcon->execSqlUpdate($sql);

	}

	function makeThumbnail($img_name, $width, $height, $save_name,$imgDir,$imgThumbDir){

		$gd = gd_info();
		$gdver = substr(preg_replace("/[^0-9]/", "", $gd['GD Version']), 0, 1);
		if(!$gdver) alert("GD 버젼체크 실패거나 GD 버젼이 1 미만입니다.");

		$srcname = $imgDir."/".$img_name;
		$timg = getimagesize($srcname);

		if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) return; //"확장자가 jp(e)g/png/gif 가 아닙니다.";

		if($timg[2] == 2){			//JPG
			$cfile = imagecreatefromjpeg($srcname);

			if($gdver == 2){
				$dest = imagecreatetruecolor($width, $height);
				imagecopyresampled($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}else{
				$dest = imagecreate($width, $height);
				imagecopyresized($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}

			imagejpeg($dest, $imgThumbDir."/".$save_name, 100);

		}else if($timg[2] == 3){	//PNG
			$cfile = imagecreatefrompng($srcname);

			if($gdver == 2){
				$dest = imagecreatetruecolor($width, $height);
				imagecopyresampled($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}else{
				$dest = imagecreate($width, $height);
				imagecopyresized($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
		}
			imagepng($dest, $imgThumbDir."/".$save_name, 9);

		}else if($timg[2] == 1){	//GIF
			$cfile = imagecreatefromgif($srcname);

			if($gdver == 2){
				$dest = imagecreatetruecolor($width, $height);
				imagecopyresampled($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}else{
				$dest = imagecreate($width, $height);
				imagecopyresized($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}
				imagegif($dest, $imgThumbDir."/".$save_name, 100);
		}

		imagedestroy($dest);
		return 1;
	}

	function getFilelist($tb, $idx){
		global $dbcon;

		$flist = $dbcon->execSqlList("select * from da_file where df_table = '{$tb}' and df_table_idx = '{$idx}'");
		return $flist;
	}

	function getBoardInfo($tb){

		global $dbcon;
		return $dbcon->execSqlOneRow("select * from da_board_info where db_table= '$tb' ");
	}

	function goto_url($url){
		
		echo "<script language='JavaScript'> location.href='$url'; </script>";
		exit;
	}

	function set_session($session_name, $value){

		$$session_name = $_SESSION["$session_name"] = $value;
	}

	function get_session($session_name){

		return $_SESSION[$session_name];
	}

	function set_cookie($cookie_name, $value, $expire){

		setcookie(md5($cookie_name), base64_encode($value), time() + $expire, '/', '');
	}


	function get_cookie($cookie_name){
		return base64_decode($_COOKIE[md5($cookie_name)]);
	}


	function alert($msg='', $url=''){
		
		if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset={$da['charset']}\">";
		echo "<script language='javascript'>alert('$msg');";
		if (!$url)
			echo "history.go(-1);";
		echo "</script>";
		if ($url)
			goto_url($url);
		exit;
	}

	function parent_reload($msg=""){
		
		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset={$da['charset']}\">";
		if($msg)echo "<script language='javascript'>alert('$msg'); </script>";
		echo "<script language='javascript'>parent.document.location.reload(); </script>";
		
		exit;
	}

	function alert_msg($msg=''){
		
		if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset={$da['charset']}\">";
		echo "<script language='javascript'>alert('$msg');";		
		echo "</script>";
		
		exit;
	}

	function alert_close($msg){

		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset={$da['charset']}\">";
		echo "<script language='javascript'> alert('$msg'); window.close(); </script>";
		exit;
	}

	function alert_close_reflash($msg){

		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset={$da['charset']}\">";
		echo "<script language='javascript'> opener.location.reload(); alert('$msg'); window.close(); </script>";
		exit;
	}

	function url_auto_link($str){

		$str = preg_replace("/&lt;/", "\t_lt_\t", $str);
		$str = preg_replace("/&gt;/", "\t_gt_\t", $str);
		$str = preg_replace("/&amp;/", "&", $str);
		$str = preg_replace("/&quot;/", "\"", $str);
		$str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);
		$str = preg_replace("/([^(http:\/\/)]|\(|^)(www\.[^[:space:]]+)/i", "\\1<A HREF=\"http://\\2\" TARGET='_blank'>\\2</A>", $str);
		$str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i", "\\1<A HREF=\"\\2\" TARGET='_blank'>\\2</A>", $str);
		
		$str = preg_replace("/([0-9a-z]([-_\.]?[0-9a-z])*@[0-9a-z]([-_\.]?[0-9a-z])*\.[a-z]{2,4})/i", "<a href='mailto:\\1'>\\1</a>", $str);
		$str = preg_replace("/\t_nbsp_\t/", "&nbsp;" , $str);
		$str = preg_replace("/\t_lt_\t/", "&lt;", $str);
		$str = preg_replace("/\t_gt_\t/", "&gt;", $str);

		return $str;
	}

	function get_filesize($size){

		if ($size >= 1048576) {
			$size = number_format($size/1048576, 1) . "M";
		} else if ($size >= 1024) {
			$size = number_format($size/1024, 1) . "K";
		} else {
			$size = number_format($size, 0) . "byte";
		}
		return $size;
	}

	function cut_str($str, $len, $suffix="…"){

		$s = substr($str, 0, $len);
		$cnt = 0;
		for ($i=0; $i<strlen($s); $i++)
			if (ord($s[$i]) > 127)
				$cnt++;
		
		$s = substr($s, 0, $len - ($cnt % 3));  // utf-8:3, euc-kr:2
		
		if (strlen($s) >= strlen($str))
			$suffix = "";
		return $s . $suffix;
	}

	function get_paging($write_pages, $cur_page, $total_page, $url, $add=""){
		$str = "";
		if ($cur_page > 1) {
			$str .= "<a href='" . $url . "1{$add}' class='pagingLink'>처음</a>";
			//$str .= "[<a href='" . $url . ($cur_page-1) . "'>이전</a>]";
		}

		$start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
		$end_page = $start_page + $write_pages - 1;

		if ($end_page >= $total_page) $end_page = $total_page;

		if ($start_page > 1) $str .= " &nbsp;<a href='" . $url . ($start_page-1) . "{$add}' class='pagingLink'>이전</a>";

		if ($total_page > 1) {
			for ($k=$start_page;$k<=$end_page;$k++) {
				if ($cur_page != $k)
					$str .= " &nbsp;<a href='$url$k{$add}' class='pagingLink'>$k</a>";
				else
					$str .= " &nbsp;<a href='javascript:;' class='selected'><span>$k</span></a> ";
			}
		}

		if ($total_page > $end_page) $str .= " &nbsp;<a href='" . $url . ($end_page+1) . "{$add}' class='pagingLink'>다음</a>";

		if ($cur_page < $total_page) {
			//$str .= "[<a href='$url" . ($cur_page+1) . "'>다음</a>]";
			$str .= " &nbsp;<a href='$url$total_page{$add}' class='pagingLink'>맨끝</a>";
		}
		$str .= "";

		return $str;
	}
	

	
					


	function get_paging2($write_pages, $cur_page, $total_page, $url, $add=""){
		$str = "";
		if ($cur_page > 1) {
			$str .= "<a href='" . $url . "1{$add}' class='paging-first'><span class='screen-hide'>첫페이지</span></a>";
		}

		$start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
		$end_page = $start_page + $write_pages - 1;

		if ($end_page >= $total_page) $end_page = $total_page;

		if ($start_page > 1) $str .= " <a href='" . $url . ($start_page-1) . "{$add}' class='paging-prev'><span class='screen-hide'>이전페이지</span></a>";

		if ($total_page > 1) {
			for ($k=$start_page;$k<=$end_page;$k++) {
				if ($cur_page != $k)
					$str .= "<a href='$url$k{$add}' >$k</a>";
				else
					$str .= "<a href='javascript:;''><span>$k</span></a> ";
			}
		}

		if ($total_page > $end_page) $str .= "<a href='" . $url . ($end_page+1) . "{$add}' class='paging-next'><span class='screen-hide'>다음페이지</span></a>";

		if ($cur_page < $total_page) {
			$str .= "<a href='$url$total_page{$add}' class='paging-last'><span class='screen-hide'>마지막페이지</span></a>";
		}
		$str .= "";

		return $str;
	}
	

	function getNewIcon($date, $period=7){

		global $da_path;
		if( !$date) return "";
		$date = str_replace("-","",substr($date,0,10));
		if( $date > date("Ymd", strtotime("-{$period} day")) )
			return "<img src='{$da_path}/_img/new.gif' align=''>";
		else
			return "";
	}
	
	function spacer($year, $month)
	{
		$day = 1;
		$spacer = array(0, 3, 2, 5, 0, 3, 5, 1, 4, 6, 2, 4);
		$year = $year - ($month < 3);
		$result = ($year + (int) ($year/4) - (int) ($year/100) + (int) ($year/400) + $spacer[$month-1] + $day) % 7;
		return $result;
	}

	function SkipOffset($no,$sdate='',$edate='')
	{  
	  for($i=1;$i<=$no;$i++) { 
		$ck = $no-$i+1;
		if($sdate) $num = date('d',$sdate-((3600*24)*$ck));
		if($edate) $num=$i;
		echo "  <TD style='background:#FFFFFF;' align=center><style='color:#D4D4D4'>$num</style></TD> \n";	
	  }
	}

	function getWriteDate($time,$date){
		
		if( $time < 60 )return $time."초 전";
		else if( $time < 3600 )return "약".round($time/60)."분 전";
		else if( $time < 86400 )return "약".round($time/3600)."시간 전";
		else return substr($date,0,10);
	}

	function _getOneImg1($tb_id, $tb_idx, $width="80", $where="", $height=65){
		global $dbcon;
	
		$str = '<img src="'.$da["noimage"].'" width="'.$width.'" height="'.$height.'" />';
	
		$data = $dbcon->getOneRow("select f_ori_name, f_mno from da_file where tb_id = '".$tb_id."' and tb_idx = '".$tb_idx."' ".$where." order by f_seq limit 1 ");
		if(!$data[0]){
			return $str;
		}
			
	
		$size = getimagesize("../uploaded/".$tb_id."/".$data[0]);
		if( $size[0] > $width )$w = $width;
		else $w = $data[2];
	
	
		if( $size[0] !== "" ){
			$str = "<img src='../uploaded/".$tb_id."/".$data[0]."' ".'onerror="this.src=\''.$da["noimage"].'\'"'." width='".$width."' height='".$height."'>";
			//$str = "<img src='../../uploaded/".$tb_id."/".$data[0]."' width='".$w."' >";
		}
	
		echo $str;
	}
	function getUrl($v){
		$v = str_replace("http://","",$v);
		$v = "http://".$v;
		return $v;
	}

	function isImage($ext){

		$ext = strtoupper($ext);
		if( $ext=="GIF" || $ext=="JPG" || $ext=="PNG" || $ext=="BMP" )return "1";
		else return "0";
	}


	// 보내는이름/보내는메일/발송될메일/제목/내용/타입
	function sendMail($fname, $fmail, $to, $subject, $content, $file="", $cc="", $bcc="") 
	{
		$fname   = "=?UTF-8?B?" . base64_encode($fname) . "?=";
		$subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
		
		$header  = "Return-Path: <$fmail>\n";
		$header .= "From: $fname <$fmail>\n";
		$header .= "Reply-To: <$fmail>\n";
		if ($cc)  $header .= "Cc: $cc\n";
		if ($bcc) $header .= "Bcc: $bcc\n";
		$header .= "MIME-Version: 1.0\n";

		// UTF-8 관련 수정
		$header .= "X-Mailer: Tourcoach Mailer 0.92 (tourcoach.co.kr) : $_SERVER[SERVER_ADDR] : $_SERVER[REMOTE_ADDR] : www.tourcoach.co.kr : $_SERVER[PHP_SELF] : $_SERVER[HTTP_REFERER] \n";

		if ($file != "") {
			$boundary = uniqid("http://tourcoach.co.kr/");

			$header .= "Content-type: MULTIPART/MIXED; BOUNDARY=\"$boundary\"\n\n";
			$header .= "--$boundary\n";
		}

		if ($type) {
			$header .= "Content-Type: TEXT/HTML; charset=UTF-8\n";
			if ($type == 2)
				$content = nl2br($content);
		} else {
			$header .= "Content-Type: TEXT/PLAIN; charset=UTF-8\n";
			$content = stripslashes($content);
		}
		$header .= "Content-Transfer-Encoding: BASE64\n\n";
		$header .= chunk_split(base64_encode($content)) . "\n";

		if ($file != "") {
			foreach ($file as $f) {
				$header .= "\n--$boundary\n";
				$header .= "Content-Type: APPLICATION/OCTET-STREAM; name=\"$f[name]\"\n";
				$header .= "Content-Transfer-Encoding: BASE64\n";
				$header .= "Content-Disposition: inline; filename=\"$f[name]\"\n";

				$header .= "\n";
				$header .= chunk_split(base64_encode($f[data]));
				$header .= "\n";
			}
			$header .= "--$boundary--\n";
		}
		return mail($to, $subject, "", $header);
	}

	function mail_log($title, $dm_idx, $email){

		global $dbcon, $_SESSION;
		$agree = "1";
		if( $dm_idx )$agree = $dbcon->execSqlOneCol(" select da_email_agree from da_member where dm_idx = {$dm_idx} ");

		if( $agree )
			$dbcon->execSqlUpdate(" insert into da_mail_log (dm_idx,sml_title,sml_email,in_date,in_id) values('{$dm_idx}','{$title}','{$email}',now(),'{$_SESSION['tc_id']}') ");
	}
	

	function mdpass($val){
		return md5($val);
	}

	function getHttpUrl($url){
		$url = str_replace("http://","",$url);
		$url = "http://".$url;
		return $url;
	}

	function getDateFormat($time, $format="Y.m.d"){
		if(!$time)return "";
		else return date($format, $time);
	}

	function getMember($col="*", $id="", $idx=0){
		global $dbcon;
		if( $id )$where = " id = '".$id."' ";
		else if( $idx )$where = " idx = ".$idx;
		
		return $dbcon->getOneRow("select ".$col." from da_member where ".$where);
	}

	function getCodeName($id){
		global $dbcon;

		return $dbcon->getOneCol("Select cd_name1 from da_code where cd_id = '".$id."' ");
	}

	function getCateName($cid){
		global $dbcon;

		return $dbcon->getOneCol("Select cat_nm from da_category where cat_id = '".$cid."' ");
	}

	function getNextCategory($cat_id){
		global $dbcon;
		
		if(!$cat_id)return "";

		$cat1 = substr($cat_id,0,3);
		$cat2 = substr($cat_id,3,3);
		$cat3 = substr($cat_id,6,3);
		$cat4 = substr($cat_id,9,3);

		if( $cat2=="000" )
			$cate = $dbcon->getList("select cat_id, cat_nm from da_category where substring(cat_id,1,3) = '".$cat1."' and substring(cat_id,4,3) != '000' and substring(cat_id,7,3) = '000' and cat_use = '1' ");
		else if( $cat3=="000" )
			$cate = $dbcon->getList("select cat_id, cat_nm from da_category where substring(cat_id,1,6) = '".$cat1.$cat2."' and substring(cat_id,7,3) != '000' and substring(cat_id,10,3) = '000' and cat_use = '1' ");
		else
			$cate = $dbcon->getList("select cat_id, cat_nm from da_category where substring(cat_id,1,9) = '".$cat1.$cat2.$cat3."' and substring(cat_id,10,3) != '000' and cat_use = '1'  ");

		return $cate;
	}

	function getFileExt($file){
		$tmp = explode(".",$file);
		$len = count($tmp);
		$ext = $tmp[$len-1];
		return $ext;
	}

	function chkReferer(){
		
		global $da;

		if (preg_match('/\.'.$da['domain'].'.(com|co.kr)/', $_SERVER['HTTP_REFERER'])){
	 
		}else{
			header("Location:".$da['siteURL']);
		}

	}
	
	/*
	da_company.grade 점수로직 반영,   da_company_rank back process 로 순위 정열하기, da_category_avg 산업군별 평균 
	*/

	function companyGradeUpdate($c_idx){
		
		global $dbcon;
		
		//업체 댓글 카운트용 (삭제글 미포함)
		$rcount = $dbcon->getOneCol("select count(*) from da_company_comment where c_idx = ".$c_idx." and isDel = '0' and stat = '1' ");
		$rcount2 = $dbcon->getOneCol("select count(*) from da_company_comment_reply where c_idx = ".$c_idx."  and isDel = '0' and stat = '1' ");
		$r_count = $rcount + $rcount2;

		//업체 평균점수 업데이트용
		$count = $dbcon->getOneCol("select count(*) from da_company_comment where c_idx = ".$c_idx." /*and isDel = '0'*/ and stat = '1' ");
		$count2 = $dbcon->getOneCol("select count(*) from da_company_comment_reply where c_idx = ".$c_idx."  /*and isDel = '0'*/ and stat = '1' ");
		$total_count = $count + $count2;

		$sql = " select sum(sc_vision), sum(sc_pay), sum(sc_work), sum(sc_culture), sum(sc_welfare), sum(sc_total) from da_company_comment where c_idx = ".$c_idx."  /*and isDel = '0'*/ and stat = '1' ";
		$data = $dbcon->getOneRow( $sql );

		$sc_vision = @round($data[0]/$count,2);
		$sc_pay = @round($data[1]/$count,2);
		$sc_work = @round($data[2]/$count,2);
		$sc_culture = @round($data[3]/$count,2);
		$sc_welfare = @round($data[4]/$count,2);
		$sc_total = @round($data[5]/$count,2);
		
		if($r_count > 2 )$ord = 1;
		else $ord = 2;

		$sql = "update da_company set sc_vision = ".$sc_vision.", sc_pay = ".$sc_pay.", sc_work = ".$sc_work.", sc_culture = ".$sc_culture.", sc_welfare = ".$sc_welfare.", sc_total = ".$sc_total.", commcnt = ".$total_count." , ord = '".$ord."' where idx = ".$c_idx;
		$dbcon->doQuery($sql);
	}	

	function updateRank(){

		global $dbcon;
		
		$dbcon->doQuery("TRUNCATE da_company_rank ");
		
		//Total Rank
		$sql = " select idx from da_company where ord = '1' order by sc_total desc, idx desc ";
		$list = $dbcon->getList( $sql );
		$len = count($list);
		for($i=0; $i<$len; $i++){
			$row = $list[$i];
			$rank = $i+1;
			$dbcon->doQuery("insert into da_company_rank set cidx = ".$row[idx].", t_rank = ".$rank);
		}

		$sql = " select idx from da_company where ord = '2' order by sc_total desc, idx desc ";
		$list = $dbcon->getList( $sql );
		$len = count($list);
		for($i=0; $i<$len; $i++){
			$row = $list[$i];
			$rank++;
			$dbcon->doQuery("insert into da_company_rank set cidx = ".$row[idx].", t_rank = ".$rank);
		}
		
		$rank = 0;
		//Category List
		$clist = $dbcon->getList(" select cat_id from da_category where cat_id like '00100%' and cat_id != '001000000000' order by cat_seq ");
		$clen = count($clist);
	
		for($j=0; $j<$clen; $j++){
			$crow = $clist[$j];
			$cate = $crow[cat_id];
			
			$rank = 0;
			//Category Rank
			$sql = " select idx from da_company where ord = '1' and cate = '".$cate."' order by sc_total desc, idx desc ";
			$list = $dbcon->getList( $sql );
			$len = count($list);
			for($i=0; $i<$len; $i++){
				$row = $list[$i];
				$rank = $i+1;
				$dbcon->doQuery("update da_company_rank set c_rank = ".$rank." where cidx = ".$row[idx]);
			}

			$sql = " select idx from da_company where ord = '2' and cate = '".$cate."'  order by sc_total desc, idx desc ";
			$list = $dbcon->getList( $sql );
			$len = count($list);
			for($i=0; $i<$len; $i++){
				$row = $list[$i];
				$rank++;
				$dbcon->doQuery("update da_company_rank set c_rank = ".$rank." where cidx = ".$row[idx]);
			}

		}
	}

	function updateAvg($cate){//산업군 평균 업데이트
		global $dbcon;
		if(!$cate)return;

		$len = $dbcon->getOneCol("Select count(*) from da_company where cate = ".$cate." and isDel = '0' and sc_total > 0 ");

		$data = $dbcon->getOneRow("select sum(sc_vision), sum(sc_pay), sum(sc_work), sum(sc_culture), sum(sc_welfare), sum(sc_total) from da_company where cate = ".$cate." and isDel = '0' "); 

		$sc1 = round(($data[0])/$len,2);
		$sc2 = round(($data[1])/$len,2);
		$sc3 = round(($data[2])/$len,2);
		$sc4 = round(($data[3])/$len,2);
		$sc5 = round(($data[4])/$len,2);
		$sc6 = round(($data[4])/$len,2);

		$old = $dbcon->getOneCol("select cat_id from da_category_avg where cat_id = '".$cate."' ");
		if($old){
			$dbcon->doQuery("update da_category_avg set sc_vision = '".$sc1."',  sc_pay = '".$sc2."',  sc_work = '".$sc3."',  sc_culture = '".$sc4."',  sc_welfare = '".$sc5."',  sc_total = '".$sc6."' where cat_id = '".$cate."' ");
		}else{
			$dbcon->doQuery("insert into da_category_avg set sc_vision = '".$sc1."',  sc_pay = '".$sc2."',  sc_work = '".$sc3."',  sc_culture = '".$sc4."',  sc_welfare = '".$sc5."',  sc_total = '".$sc6."' , cat_id = '".$cate."' ");
		}
	}

	function procGood($tb_id, $tb_idx, $m_id, $m_nm, $stat){
		global $dbcon;
		
		if($stat=="1")$col = "good";
		else $col = "nogood";

		$dbcon->doQuery("insert into da_good set tb_id='".$tb_id."', tb_idx='".$tb_idx."', m_id='".$m_id."', m_nm='".$m_nm."', g_grd='".$stat."', w_date = now() ");
		$dbcon->doQuery("update da_".$tb_id." set ".$col." = ".$col." + 1 where idx = ".$tb_idx);
	}

	function inPoint($m_id, $m_nm, $point, $p_table="", $p_idx=0, $mno){
		global $dbcon;
		
		if(!$m_id)return;

		$in_idx = $dbcon->doQuery("insert into da_point set m_id='".$m_id."', m_nm='".$m_nm."', p_point='".$point."', p_table='".$p_table."', p_idx='".$p_idx."', p_mno='".$mno."', w_date=now(), w_id='".$m_id."' ");
		$total = $dbcon->getOneCol("select sum(p_point) from da_point where m_id = '".$m_id."' ");
		$dbcon->doQuery("update da_point set p_result = ".$total." where idx = ".$in_idx);
		$dbcon->doQuery("update da_member set point = ".$total." where id = '".$m_id."' ");
		
		setLevel($m_id);

		return $total;
	}

	function delPoint($idx, $m_id){
		global $dbcon;
		
		if(!$m_id)return;

		$in_idx = $dbcon->doQuery("delete from da_point where idx = ".$idx);
		$total = $dbcon->getOneCol("select sum(p_point) from da_point where m_id = '".$m_id."' ");
		$dbcon->doQuery("update da_point set p_result = ".$total." where idx = ".$in_idx);
		$dbcon->doQuery("update da_member set point = ".$total." where id = '".$m_id."' ");
		
		setLevel($m_id);

		return $total;
	}

	function countBoard($id){
		global $dbcon;

		$cnt1 = $dbcon->getOneCol("select count(*) from da_news where id = '".$id."' and isDel = '0' ");
		$cnt2 = $dbcon->getOneCol("select count(*) from da_free where id = '".$id."' and isDel = '0' ");
		$total = number_format($cnt1 + $cnt2);

		$dbcon->doQuery("update da_member set bbs_cnt = ".$total." where id = '".$id."' ");
	}

	function countComment($id){
		global $dbcon;

		$cnt = $dbcon->getOneCol("select count(*) from da_comment where m_id = '".$id."' and c_use = '1' ");

		$dbcon->doQuery("update da_member set cmt_cnt = ".$cnt." where id = '".$id."' ");
	}

	function countReview($id){
		global $dbcon;

		$cnt1 = $dbcon->getOneCol("select count(*) from da_company_comment where m_id = '".$id."'  /*and isDel = '0'*/ ");
		$cnt2 = $dbcon->getOneCol("select count(*) from da_company_comment_reply where m_id = '".$id."'  /*and isDel = '0'*/ ");
		$total = number_format($cnt1 + $cnt2);

		$dbcon->doQuery("update da_member set rev_cnt = ".$total." where id = '".$id."' ");
	}

	function setLevel($id){
		global $dbcon;

		$point = $dbcon->getOneCol("select point from da_member where id = '".$id."' ");
		$level = $dbcon->getOneCol("select level from da_level where point <= ".$point." order by  level desc limit 1 ");
		$dbcon->doQuery(" update da_member set level = ".$level." where id = '".$id."' ");

		return $level;
	}

	function saveMember($type, $id, $name, $uname,$image=""){
		global $dbcon;
		
		$old = $dbcon->getOneCol("select idx from da_member where id = '{$id}' ");
		if(!$old){
			$idkey = $dbcon->doQuery(" insert into da_member set snsMem='$type', id='$id', nm='{$name}', nick='{$name}', in_date=now(), in_ip='".$_SERVER['REMOTE_ADDR']."' ");
			return $idkey;
		}else{
			return $old;
		}
			
	}

	function dispDate($date){
		
		$today = date("Y-m-d");
		$tmp = substr($date,0,10);

		if($today==$tmp){
			return substr($date, 11,8);
		}else{
			return substr($date,2,8);
		}
		
	}

?>