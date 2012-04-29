<!-- INCLUDE overall_header.tpl -->

{ERROR_BOX}

<form action="{SUBMIT_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_TELL_FRIEND_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1">
	<br /><br />
		<table width="70%" align="center">
		<tr>
			<td><span class="gen"><b>{L_TELL_FRIEND_SENDER_USER}</b></span></td>
			<td><span class="gen"><b>{SENDER_NAME}</b></span></td>
		</tr>
		<tr>
			<td><span class="gen"><b>{L_TELL_FRIEND_SENDER_EMAIL}</b></span></td>
			<td><span class="gen"><b>{SENDER_MAIL}</b></span></td>
		</tr>
		<tr>
			<td><span class="gen"><b>{L_TELL_FRIEND_RECEIVER_USER}</b></span></td>
			<td><input type="text" class="post" size="25" maxlength="40" name="friendname" /></td>
		</tr>
		<tr>
			<td><span class="gen"><b>{L_TELL_FRIEND_RECEIVER_EMAIL}</b></span></td>
			<td><input type="text" class="post" size="25" maxlength="40" name="friendemail" /></td>
		</tr>
		<tr>
			<td valign="top"><span class="gen"><b>{L_TELL_FRIEND_MSG}</b></span></td>
			<td><textarea name="message" rows="10" cols="60">{L_TELL_FRIEND_BODY}</textarea></td>
		</tr>
		</table>
		<br />
		<div class="center-block-text">
			<input type="hidden" name="topic_id" size="25" maxlength="100" value="{TOPIC_ID}" />
			<input type="hidden" name="topic_url" size="25" maxlength="220" value="{TOPIC_URL}" />
			<input type="hidden" name="topic_title" size="25" maxlength="220" value="{TOPIC_TITLE}" />
			<input type="hidden" name="topic_link" size="25" maxlength="220" value="{TOPIC_LINK}" />
			<input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" />
		</div>
	<br />
	</td>
</tr>
<tr><td class="cat">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE overall_footer.tpl -->