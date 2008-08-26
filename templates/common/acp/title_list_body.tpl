<h1>{ADMIN_TITLE}</h1>
<p>{ADMIN_TITLE_EXPLAIN}</p>

<span class="pagination">{PAGINATION}</span>
<form method="post" action="{S_TITLE_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{HEAD_TITLE}</th>
	<th>{HEAD_AUTH}</th>
	<th>{HEAD_DATE}</th>
	<th>{L_EDIT}</th>
	<th>{L_DELETE}</th>
</tr>
<!-- BEGIN title -->
<tr>
	<td class="{title.ROW_CLASS}" align="center">{title.TITLE}</td>
	<td class="{title.ROW_CLASS}" align="center">{title.PERMISSIONS}</td>
	<td class="{title.ROW_CLASS}" align="center">{title.DATE_FORMAT}</td>
	<td class="{title.ROW_CLASS}" align="center"><a href="{title.U_TITLE_EDIT}">{L_EDIT}</a></td>
	<td class="{title.ROW_CLASS}" align="center"><a href="{title.U_TITLE_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END title -->
<tr><td class="cat" align="center" colspan="8"><input type="submit" class="mainoption" name="add" value="{ADD_NEW}" /></td></tr>
</table>
</form>
<span class="pagination">{PAGINATION}</span>
