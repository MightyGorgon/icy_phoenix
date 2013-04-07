<!-- INCLUDE overall_header.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{NEWS_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%">
		<!-- BEGIN pagination -->
		<div style="float: right; text-align: right; padding: 10px; margin: 10px 0 10px 20px; clear: both;">{pagination.PAGINATION}</div>
		<!-- END pagination -->
		<a href="{INDEX_FILE}">{L_INDEX}</a> |
		<a href="{INDEX_FILE}?news=categories">{L_CATEGORIES}</a> |
		<a href="{INDEX_FILE}?news=archives">{L_ARCHIVES}</a>
		<br /><br />
		<!-- BEGIN categories -->
		<div style="border: #DDDDDD solid 1px;float: left; padding: 10px; margin: 10px;">
			<a href="{INDEX_FILE}?cat_id={categories.ID}"><img style="border: 0" src="{categories.IMAGE}" alt="{articles.TITLE}" /></a>
		</div>
		<br />
		<!-- END categories -->
		<!-- BEGIN arch -->
		<ul style="margin: 10px 0;">
			<!-- BEGIN year -->
			<li class="gen"><a href="{INDEX_FILE}?news=archives&amp;year={arch.year.YEAR}">{arch.year.YEAR}</a></li>
			<!-- BEGIN month -->
			<li class="gen" style="margin-left: 1em;"><a href="{INDEX_FILE}?news=archives&amp;year={arch.year.YEAR}&amp;month={arch.year.month.MONTH}">{arch.year.month.L_MONTH} {arch.year.month.POST_COUNT}</a></li>
			<!-- BEGIN day -->
			<li class="gen" style="margin-left: 2em;"><a href="{INDEX_FILE}?news=archives&amp;year={arch.year.YEAR}&amp;month={arch.year.month.MONTH}&amp;day={arch.year.month.day.DAY}">{arch.year.month.day.L_DAY} {arch.year.month.day.POST_COUNT}</a></li>
			<!-- END day -->
			<!-- END month -->
			<!-- END year -->
		</ul>
		<!-- END arch -->
		<!-- BEGIN articles -->
		<br />
		<div style="border: #dddddd solid 1px; padding: 10px; margin-bottom: 10px; clear: both;">
			<div style="float: right; padding: 5px; margin: 5px;">
				<a href="{INDEX_FILE}?cat_id={articles.CAT_ID}"><img src="{articles.CAT_IMG}" alt="{articles.CATEGORY}" style="border: 0" /></a>
			</div>
			<div class="forumlink"><a href="{INDEX_FILE}?topic_id={articles.ID}">{articles.L_TITLE}</a></div>
			<div class="post-details">{articles.POST_DATE} by {articles.L_POSTER} | <a href="{articles.U_COMMENTS}">{articles.L_COMMENTS}</a></div>
			<hr />
			<div class="post-text post-text-hide-flow">{articles.BODY}{articles.ATTACHMENTS}{articles.READ_MORE_LINK}</div>
		</div>
		<br />
		<!-- END articles -->
		<!-- BEGIN comments -->
		<hr />
		<br />
		<div class="forumline" style="padding: 10px; margin: 10px 0 10px 20px; clear: both;">
			<div class="forumlink">{comments.L_TITLE}</div>
			<div class="post-details">{comments.POST_DATE} by {comments.L_POSTER}</div>
			<hr />
			<div class="post-text post-text-hide-flow">{comments.BODY}</div>
		</div>
		<br />
		<!-- END comments -->
		<!-- BEGIN pagination -->
		<hr />
		<div style="text-align: right; padding: 10px; margin: 10px 0 10px 20px; clear: both;">{pagination.PAGINATION}</div>
		<!-- END pagination -->
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- INCLUDE overall_footer.tpl -->