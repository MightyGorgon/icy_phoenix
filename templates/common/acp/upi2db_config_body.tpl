<h1>{L_CONFIGURATION_TITLE}</h1>
<p>{L_CONFIGURATION_EXPLAIN}<br />
UPI2DB Version: {UPI2DB_VERSION_NUMBER}</p>

<form action="{S_CONFIG_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_SETUP_UPI2DB}</th></tr>
<tr>
	<td class="row1">{L_UPI2DB_ON}<br /><span class="gensmall">{L_UPI2DB_ON_EXPLAIN}</span></td>
	<td class="row2" width="35%"><input type="radio" name="upi2db_on" value="1" {UPI2DB_ON_1} /> {L_YES} <input type="radio" name="upi2db_on" value="0" {UPI2DB_ON_0} /> {L_NO} <input type="radio" name="upi2db_on" value="2" {UPI2DB_ON_2} /> {L_USER_SELECT}</td>
</tr>
<tr>
	<td class="row1">{L_UPI2DB_DAYS} <br /><span class="gensmall">{L_UPI2DB_DAYS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="upi2db_auto_read" value="{UPI2DB_DAYS}" /> {L_UPI2DB_DAYS_TAGEN}</td>
</tr>
<!-- BEGIN switch_upi2db_full -->
<tr>
	<td class="row1">{L_UPI2DB_DEL_MARK} <br /><span class="gensmall">{L_UPI2DB_DEL_MARK_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="upi2db_del_mark" value="{UPI2DB_DEL_MARK}" /> {L_UPI2DB_DAYS_TAGEN}</td>
</tr>
<tr>
	<td class="row1">{L_UPI2DB_DEL_PERM} <br /><span class="gensmall">{L_UPI2DB_DEL_PERM_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="upi2db_del_perm" value="{UPI2DB_DEL_PERM}" /> {L_UPI2DB_DAYS_TAGEN}</td>
</tr>
<!-- END switch_upi2db_full -->
<tr>
	<td class="row1">{L_EDIT_AS_NEW}<br /><span class="gensmall">{L_EDIT_AS_NEW_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="upi2db_edit_as_new" value="1" {EDIT_AS_NEW_YES} /> {L_YES} <input type="radio" name="upi2db_edit_as_new" value="0" {EDIT_AS_NEW_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1">{L_LAST_EDIT_AS_NEW}<br /><span class="gensmall">{L_LAST_EDIT_AS_NEW_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="upi2db_last_edit_as_new" value="1" {LAST_EDIT_AS_NEW_YES} /> {L_YES} <input type="radio" name="upi2db_last_edit_as_new" value="0" {LAST_EDIT_AS_NEW_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1">{L_EDIT_TOPIC_FIRST}<br /><span class="gensmall">{L_EDIT_TOPIC_FIRST_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="upi2db_edit_topic_first" value="1" {EDIT_TOPIC_FIRST_YES} /> {L_YES} <input type="radio" name="upi2db_edit_topic_first" value="0" {EDIT_TOPIC_FIRST_NO} /> {L_NO}</td>
</tr>
<!-- BEGIN switch_upi2db_full -->
<!--
<tr>
	<td class="row1">{L_UNREAD_COLOR}</span></td>
	<td class="row2"><input class="post" type="text" size="6" maxlength="6" name="upi2db_unread_color" style="background:#{UNREAD_COLOR}" value="{UNREAD_COLOR}" /></td>
</tr>
<tr>
	<td class="row1">{L_EDIT_COLOR}</span></td>
	<td class="row2"><input class="post" type="text" size="6" maxlength="6" name="upi2db_edit_color" style="background:#{EDIT_COLOR}" value="{EDIT_COLOR}" /></td>
</tr>
<tr>
	<td class="row1">{L_MARK_COLOR}</span></td>
	<td class="row2"><input class="post" type="text" size="6" maxlength="6" name="upi2db_mark_color" style="background:#{MARK_COLOR}" value="{MARK_COLOR}" /></td>
</tr>
-->
<!-- END switch_upi2db_full -->
<tr>
	<td class="row1" rowspan="3">{L_MAX_NEW_POSTS} <br /><span class="gensmall">{L_MAX_NEW_POSTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="upi2db_max_new_posts_admin" value="{MAX_NEW_POSTS_ADMIN}" />&nbsp;{ADMIN}</td>
</tr>
<tr>
	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="upi2db_max_new_posts_mod" value="{MAX_NEW_POSTS_MOD}" />&nbsp;{MOD}</td>
</tr>
<tr>
	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="upi2db_max_new_posts" value="{MAX_NEW_POSTS}" />&nbsp;{NORMAL}</td>
</tr>
<!-- BEGIN switch_upi2db_full -->
<tr>
	<td class="row1">{L_MAX_PERMANENT_TOPICS} <br /><span class="gensmall">{L_MAX_PERMANENT_TOPICS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="3" name="upi2db_max_permanent_topics" value="{MAX_PERMANENT_TOPICS}" /></td>
</tr>
<tr>
	<td class="row1">{L_MAX_MARK_POSTS} <br /><span class="gensmall">{L_MAX_MARK_POSTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="3" name="upi2db_max_mark_posts" value="{MAX_MARK_POSTS}" /></td>
</tr>
<!-- END switch_upi2db_full -->
<tr>
	<td>
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><th colspan="4">{L_CONDITION_SETUP}</th></tr>
			<tr>
				<td class="row1"><b>{L_GROUP_USER}</b></td>
				<td class="row1"><b>{L_USER_ALLOW_UPI2DB}</b></td>
				<td class="row1"><b>{L_MIN_POSTS}</b></td>
				<td class="row1"><b>{L_MIN_REG_DAYS}</b></td>
			</tr>
			<tr>
				<td class="row1">{L_USER_WITHOUT_GROUP}</td>
				<td class="row1"><input name="upi2db_no_group_upi2db_on" type="radio" value="1" {NO_GROUP_UPI2DB_ON_YES}> {L_YES} <input name="upi2db_no_group_upi2db_on" type="radio" value="0" {NO_GROUP_UPI2DB_ON_NO}> {L_NO}</td>
				<td class="row1"><input name="upi2db_no_group_min_posts" type="text" value="{NO_GROUP_MIN_POSTS}" size="4" maxlength="4"></td>
				<td class="row1"><input name="upi2db_no_group_min_regdays" type="text" value="{NO_GROUP_MIN_REGDAYS}" size="4" maxlength="4"></td>
			</tr>
			<tr>
				<td class="row1"><b>{L_GROUP_NAME}</b></td>
				<td class="row1"><b>{L_GROUP_ALLOW_UPI2DB}</b></td>
				<td class="row1"><b>{L_MIN_POSTS}</b></td>
				<td class="row1"><b>{L_MIN_REG_DAYS}</b></td>
			</tr>
			<!-- BEGIN group_loop -->
			<tr>
				<td class="row1">{group_loop.GROUP_NAME}</td>
				<td class="row1"><input name="group_upi2db_on[{group_loop.GROUP_ID}]" type="radio" value="1" {group_loop.GROUP_UPI2DB_ON_YES}> {L_YES} <input name="group_upi2db_on[{group_loop.GROUP_ID}]" type="radio" value="0" {group_loop.GROUP_UPI2DB_ON_NO}> {L_NO}</td>
				<td class="row1"><input name="group_min_posts[{group_loop.GROUP_ID}]" type="text" value="{group_loop.GROUP_MIN_POSTS}" size="4" maxlength="4"></td>
				<td class="row1"><input name="group_min_regdays[{group_loop.GROUP_ID}]" type="text" value="{group_loop.GROUP_MIN_REGDAYS}" size="4" maxlength="4"></td>
			</tr>
			<!-- END group_loop -->
		</table>
	</td>
</tr>
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>
<script type="text/javascript">
function Active(what) {if(!document.layers) {what.style.backgroundColor='#FFFFFF'}}
function NotActive(what) {if(!document.layers) {what.style.backgroundColor=''}}
</script>
<br clear="all" />