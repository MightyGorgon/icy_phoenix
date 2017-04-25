<!-- BEGIN restore -->
<h1>{L_ACP_RESTORE}</h1>
<p>{L_ACP_RESTORE_EXPLAIN}</p>

<form id="acp_backup" method="post" action="{U_ACTION}">
<table class="forumline">
<tr><th colspan="2">{L_RESTORE_OPTIONS}</th></tr>
<tr>
	<td class="row1" nowrap="nowrap"><label for="file"><b>{L_SELECT_FILE}</b>:</label></td>
	<td class="row1 row-center tw100pct">
		<select id="file" name="file" size="10" style="min-height: 200px;">
		<!-- BEGIN files -->
		<option value="{restore.files.FILE}">{restore.files.NAME}</option>
		<!-- END files -->
		</select>
	</td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center" id="submitbar">
		<input class="mainoption" type="submit" id="submit" name="submit" value="{L_START_RESTORE}" />&nbsp;
		<input class="mainoption" type="submit" id="download" name="download" value="{L_DOWNLOAD_BACKUP}" />&nbsp;
		<input class="liteoption" type="submit" id="delete" name="delete" value="{L_DELETE_BACKUP}" />
	</td>
</tr>
</table>
</form>
<!-- END restore -->
<!-- BEGIN backup -->
<h1>{L_ACP_BACKUP}</h1>
<p>{L_ACP_BACKUP_EXPLAIN}</p>

<script type="text/javascript">
// <![CDATA[
function selector(bool)
{
	var table = document.getElementById('table');
	for (var i = 0; i < table.options.length; i++)
	{
		table.options[i].selected = bool;
	}
}
// ]]>
</script>

<form id="acp_backup" method="post" action="{U_ACTION}">
<table class="forumline">
<tr><th colspan="2">{L_BACKUP_OPTIONS}</th></tr>
<tr>
	<td class="row1" nowrap="nowrap"><strong>{L_BACKUP_TYPE}:</strong></td>
	<td class="row1 tw100pct">
		<label><input type="radio" class="radio" name="type" value="full" id="type" checked="checked" /> {L_FULL_BACKUP}</label>
		<label><input type="radio" name="type" class="radio" value="structure" /> {L_STRUCTURE_ONLY}</label>
		<label><input type="radio" class="radio" name="type" value="data" /> {L_DATA_ONLY}</label>
	</td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap"><strong>{L_BACKUP_TYPE_COMPLETE}:</strong></td>
	<td class="row1 tw100pct">
		<label><input type="radio" name="complete" value="1" id="type" checked="checked" />&nbsp;{L_YES}</label>
		<label><input type="radio" name="complete" value="0" />&nbsp;{L_NO}</label>
	</td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap"><strong>{L_BACKUP_TYPE_EXTENDED}:</strong></td>
	<td class="row1 tw100pct">
		<label><input type="radio" name="extended" value="1" id="type" checked="checked" />&nbsp;{L_YES}</label>
		<label><input type="radio" name="extended" value="0" />&nbsp;{L_NO}</label>
	</td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap"><strong>{L_BACKUP_TYPE_COMPACT}:</strong></td>
	<td class="row1 tw100pct">
		<label><input type="radio" name="compact" value="1" id="type" checked="checked" />&nbsp;{L_YES}</label>
		<label><input type="radio" name="compact" value="0" />&nbsp;{L_NO}</label>
	</td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap"><label for="method"><strong>{L_FILE_TYPE}:</strong></label></td>
	<td class="row1 tw100pct">
	<!-- BEGIN methods -->
	<label><input name="method"{backup.methods.FIRST_ROW} type="radio" class="radio" value="{backup.methods.TYPE}" /> {backup.methods.TYPE}</label>
	<!-- END methods -->
	</td>
</tr>
<!--
<tr>
	<td class="row1" nowrap="nowrap"><strong><label for="where">{L_ACTION}:</label></strong></td>
	<td class="row1 tw100pct">
		<label><input type="radio" class="radio" name="where" value="store_and_download" id="where" checked="checked" /> {L_STORE_AND_DOWNLOAD}</label>
		<label><input type="radio" class="radio" name="where" value="store" /> {L_STORE_LOCAL}</label>
		<label><input type="radio" class="radio" name="where" value="download" /> {L_DOWNLOAD}</label>
	</td>
</tr>
-->
<tr>
	<td class="row1" nowrap="nowrap"><strong><label for="table">{L_TABLE_SELECT}:</label></strong></td>
	<td class="row1 row-center tw100pct">
		<select id="table" name="table[]" size="20" multiple="multiple" style="min-height: 300px;">
		<!-- BEGIN tables -->
		<option value="{tables.TABLE}">{tables.TABLE}</option>
		<!-- END tables -->
		</select><br />
		<a href="#" onclick="selector(true)">{L_SELECT_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="selector(false)">{L_DESELECT_ALL}</a>
	</td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center" id="submitbar">
		<input type="hidden" name="where" value="store" />
		<input class="mainoption" type="submit" id="submit" name="submit" value="{L_SUBMIT}" />&nbsp;
		<input class="liteoption" type="reset" id="reset" name="reset" value="{L_RESET}" />
	</td>
</tr>
</table>
</form>
<!-- END backup -->