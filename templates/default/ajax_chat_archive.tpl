<!-- INCLUDE overall_header.tpl -->

<!-- INCLUDE ajax_shoutbox_functions_js.tpl -->
<!-- INCLUDE ajax_shoutbox_html_js.tpl -->

<!-- BEGIN view_shoutbox -->
<script type="text/javascript">//<![CDATA[

// Based in part on the XHTML live Chat (http://www.plasticshore.com)
// This script is published under a creative commons license
// license: http://creativecommons.org/licenses/by-nc-sa/2.0/

// Refresh interval
REFRESH_TIME *= 2; // slower here, speed is not essential

// Initialises the polling object
function initChat()
{
	sound_flag = false; // do things quietly
	receiveChatData(); // initiates the first data query
	return true;
}
initChat();
//]]>
</script>
<!-- END view_shoutbox -->

<table align="center" width="100%" cellspacing="0" cellpadding="0">
<tr valign="top">
	<td width="67%">
		<!-- IF PAGINATION != '&nbsp;' -->
		<div style="float: right; text-align: right;"><span class="pagination">{PAGINATION}</span></div>&nbsp;
		<!-- ENDIF -->
		<table class="forumline" align="center" width="100%" cellspacing="0" cellpadding="0">
			<tr><th colspan="2">{L_SHOUTS}</th></tr>
			<!-- BEGIN shouts -->
			<tbody id="{L_SHOUT_PREFIX}{shouts.ID}">
				<tr>
					<td class="row2" colspan="2" nowrap="nowrap" valign="middle">
						<!-- IF shouts.DELETE_IMG --><span style="float: right;">{shouts.DELETE_IMG}</span><!-- ENDIF -->
						<span class="post-text">{L_AUTHOR}: <b>{shouts.SHOUTER}</b></span>
					</td>
				</tr>
				<tr>
					<td class="row1" width="10%" nowrap="nowrap"><span class="gensmall"><i>{shouts.DATE}</i></span></td>
					<td class="row1"><div class="post-text post-text-hide-flow">{shouts.MESSAGE}</div></td>
				</tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
			</tbody>
			<!-- END shouts -->
		</table>
	</td>
	<td width="3%">&nbsp;</td>
	<td width="30%">
		<table class="forumline" align="center" width="100%" cellspacing="0" cellpadding="0">
			<tr><th colspan="2">{L_STATS}</th></tr>
			<tr>
				<td class="row1"><span class="topiclink"><b>{L_TOTAL_SHOUTS}</b></span></td>
				<td class="row1"><span class="topiclink">{TOTAL_SHOUTS}</span></td>
			</tr>
			<tr>
				<td class="row2"><span class="topiclink"><b>{L_STORED_SHOUTS}</b></span></td>
				<td class="row2"><span class="topiclink">{STORED_SHOUTS}</span></td>
			</tr>
			<tr>
				<td class="row1"><span class="topiclink"><b>{L_MY_SHOUTS}</b></span></td>
				<td class="row1"><span class="topiclink">{MY_SHOUTS}</span></td>
			</tr>
			<tr>
				<td class="row2"><span class="topiclink"><b>{L_TODAY_SHOUTS}</b></span></td>
				<td class="row2"><span class="topiclink">{TODAY_SHOUTS}</span></td>
			</tr>
		</table>
		<br /><br />
		<table class="forumline" align="center" width="100%" cellspacing="0" cellpadding="0">
			<tr><th><img src="{T_COMMON_TPL_PATH}images/act_indicator.gif" id="indicator" alt="" style="visibility: hidden;" />&nbsp;{L_WIO}</th></tr>
			<tr>
				<td class="row1 row-center" id="online_list">
					<!-- BEGIN online_list -->
					<!--
					<span id="{L_USER_PREFIX}{online_list.USER_ID}" style="text-align: left; margin-right: 5px;"><a href="{online_list.LINK}" class="gensmall" {online_list.LINK_STYLE}>{online_list.USERNAME}</a></span>
					-->
					<!-- END online_list -->
				</td>
			</tr>
			<tr>
				<td class="row2 row-center">
					<span class="gensmall">
						{L_TOTAL}:<b><span id="total_c">{TOTAL_COUNTER}</span></b> {L_USERS}:<b><span id="users_c">{REGISTERED_COUNTER}</span></b> {L_GUESTS}:<b><span id="guests_c">{GUEST_COUNTER}</span></b><br><!--{L_SHOUTBOX_ONLINE_EXPLAIN}-->
					</span>
				</td>
			</tr>
		</table>
		<br /> <br />
		<table class="forumline" align="center" width="100%" cellspacing="0" cellpadding="0">
			<tr><th colspan="2">{L_TOP_SHOUTERS}</th></tr>
			<tr>
				<th>{L_USERNAME}</td>
				<th>{L_SHOUTS}</td>
			</tr>
		<!-- BEGIN top_shouters -->
			<tr>
				<td class="row1"><a href="{top_shouters.USER_LINK}" class="forumlink">{top_shouters.USERNAME}</a></td>
				<td class="row1"><span class="post-text">{top_shouters.USER_SHOUTS}</span></td>
			</tr>
		<!-- END top_shouters -->
		</table>
	</td>
</tr>
</table>

<!-- INCLUDE overall_footer.tpl -->
