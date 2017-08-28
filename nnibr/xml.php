<?
	header('Content-type: text/xml');
	include "_da.php";
	
	$xml = "<?xml version='1.0' encoding='UTF-8'?>";

	$list = $dbcon->getList("select * from da_data order by idx ");
	$len = count($list);
	for($i=0; $i<$len; $i++){
		$row = $list[$i];

		$xml .= "<LIST>";
		$xml .= "<SEQ>".$row[nmtag]."</SEQ>";
		$xml .= "<POSITION>".$row[loc]."</POSITION>";
		$xml .= "<ObjCode>".$row[nmtag]."</ObjCode>";
		$xml .= "<TITLE>".$row[tit_k]."</TITLE>";
		$xml .= "<TITLE_ENG>".$row[tit_e]."</TITLE_ENG>";
		$xml .= "<TITLE_CN>".$row[tit_c]."</TITLE_CN>";
		$xml .= "<TITLE_JP>".$row[tit_j]."</TITLE_JP>";

		$xml .= "<SCAN_OBJ>";
		$xml .= "<USED>".$row[stat]."</USED>";
		$xml .= "<SUM>http://localhost/uploaded/".$row[img7]."</SUM>";
		$xml .= "<BG>http://localhost/uploaded/".$row[img1]."</BG>";
		$xml .= "<SCAN_BG>http://localhost/uploaded/".$row[img8]."</SCAN_BG>";
		$xml .= "<SCAN>http://localhost/uploaded/".$row[img9]."</SCAN>";
		$xml .= "</SCAN_OBJ>";

		$xml .= "<IMGS>";
		if($row[img2])$xml .= "<IMG>http://localhost/uploaded/".$row[img2]."</IMG>";
		if($row[img3])$xml .= "<IMG>http://localhost/uploaded/".$row[img3]."</IMG>";
		if($row[img4])$xml .= "<IMG>http://localhost/uploaded/".$row[img4]."</IMG>";
		if($row[img5])$xml .= "<IMG>http://localhost/uploaded/".$row[img5]."</IMG>";
		$xml .= "</IMGS>";

		$xml .= "<DESC>".$row[exp_k]."</DESC>";
		$xml .= "<DESC_ENG>".$row[exp_e]."</DESC_ENG>";
		$xml .= "<DESC_CN>".$row[exp_c]."</DESC_CN>";
		$xml .= "<DESC_JP>".$row[exp_j]."</DESC_JP>";

		$xml .= "<SND>http://localhost/uploaded/".$row[mov1]."</SND>";
		$xml .= "<SND_ENG>http://localhost/uploaded/".$row[mov2]."</SND_ENG>";
		$xml .= "<SND_CN>http://localhost/uploaded/".$row[mov3]."</SND_CN>";
		$xml .= "<SND_JP>http://localhost/uploaded/".$row[mov4]."</SND_JP>";

		$xml .= "</LIST>";
	}

	echo $xml;
?>