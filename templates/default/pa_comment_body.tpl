{IMG_THL}{IMG_THC}<span class="forumlink">{L_COMMENTS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_AUTHOR}</th>
	<th>{L_COMMENTS}</th>
</tr>
<!-- IF NO_COMMENTS -->
<tr><td class="row1g row-center" align="center" colspan="2"><div class="post-text">{L_NO_COMMENTS}</div></td></tr>
<!-- ENDIF -->
<!-- BEGIN text -->
<tr>
	<td class="row-post-author">
		<span class="post-name">{text.POSTER}&nbsp;{text.POSTER_GENDER}</span><br />
		<span class="post-rank">
			<div class="center-block-text"><b>{text.USER_RANK_01}{text.USER_RANK_01_IMG}{text.USER_RANK_02}{text.USER_RANK_02_IMG}{text.USER_RANK_03}{text.USER_RANK_03_IMG}{text.USER_RANK_04}{text.USER_RANK_04_IMG}{text.USER_RANK_05}{text.USER_RANK_05_IMG}</b></div>
		</span>
		<span class="post-images">{text.POSTER_AVATAR}</span>
		<div class="post-details">
			{text.POSTER_JOINED}<br />
			{text.POSTER_POSTS}<br />
			{text.POSTER_FROM}
		</div><br />
		<img src="{SPACER}" width="150" height="1" alt="" />
	</td>
	<td class="row-post" width="100%" height="100%">

		<div class="post-buttons-top post-buttons">
			{postrow.ARROWS}
			<!-- IF text.AUTH_COMMENT_DELETE -->
			<a href="{text.U_COMMENT_DELETE}"><img src="{text.DELETE_IMG}" alt="{L_COMMENT_DELETE}" title="{L_COMMENT_DELETE}" border="0"></a>
			<!-- ENDIF -->
		</div>
		<div class="post-subject"><a href="{text.U_MINI_POST}"><img src="{text.ICON_MINIPOST_IMG}" alt="{text.L_MINI_POST_ALT}" title="{text.L_MINI_POST_ALT}" /></a> {text.TITLE}&nbsp;</div>
		&nbsp;
		<div class="post-text">{text.TEXT}</div>
		<br /><br /><br />
	</td>
</tr>
<tr>
	<td class="row-post-date"><div style="text-align:center;">{text.TIME}</div></td>
	<td class="row-post-buttons post-buttons">
		<div style="text-align: right">
			<div style="position: relative; float: left; text-align: left;">
				{text.POSTER_ONLINE_STATUS_IMG}
				{text.PROFILE_IMG}
				{text.PM_IMG}
				{text.EMAIL_IMG}
				{text.WWW_IMG}
				{text.AIM_IMG}
				{text.YIM_IMG}
				{text.MSN_IMG}
				{text.SKYPE_IMG}
				{text.ICQ_IMG}
			</div>
			&nbsp;
		</div>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END text -->
<tr><td colspan="2" class="cat">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- IF AUTH_POST -->
<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr><td><span class="img-btn"><a href="{U_COMMENT_DO}"><img src="{REPLY_IMG}" alt="{L_COMMENT_ADD}" align="middle" /></a></span></td></tr>
</table>
<br clear="all" />
<!-- ENDIF -->