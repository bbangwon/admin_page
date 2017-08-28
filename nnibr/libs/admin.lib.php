<?
	function getAdminCount($where){
		global $dbcon;
		
		$cnt = $dbcon->getOneCol("select count(*) from da_admin where (1) ".$where );
		return $cnt;
	}

	function getAdminList($col="*", $where, $order="idx desc", $from=0, $rows=10){
		global $dbcon;
	
		$data = $dbcon->getList("select ".$col." from da_admin where (1) ".$where." order by ".$order." limit ".$from." , ".$rows );
		return $data;
	}

	function getAuthName($auth){
		global $dbcon;
		
		$result = "";
		$tmp = explode("|",$auth);
		for($i=0; $i<count($tmp); $i++){
			if(!$tmp[$i])continue;
			$nm = $dbcon->getOneCol("select mnu_pnm from da_menu where mnu_pid = '".$tmp[$i]."' ");
			$result .= $nm." | ";
		}

		return $result;
	}

	function setAuth($id, $auth){
		global $dbcon;
		
		$dbcon->doQuery("delete from da_admin_auth where m_id = '".$id."' ");

		$len = count($auth);
		echo "<br>size: ".$len;
		for($i=0; $i<$len; $i++){
			$tmp = explode("-",$auth[$i]);
			$m_pid = $tmp[0];
			$m_id = $tmp[1];
			$dbcon->doQuery("insert into da_admin_auth set m_id = '".$id."', mnu_pid='".$m_pid."', mnu_id='".$m_id."' ");
		}
	}
?>