{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_DL_INDEX}">{L_DOWNLOADS}</a>{NAV_SEP}<a href="{U_BUG_TRACKER}" class="nav-current">{L_BUG_TRACKER}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_BUG_TRACKER}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="80%" valign="top">
		<table width="100%" cellpadding="2" cellspacing="1" border="0">
		<tr><td class="row2"><span class="genmed">{L_REPORT_TITLE}:</span><br /><span class="topictitle">{REPORT_TITLE}</span></td></tr>
		</table>
		<br />
		<table width="100%" cellpadding="2" cellspacing="1" border="0">
		<tr><td class="row1"><span class="genmed">{L_REPORT_TEXT}:</span><hr /><span class="postbody">{REPORT_TEXT}</span></td></tr>
		</table>
	</td>
	<td width="20%" valign="top">
		<table width="100%" cellpadding="2" cellspacing="1" border="0">
		<tr><td class="row1"><span class="genmed"><b>{L_REPORT_ID}:</b><br />{REPORT_ID}</span></td></tr>
		<tr><td class="row1"><span class="genmed"><b>{L_REPORT_FILE}:</b><br /><a href="{U_DOWNLOAD_FILE}" class="genmed" target="_blank">{REPORT_FILE}</a></span></td></tr>
		<tr><td class="row1"><span class="genmed"><b>{L_REPORT_FILE_VER}:</b><br />{REPORT_FILE_VER}</span></td></tr>
		<tr><td class="row1"><span class="genmed"><b>{L_REPORT_DATE}:</b><br />{REPORT_DATE}</span></td></tr>
		<tr><td class="row1"><span class="genmed"><b>{L_REPORT_AUTHOR}:</b><br /><a href="{U_AUTHOR_LINK}" class="genmed" target="_blank">{REPORT_AUTHOR}</a></span></td></tr>
		</table>
		<br />
		<table width="100%" cellpadding="2" cellspacing="1" border="0">
		<tr><td class="row1"><span class="genmed"><b>{L_REPORT_PHP}:</b><br />{REPORT_PHP}</span></td></tr>
		<tr><td class="row1"><span class="genmed"><b>{L_REPORT_DB}:</b><br />{REPORT_DB}</span></td></tr>
		<tr><td class="row1"><span class="genmed"><b>{L_REPORT_FORUM}:</b><br />{REPORT_FORUM}</span></td></tr>
		</table>
		<br />
		<table width="100%" cellpadding="2" cellspacing="1" border="0">
		<tr><td class="row1"><span class="genmed"><b>{L_STATUS}:</b><br />{REPORT_STATUS}</span></td></tr>
		<tr><td class="row1"><span class="genmed"><b>{L_STATUS_DATE}:</b><br />{STATUS_DATE}</span></td></tr>
		</table>
		<br />
		<form action="{S_FORM_ASSIGN_ACTION}" name="assign_user" method="post">
		<table width="100%" cellpadding="2" cellspacing="1" border="0">
		<!-- BEGIN assign -->
		<tr>
			<td class="row1"><span class="genmed"><b>{assign.L_ASSIGN_TO}:</b><br />{assign.U_ASSIGN_TO}</span></td>
		</tr>
		<tr><td class="row1"><span class="genmed"><b>{assign.L_ASSIGN_DATE}:</b><br />{assign.ASSIGN_DATE}</span></td></tr>
		<!-- END assign -->
		<!-- BEGIN no_assign -->
		<tr><td class="row1"><span class="genmed"><b>{no_assign.L_NO_ASSIGN}</b></span></td></tr>
		<!-- END no_assign -->
		<!-- BEGIN assign_mod -->
		<tr><td class="row1">{S_SELECT_ASSIGN_USER}<br /><input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></td></tr>
		<!-- END assign_mod -->
		</table>
		</form>
		<!-- BEGIN delete -->
		<table width="100%" cellpadding="2" cellspacing="1" border="0">
		<tr><td class="row1"><a href="{delete.U_DELETE}" class="genmed">{delete.L_DELETE}</a></td></tr>
		</table>
		<!-- END delete -->

	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<br />

<!-- BEGIN status_select -->
<form action="{status_select.S_FORM_STATUS_ACTION}" method="post" name="status_change">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_STATUS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="95%" align="center">
		<table width="100%" cellpadding="3" cellspacing="1" border="0">
		<tr><td class="cat" align="center" colspan="2"><span class="nav">{L_STATUS}</span></td></tr>
		<tr>
			<td class="row1" width="20%" valign="top"><span class="genmed">{L_STATUS}: </span>{status_select.S_SELECT_STATUS}<br /><br /><input type="submit" name="submit" value="{L_STATUS_UPDATE}" class="liteoption" /></td>
			<td class="row2" width="80%"><span class="genmed">{L_STATUS_TEXT}:</span><br /><textarea cols="75" rows="3" class="post" name="new_status_text"></textarea></td>
		</tr>
		</table>

	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<!-- END status_select -->

<!-- BEGIN history -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_REPORT_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="95%" align="center">
		<table width="100%" cellpadding="3" cellspacing="1" border="0">
		<tr><td class="cat" align="center" colspan="2"><span class="nav">{history.L_REPORT_HISTORY}</span></td></tr>
		<!-- BEGIN history_row -->
		<tr>
			<td class="row1" width="20%" valign="top"><span class="nav">{history.history_row.VALUE}</span><br /><span class="gensmall">{history.history_row.DATE}</span></td>
			<td class="row2" width="80%" valign="top"><span class="gen">{history.history_row.TEXT}</span></td>
		</tr>
		<!-- END history_row -->
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END history -->

{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_DL_INDEX}">{L_DOWNLOADS}</a>{NAV_SEP}<a href="{U_BUG_TRACKER}" class="nav-current">{L_BUG_TRACKER}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

<br />