<!-- BEGIN similar -->
<div id="similar_block_h" style="display: none;">
	{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('similar_block','similar_block_h','similar_block');" alt="{L_SHOW}" /><span class="forumlink">{similar.L_SIMILAR}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr><td class="row1g row-center">&nbsp;</td></tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="similar_block">
	{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('similar_block','similar_block_h','similar_block');" alt="{L_HIDE}" /><span class="forumlink">{similar.L_SIMILAR}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<th colspan="2">{similar.L_TOPIC}</th>
		<th>{similar.L_AUTHOR}</th>
		<th>{similar.L_FORUM}</th>
		<th>{similar.L_REPLIES}</th>
		<th>{similar.L_LAST_POST}</th>
	</tr>
	<!-- BEGIN topics -->
	<tr>
		<td class="row1 row-center"><img src="{similar.topics.FOLDER}" alt="{similar.topics.ALT}" title="{similar.topics.ALT}" /></td>
		<td class="row1h row-forum">
			{similar.topics.NEWEST}<span class="topiclink">{similar.topics.TYPE} {similar.topics.TITLE}</span>
			<!-- BEGIN desc -->
			<!-- <br /><span class="gensmall">{similar.topics.desc.TOPIC_DESC}</span> -->
			<!-- END desc -->
		</td>
		<td class="row1 row-center" width="12%">{similar.topics.AUTHOR}</td>
		<td class="row1 row-center"><b>{similar.topics.FORUM}</b></td>
		<td class="row1 row-center" width="10%"><span class="genmed">{similar.topics.REPLIES}</span></td>
		<td class="row1 row-center" width="18%" nowrap="nowrap"><span class="genmed">{similar.topics.POST_TIME} {similar.topics.POST_URL}</span></td>
	</tr>
	<!-- END topics -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<script type="text/javascript">
<!--
tmp = 'similar_block';
if(GetCookie(tmp) == '2')
{
	ShowHide('similar_block', 'similar_block_h', 'similar_block');
}
//-->
</script>
<!-- END similar -->