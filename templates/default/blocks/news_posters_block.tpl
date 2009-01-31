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
	<td class="row1" width="100%" style="vertical-align: top; padding: 0px;">
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
			<td class="row1" width="50%"><!-- IF IS_PROSILVER --><ul class="profile-icons" style="padding-left: 10px;"><li class="profile-icon"><a href="{news_poster_av.U_PROFILE}" title="{L_USER_PROFILE}"><span>{L_USER_PROFILE}</span></a></li><li class="pm-icon"><a href="{news_poster_av.U_PM}" title="{L_PM}"><span>{L_PM}</span></a></li><!-- IF news_poster_av.U_WWW --><li class="web-icon"><a href="{news_poster_av.U_WWW}" title="{L_USER_WWW}"><span>{L_USER_WWW}</span></a></li><!-- ENDIF --><!-- IF news_poster_av.U_AIM --><li class="aim-icon"><a href="{news_poster_av.U_AIM}" title="{L_AIM}"><span>{L_AIM}</span></a></li><!-- ENDIF --><!-- IF news_poster_av.U_ICQ --><li class="icq-icon"><a href="{news_poster_av.U_ICQ}" title="{L_ICQ}"><span>{L_ICQ}</span></a></li><!-- ENDIF --><!-- IF news_poster_av.U_MSN --><li class="msn-icon"><a href="{news_poster_av.U_MSN}" title="{L_MSNM}"><span>{L_MSNM}</span></a></li><!-- ENDIF --><!-- IF news_poster_av.U_SKYPE --><li class="skype-icon"><a href="{news_poster_av.U_SKYPE}" title="{L_SKYPE}"><span>{L_SKYPE}</span></a></li><!-- ENDIF --><!-- IF news_poster_av.U_YIM --><li class="yahoo-icon"><a href="{news_poster_av.U_YIM}" title="{L_YIM}"><span>{L_YIM}</span></a></li><!-- ENDIF --></ul><!-- ELSE --><span class="gensmall"><b>{news_poster_av.CONTACTS}</b></span><!-- ENDIF --></td>
			<td class="row1" width="50%"><span class="gensmall"><a href="{news_poster_av.U_VIEWPOSTS}"><b>{L_POSTS_POSTED} [{news_poster_av.POSTS}]</b></a></span></td>
		</tr>
		</table>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END news_poster_av -->
</table>