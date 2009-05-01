<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="190" valign="top">
	{IMG_THL}{IMG_THC}<span class="forumlink">{L_LINKS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1g row-center">
		<form name="select_all" action="">
			<br /><div class="gensmall"><div class="center-block"><img src="{U_SITE_LOGO}" alt="{SITENAME}" /></div><br />
			{L_LINK_US_EXPLAIN}</div><br />
			<textarea cols="15" rows="2" class="post" style="width: 160px" readonly="readonly" name="text_area" onclick="javascript:this.form.text_area.focus();this.form.text_area.select();">{LINK_US_SYNTAX}</textarea>
		</form>
		</td>
	</tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
	<!-- BEGIN lock -->
	<!-- BEGIN logout -->
	{IMG_THL}{IMG_THC}<span class="forumlink">{L_LOGIN}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1g-left" width="190">
		<form method="post" action="{S_LOGIN_ACTION}">
			{L_LINK_REGISTER_GUEST_RULE}<br /><br />
			<div class="center-block-text">
				{L_USERNAME}:<br /><input class="post" type="text" name="username" size="24" maxlength="40" value="" /><br />
				{L_PASSWORD}:<br /><input class="post" type="password" name="password" size="24" maxlength="32" /><br /><br />
				<span class="gensmall">&nbsp;<input type="checkbox" name="autologin" />&nbsp;{L_REMEMBER_ME}&nbsp;</span><br /><br />
				<input type="hidden" name="redirect" value="{U_SITE_LINKS}" /><input type="submit" name="login" class="mainoption" value="{L_LOGIN}" /><br /><br />
			</div>
		</form>
		</td>
	</tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
	<!-- END logout -->

	<!-- BEGIN submit -->
	<form name="linkdata" method="post" action="{U_LINK_REG}">
	{IMG_THL}{IMG_THC}<span class="forumlink">{L_LINK_REGISTER}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1g-left" style="padding: 2px;">
			{L_LINK_REGISTER_RULE}<br /><br />
			<b>{L_LINK_TITLE}</b><br /><input class="post" type="text" name="link_title" value="" size="15" maxlength="20" style="width: 160px" /><br /><br />
			<b>{L_LINK_URL}</b><br /><input class="post" type="text" name="link_url" value="http://" size="15" maxlength="100" style="width: 160px" /><br /><br />
			<b>{L_LINK_LOGO_SRC}</b><br /><input class="post" type="text" size="15" maxlength="120" style="width: 160px" name="link_logo_src" value="http://" /><br />[<a href="javascript:void(0);" onclick="var img_src=document.linkdata.link_logo_src.value;if(img_src=='http://' || img_src=='') img_src='images/links/no_logo88a.gif';_preview=window.open(img_src, '_preview', 'toolbar=no,width=200,height=100,top=300,left=300');">{L_PREVIEW}</a>]<br /><br />
			<b>{L_LINK_CATEGORY}</b><br />
			<select name="link_category" style="width:160px"><option value="" selected="selected">----------------</option>{LINK_CAT_OPTION}</select><br /><br />
			<b>{L_LINK_DESC}</b><br /><textarea name="link_desc" cols="15" rows="4" class="post" style="width: 160px"></textarea><br /><br />
		</td>
	</tr>
	<tr><td class="cat"><input type="submit" name="addlink" value="{L_SUBMIT}" class="mainoption" /></td></tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
	</form>
	<!-- END submit -->
	<!-- END lock -->
	</td>
	<td width="7" nowrap="nowrap">&nbsp;</td>
