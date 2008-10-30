<script language="JavaScript" type="text/javascript">
<!--
function checkAlbumForm()
{
	formErrors = false;
	if (document.upload.pic_title.value.length < 2)
	{
		formErrors = "{L_UPLOAD_NO_TITLE}";
	}
	if (document.upload.pic_file.value.length < 2)
	{
		formErrors = "{L_UPLOAD_NO_FILE}";
	}
	else
	{
		switch (document.upload.cat_id.value)
		{
			case '{S_ALBUM_ROOT_CATEGORY}':
			case '{S_ALBUM_JUMPBOX_SEPERATOR}':
			case '{S_ALBUM_JUMPBOX_USERS_GALLERY}':
			case '{S_ALBUM_JUMPBOX_PUBLIC_GALLERY}':
				formErrors = "{L_NO_VALID_CAT_SELECTED}";
			default:
				// do nothing
		}
	}

	if (formErrors)
	{
		alert(formErrors);
		return false;
	}
	else
	{
		return true;
	}
}
// -->
</script>

<!-- INCLUDE breadcrumbs.tpl -->

<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
<tr><th>{L_UPLOAD_PIC}</th></tr>
<tr><td class="row1 row-center"><span class="gen">{L_UPLOAD_PIC}: {CAT_TITLE}</td></tr>
<tr>
<td class="row1 row-center">
<span class="gen">
<applet code="JUpload.startup" archive="{ALBUM_MOD_PATH}jupload.jar" codebase="{APLET_PATH}" mayscript name="JUpload" alt="JUpload by www.jupload.biz" <!-- BEGIN jsize --> width="{jsize.WIDTH}" height="{jsize.HEIGHT}"<!-- END jsize -->>
<!-- BEGIN jparams -->{jparams.PARAM}<!-- END jparams -->
Your browser does not support applets. Or you have disabled applet in your options.
To use this applet, please install the newest version of Sun's java. You can get it from <a href="http://www.java.com/">java.com</a>
</applet>
</span>
</td>
</tr>

</table>

<br />

<div align="center" class="copyright">Jupload Album Mod coded by <a href="http://www.shawnmcbride.com/">Shawn McBride</a>, using <a href="http://www.jupload.biz/">Jupload</a></div>
<br clear="all" />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}