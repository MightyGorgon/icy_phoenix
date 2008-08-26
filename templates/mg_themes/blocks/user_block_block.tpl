<!-- BEGIN switch_user_logged_in -->
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
		<br />{AVATAR_IMG}<br /><br />
		<span class="name">{U_NAME_LINK}</span><br />
		<span class="gensmall">{LAST_VISIT_DATE}</span><br />
		<span class="gensmall"><a href="{U_SEARCH_NEW}">{L_NEW_SEARCH}</a></span><br /><br />
	</td>
</tr>
</table>
<!-- END switch_user_logged_in -->
<!-- BEGIN switch_user_logged_out -->
<form method="post" action="{S_LOGIN_ACTION}">
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
	<br />
	{AVATAR_IMG}<br /><br /><span class="gensmall">
	<input type="hidden" name="redirect" value="{U_PORTAL}" />
	{L_USERNAME}:<br />
	<input class="post" type="text" name="username" size="15" />
	<br />
	{L_PASSWORD}:<br />
	<input class="post" type="password" name="password" size="15" />
	<br />
	</span>
	<!-- BEGIN switch_allow_autologin -->
	<br />
	<input class="text" type="checkbox" name="autologin" /><span class="gensmall">&nbsp;{L_REMEMBER_ME}</span><br />
	<!-- END switch_allow_autologin -->
	<br/>
	<input type="submit" class="mainoption" name="login" value="{L_LOGIN}" /><br /><br />
	<a href="{U_SEND_PASSWORD}" class="gensmall">{L_SEND_PASSWORD}</a><br /><br />
	<span class="gensmall">{L_REGISTER_NEW_ACCOUNT}</span><br /><br />
	</td>
</tr>
</table>
</form>
<!-- END switch_user_logged_out -->