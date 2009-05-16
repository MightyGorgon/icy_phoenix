<script language="Javascript" type="text/javascript">
	function select_switch(status)
	{
		for (i = 0; i < document.dl_modcp.length; i++)
		{
			document.dl_modcp.elements[i].checked = status;
		}
	}
</script>

<form method="post" name="dl_modcp" action="{S_DL_MODCP_ACTION}" >
{IMG_THL}{IMG_THC}<span class="forumlink">{L_NAV2}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>&nbsp;{L_DOWNLOAD} [ {L_DL_CAT_NAME} ]&nbsp;</th>
	<th>&nbsp;{L_COMMENT}&nbsp;</th>
	<th colspan="2">&nbsp;{L_SET}&nbsp;</th>
</tr>
<!-- BEGIN approve_row -->
<tr>
	<td class="{approve_row.ROW_CLASS}">{approve_row.MINI_ICON}&nbsp;<a href="{approve_row.U_DOWNLOAD}" class="topiclink">{approve_row.DESCRIPTION}</a>&nbsp;<span class="genmed">[ <a href="{approve_row.U_CAT_VIEW}" class="genmed">{approve_row.CAT_NAME}</a> ]</span></td>
	<td class="{approve_row.ROW_CLASS}">{approve_row.U_USER_LINK}<br /><span class="gensmall">{approve_row.COMMENT_TEXT}</span></td>
	<td class="{approve_row.ROW_CLASS} row-center" width="10%"><a href="{approve_row.U_EDIT}">{approve_row.EDIT_IMG}</a></td>
	<td class="{approve_row.ROW_CLASS} row-center" width="5%"><input type="checkbox" name="dlo_id[]" value="{approve_row.COMMENT_ID}" /></td>
</tr>
<!-- END approve_row -->
<tr>
	<td colspan="4" align="right" class="cat" valign="top" nowrap="nowrap">
		<input type="submit" name="cdelete" value="{L_DELETE}" class="liteoption" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_APPROVE}" class="mainoption"/>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td align="right" valign="top" nowrap="nowrap"><span class="gensmall">
		<a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a>
		</span><br /><br /><span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>
</form>