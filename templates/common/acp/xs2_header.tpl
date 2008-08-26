<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<!-- IE conditional comments: http://msdn.microsoft.com/workshop/author/dhtml/overview/ccomment_ovw.asp -->
<!--[if IE]>
<style type="text/css">
/* IE hack to emulate the :hover & :focus pseudo-classes - Add the selectors below that required the extra attributes */
.row1h, .row1h-new { behavior: url("{FULL_SITE_PATH}{T_COMMON_TPL_PATH}pseudo-hover.htc"); }
</style>
<![endif]-->
<title>Icy Phoenix Administration</title>
<meta http-equiv="Content-Type" content="text/html;">
<link rel="stylesheet" href="../templates/common/acp.css" type="text/css" />
</head>
<body id="header" onload="PreloadFlag = true;">

	<div class="nav-links row-center" style="font-family: Arial, Helvetica, sans-serif; border-left-color: #DDDDDD;">
		<div class="nav-links-left">{L_VERSION_INFORMATION}: {VERSION_INFO}</div>
		<b>{L_HEADER_WELCOME}</b>
	</div>
	<div class="nav-links row-right" style="font-family: Arial, Helvetica, sans-serif; border-left-color: #DDDDDD;">
		<b>
		<a href="{U_ADMIN_INDEX}" target="main">{L_ADMIN_INDEX}</a> |
		<a href="{U_CMS}" target="_parent">{L_CMS}</a> |
		<a href="{U_PORTAL}" target="_parent">{L_PORTAL}</a> |
		<a href="{U_FORUM_INDEX}" target="_parent">{L_FORUM_INDEX}</a> |
		<a href="{U_FORUM_INDEX}" target="main">{L_PREVIEW_FORUM}</a> |
		{U_IP_MAIN}
		<!-- <a href="{U_PORTAL}" target="main">{L_PREVIEW_PORTAL}</a> | -->
		<!-- | {U_IP_DOWNLOAD} | {U_IP_CODE_CHANGES} | {U_IP_UPGRADE} -->
		</b>
	</div>
</body>
</html>
