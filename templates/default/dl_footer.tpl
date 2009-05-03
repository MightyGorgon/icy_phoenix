<!-- BEGIN approve -->
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<tr><td class="row3 row-center"><a href="{approve.U_APPROVE_DOWNLOADS}" class="forumlink">{approve.L_APPROVE_DOWNLOADS}</a></td></tr>
</table>
<br />
<!-- END approve -->
<!-- BEGIN approve_comments -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row3 row-center" height="25"><a href="{approve_comments.U_APPROVE_COMMENTS}" class="forumlink">{approve_comments.L_APPROVE_COMMENTS}</a></td></tr>
</table>
<br />
<!-- END approve_comments -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DOWNLOADS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
<!-- BEGIN switch_stats_view_on -->
	<td width="{WIDTH}" class="row3 row-center"><a href="{switch_stats_view_on.U_STATS}" class="forumlink">{switch_stats_view_on.L_DL_STATS}</a></td>
<!-- END switch_stats_view_on -->
	<td width="{WIDTH}" class="row3 row-center"><a href="{U_OVERALL_VIEW}" class="forumlink">{L_OVERALL_VIEW}</a></td>
<!-- BEGIN switch_todo_on -->
	<td width="{WIDTH}" class="row3 row-center"><a href="{switch_todo_on.U_TODOLIST}" class="forumlink">{switch_todo_on.L_TODOLIST}</a></td>
<!-- END switch_todo_on -->
<!-- BEGIN switch_config_on -->
	<td width="{WIDTH}" class="row3 row-center"><a href="{switch_config_on.U_CONFIG}" class="forumlink">{switch_config_on.L_CONFIG}</a></td>
<!-- END switch_config_on -->
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />

<div id="dl_legend_h" style="display: none;">
{IMG_THL}{IMG_THC}<img style="{SHOW_HIDE_PADDING}float: right; cursor: pointer;" src="{IMG_MAXIMISE}" onclick="javascript:ShowHide('dl_legend','dl_legend_h','dl_legend');" alt="{L_SHOW}" /><span class="forumlink">{L_LEGEND}</span>{IMG_THR_ALT}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="dl_legend">
<script type="text/javascript">
<!--
tmp = 'dl_legend';
if(GetCookie(tmp) == '2')
{
	ShowHide('dl_legend', 'dl_legend_h', 'dl_legend');
}
//-->
</script>
{IMG_THL}{IMG_THC}<img style="{SHOW_HIDE_PADDING}float: right; cursor: pointer;" src="{IMG_MINIMISE}" onclick="javascript:ShowHide('dl_legend','dl_legend_h','dl_legend');" alt="{L_HIDE}" /><span class="forumlink">{L_LEGEND}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" colspan="4">{DL_JUMPBOX}</td>
	<td class="row1 row-center" colspan="4">{JUMPBOX}</td>
</tr>
<!-- BEGIN footer_legend -->
<tr>
	<td class="row1 row-center" width="12%"><img src="{BLUE}" alt="{L_DL_BLUE_EXPLAIN}" title="{L_DL_BLUE_EXPLAIN}" border="0"/></td>
	<td class="row1 row-center" width="12%"><img src="{RED}" alt="{L_DL_RED_EXPLAIN_ALT}" title="{L_DL_RED_EXPLAIN_ALT}" border="0"/></td>
	<td class="row1 row-center" width="12%"><img src="{GREY}" alt="{L_DL_GREY_EXPLAIN}" title="{L_DL_GREY_EXPLAIN}" border="0"/></td>
	<td class="row1 row-center" width="12%"><img src="{WHITE}" alt="{L_DL_WHITE_EXPLAIN}" title="{L_DL_WHITE_EXPLAIN}" border="0"/></td>
	<td class="row1 row-center" width="12%"><img src="{YELLOW}" alt="{L_DL_YELLOW_EXPLAIN}" title="{L_DL_YELLOW_EXPLAIN}" border="0"/></td>
	<td class="row1 row-center" width="12%"><img src="{GREEN}" alt="{L_DL_GREEN_EXPLAIN}" title="{L_DL_GREEN_EXPLAIN}" border="0"/></td>
	<td class="row1 row-center" width="12%"><img src="{NEW_DL}" alt="{L_NEW_DL}" title="{L_NEW_DL}" border="0"/></td>
	<td class="row1 row-center" width="12%"><img src="{EDIT_DL}" alt="{L_EDIT_DL}" title="{L_EDIT_DL}" border="0"/></td>
</tr>
<tr>
	<td class="row1 row-center" width="12%"><span class="gensmall">{L_DL_BLUE_EXPLAIN}</span></td>
	<td class="row1 row-center" width="12%"><span class="gensmall">{L_DL_RED_EXPLAIN}</span></td>
	<td class="row1 row-center" width="12%"><span class="gensmall">{L_DL_GREY_EXPLAIN}</span></td>
	<td class="row1 row-center" width="12%"><span class="gensmall">{L_DL_WHITE_EXPLAIN}</span></td>
	<td class="row1 row-center" width="12%"><span class="gensmall">{L_DL_YELLOW_EXPLAIN}</span></td>
	<td class="row1 row-center" width="12%"><span class="gensmall">{L_DL_GREEN_EXPLAIN}</span></td>
	<td class="row1 row-center" width="12%"><span class="gensmall">{L_NEW_DL}</span></td>
	<td class="row1 row-center" width="12%"><span class="gensmall">{L_EDIT_DL}</span></td>
</tr>
<!-- END footer_legend -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>

<br />
<br />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DOWNLOADS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%" align="center">
		<span class="gensmall">
		{L_CAN_DOWNLOAD_AGAIN}
		<!-- BEGIN userdata -->
		{userdata.ACCOUNT_TRAFFIC}
		<!-- END userdata -->
		<!-- BEGIN remain_traffic -->
		{remain_traffic.REMAIN_TRAFFIC}
		<!-- END remain_traffic -->
		<!-- BEGIN no_remain_traffic -->
		<b><u>{no_remain_traffic.NO_OVERALL_TRAFFIC}</u></b>
		<!-- END no_remain_traffic -->
		</span>
	</td>
</tr>
<!-- BEGIN total_stat -->
<tr><td align="center"><span class="gensmall">{total_stat.TOTAL_STAT}</span></td></tr>
<!-- END total_stat -->
<tr><td align="center"><span class="gensmall">{DL_MOD_RELEASE}</span></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<script language="Javascript" type="text/javascript">
<!--
function help_popup(help_key)
{
	window.open('{U_HELP_POPUP}' + help_key, '_blank', 'height=400,resizable=yes,width=550');;
}
//-->
</script>