<h1>{L_BAN_TITLE}</h1>
<p>{L_BAN_EXPLAIN}</p>

<form method="post" name="post" action="{S_BANLIST_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_BAN_USER}</th></tr>
<tr>
	<td class="row1"><strong>{L_USERNAME}:</strong></td>
	<td class="row2"><input class="post" type="text" class="post" name="username" maxlength="50" size="20" /> <input type="hidden" name="mode" value="edit" />{S_HIDDEN_FIELDS} <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onclick="window.open('{U_SEARCH_USER}', '_search', 'width=400,height=250,resizable=yes');" /></td>
</tr>
<tr><th colspan="2">{L_UNBAN_USER}</th></tr>
<tr>
	<td class="row1"><strong>{L_USERNAME}:</strong><br /><span class="gensmall">{L_UNBAN_USER_EXPLAIN}</span></td>
	<td class="row2">{S_UNBAN_USERLIST_SELECT}</td>
</tr>
<tr><th colspan="2">{L_BAN_IP}</th></tr>
<tr>
	<td class="row1"><strong>{L_IP_OR_HOSTNAME}:</strong><br /><span class="gensmall">{L_BAN_IP_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="ban_ip" size="35" /></td>
</tr>
<tr><th colspan="2">{L_UNBAN_IP}</th></tr>
<tr>
	<td class="row1"><strong>{L_IP_OR_HOSTNAME}:</strong><br /><span class="gensmall">{L_UNBAN_IP_EXPLAIN}</span></td>
	<td class="row2">{S_UNBAN_IPLIST_SELECT}</td>
</tr>
<tr><th colspan="2">{L_BAN_EMAIL}</th></tr>
<tr>
	<td class="row1"><strong>{L_EMAIL_ADDRESS}:</strong><br /><span class="gensmall">{L_BAN_EMAIL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="ban_email" size="35" /></td>
</tr>
<tr><th colspan="2">{L_UNBAN_EMAIL}</th></tr>
<tr>
	<td class="row1"><strong>{L_EMAIL_ADDRESS}:</strong><br /><span class="gensmall">{L_UNBAN_EMAIL_EXPLAIN}</span></td>
	<td class="row2">{S_UNBAN_EMAILLIST_SELECT}</td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</table>
</form>

<p>{L_BAN_EXPLAIN_WARN}</p>