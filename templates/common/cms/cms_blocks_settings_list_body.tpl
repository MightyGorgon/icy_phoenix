<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_blocks.png" alt="{L_BLOCKS_TITLE}" title="{L_BLOCKS_TITLE}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_CMS_BLOCK_SETTINGS_TITLE}</h1><span class="genmed">{L_CMS_BLOCK_SETTINGS_TEXT}</span></td>
</tr>
</table>

<form method="post" action="{S_BLOCKS_ACTION}">
<table class="forumline">
<tr>
	<th>{L_ACTION}</th>
	<th>{L_B_NAME}</th>
	<th>{L_B_DISPLAY}</th>
	<th>{L_B_TYPE}</th>
	<th>{L_B_VIEW_BY}</th>
	<th>{L_B_GROUPS}</th>
	<!-- IF S_ADMIN && IN_CMS_USERS --><th>{L_USERNAME}</th>
	<th>{L_STATUS}</th><!-- ENDIF -->
</tr>
<!-- IF S_NO_BLOCKS -->
<tr><td class="row1" colspan="9"><div class="post-text">{L_NO_BLOCKS_AVAILABLE}</div></td></tr>
<!-- ELSE -->
<!-- BEGIN blocks -->
<tr class="{blocks.ROW_CLASS} {blocks.ROW_CLASS}h" style="background-image: none;">
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">
		<!-- IF blocks.S_MANAGE -->
		<!-- IF S_B_EDIT --><a href="{blocks.U_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;<!-- ENDIF -->
		<!-- IF S_B_DELETE --><a href="{blocks.U_DELETE}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a><!-- ENDIF -->
		<!-- ELSE -->-<!-- ENDIF -->
	</td>
	<td class="{blocks.ROW_CLASS}" style="background: none;"><a href="{blocks.U_EDIT}">{blocks.NAME}</a></td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.CONTENT}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.TYPE}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.VIEW}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.GROUPS}</td>
	<!-- IF S_ADMIN && IN_CMS_USERS --><td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.USERNAME}</td>
	<td class="{blocks.ROW_CLASS} row-center" style="background: none;">{blocks.STATUS}</td><!-- ENDIF -->
</tr>
<!-- END blocks -->
<!-- ENDIF -->
<!-- IF S_B_ADD -->
<tr><td class="spaceRow" colspan="9"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" colspan="9" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="add" value="{L_CMS_BLOCK_SETTINGS_INSTALL}" class="liteoption" />
	</td>
</tr>
<!-- ENDIF -->
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->