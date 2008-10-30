{IMG_THL}{IMG_THC}<span class="forumlink">{TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0">
<!-- BEGIN kb_list -->
<tr>
	<th colspan="2">{L_ARTICLES}</th>
	<th width="150">{L_AUTHOR}</th>
	<th width="150">{L_TIME}</th>
	<!-- BEGIN rating_switch -->
	<th width="100">{L_RATING}</th>
	<!-- END rating_switch -->
	<th width="150">&nbsp;&nbsp;&nbsp;&nbsp;{L_OPTIONS}&nbsp;&nbsp;&nbsp;&nbsp;</th>
</tr>
<!-- BEGIN kb_articles -->
<tr>
	<td class="row1h{kb_articles.XS_NEW} row-forum" width="100%" onclick="window.location.href='{kb_articles.U_VIEW_ARTICLE}'" colspan="2">
		<span class="topiclink{kb_articles.XS_NEW}">
			&nbsp;<img src="{MINIPOST_IMG}" border="0" />&nbsp;<a href="{kb_articles.U_VIEW_ARTICLE}" class="{kb_articles.CLASS}">{kb_articles.TOPIC_TITLE}</a>
		</span><br />
		<span class="gensmall">&nbsp;{kb_articles.TOPIC_DESC}</span>

	</td>
	<td class="row3 row-center-small" nowrap="nowrap">{kb_articles.POSTER_CG}</td>
	<td class="row3 row-center-small" style="padding-top: 0;" nowrap="nowrap">{kb_articles.TIME}</td>
	<!-- BEGIN rate_switch_msg -->
	<td class="row3 row-center-small"><img src="images/rates/rate_{kb_articles.TOPIC_RATING}.png"></td>
	<!-- END rate_switch_msg -->
	<td class="row3 row-center-small">
		<a href="{kb_articles.U_VIEW_COMMENTS}"><img src="{ARTICLE_COMMENTS_IMG}" alt="{L_VIEW_COMMENTS}:&nbsp;{kb_articles.REPLIES}" title="{L_VIEW_COMMENTS}:&nbsp;{kb_articles.REPLIES}" border="0" align="middle" /></a>
		<a href="{kb_articles.U_POST_COMMENT}"><img src="{ARTICLE_REPLY_IMG}" alt="{L_REPLY_ARTICLE}" title="{L_REPLY_ARTICLE}" border="0" align="middle" /></a>
		<a href="{kb_articles.U_PRINT_TOPIC}" target="_blank"><img src="{ARTICLE_PRINT_IMG}" alt="{L_PRINT_ARTICLE}" title="{L_PRINT_ARTICLE}" border="0" align="middle" /></a>
		<a href="{kb_articles.U_EMAIL_TOPIC}"><img src="{ARTICLE_EMAIL_IMG}" alt="{L_EMAIL_ARTICLE}" title="{L_EMAIL_ARTICLE}" border="0" align="middle" /></a>
	</td>
</tr>
<!-- BEGIN switch_no_topics -->
<tr><td class="row1 row-center" colspan="5" height="30"><span class="gen">{L_NO_TOPICS}</span></td></tr>
<!-- END switch_no_topics -->

<!-- END kb_articles -->

<tr>
	<td class="cat" valign="middle" colspan="5">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td valign="middle" align="right" width="100%"><span class="genmed">{S_TIMEZONE}&nbsp;</span></td>
		</tr>
		</table>
	</td>
</tr>
<!-- END kb_list -->

<!-- BEGIN kb_article -->
<!-- {IMG_THL}{IMG_THC}<span class="forumlink">{TITLE}</span>{IMG_THR}
<table cellspacing="0" width="100%" class="forumlinenb"> -->
<tr><th>{TOPIC_DESC}</th></tr>
<tr><td class="row-post"><div class="post-text">{TEXT}</div><br /><br /></td></tr>
<tr>
	<td class="cat">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td align="left"><span class="gensmall">&nbsp;<b>{L_AUTHOR}</b>:&nbsp;{POSTER_CG}&nbsp;[{TIME}]</span></td>
				<td align="right">
					<a href="{U_VIEW_COMMENTS}"><img src="{ARTICLE_COMMENTS_IMG}" alt="{L_VIEW_COMMENTS}:&nbsp;{REPLIES}" title="{L_VIEW_COMMENTS}:&nbsp;{REPLIES}" border="0" align="middle" /></a>
					<a href="{U_POST_COMMENT}"><img src="{ARTICLE_REPLY_IMG}" alt="{L_REPLY_ARTICLE}" title="{L_REPLY_ARTICLE}" border="0" align="middle" /></a>
					<a href="{U_PRINT_TOPIC}" target="_blank"><img src="{ARTICLE_PRINT_IMG}" alt="{L_PRINT_ARTICLE}" title="{L_PRINT_ARTICLE}" border="0" align="middle" /></a>
					<a href="{U_EMAIL_TOPIC}"><img src="{ARTICLE_EMAIL_IMG}" alt="{L_EMAIL_ARTICLE}" title="{L_EMAIL_ARTICLE}" border="0" align="middle" /></a>
					&nbsp;
				</td>
			</tr>
		</table>
	</td>
</tr>
<!-- END kb_article -->

<!-- BEGIN cat_row -->
<tr><th nowrap="nowrap"><b>{cat_row.CAT_ITEM}</b></th></tr>
<!-- BEGIN menu_row -->
<tr>
	<td class="row1h{cat_row.menu_row.XS_NEW} row-left" onclick="window.location.href='{cat_row.menu_row.MENU_LINK}'" height="46">
		<table width="100%" cellspacing="0" cellpadding="2" border="0">
		<tbody>
		<tr>
			<td align="center" style="padding:3px;">{cat_row.menu_row.MENU_ICON}</td>
			<td width="100%">
				<span class="forumlink">&nbsp;<a href="{cat_row.menu_row.MENU_LINK}">{cat_row.menu_row.MENU_ITEM}</a></span><br />
				<span class="genmed">&nbsp;{cat_row.menu_row.MENU_DESC}</span>
			</td>
		</tr>
		</tbody>
		</table>
	</td>
</tr>
<!-- END menu_row -->
<!-- END cat_row -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}