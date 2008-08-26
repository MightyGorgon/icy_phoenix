<h1>{L_SETTINGSTITLE}</h1>
<p>{L_SETTINGSEXPLAIN}</p>

<form action="{S_SETTINGS_ACTION}" method="post" name="form">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_SETTINGS}</th></tr>
<tr>
	<td width="50%" class="row1"><strong>{L_DBNAME}</strong><br /><span class="gensmall">{L_DBNAMEINFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="settings_dbname" value="{SETTINGS_DBNAME}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_TOPNUM}</strong><br /><span class="gensmall">{L_TOPNUMINFO}</span></td>
	<td class="row2"><input type="text" class="post" size="5" maxlength="5" name="settings_topnumber" value="{SETTINGS_TOPNUMBER}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_NFDAYS}</strong><br /><span class="gensmall">{L_NFDAYSINFO}</span></td>
	<td class="row2"><input type="text" class="post" size="5" maxlength="5" name="settings_newdays" value="{SETTINGS_NEWDAYS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FILE_IN_PAGE}</strong><br /><span class="gensmall">{L_FILE_IN_PAGE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="5" maxlength="5" name="settings_file_page" value="{SETTINGS_FILE_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SHOW_VIEWALL}</strong><br /><span class="gensmall">{L_VIEWALL_INFO}</span></td>
	<td class="row2">
	<select name="settings_viewall" class="forminput">
	<option value="1"{S_VIEW_ALL_YES}>{L_YES}</option>
	<option value="0"{S_VIEW_ALL_NO}>{L_NO}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><strong>{L_DISABLE}</strong><br /><span class="gensmall">{L_DISABLE_INFO}</span></td>
	<td class="row2">
	<select name="settings_disable" class="forminput">
	<option value="1"{S_DISABLE_YES}>{L_YES}</option>
	<option value="0"{S_DISABLE_NO}>{L_NO}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><strong>{L_HOTLINK}</strong><br /><span class="gensmall">{L_HOTLINK_INFO}</span></td>
	<td class="row2">
	<select name="hotlink_prevent" class="forminput">
	<option value="1"{S_HOTLINK_YES}>{L_YES}</option>
	<option value="0"{S_HOTLINK_NO}>{L_NO}</option>
	</select></td>
</tr>
	<tr>
		<td class="row1"><strong>{L_HOTLINK_ALLOWED}</strong><br /><span class="gensmall">{L_HOTLINK_ALLOWED_INFO}</span></td>
		<td class="row2"><input class="post" type="text" size="40" name="hotlink_allowed" value="{HOTLINK_ALLOWED}" /></td>
	</tr>
	<tr>
		<td class="row1"><span class="genmed"><strong>{L_DEFAULT_SORT_METHOD}</strong></span></td>
		<td class="row2">
		<select name="sort_method">
		<option {SORT_NAME} value='file_name'>{L_NAME}</option>
		<option {SORT_TIME} value='file_time'>{L_DATE}</option>
		<option {SORT_RATING} value='file_rating'>{L_RATING}</option>
		<option {SORT_DOWNLOADS} value='file_dls'>{L_DOWNLOADS}</option>
		<option {SORT_UPDATE_TIME} value='file_update_time'>{L_UPDATE_TIME}</option>
		</select>
		</td>
	</tr>
	<tr>
		<td class="row1"><span class="genmed"><strong>{L_DEFAULT_SORT_ORDER}</strong></span></td>
		<td class="row2">
		<select name="sort_order">
			<option {SORT_ASC} value='ASC'>{L_ASC}</option>
			<option {SORT_DESC} value='DESC'>{L_DESC}</option>
		</select>
		</td>
	</tr>
<tr>
	<td class="row1"><strong>{L_PHP_TPL}</strong><br /><span class="gensmall">{L_PHP_TPL_INFO}</span></td>
	<td class="row2">
	<select name="settings_tpl_php" class="forminput">
	<option value="1"{S_PHP_TPL_YES}>{L_YES}</option>
	<option value="0"{S_PHP_TPL_NO}>{L_NO}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><strong>{L_MAX_FILE_SIZE}</strong><br /><span class="gensmall">{L_MAX_FILE_SIZE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="8" maxlength="15" name="max_file_size" value="{MAX_FILE_SIZE}" /> {S_FILESIZE}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_UPLOAD_DIR}</strong><br /><span class="gensmall">{L_UPLOAD_DIR_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="25" maxlength="100" name="upload_dir" class="post" value="{UPLOAD_DIR}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SCREENSHOT_DIR}</strong><br /><span class="gensmall">{L_SCREENSHOT_DIR_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="25" maxlength="100" name="screenshots_dir" class="post" value="{SCREENSHOT_DIR}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FORBIDDEN_EXTENSIONS}</strong><br /><span class="gensmall">{L_FORBIDDEN_EXTENSIONS_EXPLAIN}</span></td>
	<td class="row2"><input type="text" size="25" maxlength="100" name="forbidden_extensions" class="post" value="{FORBIDDEN_EXTENSIONS}" /></td>
</tr>
<tr><th colspan="2">{L_PERMISSION_SETTINGS}</th></tr>
<tr>
	<td class="row1"><strong>{L_ATUH_SEARCH}</strong><br /><span class="gensmall">{L_ATUH_SEARCH_INFO}</span></td>
	<td class="row2">{S_ATUH_SEARCH}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ATUH_STATS}</strong><br /><span class="gensmall">{L_ATUH_STATS_INFO}</span></td>
	<td class="row2">{S_ATUH_STATS}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ATUH_TOPLIST}</strong><br /><span class="gensmall">{L_ATUH_TOPLIST_INFO}</span></td>
	<td class="row2">{S_ATUH_TOPLIST}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ATUH_VIEWALL}</strong><br /><span class="gensmall">{L_ATUH_VIEWALL_INFO}</span></td>
	<td class="row2">{S_ATUH_VIEWALL}</td>
</tr>
<tr><th colspan="2">{L_COMMENT_SETTINGS}</th></tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_HTML}</strong></td>
	<td class="row2">
	<select name="allow_html" class="forminput">
	<option value="1"{S_ALLOW_HTML_YES}>{L_YES}</option>
	<option value="0"{S_ALLOW_HTML_NO}>{L_NO}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_BBCODE}</strong></td>
	<td class="row2">
	<select name="allow_bbcode" class="forminput">
	<option value="1"{S_ALLOW_BBCODE_YES}>{L_YES}</option>
	<option value="0"{S_ALLOW_BBCODE_NO}>{L_NO}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_SMILIES}</strong></span></td>
	<td class="row2">
	<select name="allow_smilies" class="forminput">
	<option value="1"{S_ALLOW_SMILIES_YES}>{L_YES}</option>
	<option value="0"{S_ALLOW_SMILIES_NO}>{L_NO}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_LINKS}</strong></td>
	<td class="row2">
	<select name="allow_comment_links" class="forminput">
	<option value="1"{S_ALLOW_LINKS_YES}>{L_YES}</option>
	<option value="0"{S_ALLOW_LINKS_NO}>{L_NO}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><strong>{L_LINKS_MESSAGE}</strong><br /><span class="gensmall">{L_LINKS_MESSAGE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="no_comment_link_message" value="{MESSAGE_LINK}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_IMAGE}</strong></span></td>
	<td class="row2">
	<select name="allow_comment_images" class="forminput">
	<option value="1"{S_ALLOW_IMAGES_YES}>{L_YES}</option>
	<option value="0"{S_ALLOW_IMAGES_NO}>{L_NO}</option>
	</select></td>
</tr>
<tr>
	<td class="row1"><strong>{L_IMAGE_MESSAGE}</strong><br /><span class="gensmall">{L_IMAGE_MESSAGE_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="no_comment_image_message" value="{MESSAGE_IMAGE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_MAX_CHAR}</strong><br /><span class="gensmall">{L_MAX_CHAR_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="6" name="max_comment_chars" value="{MAX_CHAR}" /></td>
</tr>
<tr>
	<th colspan="2">{L_VALIDATION_SETTINGS}</th>
</tr>
<tr>
	<td class="row1"><strong>{L_NEED_VALIDATION}</strong></td>
	<td class="row2">
	<select name="need_validation" class="forminput">
	<option value="1"{S_NEED_VALIDATION_YES}>{L_YES}</option>
	<option value="0"{S_NEED_VALIDATION_NO}>{L_NO}</option>
	</select></td>
</tr>
<tr>
		<td class="row1"><strong>{L_VALIDATOR}</strong></td>
		<td class="row2">
		<select name="validator">
		<option {VALIDATOR_ADMIN} value='validator_admin'>{L_VALIDATOR_ADMIN_OPTION}</option>
		<option {VALIDATOR_MOD} value='validator_mod'>{L_VALIDATOR_MOD_OPTION}</option>
		</select>
		</td>
</tr>
<tr>
	<td class="row1"><strong>{L_PM_NOTIFY}</strong></td>
	<td class="row2">
	<select name="pm_notify" class="forminput">
	<option value="1"{S_PM_NOTIFY_YES}>{L_YES}</option>
	<option value="0"{S_PM_NOTIFY_NO}>{L_NO}</option>
	</select></td>
</tr>
<tr><td align="center" class="cat" colspan="2"><input class="liteoption" type="submit" value="{L_SUBMIT}" name="submit" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>
