<!-- INCLUDE overall_header.tpl -->

<!-- BEGIN recent_pics_block -->
<table class="forumline" width="100%" cellspacing="0">
<tr><td class="row2" align="left" colspan="{S_COLS}" nowrap="nowrap"><span class="cattitle">&nbsp;{L_RECENT_PUBLIC_PICS}</span></td></tr>
<!-- BEGIN no_pics -->
<tr><td class="row1 row-center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN recent_pics -->
<tr>
<!-- BEGIN recent_col -->
<td class="row1_no_trans" width="{S_COL_WIDTH}" align="center">{recent_pics_block.recent_pics.recent_col.SS}</td>
<!-- END recent_col -->
</tr>
<tr>
<!-- BEGIN recent_detail -->
	<td class="row1"><span class="gensmall">{recent_pics_block.recent_pics.recent_detail.TITLE}{L_FILE_DESC}: {recent_pics_block.recent_pics.recent_detail.DESC}<br />
	{L_POSTER}: {recent_pics_block.recent_pics.recent_detail.POSTER}, {L_VIEW}: {recent_pics_block.recent_pics.recent_detail.VIEW}<br />{L_POSTED}: {recent_pics_block.recent_pics.recent_detail.TIME}<br />
	{recent_pics_block.recent_pics.recent_detail.RATING}{recent_pics_block.recent_pics.recent_detail.COMMENTS}</span>
</td>
<!-- END recent_detail -->
</tr>
<!-- END recent_pics -->
</table>
<br />
<!-- END recent_pics_block -->

<!-- BEGIN most_pics_block -->
<table class="forumline" width="100%" cellspacing="0">
<tr><td class="row2" height="25" align="left" colspan="{S_COLS}" nowrap="nowrap"><span class="cattitle">&nbsp;{L_MOST_PUBLIC_PICS}</span></td></tr>
<!-- BEGIN no_pics -->
<tr><td class="row1 row-center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN most_pics -->
<tr>
<!-- BEGIN most_col -->
<td class="row1_no_trans" width="{S_COL_WIDTH}" align="center">{most_pics_block.most_pics.most_col.SS}</td>
<!-- END most_col -->
</tr>
<tr>
<!-- BEGIN most_detail -->
	<td class="row1"><span class="gensmall">{most_pics_block.most_pics.most_detail.TITLE}{L_FILE_DESC}: {most_pics_block.most_pics.most_detail.DESC}<br />
	{L_POSTER}: {most_pics_block.most_pics.most_detail.POSTER}, {L_VIEW}: {most_pics_block.most_pics.most_detail.VIEW}<br />{L_POSTED}: {most_pics_block.most_pics.most_detail.TIME}<br />
	{most_pics_block.most_pics.most_detail.RATING}{most_pics_block.most_pics.most_detail.COMMENTS}</span>
</td>
<!-- END most_detail -->
</tr>
<!-- END most_pics -->
</table>
<br />
<!-- END most_pics_block -->

<!-- BEGIN highest_pics_block -->
<table class="forumline" width="100%" cellspacing="0">
<tr><td class="row2" height="25" colspan="{S_COLS}" align="left" nowrap="nowrap"><span class="cattitle">&nbsp;{L_TOPRATED_PUBLIC_PICS}</span></td></tr>
<!-- BEGIN no_pics -->
<tr><td class="row1 row-center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN highest_pics -->
<tr>
<!-- BEGIN highest_col -->
<td class="row1_no_trans" width="{S_COL_WIDTH}" align="center">{highest_pics_block.highest_pics.highest_col.SS}</td>
<!-- END highest_col -->
</tr>
<tr>
<!-- BEGIN highest_detail -->
	<td class="row1"><span class="gensmall">{highest_pics_block.highest_pics.highest_detail.TITLE}{L_FILE_DESC}: {highest_pics_block.highest_pics.highest_detail.DESC}<br />
		{L_POSTER}: {highest_pics_block.highest_pics.highest_detail.POSTER}, {L_VIEW}: {highest_pics_block.highest_pics.highest_detail.VIEW}<br />{L_POSTED}: {highest_pics_block.highest_pics.highest_detail.TIME}<br />
		{highest_pics_block.highest_pics.highest_detail.RATING}{highest_pics_block.highest_pics.highest_detail.COMMENTS}</span>
	</td>
<!-- END highest_detail -->
</tr>
<!-- END highest_pics -->
</table>
<br />
<!-- END highest_pics_block -->

<!-- BEGIN random_pics_block -->
<table class="forumline" width="100%" cellspacing="0">
<tr><td class="row2" height="25" align="left" colspan="{S_COLS}" nowrap="nowrap"><span class="cattitle">&nbsp;{L_RANDOM_PUBLIC_PICS}</span></td></tr>
<!-- BEGIN no_pics -->
<tr><td class="row1 row-center" colspan="{S_COLS}" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->
<!-- BEGIN rand_pics -->
<tr>
<!-- BEGIN rand_col -->
<td class="row1_no_trans" width="{S_COL_WIDTH}" align="center">{random_pics_block.rand_pics.rand_col.SS}</td>
<!-- END rand_col -->
</tr>
<tr>
<!-- BEGIN rand_detail -->
	<td class="row1"><span class="gensmall">{random_pics_block.rand_pics.rand_detail.TITLE}{L_FILE_DESC}: {random_pics_block.rand_pics.rand_detail.DESC}<br />
	{L_POSTER}: {random_pics_block.rand_pics.rand_detail.POSTER}, {L_VIEW}: {random_pics_block.rand_pics.rand_detail.VIEW}<br />{L_POSTED}: {random_pics_block.rand_pics.rand_detail.TIME}<br />
	{random_pics_block.rand_pics.rand_detail.RATING}{random_pics_block.rand_pics.rand_detail.COMMENTS}</span>
</td>
<!-- END rand_detail -->
</tr>
<!-- END rand_pics -->
</table>
<br />
<!-- END random_pics_block -->

<!-- INCLUDE overall_footer.tpl -->
