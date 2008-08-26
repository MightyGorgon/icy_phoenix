<script language="Javascript" type="text/javascript">
<!--
function select_switch(status)
{
	for (i = 0; i < document.banlist.length; i++)
	{
		document.banlist.elements[i].checked = status;
	}
}
// -->
</script>

<p><b>{L_DL_BANLIST_EXPLAIN}</b></p>

<form action="{S_DOWNLOADS_ACTION}" method="post" name="add_ban">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="5">{L_DL_BANLIST}</th></tr>
<tr>
	<td class="row1 row-center"><span class="nav">{L_DL_USER_ID}</span><br /><input type="text" size="5" maxlength="8" class="post" name="user_id" value="{DL_USER_ID}" /></td>
	<td class="row1 row-center"><span class="nav">{L_DL_USER_IP}</span><br /><input type="text" size="15" maxlength="15" class="post" name="user_ip" value="{DL_USER_IP}" /></td>
	<td class="row1 row-center"><span class="nav">{L_DL_USER_AGENT}</span><br /><input type="text" size="10" maxlength="50" class="post" name="user_agent" value="{DL_USER_AGENT}" /></td>
	<td class="row1 row-center"><span class="nav">{L_DL_USERNAME}</span><br /><input type="text" size="10" maxlength="25" class="post" name="username" value="{DL_USERNAME}" /></td>
	<td class="row1 row-center"><span class="nav">{L_DL_GUESTS}</span><br /><span class="genmed"><input type="radio" name="guests" value="1" {CHECKED_YES} />&nbsp;{L_DL_YES}&nbsp;<input type="radio" name="guests" value="0" {CHECKED_NO} />&nbsp;{L_DL_NO}</span></td>
</tr>
<tr><td colspan="5" class="cat" align="center">{S_HIDDEN_FIELDS}<input type="submit" class="mainoption" name="submit" value="{L_DL_ADD_NEW}" /></td></tr>
</table>
</form>

<form action="{S_DOWNLOADS_ACTION}" method="post" name="banlist">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_DL_USER_ID}</th>
	<th>{L_DL_USER_IP}</th>
	<th>{L_DL_USER_AGENT}</th>
	<th>{L_DL_USERNAME}</th>
	<th>{L_DL_GUESTS}</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN banlist_row -->
<tr>
	<td class="{banlist_row.ROW_CLASS} row-center"><span class="genmed">&nbsp;{banlist_row.USER_ID}&nbsp;</span></td>
	<td class="{banlist_row.ROW_CLASS} row-center"><span class="genmed">&nbsp;{banlist_row.USER_IP}&nbsp;</span></td>
	<td class="{banlist_row.ROW_CLASS} row-center"><span class="genmed">&nbsp;{banlist_row.USER_AGENT}&nbsp;</span></td>
	<td class="{banlist_row.ROW_CLASS} row-center"><span class="genmed">&nbsp;{banlist_row.USERNAME}&nbsp;</span></td>
	<td class="{banlist_row.ROW_CLASS} row-center"><span class="genmed">&nbsp;{banlist_row.GUESTS}&nbsp;</span></td>
	<td class="{banlist_row.ROW_CLASS} row-center"><input type="checkbox" name="ban_id[]" value="{banlist_row.BAN_ID}" /></td>
</tr>
<!-- END banlist_row -->
<tr>
	<td colspan="6" class="cat" align="right"><input type="submit" name="edit_banlist" class="mainoption" value="{L_DL_EDIT}" />&nbsp;<input type="submit" name="delete_banlist" class="mainoption" value="{L_DL_DELETE}" /></td>
</tr>
</table>
<table width="100%" cellpadding="3" cellspacing="1" border="0">
<tr>
	<td align="right" valign="top" nowrap="nowrap">
		<a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> ::
		<a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a>
	</td>
</tr>
</table>
</form>