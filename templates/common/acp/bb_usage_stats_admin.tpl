<!-- **** BB USAGE STATISTICS MOD **** -->
<script type="text/javascript"><!--

function setValue(textObj, chkObj, viewlevel)
{
	var chkObjValue = chkObj.checked
	var txtObjValue = parseInt(textObj.value)

	if (chkObjValue == true)
	{
		textObj.value = txtObjValue + viewlevel
	} // if
	else
	{
		textObj.value = txtObjValue - viewlevel
	} // else
} // function

function selectContents(fieldObj)
{
	fieldObj.select();
}
//--></script>
<tr><th colspan="2">{L_BBUS_SETTINGS_CAPTION}</th></tr>
<tr><td class="row2" colspan="2"><span class="gensmall">{L_BBUS_SETTINGS_EXPLAIN}</span></td></tr>

<tr>
	<td class="row1" valign="top"><strong>{L_BBUS_SETTING_VIEWLEVEL_CAPTION}</strong><br /></td>
	<td class="row2">
		<span class="gensmall">{L_BBUS_SETTING_VIEWLEVEL_EXPLAIN1}</span>
		<table cellpadding="2">
			<tr>
				<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_ANONYMOUS_CAPTION}</strong></span></td>
				<td class="row3"><span class="gensmall">{L_BBUS_VIEWLEVEL_ANONYMOUS_EXPLAIN}</span></td>
				<td class="row3"><span class="gensmall">{BBUS_VIEWLEVEL_ANONYMOUS_FLAGVALUE}</span></td>
				<td class="row3">
					<span class="gensmall">
						<input id="chkVLAnonymous" {BBUS_VIEWLEVEL_ANONYMOUS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLAnonymous,{BBUS_VIEWLEVEL_ANONYMOUS_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
			<tr>
				<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_SELF_CAPTION}</strong></span></td>
				<td class="row2"><span class="gensmall">{L_BBUS_VIEWLEVEL_SELF_EXPLAIN}</span></td>
				<td class="row2"><span class="gensmall">{BBUS_VIEWLEVEL_SELF_FLAGVALUE}</span></td>
				<td class="row2">
					<span class="gensmall">
						<input id="chkVLSelf" {BBUS_VIEWLEVEL_SELF_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLSelf,{BBUS_VIEWLEVEL_SELF_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
			<tr>
				<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_USERS_CAPTION}</strong></span></td>
				<td class="row3"><span class="gensmall">{L_BBUS_VIEWLEVEL_USERS_EXPLAIN}</span></td>
				<td class="row3"><span class="gensmall">{BBUS_VIEWLEVEL_USERS_FLAGVALUE}</span></td>
				<td class="row3">
					<span class="gensmall">
						<input id="chkVLUsers" {BBUS_VIEWLEVEL_USERS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLUsers,{BBUS_VIEWLEVEL_USERS_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
			<tr>
				<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_MODERATORS_CAPTION}</strong></span></td>
				<td class="row2"><span class="gensmall">{L_BBUS_VIEWLEVEL_MODERATORS_EXPLAIN}</span></td>
				<td class="row2"><span class="gensmall">{BBUS_VIEWLEVEL_MODERATORS_FLAGVALUE}</span></td>
				<td class="row2">
					<span class="gensmall">
						<input id="chkVLModerators" {BBUS_VIEWLEVEL_MODERATORS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLModerators,{BBUS_VIEWLEVEL_MODERATORS_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
			<tr>
				<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_ADMINS_CAPTION}</strong></span></td>
				<td class="row3"><span class="gensmall">{L_BBUS_VIEWLEVEL_ADMINS_EXPLAIN}</span></td>
				<td class="row3"><span class="gensmall">{BBUS_VIEWLEVEL_ADMINS_FLAGVALUE}</span></td>
				<td class="row3">
					<span class="gensmall">
						<input id="chkVLAdmins" {BBUS_VIEWLEVEL_ADMINS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLAdmins,{BBUS_VIEWLEVEL_ADMINS_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
			<tr>
				<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWLEVEL_SPECIALGRP_CAPTION}</strong></span></td>
				<td class="row2"><span class="gensmall">{L_BBUS_VIEWLEVEL_SPECIALGRP_EXPLAIN}</span></td>
				<td class="row2"><span class="gensmall">{BBUS_VIEWLEVEL_SPECIALGRP_FLAGVALUE}</span></td>
				<td class="row2">
					<span class="gensmall">
						<input name="chkVLSpecialGrp" {BBUS_VIEWLEVEL_SPECIALGRP_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewlevel, chkVLSpecialGrp,{BBUS_VIEWLEVEL_SPECIALGRP_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
		</table>
		<br />
		<input type="text" readonly onFocus="javascript: selectContents(this)"  size="3" maxlength="10" name="bb_usage_stats_viewlevel" value="{BBUS_SETTING_VIEWLEVEL_VALUE}"/><strong>
		{L_BBUS_SETTING_VIEWLEVEL_NOTE}</strong>
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
		<table cellpadding="2">
			<tr>
				<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_SHOW_ALL_FORUMS_CAPTION}</strong></span></td>
				<td class="row2"><span class="gensmall">{BBUS_VIEWOPTION_SHOW_ALL_FORUMS_FLAGVALUE}</span></td>
				<td class="row2">
					<span class="gensmall">
						<input id="chkVOShowAllForums" {BBUS_VIEWOPTION_SHOW_ALL_FORUMS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOShowAllForums,{BBUS_VIEWOPTION_SHOW_ALL_FORUMS_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
			<tr>
				<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_CAPTION}</strong></span></td>
				<td class="row3"><span class="gensmall">{BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_FLAGVALUE}</span></td>
				<td class="row3">
					<span class="gensmall">
						<input id="chkVOPctUTUPColumnVisible" {BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOPctUTUPColumnVisible,{BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
			<tr>
				<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_CAPTION}</strong></span></td>
				<td class="row2"><span class="gensmall">{BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_FLAGVALUE}</span></td>
				<td class="row2">
					<span class="gensmall">
						<input id="chkVOMiscSectionVisible" {BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOMiscSectionVisible,{BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
			<tr>
				<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_CAPTION}</strong></span></td>
				<td class="row3"><span class="gensmall">{BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_FLAGVALUE}</span></td>
				<td class="row3">
					<span class="gensmall">
						<input id="chkVOMiscTotPrunedPosts" {BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOMiscTotPrunedPosts,{BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
			<tr>
				<td class="row2"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_CAPTION}</strong></span></td>
				<td class="row2"><span class="gensmall">{BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_FLAGVALUE}</span></td>
				<td class="row2">
					<span class="gensmall">
						<input id="chkVOViewerScalablePR" {BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOViewerScalablePR,{BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
			<tr>
				<td class="row3"><span class="gensmall"><strong>{L_BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_CAPTION}</strong></span></td>
				<td class="row3"><span class="gensmall">{BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_FLAGVALUE}</span></td>
				<td class="row3">
					<span class="gensmall">
						<input id="chkVOViewerScalableTR" {BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_CHKED} type="checkbox" onclick="javascript: setValue(bb_usage_stats_viewoptions, chkVOViewerScalableTR,{BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_FLAGVALUE})"></input>
					</span>
				</td>
			</tr>
		</table>
		<br />
		<input type="text" readonly="readonly" onFocus="javascript: selectContents(this)" size="3" maxlength="10" name="bb_usage_stats_viewoptions" value="{BBUS_SETTING_VIEWOPTIONS_VALUE}"/><strong>
		{L_BBUS_SETTING_VIEWOPTIONS_NOTE}</strong>
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