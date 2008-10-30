<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<head>
<title>{PAGE_TITLE}</title>
<meta http-equiv="refresh" content="120;url={U_SHOUTBOX_VIEW}?auto_refresh=3" />
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
<link rel="stylesheet" href="{T_URL}/style_{TPL_COLOR}.css" type="text/css">
</head>
<body style="margin:0px;padding:0px;">
<table class="forumline" width="100%" cellspacing="0">
<!-- BEGIN shoutrow -->
<tr>
	<td class="{shoutrow.ROW_CLASS}" width="100%" align="left" valign="top">
		<!-- IF S_COMPACT -->
		<div style="text-align:left;vertical-align:top;"><div style="position:relative;float:left;text-align:left;vertical-align:top;"><b>{shoutrow.USERNAME}:</b>&nbsp;&diams;&nbsp;<span class="gensmall">{shoutrow.TIME}&nbsp;&raquo;&nbsp;{shoutrow.SHOUT}</span></div></div>
		<!-- ELSE -->
		<div style="text-align:right;vertical-align:top;">
			<div style="position:relative;float:left;text-align:left;vertical-align:top;"><b>{shoutrow.USERNAME}:</b></div>
			<span class="gensmall">{shoutrow.TIME}</span>
		</div>
		<br />
		<div class="post-text">{shoutrow.SHOUT}</div>
		<!-- ENDIF -->
	</td>
</tr>
<!-- END shoutrow -->
</table>
</body>