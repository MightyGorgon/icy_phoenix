{DOCTYPE_HTML}
<head>
<!-- INCLUDE overall_inc_header.tpl -->
<!-- Nifty Start -->
<!-- <link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}nifty/nifty_corners.css" type="text/css" /> -->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}nifty/nifty_cube.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}nifty/nifty_layout.js"></script>
<!-- Nifty End -->
</head>
<body>
<span><a name="top"></a></span>
{TOP_HTML_BLOCK}
{PROFILE_VIEW}
<!-- {GREETING_POPUP} -->

<div class="rounded_line_ext">
<div class="rounded_line">
<table id="forumtable" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="forum-header" align="left" valign="top" height="100">
	<!-- IF GL_BLOCK -->
	<!-- BEGIN ghleft_blocks_row -->{ghleft_blocks_row.OUTPUT}<!-- END ghleft_blocks_row -->
	<!-- ELSE -->
	<a href="{FULL_SITE_PATH}{U_PORTAL}" title="{L_HOME}"><img src="{FULL_SITE_PATH}{SITELOGO}" alt="{L_HOME}" title="{L_HOME}"/></a>
	<!-- ENDIF -->
	</td>
	<td class="forum-header" align="center" valign="middle" style="padding-top: 2px;"><!-- IF S_HEADER_BANNER --><center><br />{HEADER_BANNER_CODE}</center><!-- ELSE -->&nbsp;<!-- ENDIF --></td>
	<td class="forum-header" align="right" valign="middle" >
	<!-- <div class="sitedes"><h1>{SITENAME}</h1><h2>{SITE_DESCRIPTION}</h2></div> -->
	<!-- IF GR_BLOCK -->
	<!-- BEGIN ghright_blocks_row -->{ghright_blocks_row.OUTPUT}<!-- END ghright_blocks_row -->
	<!-- ELSE -->
	<!-- IF S_LOGGED_IN -->&nbsp;<!-- ELSE -->&nbsp;<!-- ENDIF -->
	<!-- ENDIF -->
	</td>
</tr>

<tr>
	<td colspan="3">
		<div id="menu">
		<ul id="nav">
		<li id="home"><a href="{FULL_SITE_PATH}{U_PORTAL}">{L_HOME}</a></li>
		<li id="forum"><a href="{FULL_SITE_PATH}{U_INDEX}">{L_INDEX}</a></li>
		<!-- BEGIN switch_upi2db_on -->
		<li id="unread_posts">{L_DISPLAY_U}</li>
		<!-- END switch_upi2db_on -->
		<!-- IF S_LOGGED_IN -->
		<li id="new_posts"><a href="{FULL_SITE_PATH}{U_SEARCH_NEW}">{L_NEW2}</a></li>
		<li id="profile"><a href="{FULL_SITE_PATH}{U_PROFILE}">{L_PROFILE}</a></li>
		<!-- ENDIF -->
		<li id="search"><a href="{FULL_SITE_PATH}{U_SEARCH}">{L_SEARCH}</a></li>
		<li id="faq"><a href="{FULL_SITE_PATH}{U_FAQ}">{L_FAQ}</a></li>
		<!-- IF not S_LOGGED_IN -->
		<li id="register"><a href="{FULL_SITE_PATH}{U_REGISTER}">{L_REGISTER}</a></li>
		<!-- ENDIF -->
		<li id="login"><a href="{FULL_SITE_PATH}{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT2}</a></li>
		</ul>
		</div>
	</td>
</tr>
<!-- TOP LINKS -->
<tr><td colspan="3" width="100%"><table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td class="row5bg" width="100%">&nbsp;</td></tr></table></td></tr>

<!-- INCLUDE overall_inc_body.tpl -->
