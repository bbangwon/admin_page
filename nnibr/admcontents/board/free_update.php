<?
	include "_da.php";
	
	$qstr = "&skey=".$skey."&mnu_id=".$mnu_id."&page=".$page;
	$path = $da_path."/uploaded/";

	if($idx)$old = $dbcon->getOneRow("select * from da_data where idx = ".$idx);

	if($act=="d"){
		
		$dbcon->doQuery("delete from da_data where idx = ".$idx);
		goto_url("free_list.html?".$qstr);

	}else if($act=="u"){
		
		$add_files = "";
		if($_FILES[img1]){ $if1 = doUpload($path, 10, "img1", $old[img1], ""); $add_files .= " , img1 = '".$if1[1]."' "; }
		if($_FILES[img2]){ $if2 = doUpload($path, 10, "img2", $old[img2], ""); $add_files .= " , img2 = '".$if2[1]."' "; }
		if($_FILES[img3]){ $if3 = doUpload($path, 10, "img3", $old[img3], ""); $add_files .= " , img3 = '".$if3[1]."' "; }
		if($_FILES[img4]){ $if4 = doUpload($path, 10, "img4", $old[img4], ""); $add_files .= " , img4 = '".$if4[1]."' "; }
		if($_FILES[img5]){ $if5 = doUpload($path, 10, "img5", $old[img5], ""); $add_files .= " , img5 = '".$if5[1]."' "; }
		if($_FILES[img6]){ $if6 = doUpload($path, 10, "img6", $old[img6], ""); $add_files .= " , img6 = '".$if6[1]."' "; }
		if($_FILES[img7]){ $if7 = doUpload($path, 10, "img7", $old[img7], ""); $add_files .= " , img7 = '".$if7[1]."' "; }
		if($_FILES[img8]){ $if8 = doUpload($path, 10, "img8", $old[img8], ""); $add_files .= " , img8 = '".$if8[1]."' "; }
		if($_FILES[img9]){ $if9 = doUpload($path, 10, "img9", $old[img9], ""); $add_files .= " , img9 = '".$if9[1]."' "; }

		if($_FILES[mov1]){ $mf1 = doUpload($path, 10, "mov1", $old[mov1], ""); $add_files .= " , mov1 = '".$mf1[1]."' "; }
		if($_FILES[mov2]){ $mf2 = doUpload($path, 10, "mov2", $old[mov2], ""); $add_files .= " , mov2 = '".$mf2[1]."' "; }
		if($_FILES[mov3]){ $mf3 = doUpload($path, 10, "mov3", $old[mov3], ""); $add_files .= " , mov3 = '".$mf3[1]."' "; }
		if($_FILES[mov4]){ $mf4 = doUpload($path, 10, "mov4", $old[mov4], ""); $add_files .= " , mov4 = '".$mf4[1]."' "; }

		$sql = " 
			update da_data set 
			loc='".$loc."',
			stat='".$stat."',
			tit_k='".$tit_k."',
			tit_e='".$tit_e."',
			tit_c='".$tit_c."',
			tit_j='".$tit_j."',
			nm='".$nm."',
			nmtag='".$nmtag."',
			exp_k='".$exp_k."',
			exp_e='".$exp_e."',
			exp_c='".$exp_c."',
			exp_j='".$exp_j."',
			mno='".$mno."',
			up_date=now()
			".$add_files."
			where idx = ".$idx;
		$dbcon->doQuery($sql);
		
		goto_url("free_list.html?".$qstr);

	}else if($act=="w"){
	
		$add_files = "";
		if($_FILES[img1]){ $if1 = doUpload($path, 10, "img1", "", ""); $add_files .= " , img1 = '".$if1[1]."' "; }
		if($_FILES[img2]){ $if2 = doUpload($path, 10, "img2", "", ""); $add_files .= " , img2 = '".$if2[1]."' "; }
		if($_FILES[img3]){ $if3 = doUpload($path, 10, "img3", "", ""); $add_files .= " , img3 = '".$if3[1]."' "; }
		if($_FILES[img4]){ $if4 = doUpload($path, 10, "img4", "", ""); $add_files .= " , img4 = '".$if4[1]."' "; }
		if($_FILES[img5]){ $if5 = doUpload($path, 10, "img5", "", ""); $add_files .= " , img5 = '".$if5[1]."' "; }
		if($_FILES[img6]){ $if6 = doUpload($path, 10, "img6", "", ""); $add_files .= " , img6 = '".$if6[1]."' "; }
		if($_FILES[img7]){ $if7 = doUpload($path, 10, "img7", "", ""); $add_files .= " , img7 = '".$if7[1]."' "; }
		if($_FILES[img8]){ $if8 = doUpload($path, 10, "img8", "", ""); $add_files .= " , img8 = '".$if8[1]."' "; }
		if($_FILES[img9]){ $if9 = doUpload($path, 10, "img9", "", ""); $add_files .= " , img9 = '".$if9[1]."' "; }

		if($_FILES[mov1]){ $mf1 = doUpload($path, 10, "mov1", "", ""); $add_files .= " , mov1 = '".$mf1[1]."' "; }
		if($_FILES[mov2]){ $mf2 = doUpload($path, 10, "mov2", "", ""); $add_files .= " , mov2 = '".$mf2[1]."' "; }
		if($_FILES[mov3]){ $mf3 = doUpload($path, 10, "mov3", "", ""); $add_files .= " , mov3 = '".$mf3[1]."' "; }
		if($_FILES[mov4]){ $mf4 = doUpload($path, 10, "mov4", "", ""); $add_files .= " , mov4 = '".$mf4[1]."' "; }
		
		$sql = " 
			insert into da_data set 
			loc='".$loc."',
			stat='".$stat."',
			tit_k='".$tit_k."',
			tit_e='".$tit_e."',
			tit_c='".$tit_c."',
			tit_j='".$tit_j."',
			nm='".$nm."',
			nmtag='".$nmtag."',
			exp_k='".$exp_k."',
			exp_e='".$exp_e."',
			exp_c='".$exp_c."',
			exp_j='".$exp_j."',
			mno='".$mno."',
			in_date=now()
			".$add_files."";
		$idkey = $dbcon->doQuery($sql);
		
		goto_url("free_list.html?".$qstr);
	}
?>