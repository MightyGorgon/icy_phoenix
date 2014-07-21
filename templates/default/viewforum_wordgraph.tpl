<!-- BEGIN forum_wordgraph -->
<div id="wordgraph_table_h" style="display: none;">
	{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('wordgraph_table','wordgraph_table_h','wordgraph_table');" alt="{L_SHOW}" /><span class="forumlink">{forum_wordgraph.L_WORDGRAPH}</span>{IMG_THR_ALT}<table class="forumlinenb">
	<tr><td class="row1g row-center">&nbsp;</td></tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="wordgraph_table">
	{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('wordgraph_table','wordgraph_table_h','wordgraph_table');" alt="{L_HIDE}" /><span class="forumlink">{forum_wordgraph.L_WORDGRAPH}</span>{IMG_THR}<table class="forumlinenb">
	<tr>
		<td class="row1g row-center">
			<!-- BEGIN wordgraph_loop -->
			<span style="font-size: {forum_wordgraph.wordgraph_loop.WORD_FONT_SIZE}"><a href="{forum_wordgraph.wordgraph_loop.WORD_SEARCH_URL}" style="font-size: {forum_wordgraph.wordgraph_loop.WORD_FONT_SIZE}px">{forum_wordgraph.wordgraph_loop.WORD}</a></span>
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
<!-- END forum_wordgraph -->