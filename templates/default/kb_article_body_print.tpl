<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
<meta http-equiv="Content-Style-Type" content="text/css">
{META}
{NAV_LINKS}
<title>{SITENAME} :: {PAGE_TITLE}</title>
<link rel="stylesheet" href="{T_COMMON_TPL_PATH}print_version.css" type="text/css" >
</head>

<body>
<a name="top"></a>

<table class="forumline" width="100%" cellspacing="0">
<tr>
	<td class="row2">
		<span class="gensmall"><b>{L_ARTICLE_AUTHOR}</b></span>
		<span class="gensmall">{ARTICLE_AUTHOR}</span>
		<span class="gensmall"><b>{L_ARTICLE_DATE}</b></span>
		<span class="gensmall">{ARTICLE_DATE}</span>
		<span class="gensmall">{VIEWS}</span><br />
		<span class="gensmall"><b>{L_ARTICLE_KEYWORDS}</b></span>
		<span class="gensmall">{ARTICLE_KEYWORDS}</span><br />
		<span class="gensmall"><b>{L_ARTICLE_DESCRIPTION}</b></span>
		<span class="gensmall">{ARTICLE_DESCRIPTION}</span>

		<!-- BEGIN custom_field -->
		<span class="gensmall"><br /><b>{custom_field.CUSTOM_NAME}</b></span>
		<span class="gen">{custom_field.DATA} </span>
		<!-- END custom_field -->
		<br />
		<!-- BEGIN switch_comments -->
		<span class="gensmall"><br />{COMMENTS}</span>
		<!-- END switch_comments -->
		<!-- BEGIN switch_ratings -->
		<span class="gensmall"><br />{RATINGS}</span>
		<!-- END switch_ratings -->
	</td>
</tr>
<!-- BEGIN switch_toc -->
<tr>
	<td class="row1" align="left"><span class="maintitle">{L_TOC}</span><br /><br />
		<span class="nav">
		<!-- BEGIN pages -->
		{switch_toc.pages.TOC_ITEM}
		<!-- END pages -->
		</span>
	</td>
</tr>
<!-- END switch_toc -->
<tr><td class="maintitle" nowrap="nowrap"><br />{ARTICLE_TITLE}&nbsp;<br /><hr></td></tr>
<tr><td class="row1" wrap="wrap"><div class="post-text">{ARTICLE_TEXT}</div></td></tr>
<!-- BEGIN switch_pages -->
<tr>
	<td class="row1 row-center"><span class="nav">{L_GOTO_PAGE}
		<!-- BEGIN pages -->
		{switch_pages.pages.PAGE_LINK}
		<!-- END pages -->
		</span>
	</td>
</tr>
<!-- END switch_pages -->
</table>
<br />
<!-- BEGIN switch_comments_show -->
<table align="center" width="100%" cellpadding="4" cellspacing="0" border="0" class="forumline">
<tr><td class="cattitle">{L_COMMENTS}&nbsp;</td></tr>
<!-- END switch_comments_show -->
<!-- BEGIN postrow -->
<tr>
	<td class="row1" width="100%" height="28" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="100%">
			<span class="genmed"><b>{postrow.POSTER_NAME}</b></span>
			<span class="postdetails">&nbsp;{L_POSTED}:&nbsp;{postrow.POST_DATE}&nbsp;&nbsp;&nbsp;{L_POST_SUBJECT}&nbsp;{postrow.POST_SUBJECT}</span>
			<br />
			<hr />
			<br />
		</td>
	</tr>
	<tr><td><div class="post-text">{postrow.MESSAGE}</div></td></tr>
	</table>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2" height="1"><img src="{SPACER}" alt="" width="1" height="3" /></td></tr>
<!-- END postrow -->
<!-- BEGIN switch_comments_show -->
</table>
<!-- END switch_comments_show -->
</body>
</html>