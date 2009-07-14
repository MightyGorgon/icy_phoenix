<!-- INCLUDE pa_header.tpl -->
<!-- INCLUDE pa_links.tpl -->

<script type="text/javascript">
<!--
	var error_msg = "";
	function checkAddForm()
	{
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

		<!-- IF MODE eq 'ADD' -->
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
// -->
</script>

<form enctype="multipart/form-data" action="{S_ADD_FILE_ACTION}" method="post" name="form" onsubmit="return checkAddForm();">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_FILE_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="50%" class="row1"><span class="genmed">{L_FILE_NAME}</span><br /><span class="gensmall">{L_FILE_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="name" value="{FILE_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_SHORT_DESC}</span><br /><span class="gensmall">{L_FILE_SHORT_DESC_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="short_desc" value="{FILE_DESC}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_LONG_DESC}</span><br /><span class="gensmall">{L_FILE_LONG_DESC_INFO}</span></td>
	<td class="row2"><textarea rows="6" name="long_desc" cols="32">{FILE_LONG_DESC}</textarea></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_AUTHOR}</span><br /><span class="gensmall">{L_FILE_AUTHOR_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="author" value="{FILE_AUTHOR}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_VERSION}</span><br /><span class="gensmall">{L_FILE_VERSION_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="version" value="{FILE_VERSION}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_WEBSITE}</span><br /><span class="gensmall">{L_FILE_WEBSITE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="website" value="{FILE_WEBSITE}" /></td>
</tr>

<tr>
	<td class="row1"><span class="genmed">{L_FILE_POSTICONS}</span><br /><span class="gensmall">{L_FILE_POSTICONS_INFO}</span></td>
	<td class="row2">{S_POSTICONS}</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_CAT}</span><br /><span class="gensmall">{L_FILE_CAT_INFO}</span></td>
	<td class="row2"><select name="cat_id" class="post">{S_CAT_LIST}</select></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_LICENSE}</span><br /><span class="gensmall">{L_FILE_LICENSE_INFO}</span></td>
	<td class="row2"><select name="license" class="post">{S_LICENSE_LIST}</select></td>
</tr>
<!-- IF IS_ADMIN or IS_MOD -->
<tr>
	<td class="row1"><span class="genmed">{L_FILE_PINNED}</span><br /><span class="gensmall">{L_FILE_PINNED_INFO}</span></td>
	<td class="row2">
		<input type="radio" name="pin" value="1"{PIN_CHECKED_YES} /><span class="genmed">{L_YES}</span>&nbsp;
		<input type="radio" name="pin" value="0"{PIN_CHECKED_NO} /><span class="genmed">{L_NO}</span>&nbsp;
	</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_DOWNLOAD}</span></td>
	<td class="row2"><input type="text" class="post" size="10" name="file_download" value="{FILE_DOWNLOAD}" /></td>
</tr>
<!-- ENDIF -->
<!--
<tr>
	<td class="row1"><span class="genmed">{L_FILE_APPROVED}</span><br /><span class="gensmall">{L_FILE_APPROVED_INFO}</span></td>
	<td class="row2">
	<input type="radio" name="approved" value="1" {APPROVED_CHECKED_YES}>{L_YES}&nbsp;
	<input type="radio" name="approved" value="0" {APPROVED_CHECKED_NO}>{L_NO}&nbsp;
	</td>
</tr>
-->
<tr><th class="cat" colspan="2" align="center"><span>{L_SCREENSHOT}</span></th></tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILESS_UPLOAD}</span><br /><span class="gensmall">{L_FILESSINFO_UPLOAD}</span></td>
	<td class="row2"><input type="file" size="50" name="screen_shot" maxlength="{FILESIZE}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILESS}</span><br /><span class="gensmall">{L_FILESSINFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="screen_shot_url" value="{FILE_SSURL}"></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_SSLINK}</span><br /><span class="gensmall">{L_FILE_SSLINK_INFO}</span></td>
	<td class="row2">
		<input type="radio" name="sshot_link" value="1" {SS_CHECKED_YES}><span class="genmed">{L_YES}</span>&nbsp;
		<input type="radio" name="sshot_link" value="0" {SS_CHECKED_NO}><span class="genmed">{L_NO}</span>&nbsp;
	</td>
</tr>
<tr><th colspan="2" align="center"><span>{L_FILES}</span></th></tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_UPLOAD}</span><br /><span class="gensmall">{L_FILEINFO_UPLOAD}</span></td>
	<td class="row2"><input type="file" size="50" name="userfile" maxlength="{FILESIZE}" class="post" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_FILE_URL}</span><br /><span class="gensmall">{L_FILE_URL_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="download_url" value="{FILE_DLURL}" /></td>
</tr>
<!-- IF CUSTOM_EXIST -->
<tr><td class="cat" colspan="2" align="center"><span class="cattitle">{L_ADDTIONAL_FIELD}</span></td></tr>
<!-- ENDIF -->

<!-- INCLUDE pa_custom_field.tpl -->
<tr><td align="center" class="cat" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_FILE_TITLE}" name="submit"></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
</form>
<!-- INCLUDE pa_footer.tpl -->