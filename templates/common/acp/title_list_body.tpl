<h1>{ADMIN_TITLE}</h1>
<p>{ADMIN_TITLE_EXPLAIN}</p>

<span class="pagination">{PAGINATION}</span>
<form method="post" action="{S_TITLE_ACTION}">
<table class="forumline">
<tr>
	<th>{HEAD_TITLE}</th>
	<th>{HEAD_AUTH}</th>
	<th>{HEAD_DATE}</th>
	<th>{L_EDIT}</th>
	<th>{L_DELETE}</th>
</tr>
<!-- BEGIN title -->
<tr>
	<td class="{title.ROW_CLASS} row-center">{title.TITLE}</td>
	<td class="{title.ROW_CLASS} row-center">{title.PERMISSIONS}</td>
	<td class="{title.ROW_CLASS} row-center">{title.DATE_FORMAT}</td>
	<td class="{title.ROW_CLASS} row-center"><a href="{title.U_TITLE_EDIT}">{L_EDIT}</a></td>
	<td class="{title.ROW_CLASS} row-center"><a href="{title.U_TITLE_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END title -->
<tr><td class="cat tdalignc" colspan="8"><input type="submit" class="mainoption" name="add" value="{ADD_NEW}" /></td></tr>
</table>
</form>
<span class="pagination">{PAGINATION}</span>
