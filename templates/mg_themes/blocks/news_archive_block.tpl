<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
		<!-- BEGIN news_categories -->
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<!-- END news_categories -->
		<!-- BEGIN no_news -->
		<tr><td class="row1 row-center" height="50"><span class="gen">{L_NO_NEWS_CATS}</span></td></tr>
		<!-- END no_news -->
		<!-- BEGIN newsrow -->
		<tr>
			<td class="row1 row-center">
				<span class="genmed">
					<a href="{INDEX_FILE}?{PORTAL_PAGE_ID}cat_id={newsrow.ID}"><img src="{newsrow.THUMBNAIL}" {newsrow.IMG_W} alt="{newsrow.DESC}" title="{newsrow.DESC}" vspace="10" /></a>
				</span>
			</td>
			<td class="row1" valign="middle">&nbsp;<span class="forumlink"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}cat_id={newsrow.ID}">{newsrow.NEWSCAT}</a></span></td>
		</tr>
		<!-- END newsrow -->
		<!-- BEGIN yes_news -->
		<tr><td class="row1 row-center" colspan="2"><span class="genmed"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}">{L_ALL_NEWS_CATEGORIES}</a></span></td></tr>
		<!-- END yes_news -->
		<!-- BEGIN news_categories -->
		</table>
		<!-- END news_categories -->

		<!-- BEGIN news_archives -->
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
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
		<tr><td class="row1 row-center" colspan="2"><span class="genmed"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}">{L_ALL_NEWS_ARCHIVES}</a></span></td></tr>
		</table>
		<!-- END news_archives -->

		<!-- BEGIN no_articles -->
		<table class="empty-table" width="100%" cellspacing="1" cellpadding="3" border="0">
		<tr><td class="row1 row-center" height="50"><span class="gen">{L_NO_NEWS}</span></td></tr>
		</table>
		<!-- END no_articles -->
	</td>
</tr>
</table>