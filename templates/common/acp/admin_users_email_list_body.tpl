<h1>{L_ADMIN_USERS_LIST_MAIL_TITLE}</h1>

<p>{L_ADMIN_USERS_LIST_MAIL_EXPLAIN}</p>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th valign="middle" nowrap="nowrap">{L_USERNAME}</th>
	<th valign="middle" nowrap="nowrap">{L_EMAIL}</th>
</tr>
<!-- BEGIN userrow -->
<tr>
	<td class="{userrow.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="genmed">{userrow.USERNAME}</span></td>
	<td class="{userrow.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="genmed"><a href="mailto:{userrow.EMAIL}">{userrow.EMAIL}</a></span></td>
</tr>
<!-- END userrow -->
<tr>
	<td class="cat" height="28" align="center" valign="middle" colspan="8">
	</td>
</tr>
</table>

<table width="100%" cellpadding="3" cellspacing="1" border="0">
<tr>
	<td align="left" width="50%"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right" width="50%"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
