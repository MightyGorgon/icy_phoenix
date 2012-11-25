<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td id="var_width" width="160" valign="top" align="left" style="padding-right: 7px;">
		<div id="quick_links_profile2" style="padding-top: 5px; display: none; margin-left: 0px; text-align: left; position: relative; float: left;"><a href="#" onclick="ShowHide('quick_links_profile', 'quick_links_profile2', 'quick_links_profile'); setWidth('var_width', 160); setWidth('full_width_cpl', 'auto'); return false;" title="{L_SHOW} {L_CPL_NAV}"><img src="{IMG_NAV_MENU_APPLICATION}" alt="{L_SHOW} {L_CPL_NAV}" /></a></div>
		<div id="quick_links_profile">
		<script type="text/javascript">
		<!--
		tmp = 'quick_links_profile';
		if(GetCookie(tmp) == '2')
		{
			ShowHide('quick_links_profile', 'quick_links_profile2', 'quick_links_profile');
			setWidth('var_width', 16);
			//setWidth('full_width_cpl', '100%');
		}
		//-->
		</script>
		{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('quick_links_profile', 'quick_links_profile2', 'quick_links_profile');setWidth('var_width',16);setWidth('full_width_cpl', '100%');" alt="{L_SHOW}" />
		<span class="forumlink">{L_CPL_NAV}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<th style="cursor: pointer;" align="left" onclick="ShowHide('personal_profile', 'personal_profile2', 'personal_profile');">
				<img src="{IMG_NAV_MENU_USERS}" alt="{L_CPL_PERSONAL_PROFILE}" title="{L_CPL_PERSONAL_PROFILE}" />&nbsp;
				<a href="#" onclick="return false;" title="{L_CPL_PERSONAL_PROFILE}" class="nav-menu-link"><b>{L_CPL_PERSONAL_PROFILE}</b></a>
			</th>
		</tr>
		<tr>
			<td class="row5">
				<div id="personal_profile2" class="nav-menu">
					<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_REGISTRATION_INFO}">{L_CPL_REG_INFO}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_DELETE_ACCOUNT}">{L_CPL_DELETE_ACCOUNT}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_PROFILE_INFO}">{L_CPL_PROFILE_INFO}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_ZEBRA}">{L_CPL_ZEBRA}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_PROFILE_VIEWED}">{L_CPL_PROFILE_VIEWED}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_AVATAR_PANEL}">{L_CPL_AVATAR_PANEL}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_SIGNATURE}">{L_CPL_SIG_EDIT}</a></td>
					</tr>
					</table>
				</div>
				<div id="personal_profile" class="js-sh-box">
					<script type="text/javascript">
					<!--
					tmp = 'personal_profile';
					if(GetCookie(tmp) == '2')
					{
						ShowHide('personal_profile', 'personal_profile2', 'personal_profile');
					}
					//-->
					</script>
				</div>
			</td>
		</tr>
		<tr>
			<th style="cursor: pointer;" align="left" onclick="ShowHide('settings_options', 'settings_options2', 'settings_options');">
				<img src="{IMG_NAV_MENU_WSETTINGS}" alt="{L_CPL_SETTINGS_OPTIONS}" title="{L_CPL_SETTINGS_OPTIONS}" />&nbsp;
				<a href="#" onclick="return false;" title="{L_CPL_SETTINGS_OPTIONS}" class="nav-menu-link"><b>{L_CPL_SETTINGS_OPTIONS}</b></a>
			</th>
		</tr>
		<tr>
			<td class="row5">
				<div id="settings_options2" class="nav-menu">
					<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_PREFERENCES}">{L_CPL_PREFERENCES}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_BOARD_SETTINGS}">{L_CPL_BOARD_SETTINGS}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_CALENDAR_SETTINGS}">{L_Calendar_settings}</a></td>
					</tr>
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_SUBFORUM_SETTINGS}">{L_Hierarchy_setting}</a></td>
					</tr>
					<!-- BEGIN login_sec_link -->
					<tr>
						<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_LOGIN_SEC}">{L_LOGIN_SEC}</a></td>
					</tr>
					<!-- END login_sec_link -->
					</table>
				</div>
				<div id="settings_options" class="js-sh-box">
					<script type="text/javascript">
					<!--
					tmp = 'settings_options';
					if(GetCookie(tmp) == '2')
					{
						ShowHide('settings_options', 'settings_options2', 'settings_options');
					}
					//-->
					</script>
				</div>
			</td>
		</tr>
		<tr>
			<th style="cursor: pointer;" align="left" onclick="ShowHide('private_messages', 'private_messages2', 'private_messages');">
				<img src="{IMG_NAV_MENU_PM}" alt="{L_CPL_PRIVATE_MESSAGES}" title="{L_CPL_PRIVATE_MESSAGES}" />&nbsp;
				<a href="#" onclick="return false;" title="{L_CPL_PRIVATE_MESSAGES}" class="nav-menu-link"><b>{L_CPL_PRIVATE_MESSAGES}</b></a>
			</th>
		</tr>
		<tr>
			<td class="row5">
				<div id="private_messages2" class="nav-menu">
					<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_NEWMSG}">{L_CPL_NEWMSG}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_PRIVATEMSGS}">{PRIVATE_MESSAGE_INFO}</a></td>
					</tr>
					<!--
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_INBOX}">{L_CPL_INBOX}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_OUTBOX}">{L_CPL_OUTBOX}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_SAVEBOX}">{L_CPL_SAVEBOX}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_SENTBOX}">{L_CPL_SENTBOX}</a></td>
					</tr>
					-->
					</table>
				</div>
				<div id="private_messages" class="js-sh-box">
					<script type="text/javascript">
					<!--
					tmp = 'private_messages';
					if(GetCookie(tmp) == '2')
					{
						ShowHide('private_messages', 'private_messages2', 'private_messages');
					}
					//-->
					</script>
				</div>
			</td>
		</tr>
		<tr>
			<th style="cursor: pointer;" align="left" onclick="ShowHide('more_info', 'more_info2', 'more_info');">
				<img src="{IMG_NAV_MENU_STAR}" alt="{L_CPL_MORE_INFO}" title="{L_CPL_MORE_INFO}" />&nbsp;
				<a href="#" onclick="return false;" title="{L_CPL_MORE_INFO}" class="nav-menu-link"><b>{L_CPL_MORE_INFO}</b></a>
			</th>
		</tr>
		<tr>
			<td class="row5">
				<div id="more_info2" class="nav-menu">
					<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_DRAFTS}">{L_CPL_DRAFTS}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_BOOKMARKS}">{L_CPL_BOOKMARKS}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_WATCHED_TOPICS}">{L_WATCHED_TOPICS}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_SUBSCFORUMS}">{L_CPL_SUBSCFORUMS}</a></td>
					</tr>
					<!-- BEGIN switch_show_digests -->
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_DIGESTS}">{L_DIGESTS}</a></td>
					</tr>
					<!-- END switch_show_digests -->
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_OWN_POSTS}">{L_CPL_OWN_POSTS}</a></td>
					</tr>
					<tr>
						<td width="8" align="left" valign="middle">{IMG_NAV_MENU_SEP}</td>
						<td class="genmed" align="left"><a href="{U_CPL_OWN_PICTURES}">{L_CPL_OWN_PICTURES}</a></td>
					</tr>
					</table>
				</div>
				<div id="more_info" class="js-sh-box">
					<script type="text/javascript">
					<!--
					tmp = 'more_info';
					if(GetCookie(tmp) == '2')
					{
						ShowHide('more_info', 'more_info2', 'more_info');
					}
					//-->
					</script>
				</div>
			</td>
		</tr>
		</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		{FRIENDS_ONLINE}
		</div>
	</td>
	<td id="full_width_cpl" valign="top" style="padding-left: 7px;">