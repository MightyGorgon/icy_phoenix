<form action="{S_FORM_ACTION}" method="post" name="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DL_COMMENT}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="20%" valign="top" nowrap="nowrap">{BBCB_SMILEYS_MG}</td>
	<td class="row2" valign="top">
		{BBCB_MG}
		<textarea name="message" rows="15" cols="75" wrap="virtual" style="width:98%" tabindex="3" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{COMMENT_TEXT}</textarea>
	</td>
</tr>
<tr><td colspan="2" class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td colspan="2" class="cat">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" name="save" value="{L_SUBMIT}" />&nbsp;&nbsp;<input class="liteoption" type="submit" name="goback" value="{L_CANCEL}" /></td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
