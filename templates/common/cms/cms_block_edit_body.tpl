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

<form method="post" action="{S_BLOCKS_ACTION}" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_EDIT_BLOCK}</th></tr>
<tr>
	<td class="row1" style="padding:0px" valign="top">
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<!-- BEGIN cms_block -->
		<tr>
			<td class="row1"><b>{cms_block.L_FIELD_LABEL}</b>{cms_block.L_FIELD_SUBLABEL}</td>
			<td class="row2">{cms_block.FIELD}</td>
		</tr>
		<!-- END cms_block -->
		<!-- BEGIN cms_no_bv -->
		<tr><td class="row1 row-center" colspan="2"><br /><br /><b>{cms_no_bv.L_NO_BV}</b><br /><br /><br /><br /></td></tr>
		<!-- END cms_no_bv -->
		</table>
	</td>
</tr>
<tr><td class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="save" class="mainoption" value="{L_SUBMIT}" />&nbsp;&nbsp;
		<input type="submit" name="reset" class="liteoption" value="{L_RESET}" />
		<!-- <input type="reset" name="reset" class="liteoption" value="{L_RESET}" /> -->
	</td>
</tr>
</table>
</form>

<!-- IF CMS_STD_TPL -->
<!-- INCLUDE ../common/cms/page_footer_std.tpl -->
<!-- ELSE -->
<!-- INCLUDE ../common/cms/page_footer.tpl -->
<!-- ENDIF -->