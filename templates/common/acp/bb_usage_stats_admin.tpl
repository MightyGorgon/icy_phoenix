<!-- **** BB USAGE STATISTICS MOD **** -->
<script type="text/javascript">
<!--
function setValue(textObj, chkObj, viewlevel)
{
	var chkObjValue = document.getElementById(chkObj);
	var txtObjValue = parseInt(document.getElementById(textObj).value);

	if (chkObjValue.checked)
	{
		document.getElementById(textObj).value = txtObjValue + viewlevel;
	} // if
	else
	{
		document.getElementById(textObj).value = txtObjValue - viewlevel;
	} // else
} // function

function setValue_new(schecked, targettxt, tvalue)
{
	if (schecked)
	{
		document.getElementById(targettxt).value = parseInt(document.getElementById(targettxt).value) + tvalue;
	} // if
	else
	{
		document.getElementById(targettxt).value = parseInt(document.getElementById(targettxt).value) - tvalue;
	} // else
} // function
//-->
</script>
<tr><th colspan="2">{L_BBUS_SETTINGS_CAPTION}</th></tr>
<tr><td class="row2" colspan="2"><span class="gensmall">{L_BBUS_SETTINGS_EXPLAIN}</span></td></tr>

<tr>
	<td class="row1" valign="top"><strong>{L_BBUS_SETTING_VIEWLEVEL_CAPTION}</strong><br /></td>
	<td class="row2">
		<span class="gensmall">{L_BBUS_SETTING_VIEWLEVEL_EXPLAIN1}</span>
		<table class="forumline" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_ANONYMOUS_CAPTION}</strong></span></td>
			<td class="row3"><span class="gensmall">{L_BBUS_VIEWLEVEL_ANONYMOUS_EXPLAIN}</span></td>
			<td class="row3"><span class="gensmall">{BBUS_VIEWLEVEL_ANONYMOUS_FLAGVALUE}</span></td>

			<td class="row3"><input id="chkVLAnonymous" {BBUS_VIEWLEVEL_ANONYMOUS_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewlevel', {BBUS_VIEWLEVEL_ANONYMOUS_FLAGVALUE});" /></td>
		</tr>
		<tr>
			<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_SELF_CAPTION}</strong></span></td>
			<td class="row2"><span class="gensmall">{L_BBUS_VIEWLEVEL_SELF_EXPLAIN}</span></td>
			<td class="row2"><span class="gensmall">{BBUS_VIEWLEVEL_SELF_FLAGVALUE}</span></td>
			<td class="row2"><input id="chkVLSelf" {BBUS_VIEWLEVEL_SELF_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewlevel', {BBUS_VIEWLEVEL_SELF_FLAGVALUE});" /></td>
		</tr>
		<tr>
			<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_USERS_CAPTION}</strong></span></td>
			<td class="row3"><span class="gensmall">{L_BBUS_VIEWLEVEL_USERS_EXPLAIN}</span></td>
			<td class="row3"><span class="gensmall">{BBUS_VIEWLEVEL_USERS_FLAGVALUE}</span></td>
			<td class="row3"><input id="chkVLUsers" {BBUS_VIEWLEVEL_USERS_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewlevel', {BBUS_VIEWLEVEL_USERS_FLAGVALUE});" /></td>
		</tr>
		<tr>
			<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_MODERATORS_CAPTION}</strong></span></td>
			<td class="row2"><span class="gensmall">{L_BBUS_VIEWLEVEL_MODERATORS_EXPLAIN}</span></td>
			<td class="row2"><span class="gensmall">{BBUS_VIEWLEVEL_MODERATORS_FLAGVALUE}</span></td>
			<td class="row2"><input id="chkVLModerators" {BBUS_VIEWLEVEL_MODERATORS_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewlevel', {BBUS_VIEWLEVEL_MODERATORS_FLAGVALUE});" /></td>
		</tr>
		<tr>
			<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_ADMINS_CAPTION}</strong></span></td>
			<td class="row3"><span class="gensmall">{L_BBUS_VIEWLEVEL_ADMINS_EXPLAIN}</span></td>
			<td class="row3"><span class="gensmall">{BBUS_VIEWLEVEL_ADMINS_FLAGVALUE}</span></td>
			<td class="row3"><input id="chkVLAdmins" {BBUS_VIEWLEVEL_ADMINS_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewlevel', {BBUS_VIEWLEVEL_ADMINS_FLAGVALUE});" /></td>
		</tr>
		<tr>
			<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_SPECIALGRP_CAPTION}</strong></span></td>
			<td class="row2"><span class="gensmall">{L_BBUS_VIEWLEVEL_SPECIALGRP_EXPLAIN}</span></td>
			<td class="row2"><span class="gensmall">{BBUS_VIEWLEVEL_SPECIALGRP_FLAGVALUE}</span></td>
			<td class="row2"><input name="chkVLSpecialGrp" {BBUS_VIEWLEVEL_SPECIALGRP_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewlevel', {BBUS_VIEWLEVEL_SPECIALGRP_FLAGVALUE});" /></td>
		</tr>
		</table>
		<br />
		<input id="bb_usage_stats_viewlevel" name="bb_usage_stats_viewlevel" type="text" readonly="readonly" onFocus="javascript: this.select();" size="3" maxlength="10" value="{BBUS_SETTING_VIEWLEVEL_VALUE}" /><strong>{L_BBUS_SETTING_VIEWLEVEL_NOTE}</strong>
		<hr />
		<span class="gensmall">{L_BBUS_SETTING_VIEWLEVEL_EXPLAIN2}</span>
	</td>
</tr>

<tr>
	<td class="row1">
		<strong>{L_BBUS_SETTING_SPECIALGRP_CAPTION}</strong><br />
		<span class="gensmall">{L_BBUS_SETTING_SPECIALGRP_EXPLAIN}</span>
	</td>
	<td class="row2">
		{BBUS_SETTING_SPECIALGRP_SELECT}
	</td>
</tr>

<tr>
	<td class="row1" valign="top"><strong>{L_BBUS_SETTING_VIEWOPTIONS_CAPTION}</strong><br /></td>
	<td class="row2">
		<span class="gensmall">{L_BBUS_SETTING_VIEWOPTIONS_EXPLAIN1}</span>
		<table class="forumline" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_SHOW_ALL_FORUMS_CAPTION}</strong></span></td>
			<td class="row2"><span class="gensmall">{BBUS_VIEWOPTION_SHOW_ALL_FORUMS_FLAGVALUE}</span></td>
			<td class="row2"><input id="chkVOShowAllForums" {BBUS_VIEWOPTION_SHOW_ALL_FORUMS_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewoptions', {BBUS_VIEWOPTION_SHOW_ALL_FORUMS_FLAGVALUE});" /></td>
		</tr>
		<tr>
			<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_CAPTION}</strong></span></td>
			<td class="row3"><span class="gensmall">{BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_FLAGVALUE}</span></td>
			<td class="row3"><input id="chkVOPctUTUPColumnVisible" {BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewoptions', {BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_FLAGVALUE});" /></td>
		</tr>
		<tr>
			<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_CAPTION}</strong></span></td>
			<td class="row2"><span class="gensmall">{BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_FLAGVALUE}</span></td>
			<td class="row2"><input id="chkVOMiscSectionVisible" {BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewoptions', {BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_FLAGVALUE});" /></td>
		</tr>
		<tr>
			<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_CAPTION}</strong></span></td>
			<td class="row3"><span class="gensmall">{BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_FLAGVALUE}</span></td>
			<td class="row3"><input id="chkVOMiscTotPrunedPosts" {BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewoptions', {BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_FLAGVALUE});" /></td>
		</tr>
		<tr>
			<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_CAPTION}</strong></span></td>
			<td class="row2"><span class="gensmall">{BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_FLAGVALUE}</span></td>
			<td class="row2"><input id="chkVOViewerScalablePR" {BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewoptions', {BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_FLAGVALUE});" /></td>
		</tr>
		<tr>
			<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_CAPTION}</strong></span></td>
			<td class="row3"><span class="gensmall">{BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_FLAGVALUE}</span></td>
			<td class="row3"><input id="chkVOViewerScalableTR" {BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_CHKED} type="checkbox" onclick="javascript: setValue_new(this.checked, 'bb_usage_stats_viewoptions', {BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_FLAGVALUE});" /></td>
		</tr>
		</table>
		<br />
		<input id="bb_usage_stats_viewoptions" name="bb_usage_stats_viewoptions" type="text" readonly="readonly" onFocus="javascript: this.select();" size="3" maxlength="10" value="{BBUS_SETTING_VIEWOPTIONS_VALUE}" /><strong>{L_BBUS_SETTING_VIEWOPTIONS_NOTE}</strong>
		<hr />
		<span class="gensmall">{L_BBUS_SETTING_VIEWOPTIONS_EXPLAIN2}</span>
	</td>
</tr>

<tr>
	<td class="row1">
		<strong>{L_BBUS_DEFAULT_POST_RATE_SCALING_CAPTION}</strong><br />
		<span class="gensmall">{L_BBUS_DEFAULT_POST_RATE_SCALING_EXPLAIN}</span>
	</td>
	<td class="row2">{BBUS_DEFAULT_POST_RATE_SCALING_SELECT}</td>
</tr>
<tr>
	<td class="row1">
		<strong>{L_BBUS_DEFAULT_TOPIC_RATE_SCALING_CAPTION}</strong><br />
		<span class="gensmall">{L_BBUS_DEFAULT_TOPIC_RATE_SCALING_EXPLAIN}</span>
	</td>
	<td class="row2">{BBUS_DEFAULT_TOPIC_RATE_SCALING_SELECT}</td>
</tr>
<!-- **** BB USAGE STATISTICS MOD **** -->