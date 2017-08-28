<?

class Dbconn
{
	
	/*
	private $host = "d2.igear.co.kr";
	private $dbuser = "cyi38317";
	private $dbpass = "wjdduddlf";
	private $dbname = "cyi38317";
	*/

	
	private $host = "localhost";
	private $dbuser = "tmuseum";
	private $dbpass = "tmuseum1234";
	private $dbname = "tmuseum";
	
	static $connect = "";

	private $loggingUsed= "N";
	private $logFolder = "";
	private $logFileName = "";

	public $result = "";

	// 소멸자 호출
	function __destruct(){
		@mysql_close();
		//echo "소멸";
	}

	function set_dbname($dbname){
		$this->dbname = $dbname;
	}

	public function db_escape_string($esc_str)
	{
		$this->db_connect();
		$result = mysql_real_escape_string($esc_str);
		$this->db_close();
		return $result;
	}

	// Db접속
	public  function db_connect(){

		$this->connect = mysql_pconnect( $this->host, $this->dbuser, $this->dbpass ) or die( " Error" );
		mysql_query("SET NAMES 'utf8'");
		mysql_query("set character_set_results=utf8");
		if( !mysql_select_db( $this->dbname, $this->connect ) ) {
			echo "$this->dbname select ERROR1 " ;
		}
	}

	public function db_close(){
		$this->connect = "";
		@mysql_close();
	}


	private function getmicrotime(){
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}


	private function log_write($msg){		// 로그쓰기

		if($this->loggingUsed != "Y")
			return;

		if(! $this->logFileName)
		{
			$today = date("Ymd");
			$this->logFileName = $this->logFolder."/" . $today . ".data";
		}

		$fp = @fopen($this->logFileName , "a+") or die("Can't open ".$this->logFileName);
		$filename = $this->logFileName;

		@fwrite($fp, "\n\n\n#$msg#IP:{$_SERVER['REMOTE_ADDR']}\n\n\n");
		@fclose($fp);
	}

	private function sql_error(){
		echo "<br>".mysql_errno()." : ".mysql_error();
		exit();
	}
	
	public function  getOneCol($query){  	//한 칼럼 구하기

		$this->db_connect();
		$result = mysql_query($query);

		if (!$result){
			echo "getOneCol Query :::::::::::> ".$query."<br>";
			echo ($this->sql_error());
		}
		else {
			if (mysql_num_rows($result))
				return mysql_result($result, 0, 0);
			else
				return 0;
		}
		$this->db_close();
	}


	public function getOneRow($query){ 	//한 행 구하기

		$this->db_connect();
		$result = mysql_query($query);

		if (!$result){
			echo "execSqlOneRow Query :::::::::::> ".$query."<br>";
			echo ($this->sql_error());
		}
		else return mysql_fetch_array($result);

		$this->db_close();
	}


	public function getList($sql){		//여러 행 구하기

		$this->db_connect();

		if($sql == ""){
			return false;
		}
		$result = mysql_query($sql) or die(mysql_errno().":".mysql_error());
		if(!$result){
			echo "execSqlList Query :::::::::::> ".$sql."<br>";
			echo mysql_errno().":".mysql_error();

		}
		$count	= 0;
		$data	= array();
		while($row = mysql_fetch_array($result)){
			$data[$count] = $row;
			$count++;
		}
		mysql_free_result($result);
		return $data;
		$this->db_close();
	}

	public function doQuery($query){  // insert, update, delete

		$this->db_connect();
		
		$time_start = $this->getmicrotime();
		$result = mysql_query($query);
		$time_end = $this->getmicrotime();
		$time = $time_end - $time_start;

		$getNow = date("YmdHms");
		$this->log_write($getNow . "||" . $time . "||" . $query);

		$key = mysql_insert_id();

		if (!$result){
			echo "execSqlUpdate_insertkey Query :::::::::::> ".$query."<br>";
			echo ($this->sql_error());
		}
		else return $key;
		$this->db_close();
	}

	public function in( $table, $cols ){ //테이븖명, 컬럼(배열) 넘기면  insert
		
		global $_SERVER;
		$this->db_connect();

		$_POST[w_date] = mktime();
		if(!$_POST	[w_id])$_POST	[w_id] = get_session("da_ss_adm_id");
		
		$sql = " insert into ".$table." set ";
		$len = count($cols);
		for($i=0; $i<$len; $i++){
			$sql .= $cols[$i]." = '".$_POST[$cols[$i]] ."',";
		}
		$sql = substr($sql, 0, strlen($sql)-1);
		
		$key = $this->doQuery($sql);
		return $key;
	}

//	public function insert( $table, $cols ){ //테이븖명, 컬럼(배열) 넘기면  insert
//		
//		global $_SERVER;
//		$this->db_connect();
//
//		$cols["w_date"] = mktime();
//		if(!$_POST	[w_id])$cols["w_id"] = get_session("da_ss_adm_id");
//		
//		$sql = " insert into ".$table." set ";
//		foreach($cols as $key=>$val){
//			$sql .= $key." = '".$val."',";
//		}
//		$sql = substr($sql, 0, strlen($sql)-1);
//		
//		$idx = $this->doQuery($sql);
//		return $idx;
//	}
	// 20140702 수정
	public function insert( $table, $cols ){ //테이븖명, 컬럼(배열) 넘기면  insert
		
		global $_SERVER;
		$this->db_connect();

		$isuDate = $this->getOneCol("SELECT COUNT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'bettl' AND TABLE_NAME = '".$table."' AND COLUMN_NAME='w_date';");
		if( $isuDate > 0 ){
			if( !$cols["w_date"] ) $cols["w_date"]	= mktime();
		} else {
			if( !$cols["in_date"] ) $cols["in_date"]	= mktime();
		}
			
		$isuID = $this->getOneCol("SELECT COUNT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'bettl' AND TABLE_NAME = '".$table."' AND COLUMN_NAME='w_id';");
		if( $isuID > 0 ){
			if(!$cols["w_id"]) $cols["w_id"] = get_session("da_ss_adm_id");
		} else {
			if(!$cols["in_id"]) $cols["in_id"] = get_session("da_ss_adm_id");
		}		

//		$cols["w_date"] = mktime();
//		if(!$_POST	[w_id]) $cols["w_id"] = get_session("da_ss_adm_id");
		
		$sql = " INSERT INTO ".$table." SET ";
		foreach($cols as $key=>$val){
			$sql .= $key." = '".$val."',";
		}
		$sql = substr($sql, 0, strlen($sql)-1);
		
		$idx = $this->doQuery($sql);
		return $idx;
	}

	public function up( $table, $cols, $idx ){ 
		$this->db_connect();

		$_POST[u_date] = mktime();
		if(!$_POST	[u_id])$_POST[u_id] = get_session("da_ss_adm_id");
		$_POST[l_date] = mktime();
		if(!$_POST	[l_id])$_POST[l_id] = get_session("da_ss_adm_id");
		
		$sql = " update ".$table." set ";
		$len = count($cols);
		for($i=0; $i<$len; $i++){
			$sql .= $cols[$i]." = '".$_POST[$cols[$i]] ."',";
		}
		$sql = substr($sql, 0, strlen($sql)-1);
		$sql .= " where idx = ".$idx;

		$this->doQuery($sql);
		return 1;
	}

//	public function update( $table, $cols, $idx ){ 
//		$this->db_connect();
//
//		$cols["u_date"]	= mktime();
//		if(!$cols	[u_id])$cols["u_id"]		= get_session("da_ss_adm_id");
//		
//		$sql = " update ".$table." set ";
//		foreach($cols as $key=>$val){
//			$sql .= $key." = '".$val."',";
//		}
//		$sql = substr($sql, 0, strlen($sql)-1);
//		$sql .= " where idx = ".$idx;
//		$this->doQuery($sql);
//		return 1;
//	}
	// 20140702 수정
	public function update( $table, $cols, $idx ){ 
		$this->db_connect();

		$isuDate = $this->getOneCol("SELECT COUNT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'bettl' AND TABLE_NAME = '".$table."' AND COLUMN_NAME='u_date';");
		if( $isuDate > 0 ){
//			$cols["u_date"]	= mktime();
			if( !$cols["u_date"] ) $cols["u_date"]	= mktime();
		} else {
			if( !$cols["up_date"] ) $cols["up_date"]	= mktime();
		}
			
		$isuID = $this->getOneCol("SELECT COUNT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'bettl' AND TABLE_NAME = '".$table."' AND COLUMN_NAME='u_id';");
		if( $isuID > 0 ){
			if(!$cols["u_id"]) $cols["u_id"] = get_session("da_ss_adm_id");
		} else {
			if(!$cols["up_id"]) $cols["up_id"] = get_session("da_ss_adm_id");
		}		
		$sql = " update ".$table." set ";
		foreach($cols as $key=>$val){
			$sql .= $key." = '".$val."',";
		}
		$sql = substr($sql, 0, strlen($sql)-1);
		$sql .= " where idx = ".$idx;
		$this->doQuery($sql);
		return 1;
	}
	
	public function del( $table, $idx ){ 
		global $da;
		$this->db_connect();
		
		if( substr($table, 0, 3) == "da_" ){
			$slen = strlen($table);
			$table = substr($table, 3);
		}

		$sql = " delete from da_".$table." where idx = ".$idx;
		$this->doQuery($sql);

		$list = $this->getList("select f_ori_name from da_file where tb_id = '".$table."' and tb_idx = ".$idx );
		$len = count($list);
		for($i=0; $i<$len; $i++){
			$row = $list[$i];
			@unlink($da['uploadPath']."/".$table."/".$row[0]);
		}

		$sql = " delete from da_file where tb_id = '".$table."' and tb_idx = ".$idx ;
		$this->doQuery($sql);

		return 1;
	}
	
	public function getDBname(){
		return $this->dbname;
	}
	
}
?>
