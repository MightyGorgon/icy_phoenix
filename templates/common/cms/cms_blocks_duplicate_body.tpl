<!-- INCLUDE ../common/cms/page_header.tpl -->

<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_blocks.png" alt="{L_BLOCKS_TITLE}" title="{L_BLOCKS_TITLE}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_BLOCKS_TITLE}</h1><span class="genmed">{L_BLOCKS_TEXT}</span></td>
</tr>
</table>

<form method="post" action="{S_BLOCKS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_ACTION}</th>
	<th>{L_B_TITLE}</th>
	<th>{L_B_POSITION}</th>
	<th>{L_B_LAYOUT}</th>
	<th>{L_B_ACTIVE}</th>
	<th>{L_B_DISPLAY}</th>
	<th>{L_B_TYPE}</th>
	<th>{L_B_VIEW_BY}</th>
	<th>{L_B_GROUPS}</th>
</tr>
<!-- BEGIN blocks -->
<tr class="{blocks.ROW_CLASS} {blocks.ROW_CLASS}h" style="background-image: none;">
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;"><input type="checkbox" name="block[]" value="{blocks.BLOCK_CB_ID}" /></td>
	<td class="{blocks.ROW_CLASS}" style="background: none;">{blocks.TITLE}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.POSITION}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.LNAME}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.ACTIVE}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.CONTENT}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.TYPE}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.VIEW}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.GROUPS}</td>
</tr>
<!-- END blocks -->
<tr><td class="spaceRow" colspan="9"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" colspan="9" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="duplicate_blocks" value="{L_BLOCKS_DUPLICATE}" class="mainoption" />
	</td>
</tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->