<script type="text/javascript">
<!--
function toggle_check_all()
{
	for( var i=0; i < document.topic_ids.elements.length; i++ )
	{
		var checkbox_element = document.topic_ids.elements[i];
		if( (checkbox_element.name != 'check_all_box') && (checkbox_element.type == 'checkbox') )
		{
			checkbox_element.checked = document.topic_ids.check_all_box.checked;
		}
	}
}
-->
</script>

<form method="post" name="topic_ids" action="{S_MODCP_ACTION}">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_MOD_CP}&nbsp;{L_ENHANCED}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row3 row-center" colspan="5" height="28">&nbsp;<span class="genmed"><b>{TOPIC_TYPES}</b></span></td>
	<td class="row3 row-center" colspan="2" align="right"><span class="genmed">{TOPIC_COUNT}</span>&nbsp;</td>
</tr>
<tr>
	<th width="1%" nowrap="nowrap">&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
	<th width="19%" nowrap="nowrap">{L_FIRSTPOST}</th>
	<th width="8%" nowrap="nowrap">{L_REPLIES}</th>
	<th width="7%" nowrap="nowrap">{L_VIEWS}</th>
	<th width="19%" nowrap="nowrap">{L_LASTPOST}</th>
	<th width="1" nowrap="nowrap"><input type="checkbox" name="check_all_box" onclick="toggle_check_all()" /></th>
</tr>
<!-- BEGIN topicrow -->
<tr>
	<td class="row1 row-center"><img src="{topicrow.TOPIC_FOLDER_IMG}" alt="{topicrow.L_TOPIC_FOLDER_ALT}" title="{topicrow.L_TOPIC_FOLDER_ALT}" /></td>
	<td class="row1h{topicrow.CLASS_NEW} row-forum" onclick="window.location.href='{topicrow.U_VIEW_TOPIC}'">
	<span class="topiclink{topicrow.CLASS_NEW}">{topicrow.TOPIC_ATTACHMENT_IMG}{topicrow.TOPIC_TYPE}<a href="{topicrow.U_VIEW_TOPIC}" class="{topicrow.TOPIC_CLASS}">{topicrow.TOPIC_TITLE}</a></span><br />
	{topicrow.GOTO_PAGE}
	</td>
	<td class="row1 row-center"><span class="postdetails">{topicrow.FIRST_POST_TIME}<br />{topicrow.FIRST_POST_AUTHOR}{topicrow.FIRST_POST_URL}</span></td>
	<td class="row1 row-center"><span class="postdetails">{topicrow.REPLIES}</span>&nbsp;&nbsp;</td>
	<td class="row1 row-center"><span class="postdetails">{topicrow.VIEWS}</span>&nbsp;&nbsp;</td>
	<td class="row1 row-center"><span class="postdetails">{topicrow.LAST_POST_TIME}<br />{topicrow.LAST_POST_AUTHOR}{topicrow.LAST_POST_URL}</span></td>
	<td class="row1 row-center" align="center" valign="middle"><input type="checkbox" name="topic_id_list[]" value="{topicrow.TOPIC_ID}" /></td>
</tr>
<!-- END topicrow -->
<!-- BEGIN switch_no_topics -->
<tr><td class="row1 row-center" colspan="7"><span class="gen">{L_NO_TOPICS}</span></td></tr>
<!-- END switch_no_topics -->
<tr>
	<td class="cat" colspan="7">
		<input type="submit" class="liteoption" name="recycle" value="{L_RECYCLE}" />&nbsp;
		<!-- BEGIN switch_auth_delete -->
		<input type="submit" name="delete" class="liteoption" value="{L_DELETE}" />&nbsp;
		<!-- END switch_auth_delete -->
		<input type="submit" name="poll_delete" class="liteoption" value="{L_POLL_DELETE}" />&nbsp;
		<!-- BEGIN switch_auth_move -->
		<input type="submit" name="move" class="liteoption" value="{L_MOVE}" />&nbsp;
		<!-- END switch_auth_move -->
		<input type="submit" name="merge" class="liteoption" value="{L_MERGE}" />&nbsp;
		<!-- BEGIN switch_auth_lock -->
		<input type="submit" name="lock" class="liteoption" value="{L_LOCK}" />&nbsp;
		<!-- END switch_auth_lock -->
		<!-- BEGIN switch_auth_unlock -->
		<input type="submit" name="unlock" class="liteoption" value="{L_UNLOCK}" />&nbsp;
		<!-- END switch_auth_unlock -->
		<input type="submit" name="quick_title_edit" class="liteoption" value="{L_EDIT_TITLE}" />&nbsp;{SELECT_TITLE}&nbsp;
	</td>
</tr>
<tr>
	<td class="cat" colspan="7">
		<!-- BEGIN switch_auth_global_announce -->
		<input type="submit" name="super_announce" class="liteoption" value="{L_GLOBAL_ANNOUNCE}" />&nbsp;
		<!-- END switch_auth_global_announce -->
		<!-- BEGIN switch_auth_announce -->
		<input type="submit" name="announce" class="liteoption" value="{L_ANNOUNCE}" />&nbsp;
		<!-- END switch_auth_announce -->
		<!-- BEGIN switch_auth_sticky -->
		<input type="submit" name="sticky" class="liteoption" value="{L_STICKY}" />&nbsp;
		<!-- END switch_auth_sticky -->
		<!-- BEGIN switch_auth_normalize -->
		<input type="submit" name="normalize" class="liteoption" value="{L_NORMALIZE}" />
		<!-- END switch_auth_normalize -->
		<!-- BEGIN switch_auth_news -->
		<input type="submit" name="news_category_edit" class="liteoption" value="{L_NEWS_CATEGORY}" />&nbsp;{SELECT_NEWS_CATS}&nbsp;
		<!-- END switch_auth_news -->
		{S_HIDDEN_FIELDS}
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr>
	<td align="left" valign="middle"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="pagination">{PAGINATION}</span></td>
</tr>
<tr><td colspan="2" align="right">{JUMPBOX}</td></tr>
</table>