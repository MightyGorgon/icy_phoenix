<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<head>
<title>{PAGE_TITLE}</title>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
<link rel="stylesheet" href="{T_URL}/style_{TPL_COLOR}.css" type="text/css">
</head>

<body style="margin:0px;padding:0px;">
<script src="{T_COMMON_TPL_PATH}js/bbcode.js" type="text/javascript" ></script>
<form method="post" name="post" action="{U_SHOUTBOX}" onsubmit="return checkForm(this)">
{ERROR_BOX}
<table class="row1" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="nav-header" width="100%">
	<div class="center-block-text">
		<span class="gensmall">
			<!-- BEGIN switch_auth_post -->
			<!-- BEGIN switch_bbcode -->
			<input type="button" class="button" accesskey="b" name="addbbcode0" value=" B " style="font-weight:bold; width: 30px" onclick="bbstyle(0)" />
			<input type="button" class="button" accesskey="i" name="addbbcode2" value=" i " style="font-style:italic; width: 30px" onclick="bbstyle(2)" />
			<input type="button" class="button" accesskey="u" name="addbbcode4" value=" u " style="text-decoration: underline; width: 30px" onclick="bbstyle(4)" />
			<input type="button" class="button" accesskey="q" name="addbbcode6" value="Quote" style="width: 50px" onclick="bbstyle(6)" />&nbsp;&nbsp;
			<!-- <span class="nav"><a href="{U_MORE_SMILIES}" onclick="window.open('{U_MORE_SMILIES}', '_phpbbsmilies', 'width=250,height=300,resizable=yes,scrollbars=yes');return false;" class="nav">{L_SMILIES}</a></span> -->
			<!-- END switch_bbcode -->
			{L_SHOUT_TEXT}:&nbsp;
			<input type="text" class="button" name="message" value="{MESSAGE}" size="25" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" />
			&nbsp;
			<input type="submit" class="mainoption" value="{L_SHOUT_SUBMIT}" name="shout" />
			<!-- END switch_auth_post -->
			<!-- BEGIN switch_auth_no_post -->
			{L_SHOUTBOX_LOGIN}&nbsp;
			<!-- END switch_auth_no_post -->
			<input type="submit" class="liteoption" value="{L_SHOUT_REFRESH}" name="refresh" />
		</span>
	</div>
	</td>
</tr>
</table>
<iframe src="{U_SHOUTBOX_VIEW}" align="left" width="100%" height="162" frameborder="0" marginheight="0" marginwidth="0"></iframe>
</form>
</body>