<!-- INCLUDE overall_header.tpl -->

<script type="text/javascript">
<!--
function checkForm()
{
	formErrors = false;

	if (document.linkdata.link_title.value == '')
	{
		formErrors = '{L_LINK_TITLE}';
	}
	else if (document.linkdata.link_url.value == 'http://')
	{
		formErrors = '{L_LINK_URL}';
	}
	else if (document.linkdata.link_logo_src.value == 'http://' )
	{
		formErrors = '{L_LINK_LOGO_SRC}';
	}
	else if (document.linkdata.link_category.value == '' )
	{
		formErrors = '{L_LINK_CATEGORY}';
	}
	else if (document.linkdata.link_desc.value == '' )
	{
		formErrors = '{L_LINK_DESC}';
	}

	if (formErrors)
	{
		alert('{L_PLEASE_ENTER_YOUR}' + formErrors);
		return false;
	}

	return true;
}
//-->
</script>

<!-- INCLUDE links_leftblock.tpl -->
	<td width="100%" nowrap="nowrap" valign="top">
		{IMG_THL}{IMG_THC}<span class="forumlink">{L_LINK_TITLE1}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<th>{L_LOGO}</th>
			<th>{L_SITE}</th>
			<th>{L_LINK_HITS}</th>
			<th>{L_LINK_JOINED}</th>
			<th>{L_CATEGORY}</th>
		</tr>
				<!-- BEGIN linkrow -->
		<tr>
			<td valign="top" align="right" class="row1">&nbsp;{linkrow.LINK_LOGO}&nbsp;</td>
			<td class="row1h{catrow.forumrow.XS_NEW} row-forum" width="100%" data-href="{linkrow.LINK_URL}">
				<span class="forumlink"><a href="{linkrow.LINK_URL}" class="forumlink">{linkrow.LINK_TITLE}</a></span>
				<br /><span class="genmed">{linkrow.LINK_DESC}</span>
			</td>
			<td class="row1 row-center"><span class="genmed">{linkrow.LINK_HITS}</span></td>
			<td class="row1 row-center"><span class="genmed">{linkrow.LINK_JOINED}</span></td>
			<td class="row1 row-center"><span class="genmed">{linkrow.LINK_CATEGORY}</span></td>
		</tr>
		<!-- END linkrow -->
		</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		<br />
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td align="left" valign="top"><span class="gensmall">{PAGE_NUMBER}</span></td>
			<td align="right"><span class="gensmall">{S_TIMEZONE}</span><br /><span class="pagination">{PAGINATION}</span></td>
		</tr>
		</table>
	</td>
</tr>
</table>

<!-- INCLUDE overall_footer.tpl -->