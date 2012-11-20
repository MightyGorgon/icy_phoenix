<!-- INCLUDE overall_header.tpl -->

<?php
global $user, $config, $is_auth, $lang, $to_username, $privmsgs_id, $post_subject, $images, $privmsg;
$post_subject = (((strlen($post_subject) > 0) && ((substr($post_subject, 0, strlen($lang['REPLY_PREFIX'])) == $lang['REPLY_PREFIX']) || (substr($post_subject, 0, strlen($lang['REPLY_PREFIX'])) == $lang['REPLY_PREFIX_OLD']))) ? '' : $lang['REPLY_PREFIX']) . $post_subject;
$this->vars['qr_subject'] = $post_subject;
$this->vars['CA_QUICK_REPLY_BUTTON'] = '<a href="javascript:showQuickEditor();" title="' . $lang['Post_a_reply'] . '"><img src="' . $images['quick_reply'] . '" alt="' . $lang['Post_a_reply'] . '" /></a><a href="#quick"></a>';
$this->vars['privmsgs_id'] = $privmsgs_id;

ob_start();
?>
<div id="quick_reply" style="display: none; position: relative; ">
<a name="quick"></a>
<form method="post" action="{S_PRIVMSGS_ACTION}&amp;{POST_POST_URL}={PM_ID}" name="post">
{S_HIDDEN_FIELDS}
<input type="hidden" name="post_time" value="<?php echo time(); ?>" />
{IMG_THL}{IMG_THC}<span class="forumlink"><?php echo $lang['Post_a_reply']; ?></span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="200"><span class="gen"><b>{L_TO}</b></span></td>
	<td class="row2"  align="left" width="100%"><input type="text" class="post"  name="username" size="25" maxlength="25" tabindex="1" value="{RECIPIENT_QQ}" /></td>
</tr>
<tr>
	<td class="row1" width="200" nowrap="nowrap"><span class="gen"><b><?php echo $lang['Subject']; ?>:</b></span></td>
	<td class="row2" width="100%"><input type="text" name="subject" size="45" maxlength="120" style="width: 98%;" tabindex="2" class="post" value="{qr_subject}" /></td>
</tr>
<tr>
	<td class="row1" width="200" nowrap="nowrap"><span class="gen"><b><?php echo $lang['Message_body']; ?>:<br /><img src="{SPACER}" width="200" height="1" alt="" /></b></span></td>
	<td class="row2" width="100%"><div class="message-box"><textarea name="message" rows="15" cols="35" tabindex="3"></textarea></div></td>
</tr>
<tr>
	<td class="row1" valign="top" nowrap="nowrap"><span class="gen"><b><?php echo $lang['Options']; ?>:</b></span></td>
	<td class="row2">
	<span class="genmed">
	<?php
		$user_sig = ( $user->data['user_sig'] != '' && $config['allow_sig'] ) ? $user->data['user_sig'] : '';
		$html_on = $config['allow_html'] ? $user->data['user_allowhtml'] : 1;
		$bbcode_on = $config['allow_bbcode'] ? $user->data['user_allowbbcode'] : 0;
		$smilies_on = $config['allow_smilies'] ? $user->data['user_allowsmile'] : 0;
	?>
	<label><input type="checkbox" name="disable_acro_auto" /><span class="genmed">&nbsp;<?php echo $lang['Disable_ACRO_AUTO_post']; ?></span></label><br />
	<?php if($config['allow_html']) { ?>
	<label><input type="checkbox" name="disable_html" <?php echo ($html_on ? '' : 'checked="checked"'); ?> /><span class="genmed">&nbsp;<?php echo $lang['Disable_HTML_post']; ?></span></label><br />
	<?php } else { ?><input type="hidden" name="disable_html" value="checked" /><?php } ?>
	<?php if($config['allow_bbcode']) { ?>
	<label><input type="checkbox" name="disable_bbcode" <?php echo ($bbcode_on ? '' : 'checked="checked"'); ?> /><span class="genmed">&nbsp;<?php echo $lang['Disable_BBCode_post']; ?></span></label><br />
	<?php } else { ?><input type="hidden" name="disable_bbcode" value="checked" /><?php } ?>
	<?php if($config['allow_smilies']) { ?>
	<label><input type="checkbox" name="disable_smilies" <?php echo ($smilies_on ? '' : 'checked="checked"'); ?> /><span class="genmed">&nbsp;<?php echo $lang['Disable_Smilies_post']; ?></span></label><br />
	<?php } else { ?><input type="hidden" name="disable_smilies" value="checked" /><?php } ?>
	<?php if($user_sig) {  ?>
	<label><input type="checkbox" name="attach_sig" <?php echo ($user->data['user_attachsig'] ? 'checked="checked"' : ''); ?> /><span class="genmed">&nbsp;<?php echo $lang['Attach_signature']; ?></span></label><br />
	<?php } else { ?><input type="hidden" name="attach_sig" value="" /><?php } ?>
	</span>
	</td>
</tr>
<tr>
	<td class="cat" colspan="2">
		<input type="hidden" name="reply" value="{REPLY}" />
		<input type="hidden" name="id" value="{REPLY_ID}" />
		<input type="hidden" name="mode" value="reply" />
		<input type="hidden" name="t" value="<?php echo $privmsgs_id; ?>" />
		<input type="hidden" name="sid" value="<?php echo $user->data['session_id']; ?>" />
		<input type="submit" tabindex="5" name="preview" class="liteoption" value="<?php echo $lang['Preview']; ?>" />&nbsp;
		<input type="submit" accesskey="s" tabindex="6" name="post" class="mainoption" value="<?php echo $lang['Submit']; ?>" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
</div>
<?php
$str = ob_get_contents();
ob_end_clean();
$this->vars['CA_QUICK_REPLY_FORM'] = $str;
?>
{CPL_MENU_OUTPUT}
<div class="forumline" style="margin-left: 5%; margin-right: 5%;">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="25%" align="center">{INBOX_IMG}<br /><b><span class="topiclink">{INBOX}</span></b></td>
	<td width="25%" align="center">{SENTBOX_IMG}<br /><b><span class="topiclink">{SENTBOX}</span></b></td>
	<td width="25%" align="center">{OUTBOX_IMG}<br /><b><span class="topiclink">{OUTBOX}</span></b></td>
	<td width="25%" align="center">{SAVEBOX_IMG}<br /><b><span class="topiclink">{SAVEBOX}</span></b></td>
</tr>
</table>
</div>
<script type="text/javascript">
// <![CDATA[

message = new Array();
message[{privmsgs_id}] = " user=\"{RECIPIENT_QQ}\"]{PLAIN_MESSAGE}[/";

// ]]>
</script>
<br />
<form method="post" action="{S_PRIVMSGS_ACTION}">
{S_HIDDEN_FIELDS}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="left" valign="middle"><span class="img-btn">{REPLY_PM_IMG}</span>&nbsp;<span class="img-btn">{CA_QUICK_REPLY_BUTTON}</span></td></tr>
</table>

{IMG_THL}{IMG_THC}<span class="forumlink">{POST_SUBJECT}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<th width="150">{L_AUTHOR}</th>
		<th>{L_MESSAGE}</th>
	</tr>
	<tr>
		<td class="row-post-author">
			<span class="post-name">{MESSAGE_FROM}&nbsp;{POSTER_GENDER}</span><br />
			<div class="post-rank"><b>{POSTER_RANK}</b>{RANK_IMAGE}</div>
			<span class="post-images">{POSTER_AVATAR}</span>
			<div class="post-details">
			{ONLINE_STATUS_IMG}{IP_IMG}{AIM_IMG}{ICQ_IMG}{MSN_IMG}{SKYPE_IMG}{YIM_IMG}<br />
			{POSTER_JOINED}<br />
			{POSTER_POSTS}<br />
			{POSTER_FROM}</div><br />
			<img src="{SPACER}" width="150" height="1" alt="" />
		</td>
		<td class="row-post" width="100%">
			<div class="post-buttons-top post-buttons">{QUOTE_PM_IMG}&nbsp;{EDIT_PM_IMG}</div>
			<div class="post-subject">{POST_SUBJECT}&nbsp;</div>
			<div class="post-text post-text-hide-flow">
				{MESSAGE}
				<!-- BEGIN postrow -->
				<br />
				{ATTACHMENTS}
				<!-- END postrow -->
			</div>
			<div class="post-text post-text-hide-flow"><br /><br /><br />{SIGNATURE}</div>
		</td>
	</tr>
	<tr>
		<td class="row-post-date">{POST_DATE}</td>
		<td class="row-post-buttons post-buttons">
			<div style="text-align: right">
				<div style="position: relative; float: left; text-align: left;">
					{PROFILE_IMG}{PM_IMG}
					{EMAIL_IMG}
					{WWW_IMG}
				</div>
				<a href="javascript:addquote(%27{privmsgs_id}%27,%27quote%27,true,false);"><img src="{IMG_QUICK_QUOTE}" alt="{L_QUICK_QUOTE}" title="{L_QUICK_QUOTE}" /></a>
				<a href="javascript:addquote(%27{privmsgs_id}%27,%27ot%27,true,false);"><img src="{IMG_OFFTOPIC}" alt="{L_OFFTOPIC}" title="{L_OFFTOPIC}" /></a>
			</div>
		</td>
	</tr>
	<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
	<tr>
		<td class="cat" colspan="2">
			<input type="submit" name="save" value="{L_SAVE_MSG}" class="liteoption" />&nbsp;&nbsp;
			<input type="submit" name="delete" value="{L_DELETE_MSG}" class="liteoption" />&nbsp;
			<!-- BEGIN switch_attachments -->
			<input type="submit" name="pm_delete_attach" value="{L_DELETE_ATTACHMENTS}" class="liteoption" />
			<!-- END switch_attachments -->
		</td>
	</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<br />
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left"><span class="img-btn">{REPLY_PM_IMG}</span>&nbsp;<span class="img-btn">{CA_QUICK_REPLY_BUTTON}</span></td>
	<td align="right" valign="top" nowrap="nowrap">
		<span class="gensmall">{S_TIMEZONE}</span>
	</td>
</tr>
</table>
</form>
{CA_QUICK_REPLY_FORM}
</td>
</tr>
</table>
<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr><td valign="top" align="right">{JUMPBOX}</td></tr>
</table>

<!-- INCLUDE overall_footer.tpl -->
