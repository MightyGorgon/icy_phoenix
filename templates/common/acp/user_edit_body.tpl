<script type="text/javascript">
<!--
function ColorExample(ColorCode)
{
	var color_example_box = window.document.getElementById('color_group_example');
	if ((ColorCode.length == 6) || (ColorCode.length == 3))
	{
		color_example_box.style.color = '#' + ColorCode;
	}
	else if (((ColorCode.length == 7) || (ColorCode.length == 4)) && (ColorCode.charAt(0) == '#'))
	{
		color_example_box.style.color = ColorCode;
	}
}
//-->
</script>

<h1>{L_USER_TITLE}</h1>
<p>{L_USER_EXPLAIN}</p>

{ERROR_BOX}

<form action="{S_PROFILE_ACTION}" {S_FORM_ENCTYPE} method="post">
<table class="forumline">
<tr><th colspan="2">{L_REGISTRATION_INFO}</th></tr>
<tr><td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td></tr>
<tr>
	<td class="row1" width="38%"><span class="gen">{L_USERNAME}: *</span></td>
	<td class="row2"><input class="post" type="text" name="acp_username" size="35" maxlength="40" value="{USERNAME}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_EMAIL_ADDRESS}: *</span></td>
	<td class="row2"><input class="post" type="text" name="email" size="35" maxlength="255" value="{EMAIL}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_NEW_PASSWORD}: *</span><br />
	<span class="gensmall">{L_PASSWORD_IF_CHANGED}</span></td>
	<td class="row2"><input class="post" type="password" name="password_change" size="35" maxlength="32" value="" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_CONFIRM_PASSWORD}: * </span><br />
	<span class="gensmall">{L_PASSWORD_CONFIRM_IF_CHANGED}</span></td>
	<td class="row2"><input class="post" type="password" name="password_confirm" size="35" maxlength="32" value="" /></td>
</tr>
<tr><td class="catsides" colspan="2">&nbsp;</td></tr>
<tr><th colspan="2">{L_PROFILE_INFO}</th></tr>
<tr><td class="row2" colspan="2"><span class="gensmall">{L_PROFILE_INFO_NOTICE}</span></td></tr>
<tr>
	<td class="row1"><span class="gen">{L_USER_FIRST_NAME}:</span></td>
	<td class="row2"><input class="post" type="text" name="user_first_name" size="35" maxlength="180" value="{USER_FIRST_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_USER_LAST_NAME}:</span></td>
	<td class="row2"><input class="post" type="text" name="user_last_name" size="35" maxlength="180" value="{USER_LAST_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_PHONE}</span></td>
	<td class="row2"><input class="post" type="text" name="phone" size="35" maxlength="150" value="{PHONE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_500PX}</span></td>
	<td class="row2"><input class="post" type="text" name="500px" size="20" maxlength="255" value="{500PX}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_AIM}</span></td>
	<td class="row2"><input class="post" type="text" name="aim" size="20" maxlength="255" value="{AIM}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_FACEBOOK}</span></td>
	<td class="row2"><input class="post" type="text" name="facebook" size="20" maxlength="255" value="{FACEBOOK}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_FLICKR}</span></td>
	<td class="row2"><input class="post" type="text" name="flickr" size="20" maxlength="255" value="{FLICKR}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GITHUB}</span></td>
	<td class="row2"><input class="post" type="text" name="github" size="20" maxlength="255" value="{GITHUB}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GOOGLEPLUS}</span></td>
	<td class="row2"><input class="post" type="text" name="googleplus" size="20" maxlength="255" value="{GOOGLEPLUS}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_ICQ_NUMBER}</span></td>
	<td class="row2"><input class="post" type="text" name="icq" size="10" maxlength="15" value="{ICQ}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_INSTAGRAM}</span></td>
	<td class="row2"><input class="post" type="text" name="instagram" size="20" maxlength="255" value="{INSTAGRAM}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_JABBER}</span></td>
	<td class="row2"><input class="post" type="text" name="jabber" size="20" maxlength="255" value="{JABBER}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_LINKEDIN}</span></td>
	<td class="row2"><input class="post" type="text" name="linkedin" size="20" maxlength="255" value="{LINKEDIN}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_MESSENGER}</span></td>
	<td class="row2"><input class="post" type="text" name="msn" size="20" maxlength="255" value="{MSN}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_PINTEREST}</span></td>
	<td class="row2"><input class="post" type="text" name="pinterest" size="20" maxlength="255" value="{PINTEREST}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_SKYPE}</span></td>
	<td class="row2"><input class="post" type="text" name="skype" size="20" maxlength="255" value="{SKYPE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_TWITTER}</span></td>
	<td class="row2"><input class="post" type="text" name="twitter" size="20" maxlength="255" value="{TWITTER}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_VIMEO}</span></td>
	<td class="row2"><input class="post" type="text" name="vimeo" size="20" maxlength="255" value="{VIMEO}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_YAHOO}</span></td>
	<td class="row2"><input class="post" type="text" name="yim" size="20" maxlength="255" value="{YIM}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_YOUTUBE}</span></td>
	<td class="row2"><input class="post" type="text" name="youtube" size="20" maxlength="255" value="{YOUTUBE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_WEBSITE}</span></td>
	<td class="row2"><input class="post" type="text" name="website" size="35" maxlength="255" value="{WEBSITE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_LOCATION}</span></td>
	<td class="row2"><input class="post" type="text" name="location" size="35" maxlength="100" value="{LOCATION}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_FLAG}:</span></td>
	<td class="row2">
	<span class="gensmall">
		<table>
			<tr>
				<td>{FLAG_SELECT}&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><img src="../images/flags/{FLAG_START}" width="16" height="11" name="user_flag" /></td>
			</tr>
		</table>
	</span>
	</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_OCCUPATION}</span></td>
	<td class="row2"><input class="post" type="text" name="occupation" size="35" maxlength="100" value="{OCCUPATION}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_INTERESTS}</span></td>
	<td class="row2"><input class="post" type="text" name="interests" size="35" maxlength="150" value="{INTERESTS}" /></td>
</tr>
<tr>
	<td class="row1">
		<span class="gen">{L_EXTRA_PROFILE_INFO}:</span><br />
		<span class="gensmall">{L_EXTRA_PROFILE_INFO_EXPLAIN}</span>
	</td>
	<td class="row2">
	<textarea name="selfdes" style="width: 475px"  rows="10" cols="45" class="post">{SELFDES}</textarea>
	</td>
</tr>
<!-- Start add - Gender MOD -->
<tr>
	<td class="row1"><span class="gen">{L_GENDER}:</span></td>
	<td class="row2">
	<input type="radio" name="gender" value="0" {GENDER_NO_SPECIFY_CHECKED} />
	<span class="gen">{L_GENDER_NOT_SPECIFY}</span>&nbsp;&nbsp;
	<input type="radio" name="gender" value="1" {GENDER_MALE_CHECKED} />
	<span class="gen">{L_GENDER_MALE}</span>&nbsp;&nbsp;
	<input type="radio" name="gender" value="2" {GENDER_FEMALE_CHECKED} />
	<span class="gen">{L_GENDER_FEMALE}</span></td>
</tr>
<!-- End add - Gender MOD -->
<!-- Start add - Birthday MOD -->
<tr>
	<td class="row1"><span class="gen">{L_BIRTHDAY}</span></td>
	<td class="row2">{S_BIRTHDAY}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_NEXT_BIRTHDAY_GREETING}:</span><br /><span class="gensmall">{L_NEXT_BIRTHDAY_GREETING_EXPLAIN}<br /></span></td>
	<td class="row2"><input class="post" type="text" name="next_birthday_greeting" size="5" maxlength="4" value="{NEXT_BIRTHDAY_GREETING}" /></td>
</tr>
<!-- End add - Birthday MOD -->
<tr>
	<td class="row1"><span class="gen">{L_SIGNATURE}</span><br />
	<span class="gensmall">{L_SIGNATURE_EXPLAIN}<br />
	<br />
	{HTML_STATUS}<br />
	{BBCODE_STATUS}<br />
	{SMILIES_STATUS}</span></td>
	<td class="row2">
	<textarea class="post" name="signature" rows="6" cols="45">{SIGNATURE}</textarea>
	</td>
</tr>
<!-- Custom Profile Fields MOD start + -->
<!-- BEGIN switch_custom_fields -->
<tr>
	<td class="row3" colspan="2"><span class="gensmall">{switch_custom_fields.L_CUSTOM_FIELD_NOTICE}</span></td>
</tr>
<!-- END switch_custom_fields -->
<!-- BEGIN custom_fields -->
<tr>
	<td class="row1"><span class="gen">{custom_fields.NAME}:{custom_fields.REQUIRED}{custom_fields.ADMIN_ONLY}</span>
	<!-- BEGIN switch_description -->
	<br /><span class="gensmall">{custom_fields.switch_description.DESCRIPTION}</span>
	<!-- END switch_description -->
	</td>
	<td class="row2">
	{custom_fields.FIELD}
	</td>
</tr>
<!-- END custom_fields -->
<!-- Custom Profile Fields MOD finish + -->
<tr>
	<td class="catsides" colspan="2"><span class="cattitle">&nbsp;</span></td>
</tr>
<tr>
	<th colspan="2">{L_PREFERENCES}</th>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_HIDE_USER}</span></td>
	<td class="row2">
	<input type="radio" name="hideonline" value="1" {HIDE_USER_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="hideonline" value="0" {HIDE_USER_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_PUBLIC_VIEW_EMAIL}</span></td>
	<td class="row2">
	<input type="radio" name="viewemail" value="1" {VIEW_EMAIL_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="viewemail" value="0" {VIEW_EMAIL_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_MASS_EMAIL}</span></td>
	<td class="row2">
	<input type="radio" name="allowmassemail" value="1" {ALLOW_MASS_EMAIL_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="allowmassemail" value="0" {ALLOW_MASS_EMAIL_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_NOTIFY_ON_REPLY}</span></td>
	<td class="row2">
	<input type="radio" name="notifyreply" value="1" {NOTIFY_REPLY_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="notifyreply" value="0" {NOTIFY_REPLY_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_PM_IN}</span></td>
	<td class="row2">
	<input type="radio" name="allowpmin" value="1" {ALLOW_PM_IN_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="allowpmin" value="0" {ALLOW_PM_IN_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_NOTIFY_ON_PRIVMSG}</span></td>
	<td class="row2">
	<input type="radio" name="notifypm" value="1" {NOTIFY_PM_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="notifypm" value="0" {NOTIFY_PM_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_POPUP_ON_PRIVMSG}</span></td>
	<td class="row2">
	<input type="radio" name="popup_pm" value="1" {POPUP_PM_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="popup_pm" value="0" {POPUP_PM_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
	<tr>
	<td class="row1"><span class="gen">{L_PROFILE_VIEW_POPUP}</span></td>
	<td class="row2">
	<input type="radio" name="profile_view_popup" value="1" {PROFILE_VIEW_POPUP_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="profile_view_popup" value="0" {PROFILE_VIEW_POPUP_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_ALWAYS_ADD_SIGNATURE}</span></td>
	<td class="row2">
	<input type="radio" name="attachsig" value="1" {ALWAYS_ADD_SIGNATURE_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="attachsig" value="0" {ALWAYS_ADD_SIGNATURE_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_ALWAYS_SET_BOOKMARK}</span></td>
	<td class="row2">
	<input type="radio" name="setbm" value="1" {ALWAYS_SET_BOOKMARK_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="setbm" value="0" {ALWAYS_SET_BOOKMARK_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_ALWAYS_ALLOW_BBCODE}</span></td>
	<td class="row2">
	<input type="radio" name="allowbbcode" value="1" {ALWAYS_ALLOW_BBCODE_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="allowbbcode" value="0" {ALWAYS_ALLOW_BBCODE_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_ALWAYS_ALLOW_HTML}</span></td>
	<td class="row2">
	<input type="radio" name="allowhtml" value="1" {ALWAYS_ALLOW_HTML_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="allowhtml" value="0" {ALWAYS_ALLOW_HTML_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_ALWAYS_ALLOW_SMILIES}</span></td>
	<td class="row2">
	<input type="radio" name="allowsmilies" value="1" {ALWAYS_ALLOW_SMILIES_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="allowsmilies" value="0" {ALWAYS_ALLOW_SMILIES_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_SHOW_AVATARS}:</span></td>
	<td class="row2">
		<label><input type="radio" name="user_showavatars" value="1" {ALWAYS_SHOW_AVATARS_YES} /><span class="gen">&nbsp;{L_YES}</span></label>&nbsp;&nbsp;
		<label><input type="radio" name="user_showavatars" value="0" {ALWAYS_SHOW_AVATARS_NO} /><span class="gen">&nbsp;{L_NO}</span></label>
	</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_SHOW_SIGNATURES}:</span></td>
	<td class="row2">
		<label><input type="radio" name="user_showsignatures" value="1" {ALWAYS_SHOW_SIGNATURES_YES} /><span class="gen">&nbsp;{L_YES}</span></label>&nbsp;&nbsp;
		<label><input type="radio" name="user_showsignatures" value="0" {ALWAYS_SHOW_SIGNATURES_NO} /><span class="gen">&nbsp;{L_NO}</span></label>&nbsp;&nbsp;
	</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_ALWAYS_ALLOW_SWEARYWORDS}:</span></td>
	<td class="row2">
		<label><input type="radio" name="user_allowswearywords" value="1" {ALWAYS_SWEARY_WORDS_YES} />
		<span class="gen">{L_YES}</span></label>&nbsp;&nbsp;
		<label><input type="radio" name="user_allowswearywords" value="0" {ALWAYS_SWEARY_WORDS_NO} />
		<span class="gen">{L_NO}</span></label></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_TOPICS_PER_PAGE}:</span></td>
	<td class="row2"><input class="post" type="text" name="user_topics_per_page" size="3" maxlength="4" value="{TOPICS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_POSTS_PER_PAGE}:</span></td>
	<td class="row2"><input class="post" type="text" name="user_posts_per_page" size="3" maxlength="4" value="{POSTS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_HOT_THRESHOLD}:</span></td>
	<td class="row2"><input class="post" type="text" name="user_hot_threshold" size="3" maxlength="4" value="{HOT_TOPIC}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_VIEW_POSTS_DAYS}:</span></td>
	<td class="row2">{USER_POST_SHOW_DAYS_SELECT}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_VIEW_POSTS_KEY}:</span></td>
	<td class="row2">{USER_POST_SORTBY_TYPE_SELECT}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_VIEW_POSTS_DIR}:</span></td>
	<td class="row2">{USER_POST_SORTBY_DIR_SELECT}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_BOARD_LANGUAGE}</span></td>
	<td class="row2">{LANGUAGE_SELECT}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_BOARD_STYLE}</span></td>
	<td class="row2">{STYLE_SELECT}</td>
</tr>
<tr>
	<td class="row1">
		<span class="gen">{L_TIME_MODE}</span><br />
		<span class="gensmall">{L_TIME_MODE_TEXT}</span>
	</td>
	<td class="row2 tdnw">
		<span class="gen">{L_TIME_MODE_MANUAL}</span><br />
		<span class="gen">&nbsp;&nbsp;{L_TIME_MODE_DST}:</span>
		<input type="radio" name="time_mode" value="1" {TIME_MODE_MANUAL_DST_CHECKED} /><span class="gen">{L_YES}{L_TIME_MODE_DST_ON}</span>&nbsp;
		<input type="radio" name="time_mode" value="0" {TIME_MODE_MANUAL_CHECKED} /><span class="gen">{L_NO}{L_TIME_MODE_DST_OFF}</span>&nbsp;
		<input type="radio" name="time_mode" value="2" {TIME_MODE_SERVER_SWITCH_CHECKED} /><span class="gen">{L_TIME_MODE_DST_SERVER}</span><br />
		<span class="gen">&nbsp;&nbsp;{L_TIME_MODE_DST_TIME_LAG}: </span><input type="text" name="dst_time_lag" value="{DST_TIME_LAG}" maxlength="3" size="3" class="post" /><span class="gen">{L_TIME_MODE_DST_MN}</span><br />
		<span class="gen">&nbsp;&nbsp;{L_TIME_MODE_TIMEZONE}: </span><span class="gensmall">{TIMEZONE_SELECT}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_DATE_FORMAT}</span></td>
	<td class="row2">{DATE_FORMAT}</td>
</tr>
<tr>
	<td class="catSides" colspan="2"><span class="cattitle">&nbsp;</span></td>
</tr>
<tr>
	<th colspan="2" height="12" valign="middle">{L_AVATAR_PANEL}</th>
</tr>
<tr align="center">
	<td class="row1" colspan="2">
		<table class="tw70pct s2px">
		<tr>
			<td width="65%"><span class="gensmall">{L_AVATAR_EXPLAIN}</span></td>
			<td class="tdalignc">
				<span class="gensmall">{L_CURRENT_IMAGE}</span><br />
				{AVATAR}<br />
				<input type="checkbox" name="avatardel" />&nbsp;<span class="gensmall">{L_DELETE_AVATAR}</span>
			</td>
		</tr>
		</table>
	</td>
</tr>

<!-- BEGIN avatar_local_upload -->
<tr>
	<td class="row1"><span class="gen">{L_UPLOAD_AVATAR_FILE}</span></td>
	<td class="row2">
		<input type="hidden" name="MAX_FILE_SIZE" value="{AVATAR_SIZE}" />
		<input type="file" name="avatar" class="post" style="width: 200px;" />
	</td>
</tr>
<!-- END avatar_local_upload -->
<!-- BEGIN avatar_remote_upload -->
<tr>
	<td class="row1"><span class="gen">{L_UPLOAD_AVATAR_URL}</span></td>
	<td class="row2"><input class="post" type="text" name="avatarurl" size="40" style="width: 200px;" /></td>
</tr>
<!-- END avatar_remote_upload -->
<!-- BEGIN avatar_remote_link -->
<tr>
	<td class="row1"><span class="gen">{L_LINK_REMOTE_AVATAR}</span></td>
	<td class="row2"><input class="post" type="text" name="avatarremoteurl" size="40" style="width: 200px;" /></td>
</tr>
<!-- END avatar_remote_link -->
<!-- BEGIN avatar_local_gallery -->
<tr>
	<td class="row1"><span class="gen">{L_AVATAR_GALLERY}</span></td>
	<td class="row2"><input type="submit" name="avatargallery" value="{L_SHOW_GALLERY}" class="liteoption" /></td>
</tr>
<!-- END avatar_local_gallery -->
<!-- BEGIN switch_gravatar -->
<tr>
	<td class="row1"><span class="gen">{L_GRAVATAR}</span><br /><span class="gensmall">{L_GRAVATAR_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="gravatar" value="{GRAVATAR}" size="40" class="post" style="width: 200px;" /></td>
</tr>
<!-- END switch_gravatar -->
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
<tr><th colspan="2">{L_SPECIAL}</th></tr>
<tr><td class="row1" colspan="2"><span class="gensmall">{L_SPECIAL_EXPLAIN}</span></td></tr>
<tr>
	<td class="row1"><span class="gen">{L_UPLOAD_QUOTA}</span></td>
	<td class="row2">{S_SELECT_UPLOAD_QUOTA}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_PM_QUOTA}</span></td>
	<td class="row2">{S_SELECT_PM_QUOTA}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_ALLOW_PM}</span></td>
	<td class="row2">
	<input type="radio" name="user_allowpm" value="1" {ALLOW_PM_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="user_allowpm" value="0" {ALLOW_PM_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_ALLOW_AVATAR}</span></td>
	<td class="row2">
	<input type="radio" name="user_allowavatar" value="1" {ALLOW_AVATAR_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="user_allowavatar" value="0" {ALLOW_AVATAR_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_DISABLE_UPI2DB}</span></td>
	<td class="row2">
	<input type="radio" name="user_upi2db_disable" value="1" {DISABLE_UPI2DB_YES} />
	<span class="gen">{L_YES}</span>&nbsp;&nbsp;
	<input type="radio" name="user_upi2db_disable" value="0" {DISABLE_UPI2DB_NO} />
	<span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_GROUP_DEFAULT}</span><br /><span class="gensmall">{L_GROUP_DEFAULT_EXPLAIN}</span></td>
	<td class="row2">{USER_GROUP_ID}</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_USER_COLOR}</span><br /><span class="gensmall">{L_USER_COLOR_EXPLAIN}</span></td>
	<td class="row2">
		#<input type="text" class="post" name="user_color" value="{USER_COLOR}" size="9" maxlength="7" onblur="ColorExample(this.value);" />&nbsp;
		<span id="color_group_example"{USER_COLOR_STYLE}>{USERNAME}</span>
	</td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_SELECT_RANK1}</span></td>
	<td class="row2"><select name="user_rank">{RANK1_SELECT_BOX}</select></td>
</tr>
<tr>
<tr>
	<td class="row1"><span class="gen">{L_SELECT_RANK2}</span></td>
	<td class="row2"><select name="user_rank2">{RANK2_SELECT_BOX}</select></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_SELECT_RANK3}</span></td>
	<td class="row2"><select name="user_rank3">{RANK3_SELECT_BOX}</select></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_SELECT_RANK4}</span></td>
	<td class="row2"><select name="user_rank4">{RANK4_SELECT_BOX}</select></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_SELECT_RANK5}</span></td>
	<td class="row2"><select name="user_rank5">{RANK5_SELECT_BOX}</select></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_BANCARD}:</span><br /><span class="gensmall">{L_BANCARD_EXPLAIN}<br /></td>
	<td class="row2"><input type="text" class="post" style="width: 40px;" name="user_ycard" size="4" maxlength="4" value="{BANCARD}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_POSTCOUNT}:</span><br /><span class="gensmall">{L_POSTCOUNT_EXPLAIN}<br /></td>
	<td class="row2"><input type="text" class="post" style="width: 40px;" name="user_posts" size="4" maxlength="7" value="{POSTS}" /></td>
</tr>
<tr><th colspan="2">&nbsp;</th></tr>
<tr>
	<td class="row1"><span class="gen"><strong><i>{L_USER_ACTIVE}</i></strong></span></td>
	<td class="row2"><input type="radio" name="user_status" value="1" {USER_ACTIVE_YES} /><span class="gen">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="user_status" value="0" {USER_ACTIVE_NO} /><span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><i>{L_INACTIVE_USER_FEATURE}</i></span><br /><span class="gensmall">{L_INACTIVE_USER_FEATURE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="user_mask" value="1" {USER_MASK_YES} /><span class="gen">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="user_mask" value="0" {USER_MASK_NO} /><span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_FAILED_LOGINS_COUNTER}</span></td>
	<td class="row2"><input type="text" class="post" style="width: 40px;" name="user_login_attempts" size="4" maxlength="4" value="{FAILED_LOGINS_COUNTER_VALUE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><strong><i>{L_DELETE_USER}</i></strong></span></td>
	<td class="row2"><input type="checkbox" name="deleteuser" />&nbsp;{L_DELETE_USER_EXPLAIN}</td>
</tr>
<tr><td class="cat tdalignc" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>