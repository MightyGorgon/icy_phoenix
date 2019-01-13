<table>
<tr>
	<td>
		<!-- BEGIN news_categories -->
		<table class="forumlinenb">
		<tr><td class="row-header" colspan="{S_COLS}" nowrap="nowrap"><span>{L_NEWS_CATS}</span></td></tr>
		<!-- END news_categories -->
		<!-- BEGIN no_news -->
		<tr><td class="row1 row-center th50px"><span class="gen">{L_NO_NEWS_CATS}</span></td></tr>
		<!-- END no_news -->

		<!-- BEGIN newsrow -->
		<tr>
			<!-- BEGIN newscol -->
			<td width="25%" class="row1 row-center"><span class="genmed"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}cat_id={newsrow.newscol.ID}"><!-- IF newsrow.newscol.THUMBNAIL --><img class="vs10px" src="{newsrow.newscol.THUMBNAIL}" alt="{newsrow.newscol.DESC}" title="{newsrow.newscol.DESC}" /><!-- ELSE -->&nbsp;<!-- ENDIF --></a></span></td>
			<!-- END newscol -->
		</tr>
		<tr>
			<!-- BEGIN news_detail -->
			<td class="row1g-left row-center"><span class="forumlink">{newsrow.news_detail.NEWSCAT}</span></td>
			<!-- END news_detail -->
		</tr>
		<!-- END newsrow -->

		<!-- BEGIN news_categories -->
		</table>
		<br />
		<!-- END news_categories -->

		<!-- BEGIN news_archives -->
		{IMG_TBL}<table class="forumline">
		<tr><td class="row-header"><span>{L_NEWS_ARCHIVES}</span></td></tr>
		<tr>
			<td class="row1">
		<!-- END news_archives -->
			<!-- BEGIN arch -->
				<ul style=" padding: 0 1.3em; margin: 5px 10px;">
				<!-- BEGIN year -->
					<li class="gen"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}news=archives&amp;year={arch.year.YEAR}">{arch.year.YEAR}</a></li>
					<!-- BEGIN month -->
					<li class="gen" style="margin-left: 1em;"> <a href="{INDEX_FILE}?{PORTAL_PAGE_ID}news=archives&amp;year={arch.year.YEAR}&amp;month={arch.year.month.MONTH}">{arch.year.month.L_MONTH} {arch.year.month.POST_COUNT} </a></li>
					<!-- BEGIN day -->
					<li class="gen" style="margin-left: 2em;"> <a href="{INDEX_FILE}?{PORTAL_PAGE_ID}news=archives&amp;year={arch.year.YEAR}&amp;month={arch.year.month.MONTH}&amp;day={arch.year.month.day.DAY}">{arch.year.month.day.L_DAY3} {arch.year.month.day.L_DAY2} {arch.year.month.day.L_DAY} {arch.year.month.day.POST_COUNT}</a></li>
					<!-- END day -->
					<!-- END month -->
				<!-- END year -->
				</ul>
			<!-- END arch -->
		<!-- BEGIN news_archives -->
			</td>
		</tr>
		</table>{IMG_TBR}
		<br />
		<!-- END news_archives -->

		<!-- BEGIN no_articles -->
		<table class="forumline">
		<tr><td class="row1 row-center th50px"><span class="gen">{L_NO_NEWS}</span></td></tr>
		</table>
		<br />
		<!-- END no_articles -->
		<!-- BEGIN articles -->
		{IMG_THL}{IMG_THC}<a href="{articles.U_COMMENT}" class="forumlink">{articles.L_FULL_TITLE}</a>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="2" border="0">
		<tr>
			<th align="left" colspan="2"><span class="gensmall"><a href="{articles.U_COMMENT}"><img src="{MINIPOST_IMG}" alt="{articles.L_TITLE}" /></a>&nbsp;{L_POSTED}&nbsp;{L_WORD_ON}&nbsp;{articles.POST_DATE}&nbsp;{L_BY}&nbsp;{articles.L_POSTER}</span></th>
		</tr>
		<tr>
			<td class="row-post" style="border-right-width: 0px;">
				<div style="padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: center;"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}cat_id={articles.CAT_ID}" title ="{articles.CATEGORY}"><!-- IF articles.CAT_IMG --><img src="{articles.CAT_IMG}" alt="{articles.CATEGORY}" /><!-- ELSE -->{articles.CATEGORY}<!-- ENDIF --></a></div>
			</td>
			<td class="row-post" style="border-left-width: 0px; width: 100%;">
				<div class="post-text" style="padding: 2px;">{articles.BODY}</div>
				<div class="content-padding">{articles.ATTACHMENTS}</div><br /><br />
				<span class="gensmall">{articles.READ_MORE_LINK}&nbsp;</span><br /><br />
				<!-- IF articles.S_TOPIC_TAGS and articles.TOPIC_TAGS -->
				<br class="clear" /><br /><div><span class="gensmall"><b>{L_TOPIC_TAGS}</b>:&nbsp;{articles.TOPIC_TAGS}</span></div><br />
				<!-- ENDIF -->
			</td>
		</tr>
		<tr>
			<td class="cat" colspan="2">
				<span style="float: right; text-align: right; padding-right: 5px; padding-top: 5px;"><!-- IF not S_BOT --><a href="{articles.U_POST_COMMENT}"><img src="{NEWS_REPLY_IMG}" alt="{L_REPLY_NEWS}" title="{L_REPLY_NEWS}" /></a>&nbsp;<a href="{articles.U_PRINT_TOPIC}" target="_blank"><img src="{NEWS_PRINT_IMG}" alt="{L_PRINT_NEWS}" title="{L_PRINT_NEWS}" /></a>&nbsp;<a href="{articles.U_EMAIL_TOPIC}"><img src="{NEWS_EMAIL_IMG}" alt="{L_EMAIL_NEWS}" title="{L_EMAIL_NEWS}" /></a><!-- ELSE -->&nbsp;<!-- ENDIF --></span>
				<div class="gensmall" style="text-align: left; padding-left: 5px; padding-top: 5px;">{L_NEWS_SUMMARY}&nbsp;<!-- IF S_NEWS_VIEWS --><a href="{articles.U_VIEWS}"><!-- ENDIF --><b>{articles.COUNT_VIEWS}</b>&nbsp;{L_NEWS_VIEWS}<!-- IF S_NEWS_VIEWS --></a><!-- ENDIF -->&nbsp;{L_NEWS_AND}&nbsp;<!-- IF not S_BOT --><a href="{articles.U_COMMENT}" title="{articles.L_TITLE}"><!-- ENDIF --><b>{articles.COUNT_COMMENTS}</b>&nbsp;{L_NEWS_COMMENTS}<!-- IF not S_BOT --></a><!-- ENDIF --></div>
			</td>
		</tr>
		</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		<!-- END articles -->
		<!-- BEGIN comments -->
		{IMG_TBL}<table class="forumline">
		<tr><th align="left"><span class="gensmall"><span style="float: right; text-align: right;">{comments.POST_DATE} {L_BY} {comments.L_POSTER}</span>{comments.L_TITLE}</span></th></tr>
		<tr><td class="row-post tw100pct"><div class="post-text">{comments.BODY}</div></td></tr>
		</table>{IMG_TBR}
		<!-- END comments -->
		<!-- BEGIN pagination -->
		<div style="text-align: right; padding: 10px; margin: 10px 0 10px 20px; clear: both;"><span class="pagination">{pagination.PAGINATION}</span></div>
		<!-- END pagination -->
	</td>
</tr>
</table>