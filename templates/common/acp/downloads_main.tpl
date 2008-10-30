<h1>{DL_MANAGEMENT_TITLE}</h1>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
		<span class="genmed">{DL_MANAGEMENT_EXPLAIN}</span>
		<hr />
		<span class="gensmall">
		<!-- BEGIN remain_traffic -->
		{remain_traffic.REMAIN_TRAFFIC}
		<!-- END remain_traffic -->
		<!-- BEGIN no_remain_traffic -->
		<b><u>{no_remain_traffic.NO_OVERALL_TRAFFIC}</u></b>
		<!-- END no_remain_traffic -->
		<!-- BEGIN total_stat -->
		<br />{total_stat.TOTAL_STAT}
		<!-- END total_stat -->
		</span>
	</td>
	<td valign="top" align="right">
		<span class="genmed">&nbsp;<br />&nbsp;</span>
		<hr />
		<a href="{U_BANLIST}" class="mainmenu">{L_BANLIST}&nbsp;<img src="{BANLIST_IMG}" border="0" alt="" /></a>
	<!-- BEGIN ext_blacklist -->
		<br /><a href="{ext_blacklist.U_EXT_BLACKLIST}" class="mainmenu">{ext_blacklist.L_EXT_BLACKLIST}&nbsp;<img src="{ext_blacklist.EXT_BLACKLIST_IMG}" border="0" alt="" /></a>
	<!-- END ext_blacklist -->
	<!-- BEGIN toolbox -->
		<br /><a href="{toolbox.U_TOOLBOX}" class="mainmenu">{toolbox.L_TOOLBOX}&nbsp;<img src="{toolbox.TOOLBOX_IMG}" border="0" alt="" /></a>
	<!-- END toolbox -->
	</td>
</tr>
</table>
<table class="forumline" cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><th colspan="6">{DL_MANAGEMENT_TITLE}</th></tr>
<tr>
	<!-- BEGIN management_menu_row -->
	<td class="row1 row-center" width="{ROW_WIDTH}%" valign="top">
	<span class="genmed">
	<br />
	<a href="{management_menu_row.U_MODULE_URL}" class="mainmenu"><img src="{management_menu_row.I_MODULE_IMG}" border="0" alt="{management_menu_row.L_MODULE_TITLE}" title="{management_menu_row.L_MODULE_TITLE}" /></a>
	<br /><br />
	<b><a href="{management_menu_row.U_MODULE_URL}" class="mainmenu">{management_menu_row.L_MODULE_TITLE}</a></b>
	<br /><br />
	</span>
	</td>
	<!-- END management_menu_row -->
</tr>
<tr><td class="cat" colspan="6">&nbsp;</td></tr>
</table>

<div align="center">{DL_MOD_RELEASE}</div>

<script language="Javascript" type="text/javascript">
<!--
function help_popup(help_key)
{
	window.open('{U_HELP_POPUP}' + help_key, '_blank', 'width=550,height=400,resizable=yes');;
}
//-->
</script>