<!-- IF CMS_STD_TPL -->
<!-- INCLUDE ../common/cms/page_header_std.tpl -->
<!-- ELSE -->
<!-- INCLUDE ../common/cms/page_header.tpl -->
<!-- ENDIF -->

<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_adv_userslist.png" alt="{L_CMS_CS_USERS_LIST}" title="{L_CMS_CS_USERS_LIST}" /></td>
	<td class="row1" valign="top"><h1>{L_CMS_CS_USERS_LIST}</h1><span class="genmed">{L_CMS_CS_USERS_LIST_EXPLAIN}</span></td>
</tr>
</table>

<form method="post" action="{S_BLOCKS_ACTION}" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="150" align="center">Username{L_CMS_ACTIONS}</th>
	<th width="70" align="center">Contatti{L_CMS_ACTIONS}</th>
	<th align="center">CMS Creati{L_CMS_NAME}</th>
</tr>
<tr>

	<!-- BEGIN users_row -->
	<tr>
		<td class="{users_row.ROW_CLASS}">{users_row.CMS_USERNAME}</td>
		<td class="{users_row.ROW_CLASS} row-center">&nbsp;{users_row.CMS_PM_IMG}&nbsp;{users_row.CMS_EMAIL_IMG}&nbsp;</td>
		<td style="padding:0px;">
		<table width="100%" height="100%" cellspacing="0" cellpadding="0">
		<!-- BEGIN user_data -->
		<tr>
			<td class="{users_row.ROW_CLASS} row-center" width="100">
				<a href=""><img src="{users_row.user_data.IMG_TURNED}" alt="CMS attivo" title="CMS attivo"/></a>&nbsp;
				<a href="{users_row.user_data.U_CMS_FOLDER}" target="_blank"><img src="{IMG_LAYOUT_PREVIEW}" alt="{L_PREVIEW}" title="{L_PREVIEW}"/></a>&nbsp;
				<a href="{users_row.user_data.U_CMS_EDIT}"><img src="{IMG_BLOCK_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;
				<a href="{blocks.U_DELETE}"><img src="{IMG_BLOCK_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a>
			</td>
			<td class="{users_row.ROW_CLASS}">{users_row.user_data.CMS_USER_TITLE} - <i>{users_row.user_data.CMS_USER_DESC}</i></td>
			<td class="{users_row.ROW_CLASS} row-center" width="150">{users_row.user_data.CMS_USER_TEMPLATE}</td>
		</tr>
		<!-- END user_data -->
		</table>
	
		
		</td>
	</tr>
	<!-- END users_row -->
	
	<!-- BEGIN cms_no_users -->
	<tr><td class="row1 row-center" colspan="3"><br /><br /><b>{cms_no_users.L_NO_BV}</b><br /><br /><br /><br /></td></tr>
	<!-- END cms_no_users -->
</tr>
<tr><td class="spaceRow" colspan="3"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" align="center" colspan="3">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="save" class="mainoption" value="{L_SUBMIT}" />
		<!--
		&nbsp;&nbsp;
		<input type="submit" name="reset" class="liteoption" value="{L_RESET}" />
		-->
		<!-- <input type="reset" name="reset" class="liteoption" value="{L_RESET}" /> -->
	</td>
</tr>
</table>
</form>

<!-- IF CMS_STD_TPL -->
<!-- INCLUDE ../common/cms/page_footer_std.tpl -->
<!-- ELSE -->
<!-- INCLUDE ../common/cms/page_footer.tpl -->
<!-- ENDIF -->