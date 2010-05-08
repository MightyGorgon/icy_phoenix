<!-- INCLUDE simple_header.tpl -->

<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/bbcb_mg_small.js"></script>

<form action="{S_ACTION}" name="upload_form" method="post" enctype="multipart/form-data">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_UPLOAD_IMAGE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1 row-center">
			<br /><br />
			{L_BBCODE_DES}<br /><br />
			<b>{L_BBCODE}</b>:&nbsp;<input class="post" name="bbci" size="80" maxlength="200" value="{IMG_BBCODE}" type="text" readonly="readonly" onclick="this.form.bbci.focus(); this.form.bbci.select();" />
			<br /><br />
		</td>
	</tr>
	<tr>
		<td class="cat" align="center">
			<input type="button" class="mainoption" value="{L_INSERT_BBC}" onclick="bbcb_ui_vars_reassign_start(); emoticon_sc(this.form.bbci.value); bbcb_ui_vars_reassign_end();" />&nbsp;
			<input type="button" class="liteoption" value="{L_CLOSE_WINDOW}" onclick="window.close();" />
		</td>
	</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE simple_footer.tpl -->