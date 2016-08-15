<h1>{L_ADMIN_USERS_LIST_MAIL_TITLE}</h1>

<p>{L_ADMIN_USERS_LIST_MAIL_EXPLAIN}</p>
<table class="forumline">
<tr>
	<th valign="middle" nowrap="nowrap">{L_USERNAME}</th>
	<th valign="middle" nowrap="nowrap">{L_EMAIL}</th>
</tr>
<!-- BEGIN userrow -->
<tr>
	<td class="{userrow.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="genmed">{userrow.USERNAME}</span><!-- IF userrow.USER_FULL_NAME -->&nbsp;<span class="gensmall">({userrow.USER_FULL_NAME})</span><!-- ENDIF --></td>
	<td class="{userrow.COLOR}" align="center" valign="middle" height="28" nowrap="nowrap"><span class="genmed"><a href="mailto:{userrow.EMAIL}">{userrow.EMAIL}</a></span></td>
</tr>
<!-- END userrow -->
<tr>
	<td class="cat" height="28" align="center" valign="middle" colspan="8">
	</td>
</tr>
</table>

<table>
<tr>
	<td class="tw50pct"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td class="tw50pct tdalignr"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
