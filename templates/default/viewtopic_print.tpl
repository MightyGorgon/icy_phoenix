<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<title>{SITENAME} :: {PAGE_TITLE}</title>
	<script type="text/javascript" src="{T_COMMON_TPL_PATH}js/ip_scripts.js"></script>
	<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}print_version.css" type="text/css" />
	<!-- IE conditional comments: http://msdn.microsoft.com/workshop/author/dhtml/overview/ccomment_ovw.asp -->
	<!--[if IE]>
	<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}common_ie.css" type="text/css" />
	<![endif]-->

	<!--[if lt IE 7]>
	<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/pngfix.js"></script>
	<![endif]-->
</head>
<body>
<!-- <div style="margin: 0 auto; display: block; width: 960px !important;"> -->
<div style="margin: 0 auto; display: block; width: 680px !important;">
<div class="forumline" style="width: 100%;">
<form name="printview_form" action="{S_ACTION}" method="post">
<div class="genmed" style="text-align: right; float: right;">
<b><a href="{U_TOPIC}" class="topic_link">{TOPIC_TITLE}</a></b>&nbsp;&raquo;&nbsp;
<b>{L_SHOW_POSTS_FROM}</b>&nbsp;<input type="text" name="start" value="{POSTS_START}" size="6" maxlength="6" class="post" style="width: 30px;" />&nbsp;&nbsp;
<b>{L_SHOW_POSTS_TO}</b>&nbsp;<input type="text" name="limit" value="{POSTS_LIMIT}" size="6" maxlength="6" class="post" style="width: 30px;" />&nbsp;&nbsp;
{S_HIDDEN_FIELDS}
<input type="submit" name="submit" value="{L_GO}" class="mainoption" />&nbsp;<br />
</div>
</form>
<h1>{SITENAME}</h1><br />
<h2>{FORUM_NAME} - {TOPIC_TITLE}</h2><br /><br />
<!-- BEGIN postrow -->
<div class="forumline" style="margin: 10px; padding: 5px; display: block;">
<b>{postrow.POSTER_NAME}</b>&nbsp;[&nbsp;{postrow.POST_DATE}&nbsp;]<br />
<b>{L_POST_SUBJECT}:&nbsp;</b>{postrow.POST_SUBJECT}
<hr width="95%" class="sep" />
<!-- <div class="post-text post-text-hide-flow">&nbsp;</div> -->
<div class="post-text">{postrow.MESSAGE}</div>
<br clear="all" />
<br clear="all" />
</div>
<!-- END postrow -->
<br />
<div align="center"><br />Powered by <a href="http://www.icyphoenix.com/" target="_blank">Icy Phoenix</a> based on <a href="http://www.phpbb.com/" target="_blank">phpBB</a></div>
</div>
</div>
{GOOGLE_ANALYTICS}
</body>
</html>