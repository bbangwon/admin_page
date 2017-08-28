var siteURL = "http://www.daover.com";
function delProc(url){
	if(confirm("삭제하시겠습니까?")){
		location.href = url;
	}
}

function leaveProc(url){
	if(confirm("탈퇴하시겠습니까?")){
		location.href = url;
	}
}

function showFileUpload(tb_id,tmp_no){
	window.open("/admcontents/inc/file_pop.html?tb_id="+tb_id+"&tmp_no="+tmp_no,'fupload','width=670 height=500 scrolling=no');
}

function showPreview(idx){
	$.post('file_preview.php',{'idx':idx},function(src){ 
//		$('#previmg').attr('src','/uploaded/'+src);
		$('#previmg').attr('src', siteURL + '/uploaded/'+src);
	});
}

function showUploadedFiles(tmp_no){
	$.post("/admcontents/inc/file_list.php",{'tmp_no':tmp_no},function(html){
		$('#preview').html('').append(html);
	});
}

function preview(idx){
	$.post('/admcontents/inc/file_preview.php',{'idx':idx},function(src){ 
		$('#previmg').attr('src',siteURL + '/uploaded/'+src);
	});
}

function fileDown(fpath,fname){ 
	var path = encodeURIComponent(siteURL + "/uploaded/" + fpath);
	location.href="/download.php?filepath="+path+"&filename="+fname;
}

function fileDel(idx){
	if(confirm("삭제하시겠습니까?")){
		$.post('/admcontents/inc/file_del.php',{'idx':idx},function(v){ 
			$('.'+idx).hide();
		});
	}
}

function cmtDel(idx){
	if(confirm("삭제하시겠습니까?")){
		$.post('/admcontents/inc/comment_update.php',{'idx':idx, 'act':'d'},function(v){ 
			if(v=="0")alert("삭제 중 오류발생!!");
			else parent.document.location.reload();
		});
	}
}

function DisCmtDel(idx){
	if(confirm("삭제하시겠습니까?")){
		$.post('comment_update.php',{'idx':idx, 'act':'d'},function(v){ 
			if(v=="0")alert("삭제 중 오류발생!!");
			else parent.document.location.reload();
		});
	}
}