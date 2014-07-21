<script type="text/javascript">
// <![CDATA[
	var win = null;
	var error_msg = "";
	var mirror_mode = false;

	function set_mirror_mode(status)
	{
		mirror_mode = status;
	}

	function new_window(mypage, myname, w, h, pos, infocus)
	{
		if(pos == "random")
		{
			myleft = (screen.width) ? Math.floor(Math.random()*(screen.width-w)) : 100;
			mytop = (screen.height) ? Math.floor(Math.random()*((screen.height-h)-75)) : 100;
		}
		if(pos == "center")
		{
			myleft = (screen.width) ? (screen.width-w) / 2 : 100;
			mytop = (screen.height) ? (screen.height-h) / 2 : 100;
		}
		else if((pos != 'center' && pos != "random") || pos == null)
		{
			myleft = 0;
			mytop = 20
		}
		settings = "width=" + w + ",height=" + h + ",top=" + mytop + ",left=" + myleft + ",scrollbars=yes,location=no,directories=no,status=yes,menubar=no,toolbar=no,resizable=no";
		win = window.open(mypage, myname, settings);
		win.focus();
	}

	if({ADD_MIRRORS})
	{
		redirect_url = '{U_MIRRORS_PAGE}';
		redirect_url = redirect_url.replace(/&amp;/g, '&');
		//alert(redirect_url);
		new_window(redirect_url, 'fileupload', '600', '450', 'center', 'front');
	}

	function checkAddForm()
	{
		if(mirror_mode)
		{
			redirect_url = '{U_MIRRORS_PAGE}';
			redirect_url = redirect_url.replace(/&amp;/g, '&');
			//alert(redirect_url);
			new_window(redirect_url, 'fileupload', '600', '450', 'center', 'front');
			return false;
		}

		error_msg = "";
		if (document.form.cat_id.value == -1)
		{
			error_msg = "You can't add file to category that does not allow files on it";
		}

		if(document.form.name.value == "")
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "Please fill the file name field";
		}

		if(document.form.long_desc.value == "")
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "Please fill the file long descritpion field";
		}
		<!-- IF MODE eq 'add' -->
		if(document.form.userfile.value == "" && document.form.download_url.value == "")
		{
			if(error_msg != "")
			{
				error_msg += "\n";
			}
			error_msg += "Please fill the file url field or click browse to upload file from your machine";
		}
		<!-- ENDIF -->

		if(error_msg != "")
		{
			alert(error_msg);
			error_msg = "";
			return false;
		}
		else
		{
			return true;
		}
	}
// ]]>
</script>

<h1>{L_FILE_TITLE}</h1>
<p>{L_FILE_EXPLAIN}</p>

<form action="{S_FILE_ACTION}" method="post" name="form" enctype="multipart/form-data" onsubmit="return checkAddForm();">
<!-- IF ERROR neq '' -->
<table class="forumline"><tr><td class="row2 row-center">{ERROR}</td></tr></table>
<br />
<!-- ENDIF -->
<table class="forumline">
<tr><th colspan="2">{L_FILE_TITLE}</th></tr>
<tr>
	<td class="row1 tw50pct"><strong>{L_FILE_NAME}</strong><br /><span class="gensmall">{L_FILE_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="name" value="{FILE_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_SHORT_DESC}</strong><br /><span class="gensmall">{L_FILE_SHORT_DESC_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="short_desc" value="{FILE_DESC}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_LONG_DESC}</strong><br /><span class="gensmall">{L_FILE_LONG_DESC_INFO}</span></td>
	<td class="row2"><textarea rows="6" name="long_desc" cols="32" class="post">{FILE_LONG_DESC}</textarea></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_AUTHOR}</strong><br /><span class="gensmall">{L_FILE_AUTHOR_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="author" value="{FILE_AUTHOR}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_VERSION}</strong><br /><span class="gensmall">{L_FILE_VERSION_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="version" value="{FILE_VERSION}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_WEBSITE}</strong><br /><span class="gensmall">{L_FILE_WEBSITE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="website" value="{FILE_WEBSITE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_POSTICONS}</strong><br /><span class="gensmall">{L_FILE_POSTICONS_INFO}</span></td>
	<td class="row2">{S_POSTICONS}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_CAT}</strong><br /><span class="gensmall">{L_FILE_CAT_INFO}</span></td>
	<td class="row2"><select name="cat_id" class="post">{S_CAT_LIST}</select></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_LICENSE}</strong><br /><span class="gensmall">{L_FILE_LICENSE_INFO}</span></td>
	<td class="row2"><select name="license" class="post">{S_LICENSE_LIST}</select></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_PINNED}</strong><br /><span class="gensmall">{L_FILE_PINNED_INFO}</span></td>
	<td class="row2">
		<input type="radio" name="pin" value="1"{PIN_CHECKED_YES} />{L_YES}&nbsp;
		<input type="radio" name="pin" value="0"{PIN_CHECKED_NO} />{L_NO}&nbsp;
	</td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_DOWNLOAD}</strong></td>
	<td class="row2"><input type="text" class="post" size="10" name="file_download" value="{FILE_DOWNLOAD}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_APPROVED}</strong><br /><span class="gensmall">{L_FILE_APPROVED_INFO}</span></td>
	<td class="row2">
	<input type="radio" name="approved" value="1" {APPROVED_CHECKED_YES} />{L_YES}&nbsp;
	<input type="radio" name="approved" value="0" {APPROVED_CHECKED_NO} />{L_NO}&nbsp;
	</td>
</tr>
<tr><th colspan="2">{L_SCREENSHOT}</th></tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_FILESS_UPLOAD}</strong></span><br /><span class="gensmall">{L_FILESSINFO_UPLOAD}</span></td>
	<td class="row2"><input type="file" size="50" name="screen_shot" maxlength="{FILESIZE}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_FILESS}</strong></span><br /><span class="gensmall">{L_FILESSINFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="screen_shot_url" value="{FILE_SSURL}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_FILE_SSLINK}</strong></span><br /><span class="gensmall">{L_FILE_SSLINK_INFO}</span></td>
	<td class="row2">
		<input type="radio" name="sshot_link" value="1" {SS_CHECKED_YES} />{L_YES}&nbsp;
		<input type="radio" name="sshot_link" value="0" {SS_CHECKED_NO} />{L_NO}&nbsp;
	</td>
</tr>
<tr><th colspan="2">{L_FILES}</th></tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_FILE_UPLOAD}</strong></span><br /><span class="gensmall">{L_FILEINFO_UPLOAD}</span></td>
	<td class="row2"><input type="file" size="50" name="userfile" maxlength="{FILESIZE}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_FILE_URL}</strong></span><br /><span class="gensmall">{L_FILE_URL_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="download_url" value="{FILE_DLURL}" /></td>
</tr>
<!-- IF MIRROR_FILE -->
<tr>
	<td class="row1"><span class="genmed"><strong>{L_UPLOADED_FILE}</strong></span></td>
	<td class="row2"><a href="{U_UPLOADED_MIRROR}">{MIRROR_FILE}</a></td>
</tr>
<!-- ENDIF -->
<tr>
	<td class="row1"><span class="genmed"><strong>{L_MIRRORS}</strong></span><br /><span class="gensmall">{L_MIRRORS_INFO}</span></td>
	<td class="row2"><input class="mainoption" type="submit" value="{L_CLICK_HERE_MIRRORS}" name="mirrors"<!-- IF MODE_EDIT --> onclick="set_mirror_mode(true);"<!-- ENDIF --> />
	</td>
</tr>
<!-- IF CUSTOM_EXIST -->
<tr><th colspan="2">{L_ADDTIONAL_FIELD}</th></tr>
<!-- ENDIF -->

<!-- INCLUDE pa_custom_field.tpl -->
<tr><td class="cat tdalignc" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_FILE_TITLE}" name="submit" onclick="set_mirror_mode(false);" /></td></tr>
</table>
</form>