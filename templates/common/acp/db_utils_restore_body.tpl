<h1>{L_DATABASE_RESTORE}</h1>
<p>{L_RESTORE_EXPLAIN}</p>
<form enctype="multipart/form-data" method="post" action="{S_DBUTILS_ACTION}">
<table class="forumline">
<tr><th>{L_SELECT_FILE}</th></tr>
<tr>
	<td class="cat">
		{S_HIDDEN_FIELDS}&nbsp;
		<input type="file" name="backup_file" />&nbsp;&nbsp;
		<input type="submit" name="restore_start" value="{L_START_RESTORE}" class="mainoption" />
	</td>
</tr>
</table>
</form>
