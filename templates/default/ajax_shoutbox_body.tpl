<!-- IF S_POPUP -->
<!-- INCLUDE simple_header.tpl -->
<!-- ENDIF -->

<!-- INCLUDE ajax_shoutbox_functions_js.tpl -->
<!-- INCLUDE ajax_shoutbox_js.tpl -->
<!-- INCLUDE ajax_shoutbox_html_js.tpl -->

<!-- BEGIN view_shoutbox -->
<style type="text/css">
.box
{
	width: {view_shoutbox.BOX_WIDTH};
	/* height: {view_shoutbox.BOX_HEIGHT}; */
	text-align: center;
	margin: 0px auto;
}
.shouts
{
	width: {view_shoutbox.DIV_WIDTH};
	height: {view_shoutbox.DIV_HEIGHT};
	text-align: center;
	overflow: auto;
	margin: 0px auto;
}
.shoutlist
{
	/* width: {view_shoutbox.TABLE_WIDTH}; */
	/* height: {view_shoutbox.TABLE_HEIGHT}; */
	text-align: left;
	margin: 0;
}
</style>

<span id="notify" style="height: 0px;">&nbsp;</span>
<div align="center">
	<div class="box" align="center">
		<div id="ajax_chat_h" style="display: none;">
		{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MAXIMISE}" onclick="ShowHide('ajax_chat','ajax_chat_h','ajax_chat');" alt="{L_SHOW}" />&nbsp;<span class="forumlink">{L_AJAX_SHOUTBOX}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
		<tr><td>&nbsp;</td></tr>
		</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		</div>
		<div id="ajax_chat">
		{IMG_THL}{IMG_THC}<img class="max-min-right" style="{SHOW_HIDE_PADDING}" src="{IMG_MINIMISE}" onclick="ShowHide('ajax_chat','ajax_chat_h','ajax_chat');" alt="{L_HIDE}" />&nbsp;<img src="{T_COMMON_TPL_PATH}images/act_indicator.gif" id="indicator" alt="" style="visibility: hidden;" />&nbsp;<span class="forumlink">{L_AJAX_SHOUTBOX}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
		<!-- BEGIN shout_allowed -->
		<tr>
			<td class="row1 row-center" valign="bottom">
				<form id="chatForm" name="chatForm" onsubmit="sendComment(); return false;" action="">
					{BBCB_MG_SMALL}
					<!-- BEGIN guest_shouter -->
					<span class="topiclink">{L_USERNAME}:&nbsp;</span><input type="text" size="12" maxlength="30" name="name" id="name" class="post" onblur="checkName();" />&nbsp;&nbsp;
					<!-- END guest_shouter -->
					<span class="topiclink">{L_MESSAGE}:&nbsp;</span><input type="text" size="52" maxlength="1000" name="chatbarText" id="chatbarText" class="post" onblur="checkStatus('');" onfocus="checkStatus('active');" />
					<input type="submit" class="mainoption" id="submit" name="submit" value="{L_SUMBIT}" />
				</form>
			</td>
		</tr>
		<!-- END shout_allowed -->
		<tr>
			<td class="row2">
			<b>{L_WIO}:</b>&nbsp;<span class="gensmall">{L_TOTAL}:&nbsp;<b><span id="total_c">0</span></b>&nbsp;-&nbsp;{L_USERS}:&nbsp;<b><span id="users_c">0</span></b>&nbsp;-&nbsp;{L_GUESTS}:&nbsp;<b><span id="guests_c">0</span></b></span><br />
			<span class="post-text" id="online_list"></span>
			</td>
		</tr>
		<tr>
			<td class="row1 row-center">
				<div class="tabs" id="shoutsTabs"></div>
				<div class="shouts" id="shoutsContainer"><br style="clear: both;" /></div>
			</td>
		</tr>
		<!-- BEGIN shout_allowed -->
		<!-- END shout_allowed -->
		</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		<script type="text/javascript">//<![CDATA[
		tmp = 'ajax_chat';
		if(GetCookie(tmp) == '2')
		{
			ShowHide('ajax_chat','ajax_chat_h','ajax_chat');
		}
		//]]>
		</script>
		</div>
	</div>
</div>
<!-- END view_shoutbox -->

<!-- IF S_POPUP -->
<!-- INCLUDE simple_footer.tpl -->
<!-- ENDIF -->
