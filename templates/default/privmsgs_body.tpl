{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_PROFILE}">{L_CPL_NAV}</a>{NAV_SEP}<a href="{U_PRIVATEMSGS}" class="nav-current">{L_PRIVATEMSGS}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		<a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> :: <a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a>
	</div>
</div>{IMG_TBR}
{CPL_MENU_OUTPUT}
<form method="post" name="privmsg_list" action="{S_PRIVMSGS_ACTION}">

<script type="text/javascript">
<!--
//
// Should really check the browser to stop this whining ...
//
function select_switch(status)
{
	for (i = 0; i < document.privmsg_list.length; i++)
	{
		document.privmsg_list.elements[i].checked = status;
	}
}
//-->
</script>

<div class="forumline" style="padding-left:3px;padding-right:3px;margin-top:5px;margin-bottom:5px;">
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td style="vertical-align:top;width:190px;" width="190">
		<!-- BEGIN switch_box_size_notice -->
		<table class="forumline" width="100%" cellspacing="0">
		<tr><th colspan="3"><span class="gensmall">{BOX_SIZE_STATUS}</span></th></tr>
		<tr>
			<td colspan="3" class="row1" nowrap="nowrap"><img src="{BAR_GRAPHIC_LEFT}" width="4" height="12" alt="" /><img src="{BAR_GRAPHIC_BODY}" width="{INBOX_LIMIT_IMG_WIDTH}" height="12" alt="{INBOX_LIMIT_PERCENT}%" title="{INBOX_LIMIT_PERCENT}%" /><img src="{BAR_GRAPHIC_RIGHT}" width="4" height="12" alt="" /></td>
		</tr>
		<tr>
			<td width="33%" class="row3"><span class="gensmall"><span class="text_green">0%</span></span></td>
			<td width="34%" class="row3 row-center"><span class="gensmall"><span class="text_blue">50%</span></span></td>
			<td width="33%" class="row3 row-right"><span class="gensmall"><span class="text_red">100%</span></span></td>
		</tr>
		</table>
		<!-- END switch_box_size_notice -->
	</td>
	<td align="center" style="vertical-align:top;">
		<div class="forumline" style="margin-left:10px;margin-right:10px">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td width="25%" align="center">{INBOX_IMG}<br /><span class="forumlink">{INBOX}</span></td>
			<td width="25%" align="center">{SENTBOX_IMG}<br /><span class="forumlink">{SENTBOX}</span></td>
			<td width="25%" align="center">{OUTBOX_IMG}<br /><span class="forumlink">{OUTBOX}</span></td>
			<td width="25%" align="center">{SAVEBOX_IMG}<br /><span class="forumlink">{SAVEBOX}</span></td>
		</tr>
		</table>
		</div>
	</td>
	<td style="vertical-align:top;width:190px;" width="190">
		<!-- BEGIN switch_box_size_notice -->
		<table class="forumline" width="100%" cellspacing="0">
		<tr><th colspan="3"><span class="gensmall">{ATTACH_BOX_SIZE_STATUS}</span></th></tr>
		<tr>
			<td colspan="3" width="190" class="row1" nowrap="nowrap"><img src="{BAR_GRAPHIC_LEFT}" width="4" height="12" alt="" /><img src="{BAR_GRAPHIC_BODY}" width="{ATTACHBOX_LIMIT_IMG_WIDTH}" height="12" alt="{INBOX_LIMIT_PERCENT}" /><img src="{BAR_GRAPHIC_RIGHT}" width="4" height="12" alt="" /></td>
		</tr>
		<tr>
			<td width="33%" class="row3"><span class="gensmall"><span class="text_green">0%</span></span></td>
			<td width="34%" class="row3 row-center"><span class="gensmall"><span class="text_blue">50%</span></span></td>
			<td width="33%" class="row3 row-right"><span class="gensmall"><span class="text_red">100%</span></span></td>
		</tr>
		</table>
		<!-- END switch_box_size_notice -->
	</td>
</tr>
</table>
</div>

<div style="text-align:left;">{POST_PM_IMG}</div>

{IMG_THL}{IMG_THC}<span class="forumlink">{BOX_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="25">&nbsp;</th>
	<th width="60%">{L_SUBJECT}</th>
	<th width="20%">{L_FROM_OR_TO}</th>
	<th width="15%">{L_DATE}</th>
	<th width="5%">{L_MARK}</th>
</tr>
<!-- BEGIN listrow -->
<tr>
	<td class="row1 row-center" style="padding-right:3px;"><img src="{listrow.PRIVMSG_FOLDER_IMG}" alt="{listrow.L_PRIVMSG_FOLDER_ALT}" title="{listrow.L_PRIVMSG_FOLDER_ALT}" /></td>
	<td class="row1h row-forum" onclick="window.location.href='{listrow.U_READ}'" valign="middle">{listrow.PRIVMSG_ATTACHMENTS_IMG}<span class="topiclink"><a href="{listrow.U_READ}" class="topiclink">{listrow.SUBJECT}</a></span></td>
	<td class="row2 row-center"><a href="{listrow.U_FROM_USER_PROFILE}">{listrow.FROM}</a></td>
	<td class="row2 row-center-small">{listrow.DATE}</td>
	<td class="row3 row-center"><input type="checkbox" name="mark[]2" value="{listrow.S_MARK_ID}" /></td>
</tr>
<!-- END listrow -->
<!-- BEGIN switch_no_messages -->
<tr><td class="row1 row-center" colspan="5">{L_NO_MESSAGES}</td></tr>
<!-- END switch_no_messages -->
<tr>
	<td class="cat" colspan="5">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<!--
			<td nowrap="nowrap" align="left" valign="middle"><span class="gensmall">&nbsp;{L_DISPLAY_MESSAGES}:&nbsp;</span></td>
			<td nowrap="nowrap"><select name="msgdays">{S_SELECT_MSG_DAYS}</select> <input type="submit" value="{L_GO}" name="submit_msgdays" class="liteoption jumpbox" /></td>
		-->
			<td width="100%" align="center" nowrap="nowrap">
				{S_HIDDEN_FIELDS}
				<input type="submit" name="save" value="{L_SAVE_MARKED}" class="mainoption" style="padding-left: 2px; padding-right: 2px;" />
				&nbsp;
				<input type="submit" name="download" value="{L_DOWNLOAD_MARKED}" class="altoption" />
				&nbsp;
				<input type="submit" name="delete" value="{L_DELETE_MARKED}" class="liteoption" style="padding-left: 2px; padding-right: 2px;" />
				&nbsp;
				<input type="submit" name="deleteall" value="{L_DELETE_ALL}" class="liteoption" style="padding-left: 2px; padding-right: 2px;" />
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />

<table width="100%" cellspacing="0" border="0" align="center" cellpadding="0">
<tr>
	<td align="left" valign="middle"><span class="gen">{POST_PM_IMG}</span></td>
	<td align="right" valign="middle" width="100%"><span class="gensmall">{PAGE_NUMBER}</span></td>
</tr>
<tr><td align="right" valign="top" nowrap="nowrap"><span class="gensmall"></span><br /><div class="pagination">{PAGINATION}</div></td></tr>
</table>
</form>

<div align="right">{JUMPBOX}</div>
	</td>
	</tr>
</table>