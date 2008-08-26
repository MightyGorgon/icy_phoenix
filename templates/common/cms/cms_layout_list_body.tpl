<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_cms.png" alt="{L_CMS}" title="{L_CMS}" /></td>
	<td class="row1" valign="top"><h1>{L_LAYOUT_TITLE}</h1><span class="genmed">{L_LAYOUT_TEXT}</span></td>
</tr>
</table>

<!-- BEGIN layout_special -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="5%">{L_CMS_ID}</th>
	<th width="5%">{L_CMS_ACTIONS}</th>
	<th width="35%">{L_CMS_NAME}</th>
	<th width="35%">{L_CMS_FILENAME}</th>
	<th width="20%">{L_CMS_BLOCKS}</th>
</tr>
<!-- BEGIN ls_row -->
<tr class="{layout_special.ls_row.ROW_CLASS} row1h" style="background-image:none;">
	<td class="{layout_special.ls_row.ROW_CLASS} row-center" style="background:none;"><b>{layout_special.ls_row.LAYOUT_ID}</b></td>
	<td class="{layout_special.ls_row.ROW_CLASS} row-center" style="background:none;" nowrap="nowrap">
		<a href="{layout_special.ls_row.U_LAYOUT}"><img src="{IMG_LAYOUT_BLOCKS_EDIT}" alt="{L_CONFIGURE_BLOCKS}" title="{L_CONFIGURE_BLOCKS}" /></a>&nbsp;
		<a href="{layout_special.ls_row.U_PREVIEW_LAYOUT}"><img src="{IMG_LAYOUT_PREVIEW}" alt="{L_PREVIEW}" title="{L_PREVIEW}" /></a>&nbsp;
	</td>
	<td class="{layout_special.ls_row.ROW_CLASS} row-center" style="background:none;">&nbsp;<a href="{layout_special.ls_row.U_LAYOUT}">{layout_special.ls_row.LAYOUT_NAME}</a>&nbsp;</td>
	<td class="{layout_special.ls_row.ROW_CLASS} row-center" style="background:none;">&nbsp;{layout_special.ls_row.LAYOUT_FILENAME}&nbsp;</td>
	<td class="{layout_special.ls_row.ROW_CLASS} row-center" style="background:none;">[&nbsp;{layout_special.ls_row.LAYOUT_BLOCKS}&nbsp;]</td>
</tr>
<!-- END ls_row -->
<tr><td class="spaceRow" colspan="5"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" colspan="5" align="center">
		{S_HIDDEN_FIELDS}
	</td>
</tr>
</table>
<!-- END layout_special -->

<!-- BEGIN layout -->
<form method="post" action="{S_LAYOUT_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="5%" align="center">{L_CMS_ID}</th>
	<th width="5%" align="center">{L_CMS_ACTIONS}</th>
	<th width="30%" align="center">{L_CMS_NAME}</th>
	<th width="25%" align="center">{L_CMS_FILENAME}</th>
	<th width="25%" align="center">{L_CMS_TEMPLATE}</th>
	<th width="10%" align="center">{L_CMS_BLOCKS}</th>
</tr>
<!-- BEGIN l_row -->
<tr class="{layout.l_row.ROW_CLASS} row1h" style="background-image:none;">
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background:none;"><b>{layout.l_row.LAYOUT_ID}</b></td>
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background:none;" nowrap="nowrap">
		<a href="{layout.l_row.U_LAYOUT}"><img src="{IMG_LAYOUT_BLOCKS_EDIT}" alt="{L_CONFIGURE_BLOCKS}" title="{L_CONFIGURE_BLOCKS}" /></a>&nbsp;
		<a href="{layout.l_row.U_PREVIEW_LAYOUT}"><img src="{IMG_LAYOUT_PREVIEW}" alt="{L_PREVIEW}" title="{L_PREVIEW}" /></a>&nbsp;
		<a href="{layout.l_row.U_EDIT_LAYOUT}"><img src="{IMG_BLOCK_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;
		<a href="{layout.l_row.U_DELETE_LAYOUT}"><img src="{IMG_BLOCK_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a>
	</td>
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background:none;{layout.l_row.ROW_DEFAULT_STYLE}">&nbsp;<a href="{layout.l_row.U_LAYOUT}">{layout.l_row.LAYOUT_NAME}</a>&nbsp;</td>
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background:none;">&nbsp;{layout.l_row.LAYOUT_FILENAME}&nbsp;</td>
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background:none;">&nbsp;{layout.l_row.LAYOUT_TEMPLATE}&nbsp;</td>
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background:none;">[&nbsp;{layout.l_row.LAYOUT_BLOCKS}&nbsp;]</td>
</tr>
<!-- END l_row -->
<tr><td class="spaceRow" colspan="6"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" colspan="6" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="add" value="{L_LAYOUT_ADD}" class="mainoption" />
	</td>
</tr>
</table>
</form>
<!-- END layout -->