<h1>{L_ACCOUNT_ACTIONS}</h1>
<p>{L_ACCOUNT_ACTIONS_EXPLAIN}</p>

<!-- BEGIN switch_message -->
<p><span class="text_green">{MESSAGE}</span></p>
<!-- END switch_message -->

<form method="post" name="users" action="{S_ACCOUNT_ACTION}">
<table align="center" width="100%" cellspacing="2" cellpadding="2" border="0">
<tr>
	<td class="tdnw"><span class="gensmall">{L_SORT_PER_LETTER}:<br />{S_LETTER_SELECT}{S_LETTER_HIDDEN}</span></td>
	<td valign="bottom" align="right" nowrap="nowrap">
		<span class="gensmall">
		{L_REGISTERED_AWAITS}:&nbsp;
		<select name="days">{S_SELECT_DAYS}</select>
		<input type="submit" value="{L_GO}" name="submit_wait" class="liteoption" />
		</span>
	</td>
</tr>
</table>

<table class="forumline">
<tr>
	<th>#</th>
	<th>{L_USERNAME}</th>
	<th>{L_EMAIL}</th>
	<th>{L_POSTS_ANDOR_PICS}</th>
	<th>{L_JOINED}</th>
	<th>{L_MARK}</th>
</tr>
<tr>
	<td colspan="2" nowrap="nowrap" class="row3"><span class="gensmall">{L_ACTIVATION}</span></td>
	<td colspan="4" class="row3"><span class="gensmall align-right">{TOTAL_USERS}</span></td>
</tr>
<!-- BEGIN admin_account -->
<tr>
	<td class="{admin_account.ROW_CLASS} row-center"><span class="gensmall">{admin_account.ROW_NUMBER}&nbsp;</span></td>
	<td class="{admin_account.ROW_CLASS} tvalignm" width="30%" nowrap="nowrap">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>{admin_account.USERNAME}<!-- IF admin_account.USER_FULL_NAME -->&nbsp;<span class="gensmall">({admin_account.USER_FULL_NAME})</span><!-- ENDIF --></td>
			<td><span class="gensmall align-right"><a href="{admin_account.U_EDIT_USER}" class="gensmall">{L_EDIT_USER}</a>&nbsp;|&nbsp;<a href="{admin_account.U_USER_AUTH}" class="gensmall">{L_USER_AUTH}</a></span>&nbsp;&nbsp;</td>
		</tr>
		</table>
	</td>
	<td class="{admin_account.ROW_CLASS}"><span class="genmed">{admin_account.EMAIL}</span></td>
	<td class="{admin_account.ROW_CLASS} row-center"><span class="genmed">{admin_account.POSTS}<!-- IF admin_account.PICS --> / {admin_account.PICS}<!-- ENDIF --></span></td>
	<td class="{admin_account.ROW_CLASS}" nowrap="nowrap"><span class="gensmall"><b>{admin_account.PERIOD}</b></span>&nbsp;&nbsp;&nbsp;<span class="genmed">{admin_account.JOINED}</span>&nbsp;</td>
	<td class="{admin_account.ROW_CLASS} row-center"><input type="checkbox" name="mark[]" value="{admin_account.S_MARK_ID}" /></td>
</tr>
<!-- END admin_account -->
<!-- BEGIN switch_no_users -->
<tr><td class="row1 row-center" colspan="6" valign="middle"><span class="gen">{L_NO_USERS}</span></td></tr>
<!-- END switch_no_users -->
<tr>
	<td class="cat" colspan="6">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="activate" value="{L_DE_ACTIVATE_MARKED}" class="liteoption" />&nbsp;
		<input type="submit" name="delete" value="{L_DELETE_MARKED}" class="liteoption" />
	</td>
</tr>
</table>

<table>
<tr>
	<td valign="middle" align="left" width="100%"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td class="tdalignr tdnw">
		<span class="gensmall"><a href="#" onclick="setCheckboxes('users', 'mark[]', true); return false;" class="gensmall">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="setCheckboxes('users', 'mark[]', false); return false;" class="gensmall">{L_UNMARK_ALL}</a></span><br class="mb5" />
		<span class="pagination">{PAGINATION}</span><br />
	</td>
</tr>
</table>
</form>
