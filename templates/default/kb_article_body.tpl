{IMG_THL}{IMG_THC}<span class="forumlink">{ARTICLE_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row-post">
		<span class="post-text">
			<b>{L_ARTICLE_AUTHOR}: </b>{ARTICLE_AUTHOR}<br />
			<b>{L_ARTICLE_DATE}: </b>{ARTICLE_DATE}<br />
			<b>{L_ARTICLE_CATEGORY}: </b>{ARTICLE_CATEGORY}<br />
			<b>{L_ARTICLE_TYPE}: </b>{ARTICLE_TYPE}<br />
		</span>
		<!-- BEGIN custom_field -->
		<br /><b>{custom_field.CUSTOM_NAME}: </b> {custom_field.DATA}<br />
		<!-- END custom_field -->
		<!-- BEGIN switch_comments -->
		<b>{COMMENTS}:</b> {COMMENTS_NUM} {COMMENTS_URL}<br />
		<!-- END switch_comments -->
		<!-- BEGIN switch_ratings -->
		{RATINGS}: {RATE_IMG}<br />
		<!-- END switch_ratings -->
	</td>
</tr>
<tr>
	<td class="row-post" wrap="wrap">
		<b>{L_ARTICLE_TITLE}: </b><span class="maintitle" style="font-size: 9pt;">{ARTICLE_TITLE}</span><br />
		<b>{L_ARTICLE_DESCRIPTION}: </b><span class="genmed">{ARTICLE_DESCRIPTION}</span><br />
	</td>
</tr>
<!-- BEGIN switch_toc -->
<tr>
	<td class="row-post" align="left"><b><span class="gen">{L_TOC}:</span></b><br /><br />
		<span class="nav-current">
			<!-- BEGIN pages -->
			{switch_toc.pages.TOC_ITEM}
			<!-- END pages -->
		</span>
	</td>
</tr>
<!-- END switch_toc -->
<tr><td class="row-post" wrap="wrap"><div class="post-text">{ARTICLE_TEXT}</div></td></tr>
<!-- BEGIN switch_pages -->
<tr>
	<td class="row1 row-center">
		<span class="nav">{L_GOTO_PAGE}
			<!-- BEGIN pages -->
			{switch_pages.pages.PAGE_LINK}
			<!-- END pages -->
		</span>
	</td>
</tr>
<!-- END switch_pages -->
<tr><td class="cat" valign="middle" align="center"><span class="post-buttons">&nbsp;{EDIT_IMG}</span>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- BEGIN switch_comments_show -->
<br />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_COMMENTS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- END switch_comments_show -->
<!-- BEGIN postrow -->
<tr >
	<td class="row-post-author" nowrap="nowrap">
		<span class="post-name"><a name="{postrow.U_POST_ID}"></a>{postrow.POSTER_NAME}</span><br />
		{postrow.RANK_IMAGE}
		<span class="post-images">{postrow.POSTER_AVATAR}</span>
		<div class="post-details">
			{postrow.POSTER_JOINED}<br />
			{postrow.POSTER_POSTS}<br />
			{postrow.POSTER_FROM}
		</div><br />
		<img src="{SPACER}" width="150" height="1" alt="" />
	</td>
	<td class="row-post" width="100%" height="100%">
		<div class="post-buttons-top post-buttons">{postrow.ARROWS} {postrow.QUOTE_IMG} {postrow.EDIT_IMG} {postrow.DELETE_IMG} {postrow.IP_IMG} {postrow.TOPIC_VIEW_IMG} {postrow.U_R_CARD} {postrow.U_Y_CARD} {postrow.U_G_CARD} {postrow.U_B_CARD}</div>
		<div class="post-subject"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" /></a> {postrow.POST_SUBJECT}&nbsp;</div>
		<div class="post-text">
			{postrow.MESSAGE}<br />
			{postrow.ATTACHMENTS}<br />
			<span class="gensmall">{postrow.EDITED_MESSAGE}</span>
		</div>
		<div class="post-text"><br /><br /><br />{postrow.SIGNATURE}</div>
	</td>
</tr>
<tr>
	<td class="row-post-date">{postrow.POST_DATE}</td>
	<td class="row-post-buttons post-buttons">{postrow.POSTER_ONLINE_STATUS_IMG}{postrow.PROFILE_IMG} {postrow.PM_IMG} {postrow.EMAIL_IMG} {postrow.WWW_IMG} {postrow.AIM_IMG} {postrow.YIM_IMG} {postrow.MSN_IMG} {postrow.SKYPE_IMG} {postrow.ICQ_IMG}</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END postrow -->
<!-- BEGIN switch_comments_show -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- BEGIN comments_pag -->
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<!-- END comments_pag -->
<!-- END switch_comments_show -->

<table width="100%" cellspacing="2" border="0" align="center">
<tr>
	<td valign="top" align="left">
		<!-- BEGIN switch_comments -->
		<span class="gensmall"><b>{COMMENTS}:</b> {COMMENTS_NUM} {COMMENTS_URL}</span><br />
		<!-- END switch_comments -->
		<!-- BEGIN switch_ratings -->
		<span class="gensmall">{RATINGS}: {RATE_IMG}</span>
		<!-- END switch_ratings -->
	</td>
</tr>
</table>