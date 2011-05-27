<!-- INCLUDE ../common/cms/page_header.tpl -->
<!-- INCLUDE ../common/cms/cms_lang.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_blocks.png" alt="{L_BLOCKS_TITLE}" title="{L_BLOCKS_TITLE}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_CMS_BLOCK_SETTINGS_TITLE}</h1><span class="genmed">{L_CMS_BLOCK_SETTINGS_TEXT}</span></td>
</tr>
</table>

<script type="text/javascript">
// <![CDATA[
var cmsEditorSettings = {JSON_DATA};
// ]]>
</script>

<form method="post" action="{S_BLOCKS_ACTION}" id="add-block-top">
{S_HIDDEN_FIELDS}
<input type="submit" name="add" value="{L_CMS_BLOCK_SETTINGS_INSTALL}" class="liteoption" />
</form>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><td style="padding: 3px;"><div id="cms-editor"></div></td></tr>
</table>

<div id="cms-editor-header"></div>

<div id="cms-editor-footer"></div>

<form method="post" action="{S_BLOCKS_ACTION}" id="add-block-bottom">
{S_HIDDEN_FIELDS}
<input type="submit" name="add" value="{L_CMS_BLOCK_SETTINGS_INSTALL}" class="liteoption" />
</form>

<!-- INCLUDE ../common/cms/cms_info_box.tpl -->
<!-- INCLUDE ../common/cms/page_footer.tpl -->