<script language="JavaScript" type="text/javascript">
<!--
	function select_switch(status)
	{
		for (i = 0; i < document.dl_stats.length-9; i++)
		{
			document.dl_stats.elements[i].checked = status;
		}
	}
//-->
</script>

<form action="{S_FORM_ACTION}" method="post" name="dl_stats">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="9">{L_DL_STATS}</th></tr>
<tr>
	<td class="row1 row-center"><span class="genmed"><b>{L_USERNAME}</b></span></td>
	<td class="row1 row-center"><span class="genmed"><b>{L_DL_FILE_NAME}</b></span></td>
	<td class="row1 row-center"><span class="genmed"><b>{L_CAT_NAME}</b></span></td>
	<td class="row1 row-center"><span class="genmed"><b>{L_TRAFFIC}</b></span></td>
	<td class="row1 row-center"><span class="genmed"><b>{L_DL_DIRECTION}</b></span></td>
	<td class="row1 row-center"><span class="genmed"><b>{L_USER_IP}</b></span></td>
	<td class="row1 row-center"><span class="genmed"><b>{L_BROWSER}</b></span></td>
	<td class="row1 row-center"><span class="genmed"><b>{L_TIME_STAMP}</b></span></td>
	<td class="row1 row-center">
<!-- BEGIN filled_footer -->
		<input type="submit" name="delete" value="{L_DELETE}" class="liteoption" />
<!-- END filled_footer -->
	</td>
</tr>
<!-- BEGIN dl_stat_row -->
<tr>
	<td class="{dl_stat_row.ROW_CLASS} row-center"><span class="nav">{dl_stat_row.USERNAME}</span></td>
	<td class="{dl_stat_row.ROW_CLASS} row-center"><a href="{dl_stat_row.U_DL_LINK}" class="genmed">{dl_stat_row.DESCRIPTION}</a></td>
	<td class="{dl_stat_row.ROW_CLASS} row-center"><a href="{dl_stat_row.U_CAT_LINK}" class="genmed">{dl_stat_row.CAT_NAME}</a></td>
	<td class="{dl_stat_row.ROW_CLASS} row-center"><span class="genmed">{dl_stat_row.TRAFFIC}</span></td>
	<td class="{dl_stat_row.ROW_CLASS} row-center"><span class="genmed">{dl_stat_row.DIRECTION}</span></td>
	<td class="{dl_stat_row.ROW_CLASS} row-center"><span class="genmed">{dl_stat_row.USER_IP}</span></td>
	<td class="{dl_stat_row.ROW_CLASS} row-center"><span class="genmed">{dl_stat_row.BROWSER}</span></td>
	<td class="{dl_stat_row.ROW_CLASS} row-center"><span class="genmed">{dl_stat_row.TIME_STAMP}</span></td>
	<td class="{dl_stat_row.ROW_CLASS} row-center"><input type="checkbox" name="del_id[]" value="{dl_stat_row.ID}" /></td>
</tr>
<!-- END dl_stat_row -->
<!-- BEGIN no_dl_stat_row -->
<tr><td class="row1 row-center" colspan="9" height="25"><span class="genmed">{no_dl_stat_row.L_NO_STAT}</span></td></tr>
<tr><td class="cat" align="center" colspan="9" height="25">&nbsp;</td></tr>
<!-- END no_dl_stat_row -->
<!-- BEGIN filled_footer -->
<tr>
	<td class="cat" colspan="8" align="right">{L_GUESTS_DELETE}:&nbsp;<input type="checkbox" name="del_id" value="-1" />&nbsp;&nbsp;&nbsp;</td>
	<td class="cat" align="center"><input type="submit" name="delete" value="{L_DELETE}" class="liteoption" /></td>
</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td align="right">
		<a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> ::
		<a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a>
	</td>
</tr>
<tr>
	<td align="right"><span class="genmed">
		{L_SORT_BY}:&nbsp;{S_SORT_ORDER}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		{L_SORT_DIR}:&nbsp;{S_SORT_DIR}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" name="submit" value="{L_SORT}" class="mainoption" />
	</span></td>
</tr>
<tr>
	<td align="right"><span class="genmed">
		{S_FILTER}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="text" name="filter_string" value="{FILTER_STRING}" size="30" maxlength="50" />&nbsp;({L_FILTER_STRING})&nbsp;&nbsp;&nbsp;
		{L_SHOW_GUESTS}:&nbsp;<input type="checkbox" name="show_guests" value="1" {S_SHOW_GUESTS} />&nbsp;&nbsp;&nbsp;
		<input type="submit" name="submit" value="{L_FILTER}" class="mainoption" />
	</span></td>
</tr>
<tr><td align="right"><br /><span class="nav">{PAGINATION}</span></td></tr>
<!-- END filled_footer -->
</table>
</form>
