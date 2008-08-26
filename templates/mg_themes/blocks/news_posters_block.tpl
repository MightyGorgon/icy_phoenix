<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN news_poster -->
<tr>
	<td class="row1" width="25%">{news_poster.USERNAME}</td>
	<td class="row1 row-center" width="25%"><span class="gensmall"><a href="{news_poster.U_VIEWNEWS}"><b>{L_NEWS_POSTED} [{news_poster.NEWS}]</b></a></span></td>
	<td class="row1 row-center" width="25%"><span class="gensmall"><a href="{news_poster.U_VIEWNEWS}"><b>{L_TOPICS_POSTED}</b></a></span></td>
	<td class="row1 row-center" width="25%"><span class="gensmall"><a href="{news_poster.U_VIEWNEWS}"><b>{L_POSTS_POSTED} [{news_poster.POSTS}]</b></a></span></td>
</tr>
<!-- END news_poster -->
<!-- BEGIN news_poster_av -->
<tr>
	<td class="row1 row-center" width="150">{news_poster_av.AVATAR_IMG}</td>
	<td class="row1" width="100%" style="vertical-align:top;">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><th colspan="4">{news_poster_av.USERNAME}</th></tr>
		<tr>
			<td class="row1" width="50%"><span class="gensmall"><b>{news_poster_av.POSTER_JOINED}</b></span></td>
			<td class="row1" width="50%"><span class="gensmall"><a href="{news_poster_av.U_VIEWNEWS}"><b>{L_NEWS_POSTED} [{news_poster_av.NEWS}]</b></a></span></td>
		</tr>
		<tr>
			<td class="row1" width="50%"><span class="gensmall"><b>{news_poster_av.POSTER_FROM}</b></span></td>
			<td class="row1" width="50%"><span class="gensmall"><a href="{news_poster_av.U_VIEWTOPICS}"><b>{L_TOPICS_POSTED}</b></a></span></td>
		</tr>
		<tr>
			<td class="row1" width="50%"><span class="gensmall"><b>{news_poster_av.CONTACTS}</b></span></td>
			<td class="row1" width="50%"><span class="gensmall"><a href="{news_poster_av.U_VIEWPOSTS}"><b>{L_POSTS_POSTED} [{news_poster_av.POSTS}]</b></a></span></td>
		</tr>
		</table>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END news_poster_av -->
</table>