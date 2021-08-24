var http = createRequestObject();
function createRequestObject() {
	var xmlhttp;
	try { xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); }
	catch(e) {
    try { xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	catch(f) { xmlhttp=null; }
  }
  if(!xmlhttp&&typeof XMLHttpRequest!="undefined") {
	xmlhttp=new XMLHttpRequest();
  }
	return  xmlhttp;
}

function gift_handleResponse() {
	try {
		if((http.readyState == 4)&&(http.status == 200)){
			document.getElementById("gift_loading").style.display = "none";
			var response = http.responseText;
			if (response) {
				document.getElementById("gift_loading").innerHTML = response;
				document.getElementById("gift_loading").style.display = "block";
			}
		}
  	}
	catch(e){}
	finally{}
}

function gift_check_values() {
	media_id = encodeURIComponent(document.getElementById("media_id").value);
	sender_name = encodeURIComponent(document.getElementById("sender_name").value);
	recip_name = encodeURIComponent(document.getElementById("recip_name").value);
	sender_email = encodeURIComponent(document.getElementById("sender_email").value);
	recip_email = encodeURIComponent(document.getElementById("recip_email").value);
	message = encodeURIComponent(document.getElementById("message").value);
	if(	trim(sender_name) == "" ||	trim(recip_name) == "" ||	trim(sender_email) == "" ||	trim(recip_email) == "" || trim(message) == "" )
		alert("Bạn chưa nhập đầy đủ thông tin");
	else {
		try {
			document.getElementById("gift_loading").innerHTML = loadingText;
			document.getElementById("gift_loading").style.display = "block";
			http.open('POST',  'gift.php');
			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.onreadystatechange = gift_handleResponse;
			http.send('gift=1&media_id='+media_id+'&sender_name='+sender_name+'&recip_name='+recip_name+'&sender_email='+sender_email+'&recip_email='+recip_email+'&message='+message);
		}
		catch(e){}
		finally{}
	}
	return false;
}

function trim(a) {
	return a.replace(/^s*(S*(s+S+)*)s*$/, "$1");
}