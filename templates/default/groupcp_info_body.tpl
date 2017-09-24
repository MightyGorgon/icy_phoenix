<form action="{S_GROUPCP_ACTION}" method="post">

{IMG_THL}{IMG_THC}<span class="forumlink">{L_GROUP_INFORMATION}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<td class="row1 tw20pct"><span class="gen">{L_GROUP_NAME}:</span></td>
	<td class="row2"><span class="gen"{GROUP_COLOR_STYLE}>{GROUP_NAME}</span></td>
</tr>
<tr>
	<td class="row1 tw20pct"><span class="gen">{L_MEMBER_COUNT}:</span></td>
	<td class="row2"><span class="gen"><b>{L_COUNT}</b></span></td>
</tr>
<tr>
	<td class="row1 tw20pct"><span class="gen">{L_GROUP_DESC}:</span></td>
	<td class="row2"><span class="gen">{GROUP_DESC}&nbsp;</span></td>
</tr>
<tr>
	<td class="row1 tw20pct"><span class="gen">{L_GROUP_RANK}:</span></td>
	<td class="row2"><span class="gen">{GROUP_RANK}&nbsp;</span></td>
</tr>
<tr>
	<td class="row1 tw20pct"><span class="gen">{L_GROUP_MEMBERSHIP}:</span></td>
	<td class="row2"><span class="gen">{GROUP_DETAILS} &nbsp;&nbsp;
	<!-- BEGIN switch_subscribe_group_input -->
	<input class="mainoption" type="submit" name="joingroup" value="{L_JOIN_GROUP}" />
	<!-- END switch_subscribe_group_input -->
	<!-- BEGIN switch_unsubscribe_group_input -->
	<input class="mainoption" type="submit" name="unsub" value="{L_UNSUBSCRIBE_GROUP}" />
	<!-- END switch_unsubscribe_group_input -->
	</span></td>
</tr>
<!-- BEGIN switch_mod_option -->
<tr>
	<td class="row1 tw20pct"><span class="gen">{L_GROUP_TYPE}:</span></td>
	<td class="row2"><span class="genmed">
		<label><input type="radio" name="group_type" value="{S_GROUP_OPEN_TYPE}" {S_GROUP_OPEN_CHECKED} /> <span class="genmed">{L_GROUP_OPEN}</span></label>&nbsp;&nbsp;
		<label><input type="radio" name="group_type" value="{S_GROUP_CLOSED_TYPE}" {S_GROUP_CLOSED_CHECKED} /> <span class="genmed">{L_GROUP_CLOSED}</span></label>&nbsp;&nbsp;
		<label><input type="radio" name="group_type" value="{S_GROUP_HIDDEN_TYPE}" {S_GROUP_HIDDEN_CHECKED} /> <span class="genmed">{L_GROUP_HIDDEN}</span></label>&nbsp;&nbsp;
		<input class="mainoption" type="submit" name="groupstatus" value="{L_UPDATE}" />
	</span></td>
</tr>
<!-- END switch_mod_option -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
{S_HIDDEN_FIELDS}
</form>

<form action="{S_GROUPCP_ACTION}" name="group_info" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_GROUP_MODERATOR}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th>{L_USERNAME}</th>
	<th>{L_POSTS}</th>
	<th>{L_FROM}</th>
	<th>{L_ONLINE_STATUS}</th>
	<th>{L_PM}</th>
	<th>{L_EMAIL}</th>
	<th>{L_WEBSITE}</th>
</tr>
<tr>
	<td class="row1h row-center" data-href="{U_VIEWPROFILE}">{USERNAME}</td>
	<td class="row2 row-center">{POSTS}</td>
	<td class="row1 row-center">&nbsp;{FROM}&nbsp;</td>
	<td class="row1 row-center">&nbsp;{ONLINE_STATUS_IMG}&nbsp;</td>
	<td class="row1 post-buttons-single">&nbsp;{PM_IMG}&nbsp;</td>
	<td class="row1 post-buttons-single">&nbsp;{EMAIL_IMG}&nbsp;</td>
	<td class="row1 post-buttons-single">&nbsp;{WWW_IMG}&nbsp;</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_GROUP_MEMBERS}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th>{L_USERNAME}</th>
	<th>{L_POSTS}</th>
	<th>{L_FROM}</th>
	<th>{L_ONLINE_STATUS}</th>
	<th>{L_PM}</th>
	<th>{L_EMAIL}</th>
	<th>{L_WEBSITE}</th>
	<th>{L_SELECT}</th>
	<!-- HOOK groupcp_table_th -->
</tr>
<!-- BEGIN memberrow -->
<tr>
	<td class="row1h row-center" data-href="{memberrow.U_VIEWPROFILE}">{memberrow.USERNAME}</td>
	<td class="row2 row-center">{memberrow.POSTS}</td>
	<td class="row1 row-center">&nbsp;{memberrow.FROM}&nbsp;</td>
	<td class="row1 row-center">&nbsp;{memberrow.ONLINE_STATUS_IMG}&nbsp;</td>
	<td class="row1 post-buttons-single">&nbsp;{memberrow.PM_IMG}&nbsp;</td>
	<td class="row1 post-buttons-single">&nbsp;{memberrow.EMAIL_IMG}&nbsp;</td>
	<td class="row1 post-buttons-single">&nbsp;{memberrow.WWW_IMG}&nbsp;</td>
	<td class="row3 row-center">&nbsp;
		<!-- BEGIN switch_mod_option -->
		<input type="checkbox" name="members[]" value="{memberrow.USER_ID}" />
		<!-- END switch_mod_option -->
	&nbsp;</td>
</tr>
<!-- END memberrow -->
<!-- BEGIN switch_no_members -->
<tr><td class="row1 row-center" colspan="8">{L_NO_MEMBERS}</td></tr>
<!-- END switch_no_members -->
<!-- BEGIN switch_hidden_group -->
<tr><td class="row1 row-center" colspan="8">{L_HIDDEN_MEMBERS}</td></tr>
<!-- END switch_hidden_group -->
<!-- BEGIN switch_mod_option -->
<tr>
	<td class="cat" colspan="8">
	<!-- BEGIN switch_admin -->
	<input type="submit" name="colorize_all" value="{L_COLORIZE_ALL}" class="mainoption" />&nbsp;
	<input type="submit" name="mass_colorize" value="{L_COLORIZE_SELECTED}" class="mainoption" />&nbsp;
	<!-- END switch_admin -->
	<input type="submit" name="remove" value="{L_REMOVE_SELECTED}" class="mainoption" />
	</td>
</tr>
<!-- END switch_mod_option -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
{S_HIDDEN_FIELDS}
</form>

<form action="{S_GROUPCP_ACTION}" name="post" method="post">
<table>
<tr>
	<td>
		<!-- BEGIN switch_mod_option -->
		<span class="genmed">
		<input type="text" class="post" name="username" id="username" maxlength="50" size="20" {S_AJAX_USER_CHECK} />&nbsp;
		<span id="username_list" style="display: none;">&nbsp;<span id="username_select">&nbsp;</span></span>
		<input type="submit" name="add" value="{L_ADD_MEMBER}" class="mainoption" />&nbsp;
		<input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onclick="window.open('{U_SEARCH_USER}', '_search', 'width=400,height=250,resizable=yes'); return false;" />
		</span>
		<br />
		<span id="username_error_tbl" style="display: none;"><span id="username_error_text" class="gen">&nbsp;</span></span>
		<br /><br />
		<!-- END switch_mod_option -->
		{PAGE_NUMBER}
	</td>
	<td class="tdalignr">
		<a href="#" onclick="setCheckboxes('group_info', 'members[]', true); return false;" class="gensmall">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="setCheckboxes('group_info', 'members[]', false); return false;" class="gensmall">{L_UNMARK_ALL}</a><br class="mb5" />
		<span class="gensmall">{S_TIMEZONE}</span><br /><span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>

{PENDING_USER_BOX}

{S_HIDDEN_FIELDS}
</form>

<table class="s2px p2px"><tr><td class="tdalignr">{JUMPBOX}</td></tr></table>
