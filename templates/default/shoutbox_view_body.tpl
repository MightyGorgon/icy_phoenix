<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<head>
<title>{PAGE_TITLE}</title>
<meta http-equiv="refresh" content="120;url={U_SHOUTBOX_VIEW}?auto_refresh=1" />
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
<link rel="shortcut icon" href="{FULL_SITE_PATH}images/favicon.ico" />
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_TPL_PATH}style_{CSS_COLOR}.css" type="text/css" />
</head>
<body style="margin: 0px; padding: 0px;">
<table class="forumline">
<!-- IF S_SHOUTBOX_COMPACT -->
<!-- BEGIN shoutrow -->
<tr>
	<td class="{shoutrow.ROW_CLASS}" width="100%" align="left" valign="top">
		<div style="text-align: left; vertical-align: top;"><div style="position: relative; float: left; text-align: left; vertical-align: top;"><b>
			<!-- IF shoutrow.PROFILE -->
				<a href="{shoutrow.PROFILE}" target="_top" style="text-decoration: none;">{shoutrow.USERNAME}</a>
				<!-- ELSE -->
				{shoutrow.USERNAME}
				<!-- ENDIF -->:</b>&nbsp;&diams;&nbsp;<span class="gensmall">{shoutrow.TIME}&nbsp;&raquo;&nbsp;{shoutrow.SHOUT}</span></div></div>
	</td>
</tr>
<!-- END shoutrow -->
<!-- ELSEIF S_SHOUTBOX_FULL -->
<!-- BEGIN shoutrow -->
<tr>
	<td class="{shoutrow.ROW_CLASS}" width="100%" align="left" valign="top">
		<div style="text-align: right; vertical-align: top;">
			<div style="position: relative; float: left; text-align: left; vertical-align: top;"><b>
				<!-- IF shoutrow.PROFILE -->
				<a href="{shoutrow.PROFILE}" target="_top" style="text-decoration: none;">{shoutrow.USERNAME}</a>
				<!-- ELSE -->
				{shoutrow.USERNAME}
				<!-- ENDIF -->:</b></div>
			<span class="gensmall">{shoutrow.TIME}</span>
		</div>
		<br />
		<div class="post-text post-text-hide-flow">{shoutrow.SHOUT}</div>
	</td>
</tr>
<!-- END shoutrow -->
<!-- ELSE -->
<!-- BEGIN shoutrow -->
<tr class="{shoutrow.ROW_CLASS}h">
	<td class="{shoutrow.ROW_CLASS}h" style="background: none; min-width: 150px;" nowrap="nowrap"><span class="gensmall">
		<!-- IF shoutrow.PROFILE -->
		<a href="{shoutrow.PROFILE}" target="_top" style="text-decoration: none;">{shoutrow.USERNAME}</a>
		<!-- ELSE -->
		{shoutrow.USERNAME}
		<!-- ENDIF -->&nbsp;&bull;&nbsp;[&nbsp;{shoutrow.TIME}&nbsp;]</span></td>
	<td class="{shoutrow.ROW_CLASS}h" width="100%" style="background: none;"><div class="post-text post-text-hide-flow">{shoutrow.SHOUT}</div></td>
</tr>
<!-- END shoutrow -->
<!-- ENDIF -->
</table>
</body>