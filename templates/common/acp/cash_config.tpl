{NAVBAR}
<h1>{L_CASH_CONFIGURATION_TITLE}</h1>
<p>{L_CASH_CONFIGURATION_EXPLAIN}</p>

<form action="{S_CASH_CONFIG_ACTION}" method="post">
<input type="hidden" name="set" value="general">
<table class="forumline">
<tr><th colspan="2">{L_CASH_SETTINGS}</th></tr>
<tr>
	<td class="row1">{L_CASH_DISABLED}</td>
	<td class="row2"><input type="radio" name="cash_disable" value="1" {DISABLE_CASH_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="cash_disable" value="0" {DISABLE_CASH_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1">{L_CASH_ADMINCP}</td>
	<td class="row2"><input type="radio" name="cash_adminbig" value="1" {ADMIN_BIG} /> {L_SIDEBAR}&nbsp;&nbsp;<input type="radio" name="cash_adminbig" value="0" {ADMIN_SMALL} /> {L_MENU}</td>
</tr>
<tr>
	<td class="row1">{L_CASH_ADMINNAVBAR}</td>
	<td class="row2"><input type="radio" name="cash_adminnavbar" value="1" {ADMINNAVBAR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="cash_adminnavbar" value="0" {ADMINNAVBAR_NO} /> {L_NO}</td>
</tr>

<tr><th colspan="2">{L_MESSAGES}</th></tr>
<tr>
	<td class="row1">{L_CASH_MESSAGE}</td>
	<td class="row2"><input type="radio" name="cash_display_after_posts" value="1" {DISPLAY_AFTER_POSTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="cash_display_after_posts" value="0" {DISPLAY_AFTER_POSTS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1">{L_CASH_DISPLAY_MESSAGE}<br /><span class="gensmall">{L_CASH_DISPLAY_MESSAGE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="40" name="cash_post_message" value="{POST_MESSAGE}" /></td>
</tr>
<tr><th colspan="2">{L_SPAM}</th></tr>
<tr>
	<td class="row1">{L_CASH_SPAM_DISABLE_NUM}</td>
	<td class="row2"><input class="post" type="text" maxlength="10" size="10" name="cash_disable_spam_num" value="{DISABLE_SPAM_NUM}" /></td>
</tr>
<tr>
	<td class="row1">{L_CASH_SPAM_DISABLE_TIME}</td>
	<td class="row2"><input class="post" type="text" maxlength="10" size="10" name="cash_disable_spam_time" value="{DISABLE_SPAM_TIME}" /></td>
</tr>
<tr>
	<td class="row1">{L_CASH_SPAM_DISABLE_MESSAGE}</td>
	<td class="row2"><input class="post" type="text" maxlength="255" size="40" name="cash_disable_spam_message" value="{DISABLE_SPAM_MESSAGE}" /></td>
</tr>
<tr>
	<td class="cat" colspan="2">
	{S_HIDDEN_FIELDS}
	<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>
</form>

<br clear="all" />
