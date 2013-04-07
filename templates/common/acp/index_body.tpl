<!-- IF S_ADMIN -->
<div class="forumline" style="margin: 0 auto; display: block; text-align: center; width: 500px; padding: 10px;">
<a href="{U_ACP_SETTINGS}" title="{L_ACP_SETTINGS}"><img src="{ACP_IMAGES_PATH}config.png" alt="{L_ACP_SETTINGS}" title="{L_ACP_SETTINGS}" /></a>&nbsp;
<a href="{U_ACP_CACHE}" title="{L_ACP_CACHE}"><img src="{ACP_IMAGES_PATH}cache.png" alt="{L_ACP_CACHE}" title="{L_ACP_CACHE}" /></a>&nbsp;
<a href="{U_ACP_FORUMS}" title="{L_ACP_FORUMS}"><img src="{ACP_IMAGES_PATH}forum.png" alt="{L_ACP_FORUMS}" title="{L_ACP_FORUMS}" /></a>&nbsp;
<a href="{U_ACP_USERS}" title="{L_ACP_USERS}"><img src="{ACP_IMAGES_PATH}users.png" alt="{L_ACP_USERS}" title="{L_ACP_USERS}" /></a>&nbsp;
<a href="{U_ACP_GROUPS}" title="{L_ACP_GROUPS}"><img src="{ACP_IMAGES_PATH}groups.png" alt="{L_ACP_GROUPS}" title="{L_ACP_GROUPS}" /></a>&nbsp;
<a href="{U_ACP_EMAIL}" title="{L_ACP_EMAIL}"><img src="{ACP_IMAGES_PATH}email.png" alt="{L_ACP_EMAIL}" title="{L_ACP_EMAIL}" /></a>
</div>
<!-- ENDIF -->

<br />
<div class="text_cont_center" style="width: 500px;"><div class="text_yellow_cont">
<b><span class="text_red">{L_PAYPAL_INFO}</span></b><br /><br />
<a href="http://www.icyphoenix.com/donate.php" target="_blank"><img src="../images/paypal.gif" alt="Support us" border="0" /></a>
</div></div>

<h1>{L_WELCOME}</h1>

<p>{L_ADMIN_INTRO}</p>

<!-- BEGIN switch_adminedit -->
<h1><span class="text_red">{L_LISTOFADMINEDIT}</span></h1>
<p>{L_LISTOFADMINEDITEXP}</p>
<!-- END switch_adminedit -->
<!-- BEGIN switch_firstadmin -->
<form method="post" action="{S_WORDS_ACTION}">
<!-- END switch_firstadmin -->
<!-- BEGIN switch_adminedit -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2" align="center" nowrap="nowrap">&nbsp;{L_LISTOFADMINEDITUSERS}:&nbsp;</th></tr>
<!-- END switch_adminedit -->
<!-- BEGIN adminedit -->
<tr>
	<td class="row1" width="50%" valign="top"><span class="genmed"><b>{adminedit.EDITCOUNT}. {L_LISTOFADMINTEXT}:</b></span></td>
	<td class="row1" width="50%"><span class="genmed"><a href="admin_users.php?mode=edit&amp;u={adminedit.EDITOK}" style="text-decoration:none;">{adminedit.EDITUSER}</a></span></td>
</tr>
<!-- END adminedit -->
<!-- BEGIN switch_firstadmin -->
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="deleteedituser" value="{L_DELETEMSG}" class="mainoption" /></td></tr>
<!-- END switch_firstadmin -->
<!-- BEGIN switch_adminedit -->
</table>
<!-- END switch_adminedit -->

<br />
<div id="site_stats_h" style="display: none;"><table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td class="row-header"><img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('site_stats','site_stats_h','site_stats');" alt="{L_SHOW}" /><span>{L_SITE_STATS}</span></td></tr></table></div>
<div id="site_stats">
<script type="text/javascript">
<!--
tmp = 'site_stats';
if(GetCookie(tmp) == '2')
{
	ShowHide('site_stats', 'site_stats_h', 'site_stats');
}
//-->
</script>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row-header" colspan="6"><img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('site_stats','site_stats_h','site_stats');" alt="{L_HIDE}" /><span>{L_SITE_STATS}</span></td></tr>
<tr>
	<th width="25%" nowrap="nowrap" colspan="3">{L_STATISTIC}</th>
	<th width="25%">{L_VALUE}</th>
	<th width="25%" nowrap="nowrap">{L_STATISTIC}</th>
	<th width="25%">{L_VALUE}</th>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" colspan="3">{L_IP_VERSION}:</td>
	<td class="row2"><b>{IP_VERSION}</b></td>
	<td class="row1" nowrap="nowrap">&nbsp;<!-- {L_PHPBB_VERSION}: --></td>
	<td class="row2">&nbsp;<!-- <b>{PHPBB_VERSION}</b> --></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" colspan="3">{L_PHP_VERSION}:</td>
	<td class="row2"><b>{PHP_VERSION}</b></td>
	<td class="row1" nowrap="nowrap">{L_MYSQL_VERSION}:</td>
	<td class="row2"><b>{MYSQL_VERSION}</b></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" colspan="3">{L_BOARD_STARTED}:</td>
	<td class="row2"><b>{START_DATE}</b></td>
	<td class="row1" nowrap="nowrap">{L_AVATAR_DIR_SIZE}:</td>
	<td class="row2"><b>{AVATAR_DIR_SIZE}</b></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" colspan="3">{L_DB_SIZE}:</td>
	<td class="row2"><b>{DB_SIZE}</b></td>
	<td class="row1" nowrap="nowrap">{L_GZIP_COMPRESSION}:</td>
	<td class="row2"><b>{GZIP_COMPRESSION}</b></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" colspan="3">{L_NUMBER_TOPICS}:</td>
	<td class="row2"><b>{NUMBER_OF_TOPICS}</b></td>
	<td class="row1" nowrap="nowrap">{L_TOPICS_PER_DAY}:</td>
	<td class="row2"><b>{TOPICS_PER_DAY}</b></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" colspan="3">{L_NUMBER_POSTS}:</td>
	<td class="row2"><b>{NUMBER_OF_POSTS}</b></td>
	<td class="row1" nowrap="nowrap">{L_POSTS_PER_DAY}:</td>
	<td class="row2"><b>{POSTS_PER_DAY}</b></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" colspan="3">{L_NUMBER_USERS}:</td>
	<td class="row2"><b>{NUMBER_OF_USERS}</b></td>
	<td class="row1" nowrap="nowrap">{L_USERS_PER_DAY}:</td>
	<td class="row2"><b>{USERS_PER_DAY}</b></td>
</tr>
<tr>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap" colspan="2">{L_NUMBER_ADMINISTRATORS}:</td>
	<td class="row2" colspan="3">{NUMBER_OF_ADMINISTRATORS}</td>
</tr>
<tr>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap">{L_NAME_ADMINISTRATORS}:</td>
	<td class="row2" colspan="3">{NAMES_OF_ADMINISTRATORS}&nbsp;</td>
</tr>
<tr>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap" colspan="2">{L_NUMBER_JUNIOR_ADMINISTRATORS}:</td>
	<td class="row2" colspan="3">{NUMBER_OF_JUNIOR_ADMINISTRATORS}</td>
</tr>
<tr>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap">{L_NAME_JUNIOR_ADMINISTRATORS}:</td>
	<td class="row2" colspan="3">{NAMES_OF_JUNIOR_ADMINISTRATORS}&nbsp;</td>
</tr>
<tr>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap" colspan="2">{L_NUMBER_MODERATORS}:</td>
	<td class="row2" colspan="3">{NUMBER_OF_MODERATORS}</td>
</tr>
<tr>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap">{L_NAME_MODERATORS}:</td>
	<td class="row2" colspan="3">{NAMES_OF_MODERATORS}&nbsp;</td>
</tr>
<tr>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap" colspan="2">{L_NUMBER_DEACTIVATED_USERS}:</td>
	<td class="row2" colspan="3">{NUMBER_OF_DEACTIVATED_USERS}</td>
</tr>
<tr>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row3" nowrap="nowrap" width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap">{L_NAME_DEACTIVATED_USERS}:</td>
	<td class="row2" colspan="3">{NAMES_OF_DEACTIVATED}&nbsp;</td>
</tr>
</table>
</div>

<!--
<br />
<h1>{L_VERSION_INFORMATION}</h1>
{VERSION_INFO}<br /><br />
-->

<br />
<div id="acp_online_h" style="display: none;"><table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td class="row-header"><img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('acp_online','acp_online_h','acp_online');" alt="{L_SHOW}" /><span>{L_WHO_IS_ONLINE}</span></td></tr></table></div>
<div id="acp_online">
<script type="text/javascript">
<!--
tmp = 'acp_online';
if(GetCookie(tmp) == '2')
{
	ShowHide('acp_online', 'acp_online_h', 'acp_online');
}
//-->
</script>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row-header" colspan="5"><img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('acp_online','acp_online_h','acp_online');" alt="{L_HIDE}" /><span>{L_WHO_IS_ONLINE}</span></td></tr>
<tr>
	<th width="20%" height="25">&nbsp;{L_USERNAME}&nbsp;</th>
	<th width="20%" height="25">&nbsp;{L_STARTED}&nbsp;</th>
	<th width="20%">&nbsp;{L_LAST_UPDATE}&nbsp;</th>
	<th width="20%">&nbsp;{L_FORUM_LOCATION}&nbsp;</th>
	<th width="20%" height="25">&nbsp;{L_IP_ADDRESS}&nbsp;</th>
</tr>
<!-- BEGIN reg_user_row -->
<tr>
	<td width="20%" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen">{reg_user_row.USERNAME}</span>&nbsp;</td>
	<td width="20%" align="center" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen">{reg_user_row.STARTED}</span>&nbsp;</td>
	<td width="20%" align="center" nowrap="nowrap" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen">{reg_user_row.LASTUPDATE}</span>&nbsp;</td>
	<td width="20%" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{reg_user_row.U_FORUM_LOCATION}" class="gen">{reg_user_row.FORUM_LOCATION}</a></span>&nbsp;</td>
	<td width="20%" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{reg_user_row.U_WHOIS_IP}" class="gen" target="_blank">{reg_user_row.IP_ADDRESS}</a></span>&nbsp;</td>
</tr>
<!-- END reg_user_row -->
<tr><td colspan="5" height="1" class="row3"><img src="../images/spacer.gif" width="1" height="1" alt="." /></td></tr>
<!-- BEGIN guest_user_row -->
<tr>
	<td width="20%" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.USERNAME}</span>&nbsp;</td>
	<td width="20%" align="center" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.STARTED}</span>&nbsp;</td>
	<td width="20%" align="center" nowrap="nowrap" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.LASTUPDATE}</span>&nbsp;</td>
	<td width="20%" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{guest_user_row.U_FORUM_LOCATION}" class="gen">{guest_user_row.FORUM_LOCATION}</a></span>&nbsp;</td>
	<td width="20%" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{guest_user_row.U_WHOIS_IP}" target="_blank">{guest_user_row.IP_ADDRESS}</a></span>&nbsp;</td>
</tr>
<!-- END guest_user_row -->
</table>
</div>

<!-- IF S_IS_FOUNDER -->
<br />
<div id="actions_log_h" style="display: none;"><table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td class="row-header"><img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('actions_log','actions_log_h','actions_log');" alt="{L_SHOW}" /><span><a href="{U_ADMIN_LOGS}">{L_LOGS_TITLE}</a></span></td></tr></table></div>
<div id="actions_log">
<script type="text/javascript">
<!--
tmp = 'actions_log';
if(GetCookie(tmp) == '2')
{
	ShowHide('actions_log', 'actions_log_h', 'actions_log');
}
//-->
</script>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row-header" colspan="6"><img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('actions_log','actions_log_h','actions_log');" alt="{L_HIDE}" /><span><a href="{U_ADMIN_LOGS}">{L_LOGS_TITLE}</a></span></td></tr>
<tr>
	<th align="center" nowrap="nowrap" width="100">{L_DATE}</th>
	<th align="center" nowrap="nowrap" width="250">{L_LINK}</th>
	<th align="center" nowrap="nowrap" width="100">{L_USERNAME}</th>
	<th align="center" nowrap="nowrap" width="100">{L_ACTION}</th>
	<th align="center" nowrap="nowrap" width="100">{L_LOGS_TARGET}</th>
	<th align="center" nowrap="nowrap">{L_DESCRIPTION}</th>
</tr>
<!-- BEGIN log_row -->
<tr>
	<td class="row1 row-center"><span class="gensmall">{log_row.LOG_TIME}</span></td>
	<td class="row1"><span class="gensmall">{log_row.LOG_PAGE}</span></td>
	<td class="row1 row-center"><span class="gensmall">{log_row.LOG_USERNAME}</span></td>
	<td class="row1"><span class="gensmall">{log_row.LOG_ACTION}</span></td>
	<td class="row1 row-center"><span class="gensmall">{log_row.LOG_TARGET}</span></td>
	<td class="row1">
	<!-- IF !(log_row.S_LOG_DESC_EXTRA) -->
	<span class="gensmall">{log_row.LOG_DESC}</span>
	<!-- ELSE -->
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td class="gensmall" style="cursor: pointer;" onclick="ShowHide('log_desc_{log_row.LOG_ID}','log_desc_{log_row.LOG_ID}_h','log_desc_{log_row.LOG_ID}');">
				<a href="javascript:void(0);" style="vertical-align:top;text-decoration:none;">{log_row.LOG_DESC}</a>
			</td>
		</tr>
		<tr>
			<td class="gensmall">
				<div id="log_desc_{log_row.LOG_ID}_h" class="nav-menu">
					<div class="nav-div" style="padding:2px;">{log_row.LOG_DESC_EXTRA}</div>
				</div>
				<div id="log_desc_{log_row.LOG_ID}" class="js-sh-box">
					<script type="text/javascript">
					<!--
					tmp = 'log_desc_{log_row.LOG_ID}';
					if(GetCookie(tmp) == '2')
					{
						ShowHide('log_desc_{log_row.LOG_ID}','log_desc_{log_row.LOG_ID}_h','log_desc_{log_row.LOG_ID}');
					}
					//-->
					</script>
				</div>
			</td>
		</tr>
	</table>
	<!-- ENDIF -->
	</td>
</tr>
<!-- END log_row -->
<tr><td class="cat" colspan="6" height="28">&nbsp;</td></tr>
</table>
</div>
<!-- ENDIF -->

<br />
{JR_ADMIN_INFO_TABLE}
<br />