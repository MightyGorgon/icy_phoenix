<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_edit.png" alt="{L_CMS_PAGES}" title="{L_CMS_PAGES}" /></td>
	<td class="row1" valign="top"><h1>{L_CMS_PAGES}</h1><span class="genmed">{L_LAYOUT_TEXT}</span></td>
</tr>
</table>

<form method="post" action="{S_LAYOUT_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">&nbsp;{L_EDIT_LAYOUT}&nbsp;</th></tr>
<tr>
	<td class="row1" style="padding:0px" valign="top">
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="row1" colspan="2" style="text-align:center">
				<table class="forumline" width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<!-- BEGIN layouts -->
					<td class="row1" style="text-align:center">{layouts.LAYOUT_IMG}</td>
					<!-- END layouts -->
				</tr>
				<tr>
					<!-- BEGIN layouts -->
					<td class="row1" style="text-align:center">{layouts.LAYOUT_RADIO}</td>
					<!-- END layouts -->
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="row1">{L_LAYOUT_NAME}</td>
			<td class="row2"><input type="text" maxlength="100" size="30" name="name" value="{NAME}" class="post" /></td>
		</tr>
		<tr>
			<td class="row1">{L_CMS_FILENAME}<br /><span class="gensmall">{L_CMS_FILENAME_EXPLAIN}</span><br /><span class="gensmall">{L_CMS_FILENAME_AUTH}</span></td>
			<td class="row2"><input type="text" maxlength="100" size="30" name="filename" value="{FILENAME}" class="post" /></td>
		</tr>
		<!-- IF not S_CMS_STANDARD -->
		<tr>
			<td class="row1">{L_TEMPLATE}</td>
			<td class="row2"><select name="template" class="post">{TEMPLATE}</select></td>
		</tr>
		<!-- ENDIF -->
		<tr>
			<td class="row1">{L_LAYOUT_GLOBAL_BLOCKS}</td>
			<td class="row2">
				<input type="radio" name="global_blocks" value="1" {GLOBAL_BLOCKS} /> {L_YES}&nbsp;&nbsp;
				<input type="radio" name="global_blocks" value="0" {NOT_GLOBAL_BLOCKS} /> {L_NO}
			</td>
		</tr>
		<tr>
			<td class="row1">{L_LAYOUT_PAGE_NAV}</td>
			<td class="row2">
				<input type="radio" name="page_nav" value="1" {PAGE_NAV} /> {L_YES}&nbsp;&nbsp;
				<input type="radio" name="page_nav" value="0" {NOT_PAGE_NAV} /> {L_NO}
			</td>
		</tr>
		<tr>
			<td class="row1">{L_LAYOUT_VIEW}</td>
			<td class="row2"><select name="view" class="post">{VIEW}</select></td>
		</tr>
		<tr>
			<td class="row1">{L_PERMISSIONS}</td>
			<td class="row2"><select name="edit_auth" class="post">{EDIT_AUTH}</select></td>
		</tr>
		<tr>
			<td class="row1">{L_LAYOUT_GROUPS}</td>
			<td class="row2">{GROUPS}</td>
		</tr>
		</table>
	</td>
</tr>
<tr><td class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" />
	</td>
</tr>
</table>
</form>