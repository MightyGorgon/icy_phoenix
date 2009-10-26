<!-- INCLUDE overall_header.tpl -->

<!-- BEGIN topics_list_box -->
<!-- BEGIN row -->
<!-- BEGIN header_table -->
<!-- BEGIN multi_selection -->
<script type="text/javascript">
//
// checkbox selection management
function check_uncheck_main_{topics_list_box.row.header_table.BOX_ID}()
{
	var all_checked = true;
	for (i = 0; (i < document.{topics_list_box.FORMNAME}.elements.length) && all_checked; i++)
	{
		if (document.{topics_list_box.FORMNAME}.elements[i].name == '{topics_list_box.FIELDNAME}[]{topics_list_box.row.header_table.BOX_ID}')
		{
			all_checked =  document.{topics_list_box.FORMNAME}.elements[i].checked;
		}
	}
	document.{topics_list_box.FORMNAME}.all_mark_{topics_list_box.row.header_table.BOX_ID}.checked = all_checked;
}
// check/uncheck all
function check_uncheck_all_{topics_list_box.row.header_table.BOX_ID}()
{
	for (i = 0; i < document.{topics_list_box.FORMNAME}.length; i++)
	{
		if (document.{topics_list_box.FORMNAME}.elements[i].name == '{topics_list_box.FIELDNAME}[]{topics_list_box.row.header_table.BOX_ID}')
		{
			document.{topics_list_box.FORMNAME}.elements[i].checked = document.{topics_list_box.FORMNAME}.all_mark_{topics_list_box.row.header_table.BOX_ID}.checked;
		}
	}
}
</script>
<!-- END multi_selection -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_TOPICS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="{topics_list_box.row.header_table.COLSPAN}" nowrap="nowrap">{topics_list_box.row.L_TITLE}</th>
	<th width="50" nowrap="nowrap">{topics_list_box.row.L_REPLIES}</th>
	<th width="100" nowrap="nowrap">{topics_list_box.row.L_AUTHOR}</th>
	<th width="50" nowrap="nowrap">{topics_list_box.row.L_VIEWS}</th>
	<th width="150" nowrap="nowrap">{topics_list_box.row.L_LASTPOST}</th>
	<!-- BEGIN multi_selection -->
	<th width="20" nowrap="nowrap"><input type="checkbox" name="all_mark_{topics_list_box.row.header_table.BOX_ID}" value="0" onclick="check_uncheck_all_{topics_list_box.row.header_table.BOX_ID}();" /></th>
	<!-- END multi_selection -->
</tr>
<!-- END header_table -->
<!-- BEGIN header_row -->
<tr>
	<td class="row3" colspan="{topics_list_box.row.COLSPAN}">
		span class="gensmall">&nbsp;&nbsp;<b>{topics_list_box.row.L_TITLE}</b></span>
	</td>
</tr>
<!-- END header_row -->
<!-- BEGIN topic -->
<tr>
	<!-- BEGIN single_selection -->
	<td class="{topics_list_box.row.ROW_CLASS} row-center" valign="middle" width="18">
		<input type="radio" name="{topics_list_box.FIELDNAME}" value="{topics_list_box.row.FID}" {topics_list_box.row.L_SELECT} />
	</td>
	<!-- END single_selection -->
	<td class="row1 row-center" width="18">{topics_list_box.row.U_MARK_ALWAYS_READ}
		<!--<img src="{topics_list_box.row.TOPIC_FOLDER_IMG}" width="15" height="15" alt="{topics_list_box.row.L_TOPIC_FOLDER_ALT}" title="{topics_list_box.row.L_TOPIC_FOLDER_ALT}" />-->
	</td>
	<!-- BEGIN icon -->
	<td class="{topics_list_box.row.ROW_CLASS} row-center" valign="middle" width="18">{topics_list_box.row.ICON}</td>
	<!-- END icon -->
	<td class="row1h{topicrow.LINK_CLASS} row-forum" width="100%" onclick="window.location.href='{topics_list_box.row.U_VIEW_TOPIC}'">
		<span class="topiclink{topicrow.LINK_CLASS}">{topics_list_box.row.NEWEST_POST_IMG}{topics_list_box.row.TOPIC_ATTACHMENT_IMG}{topics_list_box.row.TOPIC_TYPE}
			<a href="{topics_list_box.row.U_VIEW_TOPIC}">{topics_list_box.row.TOPIC_TITLE}</a>
		</span>
		<span class="gensmall">&nbsp;&nbsp;{topics_list_box.row.TOPIC_ANNOUNCES_DATES}{topics_list_box.row.TOPIC_CALENDAR_DATES}</span>
		<span class="gensmall">{topics_list_box.row.GOTO_PAGE}</span>
		<span class="gensmall">
			<!-- BEGIN nav_tree -->
			{topics_list_box.row.TOPIC_NAV_TREE}
			<!-- END nav_tree -->
		</span>
	</td>
	<td class="row2 row-center-small">
		<span class="postdetails">{topics_list_box.row.REPLIES}</span>
	</td>
	<td class="row3 row-center-small">
		<span class="name">{topics_list_box.row.TOPIC_AUTHOR}</span>
	</td>
	<td class="row2 row-center-small">
		<span class="postdetails">{topics_list_box.row.VIEWS}</span>
	</td>
	<td class="row3 row-center-small">
		<span class="postdetails">{topics_list_box.row.LAST_POST_TIME}<br />{topics_list_box.row.LAST_POST_AUTHOR}&nbsp;{topics_list_box.row.LAST_POST_IMG}</span>
	</td>
	<!-- BEGIN multi_selection -->
	<td class="row2 row-center" valign="middle">
		<span class="postdetails">
			<input type="checkbox" name="{topics_list_box.FIELDNAME}[]{topics_list_box.row.BOX_ID}" value="{topics_list_box.row.FID}" onclick="check_uncheck_main_{topics_list_box.row.BOX_ID}();" {topics_list_box.row.L_SELECT} />
		</span>
	</td>
	<!-- END multi_selection -->
</tr>
<!-- END topic -->
<!-- BEGIN no_topics -->
<tr>
	<td class="row1 row-center" colspan="6" height="30">
		<span class="gen">{topics_list_box.row.L_NO_TOPICS}</span>
	</td>
</tr>
<!-- END no_topics -->
<!-- BEGIN bottom -->
<tr>
	<td class="catBottom" colspan="{topics_list_box.row.COLSPAN}" valign="middle">
		<span class="genmed">{topics_list_box.row.FOOTER}</span>
	</td>
</tr>
<!-- END bottom -->
<!-- BEGIN footer_table -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END footer_table -->
<!-- BEGIN spacer -->
<br class="gensmall">
<!-- END spacer -->
<!-- END row -->
<!-- END topics_list_box -->

<!-- INCLUDE overall_footer.tpl -->