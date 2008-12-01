<form action="{S_DOWNLOADS_ACTION}" method="post" {ENCTYPE}>
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DL_FILES_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN upload_traffic -->
<tr><td class="row3 row-center" colspan="3"><span class="gensmall"><b>{upload_traffic.L_DL_UPLOAD_TRAFFIC_COUNT}</b></span></td></tr>
<!-- END upload_traffic -->
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_NAME}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_NAME_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="description" size="40" maxlength="255" value="{DESCRIPTION}" /></td>
</tr>
<!-- BEGIN cat_choose -->
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_CAT_NAME}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_CAT_NAME_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%">{SELECT_CAT}</td>
</tr>
<!-- END cat_choose -->
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen">{L_DL_DESCRIPTION}:</span></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_DESCRIPTION_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><textarea name="long_desc" rows="5" cols="100" class="post">{LONG_DESC}</textarea></td>
</tr>
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen">{L_DL_UPLOAD_FILE}:</span></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_UPLOAD_FILE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%">
		<input type="file" name="dl_name" class="post" size="46" /><br />
		<span class="gensmall">{MAX_UPLOAD_SIZE}{L_EXT_BLACKLIST}</span>
	</td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_EXTERN}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_EXTERN_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%">
		<input type="checkbox" class="post" name="file_extern" {CHECKEXTERN} value="1" />
		&nbsp;&nbsp;<input type="text" class="post" name="file_name" size="40" value="{URL}" />
	</td>
</tr>
<!-- BEGIN allow_thumbs -->
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen">{L_DL_THUMBNAIL}:</span></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_THUMBNAIL_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
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
		<input class="post" type="file" name="thumb_name" size="46" /><br />
		<span class="gensmall">{L_DL_THUMBNAIL_SECOND}</span>
	</td>
</tr>
<!-- END allow_thumbs -->
<!-- BEGIN modcp -->
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_IS_FREE}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_IS_FREE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><span class="gen"><input type="radio" name="file_free" value="0" {CHECKNOTFREE} />&nbsp;{L_NO}&nbsp;&nbsp;
		<input type="radio" name="file_free" value="1" {CHECKFREE} />&nbsp;{L_YES}&nbsp;&nbsp;
		<input type="radio" name="file_free" value="2" {CHECKFREE_REG} />&nbsp;{L_DL_IS_FREE_REG}</span></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_TRAFFIC}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_TRAFFIC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><span class="gen"><input type="text" class="post" name="file_traffic" size="10" maxlength="10" value="{TRAFFIC}" />
		<input name="file_traffic_range" type="radio" value="KB" {FILE_TRAFFIC_RANGE_KB} />&nbsp;{L_KB}&nbsp;&nbsp;&nbsp;
		<input name="file_traffic_range" type="radio" value="MB" {FILE_TRAFFIC_RANGE_MB} />&nbsp;{L_MB}&nbsp;&nbsp;&nbsp;
		<input name="file_traffic_range" type="radio" value="GB" {FILE_TRAFFIC_RANGE_GB} />&nbsp;{L_GB}</span></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_APPROVE}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_APPROVE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="approve" value="1" {APPROVE} /></td>
</tr>
<!-- END modcp -->
<!-- BEGIN change_time -->
<tr>
	<td class="row1" width="30%"><span class="gen">{change_time.L_CHANGE_TIME}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_CHANGE_TIME_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="change_time" value="1" /></td>
</tr>
<!-- END change_time -->
<!-- BEGIN popup_notify -->
<tr>
	<td class="row1" width="30%"><span class="gen">{popup_notify.L_DISABLE_POPUP}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DISABLE_POPUP_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="disable_popup_notify" value="1" /></td>
</tr>
<!-- END popup_notify -->
<!-- BEGIN email_block -->
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_SEND_NOTIFY}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_SEND_NOTIFY_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="send_notify" value="1" /></td>
</tr>
<!-- END email_block -->
<tr>
	<td class="cat" colspan="3" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
<!-- BEGIN use_hacklist -->
<!--
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_HACKLIST}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_HACKLIST_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><span class="gen">{L_YES}: <input type="radio" name="hacklist" {HACKLIST_YES} value="1" />&nbsp;{L_NO}: <input type="radio" name="hacklist" {HACKLIST_NO} value="0" />&nbsp;{L_DL_MOD_LIST}: <input type="radio" name="hacklist" {HACKLIST_EVER} value="2" /></span></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_HACK_VERSION}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_HACK_VERSION_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="hack_version" size="10" maxlength="32" value="{HACK_VERSION}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_HACK_AUTHOR}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_HACK_AUTHOR_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="hack_author" size="40" maxlength="255" value="{HACK_AUTHOR}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_HACK_AUTHOR_EMAIL}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_HACK_AUTHOR_EMAIL_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="hack_author_email" size="40" maxlength="255" value="{HACK_AUTHOR_EMAIL}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_HACK_AUTHOR_WEBSITE}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_HACK_AUTHOR_WEBSITE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="hack_author_website" size="40" maxlength="255" value="{HACK_AUTHOR_WEBSITE}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_HACK_DL_URL}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_HACK_DL_URL_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="hack_dl_url" size="40" maxlength="255" value="{HACK_DL_URL}" /></td>
</tr>
<tr>
	<td class="cat" colspan="3" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
-->
<!-- END use_hacklist -->
<!-- BEGIN allow_edit_mod_desc -->
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_MOD_LIST}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_MOD_LIST_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="checkbox" name="mod_list" {MOD_LIST} value="1" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_MOD_TEST}:</span><br /></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_MOD_TEST_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="test" size="40" maxlength="50" value="{MOD_TEST}" /></td>
</tr>
<tr>
	<td class="row1" width="30%"><span class="gen">{L_DL_MOD_REQUIRE}:</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_MOD_REQUIRE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><input type="text" class="post" name="require" size="40" maxlength="255" value="{MOD_REQUIRE}" /></td>
</tr>
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen">{L_DL_MOD_DESC}:</span><br /></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_MOD_DESC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><textarea name="mod_desc" class="post" rows="25" cols="100">{MOD_DESC}</textarea></td>
</tr>
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen">{L_DL_MOD_WARNING}:</span><br /></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_MOD_WARNING_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><textarea name="warning" class="post" rows="10" cols="100">{MOD_WARNING}</textarea></td>
</tr>
<tr>
	<td class="row1" width="30%" valign="top"><span class="gen">{L_DL_MOD_TODO}:</span></td>
	<td class="row1" width="2%" valign="top">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_MOD_TODO_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="68%"><textarea name="todo" class="post" rows="10" cols="100">{MOD_TODO}</textarea></td>
</tr>
<tr>
	<td class="cat" colspan="3" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
<!-- END allow_edit_mod_desc -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
{S_HIDDEN_FIELDS}
</form>