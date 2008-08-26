<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_blocks.png" alt="{L_BLOCKS_TITLE}" title="{L_BLOCKS_TITLE}" /></td>
	<td class="row1" valign="top"><h1>{L_BLOCKS_TITLE} - {LAYOUT_NAME}</h1><span class="genmed">{L_BLOCKS_TEXT}</span></td>
</tr>
</table>

<form method="post" action="{S_BLOCKS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_ACTION}</th>
	<th>{L_B_TITLE}</th>
	<th>{L_B_POSITION}</th>
	<th>{L_B_ACTIVE}</th>
	<th>{L_B_DISPLAY}</th>
	<th>{L_B_TYPE}</th>
	<th>{L_B_VIEW_BY}</th>
	<th>{L_B_GROUPS}</th>
</tr>
<!-- BEGIN blocks -->
<tr class="{blocks.ROW_CLASS} row1h" style="background-image:none;">
	<td class="{blocks.ROW_CLASS} row-center" style="background:none;">
		<a href="{blocks.U_MOVE_UP}"><img src="{IMG_CMS_ARROW_UP}" alt="{L_MOVE_UP}" title="{L_MOVE_UP}" /></a>&nbsp;
		<a href="{blocks.U_MOVE_DOWN}"><img src="{IMG_CMS_ARROW_DOWN}" alt="{L_MOVE_DOWN}" title="{L_MOVE_DOWN}" /></a>&nbsp;
		<a href="{blocks.U_EDIT}"><img src="{IMG_BLOCK_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;
		<a href="{blocks.U_DELETE}"><img src="{IMG_BLOCK_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a>
	</td>
	<td class="{blocks.ROW_CLASS}" style="background:none;"><a href="{blocks.U_EDIT}">{blocks.TITLE}</a></td>
	<td class="{blocks.ROW_CLASS} row-center" style="background:none;">{blocks.POSITION}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background:none;"><input type="checkbox" name="block[]" value="{blocks.BLOCK_CB_ID}"{blocks.BLOCK_CHECKED} /></td>
	<td class="{blocks.ROW_CLASS} row-center" style="background:none;">{blocks.CONTENT}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background:none;">{blocks.TYPE}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background:none;">{blocks.VIEW}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background:none;">{blocks.GROUPS}</td>
</tr>
<!-- END blocks -->
<tr><td class="spaceRow" colspan="9"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" colspan="9" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="add" value="{L_BLOCKS_ADD}" class="mainoption" />
		&nbsp;&nbsp;<input type="submit" name="action_update" value="{L_BLOCKS_UPDATE}" class="liteoption" />
		<!-- BEGIN duplicate_switch -->
		&nbsp;&nbsp;<input type="submit" name="action_duplicate" value="{L_BLOCKS_DUPLICATE}" class="liteoption" />
		<!-- END duplicate_switch -->
	</td>
</tr>
</table>
</form>