<h1>{L_DATABASE_BACKUP}</h1>
<p>{L_BACKUP_EXPLAIN}</p>

<form method="post" action="{S_DBUTILS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_BACKUP_OPTIONS}</th></tr>
<tr>
	<td class="row2">{L_FULL_BACKUP}</td>
	<td class="row2"><input type="radio" name="backup_type" value="full" checked /></td>
</tr>
<tr>
	<td class="row1">{L_STRUCTURE_BACKUP}</td>
	<td class="row1"><input type="radio" name="backup_type" value="structure" /></td>
</tr>
<tr>
	<td class="row2">{L_DATA_BACKUP}</td>
	<td class="row2"><input type="radio" name="backup_type" value="data" /></td>
</tr>
<tr>
	<td class="row1">{L_GZIP_COMPRESS}</td>
	<td class="row1">{L_NO}&nbsp;<input type="radio" name="gzipcompress" value="0" checked />&nbsp;&nbsp;{L_YES}&nbsp;<input type="radio" name="gzipcompress" value="1" /></td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center">
		{S_HIDDEN_FIELDS}
		<input type="hidden" name="phpbb_only" value="0" checked />
		<input type="submit" name="backupstart" value="{L_START_BACKUP}" class="mainoption" />
	</td>
</tr>
</table>
</form>
