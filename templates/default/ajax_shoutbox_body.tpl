<!-- INCLUDE ajax_shoutbox_js.tpl -->
<script src="{BBCB_MG_PATH_PREFIX}{T_COMMON_TPL_PATH}js/color_bar_ajax_chat.js" type="text/javascript"></script>
<script src="{BBCB_MG_PATH_PREFIX}{T_COMMON_TPL_PATH}js/bbcb_mg_ajax_chat.js" type="text/javascript"></script>
<!-- BEGIN view_shoutbox -->
<style type="text/css">
.box
{
	width: {view_shoutbox.BOX_WIDTH};
	height: {view_shoutbox.BOX_HEIGHT};
	text-align: center;
	margin: 0px auto;
}
.shouts
{
	width: {view_shoutbox.DIV_WIDTH};
	height: {view_shoutbox.DIV_HEIGHT};
	overflow: auto;
}
#outputList
{
	width: {view_shoutbox.TABLE_WIDTH};
	height: {view_shoutbox.TABLE_HEIGHT};
}
</style>

<div align="center">
	<div class="box" align="center">
		<div id="ajax_chat_h" style="display:none;">
		{IMG_THL}{IMG_THC}<img style="{SHOW_HIDE_PADDING}float:right;cursor:pointer;" src="{IMG_MAXIMISE}" onclick="javascript:ShowHide('ajax_chat','ajax_chat_h','ajax_chat');" alt="{L_SHOW}" />&nbsp;<img src="{T_COMMON_TPL_PATH}images/act_indicator.gif" id="act_indicator" alt="" />&nbsp;<span class="forumlink">{L_AJAX_SHOUTBOX}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
		<tr><td>&nbsp;</td></tr>
		</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		</div>
		<div id="ajax_chat">
		{IMG_THL}{IMG_THC}<img style="{SHOW_HIDE_PADDING}float:right;cursor:pointer;" src="{IMG_MINIMISE}" onclick="javascript:ShowHide('ajax_chat','ajax_chat_h','ajax_chat');" alt="{L_HIDE}" />&nbsp;<img src="{T_COMMON_TPL_PATH}images/act_indicator.gif" id="act_indicator" alt="" />&nbsp;<span class="forumlink">{L_AJAX_SHOUTBOX}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
		<script type="text/javascript">
		<!--
		tmp = 'ajax_chat';
		if(GetCookie(tmp) == '2')
		{
			ShowHide('ajax_chat','ajax_chat_h','ajax_chat');
		}
		//-->
		</script>
		<!-- BEGIN shout_allowed -->
		<tr>
			<td class="row1 row-center" valign="bottom">
				<form id="chatForm" name="chatForm" onsubmit="sendComment(); return false;" action="">
					{BBCB_MG_SMALL}
					<!-- BEGIN guest_shouter -->
					<span class="topiclink">{L_USERNAME}:&nbsp;</span><input type="text" size="12" maxlength="30" name="name" id="name" class="post" onblur="checkName();" />&nbsp;&nbsp;
					<!-- END guest_shouter -->
					<span class="topiclink">{L_MESSAGE}:&nbsp;</span><input type="text" size="52" maxlength="1000" name="chatbarText" id="chatbarText" class="post" onblur="checkStatus('');" onfocus="checkStatus('active');" />
					<input type="submit" class="button" id="submit" name="submit" value="{L_SUMBIT}" />
				</form>
			</td>
		</tr>
		<!-- END shout_allowed -->
		<tr>
			<td class="row2">
			<b>{L_WIO}:</b>&nbsp;<span class="gensmall">{L_TOTAL}:&nbsp;<b><span id="total_c">0</span></b>&nbsp;-&nbsp;{L_USERS}:&nbsp;<b><span id="user_c">0</span></b>&nbsp;-&nbsp;{L_GUESTS}:&nbsp;<b><span id="guests_c">0</span></b></span><br />
			<span class="post-text" id="online_list"></span>
			</td>
		</tr>
		<tr>
			<td class="row1 row-center">
				<div class="shouts">
					<table class="forumline" id="outputList" width="100%" align="center" cellspacing="0" cellpadding="0">

					</table>
					<br style="clear: both;" />
				</div>
			</td>
		</tr>
		<!-- BEGIN shout_allowed -->
		<!-- END shout_allowed -->
		</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		</div>
	</div>
</div>
<!-- <div id="s1"></div> -->
<!-- END view_shoutbox -->