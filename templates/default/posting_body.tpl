<!-- BEGIN switch_privmsg -->
<!-- INCLUDE overall_header.tpl -->
<!-- END switch_privmsg -->

<!-- IF S_AJAX_FEATURES -->
<script type="text/javascript">
// <![CDATA[
var L_RESULTS = '{L_SEARCH_RESULTS}';
var L_RESULT = '{L_SEARCH_RESULT}';
var L_MORE_MATCHES = '{L_MORE_MATCHES}';
var L_AJAX_NO_RESULTS = '{L_AJAX_NO_RESULTS}';
// ]]>
</script>

<!-- <script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ajax/ajax_core.js"></script> -->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ajax/ajax_postfunctions.js"></script>
<!-- ENDIF -->

<script type="text/javascript">
// <![CDATA[
function attach_rules(forum_id)
{
	window.open('{U_ATTACH_RULES}' + forum_id + '&sid={S_SID}', '_attach_rules', 'height=200,width=500,resizable=no,scrollbars=yes');
}
// ]]>
</script>

<!-- IF S_JQUERY_TOPIC_TAGS -->
<script type="text/javascript">
// <![CDATA[
$(function() {
	$('input.tag').tagedit({
		texts: { // some texts
			removeLinkTitle: '{L_TAGS_REMOVE_ITEM}',
			saveEditLinkTitle: '{L_TAGS_EDIT_ITEM}',
			deleteLinkTitle: '{L_TAGS_DELETE_ITEM}',
			deleteConfirmation: '{L_TAGS_DELETE_CONFIRM}',
			deletedElementTitle: '{L_TAGS_DELETED_ITEM}',
			breakEditLinkTitle: '{L_TAGS_BREAK_EDIT}'
		},
		autocompleteURL: 'ajax.php?mode=tags_search_json&json=1&sid={S_SID}',
		//autocompleteOptions: {minLength: 0},
		// put a classname here if the wrapper ul shoud receive a special class
		//additionalListClass: 'post',
		// return, comma, space, period, semicolon
		//breakKeyCodes: [ 13, 44, 32, 46, 59 ]
	});
	
});
// ]]>
</script>
<!-- ENDIF -->

<!-- BEGIN switch_privmsg -->
{CPL_MENU_OUTPUT}
<table>
<tr>
	<td class="tw100pct tdalignc tvalignt">
		<div class="forumline" style="margin-left: 5%; margin-right: 5%;">
		<table>
		<tr>
			<td class="tdalignc tw25pct">{INBOX_IMG}<br /><span class="forumlink">{INBOX}</span></td>
			<td class="tdalignc tw25pct">{SENTBOX_IMG}<br /><span class="forumlink">{SENTBOX}</span></td>
			<td class="tdalignc tw25pct">{OUTBOX_IMG}<br /><span class="forumlink">{OUTBOX}</span></td>
			<td class="tdalignc tw25pct">{SAVEBOX_IMG}<br /><span class="forumlink">{SAVEBOX}</span></td>
		</tr>
		</table>
		</div>
	</td>
</tr>
</table>
<!-- END switch_privmsg -->

<!-- BEGIN switch_prv_msg_review -->
<table class="forumline">
<tr><td class="row-header" colspan="2"><span>{switch_prv_msg_review.PRIVATE_MSG_TITLE}</span></td></tr>
<tr><td class="row2 tw65pct" colspan="2"><div class="post-text post-text-hide-flow">{switch_prv_msg_review.PRIVATE_MSG_REVIEW}</div></td></tr>
</table>
<!-- END switch_prv_msg_review -->
<form action="{S_POST_ACTION}" method="post" name="post" onsubmit="return checkForm(this);" {S_FORM_ENCTYPE}>
<!-- BEGIN save_draft_confirm -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DRAFTS}</span>{IMG_THR}<table class="forumlinenb">
<tr><td class="row1g">{L_DRAFT_CONFIRM}<br /><br /><input type="submit" name="draft_confirm" value="{L_YES}" class="mainoption" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END save_draft_confirm -->
<input type="hidden" name="post_time" value="<?php echo time(); ?>" />
{POST_PREVIEW_BOX}
{ERROR_BOX}

<!-- IF S_FORUM_RULES -->
<table class="forumline">
<tr><td class="row-header"><span><!-- IF S_FORUM_RULES_TITLE -->{L_FORUM_RULES}<!-- ENDIF -->&nbsp;</span></td></tr>
<tr><td class="row1g-left tw100pct"><div class="post-text">{FORUM_RULES}</div></td></tr>
</table>
<br />
<!-- ENDIF -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_POST_A}</span>{IMG_THR}<table class="forumlinenb">
<!-- BEGIN switch_username_select -->
<tr>
	<td class="row1"><span class="gen"><b>{L_USERNAME}</b></span></td>
	<td class="row2"><span class="genmed"><input type="text" class="post" tabindex="1" name="username" size="25" maxlength="25" value="{USERNAME}" /></span></td>
</tr>
<!-- END switch_username_select -->
<!-- BEGIN switch_privmsg -->
<tr>
	<td class="row1"><span class="gen"><b>{L_USERNAME}</b></span></td>
	<td class="row2"><span class="genmed"><input type="text" class="post" name="username" maxlength="25" size="25" tabindex="1" value="{USERNAME}" {S_AJAX_PM_USER_CHECK} /><span id="pm_username_select">&nbsp;</span>&nbsp;<input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onclick="window.open('{U_SEARCH_USER}', '_search', 'width=400,height=200,resizable=yes'); return false;" /></span></td>
</tr>
<tr id="pm_username_error_tbl" style="display: none;">
	<td class="row1">&nbsp;</td>
	<td class="row2"><span class="gen" id="pm_username_error_text">&nbsp;</span></td>
</tr>
<!-- END switch_privmsg -->
<tr>
	<td class="row1"><span class="gen"><b>{L_SUBJECT}</b></span></td>
	<td class="row2"><input type="text" name="subject" size="45" maxlength="120" style="width: 98%;" tabindex="2" class="post" value="{SUBJECT}" {S_AJAX_BLUR} /></td>
</tr>
<!-- IF S_AJAX_FEATURES -->
<tr id="subject_error_tbl" style="display: none;">
	<td class="row1">&nbsp;</td>
	<td class="row2"><span class="gen">{L_EMPTY_SUBJECT}</span></td>
</tr>
<tr id="searchresults_tbl" style="display: none;">
	<td class="row1">&nbsp;</td>
	<td class="row2"><span class="gen"><a href="#" target="_blank" class="gen" id="searchresults_lnk">{L_AJAX_NO_RESULTS}</a></span></td>
</tr>
<!-- ENDIF -->
<!-- BEGIN topic_description -->
<tr>
	<td class="row1"><span class="gen"><b>{L_TOPIC_DESCRIPTION}</b></span></td>
	<td class="row2"><span class="gen"><input type="text" name="topic_desc" size="45" maxlength="240" style="width: 98%;" tabindex="3" class="post" value="{TOPIC_DESCRIPTION}" /></span></td>
</tr>
<!-- END topic_description -->
<!-- BEGIN switch_type_toggle -->
<tr>
	<td class="row1"><span class="gen"><b>{L_TYPE_TOGGLE_TITLE}</b></span></td>
	<td class="row2"><span class="gen"><b>{S_TYPE_TOGGLE}</b></span></td>
</tr>
<!-- END switch_type_toggle -->
<!-- BEGIN switch_news_cat -->
<tr>
	<td class="row1"><span class="gen"><b>{switch_news_cat.L_NEWS_CATEGORY}</b></span></td>
	<td class="row2"><select name="{switch_news_cat.S_NAME}">{switch_news_cat.S_CATEGORY_BOX}</select></td>
</tr>
<!-- END switch_news_cat -->
<tr>
	<td class="row1 tw22pct">
		<table class="p2px">
			<tr><td><span class="gen"><b>{L_MESSAGE_BODY}</b></span></td></tr>
			<tr>
				<td class="tdalignc tvalignm"><br />
					<!-- Begin Lo-Fi Mod -->
					<?php
					global $lofi;
					if (!$lofi)
					{
					?>
					<!-- End Lo-Fi Mod -->
					{BBCB_SMILEYS_MG}
					<!-- Begin Lo-Fi Mod -->
					<?php
					}
					?>
					<!-- End Lo-Fi Mod -->
					<br />
				</td>
			</tr>
		</table>
	</td>
	<td class="row2 tw78pct">
		{BBCB_MG}
		<div class="message-box"><textarea id="message" name="message" rows="15" cols="76" tabindex="4" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{MESSAGE}</textarea></div>
	</td>
</tr>
<!-- IF S_EDIT_NOTES -->
<tr>
	<td class="row1"><span class="gen"><b>{L_EDIT_NOTES}</b></span></td>
	<td class="row2"><input type="text" name="notes" size="45" maxlength="240" style="width: 98%;" tabindex="5" class="post" value="{switch_edit.NOTES}" /></td>
</tr>
<!-- IF S_ADMIN or S_MOD -->
<tr>
	<td class="row1"><span class="gen"><b>{L_NOTES_MOD}</b></span></td>
	<td class="row2"><input type="text" name="notes_mod" size="45" maxlength="240" style="width: 98%;" tabindex="6" class="post" value="{switch_edit.NOTES_MOD}" /></td>
</tr>
<!-- ENDIF -->
<!-- ENDIF -->
<!-- IF S_POSTING_TOPIC -->
<!-- IF S_ADMIN or S_MOD -->
<!--
<tr>
	<td class="row1"><span class="gen"><b>{L_CLEAN_NAME}</b></span><br /><span class="gensmall">{L_CLEAN_NAME_EXPLAIN}</span></td>
	<td class="row2"><span class="gen"><input type="text" name="topic_title_clean" size="45" maxlength="240" style="width: 98%;" tabindex="7" class="post" value="{TOPIC_TITLE_CLEAN}" /></span></td>
</tr>
-->
<!-- ENDIF -->
<!-- IF S_TOPIC_TAGS -->
<tr>
	<td class="row1"><span class="gen"><b>{L_TOPIC_TAGS}</b></span><br /><span class="gensmall">{L_TOPIC_TAGS_EXPLAIN}</span></td>
	<!-- <td class="row2"><span class="gen"><input type="text" id="topic_tags" name="topic_tags" size="45" maxlength="240" style="width: 98%;" tabindex="8" class="post" value="{TOPIC_TAGS}" /></span></td> -->
	<td class="row2">
		<span class="gen">
			<!-- IF S_JQUERY_TOPIC_TAGS -->
			<!-- BEGIN ttag -->
			<input type="text" name="ttag[]" size="45" maxlength="240" style="width: 98%;" class="tag" value="{ttag.TTAG}" />
			<!-- END ttag -->
			<input type="text" name="ttag[]" size="45" maxlength="240" style="width: 98%;" tabindex="8" class="tag" value="" />
			<!-- ELSE -->
			<input type="text" name="topic_tags" size="45" maxlength="240" style="width: 98%;" tabindex="8" class="post" value="{TOPIC_TAGS}" />
			<!-- ENDIF -->
		</span>
	</td>
</tr>
<!-- ENDIF -->
<!-- ENDIF -->
<!-- IF S_FEATURED_IMAGE -->
<tr>
	<td class="row1"><span class="gen"><b>{L_POST_FEATURED_IMAGE}</b></span><br /><span class="gensmall">{L_POST_FEATURED_IMAGE_EXPLAIN}</span></td>
	<td class="row2"><span class="gen"><input type="text" name="post_images" size="45" maxlength="240" style="width: 98%;" tabindex="9" class="post" value="{POST_FEATURED_IMAGE}" /></span></td>
</tr>
<!-- ENDIF -->
<tr>
	<td class="catBottom" colspan="2">
		<input type="button" tabindex="10" class="liteoption" value="{L_POST_HIGHLIGHT}" onclick="copymetasearch();" />&nbsp;
		<input type="submit" tabindex="11" name="preview" class="liteoption" value="{L_PREVIEW}" />&nbsp;
		<!-- BEGIN allow_drafts -->
		<input type="submit" tabindex="12" name="draft" class="altoption" value="{L_DRAFT_SAVE}" />&nbsp;
		<!-- END allow_drafts -->
		<input type="submit" accesskey="s" tabindex="13" name="post" class="mainoption" value="{L_SUBMIT}" />
	</td>
</tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_OPTIONS}</b></span><br /><span class="gensmall">{HTML_STATUS}<br />{BBCODE_STATUS}<br />{SMILIES_STATUS}</span></td>
	<td class="row2">
		<div class="genmed">
			<!-- BEGIN switch_show_portal -->
			<label><input type="checkbox" name="topic_show_portal" {S_TOPIC_SHOW_PORTAL} />&nbsp;{L_SHOW_PORTAL}</label><br />
			<!-- END switch_show_portal -->
			<!-- BEGIN switch_html_checkbox -->
			<label><input type="checkbox" name="disable_html" {S_HTML_CHECKED} />&nbsp;{L_DISABLE_HTML}</label><br />
			<!-- END switch_html_checkbox -->
			<label><input type="checkbox" name="disable_acro_auto" {S_ACRO_AUTO_CHECKED} />&nbsp;{L_DISABLE_ACRO_AUTO}</label><br />
			<!-- BEGIN switch_bbcode_checkbox -->
			<label><input type="checkbox" name="disable_bbcode" {S_BBCODE_CHECKED} />&nbsp;{L_DISABLE_BBCODE}</label><br />
			<!-- END switch_bbcode_checkbox -->
			<!-- BEGIN switch_smilies_checkbox -->
			<label><input type="checkbox" name="disable_smilies" {S_SMILIES_CHECKED} />&nbsp;{L_DISABLE_SMILIES}</label><br />
			<!-- END switch_smilies_checkbox -->
			<!-- BEGIN switch_signature_checkbox -->
			<label><input type="checkbox" name="attach_sig" {S_SIGNATURE_CHECKED} />&nbsp;{L_ATTACH_SIGNATURE}</label><br />
			<!-- END switch_signature_checkbox -->
			<!-- BEGIN switch_bookmark_checkbox -->
			<label><input type="checkbox" name="setbm" {S_SETBM_CHECKED} />&nbsp;{L_SET_BOOKMARK}</label><br />
			<!-- END switch_bookmark_checkbox -->
			<!-- BEGIN switch_notify_checkbox -->
			<label><input type="checkbox" name="notify" {S_NOTIFY_CHECKED} />&nbsp;{L_NOTIFY_ON_REPLY}</label><br />
			<!-- END switch_notify_checkbox -->
			<!-- BEGIN switch_mark_edit_checkbox -->
			<label><input type="checkbox" name="mark_edit" {S_MARK_EDIT_CHECKED} />&nbsp;{L_MARK_EDIT}</label><br />
			<!-- END switch_mark_edit_checkbox -->
			<!-- BEGIN switch_delete_checkbox -->
			<label><input type="checkbox" name="delete" />&nbsp;{L_DELETE_POST}</label><br />
			<!-- END switch_delete_checkbox -->
			<!-- BEGIN switch_lock_topic -->
			<label><input type="checkbox" name="lock" {S_LOCK_CHECKED} />&nbsp;{L_LOCK_TOPIC}</label><br />
			<!-- END switch_lock_topic -->
			<!-- BEGIN switch_unlock_topic -->
			<label><input type="checkbox" name="unlock" {S_UNLOCK_CHECKED} />&nbsp;{L_UNLOCK_TOPIC}</label><br />
			<!-- END switch_unlock_topic -->
			<!-- BEGIN switch_lock_post -->
			<label><input type="checkbox" name="post_locked" {S_POST_LOCKED} />&nbsp;{L_LOCK_POST}</label><br />
			<!-- END switch_lock_post -->
		</div>
	</td>
</tr>

{ATTACHBOX}
{POLLBOX}
{REGBOX}
<!-- BEGIN switch_type_cal -->
<tr><th colspan="2">{L_CALENDAR_TITLE}</th></tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_CALENDAR_TITLE}</b></span></td>
	<td class="row1">
		<table cellpadding="2" cellspacing="0" width="100%" border="0">
			<tr>
				<td class="tdalignr tdnw"><span><b>{L_CALENDAR_TITLE}:&nbsp;</b></span></td>
				<td class="tw100pct">
					<span class="genmed">
						{S_CALENDAR_DAY}{S_CALENDAR_MONTH}{S_CALENDAR_YEAR}&nbsp;
						<a href="#" class="genmed" onclick="document.post.topic_calendar_day.value='{TODAY_DAY}';document.post.topic_calendar_month.value='{TODAY_MONTH}';document.post.topic_calendar_year.value='{TODAY_YEAR}';return false;">{L_TODAY}</a>
					</span>
				</td>
			</tr>
			<tr>
				<td class="tdalignr tdnw"><span><b>{L_TIME}:&nbsp;</b></span></td>
				<td class="tw100pct">
					<span class="genmed">
						<input name="topic_calendar_hour" type="text" maxlength="2" size="3" value="{CALENDAR_HOUR}" class="post" />&nbsp;{L_HOURS}&nbsp;&nbsp;
						<input name="topic_calendar_min" type="text" maxlength="2" size="3" value="{CALENDAR_MIN}" class="post" />&nbsp;{L_MINUTES}
					</span>
				</td>
			</tr>
			<tr>
				<td class="tdalignr tdnw"><span><b>{L_CALENDAR_DURATION}:&nbsp;</b></span></td>
				<td class="tw100pct">
					<span class="genmed">
						<input name="topic_calendar_duration_day" type="text" maxlength="5" size="3" value="{CALENDAR_DURATION_DAY}" class="post" />&nbsp;{L_DAYS}&nbsp;&nbsp;
						<input name="topic_calendar_duration_hour" type="text" maxlength="5" size="3" value="{CALENDAR_DURATION_HOUR}" class="post" />&nbsp;{L_HOURS}&nbsp;&nbsp;
						<input name="topic_calendar_duration_min" type="text" maxlength="5" size="3" value="{CALENDAR_DURATION_MIN}" class="post" />&nbsp;{L_MINUTES}
					</span>
				</td>
			</tr>
		</table>
	</td>
</tr>
<!-- END switch_type_cal -->

<!-- BEGIN switch_confirm -->
<tr><td class="row1 row-center" colspan="2"><br /><br />{CONFIRM_IMAGE}<br /><br /></td></tr>
<tr>
	<td class="row2 row-center" colspan="2">
		<span class="gen"><b>{L_CT_CONFIRM}</b></span><br />
		<span class="gensmall">{L_CT_CONFIRM_E}</span><br /><br />
		<input type="text" class="post" style="width: 200px" name="confirm_code" size="6" value="" />
		{S_HIDDEN_FIELDS}
	</td>
</tr>
<!-- END switch_confirm -->

<tr>
	<td class="cat" colspan="2">
		{S_HIDDEN_FORM_FIELDS}
		<input type="submit" tabindex="12" name="preview" class="liteoption" value="{L_PREVIEW}" />&nbsp;
		<input type="submit" accesskey="s" tabindex="13" name="post" class="mainoption" value="{L_SUBMIT}" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<!-- BEGIN switch_privmsg -->
	</td>
</tr>
</table>
<!-- END switch_privmsg -->
<div align="right">{JUMPBOX}</div>

{TOPIC_REVIEW_BOX}

<!-- INCLUDE overall_footer.tpl -->