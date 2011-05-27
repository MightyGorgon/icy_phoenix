<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_blocks.png" alt="{L_BLOCKS_PAGE_01}" title="{L_BLOCKS_PAGE_01}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_CMS_BLOCK_PAGE}</h1><span class="genmed">{L_BLOCKS_TEXT}</span></td>
</tr>
</table>

<form method="post" action="{S_BLOCKS_ACTION}" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_EDIT_BLOCK}</th></tr>
<tr class="row1 row1h">
	<td class="row1" width="200">{L_B_NAME}</td>
	<td class="row1"><input type="text" maxlength="60" size="30" name="name" value="{NAME}" class="post" /></td>
</tr>
<tr class="row1 row1h">
	<td class="row1">{L_B_BLOCKFILE}</td>
	<td class="row1">{BLOCKFILE}</td>
</tr>
<tr class="row1 row1h">
	<td class="row1">{L_B_VIEW_BY}</td>
	<td class="row1">{VIEWBY}</td>
</tr>
<tr class="row1 row1h">
	<td class="row1">{L_B_GROUPS}</td>
	<td class="row1">{GROUP}</td>
</tr>
<!-- IF S_ADMIN && IN_CMS_USERS -->
<tr class="row1 row1h">
	<td class="row1">{L_CMS_USERS_B_GLOBAL}</td>
	<td class="row1"><input type="checkbox" name="locked" {LOCKED}/></td>
</tr>
<!-- ENDIF -->
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat" align="center" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="hascontent" class="mainoption" value="{L_SUBMIT}" />&nbsp;&nbsp;<input type="reset" name="reset" class="liteoption" value="{L_RESET}" /></td></tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->