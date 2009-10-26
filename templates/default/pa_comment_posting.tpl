<!-- INCLUDE pa_header.tpl -->
<!-- INCLUDE pa_links.tpl -->

<form action="{S_POST_ACTION}" method="post" name="post" onsubmit="return checkForm(this)">
<!-- IF PREVIEW -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_PREVIEW}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1g-left" valign="top"><div class="post-text">{PRE_COMMENT}</div></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- ENDIF -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_COMMENT_ADD}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="22%"><span class="gen"><b>{L_COMMENT_TITLE}</b></span></td>
	<td class="row2" width="78%"><input type="text" name="subject" size="45" maxlength="60" style="width: 98%;" tabindex="2" class="post" value="{SUBJECT}" /></td></td>
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
		<div class="message-box"><textarea id="message" name="message" rows="15" cols="76" tabindex="3" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{COMMENT}</textarea></div>
	</td>
</tr>
<tr>
	<td class="row1"><span class="gen"><B>{L_OPTIONS}</b></span><br /><span class="gensmall">{HTML_STATUS}<br />{BBCODE_STATUS}<br />{SMILIES_STATUS}<br />{LINKS_STATUS}<br />{IMAGES_STATUS}</span></td>
	<td class="row2"><span class="gen">{L_COMMENT_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="cat" colspan="2">
		{S_HIDDEN_FORM_FIELDS}
		<input type="submit" tabindex="5" name="preview" class="mainoption" value="{L_PREVIEW}" />&nbsp;
		<input type="submit" accesskey="s" tabindex="6" name="submit" class="mainoption" value="{L_SUBMIT}" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
</form>
<!-- INCLUDE pa_footer.tpl -->