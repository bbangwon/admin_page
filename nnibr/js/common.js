
function doDel(url){
	if(confirm("삭제하시겠습니까?")){
		location.href = url;
	}
}

function doCmtDel(idx){
	if(confirm("삭제하시겠습니까?")){
		$.post('board_comment_update.php',{'idx':idx, 'act':'d'},function(v){ 
			if(v=="0")alert("삭제 중 오류발생!!");
			else parent.document.location.reload();
		});
	}
}

function isNumeber(f,n,v){
	
	if( isNaN(v) ){
		alert("숫자로만 입력해주세요");
		eval("document."+f+"."+n+".value = '';");
	}
}

function number_format(input){ 
	
    var input = String(input); 
    var reg = /(\-?\d+)(\d{3})($|\.\d+)/; 
    if(reg.test(input)){ 
        return input.replace(reg, function(str, p1,p2,p3){ 
                return number_format(p1) + "," + p2 + "" + p3; 
            }     
        ); 
    }else{ 
        return input; 
    } 
}


function getCookie(Name)
{

	var search = Name + "="
	if (document.cookie.length > 0)
	{
		var offset = document.cookie.indexOf(search)
		if (offset != -1)
		{
			offset += search.length
			var end = document.cookie.indexOf(";", offset)
	if (end == -1)
		end = document.cookie.length
			return unescape(document.cookie.substring(offset, end))
		}
	}
	return "";
}

function getAgent(){
	var agt = navigator.userAgent.toLowerCase();
	if (agt.indexOf("chrome") != -1) return 'Chrome'; 
	if (agt.indexOf("opera") != -1) return 'Opera'; 
	if (agt.indexOf("staroffice") != -1) return 'Star Office'; 
	if (agt.indexOf("webtv") != -1) return 'WebTV'; 
	if (agt.indexOf("beonex") != -1) return 'Beonex'; 
	if (agt.indexOf("chimera") != -1) return 'Chimera'; 
	if (agt.indexOf("netpositive") != -1) return 'NetPositive'; 
	if (agt.indexOf("phoenix") != -1) return 'Phoenix'; 
	if (agt.indexOf("firefox") != -1) return 'Firefox'; 
	if (agt.indexOf("safari") != -1) return 'Safari'; 
	if (agt.indexOf("skipstone") != -1) return 'SkipStone'; 
	if (agt.indexOf("msie") != -1){
		return 'Internet Explorer ' + getInternetExplorerVersion(); 
	}
	if (agt.indexOf("netscape") != -1) return 'Netscape'; 
	if (agt.indexOf("mozilla/5.0") != -1) return 'Mozilla'; 
}
function getInternetExplorerVersion() {    
	var rv = -1; // Return value assumes failure.    
	if (navigator.appName == 'Microsoft Internet Explorer') {        
		var ua = navigator.userAgent;        
		var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");        
		if (re.exec(ua) != null)            
			rv = parseFloat(RegExp.$1);    
	}    
	return rv; 
} 

function getScrollTop(agent){
	var top = 0;
	switch(agent){
		case "Firefox":
		case "Mozilla":
		case "Internet Explorer 6":
		case "Internet Explorer 7":
		case "Internet Explorer 8":
			top = document.documentElement.scrollTop;
			break;
		default:
			top = document.body.scrollTop;
			break;
	}
	return top+"px";
}

function kbdchk(type, src){
	var res = "";
	switch(type){
		case "english":
			res = src.replace(/[^a-zA-Z]/gi, '');
			break;
		case "number":
			res = src.replace(/[^0-9]/gi, '');
			break;
		case "engnum":
			res = src.replace(/[^a-zA-Z0-9]/gi, '');
			break;
		default:
			res = src.replace(/[^a-zA-Z]/gi, '');
			break;
	}
	
	return res;
}




