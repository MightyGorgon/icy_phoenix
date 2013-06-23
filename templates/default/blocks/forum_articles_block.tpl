<!-- IF IS_LIST -->
{IMG_THL}{IMG_THC}{L_TITLE}&nbsp;{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{L_TOPICS}</th>
	<th>{L_AUTHOR}</th>
	<th width="50">{L_VIEWS}</th>
</tr>
<!-- BEGIN articles_fa -->
<tr>
	<td class="row1h row-forum" data-href="{articles_fa.U_VIEW_TOPIC}">
		<div class="topic-title-hide-flow"><span class="topiclink"><a href="{articles_fa.U_VIEW_TOPIC}">{articles_fa.TITLE}</a></span></div>
	</td>
	<td class="row3 row-center" nowrap="nowrap" style="padding-top: 0; padding-left: 2px; padding-right: 2px;">{articles_fa.TIME}<br />{articles_fa.POSTER_CG}</td>
	<td class="row2 row-center">{articles_fa.VIEWS}</td>
</tr>
<!-- END articles_fa -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- ELSE -->
<!-- BEGIN articles_fa -->
{IMG_THL}{IMG_THC}{articles_fa.TITLE}&nbsp;{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="row-post">
		<div class="post-text">{articles_fa.TEXT}</div><br /><br />
		<center>&nbsp;{articles_fa.ATTACHMENTS}&nbsp;</center>
	</td>
</tr>
<tr>
	<td class="cat">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td align="left"><span class="gensmall">&nbsp;{articles_fa.POSTER_CG}&nbsp;[ {articles_fa.TIME} ]</span></td>
				<td align="right">
					<a href="{articles_fa.U_PRINT_TOPIC}" target="_blank"><img src="{NEWS_PRINT_IMG}" alt="{L_PRINT_NEWS}" title="{L_PRINT_NEWS}" border="0" align="middle" /></a>
					<a href="{articles_fa.U_EMAIL_TOPIC}"><img src="{NEWS_EMAIL_IMG}" alt="{L_EMAIL_NEWS}" title="{L_EMAIL_NEWS}" border="0" align="middle" /></a>
					&nbsp;
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END articles_fa -->
<!-- ENDIF IS_LIST -->

