<!-- INCLUDE breadcrumbs.tpl -->

{POST_PREVIEW_BOX}
{ERROR_BOX}
<form action="{U_SHOUTBOX}" method="post" name="post" onsubmit="return checkForm(this)">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SHOUT_TEXT}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN switch_username_select -->
<tr>
	<td class="row1"><span class="gen"><b>{L_USERNAME}</b></span></td>
	<td class="row2"><span class="genmed"><input type="text" class="post" tabindex="1" name="username" size="25" maxlength="25" value="{USERNAME}" /></span></td>
</tr>
<!-- END switch_username_select -->
<tr>
	<td class="row1" valign="top">
		<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center" valign="middle"><br />{BBCB_SMILEYS_MG}</td></tr></table>
	</td>
	<td class="row2" valign="top">
		<table width="450" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td colspan="9">
				{BBCB_MG}
				<textarea name="message" rows="15" cols="35" wrap="virtual" style="width:98%" tabindex="3" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{MESSAGE}</textarea>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="row1" valign="top">
		<span class="gen"><b>{L_OPTIONS}</b></span><br />
		<span class="gensmall">{HTML_STATUS}<br />{BBCODE_STATUS}<br />{SMILIES_STATUS}</span>
	</td>
	<td class="row2">
		<table cellspacing="0" cellpadding="1" border="0">
			<!-- BEGIN switch_html_checkbox -->
			<tr><td><label><input type="checkbox" name="disable_html" {S_HTML_CHECKED} /><span class="gen">{L_DISABLE_HTML}</span></label></td></tr>
			<!-- END switch_html_checkbox -->
			<!-- BEGIN switch_bbcode_checkbox -->
			<tr><td><label><input type="checkbox" name="disable_bbcode" {S_BBCODE_CHECKED} /><span class="genmed">{L_DISABLE_BBCODE}</span></label></td></tr>
			<!-- END switch_bbcode_checkbox -->
		</table>
	</td>
</tr>
<tr>
	<td class="catBottom" colspan="2">{S_HIDDEN_FORM_FIELDS}
		<input type="submit" tabindex="5" name="refresh" class="mainoption" value="{L_SHOUT_REFRESH}" />&nbsp;
		<input type="submit" tabindex="5" name="preview" class="mainoption" value="{L_SHOUT_PREVIEW}" />&nbsp;
		<input type="submit" accesskey="s" tabindex="6" name="shout" class="mainoption" value="{L_SHOUT_SUBMIT}" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr>
	<td align="right" valign="bottom">
		<span class="gensmall">&nbsp;{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SHOUTBOX}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="160" nowrap="nowrap">{L_AUTHOR}</th>
	<th nowrap="nowrap">{L_MESSAGE}</th>
</tr>
<!-- BEGIN shoutrow -->
<tr>
	<td class="row-post-author" nowrap="nowrap">
		<span class="post-name">{shoutrow.SHOUT_USERNAME}</span><br />
		<span class="post-images">{shoutrow.USER_AVATAR}</span>
		<div class="post-details">{shoutrow.USER_JOINED}<br /></div><br />
		<img src="{SPACER}" width="150" height="3" alt="" />
	</td>
	<td class="row-post" width="100%" height="100%">
		<div class="post-buttons-top post-buttons">{shoutrow.CENSOR_IMG}{shoutrow.EDIT_IMG}{shoutrow.DELETE_IMG}{shoutrow.IP_IMG}</div>
		<div class="post-subject"><span class="genmed">{L_POSTED}:&nbsp;{shoutrow.TIME}</span></div>
		<div class="post-text">{shoutrow.SHOUT}{shoutrow.SIGNATURE}</div>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END shoutrow -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr>
	<td align="right" valign="bottom">
		<span class="gensmall">&nbsp;{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>