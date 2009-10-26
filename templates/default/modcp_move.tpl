<form action="{S_MODCP_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{MESSAGE_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row-post" style="padding:20px;text-align:center;">
		<span class="gen">
		{L_MOVE_TO_FORUM} &nbsp; {S_FORUM_SELECT}<br /><br />
		<label><input type="checkbox" name="move_leave_shadow" />&nbsp;{L_LEAVESHADOW}</label><br />
		<br />
		{MESSAGE_TEXT}<br />
		<br />
		{S_HIDDEN_FIELDS}
		<input class="mainoption" type="submit" name="confirm" value="{L_YES}" />
		&nbsp;&nbsp;
		<input class="liteoption" type="submit" name="cancel" value="{L_NO}" />
		</span>
	</td>
</tr>
<tr><td class="cat">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>