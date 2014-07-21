<table class="nav-div talignc">
<tr>
	<td class="tdalignc">
		<span style="vertical-align: middle;"><a href="{FULL_SITE_PATH}{U_PORTAL}">{L_HOME}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" style="vertical-align: middle;" />&nbsp;</span>
		<span style="vertical-align: middle;"><a href="{FULL_SITE_PATH}{U_INDEX}">{L_INDEX}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" style="vertical-align: middle;" />&nbsp;</span>
		<!-- BEGIN switch_upi2db_off -->
		<span style="vertical-align: middle;"><a href="{FULL_SITE_PATH}{U_SEARCH_NEW}">{L_NEW2}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" style="vertical-align: middle;" />&nbsp;</span>
		<!-- END switch_upi2db_off -->
		<!-- BEGIN switch_upi2db_on -->
		<span style="vertical-align: middle;">{L_POSTS}:&nbsp;<a href="{FULL_SITE_PATH}{U_SEARCH_NEW}">{L_NEW2}</a>&nbsp;&#8226;&nbsp;{L_DISPLAY_U}&nbsp;&#8226;&nbsp;{L_DISPLAY_M}&nbsp;&#8226;&nbsp;{L_DISPLAY_P}&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" style="vertical-align: middle;" />&nbsp;</span>
		<!-- END switch_upi2db_on -->
		<!-- IF S_LOGGED_IN -->
		<span style="vertical-align: middle;"><a href="{FULL_SITE_PATH}{U_PROFILE}">{L_PROFILE}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" style="vertical-align: middle;" />&nbsp;</span>
		<!-- ENDIF -->
		<span style="vertical-align: middle;"><a href="{FULL_SITE_PATH}{U_SEARCH}">{L_SEARCH}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" style="vertical-align: middle;" />&nbsp;</span>
		<span style="vertical-align: middle;"><a href="{FULL_SITE_PATH}{U_FAQ}">{L_FAQ}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" style="vertical-align: middle;" />&nbsp;</span>
		<!-- IF not S_LOGGED_IN -->
		<span style="vertical-align: middle;"><a href="{FULL_SITE_PATH}{U_REGISTER}">{L_REGISTER}</a>&nbsp;&nbsp;<img src="{FULL_SITE_PATH}{IMG_MENU_SEP}" alt="" style="vertical-align: middle;" />&nbsp;</span>
		<!-- ENDIF -->
		<span style="vertical-align: middle;"><a href="{FULL_SITE_PATH}{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT2}</a></span>
	</td>
</tr>
</table>