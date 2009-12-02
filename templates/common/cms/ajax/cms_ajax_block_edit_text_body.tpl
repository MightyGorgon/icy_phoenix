<!-- INCLUDE ../common/cms/ajax/page_header_ajax.tpl -->

<!-- BEGIN block_preview -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
	<tr><td class="row-header"><span>{L_PREVIEW}</span></td></tr>
	<tr><td class="row-post" width="100%"><div class="post-text-container"><div class="post-text post-text-hide-flow">{block_preview.PREVIEW_MESSAGE}</div></div></td></tr>
</table>
<!-- END block_preview -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
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
		<input type="hidden" name="isconfig" value="1" />
	</td>
</tr>
</table>

<!-- INCLUDE ../common/cms/ajax/page_footer_ajax.tpl -->