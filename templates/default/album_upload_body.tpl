<!-- BEGIN switch_nuffload_enabled -->
<script type="text/javascript">
<!--
var inpIndex = 0;

function addInput()
{
	if (inpIndex >= ({MAX_UPLOADS}-1)){return;}
	var section = document.getElementById('parah');
	var newInput = document.createElement('input');
	var newPara = document.createElement('p');
	inpIndex++;
	newInput.type = 'file';
	newInput.id = 'pic_file-' + inpIndex;
	newInput.name = 'pic_file-' + inpIndex;
	newInput.className='post';
	newInput.size = '60';
	newPara.id = 'parah-' + inpIndex;
	newPara.style.margin = '0px';
	newPara.style.padding =  '0px';
	section.appendChild(newPara);
	newPara.appendChild(newInput);

	if (document.getElementById('parat'))
	{
		var section = document.getElementById('parat');
		var newInput = document.createElement('input');
		var newPara = document.createElement('p');
		newInput.type = 'file';
		newInput.id = 'pic_thumbnail-' + inpIndex;
		newInput.name = 'pic_thumbnail-' + inpIndex;
		newInput.className='post';
		newInput.size = '60';
		newPara.id = 'parat-' + inpIndex;
		newPara.style.margin = '0px';
		newPara.style.padding =  '0px';
		section.appendChild(newPara);
		newPara.appendChild(newInput);
	}
}

function deleteInput()
{
	var section = document.getElementById('parah');
	var oldPara = document.getElementById('parah-' + inpIndex);
	section.removeChild(oldPara);
	if (document.getElementById('parat'))
	{
		var section = document.getElementById('parat');
		var oldPara = document.getElementById('parat-' + inpIndex);
		section.removeChild(oldPara);
	}
	inpIndex--;
}

function popUP(mypage, myname, w, h, scroll, titlebar)
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable=no,menubar=no,status=no,toolbar=no'
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4)
	{
		win.window.focus();
	}
}

function postIt()
{
	var formOK = true;
	var complete = true;
	for (var loop=0; loop < document.upload.elements.length; loop++)
	{
		if (document.upload.elements[loop].value == "" & document.upload.elements[loop].type == "file")
		{
			complete = false;
		}
	}

	if (complete == false)
	{
		alert("{L_UPLOAD_NO_FILE}");
		formOK = false;
	}
	else if (document.upload.pic_desc.value.length > {S_PIC_DESC_MAX_LENGTH})
	{
		alert("{L_DESC_TOO_LONG}");
		formOK = false;
	}

	if (formOK)
	{
		<!-- BEGIN switch_show_progress_bar -->
		popUP("album_nuffload_pbar.php?sessionid={PSID}","Uploader",460,150,false,false);
		<!-- END switch_show_progress_bar -->
	}
	return formOK;
}
// -->
</script>
<!-- END switch_nuffload_enabled -->

<script type="text/javascript">
<!--
var gFileuploadCounter = 1;
var fileBrowseHTML = '<input class="post" type="file" name="pic_file[]" size="49" \/><br \/>';
<!-- BEGIN switch_manual_thumbnail -->
var fileThumbBrowseHTML = '<input class="post" type="file" name="pic_thumbnail[]" size="49" /><br \/>';
<!-- END switch_manual_thumbnail -->

function checkAlbumForm()
{
	formErrors = false;

	if (gFileuploadCounter == 1)
	{
		if (document.upload.pic_title.value.length < 2)
		{
			formErrors = "{L_UPLOAD_NO_TITLE}";
		}
		else if (document.upload.pic_file.value.length < 2)
		{
			formErrors = "{L_UPLOAD_NO_FILE}";
		}
		else if (document.upload.pic_desc.value.length > {S_PIC_DESC_MAX_LENGTH})
		{
			formErrors = "{L_DESC_TOO_LONG}";
		}
		else
		{
			switch (document.upload.cat_id.value)
			{
				case '{S_ALBUM_ROOT_CATEGORY}':
				case '{S_ALBUM_JUMPBOX_SEPERATOR}':
				case '{S_ALBUM_JUMPBOX_USERS_GALLERY}':
				case '{S_ALBUM_JUMPBOX_PUBLIC_GALLERY}':
					formErrors = "{L_NO_VALID_CAT_SELECTED}";
				default:
					// do nothing
			}
		}
	}
	if (formErrors)
	{
		alert(formErrors);
		return false;
	}
	else
	{
		return true;
	}
}

// this function add the file input form field... and should be cross-browser compatible
function AddFileField()
{
	if (gFileuploadCounter <= {S_MAX_FILE_UPLOADS} )
	{
		gFileuploadCounter++;

		if(document.all)
		{
			document.all.file_browse.innerHTML += fileBrowseHTML;
			<!-- BEGIN switch_manual_thumbnail -->
			document.all.file_thumb_browse.innerHTML += fileThumbBrowseHTML;
			<!-- END switch_manual_thumbnail -->

			if ( gFileuploadCounter > {S_MAX_FILE_UPLOADS})
			{
				document.all.addbutton.style.visibility = 'hidden';
			}
		}
		else
		{
			// IE501+ and NS6+
			document.getElementById("file_browse").innerHTML += fileBrowseHTML;
			<!-- BEGIN switch_manual_thumbnail -->
			document.getElementById("file_thumb_browse").innerHTML += fileThumbBrowseHTML;
			<!-- END switch_manual_thumbnail -->

			if ( gFileuploadCounter > {S_MAX_FILE_UPLOADS})
			{
				document.getElementById("addbutton").style.visibility = 'hidden';
			}
		}
	}
}

function InitForm()
{
	// initialise the form and prepare it for uplaod fields...
	gFileuploadCounter = 1;
	if(document.all)
	{
		document.all.file_browse.innerHTML = '';
		<!-- BEGIN switch_manual_thumbnail -->
		document.all.file_thumb_browse.innerHTML = '';
		<!-- END switch_manual_thumbnail -->
		document.all.addbutton.style.visibility = '{DYNAMIC_GENERATION_STATUS}';
	}
	else
	{
		// IE501+ and NS6+
		document.getElementById("file_browse").innerHTML = '';
		<!-- BEGIN switch_manual_thumbnail -->
		document.getElementById("file_thumb_browse").innerHTML = '';
		<!-- END switch_manual_thumbnail -->
		document.getElementById("addbutton").style.visibility = '{DYNAMIC_GENERATION_STATUS}';
	}

	// init the first upload field, is ALWAYS visible !
	AddFileField();

	<!-- BEGIN pre_generate -->
	// pre-generate the rest of the fields
	while (gFileuploadCounter <= {S_MAX_PREGEN_FILE_UPLOADS} )
	{
		AddFileField();
	}
	<!-- END pre_generate -->
}
// -->
</script>

<form name="upload" action="{S_ALBUM_ACTION}" method="post" enctype="multipart/form-data" onSubmit="{S_ON_SUBMIT}">

{IMG_THL}{IMG_THC}<span class="forumlink">{L_UPLOAD_PIC}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- IF not S_LOGGED_IN -->
<tr>
	<td class="row1" width="30%" height="28"><span class="gen">{L_USERNAME}:</span></td>
	<td class="row2"><input class="post" type="text" name="pic_username" size="32" maxlength="32" /></td>
</tr>
<!-- ENDIF -->
<tr>
	<td class="row1" height="28"><span class="gen">{L_PIC_TITLE}:</span></td>
	<td class="row2"><input class="post" type="text" name="pic_title" size="60" /></td>
</tr>
<tr>
	<td class="row1" valign="top" height="28"><span class="gen">{L_PIC_DESC}:<br />
	</span><span class="genmed">{L_PLAIN_TEXT_ONLY}<br />{L_MAX_LENGTH}: <b>{S_PIC_DESC_MAX_LENGTH}</b></span></td>
	<td class="row2"><textarea class="post" cols="60" rows="4" name="pic_desc" size="60"></textarea></td>
</tr>
<!-- BEGIN switch_nuffload_enabled -->
<td class="row1">
	<span class="gen">{L_UPLOAD_PIC_FROM_MACHINE}:
	<!-- BEGIN switch_multiple_uploads -->
		<br /><a href="javascript:addInput()">{ADD_FIELD}</a><br /><a href="javascript:deleteInput()">{REMOVE_FIELD}</a>
	<!-- END switch_multiple_uploads -->
	</span>
</td>
<td class="row2" id="parah"><input class="post" type="file" name="pic_file" size="49" /></td>
<!-- END switch_nuffload_enabled -->
<!-- BEGIN switch_nuffload_disabled -->
	<tr>
		<td class="row1" valign="top"><span class="gen">{L_UPLOAD_PIC_FROM_MACHINE}:</span></td>
		<td class="row2">
			<span id="addbutton" class="gen"><input type="button" value="{L_ADD_FILE}" onclick="AddFileField()" class="liteoption" /></span>
			<div id="file_browse" style="position:relative;"></div>
		</td>
	</tr>
<!-- END switch_nuffload_disabled -->
<!-- BEGIN switch_manual_thumbnail -->
<tr>
	<td class="row1" valign="top"><span class="gen">{L_UPLOAD_THUMBNAIL}:</span></td>
	<!-- BEGIN switch_nuffload_enabled -->
	<td class="row2" id="parat"><input class="post" type="file" name="pic_thumbnail" size="60" /></td>
	<!-- END switch_nuffload_enabled -->
	<!-- BEGIN switch_nuffload_disabled -->
	<td class="row2"><div id="file_thumb_browse" style="position:relative;"></div></td>
	<!-- END switch_nuffload_disabled -->
</tr>
<tr>
	<td class="row1" height="28"><span class="gen">{L_THUMBNAIL_SIZE}:</span></td>
	<td class="row2"><span class="gen"><b>{S_THUMBNAIL_SIZE}</b></span></td>
</tr>
<!-- END switch_manual_thumbnail -->
<!-- BEGIN switch_nuffload_disabled -->
<!-- BEGIN switch_rotation -->
<tr>
	<td class="row1" height="28"><span class="gen">{L_ROTATION}:</span></td>
	<td class="row2"><input type="radio" name="rotation" value="0" checked="checked"><span class="gen">0</span>&nbsp;&nbsp;<input type="radio" name="rotation" value="90"><span class="gen">90</span>&nbsp;&nbsp;<input type="radio" name="rotation" value="180"><span class="gen">180</span>&nbsp;&nbsp;<input type="radio" name="rotation" value="270"><span class="gen">270</span></td>
</tr>
<!-- END switch_rotation -->
<!-- END switch_nuffload_disabled -->
<tr>
	<td class="row1" height="28"><span class="gen">{L_UPLOAD_TO_CATEGORY}:</span></td>
	<td class="row2">{SELECT_CAT}</td>
</tr>
<!-- BEGIN switch_nuffload_disabled -->
<tr>
	<td class="row1" height="28"><span class="gen">{L_PIC_COMMENT_WATCH}</span></td>
	<td class="row2"><span class="gen"><input type="checkbox" name="comment_watch" value="0" checked="checked" /></span></td>
</tr>
<tr>
	<td class="row1" height="28"><span class="gen">{L_MAX_FILESIZE}:</span></td>
	<td class="row2"><span class="gen"><b>{S_MAX_FILESIZE}</b></span></td>
</tr>
<tr>
	<td class="row1" height="28"><span class="gen">{L_MAX_WIDTH}:</span></td>
	<td class="row2"><span class="gen"><b>{S_MAX_WIDTH}</b></span></td>
</tr>
<tr>
	<td class="row1" height="28"><span class="gen">{L_MAX_HEIGHT}:</span></td>
	<td class="row2"><span class="gen"><b>{S_MAX_HEIGHT}</b></span></td>
</tr>
<!-- END switch_nuffload_disabled -->
<tr>
	<td class="row1" height="28"><span class="gen">{L_ALLOWED_JPG}:</span></td>
	<td class="row2"><span class="gen"><b>{S_JPG}</b></span></td>
</tr>
<tr>
	<td class="row1" height="28"><span class="gen">{L_ALLOWED_PNG}:</span></td>
	<td class="row2"><span class="gen"><b>{S_PNG}</b></span></td>
</tr>
<tr>
	<td class="row1" height="28"><span class="gen">{L_ALLOWED_GIF}:</span></td>
	<td class="row2"><span class="gen"><b>{S_GIF}</b></span></td>
</tr>
<!-- BEGIN switch_nuffload_enabled -->
<tr>
	<td class="row1" height="28"><span class="gen">{L_ALLOWED_ZIP}:</span></td>
	<td class="row2"><span class="gen"><b>{S_ZIP}</b></span></td>
</tr>
<!-- END switch_nuffload_enabled -->
<tr>
	<td class="catBottom" align="center" colspan="2">
		<!-- BEGIN switch_nuffload_enabled -->
		<input type="reset" value="{L_RESET}" class="liteoption" />&nbsp;&nbsp;&nbsp;
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
		<!-- END switch_nuffload_enabled -->
		<!-- BEGIN switch_nuffload_disabled -->
		<input type="reset" value="{L_RESET}" class="liteoption" onclick="InitForm();" />&nbsp;&nbsp;&nbsp;
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
		<!-- END switch_nuffload_disabled -->
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<script type="text/javascript">InitForm();</script>
</form>
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}