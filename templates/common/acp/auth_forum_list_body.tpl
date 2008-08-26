<h1>{L_AUTH_LIST_TITLE}</h1>
<p>{L_AUTH_LIST_EXPLAIN}</p>

<form action="{S_FORM_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row-header" colspan="{COLSPAN}"><span>{L_AUTH_LIST_TITLE}</span></td></tr>
<tr>
	<!-- BEGIN forum_auth_titles -->
	<th>{forum_auth_titles.CELL_TITLE}</th>
	<!-- END forum_auth_titles -->
</tr>
<!-- BEGIN forum_row -->
<tr>
	<td class="{forum_row.ROW_CLASS}" align="center" nowrap="nowrap">{forum_row.S_FORUM}</td>
	<!-- BEGIN forum_auth_data -->
	<td class="{forum_row.ROW_CLASS} row-center" valign="center" nowrap="nowrap">{forum_row.forum_auth_data.S_AUTH_LEVELS_SELECT}</td>
	<!-- END forum_auth_data -->
</tr>
<!-- END forum_row -->
<tr>
	<!-- BEGIN forum_auth_titles -->
	<th>{forum_auth_titles.CELL_TITLE}</th>
	<!-- END forum_auth_titles -->
</tr>
<tr>
	<td colspan="{COLSPAN}" class="cat" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
		<input type="reset" value="{L_RESET}" name="reset" class="liteoption" />
	</td>
</tr>
</table>
</form>
<br />
