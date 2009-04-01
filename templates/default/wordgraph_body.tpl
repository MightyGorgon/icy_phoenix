<div id="wordgraph_table_h" style="display: none;">
	{IMG_THL}{IMG_THC}<span class="forumlink">{L_WORDGRAPH}</span>&nbsp;<b>[<a href="javascript:ShowHide('wordgraph_table','wordgraph_table_h','wordgraph_table');" title="{L_SHOW}">{L_SHOW}</a>]</b>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr><td class="row1g row-center">&nbsp;</td></tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="wordgraph_table">
	{IMG_THL}{IMG_THC}<span class="forumlink">{L_WORDGRAPH}</span>&nbsp;<b>[<a href="javascript:ShowHide('wordgraph_table','wordgraph_table_h','wordgraph_table');" title="{L_HIDE}">{L_HIDE}</a>]</b>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1g row-center">
			<!-- BEGIN wordgraph_loop -->
			<span style="font-size: {wordgraph_loop.WORD_FONT_SIZE}px;"><a href="{wordgraph_loop.WORD_SEARCH_URL}" style="font-size: {wordgraph_loop.WORD_FONT_SIZE}px;">{wordgraph_loop.WORD}</a></span>
			<!-- END wordgraph_loop -->
		</td>
	</tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<script type="text/javascript">
<!--
tmp = 'wordgraph_table';
if(GetCookie(tmp) == '2')
{
	ShowHide('wordgraph_table', 'wordgraph_table_h', 'wordgraph_table');
}
//-->
</script>