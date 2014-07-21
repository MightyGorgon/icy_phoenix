<!-- INCLUDE overall_header.tpl -->

{CPL_MENU_OUTPUT}
<form method="post" name="privmsg_list" action="{S_PRIVMSGS_ACTION}">

<script type="text/javascript">
// <![CDATA[
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
// ]]>
</script>

<div class="forumline" style="padding-left: 3px; padding-right: 3px; margin-top: 5px; margin-bottom: 5px;">
<table>
<tr>
	<td style="vertical-align: top; width: 190px;">
		<!-- BEGIN switch_box_size_notice -->
		<table class="forumline">
		<tr><th colspan="3"><span class="gensmall">{BOX_SIZE_STATUS}</span></th></tr>
		<tr>
			<td class="row1 tdnw" colspan="3"><img src="{BAR_GRAPHIC_LEFT}" width="4" height="12" alt="" /><img src="{BAR_GRAPHIC_BODY}" width="{INBOX_LIMIT_IMG_WIDTH}" height="12" alt="{INBOX_LIMIT_PERCENT}%" title="{INBOX_LIMIT_PERCENT}%" /><img src="{BAR_GRAPHIC_RIGHT}" width="4" height="12" alt="" /></td>
		</tr>
		<tr>
			<td class="tw33pct row3"><span class="gensmall"><span class="text_green">0%</span></span></td>
			<td class="tw34pct row3 row-center"><span class="gensmall"><span class="text_blue">50%</span></span></td>
			<td class="tw33pct row3 row-right"><span class="gensmall"><span class="text_red">100%</span></span></td>
		</tr>
		</table>
		<!-- END switch_box_size_notice -->
	</td>
	<td class="tdalignc">
		<div class="forumline" style="margin-left: 10px; margin-right: 10px;">
		<table>
		<tr>
			<td class="tdalignc tw25pct">{INBOX_IMG}<br /><b><span class="topiclink">{INBOX}</span></b></td>
			<td class="tdalignc tw25pct">{SENTBOX_IMG}<br /><b><span class="topiclink">{SENTBOX}</span></b></td>
			<td class="tdalignc tw25pct">{OUTBOX_IMG}<br /><b><span class="topiclink">{OUTBOX}</span></b></td>
			<td class="tdalignc tw25pct">{SAVEBOX_IMG}<br /><b><span class="topiclink">{SAVEBOX}</span></b></td>
		</tr>
		</table>
		</div>
	</td>
	<td style="vertical-align: top; width: 190px;">
		<!-- BEGIN switch_box_size_notice -->
		<table class="forumline">
		<tr><th colspan="3"><span class="gensmall">{ATTACH_BOX_SIZE_STATUS}</span></th></tr>
		<tr>
			<td class="row1 tdnw" colspan="3"><img src="{BAR_GRAPHIC_LEFT}" width="4" height="12" alt="" /><img src="{BAR_GRAPHIC_BODY}" width="{ATTACHBOX_LIMIT_IMG_WIDTH}" height="12" alt="{INBOX_LIMIT_PERCENT}" /><img src="{BAR_GRAPHIC_RIGHT}" width="4" height="12" alt="" /></td>
		</tr>
		<tr>
			<td class="tw33pct row3"><span class="gensmall"><span class="text_green">0%</span></span></td>
			<td class="tw34pct row3 row-center"><span class="gensmall"><span class="text_blue">50%</span></span></td>
			<td class="tw33pct row3 row-right"><span class="gensmall"><span class="text_red">100%</span></span></td>
		</tr>
		</table>
		<!-- END switch_box_size_notice -->
	</td>
</tr>
</table>
</div>

<div style="text-align: left;"><span class="img-btn">{POST_PM_IMG}</span></div>

{IMG_THL}{IMG_THC}<span class="forumlink">{BOX_NAME}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw25px">&nbsp;</th>
	<th class="tw60pct">{L_SUBJECT}</th>
	<th class="tw20pct">{L_FROM_OR_TO}</th>
	<th class="tw15pct">{L_DATE}</th>
	<th class="tw5pct">{L_MARK}</th>
</tr>
<!-- BEGIN listrow -->
<tr>
	<td class="row1 row-center" style="padding-right: 3px;"><img src="{listrow.PRIVMSG_FOLDER_IMG}" alt="{listrow.L_PRIVMSG_FOLDER_ALT}" title="{listrow.L_PRIVMSG_FOLDER_ALT}" /></td>
	<td class="row1h row-forum" data-href="{listrow.U_READ}" valign="middle">{listrow.PRIVMSG_ATTACHMENTS_IMG}<span class="topiclink"><a href="{listrow.U_READ}" class="topiclink">{listrow.SUBJECT}</a></span></td>
	<td class="row2 row-center">{listrow.FROM}</td>
	<td class="row2 row-center-small">{listrow.DATE}</td>
	<td class="row3 row-center"><input type="checkbox" name="mark[]2" value="{listrow.S_MARK_ID}" /></td>
</tr>
<!-- END listrow -->
<!-- BEGIN switch_no_messages -->
<tr><td class="row1 row-center" colspan="5">{L_NO_MESSAGES}</td></tr>
<!-- END switch_no_messages -->
<tr>
	<td class="cat" colspan="5">
		<table>
		<tr>
		<!--
			<td class="tvalignm tdnw"><span class="gensmall">&nbsp;{L_DISPLAY_MESSAGES}:&nbsp;</span></td>
			<td class="tdnw"><select name="msgdays">{S_SELECT_MSG_DAYS}</select> <input type="submit" value="{L_GO}" name="submit_msgdays" class="liteoption jumpbox" /></td>
		-->
			<td class="tw100pct tdalignc tdnw">
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

<table>
<tr>
	<td><span class="img-btn">{POST_PM_IMG}</span></td>
	<td class="tw70pct tdalignr tdnw"><span class="gensmall"><!-- IF PAGE_NUMBER -->{PAGE_NUMBER}<!-- ELSE -->&nbsp;<!-- ENDIF --></span><br /><div class="pagination"><!-- IF PAGINATION -->{PAGINATION}<!-- ELSE -->&nbsp;<!-- ENDIF --></div></td>
</tr>
</table>
</form>

<div align="right">{JUMPBOX}</div>
	</td>
	</tr>
</table>

<!-- INCLUDE overall_footer.tpl -->