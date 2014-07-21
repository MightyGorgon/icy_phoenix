<div id="wordgraph_block_h" style="display: none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('wordgraph_block','wordgraph_block_h','wordgraph_block');" alt="{L_SHOW}" /><span class="forumlink">{L_WORDGRAPH}</span>{IMG_THR}<table class="forumlinenb">
<tr><td class="row1g row-center">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="wordgraph_block">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('wordgraph_block','wordgraph_block_h','wordgraph_block');" alt="{L_HIDE}" /><span class="forumlink">{L_WORDGRAPH}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<td class="row1g row-center">
		<!-- BEGIN wordgraph_loop -->
		<span style="font-size: {wordgraph_loop.WORD_FONT_SIZE}"><a href="{wordgraph_loop.WORD_SEARCH_URL}" style="font-size: {wordgraph_loop.WORD_FONT_SIZE}px">{wordgraph_loop.WORD}</a></span>
		<!-- END wordgraph_loop -->
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<script type="text/javascript">
<!--
tmp = 'wordgraph_block';
if(GetCookie(tmp) == '2')
{
	ShowHide('wordgraph_block', 'wordgraph_block_h', 'wordgraph_block');
}
//-->
</script>