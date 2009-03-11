<!-- BEGIN no_pics -->
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="center" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
</table>
<!-- END no_pics -->
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN recent_pics -->
<tr>
	<!-- BEGIN recent_detail -->
	<td class="row1 row-center" width="{S_COL_WIDTH}">
		<span class="genmed" style="line-height:150%">
			<b>{recent_pics.recent_detail.TITLE}</b><br />
			<a href="{recent_pics.recent_detail.U_PIC}" {TARGET_BLANK}><img src="{recent_pics.recent_detail.THUMBNAIL}" alt="{recent_pics.recent_detail.TITLE}" title="{recent_pics.recent_detail.TITLE}" /></a><br />
			<b>{recent_pics.recent_detail.POSTER}</b><br />
			{recent_pics.recent_detail.TIME}
		</span>
		<br /><br />
	</td>
	<!-- END recent_detail -->
	<!-- BEGIN recent_no_detail -->
	<td class="row1 row-center"><span class="genmed" style="line-height: 150%">&nbsp;</span><br /><br /></td>
	<!-- END recent_no_detail -->
</tr>
<!-- END recent_pics -->
</table>
