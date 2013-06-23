<!-- INCLUDE overall_header.tpl -->

<script type="text/javascript">
// <![CDATA[
function setCheckboxes(theForm, elementName, isChecked)
{
	var chkboxes = document.forms[theForm].elements[elementName];
	var count = chkboxes.length;

	if (count)
	{
		for (var i = 0; i < count; i++)
		{
			chkboxes[i].checked = isChecked;
		}
	}
	else
	{
		chkboxes.checked = isChecked;
	}
	return true;
}
// ]]>
</script>
<form name="unwatch_form" id="unwatch_form" method="post" action="{S_FORM_ACTION}">
<!-- INCLUDE profile_cpl_menu_inc_start.tpl -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_WATCHED_TOPICS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>&nbsp;</th>
	<th>{L_FORUM}</th>
	<th>{L_TOPICS}</th>
	<th>{L_REPLIES}</th>
	<th>{L_STARTED}</th>
	<th>{L_LAST_POST}</th>
	<th>#</th>
</tr>
<!-- BEGIN topic_watch_row -->
<tr>
	<td class="row1 row-center" width="22"><img src="{topic_watch_row.TOPIC_FOLDER_IMG}" alt="{topic_watch_row.L_TOPIC_FOLDER_ALT}" title="{topic_watch_row.L_TOPIC_FOLDER_ALT}" style="margin-right:4px;" /></td>
	<td class="row1h{topic_watch_row.CLASS_NEW} row-forum" data-href="{topic_watch_row.U_VIEW_FORUM}">
		<span class="topiclink{topic_watch_row.CLASS_NEW}"><a href="{topic_watch_row.U_VIEW_FORUM}">{topic_watch_row.FORUM_NAME}</a></span>
	</td>
	<td class="row1h{topic_watch_row.CLASS_NEW} row-forum" data-href="{topic_watch_row.U_VIEW_TOPIC}">
		<div class="topic-title-hide-flow"><div style="float: right; display: inline; vertical-align: top; margin-top: 0px !important; padding-top: 0px !important; padding-right: 3px;">{topic_watch_row.TOPIC_ATTACHMENT_IMG}{topic_watch_row.TOPIC_TYPE_ICON}</div>{topic_watch_row.NEWEST_POST_IMG}<span class="topiclink{topic_watch_row.CLASS_NEW}"><a href="{topic_watch_row.U_VIEW_TOPIC}" class="{topic_watch_row.TOPIC_CLASS}">{topic_watch_row.TOPIC_TITLE}</a></span></div>
		{topic_watch_row.GOTO_PAGE_FULL}
	</td>
	<td class="row2 row-center"><span class="genmed">{topic_watch_row.S_WATCHED_TOPIC_REPLIES}</span></td>
	<td class="row1 row-center"><span class="genmed">{topic_watch_row.S_WATCHED_TOPIC_START}<br />{topic_watch_row.TOPIC_POSTER}</span></td>
	<td class="row2 row-center"><span class="genmed">{topic_watch_row.S_WATCHED_TOPIC_LAST}<br />{topic_watch_row.LAST_POSTER}</span></td>
	<td class="row1 row-center" nowrap="nowrap"><input type="checkbox" name="unwatch_list[]" value="{topic_watch_row.S_WATCHED_TOPIC_ID}" /></td>
</tr>
<!-- END topic_watch_row -->
<!-- BEGIN switch_watched_topics_block -->
<tr><td class="spaceRow" colspan="8"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td colspan="8" class="catBottom"><input type="submit" name="unwatch_topics" class="liteoption" value="{L_STOP_WATCH}" />&nbsp;&nbsp;</td></tr>
<!-- END switch_watched_topics_block -->
<!-- BEGIN switch_no_watched_topics -->
<tr><td colspan="8" class="row1 row-center"><span class="genmed">{L_NO_WATCHED_TOPICS}</span></td></tr>
<!-- END switch_no_watched_topics -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- BEGIN switch_watched_topics_block -->
<div align="right">
	<a href="#" onclick="setCheckboxes('unwatch_form', 'unwatch_list[]', true); return false;" class="gensmall">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="setCheckboxes('unwatch_form', 'unwatch_list[]', false); return false;" class="gensmall">{L_UNMARK_ALL}</a>
	<br />
	<!-- END switch_watched_topics_block -->
	<div class="pagination"><!-- IF PAGINATION -->{PAGINATION}<!-- ELSE -->&nbsp;<!-- ENDIF --></div>
	<!-- BEGIN switch_watched_topics_block -->
</div>
<!-- END switch_watched_topics_block -->

<!-- INCLUDE profile_cpl_menu_inc_end.tpl -->
</form>

<!-- INCLUDE overall_footer.tpl -->