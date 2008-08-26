<h1>{L_USER_SEARCH}</h1>

<p>{NEW_SEARCH}</p>

<form action="{S_POST_ACTION}" method="post" name="post">
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
<tr>
	<td align="center" class="nav"><span class="gen">{L_SORT_OPTIONS}</span> <a href="{U_USERNAME}">{L_USERNAME}</a> | <a href="{U_EMAIL}">{L_EMAIL}</a> | <a href="{U_POSTS}">{L_POSTS}</a> | <a href="{U_JOINDATE}">{L_JOINDATE}</a> | <a href="{U_LASTVISIT}">{L_LASTVISIT}</a></td>
</tr>
</table>
<p>&nbsp;</p>
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
<tr>
	<td align="left" class="nav"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right" class="nav" nowrap="nowrap"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th nowrap="nowrap">{L_USERNAME}</th>
	<th nowrap="nowrap">{L_EMAIL}</th>
	<th nowrap="nowrap">{L_JOINDATE}</th>
	<th nowrap="nowrap">{L_POSTS}</th>
	<th nowrap="nowrap">{L_LASTVISIT}</th>
	<th nowrap="nowrap">&nbsp;</th>
	<th nowrap="nowrap">&nbsp;</th>
	<th nowrap="nowrap">&nbsp;</th>
	<th nowrap="nowrap">{L_ACCOUNT_STATUS}</th>
</tr>
<!-- BEGIN userrow -->
<tr>
	<td class="{userrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">&nbsp;<a href="{userrow.U_VIEWPROFILE}" class="gen">{userrow.USERNAME}</a>&nbsp;</span></td>
	<td class="{userrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">&nbsp;{userrow.EMAIL}&nbsp;</span></td>
	<td class="{userrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">&nbsp;{userrow.JOINDATE}&nbsp;</span></td>
	<td class="{userrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">&nbsp;<a href="{userrow.U_VIEWPOSTS}" class="gen">{userrow.POSTS}</a>&nbsp;</span></td>
	<td class="{userrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">&nbsp;{userrow.LASTVISIT}&nbsp;</span></td>
	<td class="{userrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">&nbsp;<a href="{userrow.U_MANAGE}" class="gen">{L_MANAGE}</a>&nbsp;</span></td>
	<td class="{userrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">&nbsp;<a href="{userrow.U_PERMISSIONS}" class="gen">{L_PERMISSIONS}</a>&nbsp;</span></td>
	<td class="{userrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">&nbsp;{userrow.BAN}&nbsp;</span></td>
	<td class="{userrow.ROW_CLASS}" align="center" nowrap="nowrap"><span class="gen">&nbsp;{userrow.ABLED}&nbsp;</span></td>
</tr>
<!-- END userrow -->
<tr><td class="cat" colspan="9" height="28">&nbsp;</td></tr>
</table>
</form>