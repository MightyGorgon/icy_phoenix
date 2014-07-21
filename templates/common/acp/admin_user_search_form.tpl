<h1>{L_USER_SEARCH}</h1>
<p>{L_SEARCH_EXPLAIN}</p>

<form method="post" name="post" action="{S_SEARCH_ACTION}">
<input type="hidden" name="dosearch" value="true" />
<table class="forumline">
<tr><th>&nbsp;</th></tr>
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_USERNAME}:&nbsp;</strong><input class="post" type="text" name="username" value="" maxlength="255" size="25" />&nbsp;<input type="submit" class="post" name="search_username" value="{L_SEARCH}" /><strong> {L_REGEX} </strong><input type="checkbox" name="search_username_regex" value="true" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_USERNAME_EXPLAIN}</span></td></tr>
<tr><th>&nbsp;</th></tr>
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_EMAIL}:&nbsp;</strong><input class="post" type="text" name="email" value="" maxlength="255" size="25" />&nbsp;<input type="submit" class="post" name="search_email" value="{L_SEARCH}" /><strong> {L_REGEX} </strong><input type="checkbox" name="search_email_regex" value="true" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_EMAIL_EXPLAIN}</span></td></tr>
<tr><th>&nbsp;</th></tr>
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_IP}:&nbsp;</strong><input class="post" type="text" name="ip_address" value="" maxlength="255" size="25" />&nbsp;<input type="submit" class="post" name="search_ip" value="{L_SEARCH}" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_IP_EXPLAIN}</span></td></tr>
<tr><th>&nbsp;</th></tr>
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_USERS_JOINED}:</strong>&nbsp;<select name="date_type" class="post"><option value="before" selected="selected">{L_BEFORE}</option><option value="after">{L_AFTER}</option></select>&nbsp;<input class="post" type="text" name="date_year" value="{YEAR}" size="4" maxlength="4" />/<input class="post" type="text" name="date_month" value="{MONTH}" size="2" maxlength="2" />/<input class="post" type="text" name="date_day" value="{DAY}" maxlength="2" size="2" />&nbsp;<input type="submit" class="post" name="search_joindate" value="{L_SEARCH}" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_JOIN_DATE_EXPLAIN}:</span></td></tr>
<tr><th>&nbsp;</th></tr>
<!-- BEGIN groups_exist -->
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_GROUP_MEMBERS}:</strong>&nbsp;<select name="group_id" class="post">{GROUP_LIST}</select>&nbsp;<input type="submit" class="post" name="search_group" value="{L_SEARCH}" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_GROUP_EXPLAIN}</span></td></tr>
<tr><th>&nbsp;</th></tr>
<!-- END groups_exist -->
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_POSTCOUNT}:</strong>&nbsp;<select name="postcount_type"><option value="equals" selected="selected">{L_EQUALS}</option><option value="lesser">{L_LESSERTHAN}</option><option value="greater">{L_GREATERTHAN}</option></select>&nbsp;<input class="post" type="text" name="postcount_value" value="" maxlength="25" size="5" />&nbsp;<input type="submit" class="post" name="search_postcount" value="{L_SEARCH}" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_POSTCOUNT_EXPLAIN}</span></td></tr>
<tr><th>&nbsp;</th></tr>
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_USERFIELD}:</strong>&nbsp;<select name="userfield_type"><option value="icq" selected="selected">{L_ICQ}</option><option value="aim">{L_AIM}</option><option value="msn">{L_MSN}</option><option value="yahoo">{L_YAHOO}</option><option value="website">{L_WEBSITE}</option><option value="location">{L_LOCATION}</option><option value="interests">{L_INTERESTS}</option></select>&nbsp;<input class="post" type="text" name="userfield_value" value="" maxlength="25" size="25" />&nbsp;<input type="submit" class="post" name="search_userfield" value="{L_SEARCH}" /><strong> {L_REGEX} </strong><input type="checkbox" name="search_userfield_regex" value="true" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_USERFIELD_EXPLAIN}</span></td></tr>
<tr><th>&nbsp;</th></tr>
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_LASTVISITED}:</strong>&nbsp;<select name="lastvisited_type"><option value="in">{L_IN_THE_LAST}</option><option value="after">{L_AFTER_THE_LAST}</option></select>&nbsp;<select name="lastvisited_days">{LASTVISITED_LIST}</select>&nbsp;<input type="submit" class="post" name="search_lastvisited" value="{L_SEARCH}" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_LASTVISITED_EXPLAIN}</span></td></tr>
<tr><th>&nbsp;</th></tr>
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_LANGUAGE}:</strong>&nbsp;{LANGUAGE_LIST}&nbsp;<input type="submit" class="post" name="search_language" value="{L_SEARCH}" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_LANGUAGE_EXPLAIN}</span></td></tr>
<tr><th>&nbsp;</th></tr>
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_TIMEZONE}:</strong>&nbsp;{TIMEZONE_LIST}&nbsp;<input type="submit" class="post" name="search_timezone" value="{L_SEARCH}" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_TIMEZONE_EXPLAIN}</span></td></tr>
<tr><th>&nbsp;</th></tr>
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_STYLE}:</strong>&nbsp;{STYLE_LIST}&nbsp;<input type="submit" class="post" name="search_style" value="{L_SEARCH}" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_STYLE_EXPLAIN}</span></td></tr>
<tr><th>&nbsp;</th></tr>
<!-- BEGIN forums_exist -->
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_MODERATORS_OF}:</strong>&nbsp;<select name="moderators_forum">{FORUMS_LIST}</select>&nbsp;<input type="submit" class="post" name="search_moderators" value="{L_SEARCH}" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_MODERATORS_OF_EXPLAIN}</span></td></tr>
<th width="100%"></th>
<!-- END forums_exist -->
<tr><td class="row2 tdnw"><span class="genmed"><strong>{L_ADMINS}:</strong>&nbsp;<select name="misc" class="post"><option value="admins" selected="selected">{L_ADMINS}</option><option value="mods">{L_MODS}</option><option value="banned">{L_BANNED_USERS}</option><option value="disabled">{L_DISABLED_USERS}</option><option value="disabled_pms">{L_USERS_DISABLED_PMS}</option></select>&nbsp;<input type="submit" class="post" name="search_misc" value="{L_SEARCH}" /></span></td></tr>
<tr><td class="row3"><span class="gensmall">{L_MISC_EXPLAIN}</span></td></tr>
<tr><td class="cat">&nbsp;</td></tr>
</table>
</form>