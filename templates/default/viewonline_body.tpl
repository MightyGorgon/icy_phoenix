<!-- INCLUDE overall_header.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_WHO_IS_ONLINE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="35%">{L_USERNAME}</th>
	<th width="25%">{L_LAST_UPDATE}</th>
	<th width="40%">{L_FORUM_LOCATION}</th>
</tr>
<tr>
	<td class="row1" colspan="3">
		<span class="gen">{TOTAL_REGISTERED_USERS_ONLINE}</span>
	</td>
</tr>
<!-- BEGIN reg_user_row -->
<tr>
	<td width="35%" class="{reg_user_row.ROW_CLASS}h" onclick="window.location.href='{reg_user_row.U_USER_PROFILE}'">{reg_user_row.USERNAME}&nbsp;{reg_user_row.USER_OS_IMG}&nbsp;{reg_user_row.USER_BROWSER_IMG}</td>
	<td width="25%" nowrap="nowrap" class="{reg_user_row.ROW_CLASS} row-center"><span class="genmed">{reg_user_row.LASTUPDATE}</span></td>
	<td width="40%" class="{reg_user_row.ROW_CLASS}h" onclick="window.location.href='{reg_user_row.U_FORUM_LOCATION}'"><span class="gen"><a href="{reg_user_row.U_FORUM_LOCATION}">{reg_user_row.FORUM_LOCATION}</a></span></td>
</tr>
<!-- BEGIN switch_user_admin_or_mod -->
<tr>
	<td width="100%" class="{reg_user_row.ROW_CLASS}" colspan="3"><span class="gensmall">{L_IP}:&nbsp;<a href="{reg_user_row.U_HOSTNAME_LOOKUP}">{reg_user_row.IP}</a>&nbsp;&#187;&nbsp;<a href="{reg_user_row.U_WHOIS}" target="_blank">{L_WHOIS}</a>&nbsp;||&nbsp;{L_BROWSER}:&nbsp;{reg_user_row.USER_AGENT}</span></td>
</tr>
<!-- END switch_user_admin_or_mod -->
<!-- END reg_user_row -->
<tr><td class="spaceRow" colspan="3"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="row1" colspan="3"><span class="gen">{TOTAL_GUEST_USERS_ONLINE}</span></td></tr>
<!-- BEGIN guest_user_row -->
<tr>
	<td width="35%" class="{guest_user_row.ROW_CLASS}h"><span class="genmed">{guest_user_row.USERNAME}&nbsp;{guest_user_row.USER_OS_IMG}&nbsp;{guest_user_row.USER_BROWSER_IMG}</span></td>
	<td width="25%" nowrap="nowrap" class="{guest_user_row.ROW_CLASS} row-center"><span class="genmed">{guest_user_row.LASTUPDATE}</span></td>
	<td width="40%" class="{guest_user_row.ROW_CLASS}h" onclick="window.location.href='{guest_user_row.U_FORUM_LOCATION}'"><span class="gen"><a href="{guest_user_row.U_FORUM_LOCATION}">{guest_user_row.FORUM_LOCATION}</a></span></td>
</tr>
<!-- BEGIN switch_user_admin_or_mod -->
<tr>
	<td width="100%" class="{guest_user_row.ROW_CLASS}" colspan="3"><span class="gensmall">{L_IP}:&nbsp;<a href="{guest_user_row.U_HOSTNAME_LOOKUP}">{guest_user_row.IP}</a>&nbsp;&#187;&nbsp;<a href="{guest_user_row.U_WHOIS}" target="_blank">{L_WHOIS}</a>&nbsp;||&nbsp;{L_BROWSER}:&nbsp;{guest_user_row.USER_AGENT}</span></td>
</tr>
<!-- END switch_user_admin_or_mod -->
<!-- END guest_user_row -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- BEGIN switch_show_recent -->
{IMG_TBL}<table class="forumline" width="100%" cellspacing="0">
<tr>
	<td class="row-header" width="70%"><span class="genmed"><b>{L_RECENT_TOPICS}</b></span></td>
	<td class="row-header" width="30%"><span class="genmed"><b>{L_LAST_SEEN}</b></span></td>
</tr>
<tr>
	<td class="no-padding" valign="top">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<!-- BEGIN recent_topic_row -->
			<tr>
				<td class="row1" width="100%">
					<span class="genmed">
						<a href="{switch_show_recent.recent_topic_row.U_FORUM}" title="{switch_show_recent.recent_topic_row.L_FORUM}">{switch_show_recent.recent_topic_row.L_FORUM}</a><b>{NAV_SEP}</b>
						<a href="{switch_show_recent.recent_topic_row.U_TITLE}" title="{switch_show_recent.recent_topic_row.L_TITLE}" >{switch_show_recent.recent_topic_row.L_TITLE}</a><b>{NAV_SEP}</b>
						{switch_show_recent.recent_topic_row.S_POSTTIME}<b>{NAV_SEP}</b>
						{switch_show_recent.recent_topic_row.U_POSTER}
					</span>
				</td>
			</tr>
			<!-- END recent_topic_row -->
		</table>
	</td>
	<td class="no-padding" valign="top">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<!-- BEGIN last_seen_row -->
			<tr><td class="row1" width="100%"><span class="genmed">{switch_show_recent.last_seen_row.L_LSEEN_TIME}<b>{NAV_SEP}</b>{switch_show_recent.last_seen_row.U_LSEEN_LINK}</span></td></tr>
			<!-- END last_seen_row -->
		</table>
	</td>
</tr>
</table>{IMG_TBR}
<br />
<!-- END switch_show_recent -->
<!-- IF S_SHOUTBOX -->
<div id="shoutbox_h" style="display: none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('shoutbox','shoutbox_h','shoutbox');" alt="{L_SHOW}" /><a href="{U_SHOUTBOX_MAX}" class="forumlink">{L_SHOUTBOX}</a>{IMG_THR_ALT}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="shoutbox">
<script type="text/javascript">
<!--
tmp = 'shoutbox';
if(GetCookie(tmp) == '2')
{
	ShowHide('shoutbox', 'shoutbox_h', 'shoutbox');
}
//-->
</script>
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('shoutbox','shoutbox_h','shoutbox');" alt="{L_HIDE}" /><a href="{U_SHOUTBOX_MAX}" class="forumlink">{L_SHOUTBOX}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td><iframe src="{U_SHOUTBOX}" scrolling="no" width="100%" height="190" frameborder="0" marginheight="0" marginwidth="0"></iframe></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<!-- ENDIF -->
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top"><span class="gensmall">{L_ONLINE_EXPLAIN}</span></td>
	<td align="right" valign="top"><span class="gensmall">{S_TIMEZONE}</span></td>
</tr>
</table>

<div align="right">{JUMPBOX}</div>

<!-- INCLUDE overall_footer.tpl -->