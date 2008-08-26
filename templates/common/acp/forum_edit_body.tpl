<script type="text/javascript">
<!--
function update_icon(newimage)
{
	if(newimage != '')
	{
		document.icon_image.src = '../' + newimage;
		document.post.icon.value = newimage;
	}
	else
	{
		document.icon_image.src = '../images/spacer.gif';
		document.post.icon.value = '';
	}
}
//-->
</script>

<p>{L_FORUM_EXPLAIN}</p>

<form action="{S_FORUM_ACTION}" method="post" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_FORUM_SETTINGS}</th></tr>
<tr>
	<td class="row1">{L_FORUM_NAME}</td>
	<td class="row2"><input type="text" size="25" name="forumname" value="{FORUM_NAME}" class="post" /></td>
</tr>
<tr>
	<td class="row1">{L_FORUM_DESCRIPTION}</td>
	<td class="row2"><textarea rows="5" cols="45" wrap="virtual" name="forumdesc" class="post">{DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1" wrap="wrap" width="300">{L_ICON}<br /><span class="gensmall">{L_ICON_EXPLAIN}</span></td>
	<td class="row2">{ICON_LIST}</td>
</tr>
<tr>
	<td class="row1">{L_CATEGORY}</td>
	<td class="row2"><select name="c">{S_CAT_LIST}</select></td>
</tr>
<tr>
	<td class="row1">{L_COPY_AUTH}<!-- <br /><span class="gensmall">{L_COPY_AUTH_EXPLAIN}</span> --></td>
	<td class="row2"><select name="dup_auth">{S_FORUM_LIST}</select></td>
</tr>
<tr>
	<td class="row1">{L_FORUM_STATUS}</td>
	<td class="row2"><select name="forumstatus">{S_STATUS_LIST}</select></td>
</tr>
<tr>
	<td class="row1">{L_FORUM_THANK}</td>
	<td class="row2">{S_THANK_RADIO}</td>
</tr>
<tr>
	<td class="row1">{L_FORUM_NOTIFY}</td>
	<td class="row2"><select name="notify_enable">{S_NOTIFY_ENABLED}</select></td>
</tr>
<tr>
	<td class="row1">{L_POSTCOUNT}</td>
	<td class="row2"><input type="checkbox" name="forum_postcount" value="1" {S_FORUM_POSTCOUNT} />&nbsp;{L_ENABLED}</td>
</tr>
<tr>
	<td class="row1">{L_AUTO_PRUNE}</td>
	<td class="row2"><table cellspacing="0" cellpadding="1" border="0">
		<tr>
		<td align="right" valign="middle">{L_ENABLED}</td>
		<td align="left" valign="middle"><input type="checkbox" name="prune_enable" value="1" {S_PRUNE_ENABLED} /></td>
		</tr>
		<tr>
		<td align="right" valign="middle">{L_PRUNE_DAYS}</td>
		<td align="left" valign="middle">&nbsp;<input type="text" name="prune_days" value="{PRUNE_DAYS}" size="5" class="post" />&nbsp;{L_DAYS}</td>
		</tr>
		<tr>
		<td align="right" valign="middle">{L_PRUNE_FREQ}</td>
		<td align="left" valign="middle">&nbsp;<input type="text" name="prune_freq" value="{PRUNE_FREQ}" size="5" class="post" />&nbsp;{L_DAYS}</td>
		</tr>
	</table></td>
</tr>
<tr>
	<td class="row1">{L_LINK}&nbsp;</td>
	<td class="row2 row-center">
		<table cellspacing="0" cellpadding="3" border="0">
		<tr>
			<td align="right" valign="top">{L_FORUM_LINK}&nbsp;</td>
			<td>
				<input type="text" name="forum_link" value="{FORUM_LINK}" size="60" class="post" /><br />
				<span class="gensmall">{L_FORUM_LINK_EXPLAIN}</span>
			</td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap" valign="top">{L_FORUM_LINK_INTERNAL}&nbsp;</td>
			<td class="row">
				<input type="radio" name="forum_link_internal" value="1" {FORUM_LINK_INTERNAL_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="forum_link_internal" value="0" {FORUM_LINK_INTERNAL_NO} />&nbsp;{L_NO}<br />
				<span class="gensmall">{L_FORUM_LINK_INTERNAL_EXPLAIN}</span>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top">{L_FORUM_LINK_HIT_COUNT}&nbsp;</td>
			<td>
				<input type="radio" name="forum_link_hit_count" value="1" {FORUM_LINK_HIT_COUNT_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="forum_link_hit_count" value="0" {FORUM_LINK_HIT_COUNT_NO} />&nbsp;{L_NO}<br />
				<span class="gensmall">&nbsp;{L_FORUM_LINK_HIT_COUNT_EXPLAIN}</span>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr><th colspan="2">{L_MOD_OS_FORUMRULES}</th></tr>
<tr>
	<td class="row1">{L_RULES_DISPLAY_TITLE}</td>
	<td class="row2">
		<table cellpadding="2" cellspacing="0" border="0">
			<tr>
				<td><input type="checkbox" name="rules_display_title" value="1" {S_RULES_DISPLAY_TITLE_ENABLED} /></td>
				<td>{L_ENABLED}</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="row1">{L_RULES_CUSTOM_TITLE}</td>
	<td class="row2"><input type="text" name="rules_custom_title" value="{RULES_CUSTOM_TITLE}" size="50" maxlength="80" class="post" /></td>
</tr>
<tr>
	<td class="row1" valign="top">{L_FORUM_RULES}</td>
	<td class="row2"><textarea rows="8" cols="70" wrap="virtual" name="forum_rules" class="post">{FORUM_RULES}</textarea></td>
</tr>
<tr>
	<td class="row1" valign="top">{L_RULES_APPEAR_IN}</td>
	<td class="row2">
		<table cellpadding="2" cellspacing="0" border="0">
			<tr>
				<td><input type="checkbox" name="rules_in_viewforum" value="1" {S_RULES_VIEWFORUM_ENABLED} /></td>
				<td>{L_RULES_IN_VIEWFORUM}</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="rules_in_viewtopic" value="1" {S_RULES_VIEWTOPIC_ENABLED} /></td>
				<td>{L_RULES_IN_VIEWTOPIC}</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="rules_in_posting" value="1" {S_RULES_POSTING_ENABLED} /></td>
				<td>{L_RULES_IN_POSTING}</span></td>
			</tr>
		</table>
	</td>
</tr>
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="mainoption" /></td></tr>
</table>
</form>

<br clear="all" />
