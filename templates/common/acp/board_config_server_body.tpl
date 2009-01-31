<h1>{L_CONFIGURATION_TITLE}</h1>
<p>{L_CONFIGURATION_EXPLAIN}</p>

<form id="configform" action="{S_CONFIG_ACTION}" method="post"><table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_SERVER_SETTINGS}</th></tr>
<tr>
	<td class="row1" width="300"><strong>{L_SERVER_NAME}</strong></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="40" name="server_name" value="{SERVER_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SERVER_PORT}</strong><br /><span class="gensmall">{L_SERVER_PORT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="5" size="5" name="server_port" value="{SERVER_PORT}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SCRIPT_PATH}</strong><br /><span class="gensmall">{L_SCRIPT_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" name="script_path" value="{SCRIPT_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SITE_NAME}</strong><br /><span class="gensmall">{L_SITE_NAME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="100" name="sitename" value="{SITENAME}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SITE_DESCRIPTION}</strong></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="255" name="site_desc" value="{SITE_DESCRIPTION}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_DISABLE_BOARD}</strong><br /><span class="gensmall">{L_DISABLE_BOARD_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="board_disable" value="1" {S_DISABLE_BOARD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="board_disable" value="0" {S_DISABLE_BOARD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_MESSAGE_DISABLE_BOARD}</strong></td>
	<td class="row2"><input type="radio" name="board_disable_mess_st" value="1" {S_MESSAGE_DISABLE_BOARD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="board_disable_mess_st" value="0" {S_MESSAGE_DISABLE_BOARD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><span class="gensmall">{L_MESSAGE_DISABLE_BOARD_TEXT}</span></td>
	<td class="row2"><input class="post" type="text" size="80" name="message_board_disable_text" value="{BOARD_DISABLE_MESSAGE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ENABLE_GZIP}</strong></td>
	<td class="row2"><input type="radio" name="gzip_compress" value="1" {GZIP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gzip_compress" value="0" {GZIP_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_DISABLE_REGISTRATION_IP_CHECK}</strong><br /><span class="gensmall">{L_DISABLE_REGISTRATION_IP_CHECK_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="disable_registration_ip_check" value="1" {S_DISABLE_REGISTRATION_IP_CHECK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disable_registration_ip_check" value="0" {S_DISABLE_REGISTRATION_IP_CHECK_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_REGISTRATION_STATUS}</strong><br /><span class="gensmall">{L_REGISTRATION_STATUS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="registration_status" value="1" {S_REGISTRATION_STATUS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="registration_status" value="0" {S_REGISTRATION_STATUS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_REGISTRATION_CLOSED}</strong><br /><span class="gensmall">{L_REGISTRATION_CLOSED_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="40" maxlength="255" name="registration_closed" value="{REGISTRATION_CLOSED}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ACCT_ACTIVATION}</strong></td>
	<td class="row2"><input type="radio" name="require_activation" value="{ACTIVATION_NONE}" {ACTIVATION_NONE_CHECKED} />{L_NONE}&nbsp; &nbsp;<input type="radio" name="require_activation" value="{ACTIVATION_USER}" {ACTIVATION_USER_CHECKED} />{L_USER}&nbsp; &nbsp;<input type="radio" name="require_activation" value="{ACTIVATION_ADMIN}" {ACTIVATION_ADMIN_CHECKED} />{L_ADMIN}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_VISUAL_CONFIRM}</strong><br /><span class="gensmall">{L_VISUAL_CONFIRM_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="enable_confirm" value="1" {CONFIRM_ENABLE} />{L_YES}&nbsp; &nbsp;<input type="radio" name="enable_confirm" value="0" {CONFIRM_DISABLE} />{L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_USE_CAPTCHA}</strong><br /><span class="gensmall">{L_USE_CAPTCHA_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="use_captcha" value="1" {USE_CAPTCHA_ENABLE} />{L_YES}&nbsp; &nbsp;<input type="radio" name="use_captcha" value="0" {USE_CAPTCHA_DISABLE} />{L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_AUTOLOGIN}</strong><br /><span class="gensmall">{L_ALLOW_AUTOLOGIN_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_autologin" value="1" {ALLOW_AUTOLOGIN_YES} />{L_YES}&nbsp; &nbsp;<input type="radio" name="allow_autologin" value="0" {ALLOW_AUTOLOGIN_NO} />{L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_AUTOLOGIN_TIME}</strong><br /><span class="gensmall">{L_AUTOLOGIN_TIME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="max_autologin_time" value="{AUTOLOGIN_TIME}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_MAX_LOGIN_ATTEMPTS}</strong><br /><span class="gensmall">{L_MAX_LOGIN_ATTEMPTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="max_login_attempts" value="{MAX_LOGIN_ATTEMPTS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_LOGIN_RESET_TIME}</strong><br /><span class="gensmall">{L_LOGIN_RESET_TIME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="login_reset_time" value="{LOGIN_RESET_TIME}" /></td>
</tr>

<tr><th colspan="2">{L_COOKIE_SETTINGS}</th></tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_COOKIE_SETTINGS_EXPLAIN}</span></td>
</tr>
<tr>
	<td class="row1"><strong>{L_COOKIE_DOMAIN}</strong></td>
	<td class="row2"><input class="post" type="text" maxlength="255" name="cookie_domain" value="{COOKIE_DOMAIN}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_COOKIE_NAME}</strong></td>
	<td class="row2"><input class="post" type="text" maxlength="16" name="cookie_name" value="{COOKIE_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_COOKIE_PATH}</strong></td>
	<td class="row2"><input class="post" type="text" maxlength="255" name="cookie_path" value="{COOKIE_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_COOKIE_SECURE}</strong><br /><span class="gensmall">{L_COOKIE_SECURE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="cookie_secure" value="0" {S_COOKIE_SECURE_DISABLED} />{L_DISABLED}&nbsp; &nbsp;<input type="radio" name="cookie_secure" value="1" {S_COOKIE_SECURE_ENABLED} />{L_ENABLED}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_SESSION_LENGTH}</strong></td>
	<td class="row2"><input class="post" type="text" maxlength="5" size="5" name="session_length" value="{SESSION_LENGTH}" /></td>
</tr>

<tr><th colspan="2">{L_COPPA_SETTINGS}</th></tr>
<tr>
	<td class="row1"><strong>{L_COPPA_FAX}</strong></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="100" name="coppa_fax" value="{COPPA_FAX}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_COPPA_MAIL}</strong><br /><span class="gensmall">{L_COPPA_MAIL_EXPLAIN}</span></td>
	<td class="row2"><textarea name="coppa_mail" rows="5" cols="30">{COPPA_MAIL}</textarea></td>
</tr>

<tr><th colspan="2">{L_EMAIL_SETTINGS}</th></tr>
<tr>
	<td class="row1"><strong>{L_BOARD_EMAIL_FORM}</strong><br /><span class="gensmall">{L_BOARD_EMAIL_FORM_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="board_email_form" value="1" {BOARD_EMAIL_FORM_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="board_email_form" value="0" {BOARD_EMAIL_FORM_DISABLE} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ADMIN_EMAIL}</strong></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="100" name="board_email" value="{EMAIL_FROM}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_EMAIL_SIG}</strong><br /><span class="gensmall">{L_EMAIL_SIG_EXPLAIN}</span></td>
	<td class="row2"><textarea name="board_email_sig" rows="5" cols="30">{EMAIL_SIG}</textarea></td>
</tr>
<tr>
	<td class="row1"><strong>{L_USE_SMTP}</strong><br /><span class="gensmall">{L_USE_SMTP_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="smtp_delivery" value="1" {SMTP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="smtp_delivery" value="0" {SMTP_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_SMTP_SERVER}</strong></td>
	<td class="row2"><input class="post" type="text" name="smtp_host" value="{SMTP_HOST}" size="25" maxlength="50" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SMTP_USERNAME}</strong><br /><span class="gensmall">{L_SMTP_USERNAME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="smtp_username" value="{SMTP_USERNAME}" size="25" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SMTP_PASSWORD}</strong><br /><span class="gensmall">{L_SMTP_PASSWORD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="password" name="smtp_password" value="{SMTP_PASSWORD}" size="25" maxlength="255" /></td>
</tr>
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>

<br clear="all" />
