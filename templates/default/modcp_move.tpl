<form action="{S_MODCP_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{MESSAGE_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row-post" style="padding: 20px; text-align: center;">
		<span class="gen">
		{L_MOVE_TO_FORUM} &nbsp; {S_FORUM_SELECT}<br /><br />
		<!-- IF S_MOVE_ALL_SWITCH -->
		<label>{L_MOVED_TOPICS_PREFIX}&nbsp;<input type="text" class="post" name="moved_topics_prefix" maxlength="25" size="25" value="" /></label><br />
		<!-- ELSE -->
		<label><input type="checkbox" name="move_leave_shadow" />&nbsp;{L_LEAVESHADOW}</label><br />
		<!-- ENDIF -->
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