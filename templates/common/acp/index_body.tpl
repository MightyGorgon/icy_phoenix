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
	<td class="row1" width="50%" valign="top"><span class="genmed"><b>{adminedit.editcount}. {L_LISTOFADMINTEXT}:</b></span></td>
	<td class="row1" width="50%"><span class="genmed"><a href="admin_users.php?mode=edit&u={adminedit.editok}">{adminedit.edituser}</a></span></td>
</tr>
<!-- END adminedit -->
<!-- BEGIN switch_firstadmin -->
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="deleteedituser" value="{L_DELETEMSG}" class="mainoption" /></td></tr>
<!-- END switch_firstadmin -->
<!-- BEGIN switch_adminedit -->
</table>
<!-- END switch_adminedit -->

<br />
<h1>{L_FORUM_STATS}</h1>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th width="25%" nowrap="nowrap" colspan="3">{L_STATISTIC}</th>
	<th width="25%">{L_VALUE}</th>
	<th width="25%" nowrap="nowrap">{L_STATISTIC}</th>
	<th width="25%">{L_VALUE}</th>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" colspan="3">{L_IP_VERSION}:</td>
	<td class="row2"><b>{IP_VERSION}</b></td>
	<td class="row1" nowrap="nowrap">{L_PHPBB_VERSION}:</td>
	<td class="row2"><b>{PHPBB_VERSION}</b></td>
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
	<td class="row1" nowrap="nowrap" colspan="3">{L_NUMBER_POSTS}:</td>
	<td class="row2"><b>{NUMBER_OF_POSTS}</b></td>
	<td class="row1" nowrap="nowrap">{L_POSTS_PER_DAY}:</td>
	<td class="row2"><b>{POSTS_PER_DAY}</b></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" colspan="3">{L_NUMBER_TOPICS}:</td>
	<td class="row2"><b>{NUMBER_OF_TOPICS}</b></td>
	<td class="row1" nowrap="nowrap">{L_TOPICS_PER_DAY}:</td>
	<td class="row2"><b>{TOPICS_PER_DAY}</b></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" colspan="3">{L_NUMBER_USERS}:</td>
	<td class="row2"><b>{NUMBER_OF_USERS}</b></td>
	<td class="row1" nowrap="nowrap">{L_USERS_PER_DAY}:</td>
	<td class="row2"><b>{USERS_PER_DAY}</b></td>
</tr>
<tr>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap" colspan="2">{L_NUMBER_DEACTIVATED_USERS}:</td>
	<td class="row2" colspan="3">{NUMBER_OF_DEACTIVATED_USERS}</td>
</tr>
<tr>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap">{L_NAME_DEACTIVATED_USERS}:</td>
	<td class="row2" colspan="3">{NAMES_OF_DEACTIVATED}&nbsp;</td>
</tr>
<tr>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap" colspan="2">{L_NUMBER_MODERATORS}:</td>
	<td class="row2" colspan="3">{NUMBER_OF_MODERATORS}</td>
</tr>
<tr>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap">{L_NAME_MODERATORS}:</td>
	<td class="row2" colspan="3">{NAMES_OF_MODERATORS}&nbsp;</td>
</tr>
<tr>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap" colspan="2">{L_NUMBER_JUNIOR_ADMINISTRATORS}:</td>
	<td class="row2" colspan="3">{NUMBER_OF_JUNIOR_ADMINISTRATORS}</td>
</tr>
<tr>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap">{L_NAME_JUNIOR_ADMINISTRATORS}:</td>
	<td class="row2" colspan="3">{NAMES_OF_JUNIOR_ADMINISTRATORS}&nbsp;</td>
</tr>
<tr>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap" colspan="2">{L_NUMBER_ADMINISTRATORS}:</td>
	<td class="row2" colspan="3">{NUMBER_OF_ADMINISTRATORS}</td>
</tr>
<tr>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row3" nowrap width="10">&nbsp;</td>
	<td class="row1" nowrap="nowrap">{L_NAME_ADMINISTRATORS}:</td>
	<td class="row2" colspan="3">{NAMES_OF_ADMINISTRATORS}&nbsp;</td>
</tr>
</table>

<br />
<h1>{L_VERSION_INFORMATION}</h1>
{VERSION_INFO}<br /><br />

<br />
<h1>{L_WHO_IS_ONLINE}</h1>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th width="20%" height="25">&nbsp;{L_USERNAME}&nbsp;</th>
	<th width="20%" height="25">&nbsp;{L_STARTED}&nbsp;</th>
	<th width="20%">&nbsp;{L_LAST_UPDATE}&nbsp;</th>
	<th width="20%">&nbsp;{L_FORUM_LOCATION}&nbsp;</th>
	<th width="20%" height="25">&nbsp;{L_IP_ADDRESS}&nbsp;</th>
</tr>
<!-- BEGIN reg_user_row -->
<tr>
	<td width="20%" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{reg_user_row.U_USER_PROFILE}" class="gen">{reg_user_row.USERNAME}</a></span>&nbsp;</td>
	<td width="20%" align="center" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen">{reg_user_row.STARTED}</span>&nbsp;</td>
	<td width="20%" align="center" nowrap="nowrap" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen">{reg_user_row.LASTUPDATE}</span>&nbsp;</td>
	<td width="20%" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{reg_user_row.U_FORUM_LOCATION}" class="gen">{reg_user_row.FORUM_LOCATION}</a></span>&nbsp;</td>
	<td width="20%" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{reg_user_row.U_WHOIS_IP}" class="gen" target="_phpbbwhois">{reg_user_row.IP_ADDRESS}</a></span>&nbsp;</td>
</tr>
<!-- END reg_user_row -->
<tr>
	<td colspan="5" height="1" class="row3"><img src="../images/spacer.gif" width="1" height="1" alt="."></td>
</tr>
<!-- BEGIN guest_user_row -->
<tr>
	<td width="20%" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.USERNAME}</span>&nbsp;</td>
	<td width="20%" align="center" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.STARTED}</span>&nbsp;</td>
	<td width="20%" align="center" nowrap="nowrap" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.LASTUPDATE}</span>&nbsp;</td>
	<td width="20%" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{guest_user_row.U_FORUM_LOCATION}" class="gen">{guest_user_row.FORUM_LOCATION}</a></span>&nbsp;</td>
	<td width="20%" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{guest_user_row.U_WHOIS_IP}" target="_phpbbwhois">{guest_user_row.IP_ADDRESS}</a></span>&nbsp;</td>
</tr>
<!-- END guest_user_row -->
</table>

<br />
{JR_ADMIN_INFO_TABLE}
<br />