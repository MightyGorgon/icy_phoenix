<h1>{L_YAHOO_SEARCH_SETTINGS}</h1>
<p>{L_YAHOO_SEARCH_SETTINGS_EXPLAIN}</p>

<form method="post" action="{S_USER_ACTION}">
{ERROR_BOX}
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_YAHOO_SEARCH_SETTINGS}</th></tr>
<tr>
	<td class="row1" width="60%"><b>{L_YAHOO_SEARCH_SELECT_FORUMS}:</b><br /><span class="gensmall">{L_YAHOO_SEARCH_SELECT_FORUMS_EXPLAIN}</span></td>
	<td class="row2" width="40%">{S_FORUMS_SELECT}</td>
</tr>
<tr>
	<td class="row1" width="60%"><b>{L_YAHOO_SEARCH_SAVEPATH}:</b><br /><span class="gensmall">{L_YAHOO_SEARCH_SAVEPATH_EXPLAIN}</span></td>
	<td class="row2" width="40%"><input type="text" class="post" name="search_savepath" value="{SEARCH_SAVEPATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_YAHOO_SEARCH_ADDITIONAL_URLS}:</b><br /><span class="gensmall">{L_YAHOO_SEARCH_ADDITIONAL_URLS_EXPLAIN}</span></td>
	<td class="row2"><textarea name="additional_urls" class="post" rows="5" cols="50">{ADDITIONAL_URLS}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_YAHOO_SEARCH_COMPRESS_FILE}:</b><br /><span class="gensmall">{L_YAHOO_SEARCH_COMPRESS_FILE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="compress_file" value="1" {S_COMPRESS_FILE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="compress_file" value="0" {S_COMPRESS_FILE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><b>{L_YAHOO_SEARCH_COMPRESSION_LEVEL}:</b><br /><span class="gensmall">{L_YAHOO_SEARCH_COMPRESSION_LEVEL_EXPLAIN}</span></td>
	<td class="row2"><input type="text" class="post" name="compression_level" value="{COMPRESSION_LEVEL}" size="1" maxlength="1" /></td>
</tr>
<tr><td class="cat" align="center" colspan="2"><input type="submit" value="{L_YAHOO_SEARCH_GENERATE_FILE}" name="submit" class="mainoption" /></td></tr>
</table>

</form>