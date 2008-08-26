<h1>{L_DATABASE_RESTORE}</h1>
<P>{L_RESTORE_EXPLAIN}</p>
<form enctype="multipart/form-data" method="post" action="{S_DBUTILS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th>{L_SELECT_FILE}</th></tr>
<tr>
	<td class="row1 row-center">
		{S_HIDDEN_FIELDS}&nbsp;
		<input type="file" name="backup_file" />&nbsp;&nbsp;
		<input type="submit" name="restore_start" value="{L_START_RESTORE}" class="mainoption" />&nbsp;
	</td>
</tr>
</table>
</form>
