<table>
<tr>
	<td>
		<!-- BEGIN news_categories -->
		<table>
		<!-- END news_categories -->
		<!-- BEGIN no_news -->
		<tr><td class="row1 row-center th50px"><span class="gen">{L_NO_NEWS_CATS}</span></td></tr>
		<!-- END no_news -->
		<!-- BEGIN newsrow -->
		<tr>
			<td class="row1 row-center"><span class="genmed"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}cat_id={newsrow.ID}"><img src="{newsrow.THUMBNAIL}" alt="{newsrow.DESC}" title="{newsrow.DESC}" style="padding: 3px;{newsrow.IMG_W}" /></a></span></td>
			<td class="row1 tvalignm">&nbsp;<span class="forumlink"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}cat_id={newsrow.ID}">{newsrow.NEWSCAT}</a></span></td>
		</tr>
		<!-- END newsrow -->
		<!-- BEGIN yes_news -->
		<tr><td class="row1 row-center" colspan="2"><span class="genmed"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}">{L_ALL_NEWS_CATEGORIES}</a></span></td></tr>
		<!-- END yes_news -->
		<!-- BEGIN news_categories -->
		</table>
		<!-- END news_categories -->

		<!-- BEGIN news_archives -->
		<table>
		<tr>
			<td class="row1">
		<!-- END news_archives -->

			<!-- BEGIN arch -->
			<!-- BEGIN year -->
			<ul style="list-style-type: disc; padding-left: 15px; margin-left: 5px;" id="ny_{arch.year.YEAR}">
			<li class="gen">
			<a href="{INDEX_FILE}?{PORTAL_PAGE_ID}news=archives&amp;year={arch.year.YEAR}">{arch.year.YEAR}</a>
				<!-- BEGIN month -->
				<ul style="list-style-type: square; margin-left: 10px;" id="nm_{arch.year.YEAR}_{arch.year.month.MONTH}">
				<li class="gen">
				<a href="{INDEX_FILE}?{PORTAL_PAGE_ID}news=archives&amp;year={arch.year.YEAR}&amp;month={arch.year.month.MONTH}">{arch.year.month.L_MONTH} {arch.year.month.POST_COUNT} </a>
					<!-- BEGIN day -->
					<ul style="list-style-type: circle; margin-left: 10px;" id="nd_{arch.year.YEAR}_{arch.year.month.MONTH}_{arch.year.month.day.DAY}">
					<li class="gen"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}news=archives&amp;year={arch.year.YEAR}&amp;month={arch.year.month.MONTH}&amp;day={arch.year.month.day.DAY}">{arch.year.month.day.L_DAY3} {arch.year.month.day.L_DAY2} {arch.year.month.day.L_DAY} {arch.year.month.day.POST_COUNT}</a></li>
					</ul>
					<!-- END day -->
				</li>
				</ul>
				<!-- END month -->
			</li>
			</ul>
			<!-- END year -->
			<!-- END arch -->

		<!-- BEGIN news_archives -->
			</td>
		</tr>
		<tr><td class="row1 row-center" colspan="2"><span class="genmed"><a href="{INDEX_FILE}?{PORTAL_PAGE_ID}">{L_ALL_NEWS_ARCHIVES}</a></span></td></tr>
		</table>
		<!-- END news_archives -->

		<!-- BEGIN no_articles -->
		<div style="width: 100%; text-align: center;; padding-top: 10px; padding-bottom: 10px;">{L_NO_NEWS}</div>
		<!-- END no_articles -->
	</td>
</tr>
</table>