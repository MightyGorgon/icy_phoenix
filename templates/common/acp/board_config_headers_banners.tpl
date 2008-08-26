<h1>{L_CONFIGURATION_TITLE}</h1>
<p>{L_CONFIGURATION_EXPLAIN}</p>

<form action="{S_CONFIG_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_TOP_HEADER_BOTTOM_FOOTER}</th></tr>
<tr>
	<td class="row1"><strong>{L_TOP_HTML_BLOCK_SWITCH}</strong><br /><span class="gensmall">{L_TOP_HTML_BLOCK_SWITCH_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="switch_top_html_block" value="1" {TOP_HTML_BLOCK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="switch_top_html_block" value="0" {TOP_HTML_BLOCK_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="300">{L_TOP_HTML_BLOCK_TEXT}</td>
	<td class="row2"><textarea name="top_html_block_text" rows="10" cols="35" wrap="virtual" style="width:300" class="post" >{TOP_HTML_BLOCK_TXT}</textarea></td>
</tr>
<tr>
	<td class="row1"><strong>{L_BOTTOM_HTML_BLOCK_SWITCH}</strong><br /><span class="gensmall">{L_BOTTOM_HTML_BLOCK_SWITCH_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="switch_bottom_html_block" value="1" {BOTTOM_HTML_BLOCK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="switch_bottom_html_block" value="0" {BOTTOM_HTML_BLOCK_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="300">{L_BOTTOM_HTML_BLOCK_TEXT}</td>
	<td class="row2"><textarea name="bottom_html_block_text" rows="10" cols="35" wrap="virtual" style="width:300" class="post" >{BOTTOM_HTML_BLOCK_TXT}</textarea></td>
</tr>
<tr><th colspan="2">{L_HEADER_FOOTER}</th></tr>
<tr>
	<td class="row1"><strong>{L_HEADER_TABLE_SWITCH}</strong><br /><span class="gensmall">{L_HEADER_TABLE_SWITCH_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="switch_header_table" value="1" {HEADER_TBL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="switch_header_table" value="0" {HEADER_TBL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="300">{L_HEADER_TABLE_TEXT}</td>
	<td class="row2"><textarea name="header_table_text" rows="10" cols="35" wrap="virtual" style="width:300" class="post" >{HEADER_TBL_TXT}</textarea></td>
</tr>
<tr>
	<td class="row1"><strong>{L_FOOTER_TABLE_SWITCH}</strong><br /><span class="gensmall">{L_FOOTER_TABLE_SWITCH_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="switch_footer_table" value="1" {FOOTER_TBL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="switch_footer_table" value="0" {FOOTER_TBL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="300">{L_FOOTER_TABLE_TEXT}</td>
	<td class="row2"><textarea name="footer_table_text" rows="10" cols="35" wrap="virtual" style="width:300" class="post" >{FOOTER_TBL_TXT}</textarea></td>
</tr>
<tr><th colspan="2">{L_BANNER_TITLE}</th></tr>
<tr>
	<td class="row1"><strong>{L_BANNER_HEADER}</strong><br /><span class="gensmall">{L_BANNER_HEADER_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="switch_header_banner" value="1" {HEADER_BANNER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="switch_header_banner" value="0" {HEADER_BANNER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="300"><strong>{L_BANNER_HEADER_CODE}</strong><br /><span class="gensmall">{L_BANNER_HEADER_CODE_EXPLAIN}</span></td>
	<td class="row2"><textarea name="header_banner_text" rows="10" cols="35" wrap="virtual" style="width:300" class="post" >{HEADER_BANNER_CODE}</textarea></td>
</tr>
<tr>
	<td class="row1"><strong>{L_BANNER_VIEWTOPIC}</strong><br /><span class="gensmall">{L_BANNER_VIEWTOPIC_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="switch_viewtopic_banner" value="1" {VIEWTOPIC_BANNER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="switch_viewtopic_banner" value="0" {VIEWTOPIC_BANNER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="300"><strong>{L_BANNER_VIEWTOPIC_CODE}</strong><br /><span class="gensmall">{L_BANNER_VIEWTOPIC_CODE_EXPLAIN}</span></td>
	<td class="row2"><textarea name="viewtopic_banner_text" rows="10" cols="35" wrap="virtual" style="width:300" class="post" >{VIEWTOPIC_BANNER_CODE}</textarea></td>
</tr>

<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>

<br clear="all" />
