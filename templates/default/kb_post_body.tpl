<script type="text/javascript">
<!--
function attach_rules(forum_id)
{
	window.open('{U_ATTACH_RULES}' + forum_id + '&sid={S_SID}', '_postedittime', 'height=200, width=500, resizable=no, scrollbars=no');
}
//-->
</script>

<form method="post" action="{S_ACTION}" onsubmit="return checkForm(this)" name="post">
{KB_PRETEXT_BOX}
{KB_PREVIEW_BOX}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_ADD_ARTICLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN switch_name -->
<tr>
	<td class="row1" width="22%"><span class="gen"><b><nobr>{L_NAME}</nobr></b></span></td>
	<td class="row2" width="78%"><span class="gen"><input type="text" name="username" size="45" maxlength="100" style="width: 98%; class="post" value="{USERNAME}" /></span></td>
</tr>
<!-- END switch_name -->
<tr>
	<td class="row1" width="22%"><span class="gen"><b><nobr>{L_ARTICLE_TITLE}</nobr></b></span></td>
	<td class="row2"><span class="gen"><input type="text" name="article_name" size="45" maxlength="100" style="width: 98%;" class="post" value="{ARTICLE_TITLE}" /></span></td>
</tr>
<tr>
	<td class="row1" width="22%"><span class="gen"><b>{L_ARTICLE_DESCRIPTION}</b></span></td>
	<td class="row2"><span class="gen"><input type="text" name="article_desc" size="45" maxlength="255" style="width: 98%;" class="post" value="{ARTICLE_DESC}" /></span></td>
</tr>
<tr>
	<td class="row1" valign="top"><span class="gen"><b><nobr>{L_ARTICLE_TYPE}</nobr></b></span></td>
	<td class="row2"><span class="gen">&nbsp;<select name="type_id"><option value="select_one">{L_SELECT_TYPE}</option><!-- BEGIN types -->{types.TYPE}<!-- END types --></select></span></td>
</tr>
<!-- BEGIN switch_edit -->
<tr>
	<td class="row1" width="22%"><span class="gen"><b>{L_ARTICLE_CATEGORY}</b></span></td>
	<td class="row2">&nbsp;<select name="cat">{switch_edit.CAT_LIST}</select></td>
</tr>
<!-- END switch_edit -->
<!-- BEGIN custom_data_fields -->
<tr><td class="cat" colspan="2" align="center"><span class="cattitle">{custom_data_fields.L_ADDTIONAL_FIELD}</span></td></tr>
<!-- END custom_data_fields -->

<!-- BEGIN input -->
<tr>
	<td class="row1" width="22%"><span class="genmed">{input.FIELD_NAME}</span><br /><span class="gensmall">{input.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><input type="text" class="post" size="50" name="field[{input.FIELD_ID}]" value="{input.FIELD_VALUE}" /></td>
</tr>
<!-- END input -->
<!-- SPILT -->
<!-- BEGIN textarea -->
<tr>
	<td class="row1" width="22%"><span class="genmed">{textarea.FIELD_NAME}</span><br /><span class="gensmall">{textarea.FIELD_DESCRIPTION}</span></td>
	<td class="row2"><textarea rows="6" class="post" name="field[{textarea.FIELD_ID}]" cols="32">{textarea.FIELD_VALUE}</textarea></td>
</tr>
<!-- END textarea -->
<!-- SPILT -->
<!-- BEGIN radio -->
<tr>
	<td class="row1" width="22%"><span class="genmed">{radio.FIELD_NAME}</span><br /><span class="gensmall">{radio.FIELD_DESCRIPTION}</span></td>
	<td class="row2">
		<!-- BEGIN row -->
		<input type="radio" name="field[{radio.FIELD_ID}]" value="{radio.row.FIELD_VALUE}" {radio.row.FIELD_SELECTED} /><span class="gensmall">{radio.row.FIELD_VALUE}</span>&nbsp;
		<!-- END row -->
	</td>
</tr>
<!-- END radio -->
<!-- SPILT -->
<!-- BEGIN select -->
<tr>
	<td class="row1" width="22%"><span class="genmed">{select.FIELD_NAME}</span><br /><span class="gensmall">{select.FIELD_DESCRIPTION}</span></td>
	<td class="row2">
		<select name="field[{select.FIELD_ID}]" class="post">
			<!-- BEGIN row -->
			<option value="{select.row.FIELD_VALUE}"{radio.row.FIELD_SELECTED}>{select.row.FIELD_VALUE}</option>
			<!-- END row -->
		</select>
	</td>
</tr>
<!-- END select -->
<!-- SPILT -->
<!-- BEGIN select_multiple -->
<tr>
	<td class="row1" width="22%"><span class="genmed">{select_multiple.FIELD_NAME}</span><br /><span class="gensmall">{select_multiple.FIELD_DESCRIPTION}</span></td>
	<td class="row2">
		<select name="field[{select_multiple.FIELD_ID}][]" multiple="multiple" size="4" class="post">
			<!-- BEGIN row -->
			<option value="{select_multiple.row.FIELD_VALUE}"{select_multiple.row.FIELD_SELECTED}>{select_multiple.row.FIELD_VALUE}</option>
			<!-- END row -->
		</select>
	</td>
</tr>
<!-- END select_multiple -->
<!-- SPILT -->
<!-- BEGIN checkbox -->
<tr>
	<td class="row1" width="22%"><span class="genmed">{checkbox.FIELD_NAME}</span><br /><span class="gensmall">{checkbox.FIELD_DESCRIPTION}</span></td>
	<td class="row2">
		<!-- BEGIN row -->
		<input type="checkbox" name="field[{checkbox.FIELD_ID}][{checkbox.row.FIELD_VALUE}]" value="{checkbox.row.FIELD_VALUE}" {checkbox.row.FIELD_CHECKED} /><span class="gensmall">{checkbox.row.FIELD_VALUE}</span>&nbsp;
		<!-- END row -->
	</td>
</tr>
<!-- END checkbox -->
<tr>
	<td class="row1" valign="top"><span class="gen"><b><nobr>{L_ARTICLE_TEXT}</nobr></b><br /><br />
		<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center" valign="middle"><br />{BBCB_SMILEYS_MG}</td></tr></table>
		<br /><br /><span class="gen"><b><nobr>{L_OPTIONS}</nobr></b></span><br /><span class="gensmall">{HTML_STATUS}<br />{BBCODE_STATUS}<br />{SMILIES_STATUS}</span><br /><br />
	</td>
	<td class="row2" valign="top">
		<span class="gen">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="row2" valign="top" align="left">
						{BBCB_MG}
						<textarea name="message" rows="30" cols="35" style="width: 98%" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{ARTICLE_BODY}</textarea>
						<!-- BEGIN formatting -->
						<!--
						<br /><span class="gen"><b><nobr>{L_FORMATTING}</nobr></b></span><hr><span class="gensmall"><b>{L_PAGES}</b><br />{L_PAGES_EXPLAIN}<br /><b>{L_TOC}</b><br />{L_TOC_EXPLAIN}<br /><b>{L_ABSTRACT}</b><br />{L_ABSTRACT_EXPLAIN}<br /><hr><b>{L_TITLE_FORMAT}</b><br />{L_TITLE_FORMAT_EXPLAIN}<br /><b>{L_SUBTITLE_FORMAT}</b><br />{L_SUBTITLE_FORMAT_EXPLAIN}<br /><b>{L_SUBSUBTITLE_FORMAT}</b><br />{L_SUBSUBTITLE_FORMAT_EXPLAIN}</span><br /><br />
						-->
						<!-- END formatting -->
					</td>
				</tr>
			</table>
		</span>
	</td>
</tr>
<tr>
	<td class="catBottom" colspan="2">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="preview" value="{L_PREVIEW}" class="mainoption" />&nbsp;&nbsp;
		<input type="submit" name="article_submit" class="mainoption" value="{L_SUBMIT}" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>