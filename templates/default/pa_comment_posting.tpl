<!-- INCLUDE pa_header.tpl -->
<form action="{S_POST_ACTION}" method="post" name="post" onsubmit="return checkForm(this)">
{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_INDEX}">{L_HOME}</a>{NAV_SEP}<a href="{U_DOWNLOAD}" class="nav">{DOWNLOAD}</a><!-- BEGIN navlinks -->{NAV_SEP}<a href="{navlinks.U_VIEW_CAT}" class="nav">{navlinks.CAT_NAME}</a><!-- END navlinks -->{NAV_SEP}<a href="{U_FILE_NAME}" class="nav">{FILE_NAME}</a>{NAV_SEP}<a href="#" class="nav-current">{L_COMMENT_ADD}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		<!-- INCLUDE pa_links.tpl -->
	</div>
</div>{IMG_TBR}

<!-- IF PREVIEW -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_PREVIEW}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1g-left" valign="top"><div class="post-text">{PRE_COMMENT}</div></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- ENDIF -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_COMMENT_ADD}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="22%"><span class="gen"><b>{L_COMMENT_TITLE}</b></span></td>
	<td class="row2" width="78%"><input type="text" name="subject" size="45" maxlength="60" style="width:98%" tabindex="2" class="post" value="{SUBJECT}" /></td></td>
</tr>
<tr>
	<td class="row1" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="empty-table">
			<tr><td><span class="gen"><b>{L_COMMENT}</b></span></td></tr>
			<tr>
				<td valign="middle" align="center">
					<br />
					<!-- INCLUDE bbcb_smileys_mg.tpl -->
				</td>
			</tr>
		</table>
		</td>
		<td class="row2" valign="top">
		<!-- INCLUDE bbcb_mg.tpl -->
		<textarea name="message" rows="15" cols="35" wrap="virtual" style="width:98%" tabindex="3" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{COMMENT}</textarea>
	</td>
</tr>
<tr>
	<td class="row1"><span class="gen"><B>{L_OPTIONS}</b></span><br /><span class="gensmall">{HTML_STATUS}<br />{BBCODE_STATUS}<br />{SMILIES_STATUS}<br />{LINKS_STATUS}<br />{IMAGES_STATUS}</span></td>
	<td class="row2"><span class="gen">{L_COMMENT_EXPLAIN}<br /><a href="javascript:checklength(document.post);">{L_CHECK_MSG_LENGTH}</a></span></td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center" height="28">
		{S_HIDDEN_FORM_FIELDS}
		<input type="submit" tabindex="5" name="preview" class="mainoption" value="{L_PREVIEW}" />&nbsp;
		<input type="submit" accesskey="s" tabindex="6" name="submit" class="mainoption" value="{L_SUBMIT}" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
</form>
<!-- INCLUDE pa_footer.tpl -->