<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_blocks.png" alt="{L_BLOCKS_TITLE}" title="{L_BLOCKS_TITLE}" /></td>
	<td class="row1" valign="top"><h1>{L_BLOCKS_TITLE} - {LAYOUT_NAME}</h1><span class="genmed">{L_BLOCKS_TEXT}</span></td>
</tr>
</table>

<form method="post" action="{S_BLOCKS_ACTION}">
<!-- IF S_NO_BLOCKS -->
<div class="post-text">{L_NO_BLOCKS_AVAILABLE}</div>
<!-- ELSE -->
<div style="text-align: left">
<!-- BEGIN blocks -->
<!-- IF blocks.POSITION_CHANGE and not blocks.FIRST_ID --></ul></div><br /><br /><!-- ENDIF -->
<!-- IF blocks.POSITION_CHANGE or blocks.FIRST_ID --><div id="{blocks.SORT_CID}"><span class="topiclink">{blocks.POSITION}</span><br /><ul id="{blocks.SORT_SID}" class="sortable-list"><!-- ENDIF -->
<li id="{blocks.SORT_SID}_{blocks.SORT_EID}">
<input type="hidden" class="block_weight" name="weight[{blocks.BLOCK_CB_ID}]" value="{blocks.WEIGHT}" />
<span><img src="{IMG_CMS_ICON_MOVE}" alt="{L_B_MOVE}" title="{L_B_MOVE}" class="sort-handler" /></span>&nbsp;<!-- IF S_B_EDIT --><a href="{blocks.U_MOVE_UP}"><img src="{IMG_CMS_ARROW_UP}" alt="{L_MOVE_UP}" title="{L_B_MOVE_UP}" /></a>&nbsp;<a href="{blocks.U_MOVE_DOWN}"><img src="{IMG_CMS_ARROW_DOWN}" alt="{L_MOVE_DOWN}" title="{L_B_MOVE_DOWN}" /></a>&nbsp;<a href="{blocks.U_EDIT_BS}"><img src="{IMG_CMS_ICON_BLOCKS}" alt="{L_B_EDIT_BS}" title="{L_B_EDIT_BS}" /></a>&nbsp;<a href="{blocks.U_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_B_EDIT}" title="{L_B_EDIT}" /></a>&nbsp;<!-- ENDIF --><!-- IF S_B_DELETE --><a href="{blocks.U_DELETE}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_B_DELETE}" title="{L_B_DELETE}" /></a>&nbsp;<!-- ENDIF -->&nbsp;&nbsp;<input type="checkbox" name="block[]" value="{blocks.BLOCK_CB_ID}"{blocks.BLOCK_CHECKED} />&nbsp;&nbsp;<a href="{blocks.U_EDIT}" class="tiptip" title="{blocks.BLOCK_TIP}">{blocks.TITLE}</a>
</li>
<!-- IF blocks.LAST_ID --></ul></div><br /><!-- ENDIF -->
<!-- END blocks -->
<!-- ENDIF -->
</div>

{S_HIDDEN_FIELDS}
<!-- IF S_B_EDIT --><input type="submit" name="action_update" value="{L_CMS_SAVE_CHANGES}" class="liteoption" /><!-- ENDIF -->
<!-- IF S_B_ADD -->&nbsp;&nbsp;<input type="submit" name="add" value="{L_B_ADD}" class="mainoption" /><!-- ENDIF -->
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->