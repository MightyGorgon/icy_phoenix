<!-- BEGIN fetchpost_row -->
<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row-header"><span class="genmed"><b>{L_ANNOUNCEMENT}: {fetchpost_row.TITLE}</b></span></td></tr>
<tr><td class="row2" align="left"><span class="gensmall">{L_POSTED}: <b>{fetchpost_row.POSTER}</b> @ {fetchpost_row.TIME}</span></td></tr>
<tr>
	<td class="row1">
		<div class="post-text-container"><div class="post-text">{fetchpost_row.TEXT}</div></div><br /><br />
		<div class="post-text">{fetchpost_row.OPEN}<a href="{fetchpost_row.U_READ_FULL}">{fetchpost_row.L_READ_FULL}</a>{fetchpost_row.CLOSE}</div>
	</td>
</tr>
<tr>
	<td class="row3" height="24">
		<span class="gensmall">{L_COMMENTS}: {fetchpost_row.REPLIES} :: <a href="{fetchpost_row.U_VIEW_COMMENTS}">{L_VIEW_COMMENTS}</a> (<a href="{fetchpost_row.U_POST_COMMENT}">{L_POST_COMMENT}</a>)</span>
	</td>
</tr>
</table>
<br />
<!-- END fetch_post_row -->