<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_blocks.png" alt="{L_BLOCKS_CREATION_02}" title="{L_BLOCKS_CREATION_02}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_BLOCKS_CREATION_02}</h1><span class="genmed">{L_BLOCKS_TEXT}</span></td>
</tr>
</table>

<form method="post" action="{S_BLOCKS_ACTION}" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_EDIT_BLOCK}</th></tr>
<!-- BEGIN cms_block -->
<tr class="row1 row1h">
	<td class="row1"><b>{cms_block.L_FIELD_LABEL}</b>{cms_block.L_FIELD_SUBLABEL}</td>
	<td class="row1">{cms_block.FIELD}</td>
</tr>
<!-- END cms_block -->
<!-- BEGIN cms_no_bv -->
<tr class="row1 row1h"><td class="row1 row-center" colspan="2"><br /><br /><b>{cms_no_bv.L_NO_BV}</b><br /><br /><br /><br /></td></tr>
<!-- END cms_no_bv -->
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat" align="center" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="save" class="mainoption" value="{L_SUBMIT}" />&nbsp;&nbsp;<input type="reset" name="reset" class="liteoption" value="{L_RESET}" /></td></tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->