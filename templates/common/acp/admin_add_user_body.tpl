<form action="{S_PROFILE_ACTION}" method="post" name="profileform">
{ERROR_BOX}

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2" height="25" valign="middle">{L_REGISTRATION_INFO}</th></tr>
<tr><td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td></tr>
<tr>
	<td class="row1" width="50%"><span class="gen"><strong>{L_USERNAME}:</strong> *</span></td>
	<td class="row2"><input type="text" class="post" style="width:200px" name="username" size="25" maxlength="25" value="{USERNAME}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><strong>{L_EMAIL_ADDRESS}:</strong> *</span></td>
	<td class="row2"><input type="text" class="post" style="width:200px" name="email" size="25" maxlength="255" value="{EMAIL}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><strong>{L_NEW_PASSWORD}:</strong> *</span><br />
	<span class="gensmall">{L_PASSWORD_IF_CHANGED}</span></td>
	<td class="row2"><input type="password" class="post" style="width: 200px" name="new_password" size="25" maxlength="32" value="{NEW_PASSWORD}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><strong>{L_CONFIRM_PASSWORD}:</strong> *</span><br /><span class="gensmall">{L_PASSWORD_CONFIRM_IF_CHANGED}</span></td>
	<td class="row2"><input type="password" class="post" style="width: 200px" name="password_confirm" size="25" maxlength="32" value="{PASSWORD_CONFIRM}" /></td>
</tr>
<tr><th colspan="2" height="25" valign="middle">{L_PREFERENCES}</th></tr>
<tr>
	<td class="row1"><span class="gen"><strong>{L_BOARD_LANGUAGE}:</strong></span></td>
	<td class="row2"><span class="gensmall">{LANGUAGE_SELECT}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><strong>{L_BOARD_STYLE}:</strong></span></td>
	<td class="row2"><span class="gensmall">{STYLE_SELECT}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><strong>{L_TIMEZONE}:</strong></span></td>
	<td class="row2"><span class="gensmall">{TIMEZONE_SELECT}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><strong>{L_DATE_FORMAT}:</strong></span></td>
	<td class="row2"><span class="gensmall">{DATE_FORMAT_SELECT}</span></td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center" height="28">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="liteoption" /></td>
</tr>
</table>

</form>