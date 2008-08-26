<form action="{S_FORM_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_INSTRUCTIONS}</th></tr>
<tr><td colspan="2" class="row1"><span class="gen">{L_SQL_INSTRUCTIONS}</span></td></tr>
<tr><th colspan="2">&nbsp;</th></tr>
<tr><th colspan="2">{L_ENTER_SQL}</th></tr>
<tr>
	<td width="150" class="row1" valign="top">{L_ENTER_SQL}<br /><span class="gensmall">{L_ENTER_SQL_EXPLAIN}</span></td>
	<td class="row2"><textarea name="sql" rows="10" cols="50" class="post">{ORIGINAL_SQL}</textarea></td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;
		<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
<!-- BEGIN show_output -->
<tr><th colspan="2">{L_OUTPUT_SQL}</th></tr>
<tr>
	<td width="150" valign="top" class="row1">{L_OUTPUT_SQL}<br /><span class="gensmall">{L_OUTPUT_SQL_EXPLAIN_ADMIN}</span></td>
	<td class="row2"><textarea rows="20" cols="50" class="post">{show_output.OUTPUT}</textarea></td>
</tr>
<tr><td class="cat" colspan="2" align="center"><input type="submit" name="download" value="{L_DOWNLOAD}" class="mainoption" /></td></tr>
<!-- END show_output -->
</table>
</form>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center"><span class="copyright">&copy;2003 <a href="http://freakingbooty.no-ip.com" target="_fb" class="copyright">Freakin' Booty ;-P</a> &amp; <a href="http://www.rapiddr3am.net" target="_rd" class="copyright">Antony Bailey</a><br />
	<a href="http://sourceforge.net/projects/dbgenerator" target="_project" class="copyright">A sourceforge.net hosted project</a></span></td>
</tr>
</table>