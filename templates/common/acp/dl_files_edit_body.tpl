<form action="{S_DOWNLOADS_ACTION}" method="post" name="cat_id" {ENCTYPE}>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="3">{L_DL_FILES_TITLE}</th></tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_NAME}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_NAME_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="description" size="40" maxlength="255" value="{DESCRIPTION}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_CAT_NAME}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_CAT_NAME_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%">{SELECT_CAT}</td>
</tr>
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen"><b>{L_DL_DESCRIPTION}:</b></span></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_DESCRIPTION_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><textarea name="long_desc" class="post" rows="5" cols="75">{LONG_DESC}</textarea></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_LINK_URL}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_LINK_URL_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="file_name" size="40" maxlength="255" value="{URL}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_EXTERN}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_EXTERN_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="file_extern" {CHECKEXTERN} value="1" /></td>
</tr>
<!-- BEGIN allow_thumbs -->
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen"><b>{L_DL_THUMBNAIL}:</b></span></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_THUMBNAIL_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%">
		<!-- BEGIN thumbnail -->
		<table width="50%" border="0">
		<tr>
			<td align="center"><span class="gen">
				<img src="{allow_thumbs.thumbnail.THUMBNAIL}" border="0" alt="" title="" />
				<br /><input type="checkbox" name="del_thumb" value="1" />&nbsp;{L_DELETE}</span>
			</td>
		</tr>
		</table>
		<br /><br />
		<!-- END thumbnail -->
		<input type="file" name="thumb_name" class="post" size="46" /><br />
		<span class="gensmall">{L_DL_THUMBNAIL_SECOND}</span>
	</td>
</tr>
<!-- END allow_thumbs -->
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_IS_FREE}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_IS_FREE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%">
		<input type="radio" name="file_free" value="0" {CHECKNOTFREE} />&nbsp;{L_NO}
		<input type="radio" name="file_free" value="1" {CHECKFREE} />&nbsp;{L_YES}
		<input type="radio" name="file_free" value="2" {CHECKFREE_REG} />&nbsp;{L_FREE_REG}
	</td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_TRAFFIC}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_TRAFFIC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%">
		<input type="text" class="post" name="file_traffic" size="10" maxlength="10" value="{TRAFFIC}" />
		<input name="file_traffic_range" type="radio" value="KB" {FILE_TRAFFIC_RANGE_KB} />&nbsp;{L_KB}&nbsp;&nbsp;&nbsp;
		<input name="file_traffic_range" type="radio" value="MB" {FILE_TRAFFIC_RANGE_MB} />&nbsp;{L_MB}&nbsp;&nbsp;&nbsp;
		<input name="file_traffic_range" type="radio" value="GB" {FILE_TRAFFIC_RANGE_GB} />&nbsp;{L_GB}
	</td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_APPROVE}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_APPROVE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="approve" {APPROVE} value="1" /></td>
</tr>
<!-- BEGIN change_time -->
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{change_time.L_CHANGE_TIME}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_CHANGE_TIME_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="change_time" value="1" /></td>
</tr>
<!-- END change_time -->
<!-- BEGIN popup_notify -->
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{popup_notify.L_DISABLE_POPUP}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DISABLE_POPUP_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="disable_popup_notify" value="1"></td>
</tr>
<!-- END popup_notify -->
<!-- BEGIN email_block -->
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{email_block.L_DL_SEND_NOTIFY}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_SEND_NOTIFY_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="send_notify" value="1"></td>
</tr>
<!-- END email_block -->
<tr>
	<td class="cat" colspan="3" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
<!-- BEGIN use_hacklist -->
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_HACKLIST}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_HACKLIST_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><span class="gen">{L_YES}: <input type="radio" name="hacklist" {HACKLIST_YES} value="1" />&nbsp;{L_NO}: <input type="radio" name="hacklist" {HACKLIST_NO} value="0" />&nbsp;{L_DL_MOD_LIST}: <input type="radio" name="hacklist" {HACKLIST_EVER} value="2" /></span></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_HACK_VERSION}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_HACK_VERSION_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="hack_version" size="10" maxlength="32" value="{HACK_VERSION}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_HACK_AUTHOR}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_HACK_AUTHOR_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="hack_author" size="40" maxlength="255" value="{HACK_AUTHOR}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_HACK_AUTHOR_EMAIL}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_HACK_AUTHOR_EMAIL_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="hack_author_email" size="40" maxlength="255" value="{HACK_AUTHOR_EMAIL}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_HACK_AUTHOR_WEBSITE}:</b></span><br /></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_HACK_AUTHOR_WEBSITE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="hack_author_website" size="40" maxlength="255" value="{HACK_AUTHOR_WEBSITE}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_HACK_DL_URL}:</b></span><br /></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_HACK_DL_URL_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="hack_dl_url" size="40" maxlength="255" value="{HACK_DL_URL}" /></td>
</tr>
<tr>
	<td class="cat" colspan="3" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
<!-- END use_hacklist -->
<!-- BEGIN use_mod_desc -->
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_MOD_LIST}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_MOD_LIST_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="mod_list" {MOD_LIST} value="1"></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_MOD_TEST}:</b></span><br /></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_MOD_TEST_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="test" size="40" maxlength="50" value="{MOD_TEST}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen"><b>{L_DL_MOD_REQUIRE}:</b></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_MOD_REQUIRE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="require" size="40" maxlength="255" value="{MOD_REQUIRE}" /></td>
</tr>
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen"><b>{L_DL_MOD_DESC}:</b></span><br /></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_MOD_DESC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><textarea name="mod_desc" class="post" rows="15" cols="75">{MOD_DESC}</textarea></td>
</tr>
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen"><b>{L_DL_MOD_WARNING}:</b></span><br /></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_MOD_WARNING_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><textarea name="warning" class="post" rows="5" cols="75">{MOD_WARNING}</textarea></td>
</tr>
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen"><b>{L_DL_MOD_TODO}:</b></span></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="help_popup('{L_DL_MOD_TODO_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><textarea name="todo" class="post" rows="5" cols="75">{MOD_TODO}</textarea></td>
</tr>
<tr>
	<td class="cat" colspan="3" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
<!-- END use_mod_desc -->
</table>
{S_HIDDEN_FIELDS}
</form>
