<script type="text/javascript">
function select_switch(status)
{
	for( i = 0; i < document.users.length; i++ )
	{
		document.users.elements[i].checked = status;
	}
}
</script>

<h1>{L_ACCOUNT_ACTIONS}</h1>
<p>{L_ACCOUNT_ACTIONS_EXPLAIN}</p>

<!-- BEGIN switch_message -->
<p><span class="text_green">{MESSAGE}</span></p>
<!-- END switch_message -->

<form method="post" name="users" action="{S_ACCOUNT_ACTION}">
<table border="0" cellspacing="2" cellpadding="2" align="center" width="100%">
<tr>
	<td align="left" nowrap="nowrap"><span class="gensmall">{L_SORT_PER_LETTER}:<br />{S_LETTER_SELECT}{S_LETTER_HIDDEN}</span></td>
	<td valign="bottom" align="right" nowrap="nowrap">
		<span class="gensmall">
		{L_REGISTERED_AWAITS}:&nbsp;
		<select name="days">{S_SELECT_DAYS}</select>
		<input type="submit" value="{L_GO}" name="submit_wait" class="liteoption" />
		</span>
	</td>
</tr>
</table>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>#</th>
	<th>{L_USERNAME}</th>
	<th>{L_EMAIL}</th>
	<th>{L_JOINED}</th>
	<th>{L_MARK}</th>
</tr>
<tr>
	<td colspan="2" align="left" nowrap="nowrap" class="row3"><span class="gensmall">{L_ACTIVATION}</span></td>
	<td colspan="3" align="right" class="row3"><span class="gensmall">{TOTAL_USERS}</span></td>
</tr>
<!-- BEGIN admin_account -->
<tr>
	<td valign="middle" align="right" class="{admin_account.ROW_CLASS}"><span class="gensmall">{admin_account.ROW_NUMBER}&nbsp;</span></td>
	<td valign="middle" width="30%" nowrap="nowrap" class="{admin_account.ROW_CLASS}">
		<table border="0" cellpadding="1" cellspacing="1" width="100%">
		<tr>
			<td align="left"><span class="cattitle" nowrap="nowrap"><a href="{admin_account.U_PROFILE}" class="cattitle">{admin_account.USERNAME}</a></span></td>
			<td align="right">
				<span class="gensmall"><a href="{admin_account.U_EDIT_USER}" class="gensmall">{L_EDIT_USER}</a>&nbsp;|&nbsp;<a href="{admin_account.U_USER_AUTH}" class="gensmall">{L_USER_AUTH}</a></span>&nbsp;&nbsp;
			</td>
		</tr>
		</table></td>
	<td valign="middle" align="center" class="{admin_account.ROW_CLASS}"><span class="genmed">{admin_account.EMAIL}</span></td>
	<td valign="middle" align="right" nowrap="nowrap" class="{admin_account.ROW_CLASS}"><span class="gensmall"><b>{admin_account.PERIOD}</b></span>&nbsp;&nbsp;&nbsp;<span class="genmed">{admin_account.JOINED}</span>&nbsp;</td>
	<td valign="middle" align="center" width="1" class="{admin_account.ROW_CLASS}"><input type="checkbox" name="mark[]2" value="{admin_account.S_MARK_ID}" /></td>
</tr>
<!-- END admin_account -->
<!-- BEGIN switch_no_users -->
<tr><td colspan="5" valign="middle" align="center" class="row1"><span class="gen">{L_NO_USERS}</span></td></tr>
<!-- END switch_no_users -->
<tr>
	<td colspan="5" align="right" height="28" class="cat">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="activate" value="{L_DE_ACTIVATE_MARKED}" class="liteoption" />&nbsp;
		<input type="submit" name="delete" value="{L_DELETE_MARKED}" class="liteoption" />
	</td>
</tr>
</table>

<table border="0" cellspacing="2" cellpadding="2" align="center" width="100%">
<tr>
	<td valign="middle" align="left" width="100%"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td valign="top" align="right" nowrap="nowrap">
		<b><span class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a></span></b><br />
		<span class="pagination">{PAGINATION}</span><br />
	</td>
</tr>
</table>
</form>