<?
	error_reporting(E_ALL ^ E_NOTICE);
	header("Content-Type: text/html; charset=UTF-8");
	header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

	if (!isset($set_time_limit)) $set_time_limit = 0;
	@set_time_limit($set_time_limit);

	if (isset($HTTP_POST_VARS) && !isset($_POST)) {
		$_POST   = &$HTTP_POST_VARS;
		$_GET    = &$HTTP_GET_VARS;
		$_SERVER = &$HTTP_SERVER_VARS;
		$_COOKIE = &$HTTP_COOKIE_VARS;
		$_ENV    = &$HTTP_ENV_VARS;
		$_FILES  = &$HTTP_POST_FILES;

		if (!isset($_SESSION))
			$_SESSION = &$HTTP_SESSION_VARS;
	}

	if( !get_magic_quotes_gpc() )
	{
		if( is_array($_GET) )
		{
			while( list($k, $v) = each($_GET) )
			{
				if( is_array($_GET[$k]) )
				{
					while( list($k2, $v2) = each($_GET[$k]) )
					{
						$_GET[$k][$k2] = addslashes($v2);
					}
					@reset($_GET[$k]);
				}
				else
				{
					$_GET[$k] = addslashes($v);
				}
			}
			@reset($_GET);
		}

		if( is_array($_POST) )
		{
			while( list($k, $v) = each($_POST) )
			{
				if( is_array($_POST[$k]) )
				{
					while( list($k2, $v2) = each($_POST[$k]) )
					{
						$_POST[$k][$k2] = addslashes($v2);
					}
					@reset($_POST[$k]);
				}
				else
				{
					$_POST[$k] = addslashes($v);
				}
			}
			@reset($_POST);
		}

		if( is_array($_COOKIE) )
		{
			while( list($k, $v) = each($_COOKIE) )
			{
				if( is_array($_COOKIE[$k]) )
				{
					while( list($k2, $v2) = each($_COOKIE[$k]) )
					{
						$_COOKIE[$k][$k2] = addslashes($v2);
					}
					@reset($_COOKIE[$k]);
				}
				else
				{
					$_COOKIE[$k] = addslashes($v);
				}
			}
			@reset($_COOKIE);
		}
	}

	@extract($_GET);
	@extract($_POST);
	@extract($_SERVER); 

	include_once("{$da_path}/daconfig.php");
	include_once("{$da_path}/libs/dbconn.php");
	include_once("{$da_path}/libs/function.php");

	//-------------------------------------------
	// SESSION 설정
	//-------------------------------------------
	ini_set("session.use_trans_sid", 0);
	ini_set("url_rewriter.tags","");

	//session_save_path("/home/real/sessions");

	if (isset($SESSION_CACHE_LIMITER)) 
		@session_cache_limiter($SESSION_CACHE_LIMITER);
	else 
		@session_cache_limiter("no-cache, must-revalidate");

//	ini_set("session.cache_expire", 180000); 
//	ini_set("session.gc_maxlifetime", 144000); 
	ini_set("session.cache_expire", 180); // 세션 유효시간 : 분
	ini_set("session.gc_maxlifetime", 3600);  // 세션 가비지 컬렉션(로그인시 세션지속 시간) : 초

	session_set_cookie_params(0, "/");
	session_start();

	$dbcon = new Dbconn();
	
	if( $_SERVER['HTTP_HOST']=="ireal100.co.kr" ||  $_SERVER['HTTP_HOST']=="www.ireal100.co.kr" ||  $_SERVER['HTTP_HOST']=="ireal100.com" ){
		$url = "http://www.ireal100.com/content/main.html";
		echo "<meta http-equiv='Refresh' content='0; URL=".$url."'> ";
		exit;
	}

	if( get_cookie("da_auto_login") ){
		$data = $dbcon->getOneRow("select idx,id,nick from da_member where id = '".get_cookie("da_auto_login")."' ");
		if( $data[0] ){
			set_session("da_ss_midx",$data[0]);
			set_session("da_ss_mid",$data[1]);
			set_session("da_ss_mnick",$data[2]);
		}
	}
?>