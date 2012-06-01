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

<!-- BEGIN linkrow -->
<div class="forum" onclick="window.location.href='{linkrow.LINK_URL}'; return false;">
	<p><a href="{linkrow.LINK_URL}" class="forumlink">{linkrow.LINK_TITLE}</a></p>
	<p><span class="extra">{linkrow.LINK_NUMBER}</span></p>
</div>
<!-- END linkrow -->

<!-- INCLUDE overall_footer.tpl -->