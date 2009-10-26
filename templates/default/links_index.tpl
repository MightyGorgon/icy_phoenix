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
		{IMG_THL}{IMG_THC}<span class="forumlink">{L_SITE_LINKS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<th colspan="2" width="75%">{L_LINK_CATEGORY}</th>
			<th>{L_SITE_LINKS}</th>
		</tr>
		<!-- BEGIN linkrow1 -->
		<tr>
			<td class="row1 row-center" width="30" style="padding-right:5px;" nowrap="nowrap"><img src="{FOLDER_IMG}" alt="{linkrow1.LINK_TITLE}" title="{linkrow1.LINK_TITLE}" /></td>
			<td class="row1h{catrow.forumrow.XS_NEW} row-forum" width="100%" onclick="window.location.href='{linkrow1.LINK_URL}'"><span class="forumlink"><a href="{linkrow1.LINK_URL}" class="forumlink">{linkrow1.LINK_TITLE}</a></span></td>
			<td class="row2 row-center-small"><span class="genmed">{linkrow1.LINK_NUMBER}</span></td>
		</tr>
		<!-- END linkrow1 -->
		<!-- BEGIN linkrow0 -->
		<tr>
			<td class="row1 row-center" width="30" style="padding-right:5px;" nowrap="nowrap"><img src="{FOLDER_IMG}" alt="{linkrow0.LINK_TITLE}" title="{linkrow0.LINK_TITLE}" /></td>
			<td class="row1h{catrow.forumrow.XS_NEW} row-forum" width="100%" onclick="window.location.href='{linkrow0.LINK_URL}'"><span class="forumlink"><a href="{linkrow0.LINK_URL}" class="forumlink">{linkrow0.LINK_TITLE}</a></span></td>
			<td class="row2 row-center"><span class="genmed">{linkrow0.LINK_NUMBER}</span></td>
		</tr>
		<!-- END linkrow0 -->
		</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		<div align="center" style="font-family: Verdana; font-size: 10px; letter-spacing: -1px">
			<br />Links MOD v1.2.2 by <a href="http://www.phpbb2.de" target="_blank">phpBB2.de</a> and OOHOO and <a href="http://mail.dhjh.tcc.edu.tw/~gzqbyr/" target="_blank">CRLin</a>.
		</div>
	</td>
</tr>
</table>

<!-- INCLUDE overall_footer.tpl -->