<h1>{ADMIN_TITLE}</h1>
<p>{ADMIN_TITLE_EXPLAIN}</p>

<span class="pagination">{PAGINATION}</span>
<form method="post" action="{S_TITLE_ACTION}">
<table class="forumline">
<tr>
	<th>{L_TOPICS_LABELS_HEAD}</th>
	<th>{L_LABEL_CODE}</th>
	<th>{L_LABEL_PERMISSION}</th>
	<th>{L_DATE_FORMAT}</th>
	<th>{L_EDIT}</th>
	<th>{L_DELETE}</th>
</tr>
<!-- BEGIN topic_label -->
<tr>
	<td class="{topic_label.ROW_CLASS} row-center">{topic_label.TITLE}</td>
	<td class="{topic_label.ROW_CLASS} row-center">{topic_label.HTML}</td>
	<td class="{topic_label.ROW_CLASS} row-center">{topic_label.PERMISSIONS}</td>
	<td class="{topic_label.ROW_CLASS} row-center">{topic_label.DATE_FORMAT}</td>
	<td class="{topic_label.ROW_CLASS} row-center"><a href="{topic_label.U_TITLE_EDIT}">{L_EDIT}</a></td>
	<td class="{topic_label.ROW_CLASS} row-center"><a href="{topic_label.U_TITLE_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END topic_label -->
<tr><td class="cat tdalignc" colspan="8"><input type="submit" class="mainoption" name="add" value="{ADD_NEW}" /></td></tr>
</table>
</form>
<span class="pagination">{PAGINATION}</span>

