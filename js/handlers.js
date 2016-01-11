function fileQueueError(file, errorCode, message) {
	try {
		var imageName = "error.gif";
		var errorName = "";
		if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
			errorName = "You have attempted to queue too many files.";
		}

		if (errorName !== "") {
			alert('Error='+errorCode+'-'+errorName);
			return;
		}

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			imageName = "zerobyte.gif";
			break;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			imageName = "toobig.gif";
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
		    break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
		    break;
		default:
			alert('Error message='+errorCode+'-'+message);
			break;
		}


	} catch (ex) {
		this.debug(ex);
	}

}

number_of_files=1;
total_files=0;

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) 
		{
		   this.startUpload();
		   document.getElementById('loading').style.display='block';		
		}
	} catch (ex) {
		this.debug(ex);
	}
}


function uploadSuccess(file, serverData) 
{
	try 
	{
	    fid_pos=serverData.indexOf('FID:')+4;
		fid=serverData.substr(fid_pos);
		if (serverData.substring(0, 7) === "FILEID:") 
		{
		    len=fid_pos-4;
			id=getQueryVariable("id");
			cntent="<div><span style=\'margin-left:5px;height:16px;font-size:12px;padding-right:5px;\'>#"+fid+"</span><a href='/editsubdivision.php?idel="+fid+"&id="+id+"'><img border='0' src='/images/trash.gif' /></a></div>";
			addImage("thumbnail.php?id=" + serverData.substring(7,len),fid);			
            jQuery("#img_"+fid).simpletip({ showEffect: "none",fixed: true, position: [50,-15], content: cntent});	
		} 
		else 
		{
			addImage("images/error.gif");
			alert('bad happened'+serverData);
		}
	} 
	catch (ex) 
	{
		this.debug(ex);
	}
}

function getQueryVariable(variable) 
{
   var query = window.location.search.substring(1);
   var vars = query.split("&");
   for (var i=0;i<vars.length;i++) 
   {
      var pair = vars[i].split("=");
      if (pair[0] == variable) 
        return pair[1];
   }
}

function strpos (haystack, needle, offset)
{
    var i = (haystack+'').indexOf(needle, (offset ? offset : 0));
    return i === -1 ? false : i;
}

function chr (codePt)
{
   if (codePt > 0xFFFF)
   {
        codePt -= 0x10000;
        return String.fromCharCode(0xD800 + (codePt >> 10), 0xDC00 + (codePt & 0x3FF));
   }
   else
   {
        return String.fromCharCode(codePt);
   }
}

function uploadComplete(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) 
		{
		    document.getElementById('loading').style.display='block';		
			this.startUpload();
		} else {
		    document.getElementById('loading').style.display='none';		
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {
	var imageName =  "error.gif";
	try {
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			try {
			}
			catch (ex1) {
				this.debug(ex1);
			}
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			try {
			}
			catch (ex2) {
				this.debug(ex2);
			}
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			imageName = "uploadlimit.gif";
			break;
		default:
			alert(message);
			break;
		}

	} catch (ex3) {
		this.debug(ex3);
	}

}


function addImage(src,fid) {
    var newDiv = document.createElement("li");
	newDiv.style.position="relative";
	newDiv.id='img_'+fid;
	newDiv.style.height="130px";
	newDiv.style.width="130px";	
	var newImg = document.createElement("img");
    newImg.border="0";
    newDiv.appendChild(newImg);
	document.getElementById("upload_list").appendChild(newDiv);
    document.getElementById('img_'+fid).style.cssFloat='left';	
	if (newImg.filters) {
		try {
			newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
		} catch (e) {
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
		}
	} else {
		newImg.style.opacity = 0;
	}

	newImg.onload = function () {
		fadeIn(newImg, 0);
	};
	newImg.src = src;
}

function fadeIn(element, opacity) {
	var reduceOpacityBy = 5;
	var rate = 30;	// 15 fps


	if (opacity < 100) {
		opacity += reduceOpacityBy;
		if (opacity > 100) {
			opacity = 100;
		}

		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}

	if (opacity < 100) {
		setTimeout(function () {
			fadeIn(element, opacity);
		}, rate);
	}
}