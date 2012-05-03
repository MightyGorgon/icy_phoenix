<!-- BEGIN switch_header_table -->
<tr>
	<td width="100%" colspan="3" align="center">
	<div style="width: 90%; margin: 0 auto; clear: both; text-align: center; padding: 10px;">
		{IMG_TBL}<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
			<tr><td class="row-header"><span>{L_STAFF_MESSAGE}</span></td></tr>
			<tr><td class="row-post"><div class="post-text">{switch_header_table.HEADER_TEXT}</div><br /><br /></td></tr>
		</table>{IMG_TBR}
	</div>
	</td>
</tr>
<!-- END switch_header_table -->

<!-- BEGIN ctracker_message -->
<tr>
	<td width="100%" colspan="3" align="center">
	<div style="width: 90%; margin: 0 auto; clear: both; text-align: center; padding: 10px;">
		{IMG_TBL}<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td width="80" align="center" style="background-color:#{ctracker_message.ROW_COLOR};"><img src="{ctracker_message.ICON_GLOB}" alt="" title="" /></td>
			<td style="background-color:#{ctracker_message.ROW_COLOR};"><div class="gensmall">{ctracker_message.L_MESSAGE_TEXT}</div></td>
		</tr>
		<tr><td class="row1 row-center" colspan="2"><span class="gensmall"><!-- IF ctracker_message.U_MARK_MESSAGE --><b><a href="{ctracker_message.U_MARK_MESSAGE}">{ctracker_message.L_MARK_MESSAGE}</a></b><!-- ELSE -->&nbsp;<!-- ENDIF --></span><br /></td></tr>
		</table>{IMG_TBR}
	</div>
	</td>
</tr>
<!-- END ctracker_message -->

<tr>
	<td colspan="3" id="content">
	<!-- IF S_LOGGED_IN -->
	<!-- IF NEW_PM_SWITCH --><div class="popup{PRIVMSG_IMG}"><a href="{FULL_SITE_PATH}{U_PRIVATEMSGS}">{PRIVATE_MESSAGE_INFO}</a></div><!-- ENDIF -->
	<!-- IF NEW_PRIVATE_CHAT_SWITCH --><div id="shoutbox_pvt_alert" class="popup{PRIVATE_CHAT_IMG}"><a class="gensmall" href="{U_PRIVATE_CHAT}">{L_AJAX_SHOUTBOX_PVT_ALERT}</a></div><!-- ENDIF -->
	<!-- ENDIF -->
	<!-- BEGIN switch_admin_disable_board -->
	<table width="100%" align="center" cellspacing="0" cellpadding="10" border="0">
	<tr><td align="center" class="forumline-no"><div class="genmed"><div class="topic_ann">{L_BOARD_DISABLE}</div></div></td></tr>
	</table>
	<!-- END switch_admin_disable_board -->

	<!-- IF SWITCH_CMS_GLOBAL_BLOCKS -->
	<div style="vertical-align: top;"><!-- BEGIN header_blocks_row -->{header_blocks_row.CMS_BLOCK}<!-- END header_blocks_row --></div>
	<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
	<!-- IF HL_BLOCK -->
	<td id="var_width" width="{HEADER_WIDTH}" style="width: {HEADER_WIDTH}px !important;" valign="top">
	<div id="quick_links2" style="padding-top: 5px; display: none; margin-left: 0px; text-align: left; position: relative; float: left;"><a href="javascript:ShowHide('quick_links','quick_links2','quick_links');setWidth('var_width',{HEADER_WIDTH});setWidth('full_width','auto');" title="{L_SHOW} {L_QUICK_LINKS}"><img src="{IMG_NAV_MENU_APPLICATION}" alt="{L_SHOW} {L_QUICK_LINKS}" /></a></div>
	<div id="quick_links"><!-- BEGIN headerleft_blocks_row -->{headerleft_blocks_row.CMS_BLOCK}<!-- END headerleft_blocks_row --></div>
	</td>
	<td width="5"><img src="{SPACER}" alt="" width="5" height="10" /></td>
	<!-- ENDIF -->
	<td id="full_width" valign="top">
	<script type="text/javascript">
	// <![CDATA[
	cellobject = document.getElementById('var_width');
	if((cellobject != null) && !is_ie && ((getWidth('var_width') == 16) | (getWidth('var_width') == '16px')))
	{
		setWidth('full_width', '100%');
	}
	// ]]>
	</script>
	<!-- IF HC_BLOCK --><div style="vertical-align: top;"><!-- BEGIN headercenter_blocks_row -->{headercenter_blocks_row.CMS_BLOCK}<!-- END headercenter_blocks_row --></div><!-- ENDIF -->
	<!-- ENDIF -->
