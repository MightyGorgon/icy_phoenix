<!-- INCLUDE breadcrumbs.tpl --> 

<form action="{S_FORM_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_Db_update_generator}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th class="thTop" colspan="2">{L_INSTRUCTIONS}</th></tr>
<tr><td colspan="2" class="row1"><span class="genmed">{L_SQL_INSTRUCTIONS}</span></td></tr>
<tr><th class="thSides" colspan="2">{L_ENTER_SQL}</th></tr>
<tr>
	<td width="250" valign="top" class="row1"><span class="gen"><b>{L_ENTER_SQL}:</b></span><br /><span class="gensmall">{L_ENTER_SQL_EXPLAIN}</span></td>
	<td class="row2" width="85%"><textarea name="sql" rows="10" cols="80" style="width:99%" class="post">{ORIGINAL_SQL}</textarea></td>
</tr>
<tr>
	<td colspan="2" class="catSides" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;
		<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
<!-- BEGIN show_output -->
<tr><th class="thSides" colspan="2">{L_OUTPUT_SQL}</th></tr>
<tr>
	<td width="200" valign="top" class="row1"><span class="gen"><b>{L_OUTPUT_SQL}:</b></span><br /><span class="gensmall">{L_OUTPUT_SQL_EXPLAIN}</span></td>
	<td class="row2"><textarea rows="20" cols="80" style="width:99%" class="post">{show_output.OUTPUT}</textarea></td>
</tr>
<tr><td colspan="2" class="catBottom" align="center"><input type="submit" name="download" value="{L_DOWNLOAD}" class="mainoption" /></td></tr>
<!-- END show_output -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
		<span class="copyright">
			&copy;2003 <a href="http://freakingbooty.no-ip.com" target="_fb" class="copyright">Freakin' Booty ;-P</a> &amp; <a href="http://www.rapiddr3am.net" target="_rd" class="copyright">Antony Bailey</a><br />
			<a href="http://sourceforge.net/projects/dbgenerator" target="_project" class="copyright">A sourceforge.net hosted project</a>
		</span>
	</td>
</tr>
</table>