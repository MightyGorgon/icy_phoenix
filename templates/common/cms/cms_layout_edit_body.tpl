<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_edit.png" alt="{L_CMS_PAGES}" title="{L_CMS_PAGES}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_CMS_PAGES}</h1><span class="genmed">{L_LAYOUT_TEXT}</span></td>
</tr>
</table>

<form method="post" action="{S_LAYOUT_ACTION}">
<table class="forumline">
<tr><th colspan="2">&nbsp;{L_LAYOUT_EDIT}&nbsp;</th></tr>
<!-- IF not S_LAYOUT_SPECIAL -->
<tr class="row1 row1h">
	<td class="row1 row-center" colspan="2">
		<table>
		<tr>
			<!-- BEGIN layouts -->
			<td class="tdalignc">{layouts.LAYOUT_IMG}</td>
			<!-- END layouts -->
		</tr>
		<tr>
			<!-- BEGIN layouts -->
			<td class="tdalignc">{layouts.LAYOUT_RADIO}</td>
			<!-- END layouts -->
		</tr>
		</table>
	</td>
</tr>
<!-- ENDIF -->
<tr class="row1 row1h">
	<td class="row1">{L_LAYOUT_NAME}</td>
	<td class="row1"><input type="text" maxlength="100" size="30" name="name" value="{NAME}" class="post" /></td>
</tr>
<!-- IF S_LAYOUT_SPECIAL -->
<tr class="row1 row1h">
	<td class="row1">{L_CMS_LAYOUT_PAGE_ID}<br /><span class="gensmall">{L_CMS_LAYOUT_PAGE_ID_EXPLAIN}</span></td>
	<td class="row2"><input type="text" maxlength="100" size="30" name="page_id" value="{PAGE_ID}" class="post" /></td>
</tr>
<!-- ENDIF -->
<tr class="row1 row1h">
	<td class="row1">{L_CMS_FILENAME}<!-- IF not S_LAYOUT_SPECIAL --><br /><span class="gensmall">{L_CMS_FILENAME_EXPLAIN}</span><!-- IF L_CMS_FILENAME_AUTH --><br /><span class="gensmall">{L_CMS_FILENAME_AUTH}</span><!-- ENDIF --><!-- ENDIF --></td>
	<td class="row2"><input type="text" maxlength="100" size="30" name="filename" value="{FILENAME}" class="post" /></td>
</tr>
<!--
<tr class="row1 row1h">
	<td class="row1">{L_LAYOUT_GLOBAL_BLOCKS}</td>
	<td class="row2">
		<input type="radio" name="global_blocks" value="1" {GLOBAL_BLOCKS} /> {L_YES}&nbsp;&nbsp;
		<input type="radio" name="global_blocks" value="0" {NOT_GLOBAL_BLOCKS} /> {L_NO}
	</td>
</tr>
-->
<tr class="row1 row1h">
	<td class="row1">{L_LAYOUT_PAGE_NAV}</td>
	<td class="row2">
		<input type="radio" name="page_nav" value="1" {PAGE_NAV} /> {L_YES}&nbsp;&nbsp;
		<input type="radio" name="page_nav" value="0" {NOT_PAGE_NAV} /> {L_NO}
	</td>
</tr>
<tr class="row1 row1h">
	<td class="row1">{L_LAYOUT_VIEW}</td>
	<td class="row2">{VIEW}</td>
</tr>
<!-- IF not S_LAYOUT_SPECIAL -->
<tr class="row1 row1h">
	<td class="row1">{L_PERMISSIONS}</td>
	<td class="row2"><a href="{U_EDIT_AUTH}">{L_CMS_PERMISSIONS_AUTH_EDIT_SET}</a></td>
</tr>
<tr class="row1 row1h">
	<td class="row1">{L_B_GROUPS}</td>
	<td class="row2">{GROUPS}</td>
</tr>
<!-- ENDIF -->
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat tdalignc" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->