<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
<tr>
	<td align="left"><span class="maintitle">{L_PAGE_NAME}</span>
		<br /><span class="gensmall"><b>{L_VERSION} {VERSION}
		<br />{NIVISEC_CHECKER_VERSION}</b></span><br /><br />
	<span class="genmed">{L_PAGE_DESC}<br /><br />{VERSION_CHECK_DATA}</span></td>
</tr>
</table>

<form method="post" action="{S_MODE_ACTION}" name="update_hacks">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_PAGE_NAME}</th></tr>
<tr>
	<td class="row1" width="40%"><span class="genmed"><strong>{L_HACK_NAME}:</strong><br /></span><span class="gensmall">*{L_REQUIRED}</span></td>
	<td class="row1"><input type="text" class="post" name="hack_name" value="{S_HACK_NAME}" maxlength="255" size="50" /></td>
</tr>
<tr>
	<td class="row2"><span class="genmed"><strong>{L_VERSION}:</strong></td>
	<td class="row2"><input type="text" class="post" name="hack_version" value="{S_HACK_VERSION}" maxlength="255" size="10" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_DESCRIPTION}:</strong></span><br /><span class="gensmall">*{L_REQUIRED}</span></td>
	<td class="row1"><textarea class="post" cols="50" rows="4" name="hack_desc">{S_HACK_DESC}</textarea></td>
</tr>
<tr>
	<td class="row2"><span class="genmed"><strong>{L_DOWNLOAD_URL}:</strong></span></td>
	<td class="row2"><textarea class="post" cols="50" rows="4" name="hack_download_url">{S_HACK_DOWNLOAD}</textarea></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_AUTHOR}:</strong></span><br /><span class="gensmall">*{L_REQUIRED}</span></td>
	<td class="row1"><input type="text" class="post" name="hack_author" value="{S_HACK_AUTHOR}" maxlength="255" size="32" /></td>
</tr>
<tr>
	<td class="row2"><span class="genmed"><strong>{L_AUTHOR_EMAIL}:</strong></span></td>
	<td class="row2"><input type="text" class="post" name="hack_author_email" value="{S_HACK_AUTHOR_EMAIL}" maxlength="255" size="32" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_WEBSITE}:</strong></span></td>
	<td class="row1"><textarea class="post" cols="50" rows="4" name="hack_author_website">{S_HACK_WEBSITE}</textarea></td>
</tr>
<tr>
	<td class="row2"><span class="genmed"><strong>{L_USER_VIEWABLE}:</strong></span></td>
	<td class="row2"><input type="radio" name="hack_hide" value="Yes" {S_HACK_HIDE_YES} />&nbsp;{L_YES}&nbsp;&nbsp;
	<input type="radio" name="hack_hide" value="No" {S_HACK_HIDE_NO} />&nbsp;{L_NO}</td>
</tr>
<tr>
	<td align="center" class="cat" colspan="2">
	<input type="hidden" name="{S_HIDDEN}" value="{S_HACK_ID}" />
	<input type="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>
</form>