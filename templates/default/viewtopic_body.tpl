<script type="text/javascript">
<!--
function post_time_edit(url)
{
	window.open(url, '_postedittime', 'width=600,height=300,resizable=no,scrollbars=no');
}
//-->
</script>
<!-- INCLUDE breadcrumbs_vt.tpl -->
<br clear="all" />

<!-- IF S_FORUM_RULES -->
<table class="forumline" width="100%" cellspacing="0">
<tr><td class="row-header"><span><!-- IF S_FORUM_RULES_TITLE -->{L_FORUM_RULES}<!-- ENDIF -->&nbsp;</span></td></tr>
<tr><td class="row1g-left" width="100%"><div class="post-text">{FORUM_RULES}</div></td></tr>
</table>
<br />
<!-- ENDIF -->

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="bottom">
		<div style="overflow: hidden; max-width: 720px;"><h2><a href="{U_VIEW_TOPIC_BASE}" style="text-decoration: none;">{TOPIC_TITLE}</a></h2></div><br />
		<!-- IF not S_BOT -->
		<span class="img-btn"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a></span>&nbsp;
		<span class="img-btn"><a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" alt="{L_POST_REPLY_TOPIC}" title="{L_POST_REPLY_TOPIC}" /></a></span>&nbsp;
		<!-- IF S_THANKS --><span class="img-btn"><a href="{U_THANKS}"><img src="{THANKS_IMG}" alt="{L_THANKS}" title="{L_THANKS}" /></a></span><!-- ENDIF -->
		<!-- ELSE -->
		&nbsp;
		<!-- ENDIF -->
	</td>
	<td align="right" valign="bottom">
		<span class="gen">{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span><br />
		<!-- IF S_EXTENDED_PAGINATION -->
		<div style="margin-top: 3px;"><form method="post" action="{U_VIEW_TOPIC}"><span class="gen"><b>{L_GO_TO_PAGE_NUMBER}</b>&nbsp;<input type="text" name="page_number" value="" size="3" class="post" />&nbsp;&nbsp;<input type="submit" name="submit" value="{L_GO}" class="mainoption" /></span></form></div>
		<!-- ENDIF -->
		<!-- IF not S_BOT --><div style="margin-top: 3px;"><form action="{FULL_SITE_PATH}{U_SEARCH}" method="post"><input name="search_keywords" type="text" class="post search" style="width: 160px;" value="{L_SEARCH_THIS_TOPIC}" onclick="if(this.value=='{L_SEARCH_THIS_TOPIC}')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH_THIS_TOPIC}';" /><input type="hidden" name="search_where" value="{FORUM_ID_FULL}" /><input type="hidden" name="search_where_topic" value="{TOPIC_ID_FULL}" />&nbsp;<input type="submit" class="mainoption" value="{L_SEARCH}" /></form></div><!-- ENDIF -->
	</td>
</tr>
</table>

<script type="text/javascript">
<!--

<!-- BEGIN switch_quick_quote -->
message = new Array();
<!-- END switch_quick_quote -->

<!-- BEGIN postrow -->
<!-- BEGIN switch_quick_quote -->
message[{postrow.U_POST_ID}] = " user=\"{postrow.POSTER_NAME_QQ}\" post=\"{postrow.U_POST_ID}\"]{postrow.PLAIN_MESSAGE}[/";
<!-- END switch_quick_quote -->
<!-- END postrow -->

<!-- BEGIN switch_quick_quote -->
function addquote(post_id, tag)
{
	document.getElementById('quick_reply').style.display="";
	str_find = new Array("&lt_mg;", "&gt_mg;");
	str_replace = new Array("<", ">");
	for(var i = 0; i < message[post_id].length; i++)
	{
		for (var j = 0; j < str_find.length; j++)
		{
			if (message[post_id].search(str_find[j]) != -1)
			{
				message[post_id] = message[post_id].replace(str_find[j],str_replace[j]);
			}
		}
	}
	document.post.message.value += "[" + tag + message[post_id] + tag + "]";
	document.post.message.focus();
	return;
}
<!-- END switch_quick_quote -->

function quotename(username)
{
	document.getElementById('quick_reply').style.display="";
	document.post.message.value += username;
	document.post.message.focus();
	return;
}

function open_postreview(ref)
{
	height = screen.height / 2.23;
	width = screen.width / 2;
	window.open(ref,'_phpbbpostreview','height=' + height + ',width=' + width + ',resizable=yes,scrollbars=yes');
	return;
}
//-->
</script>
{IMG_THL}{IMG_THC}<span class="forumlink">{TOPIC_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- IF VIEWTOPIC_BANNER_TOP -->
<tr><td class="row-post" colspan="2" align="center" style="text-align: center; vertical-align: middle !important;"><div class="center-block-text" style="overflow:auto;">{VIEWTOPIC_BANNER_TOP}</div></td></tr>
<!-- ENDIF -->
{POLL_DISPLAY}
{REG_DISPLAY}
<tr>
	<th width="150">{L_AUTHOR}</th>
	<th width="100%">{L_MESSAGE}</th>
</tr>
<!-- BEGIN postrow -->
<tr>
	<td class="row-post-author">
		<span class="post-name"><a id="p{postrow.U_POST_ID}"></a>{postrow.POSTER_FROM_FLAG}&nbsp;{postrow.POSTER_NAME}&nbsp;{postrow.POSTER_GENDER}</span>
		<br />
		<div class="center-block-text">
			<div class="post-rank">
			<b>
			{postrow.USER_RANK_01}{postrow.USER_RANK_01_IMG}
			{postrow.USER_RANK_02}{postrow.USER_RANK_02_IMG}
			{postrow.USER_RANK_03}{postrow.USER_RANK_03_IMG}
			{postrow.USER_RANK_04}{postrow.USER_RANK_04_IMG}
			{postrow.USER_RANK_05}{postrow.USER_RANK_05_IMG}
			</b>
			</div>
		</div>
		{postrow.CARD_IMG}
		<!-- BEGIN switch_showavatars -->
		<span class="post-images"><a href="javascript:quotename(%27[b]{postrow.POSTER_NAME_QR}[/b],%27);">{postrow.POSTER_AVATAR}</a></span>
		<!-- END switch_showavatars -->
		<div class="post-details">
		{postrow.POSTER_ONLINE_STATUS_IMG}{postrow.IP_IMG}{postrow.AIM_IMG}{postrow.ICQ_IMG}{postrow.MSN_IMG}{postrow.SKYPE_IMG}{postrow.YIM_IMG}<br />
			<!-- {postrow.POSTER_NO}<br /> -->
			{postrow.POSTER_JOINED}<br />
			{postrow.POSTER_POSTS}<br />
			{postrow.POSTER_THANKS_RECEIVED}
			{postrow.POSTER_AGE}
			{postrow.POSTER_FROM}<br />
			<!-- IF postrow.FEEDBACKS -->{postrow.FEEDBACKS}<!-- ENDIF -->
			<!-- BEGIN switch_poster_info -->
			{postrow.CASH}<br />
			{postrow.POSTER_NO}<br />
			{postrow.POSTER_LANG}
			{postrow.POSTER_STYLE}
			<!-- END switch_poster_info -->
			<!-- BEGIN author_profile -->
			{postrow.author_profile.AUTHOR_VAL}<br />
			<!-- END author_profile -->
			<div class="center-block-text">&nbsp;{postrow.GEB_BILD}</div>
		</div>
		&nbsp;<br />
	</td>
	<!-- <td class="row-post" width="100%" height="100%" style="overflow: hidden;"> -->
	<td class="row-post" height="100%">
		<div class="post-buttons-top post-buttons">
			<!-- IF not S_BOT -->
			{postrow.QUOTE_IMG}{postrow.EDIT_IMG}{postrow.DELETE_IMG}
			{postrow.UNREAD_IMG}
			{postrow.U_R_CARD}{postrow.U_Y_CARD}{postrow.U_G_CARD}{postrow.U_B_CARD}<a href="{postrow.DOWNLOAD_POST}" class="genmed"><img src="{postrow.DOWNLOAD_IMG}" alt="{L_DOWNLOAD_POST}" title="{L_DOWNLOAD_POST}" /></a>{postrow.ARROWS}
			<!-- ELSE -->
			&nbsp;
			<!-- ENDIF -->
		</div>
		<div class="post-subject {postrow.UNREAD_COLOR}"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" /></a> {postrow.POST_SUBJECT}&nbsp;</div>
		&nbsp;
		<div class="post-text">
			{postrow.MESSAGE}<br />
			{postrow.ATTACHMENTS}
		</div>
		<div style="margin-bottom: 2px;clear: both;display: block;">&nbsp;</div>
		<div class="post-text">
			<br /><br /><br />
			<!-- BEGIN above_sig -->
			<span class="post-details"><br />{postrow.above_sig.ABOVE_VAL}</span>
			<!-- END above_sig -->
			<!-- BEGIN switch_showsignatures -->
			{postrow.SIGNATURE}
			<!-- END switch_showsignatures -->
			<!-- BEGIN below_sig -->
			<span class="post-details"><br />{postrow.below_sig.BELOW_VAL}</span>
			<!-- END below_sig -->
		</div>
		<div style="margin-bottom: 2px;clear: both;display: block;">&nbsp;</div>
		<!-- IF S_EDIT_NOTES -->
		<!-- IF postrow.EDITED_MESSAGE -->
		<div class="post-notes"><div class="post-note"><span class="gensmall">{postrow.EDITED_MESSAGE}&nbsp;</span></div></div>
		<!-- ENDIF -->
		<!-- IF postrow.NOTES_COUNT -->
		<div class="post-notes">
		<!-- BEGIN notes -->
		<div class="post-note">
			<!-- IF notes.U_DELETE -->
			<div class="post-note-delete">[<a href="{notes.U_DELETE}">{L_DELETE_NOTE}</a>]</div>
			<!-- ENDIF -->
			{L_EDITED_BY} {postrow.notes.POSTER_NAME}, {postrow.notes.TIME}: {postrow.notes.TEXT}
		</div>
		<!-- END notes -->
		</div>
		<!-- ENDIF -->
		<!-- ENDIF -->
	</td>
</tr>
<tr>
	<td class="row-post-date"><div style="text-align:center;"><b>{postrow.SINGLE_POST}&nbsp;&nbsp;&nbsp;</b>{postrow.POST_DATE}<!-- IF S_ADMIN_MOD --><b>&nbsp;&nbsp;{postrow.POST_EDIT_STRING}&nbsp;</b><!-- ENDIF --></div></td>
	<td class="row-post-buttons post-buttons">
		<div style="text-align:right;vertical-align:middle;">
			<div class="extra-top-padding" style="position:relative;float:left;text-align:left;vertical-align:middle;">
				{postrow.PROFILE_IMG}{postrow.PM_IMG}{postrow.EMAIL_IMG}{postrow.WWW_IMG}{postrow.ALBUM_IMG}&nbsp;
			</div>
			<!-- IF not S_BOT -->
			<!-- BEGIN switch_quick_quote -->
			<a href="javascript:addquote(%27{postrow.U_POST_ID}%27,%27quote%27);"><img src="{IMG_QUICK_QUOTE}" alt="{L_QUICK_QUOTE}" title="{L_QUICK_QUOTE}" /></a><a href="javascript:addquote(%27{postrow.U_POST_ID}%27,%27ot%27);"><img src="{IMG_OFFTOPIC}" alt="{L_OFFTOPIC}" title="{L_OFFTOPIC}" /></a>
			<!-- END switch_quick_quote -->
			<!-- ENDIF -->
			<a href="{U_BACK_TOP}"><img src="{IMG_ARU}" alt="{L_BACK_TOP}" title="{L_BACK_TOP}" /></a><a href="{U_BACK_BOTTOM}"><img src="{IMG_ARD}" alt="{L_BACK_BOTTOM}" title="{L_BACK_BOTTOM}" /></a>
		</div>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- BEGIN thanks -->
<tr><th colspan="3" align="left">{postrow.thanks.THANKS3}</th></tr>
<tr><td colspan="3" class="row-post" valign="top" align="left"><span class="gensmall">{postrow.thanks.THANKS}&nbsp;</span></td></tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END thanks -->
<!-- IF not S_BOT -->
<!-- BEGIN switch_viewtopic_banner -->
<tr>
	<td align="center" class="row-post-author">
		<span class="post-name"><a href="#" style="font-weight:bold;text-decoration:none;">{L_SPONSORS_LINKS}</a></span><br />
		<img src="images/ranks/rank_sponsor.png" alt="" style="margin-bottom: 3px;" /><br />
		<img src="images/avatars/default_avatars/sponsor.gif" alt="" />
	</td>
	<td class="row-post" align="center" style="text-align: center; vertical-align: middle !important;"><div class="center-block-text" style="overflow:auto;">{VIEWTOPIC_BANNER_CODE}</div></td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END switch_viewtopic_banner -->
<!-- ENDIF -->
<!-- END postrow -->
<!-- IF VIEWTOPIC_BANNER_BOTTOM -->
<tr><td class="row-post" colspan="2" align="center" style="text-align: center; vertical-align: middle !important;"><div class="center-block-text" style="overflow:auto;">{VIEWTOPIC_BANNER_BOTTOM}</div></td></tr>
<!-- ENDIF -->
<tr>
	<td class="cat" colspan="2">
		<form method="post" action="{S_POST_DAYS_ACTION}" style="display:inline;">
		<span class="genmed">{L_DISPLAY_POSTS}:</span>&nbsp;{S_SELECT_POST_DAYS}&nbsp;{S_SELECT_POST_ORDER}&nbsp;<input type="submit" value="{L_GO}" class="liteoption jumpbox" name="submit" />
		</form>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- IF not S_BOT -->
<!-- BEGIN switch_topic_useful -->
<a id="ratingblock"></a>
<div id="rate_block_h" style="display: none;">
{IMG_THL}{IMG_THC}<img style="{SHOW_HIDE_PADDING}float:right;cursor:pointer;" src="{IMG_MAXIMISE}" onclick="javascript:ShowHide('rate_block','rate_block_h','rate_block');" alt="{L_SHOW}" /><span class="forumlink">{L_TOPIC_RATING}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1g row-center">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>

<div id="rate_block">
{IMG_THL}{IMG_THC}<img style="{SHOW_HIDE_PADDING}float:right;cursor:pointer;" src="{IMG_MINIMISE}" onclick="javascript:ShowHide('rate_block','rate_block_h','rate_block');" alt="{L_HIDE}" /><span class="forumlink">{L_TOPIC_RATING}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
{RATING_VIEWTOPIC}
<!-- BEGIN social_bookmarks -->
<tr><th colspan="4">{L_SHARE_TOPIC}</th></tr>
<!-- INCLUDE social_bookmarks.tpl -->
<!-- END social_bookmarks -->
<!-- BEGIN link_this_topic -->
<!-- INCLUDE link_this_topic.tpl -->
<!-- END link_this_topic -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<script type="text/javascript">
<!--
tmp = 'rate_block';
if(GetCookie(tmp) == '2')
{
	ShowHide('rate_block', 'rate_block_h', 'rate_block');
}
//-->
</script>
<!-- END switch_topic_useful -->
<!-- ENDIF -->

{SIMILAR_VIEWTOPIC}
{CA_QUICK_REPLY_FORM}

<br />
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top">
		<!-- IF not S_BOT -->
		<span class="img-btn"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}"/></a></span>&nbsp;
		<span class="img-btn"><a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" alt="{L_POST_REPLY_TOPIC}" title="{L_POST_REPLY_TOPIC}"/></a></span>&nbsp;
		<!-- IF S_THANKS --><span class="img-btn"><a href="{U_THANKS}"><img src="{THANKS_IMG}" alt="{L_THANKS}" title="{L_THANKS}" /></a></span>&nbsp;<!-- ENDIF -->
		<span class="img-btn">{CA_QUICK_REPLY_BUTTON}</span>
		<!-- ELSE -->
		&nbsp;
		<!-- ENDIF -->
	</td>
	<td align="right" valign="top">
		<span class="gen">{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>

<!-- INCLUDE breadcrumbs_vt.tpl -->

<!-- IF not S_BOT -->
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="left" valign="top" class="gensmall" width="100%"><span class="gensmall"><b><br />{TOTAL_USERS_ONLINE}<br />{LOGGED_IN_USER_LIST}</b></span><br /><br /></td></tr>
</table>
<!-- ENDIF -->

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top" class="gensmall" width="40%">
		{IMG_TBL}<div id="topic_auth_list_h" style="display: none;">
			<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td class="row-header">
					<div style="display:inline;{SHOW_HIDE_PADDING}float:right;cursor:pointer;"><img src="{IMG_MAXIMISE}" onclick="javascript:ShowHide('topic_auth_list','topic_auth_list_h','topic_auth_list');" alt="" />&nbsp;</div><span>{L_PERMISSIONS_LIST}</span>
				</td>
			</tr>
			</table>
		</div>
		<div id="topic_auth_list">
			<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td class="row-header">
					<div style="display:inline;{SHOW_HIDE_PADDING}float:right;cursor:pointer;"><img src="{IMG_MINIMISE}" onclick="javascript:ShowHide('topic_auth_list','topic_auth_list_h','topic_auth_list');" alt="" />&nbsp;</div><span>{L_PERMISSIONS_LIST}</span>
				</td>
			</tr>
			<tr><td class="row1">{S_AUTH_LIST}</td></tr>
			</table>
		</div>{IMG_TBR}
		<script type="text/javascript">
		<!--
		tmp = 'topic_auth_list';
		if(GetCookie(tmp) == '2')
		{
			ShowHide('topic_auth_list', 'topic_auth_list_h', 'topic_auth_list');
		}
		//-->
		</script>
	</td>
	<td align="right" valign="top">
		{S_TOPIC_ADMIN}<br /><br /><br />
		{JUMPBOX}
	</td>
</tr>
</table>