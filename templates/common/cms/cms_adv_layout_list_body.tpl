<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_cms.png" alt="{L_CMS}" title="{L_CMS}" /></td>
	<td class="row1" valign="top">
		<!-- BEGIN cms_user -->
		<div style="float: right">{L_CMS_OWNER}: {cms_user.CMS_USERNAME}</div>
		<h1>{cms_user.CMS_PAGE_TITLE}</h1>
		<!-- END cms_user -->
		<span class="genmed">{L_LAYOUT_TEXT}</span>
	</td>
</tr>
</table>


<!-- BEGIN layout -->
<form method="post" action="{S_LAYOUT_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<th width="5%" align="center">{L_CMS_ID}</th>
		<th width="5%" align="center">{L_CMS_ACTIONS}</th>
		<th width="30%" align="center">{L_CMS_NAME}</th>
		<th width="20%" align="center">{L_CMS_FILENAME}</th>
		<th width="15%" align="center">{L_CMS_LAYOUT}</th>
		<th width="15%" align="center">{L_CMS_TEMPLATE}</th>
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
		<td class="{layout.l_row.ROW_CLASS}" style="background:none;{layout.l_row.ROW_DEFAULT_STYLE}">&nbsp;<a href="{layout.l_row.U_LAYOUT}">{layout.l_row.LAYOUT_NAME}</a>&nbsp;</td>
		<td class="{layout.l_row.ROW_CLASS} row-center" style="background:none;">&nbsp;{layout.l_row.LAYOUT_FILENAME}&nbsp;</td>
		<td class="{layout.l_row.ROW_CLASS} row-center" style="background:none;">&nbsp;{layout.l_row.LAYOUT_LAYOUT}&nbsp;</td>
		<td class="{layout.l_row.ROW_CLASS} row-center" style="background:none;">&nbsp;{layout.l_row.LAYOUT_TEMPLATE}&nbsp;</td>
		<td class="{layout.l_row.ROW_CLASS} row-center" style="background:none;">[&nbsp;{layout.l_row.LAYOUT_BLOCKS}&nbsp;]</td>
	</tr>
	<!-- END l_row -->
	<tr><td class="spaceRow" colspan="7"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
	<tr>
		<td class="cat" colspan="7" align="center">
			{S_HIDDEN_FIELDS}
			<input type="submit" name="add" value="{L_LAYOUT_ADD}" class="mainoption" />
		</td>
	</tr>
</table>
</form>
<!-- END layout -->