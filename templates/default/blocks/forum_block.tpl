<!-- BEGIN fetchpost_row -->
{IMG_THL}{IMG_THC}<a href="{fetchpost_row.U_VIEW_COMMENTS}" class="forumlink">{fetchpost_row.TITLE}</a>&nbsp;{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="row-post">
		<div class="post-text">{fetchpost_row.TEXT}</div><br /><br />
		<span class="gensmall">{fetchpost_row.OPEN}<a href="{fetchpost_row.U_READ_FULL}">{fetchpost_row.L_READ_FULL}</a>{fetchpost_row.CLOSE}</span>
		<br /><br />
		<center>&nbsp;{fetchpost_row.ATTACHMENTS}&nbsp;</center>
	</td>
</tr>
<tr>
	<td class="cat">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td align="left"><span class="gensmall">&nbsp;{fetchpost_row.POSTER_CG}&nbsp;[ {fetchpost_row.TIME} ]</span></td>
				<td align="right">
					<!-- <img src="{IMG_CLOCK}" alt="" title="" border="0" align="middle" />&nbsp; -->
					<a href="{fetchpost_row.U_POST_COMMENT}"><img src="{NEWS_REPLY_IMG}" alt="{L_REPLY_NEWS}" title="{L_REPLY_NEWS}" border="0" align="middle" /></a>
					<a href="{fetchpost_row.U_PRINT_TOPIC}" target="_blank"><img src="{NEWS_PRINT_IMG}" alt="{L_PRINT_NEWS}" title="{L_PRINT_NEWS}" border="0" align="middle" /></a>
					<a href="{fetchpost_row.U_EMAIL_TOPIC}"><img src="{NEWS_EMAIL_IMG}" alt="{L_EMAIL_NEWS}" title="{L_EMAIL_NEWS}" border="0" align="middle" /></a>
					&nbsp;
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END fetchpost_row -->
