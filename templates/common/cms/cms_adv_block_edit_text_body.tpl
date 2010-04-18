<!-- IF CMS_STD_TPL -->
<!-- INCLUDE ../common/cms/page_header_std.tpl -->
<!-- ELSE -->
<!-- INCLUDE ../common/cms/page_header.tpl -->
<!-- ENDIF -->

<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_blocks.png" alt="{L_BLOCKS_PAGE_02}" title="{L_BLOCKS_PAGE_02}" /></td>
	<td class="row1" valign="top"><h1>{L_BLOCKS_PAGE_02}</h1><span class="genmed">{L_BLOCKS_TEXT}</span></td>
</tr>
</table>

<!-- BEGIN block_preview -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
	<tr><td class="row-header"><span>{L_PREVIEW}</span></td></tr>
	<tr><td class="row-post" width="100%"><div class="post-text post-text-hide-flow">{block_preview.PREVIEW_MESSAGE}</div></td></tr>
</table>
<!-- END block_preview -->

<form method="post" action="{S_BLOCKS_ACTION}" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_EDIT_BLOCK}</th></tr>
<tr>
	<td class="row1" valign="top">
		<b>{L_B_CONTENT}</b><br /><br /><br />
		<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center" valign="middle"><br />{BBCB_SMILEYS_MG}</td></tr></table>
	</td>
	<td class="row2" valign="top" align="left">
		{BBCB_MG}
		<textarea name="message" rows="15" cols="35" style="width: 98%;" tabindex="3" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{CONTENT}</textarea>
	</td>
</tr>
<tr>
	<td class="row1" align="right"><b>{L_B_TYPE}</b></td>
	<td class="row2">
		<input type="radio" name="type" value="1" {BBCODE} /> {L_B_BBCODE}&nbsp;&nbsp;
		<input type="radio" name="type" value="0" {HTML} /> {L_B_HTML}
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" colspan="2" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="save" class="mainoption" value="{L_SUBMIT}" />&nbsp;&nbsp;
		<input type="submit" name="preview" class="liteoption" value="{L_PREVIEW}" />&nbsp;&nbsp;
		<!-- <input type="submit" name="reset" class="liteoption" value="{L_RESET}" /> -->
		<input type="reset" name="reset" class="liteoption" value="{L_RESET}" />
	</td>
</tr>
</table>
</form>

<!-- IF CMS_STD_TPL -->
<!-- INCLUDE ../common/cms/page_footer_std.tpl -->
<!-- ELSE -->
<!-- INCLUDE ../common/cms/page_footer.tpl -->
<!-- ENDIF -->