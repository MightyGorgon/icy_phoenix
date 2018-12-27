<!-- INCLUDE viewtopic_inc_js.tpl -->

<!-- INCLUDE breadcrumbs_vt.tpl -->
<br class="clear" />

<!-- IF S_FORUM_RULES -->
<table class="forumline">
<tr><td class="row-header"><span><!-- IF S_FORUM_RULES_TITLE -->{L_FORUM_RULES}<!-- ENDIF -->&nbsp;</span></td></tr>
<tr><td class="row1g-left tw100pct"><div class="post-text">{FORUM_RULES}</div></td></tr>
</table>
<br />
<!-- ENDIF -->

<table>
<tr>
	<td class="tdalignl tvalignb">
		<div class="topic-title-hide-flow-header"><h2><a href="{U_VIEW_TOPIC_BASE}" style="text-decoration: none;">{TOPIC_TITLE}</a></h2></div><br />
		<!-- IF S_TOPIC_TAGS and TOPIC_TAGS -->
		<div><span class="gensmall"><b>{L_TOPIC_TAGS}</b>:&nbsp;{TOPIC_TAGS}</span></div><br />
		<!-- ENDIF -->
		<!-- IF not S_BOT -->
		<span class="img-btn"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a></span>&nbsp;
		<span class="img-btn"><a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" alt="{L_POST_REPLY_TOPIC}" title="{L_POST_REPLY_TOPIC}" /></a></span>&nbsp;
		<!-- ELSE -->
		&nbsp;
		<!-- ENDIF -->
	</td>
	<td class="tdalignr tvalignb">
		<span class="gen">{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span><br />
		<!-- IF S_EXTENDED_PAGINATION -->
		<div style="margin-top: 3px;"><form method="post" action="{U_VIEW_TOPIC}"><span class="gen"><b>{L_GO_TO_PAGE_NUMBER}</b>&nbsp;<input type="text" name="page_number" value="" size="3" class="post" />&nbsp;&nbsp;<input type="submit" name="submit" value="{L_GO}" class="mainoption" /></span></form></div>
		<!-- ENDIF -->
		<!-- IF not S_BOT --><div style="margin-top: 3px; white-space: nowrap;"><form action="{FULL_SITE_PATH}{U_SEARCH}" method="post"><input name="search_keywords" type="text" class="post search" style="width: 160px;" value="{L_SEARCH_THIS_TOPIC}" onclick="if(this.value=='{L_SEARCH_THIS_TOPIC}')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH_THIS_TOPIC}';" /><input type="hidden" name="search_where" value="{FORUM_ID_FULL}" /><input type="hidden" name="search_where_topic" value="{TOPIC_ID_FULL}" />&nbsp;<input type="submit" class="mainoption" value="{L_SEARCH}" /></form></div><!-- ENDIF -->
	</td>
</tr>
</table>

{IMG_THL}{IMG_THC}<span class="forumlink">{TOPIC_TITLE_SHORT}</span>{IMG_THR}<table class="forumlinenb">
<!-- IF VIEWTOPIC_BANNER_TOP -->
<tr><td class="row-post" colspan="2" style="text-align: center; vertical-align: middle !important;"><div class="center-block-text" style="overflow: auto;">{VIEWTOPIC_BANNER_TOP}</div></td></tr>
<!-- ENDIF -->
{POLL_DISPLAY}
{REG_DISPLAY}
<tr>
	<th class="tw180px">{L_AUTHOR}</th>
	<th>{L_MESSAGE}</th>
</tr>
<!-- BEGIN postrow -->
<tr data-post-id="{postrow.U_POST_ID}">
	<td class="row-post-author">
		<span class="post-name">
			<a id="p{postrow.U_POST_ID}"></a><!-- IF not postrow.S_THIS_POSTER_MASK --><a href="{postrow.U_USER_ONLINE_STATUS}"><img src="<!-- IF postrow.S_USER_ONLINE -->{postrow.IMG_USER_ONLINE}<!-- ELSEIF postrow.S_USER_HIDDEN -->{postrow.IMG_USER_HIDDEN}<!-- ELSE -->{postrow.IMG_USER_OFFLINE}<!-- ENDIF -->" alt="{postrow.L_POSTER_ONLINE_STATUS}" title="{postrow.L_POSTER_ONLINE_STATUS}" /></a><!-- ENDIF -->&nbsp;{postrow.POSTER_NAME}&nbsp;<!-- IF postrow.S_GENDER_MALE and not postrow.S_THIS_POSTER_MASK --><a href="#"><img src="{postrow.IMG_GENDER_MALE}" alt="{postrow.L_GENDER_MALE}" title="{postrow.L_GENDER_MALE}" /></a><!-- ELSEIF postrow.S_GENDER_FEMALE and not postrow.S_THIS_POSTER_MASK --><a href="#"><img src="{postrow.IMG_GENDER_FEMALE}" alt="{postrow.L_GENDER_FEMALE}" title="{postrow.L_GENDER_FEMALE}" /></a><!-- ENDIF -->
		</span><br />
		<!-- IF postrow.POSTER_FULL_NAME and not postrow.S_THIS_POSTER_MASK --><span class="post-details">{postrow.POSTER_FULL_NAME}</span><br /><!-- ENDIF -->
		<!-- IF not S_BOT and not postrow.S_THIS_POSTER_MASK -->
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

		<!-- IF postrow.S_CARD_SWITCH_Y_R_A -->
			<!-- IF postrow.S_CARD_SWITCH_R_A -->
				<img src="{postrow.IMG_CARD_R_A}" alt="{postrow.L_CARD_R_A}" title="{postrow.L_CARD_R_A}" />
			<!-- ELSE -->
				<!-- BEGIN cards_y -->
					<img src="{postrow.IMG_CARD_Y_A}" alt="{postrow.L_CARD_Y_A}" title="{postrow.L_CARD_Y_A}" />
				<!-- END cards_y -->
			<!-- ENDIF -->
		<!-- ENDIF -->

		<!-- ENDIF -->
		<!-- BEGIN switch_showavatars -->
		<!-- POSTER_NAME_QR has been removed to use the new bbcode USER -->
		<span class="post-images"><a href="<!-- IF postrow.POSTER_ID and not S_BOT and not postrow.S_THIS_POSTER_MASK -->javascript:quotename(%27[user]{postrow.POSTER_ID}[/user],%27);<!-- ELSE -->#<!-- ENDIF -->">{postrow.POSTER_AVATAR}</a></span>
		
		<!-- END switch_showavatars -->
		<div class="post-details">
			<!-- IF not postrow.S_THIS_POSTER_MASK -->
			<!-- IF not S_BOT -->
			<!-- {postrow.POSTER_NO}<br /> -->
			{postrow.POSTER_JOINED}<br />
			{postrow.POSTER_POSTS}<br />
			<!-- ENDIF -->
			{postrow.POSTER_AGE}
			{postrow.POSTER_FROM_FULL}<br />
			<!-- IF postrow.FEEDBACK -->{postrow.FEEDBACK}<!-- ENDIF -->
			<!-- IF not S_BOT -->
			<!-- BEGIN switch_poster_info -->
			{postrow.CASH}<br />
			{postrow.POSTER_NO}<br />
			{postrow.POSTER_LANG}
			{postrow.POSTER_STYLE}
			<!-- END switch_poster_info -->
			<!-- ENDIF -->
			<!-- BEGIN author_profile -->
			{postrow.author_profile.AUTHOR_VAL}<br />
			<!-- END author_profile -->
			<div class="center-block-text">&nbsp;{postrow.HAPPY_BIRTHDAY}</div>
			<!-- ENDIF -->
		</div>
		&nbsp;<br />
	</td>
	<!-- <td class="row-post" width="100%" height="100%" style="overflow: hidden;"> -->
	<td class="row-post th100pct">
		<div class="post-buttons-top post-buttons">
			<!-- IF not S_BOT -->
			<a href="{postrow.U_POST_QUOTE}"><img class="{IMG_BUTTON_CLASS_MB}" src="{postrow.IMG_POST_QUOTE}" alt="{postrow.L_POST_QUOTE}" title="{postrow.L_POST_QUOTE}" /></a>
			<!-- IF postrow.S_POST_EDIT -->
			&nbsp;<a href="{postrow.U_POST_EDIT}"><img class="{IMG_BUTTON_CLASS_MB}" src="{postrow.IMG_POST_EDIT}" alt="{postrow.L_POST_EDIT}" title="{postrow.L_POST_EDIT}" /></a>
			<!-- ENDIF -->
			<!-- IF postrow.S_POST_DELETE -->
			&nbsp;<a href="{postrow.U_POST_DELETE}"><img class="{IMG_BUTTON_CLASS_MB}" src="{postrow.IMG_POST_DELETE}" alt="{postrow.L_POST_DELETE}" title="{postrow.L_POST_DELETE}" /></a>
			<!-- ENDIF -->
			<!-- IF IS_UPI2DB -->
				<!-- IF postrow.UPI2DB_MARK_UNREAD --><a href="{postrow.UPI2DB_MARK_UNREAD_URL}"><img src="{postrow.UPI2DB_MARK_UNREAD_IMG}" alt="{postrow.L_UPI2DB_MARK_UNREAD}" title="{postrow.L_UPI2DB_MARK_UNREAD}" /></a><!-- ENDIF -->
				<!-- IF postrow.UPI2DB_CANT_MARK --><img src="{postrow.UPI2DB_CANT_MARK_IMG}" alt="{postrow.L_UPI2DB_CANT_MARK}" title="{postrow.L_UPI2DB_CANT_MARK}" /><!-- ENDIF -->
				<!-- IF postrow.UPI2DB_MARK_POST --><a href="{postrow.UPI2DB_MARK_POST_URL}"><img src="{postrow.UPI2DB_MARK_POST_IMG}" alt="{postrow.L_UPI2DB_MARK_POST}" title="{postrow.L_UPI2DB_MARK_POST}" /></a><!-- ENDIF -->
				<!-- IF postrow.UPI2DB_UNMARK_POST --><a href="{postrow.UPI2DB_UNMARK_POST_URL}"><img src="{postrow.UPI2DB_UNMARK_POST_IMG}" alt="{postrow.L_UPI2DB_UNMARK_POST}" title="{postrow.L_UPI2DB_UNMARK_POST}" /></a><!-- ENDIF -->
			<!-- ENDIF -->
			<!-- IF postrow.S_CARD_SWITCH -->
				<!-- IF postrow.S_CARD_SWITCH_B --><!-- IF S_USE_CSS_CARDS --><span class="img-report"><!-- ENDIF --><a href="{postrow.U_CARD_B}" onclick="return confirm('{postrow.L_CARD_B_JS}')"><img src="{postrow.IMG_CARD_B}" alt="{postrow.L_CARD_B}" title="{postrow.L_CARD_B}" /></a><!-- IF S_USE_CSS_CARDS --></span><!-- ENDIF --><!-- ENDIF -->
				<!-- IF postrow.S_CARD_SWITCH_P --><!-- IF S_USE_CSS_CARDS --><span class="img-clear"><!-- ENDIF --><a href="{postrow.U_CARD_P}" onclick="return confirm('{postrow.L_CARD_P_JS}')"><img src="{postrow.IMG_CARD_P}" alt="{postrow.L_CARD_P}" title="{postrow.L_CARD_P}" /></a><!-- IF S_USE_CSS_CARDS --></span><!-- ENDIF --><!-- ENDIF -->
				<!-- IF postrow.S_CARD_SWITCH_G --><!-- IF S_USE_CSS_CARDS --><span class="img-green"><!-- ENDIF --><a href="{postrow.U_CARD_G}" onclick="return confirm('{postrow.L_CARD_G_JS}')"><img src="{postrow.IMG_CARD_G}" alt="{postrow.L_CARD_G}" title="{postrow.L_CARD_G}" /></a><!-- IF S_USE_CSS_CARDS --></span><!-- ENDIF --><!-- ENDIF -->
				<!-- IF postrow.S_CARD_SWITCH_Y --><!-- IF S_USE_CSS_CARDS --><span class="img-warn"><!-- ENDIF --><a href="{postrow.U_CARD_Y}" onclick="return confirm('{postrow.L_CARD_Y_JS}')"><img src="{postrow.IMG_CARD_Y}" alt="{postrow.L_CARD_Y}" title="{postrow.L_CARD_Y}" /></a><!-- IF S_USE_CSS_CARDS --></span><!-- ENDIF --><!-- ENDIF -->
				<!-- IF postrow.S_CARD_SWITCH_R --><!-- IF S_USE_CSS_CARDS --><span class="img-ban"><!-- ENDIF --><a href="{postrow.U_CARD_R}" onclick="return confirm('{postrow.L_CARD_R_JS}')"><img src="{postrow.IMG_CARD_R}" alt="{postrow.L_CARD_R}" title="{postrow.L_CARD_R}" /></a><!-- IF S_USE_CSS_CARDS --></span><!-- ENDIF --><!-- ENDIF -->
			<!-- ENDIF -->
			&nbsp;<!-- IF IS_APHRODITE -->{postrow.IP_IMG}&nbsp;<a href="{postrow.DOWNLOAD_POST}" class="genmed" rel="nofollow"><img class="{IMG_BUTTON_CLASS_MB}" src="{postrow.DOWNLOAD_IMG}" alt="{L_DOWNLOAD_POST}" title="{L_DOWNLOAD_POST}" /></a><!-- ELSE -->{postrow.IP_IMG_ICON}&nbsp;<a href="{postrow.DOWNLOAD_POST}" class="genmed" rel="nofollow"><img class="{IMG_BUTTON_CLASS_MB}" src="{postrow.DOWNLOAD_IMG_ICON}" alt="{L_DOWNLOAD_POST}" title="{L_DOWNLOAD_POST}" /></a><!-- ENDIF -->&nbsp;{postrow.ARROWS}
			<!-- ELSE -->
			&nbsp;
			<!-- ENDIF -->
		</div>
		<div class="post-subject {postrow.UNREAD_COLOR}"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" /></a> {postrow.POST_SUBJECT}&nbsp;</div>
		&nbsp;
		<div class="post-text post-text-hide-flow">
			{postrow.MESSAGE}<br />
			{postrow.ATTACHMENTS}
		</div>
		<div class="align-spacer">&nbsp;</div>
		<!-- IF not postrow.S_THIS_POSTER_MASK -->
		<div class="post-text post-text-hide-flow">
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
		<!-- ENDIF -->
		<div class="align-spacer">&nbsp;</div>
		<!-- IF S_EDIT_NOTES -->
		<!-- IF postrow.EDITED_MESSAGE --><div class="post-notes"><div class="post-note"><span class="gensmall">{postrow.EDITED_MESSAGE}&nbsp;</span></div></div><!-- ENDIF -->
		<!-- IF postrow.NOTES_S_COUNT > 0 --><div class="post-notes"><!-- BEGIN notes --><div class="post-note"><!-- IF notes.U_DELETE --><div class="post-note-delete">[<a href="{notes.U_DELETE}">{L_DELETE_NOTE}</a>]</div><!-- ENDIF -->{L_EDITED_BY} {postrow.notes.POSTER_NAME}, {postrow.notes.TIME}: {postrow.notes.TEXT}</div><!-- END notes --></div><!-- ENDIF -->
		<!-- IF postrow.NOTES_M_COUNT --><div class="post-notes"><!-- BEGIN notes_mod --><div class="post-note"><!-- IF notes_mod.U_DELETE --><div class="post-note-delete">[<a href="{notes_mod.U_DELETE}">{L_DELETE_NOTE}</a>]</div><!-- ENDIF -->{L_NOTES_MOD} &bull; {postrow.notes_mod.POSTER_NAME}, {postrow.notes_mod.TIME}: {postrow.notes_mod.TEXT}</div><!-- END notes_mod --></div><!-- ENDIF -->
		<!-- ENDIF -->
	</td>
</tr>
<!-- IF S_POSTS_LIKES and not postrow.S_THIS_POSTER_MASK -->
<tr data-post-id="{postrow.U_POST_ID}"><td class="row-post-date tdalignc tvalignm" colspan="2"><span class="gensmall"><span id="like_s_p{postrow.U_POST_ID}"><!-- IF postrow.POST_LIKE_TEXT -->{postrow.POST_LIKE_TEXT}&nbsp;&bull;<!-- ENDIF -->&nbsp;</span><!-- IF S_LOGGED_IN and not postrow.S_OWN_POST --><a href="#" id="like_a_p{postrow.U_POST_ID}" style="text-decoration: none;" onclick="post_like_ajax({postrow.U_TOPIC_ID}, {postrow.U_POST_ID}); return false;"><!-- IF postrow.READER_LIKES -->{L_UNLIKE}<!-- ELSE -->{L_LIKE}<!-- ENDIF --></a>&nbsp;&bull;<!-- ENDIF -->&nbsp;{postrow.SINGLE_POST_SHARE}</span></td></tr>
<!-- ENDIF -->
<tr data-post-id="{postrow.U_POST_ID}">
	<td class="row-post-date"><div style="text-align: center;"><b>{postrow.SINGLE_POST}</b>&nbsp;&nbsp;<!-- IF S_ADMIN -->{postrow.POST_EDIT_STRING_SHORT}<!-- ELSE -->{postrow.POST_DATE}<!-- ENDIF --></div></td>
	<td class="row-post-buttons post-buttons">
		<div style="text-align: right; vertical-align: middle;">
			<!-- IF not postrow.S_THIS_POSTER_MASK -->
			<div class="extra-top-padding" style="position: relative; float: left; text-align: left; vertical-align: middle;">
				<a href="{postrow.U_VIEW_PROFILE}"><img class="{IMG_BUTTON_CLASS_MB}" src="{postrow.IMG_VIEW_PROFILE}" alt="{postrow.POSTER_NAME_QQ}" title="{postrow.POSTER_NAME_QQ}" /></a>
				<a href="{postrow.U_SEND_PRIVMSG}"><img class="{IMG_BUTTON_CLASS_MB}" src="{postrow.IMG_SEND_PRIVMSG}" alt="{postrow.L_SEND_PRIVMSG}" title="{postrow.L_SEND_PRIVMSG}" /></a>
				<!-- IF postrow.S_USER_ALLOW_VIEWEMAIL -->
				<a href="{postrow.U_SEND_EMAIL}"><img class="{IMG_BUTTON_CLASS_MB}" src="{postrow.IMG_SEND_EMAIL}" alt="{postrow.L_SEND_EMAIL}" title="{postrow.L_SEND_EMAIL}" /></a>
				<!-- ENDIF -->
				<!-- IF postrow.S_USER_WEBSITE -->
				<a href="{postrow.U_USER_WEBSITE}" target="_blank"><img class="{IMG_BUTTON_CLASS_MB}" src="{postrow.IMG_USER_WEBSITE}" alt="{postrow.L_USER_WEBSITE}" title="{postrow.L_USER_WEBSITE}" /></a>
				<!-- ENDIF -->
				<!-- IF postrow.S_USER_ALBUM -->
				<a href="{postrow.U_USER_ALBUM}"><img class="{IMG_BUTTON_CLASS_MB}" src="{postrow.IMG_USER_ALBUM}" alt="{postrow.L_S_USER_ALBUM}: {postrow.POSTER_NAME_QQ}" title="{postrow.L_S_USER_ALBUM}: {postrow.POSTER_NAME_QQ}" /></a>&nbsp;
				<!-- ENDIF -->
				<!-- IF not postrow.S_THIS_POSTER_MASK -->
				<!-- IF postrow.IMG_SOCIAL_500PX -->{postrow.IMG_SOCIAL_500PX} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_AIM -->{postrow.IMG_SOCIAL_AIM} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_FACEBOOK -->{postrow.IMG_SOCIAL_FACEBOOK} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_FLICKR -->{postrow.IMG_SOCIAL_FLICKR} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_GITHUB -->{postrow.IMG_SOCIAL_GITHUB} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_GOOGLEPLUS -->{postrow.IMG_SOCIAL_GOOGLEPLUS} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_ICQ -->{postrow.IMG_SOCIAL_ICQ} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_INSTAGRAM -->{postrow.IMG_SOCIAL_INSTAGRAM} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_JABBER -->{postrow.IMG_SOCIAL_JABBER} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_LINKEDIN -->{postrow.IMG_SOCIAL_LINKEDIN} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_MSN -->{postrow.IMG_SOCIAL_MSN} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_PINTEREST -->{postrow.IMG_SOCIAL_PINTEREST} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_SKYPE -->{postrow.IMG_SOCIAL_SKYPE} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_TWITTER -->{postrow.IMG_SOCIAL_TWITTER} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_VIMEO -->{postrow.IMG_SOCIAL_VIMEO} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_YAHOO -->{postrow.IMG_SOCIAL_YAHOO} <!-- ENDIF --><!-- IF postrow.IMG_SOCIAL_YOUTUBE -->{postrow.IMG_SOCIAL_YOUTUBE} <!-- ENDIF -->
				<!-- ENDIF -->
			</div>
			<!-- IF not S_BOT and not postrow.S_THIS_POSTER_MASK -->
			<!-- BEGIN switch_quick_quote -->
			<a href="javascript:addquote(%27{postrow.U_POST_ID}%27,%27quote%27,true,false);"><img class="{IMG_BUTTON_CLASS_MB}" src="{IMG_QUICK_QUOTE}" alt="{L_QUICK_QUOTE}" title="{L_QUICK_QUOTE}" /></a><a href="javascript:addquote(%27{postrow.U_POST_ID}%27,%27ot%27,true,false);"><img class="{IMG_BUTTON_CLASS_MB}" src="{IMG_OFFTOPIC}" alt="{L_OFFTOPIC}" title="{L_OFFTOPIC}" /></a>
			<!-- END switch_quick_quote -->
			<!-- ENDIF -->
			<a href="{U_BACK_TOP}"><img src="{IMG_ARU}" alt="{L_BACK_TOP}" title="{L_BACK_TOP}" /></a><a href="{U_BACK_BOTTOM}"><img src="{IMG_ARD}" alt="{L_BACK_BOTTOM}" title="{L_BACK_BOTTOM}" /></a>
			<!-- ENDIF -->
		</div>
	</td>
</tr>
<tr data-post-id="{postrow.U_POST_ID}"><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- IF not S_BOT -->
<!-- BEGIN switch_viewtopic_banner -->
<tr>
	<td class="row-post-author row-center">
		<span class="post-name"><a href="#" style="font-weight:bold;text-decoration:none;">{L_SPONSORS_LINKS}</a></span><br />
		<img src="images/ranks/rank_sponsor.png" alt="" style="margin-bottom: 3px;" /><br />
		<img src="images/avatars/default_avatars/sponsor.gif" alt="" />
	</td>
	<td class="row-post" style="text-align: center; vertical-align: middle !important;"><div class="center-block-text" style="overflow:auto;">{VIEWTOPIC_BANNER_CODE}</div></td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END switch_viewtopic_banner -->
<!-- ENDIF -->
<!-- END postrow -->

<!-- IF VIEWTOPIC_BANNER_BOTTOM -->
<tr><td class="row-post" colspan="2" style="text-align: center; vertical-align: middle !important;"><div class="center-block-text" style="overflow:auto;">{VIEWTOPIC_BANNER_BOTTOM}</div></td></tr>
<!-- ENDIF -->
<tr>
	<td class="cat" colspan="2">
		<form method="post" action="{S_POST_DAYS_ACTION}" style="display: inline;">
		<span class="genmed">{L_DISPLAY_POSTS}:</span>&nbsp;{S_SELECT_SORT_DAYS}&nbsp;{S_SELECT_SORT_KEY}&nbsp;{S_SELECT_SORT_DIR}&nbsp;<input type="submit" value="{L_GO}" class="liteoption jumpbox" name="submit" />
		</form>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<br class="clear" />

<table>
<tr>
	<td>
		<!-- IF not S_BOT -->
		<span class="img-btn"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}"/></a></span>&nbsp;
		<span class="img-btn"><a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" alt="{L_POST_REPLY_TOPIC}" title="{L_POST_REPLY_TOPIC}"/></a></span>&nbsp;
		<!-- IF S_CAN_REPLY --><span class="img-btn">{CA_QUICK_REPLY_BUTTON}</span>&nbsp;<!-- ENDIF -->
		<!-- ELSE -->
		&nbsp;
		<!-- ENDIF -->
	</td>
	<td class="tdalignr">
		<span class="gen">{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>

<br class="clear" />

<!-- IF not S_BOT -->
{CA_QUICK_REPLY_FORM}
<!-- ENDIF -->

<br class="clear" />

<!-- IF not S_BOT -->
<!-- BEGIN switch_topic_useful -->
<a id="ratingblock"></a>
<div id="rate_block_h" style="display: none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('rate_block','rate_block_h','rate_block');" alt="{L_SHOW}" /><span class="forumlink">{L_TOPIC_RATING}</span>{IMG_THR}<table class="forumlinenb">
<tr><td class="row1g row-center">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>

<div id="rate_block">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('rate_block','rate_block_h','rate_block');" alt="{L_HIDE}" /><span class="forumlink">{L_TOPIC_RATING}</span>{IMG_THR}<table class="forumlinenb">
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

<!-- INCLUDE breadcrumbs_vt.tpl -->

<!-- IF not S_BOT -->
<table>
<tr><td class="gensmall tw100pct"><span class="gensmall"><br />{TOTAL_USERS_ONLINE}<br />{LOGGED_IN_USER_LIST}</span><br /><br /></td></tr>
</table>
<!-- ENDIF -->

<table>
<tr>
	<td class="gensmall tw40pct">
		{IMG_TBL}<div id="topic_auth_list_h" style="display: none;">
			<table class="forumline">
			<tr>
				<td class="row-header">
					<div class="max-min-right" style="{SHOW_HIDE_PADDING}"><img src="{IMG_MAXIMISE}" onclick="ShowHide('topic_auth_list','topic_auth_list_h','topic_auth_list');" alt="" />&nbsp;</div><span>{L_PERMISSIONS_LIST}</span>
				</td>
			</tr>
			</table>
		</div>
		<div id="topic_auth_list">
			<table class="forumline">
			<tr>
				<td class="row-header">
					<div class="max-min-right" style="{SHOW_HIDE_PADDING}"><img src="{IMG_MINIMISE}" onclick="ShowHide('topic_auth_list','topic_auth_list_h','topic_auth_list');" alt="" />&nbsp;</div><span>{L_PERMISSIONS_LIST}</span>
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
	<td class="tdalignr">
		<!-- IF not S_BOT and S_TMOD_BUTTONS -->
		<!-- INCLUDE viewtopic_admin.tpl -->
		<!-- ENDIF -->
		{S_TMOD_TOPIC_LABELS_BLOCK}<br /><br />
		{JUMPBOX}
	</td>
</tr>
</table>
<!-- IF IS_LAST_PAGE -->
<script type="text/javascript">
var ajaxPostData = {AJAX_POST_DATA};
</script>
<script type="text/javascript" src="templates/common/js/ajax_post.js"></script>
<!-- ENDIF -->