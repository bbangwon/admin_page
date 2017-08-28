<? 
  include "_da.php";
 
  if(file_exists($filepath."/".$filename)){
	  
	  $filesize = filesize($filepath."/".$filename);
	
	  Header("Content-Type: application/octet-stream");
	  Header("Content-Type: application/x-msdownload");
	  Header("Content-Disposition: attachment; filename=".urlencode($filename));
	  Header("Content-Length: $filesize");
	  Header("Pragma: no-cache");
	  Header("Expires: 0");

	  $fp = fopen($filepath."/".$filename, "r");
	  while(!feof($fp)) { 
			echo fread($fp, 500*1024); 
			flush(); 
		} 
	  fclose($fp);



  }else{
	  echo $filepath."/".$filename;
	  echo "
	  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	  <script>
		alert('파일이 존재하지 않습니다. 관리자에게 문의하세요');
	    history.go(-1);
	  </script>
	  ";
  }
	
?> 