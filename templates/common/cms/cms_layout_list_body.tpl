<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_cms.png" alt="{L_CMS_TITLE}" title="{L_CMS_TITLE}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_LAYOUT_TITLE}</h1><span class="genmed">{L_LAYOUT_TEXT}</span></td>
</tr>
</table>

<!-- IF CMS_CHANGES_SAVED -->
<script type="text/javascript">
// <![CDATA[
setTimeout(function() { $('#box-updated').fadeOut(1000); }, 3000);
// ]]>
</script>
<div id="box-updated" class="row-center" style="position: fixed; top: 10px; right: 20px; z-index: 1; background: none; border: none; height: 26px; width: 300px; padding: 3px;"><div id="result-box" class="rmbox rmb-green"><p class="rmb-center">{L_CMS_CHANGES_SAVED}</p></div></div>
<!-- ENDIF -->

<!-- BEGIN layout -->
<form method="post" action="{S_LAYOUT_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th style="width: 30px;">{L_CMS_ID}</th>
	<th style="width: 120px;">{L_CMS_ACTIONS}</th>
	<th>{L_CMS_NAME}</th>
	<th>{L_CMS_FILENAME}</th>
	<!-- IF not S_LAYOUT_SPECIAL --><th>{L_CMS_TEMPLATE}</th><!-- ENDIF -->
	<th style="width: 120px;" nowrap="nowrap">{L_CMS_PERMISSIONS_AUTH_VIEW}</th>
	<!-- <th style="width: 95px;" nowrap="nowrap">{L_CMS_GLOBAL_BLOCKS}</th> -->
	<th style="width: 90px;" nowrap="nowrap">{L_CMS_BREADCRUMBS}</th>
	<th style="width: 70px;" nowrap="nowrap">{L_CMS_BLOCKS}</th>
</tr>
<!-- BEGIN l_row -->
<tr class="{layout.l_row.ROW_CLASS} {layout.l_row.ROW_CLASS}h" style="background-image: none;">
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background: none;"><b>{layout.l_row.LAYOUT_ID}</b></td>
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background: none;" nowrap="nowrap">
		<a href="{layout.l_row.U_LAYOUT}"><img src="{IMG_CMS_ICON_BLOCKS}" alt="{L_CMS_CONFIGURE_BLOCKS}" title="{L_CMS_CONFIGURE_BLOCKS}" style="margin-right: 3px;" /></a><a href="{layout.l_row.U_PREVIEW_LAYOUT}"><img src="{IMG_CMS_ICON_PREVIEW}" alt="{L_PREVIEW}" title="{L_PREVIEW}" style="margin-right: 3px;" /></a><a href="{layout.l_row.U_EDIT_AUTH}"><img src="{IMG_CMS_ICON_PERMISSIONS}" alt="{L_CMS_PERMISSIONS_AUTH_EDIT_SET}" title="{L_CMS_PERMISSIONS_AUTH_EDIT_SET}" style="margin-right: 3px;" /></a><!-- IF not layout.l_row.LOCKED  --><!-- IF S_L_EDIT --><a href="{layout.l_row.U_EDIT_LAYOUT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" style="margin-right: 3px;" /></a><!-- ENDIF --><!-- IF S_L_DELETE --><a href="{layout.l_row.U_DELETE_LAYOUT}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a><!-- ENDIF --><!-- ENDIF -->
	</td>
	<td class="{layout.l_row.ROW_CLASS}" style="background: none;{layout.l_row.ROW_DEFAULT_STYLE}"><!-- IF layout.l_row.U_COPY --><a href="{layout.l_row.U_COPY}" style="display: inline-block; float: right;" class="cms-button-small">{L_COPY_LAYOUT}</a><!-- ENDIF --><a href="{layout.l_row.U_LAYOUT}">{layout.l_row.LAYOUT_NAME}</a></td>
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background: none;"><!-- IF layout.l_row.LAYOUT_FILENAME -->{layout.l_row.LAYOUT_FILENAME}<!-- ELSE -->&nbsp;<!-- ENDIF --></td>
	<!-- IF not S_LAYOUT_SPECIAL --><td class="{layout.l_row.ROW_CLASS} row-center" style="background: none;">{layout.l_row.LAYOUT_TEMPLATE}</td><!-- ENDIF -->
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background: none;">{layout.l_row.PAGE_AUTH}</td>
	<!-- <td class="{layout.l_row.ROW_CLASS} row-center" style="background: none;"><input type="checkbox" name="layout_gb[]" value="{layout.l_row.LAYOUT_ID}"{layout.l_row.GB_CHECKED} /></td> -->
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background: none;"><input type="checkbox" name="layout_bc[]" value="{layout.l_row.LAYOUT_ID}"{layout.l_row.BC_CHECKED} /></td>
	<td class="{layout.l_row.ROW_CLASS} row-center" style="background: none;">[&nbsp;{layout.l_row.LAYOUT_BLOCKS}&nbsp;]</td>
</tr>
<!-- END l_row -->
<tr><td class="spaceRow" colspan="9"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat" colspan="9" align="center">{S_HIDDEN_FIELDS}<!-- IF S_L_EDIT --><input type="submit" name="action_update" value="{L_CMS_SAVE_CHANGES}" class="liteoption" /><!-- ENDIF --><!-- IF S_L_ADD -->&nbsp;&nbsp;<input type="submit" name="add" value="{L_LAYOUT_ADD}" class="mainoption" /><!-- ENDIF --></td></tr>
</table>
</form>
<!-- END layout -->

<!-- INCLUDE ../common/cms/page_footer.tpl -->