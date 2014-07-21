<form name="configuration" method="post" action="{ACTION}">
<table class="forumline">
<tr><th colspan="2">{PS_ADMIN_TITLE}</th></tr>
<tr class="th20px">
	<td width="60%" class="row2">&nbsp;</td>
	<td width="40%" class="row2">&nbsp;</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{ALLOW_CHANGE_L}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_allow_change" value="1" {ALLOW_CHANGE_Y} /> {L_ENABLED}  <input type="radio" name="ps_allow_change" value="0" {ALLOW_CHANGE_N} /> {L_DISABLED}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{PS_ADMIN_SESS_L}</span><span class="gensmall">{PS_ADMIN_SESS_E}</span></td>
	<td class="row2"><input type="text" name="ps_sess" class="post" size="5" value="{PS_ADMIN_SESS_V}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{PS_ADMIN_BAN_L}</span><span class="gensmall">{PS_ADMIN_BAN_E}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_ban" value="1" {PS_ADMIN_BAN_Y} /> {L_ENABLED}  <input type="radio" name="ps_ban" value="0" {PS_ADMIN_BAN_N} /> {L_DISABLED}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{PS_LOGIN_LIMIT_L}</span><span class="gensmall">{PS_LOGIN_LIMIT_E}</span></td>
	<td class="row2"><input type="text" name="ps_limit" class="post" size="5" value="{PS_LOGIN_LIMIT_V}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{PS_NOTIFY_ADMIN_L}</span><span class="gensmall">{PS_NOTIFY_ADMIN_E}</span></td>
	<td class="row2">
		<span class="genmed">
			<input type="radio" name="ps_admin" value="1" {PS_NOTIFY_ADMIN_Y} /> {L_ENABLED} <input type="radio" name="ps_admin" value="0" {PS_NOTIFY_ADMIN_N} /> {L_DISABLED}<br />
			<input type="checkbox" name="ps_admin_em" value="1" {ADMIN_EM_V}>{ADMIN_EM_L} <input type="checkbox" name="ps_admin_pm" value="1" {ADMIN_PM_V}>{ADMIN_PM_L}
		</span>
	</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{PS_ADMIN_ID_L}</span><span class="gensmall">{PS_ADMIN_ID_E}</span></td>
	<td class="row2">
		<select name="ps_admin_id">
			<option value="" class="post">{PS_ADMIN_DEFAULT}</option>
	<!-- BEGIN admins -->
			<option value="{admins.ID}" class="post">{admins.NAME}</option>
	<!-- END admins -->
		</select>
		<br />
		<span class="gensmall">{PS_ADMIN_ID_V}</span>
	</td>
</tr>
<tr>
	<th colspan="2">
		<input type="hidden" value="save_config" name="action">
		<input type="submit" value="{L_SUBMIT}" onlick="document.configuration.submit()" class="mainoption">
	</th>
</tr>
</table>
</form>

<br class="clear" />