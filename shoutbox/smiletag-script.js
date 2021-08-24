	var sURL = unescape(window.location.pathname);

	function doLoad()
	{
	    // the timeout value should be the same as in the "refresh" meta-tag
	    setTimeout( "reload_shoutbox()", 45*1000 );
	}

	//clear the message from the text area
	function clearMessage(){
		document.smiletagform.message.value=document.smiletagform.message_box.value;
	    document.smiletagform.message_box.value="";
    }
	
	//reload the iframe
	function reload_shoutbox(){
		var smiletagFrame = window.frames['iframetag'];
		smiletagFrame.location = "shoutbox/view.php";
	}
	


    
	/******************************** Start smilie picker code *******************************/
	function showSmileyWindow(e){
		if(document.all)e = event;
		
		var smileyBox = document.getElementById('smiley_box');
				
		smileyBox.style.display = 'block';
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		var leftPos = e.clientX - 100;
		if(leftPos<0)leftPos = 0;
		smileyBox.style.left = leftPos + 'px';
		smileyBox.style.top = e.clientY - smileyBox.offsetHeight -1 + st + 'px';
	}	
	
	function hideSmileyWindow()
	{
		document.getElementById('smiley_box').style.display = 'none';
		
	}
	
	function insertSmiley(code){
		document.smiletagform.message_box.value += code;
		hideSmileyWindow();
	}
	/******************************** End smilie picker code *******************************/