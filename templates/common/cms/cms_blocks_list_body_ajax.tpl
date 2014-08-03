<!-- INCLUDE ../common/cms/page_header.tpl -->
<!-- INCLUDE ../common/cms/cms_lang.tpl -->

<table class="forumline">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_blocks.png" alt="{L_BLOCKS_TITLE}" title="{L_BLOCKS_TITLE}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_BLOCKS_TITLE} - {LAYOUT_NAME}</h1><span class="genmed">{L_BLOCKS_TEXT}</span></td>
</tr>
</table>
<script type="text/javascript">
// <![CDATA[
var cmsEditorData = {JSON_DATA};
// ]]>
</script>

<!-- IF S_GLOBAL_LAYOUT -->

<!-- BEGIN gheader_blocks_row -->{gheader_blocks_row.CMS_BLOCK}<!-- END gheader_blocks_row -->
<table>
<tr>
	<td class="tw180px" style="padding-right: 7px; vertical-align: top;">
		<!-- BEGIN ghleft_blocks_row -->{ghleft_blocks_row.CMS_BLOCK}<!-- END ghleft_blocks_row -->
	</td>
	<td style="vertical-align: top;">
		<!-- BEGIN ghtop_blocks_row -->{ghtop_blocks_row.CMS_BLOCK}<!-- END ghtop_blocks_row -->
		<!-- BEGIN ghbottom_blocks_row -->{ghbottom_blocks_row.CMS_BLOCK}<!-- END ghbottom_blocks_row -->
		<div class="cms-editor-container">{L_B_DISPLAY}</div>
	</td>
	<td class="tw180px" style="padding-left: 7px; vertical-align: top;">
		<!-- BEGIN ghright_blocks_row -->{ghright_blocks_row.CMS_BLOCK}<!-- END ghright_blocks_row -->
	</td>
</tr>
</table>
<!-- BEGIN gfooter_blocks_row -->{gfooter_blocks_row.CMS_BLOCK}<!-- END gfooter_blocks_row -->

<!-- ELSEIF S_PAGE_LAYOUT -->

<!-- BEGIN header_blocks_row -->{header_blocks_row.CMS_BLOCK}<!-- END header_blocks_row -->
<table>
<tr>
	<td class="tw180px" style="padding-right: 7px; vertical-align: top;">
		<!-- BEGIN headerleft_blocks_row -->{headerleft_blocks_row.CMS_BLOCK}<!-- END headerleft_blocks_row -->
	</td>
	<td style="vertical-align: top;">
		<!-- BEGIN headercenter_blocks_row -->{headercenter_blocks_row.CMS_BLOCK}<!-- END headercenter_blocks_row -->
		<div class="cms-editor-container">{L_B_DISPLAY}</div>
		<!-- BEGIN footercenter_blocks_row -->{footercenter_blocks_row.CMS_BLOCK}<!-- END footercenter_blocks_row -->
	</td>
	<td class="tw180px" style="padding-left: 7px; vertical-align: top;">
		<!-- BEGIN footerright_blocks_row -->{footerright_blocks_row.CMS_BLOCK}<!-- END footerright_blocks_row -->
	</td>
</tr>
</table>
<!-- BEGIN footer_blocks_row -->{footer_blocks_row.CMS_BLOCK}<!-- END footer_blocks_row -->

<!-- ELSE -->
<?php $this->pparse('layout_blocks'); ?>
<!-- ENDIF -->

<div id="cms-editor-header"></div>

<div id="cms-editor-footer"></div>

<!-- INCLUDE ../common/cms/cms_info_box.tpl -->
<!-- INCLUDE ../common/cms/page_footer.tpl -->