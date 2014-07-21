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

{IMG_THL}{IMG_THC}<span class="forumlink">{L_TOPICS}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tdnw" colspan="{topics_list_box.row.header_table.COLSPAN}">{topics_list_box.row.L_TITLE}</th>
	<th class="tw50px tdnw">{topics_list_box.row.L_REPLIES}</th>
	<th class="tw100px tdnw">{topics_list_box.row.L_AUTHOR}</th>
	<th class="tw50px tdnw">{topics_list_box.row.L_VIEWS}</th>
	<th class="tw150px tdnw">{topics_list_box.row.L_LASTPOST}</th>
	<!-- BEGIN multi_selection -->
	<th class="tw20px tdnw"><input type="checkbox" name="all_mark_{topics_list_box.row.header_table.BOX_ID}" value="0" onclick="check_uncheck_all_{topics_list_box.row.header_table.BOX_ID}();" /></th>
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
	<td class="{topics_list_box.row.ROW_CLASS} row-center tvalignm tw18px">
		<input type="radio" name="{topics_list_box.FIELDNAME}" value="{topics_list_box.row.FID}" {topics_list_box.row.L_SELECT} />
	</td>
	<!-- END single_selection -->
	<td class="row1 row-center tw18px">{topics_list_box.row.U_MARK_ALWAYS_READ}
		<!--<img src="{topics_list_box.row.TOPIC_FOLDER_IMG}" width="15" height="15" alt="{topics_list_box.row.L_TOPIC_FOLDER_ALT}" title="{topics_list_box.row.L_TOPIC_FOLDER_ALT}" />-->
	</td>
	<!-- BEGIN icon -->
	<td class="{topics_list_box.row.ROW_CLASS} row-center tvalignm tw18px">{topics_list_box.row.ICON}</td>
	<!-- END icon -->
	<td class="row1h{topicrow.LINK_CLASS} row-forum tw100pct" data-href="{topics_list_box.row.U_VIEW_TOPIC}">
		<span class="topiclink{topicrow.LINK_CLASS}">{topics_list_box.row.NEWEST_POST_IMG}{topics_list_box.row.TOPIC_ATTACHMENT_IMG}{topics_list_box.row.TOPIC_TYPE}
			<a href="{topics_list_box.row.U_VIEW_TOPIC}">{topics_list_box.row.TOPIC_TITLE}</a>
		</span>
		<span class="gensmall">&nbsp;&nbsp;{topics_list_box.row.TOPIC_ANNOUNCES_DATES}{topics_list_box.row.TOPIC_CALENDAR_DATES}</span>
		{topics_list_box.row.GOTO_PAGE_FULL}
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
	<td class="row2 row-center tvalignm">
		<span class="postdetails">
			<input type="checkbox" name="{topics_list_box.FIELDNAME}[]{topics_list_box.row.BOX_ID}" value="{topics_list_box.row.FID}" onclick="check_uncheck_main_{topics_list_box.row.BOX_ID}();" {topics_list_box.row.L_SELECT} />
		</span>
	</td>
	<!-- END multi_selection -->
</tr>
<!-- END topic -->
<!-- BEGIN no_topics -->
<tr>
	<td class="row1 row-center th30px" colspan="6">
		<span class="gen">{topics_list_box.row.L_NO_TOPICS}</span>
	</td>
</tr>
<!-- END no_topics -->
<!-- BEGIN bottom -->
<tr>
	<td class="catBottom tvalignm" colspan="{topics_list_box.row.COLSPAN}">
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