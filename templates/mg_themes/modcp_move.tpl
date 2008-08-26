{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_CAT_DESC}
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_TBL}<div class="forumline">
	<p class="row-header"><span>{MESSAGE_TITLE}</span></p>
	<div class="row1g gen" style="padding:20px;text-align:center;">
		<form action="{S_MODCP_ACTION}" method="post">
			{L_MOVE_TO_FORUM} &nbsp; {S_FORUM_SELECT}<br /><br />
			<label><input type="checkbox" name="move_leave_shadow" />&nbsp;{L_LEAVESHADOW}</label><br />
				<br />
				{MESSAGE_TEXT}<br />
				<br />
				{S_HIDDEN_FIELDS}
				<input class="mainoption" type="submit" name="confirm" value="{L_YES}" />
				&nbsp;&nbsp;
				<input class="liteoption" type="submit" name="cancel" value="{L_NO}" />
		</form>
	</div>
</div>{IMG_TBR}