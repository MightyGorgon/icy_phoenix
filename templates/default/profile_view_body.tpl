{IMG_THL}{IMG_THC}<span class="forumlink">{USERNAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td height="2"></td>
	<td>
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td height="2"></td></tr>
			<tr>
				<td width="50%" valign="top" class="forumline">
					<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><th colspan="2"><span class="genmed"><b>{L_INVISION_A_STATS}</b></span></th></tr>
						<tr>
							<td width="33%" class="row2" valign="top"><b><span class="genmed">{L_INVISION_POSTS}</span></b></td>
							<td width="64%" class="row1"><span class="genmed"><b>{POSTS}</b>&nbsp;{INVISION_POST_PERCENT_STATS}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_INVISION_PPD_STATS}</span></b></td>
							<td class="row1"><span class="genmed">{INVISION_POST_DAY_STATS}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_JOINED}</span></b></td>
							<td class="row1"><span class="genmed">{JOINED}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_INVISION_MOST_ACTIVE}</span></b></td>
							<td class="row1"><span class="genmed"><a href="{INVISION_MOST_ACTIVE_FORUM_URL}">{INVISION_MOST_ACTIVE_FORUM_NAME}</a><br />{L_INVISION_MOST_ACTIVE_POSTS}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_RECENT_USER_ACTIVITY}</span></b></td>
							<td class="row1">
								<span class="genmed">[ <a href="{U_USER_RECENT_TOPICS}">{L_USER_TOPICS_STARTED}</a> ]</span><br />
								<!-- IF S_ADMIN_MOD -->
								<span class="genmed">[ <a href="{U_USER_RECENT_POSTS}">{L_USER_POSTS}</a> ]</span><br />
								<span class="genmed">[ <a href="{U_USER_RECENT_TOPICS_VIEW}">{L_USER_TOPICS_VIEWS}</a> ]</span>
								<!-- ENDIF -->
							</td>
						</tr>
						<!-- BEGIN show_thanks_profile -->
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_THANKS_RECEIVED}</span></b></td>
							<td class="row1"><span class="genmed">{THANKS_RECEIVED}</span></td>
						</tr>
						<!-- END show_thanks_profile -->
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_LOGON}</span></b></td>
							<td class="row1"><span class="genmed">{LAST_LOGON}</span></td>
						</tr>
						<!-- IF S_ADMIN_MOD -->
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_TOTAL_ONLINE_TIME}</span></b></td>
							<td class="row1"><span class="genmed">{TOTAL_ONLINE_TIME}</span></td>
						</tr>
						<!-- ENDIF -->
						<!-- BEGIN switch_upload_limits -->
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_UPLOAD_QUOTA}</span></b></td>
							<td align="left" class="row2">
								<table width="190" cellspacing="0" class="forumline">
									<tr>
										<td colspan="3" width="190" class="row2" nowrap="nowrap"><img src="{BAR_GRAPHIC_LEFT}" width="4" height="12" alt="" /><img src="{BAR_GRAPHIC_BODY}" width="{UPLOAD_LIMIT_IMG_WIDTH}" height="12" alt="{INBOX_LIMIT_PERCENT}" /><img src="{BAR_GRAPHIC_RIGHT}" width="4" height="12" alt="" /></td>
									</tr>
									<tr>
										<td width="33%" class="row3"><span class="gensmall"><span class="text_green">0%</span></span></td>
										<td width="34%" class="row3 row-center"><span class="gensmall"><span class="text_blue">50%</span></span></td>
										<td width="33%" class="row3 row-right"><span class="gensmall"><span class="text_red">100%</span></span></td>
									</tr>
								</table>
								<span class="genmed">[{UPLOADED} / {QUOTA} / {PERCENT_FULL}]</span><br />
								<span class="gen"><a href="{U_UACP}" class="genmed">{L_UACP}</a></span>
							</td>
						</tr>
						<!-- END switch_upload_limits -->
					</table>
				</td>
				<td width="2"><img src="{SPACER}" width="2" alt="" /></td>
				<td width="50%" valign="top" class="forumline">
					<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><th colspan="2"><span class="genmed"><b>{L_INVISION_COMMUNICATE}</b></span></th></tr>
						<tr>
							<td width="33%" class="row2" valign="top"><b><span class="genmed">{L_EMAIL_ADDRESS}</span></b></td>
							<td width="64%" class="row1 post-buttons"><span class="genmed">{EMAIL_IMG}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_AIM}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed">{AIM_IMG}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_ICQ_NUMBER}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed">{ICQ_IMG}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_MESSENGER}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed">{MSN_IMG}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_YAHOO}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed">{YIM_IMG}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_SKYPE}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed">{SKYPE_IMG}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_PM}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed">{PM_IMG}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_PHONE}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed">{PHONE}</span></td>
						</tr>
						<!-- BEGIN custom_contact -->
						<tr>{custom_contact.CONTACT}</tr>
						<!-- END custom_contact -->
						<!-- IF S_ADMIN_MOD -->
						<tr><th colspan="2" align="center">{L_MODERATOR_IP_INFORMATION}:</th></tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_EMAIL_ADDRESS}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed"><a href="mailto:{USER_EMAIL_ADDRESS}">{USER_EMAIL_ADDRESS}</a></span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_REGISTERED_IP_ADDRESS}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed">{U_USER_IP_ADDRESS}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_REGISTERED_HOSTNAME}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed">{USER_REGISTERED_HOSTNAME}</span></td>
						</tr>
						<!-- ENDIF -->
					</table>
				</td>
			</tr>
			<tr><td width="2"><img src="{SPACER}" width="2" alt="" /></td></tr>
			<tr>
				<td width="50%" valign="top" class="forumline">
					<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><th colspan="2"><span class="genmed"><b>{L_INVISION_INFO}</b></span></th></tr>
						<tr>
							<td width="33%" class="row2" valign="top"><b><span class="genmed">{L_ONLINE_STATUS}</span></b></td>
							<td width="64%" class="row1"><span class="genmed">{ONLINE_STATUS_IMG}&nbsp;{USER_OS_IMG}&nbsp;{USER_BROWSER_IMG}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_INVISION_WEBSITE}</span></b></td>
							<td class="row1"><span class="genmed">{WWW}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_BIRTHDAY}</span></b></td>
							<td class="row1"><span class="genmed">{BIRTHDAY}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_GENDER}</span></b></td>
							<td class="row1"><span class="genmed">{GENDER}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_LOCATION}</span></b></td>
							<td class="row1"><span class="genmed">{LOCATION}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_INTERESTS}</span></b></td>
							<td class="row1"><span class="genmed">{INTERESTS}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_OCCUPATION}</span></b></td>
							<td class="row1"><span class="genmed">{OCCUPATION}</span></td>
						</tr>
						{CASH}
						<!-- BEGIN trophy -->
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{trophy.TROPHY_TITLE}:</span></b></td>
							<td class="row1"><span class="genmed">{trophy.PROFILE_TROPHY}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{PROFILE_TITLE}</span></b></td>
							<td class="row1"><span class="genmed">{PROFILE_TIME}</span></td>
						</tr>
						<!-- END trophy -->
						<!-- BEGIN custom_about -->
						<tr>{custom_about.ABOUT}</tr>
						<!-- END custom_about -->
						<tr>
							<td class="row2" valign="top"><b><span class="genmed">{L_Profile_viewed}</span></b></td>
							<td class="row1 post-buttons"><span class="genmed">{U_VISITS}</span></td>
						</tr>
						<!-- BEGIN switch_groups_on -->
						<tr>
							<td width="33%" class="row2" valign="top"><b><span class="genmed">{L_INVISION_MEMBER_GROUP}</span></b></td>
							<td width="64%" class="row1">
								<span class="genmed">
						<!-- END switch_groups_on -->
								<!-- BEGIN groups -->
									<a href="{groups.U_GROUP_NAME}" class="gentbl"><b>{groups.L_GROUP_NAME}</b></a>:&nbsp;{groups.L_GROUP_DESC}<br />
								<!-- END groups -->
						<!-- BEGIN switch_groups_on -->
								</span>
							</td>
						</tr>
						<!-- END switch_groups_on -->
					</table>
				</td>
				<td width="2"><img src="{SPACER}" width="7" alt="" /></td>
				<td width="50%" valign="top" class="forumline">
					<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><th colspan="2"><span class="genmed"><b>{L_INVISION_P_DETAILS}</b></span></th></tr>
						<tr>
							<td class="row2" valign="top" width="30%"><b><span class="genmed">{L_INVISION_MEMBER_TITLE} &amp; {L_AVATAR}</span></b></td>
							<td class="row1 row-center"><span class="genmed">{USER_RANK_01}{USER_RANK_01_IMG}{USER_RANK_02}{USER_RANK_02_IMG}{USER_RANK_03}{USER_RANK_03_IMG}{USER_RANK_04}{USER_RANK_04_IMG}{USER_RANK_05}{USER_RANK_05_IMG}{INVISION_AVATAR_IMG}</span></td>
						</tr>
						<tr>
							<td class="row2" valign="top" width="30%"><b><span class="genmed">{L_INVISION_SIGNATURE}</span></b></td>
							<td class="row1"><span class="genmed">{INVISION_USER_SIG}</span></td>
						</tr>
						<!-- IF FEEDBACKS -->
						<tr>
							<td class="row2" valign="top" width="30%"><b><span class="genmed">{L_FEEDBACKS_RECEIVED}</span></b></td>
							<td class="row1">{FEEDBACKS}</td>
						</tr>
						<!-- ENDIF -->
					</table>
				</td>
			</tr>
		</table>
	</td>
	<td height="1">&nbsp;</td>
</tr>
<tr><td height="2">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_EXTRA_PROFILE_INFO}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1"><div class="post-text">{SELFDES}</div></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- BEGIN recent_pics_block -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RECENT_PUBLIC_PICS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN no_pics -->
<tr><td class="row1 row-center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN recent_pics -->
<tr>
	<!-- BEGIN recent_col -->
	<td class="row1 row-center" width="{S_COL_WIDTH}">
		<a href="{recent_pics_block.recent_pics.recent_col.U_PIC}" {}><img src="{recent_pics_block.recent_pics.recent_col.THUMBNAIL}" alt="{recent_pics_block.recent_pics.recent_col.DESC}" title="{recent_pics_block.recent_pics.recent_col.DESC}" vspace="10" /></a>
	</td>
	<!-- END recent_col -->
</tr>
<tr>
	<!-- BEGIN recent_detail -->
	<td class="row1 row-center">
		<span class="gensmall">
			{L_PIC_TITLE}: {recent_pics_block.recent_pics.recent_detail.TITLE}<br />
			{L_POSTER}: {recent_pics_block.recent_pics.recent_detail.POSTER}<br />
			{L_POSTED}: {recent_pics_block.recent_pics.recent_detail.TIME}<br />
			{L_VIEW}: {recent_pics_block.recent_pics.recent_detail.VIEW}<br />
		</span>
	</td>
	<!-- END recent_detail -->
</tr>
<!-- END recent_pics -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END recent_pics_block -->

{BB_USAGE_STATS_TEMPLATE}
<!-- IF S_ADMIN -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_QUICK_ADMIN_OPTIONS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" colspan="2" ><span class="genmed"><a href="{U_ADMIN_EDIT_PROFILE}">{L_ADMIN_EDIT_PROFILE}</a> || <a href="{U_ADMIN_EDIT_PERMISSIONS}">{L_ADMIN_EDIT_PERMISSIONS}</a> || {L_USER_ACTIVE_INACTIVE} || {L_BANNED_USERNAME} || {L_BANNED_EMAIL}</span></td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- ENDIF -->
<!-- BEGIN switch_user_admin_or_mod -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_OTHER_REGISTERED_IPS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_USERNAME}</th>
	<th>{L_REGISTERED_HOSTNAME}</th>
	<th>{L_TIME}</th>
</tr>
<!-- BEGIN switch_other_user_ips -->
<!-- BEGIN OTHER_REGISTERED_IPS -->
<tr>
	<td class="row1"><span class="genmed"><a href="{switch_user_admin_or_mod.switch_other_user_ips.OTHER_REGISTERED_IPS.U_PROFILE}">{switch_user_admin_or_mod.switch_other_user_ips.OTHER_REGISTERED_IPS.USER_NAME}</a></span></td>
	<td class="row1"><span class="genmed">{switch_user_admin_or_mod.switch_other_user_ips.OTHER_REGISTERED_IPS.USER_HOSTNAME}</span></td>
	<td class="row1"><span class="genmed">{switch_user_admin_or_mod.switch_other_user_ips.OTHER_REGISTERED_IPS.TIME}</span></td>
</tr>
<!-- END OTHER_REGISTERED_IPS -->
<!-- END switch_other_user_ips -->
<!-- BEGIN switch_no_other_registered_ips -->
<tr><td class="row1 row-center" colspan="3"><span class="genmed">{L_NO_OTHER_REGISTERED_IPS}</span></td></tr>
<!-- END switch_no_other_registered_ips -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top"><span class="gen">{USERS_PAGE_NUMBER}&nbsp;</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="pagination">&nbsp;{USERS_PAGINATION}</span><br /></td>
</tr>
</table>

<br />

{IMG_THL}{IMG_THC}<span class="forumlink">{L_OTHER_IPS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN switch_other_posted_ips -->
<!-- BEGIN ALL_IPS_POSTED_FROM -->
<tr>
	<td class="row1"><span class="genmed"><a href="{switch_user_admin_or_mod.switch_other_posted_ips.ALL_IPS_POSTED_FROM.U_POSTER_IP}" target="_blank">{switch_user_admin_or_mod.switch_other_posted_ips.ALL_IPS_POSTED_FROM.POSTER_IP}</a> [ <a href="{switch_user_admin_or_mod.switch_other_posted_ips.ALL_IPS_POSTED_FROM.U_POSTS_LINK}">{switch_user_admin_or_mod.switch_other_posted_ips.ALL_IPS_POSTED_FROM.POSTS}</a> ] </span></td>
</tr>
<!-- END ALL_IPS_POSTED_FROM -->
<!-- END switch_other_posted_ips -->
<!-- BEGIN switch_no_other_posted_ips -->
<tr>
	<td class="row1 row-center"><span class="genmed">{L_NO_OTHER_POSTED_IPS}</span></td>
</tr>
<!-- END switch_no_other_posted_ips -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top"><span class="gen">{IPS_PAGE_NUMBER}&nbsp;</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="pagination">&nbsp;{IPS_PAGINATION}</span></td>
</tr>
</table>

<br />

<!-- IF S_LOGINS_HISTORY -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_LOGINS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_IP}</th>
	<th>{L_BROWSER}</th>
	<th>{L_TIME}</th>
</tr>
<!-- BEGIN USER_LOGINS -->
<tr>
	<td class="row1"><span class="genmed"><a href="{switch_user_admin_or_mod.USER_LOGINS.U_IP}" target="_blank">{switch_user_admin_or_mod.USER_LOGINS.IP}</a></span></td>
	<td class="row1"><span class="genmed">{switch_user_admin_or_mod.USER_LOGINS.USER_AGENT}</span></td>
	<td class="row1"><span class="genmed">{switch_user_admin_or_mod.USER_LOGINS.LOGIN_TIME}</span></td>
</tr>
<!-- END USER_LOGINS -->
<!-- BEGIN switch_no_logins -->
<tr><td class="row1 row-center" colspan="3"><span class="genmed">{L_NO_LOGINS}</span></td></tr>
<!-- END switch_no_logins -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr>
	<td align="left" valign="top"><span class="gen">{LOGINS_PAGE_NUMBER}&nbsp;</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="pagination">&nbsp;{LOGINS_PAGINATION}</span><br /><span class="gensmall">{S_TIMEZONE}</span></td>
</tr>
</table>
<!-- ENDIF -->
<!-- END switch_user_admin_or_mod -->

<!-- BEGIN profile_char -->
{profile_char.CHAR_PROFILE}
<!-- END profile_char -->

<table class="empty-table" width="100%" align="center" cellspacing="0"><tr><td align="right" class="nav"><br />{JUMPBOX}</td></tr></table>