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
<!-- BEGIN bug_tracker_list_head -->
<tr>
	<th colspan="2">{L_REPORT_TITLE}</th>
	<th>{L_REPORT_FILE}</th>
	<th colspan="2">{L_REPORT_STATUS}</th>
</tr>
<!-- END bug_tracker_list_head -->
<!-- BEGIN bug_tracker_list -->
<tr>
	<td class="{bug_tracker_list.ROW_CLASS}"><span class="nav">{bug_tracker_list.REPORT_ID}</span></td>
	<td class="{bug_tracker_list.ROW_CLASS}"><span class="gensmall"><b>{bug_tracker_list.REPORT_TITLE}</b> [<a href="{bug_tracker_list.REPORT_DETAIL}" class="gensmall">{L_REPORT_DETAIL}</a>]<br />{L_REPORT_DATE}: {bug_tracker_list.REPORT_DATE}<br />{L_REPORT_AUTHOR}: {bug_tracker_list.REPORT_AUTHOR_LINK}</span></td>
	<td class="{bug_tracker_list.ROW_CLASS}"><span class="nav"><a href="{bug_tracker_list.REPORT_FILE_LINK}" class="nav">{bug_tracker_list.REPORT_FILE} {bug_tracker_list.REPORT_FILE_VER}</a></span></td>
	<td class="{bug_tracker_list.ROW_CLASS}"><span class="nav">{bug_tracker_list.REPORT_STATUS}</span><br /><span class="gensmall">{bug_tracker_list.REPORT_STATUS_DATE}</span></td>
	<td class="{bug_tracker_list.ROW_CLASS}"><span class="gensmall">
	<!-- BEGIN assign -->
		{bug_tracker_list.assign.REPORT_ASSIGN_LINK}<br />
		{bug_tracker_list.assign.REPORT_ASSIGN_DATE}<br />
	<!-- END assign -->
	<!-- BEGIN no_assign -->
		{bug_tracker_list.no_assign.L_NO_ASSIGNED}&nbsp;
	<!-- END no_assign -->
	<!-- BEGIN mod -->
		<a href="{bug_tracker_list.mod.U_DELETE}" class="gensmall">{bug_tracker_list.mod.L_DELETE}</a><br />
	<!-- END mod -->
	</span></td>
</tr>
<!-- END bug_tracker_list -->
<!-- BEGIN no_bug_tracker_list -->
<tr><th>&nbsp;</th></tr>
<tr><td class="row1 row-center" height="28"><span class="genmed"><b>{no_bug_tracker_list.L_NO_BUG_TRACKER}</b></span></td></tr>
<!-- END no_bug_tracker_list -->
<!-- BEGIN bug_tracker_file_head -->
<tr>
	<th colspan="2">{L_REPORT_TITLE}</th>
	<th>{L_REPORT_FILE}</th>
	<th>{L_REPORT_PHP}, {L_REPORT_DB}, {L_REPORT_FORUM}</th>
	<th colspan="2">{L_REPORT_STATUS}</th>
</tr>
<!-- END bug_tracker_file_head -->
<!-- BEGIN bug_tracker_file -->
<tr>
	<td class="{bug_tracker_file.ROW_CLASS}"><span class="nav">{bug_tracker_file.REPORT_ID}</span></td>
	<td class="{bug_tracker_file.ROW_CLASS}"><span class="gensmall"><b>{bug_tracker_file.REPORT_TITLE}</b> [<a href="{bug_tracker_file.REPORT_DETAIL}" class="gensmall">{L_REPORT_DETAIL}</a>]<br />{L_REPORT_DATE}: {bug_tracker_file.REPORT_DATE}<br />{L_REPORT_AUTHOR}: {bug_tracker_file.REPORT_AUTHOR_LINK}</span><hr /><span class="gensmall"><i>{bug_tracker_file.REPORT_TEXT}</i></span></td>
	<td class="{bug_tracker_file.ROW_CLASS}"><span class="nav"><a href="{bug_tracker_file.REPORT_FILE_LINK}" class="nav">{bug_tracker_file.REPORT_FILE} {bug_tracker_file.REPORT_FILE_VER}</a></span></td>
	<td class="{bug_tracker_file.ROW_CLASS}"><span class="gensmall">{bug_tracker_file.REPORT_PHP}<br />{bug_tracker_file.REPORT_DB}<br />{bug_tracker_file.REPORT_FORUM}</span></td>
	<td class="{bug_tracker_file.ROW_CLASS}"><span class="nav">{bug_tracker_file.REPORT_STATUS}</span><br /><span class="gensmall">{bug_tracker_file.REPORT_STATUS_DATE}</span></td>
	<td class="{bug_tracker_file.ROW_CLASS}"><span class="gensmall">
	<!-- BEGIN assign -->
		{bug_tracker_file.assign.REPORT_ASSIGN_LINK}<br />
		{bug_tracker_file.assign.REPORT_ASSIGN_DATE}<br />
	<!-- END assign -->
	<!-- BEGIN no_assign -->
		{bug_tracker_file.no_assign.L_NO_ASSIGNED}&nbsp;
	<!-- END no_assign -->
	<!-- BEGIN mod -->
		<a href="{bug_tracker_file.mod.U_DELETE}" class="gensmall">{bug_tracker_file.mod.L_DELETE}</a><br />
	<!-- END mod -->
	</span></td>
</tr>
<!-- END bug_tracker_file -->
<!-- BEGIN no_bug_tracker_file -->
<tr><th>&nbsp;</th></tr>
<tr><td class="row1 row-center" height="28"><span class="genmed"><b>{no_bug_tracker_file.L_NO_BUG_TRACKER}</b></span></td></tr>
<!-- END no_bug_tracker_file -->
<tr><td class="cat" {SPANINC} align="center">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table width="100%" cellpadding="2" cellspacing="1" border="0">
<tr>
	<td align="center">
		<form action="{S_FORM_ACTION}" method="post" name="filter_report">
		<table cellpadding="2" cellspacing="1" border="0" align="center">
		<tr><td>{S_SELECT_FILTER}&nbsp;<input type="submit" name="submit" value="{L_REPORT_FILTER}" class="liteoption" /></td></tr>
		</table>
		</form>
	</td>
	<!-- BEGIN own_report -->
	<td align="center">

		<form action="{S_FORM_OWN_ACTION}" method="post" name="filter_own_report">
		<table cellpadding="2" cellspacing="1" border="0" align="center">
		<tr>
			<td><input type="submit" name="submit" value="{L_REPORT_FILTER_OWN}" class="liteoption" /></td>
		</tr>
		</table>
		</form>
	</td>
	<!-- END own_report -->
	<!-- BEGIN assign_report -->
	<td align="center">

		<form action="{S_FORM_ASSIGN_ACTION}" method="post" name="filter_assign_report">
		<table cellpadding="2" cellspacing="1" border="0" align="center">
		<tr><td><input type="submit" name="submit" value="{L_REPORT_FILTER_ASSIGN}" class="liteoption" /></td></tr>
		</table>
		</form>
	</td>
	<!-- END assign_report -->
	<!-- BEGIN add_new_report -->
	<td align="center">

		<form action="{S_FORM_ACTION}" method="post" name="add_new_report">
		<table cellpadding="2" cellspacing="1" border="0" align="center">
		<tr><td><input type="submit" name="submit" value="{add_new_report.L_ADD_REPORT}" class="mainoption" /></tr>
		</table>
		{S_HIDDEN_FIELDS}
		</form>
	</td>
	<!-- END add_new_report -->

</tr>
</table>

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