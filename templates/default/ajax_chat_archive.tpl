<!-- INCLUDE overall_header.tpl -->

<script type="text/javascript">
<!--
// Delete shout from DB
function deleteShout(shout_id)
{
	// Ask for confirmation for the deletion of the shout
	if(confirm("{L_CONFIRM}"))
	{
		httpDeleteChat = getHTTPObject();
		param = 'act=del&sh=' + shout_id;
		httpDeleteChat.open("POST", '{U_ACTION}', true);
		httpDeleteChat.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		httpDeleteChat.onreadystatechange = handlehResponse;
		httpDeleteChat.send(param);
		var x = document.getElementById('tbody' + shout_id);
		x.parentNode.removeChild(x);
	}
}

function getHTTPObject()
{
	var xmlhttp;
	/*@cc_on
	@if (@_jscript_version >= 5)
		try
		{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			try
			{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (E)
			{
				xmlhttp = false;
			}
		}
	@else
	xmlhttp = false;
	@end @*/
	if (!xmlhttp && (typeof XMLHttpRequest != 'undefined') )
	{
		try
		{
			xmlhttp = new XMLHttpRequest();
		}
		catch (e)
		{
			xmlhttp = false;
		}
	}
	return xmlhttp;
}

function handlehResponse()
{
	if (httpDeleteChat.readyState == 4)
	{
		if (httpDeleteChat.status == 200)
		{
			var xmldoc;
			var arr = new Array();
			if (xmldoc = httpDeleteChat.responseXML)
			{
				var root, field;
				root = xmldoc.getElementsByTagName('response').item(0);
				for (var iNode = 0; iNode < root.childNodes.length; iNode++)
				{
					var node = root.childNodes.item(iNode);
					if(node.tagName != undefined)
					{
						for (i = 0; i < node.childNodes.length; i++)
						{
							var sibl = node.childNodes.item(i);
							for (x = 0; x < sibl.childNodes.length; x++)
							{
								if (sibl.childNodes.length > 0)
								{
									sibl2 = sibl.childNodes.item(0);
									field = sibl.tagName;
									if (field != undefined)
									{
										arr[field] = sibl2.data;
										if ( (arr['error_status'] == 1) && (field == 'error_msg') )
										{
											alert(arr['error_msg']);
											return false;
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
//-->
</script>

<table align="center" width="100%" cellspacing="0" cellpadding="0">
<tr valign="top">
	<td width="67%">
		<!-- BEGIN pag -->
		<div style="float: right; text-align: right;"><span class="pagination">{pag.PAGINATION}</span></div>&nbsp;
		<!-- END pag -->
		<table class="forumline" align="center" width="100%" cellspacing="0" cellpadding="0">
			<tr><th colspan="2">{L_SHOUTS}</th></tr>
			<!-- BEGIN shouts -->
			<tbody id="tbody{shouts.ID}">
				<tr>
					<td class="row2" colspan="2" nowrap="nowrap" valign="middle">
						<span style="float: right;">{shouts.DELETE_IMG} {shouts.IP_IMG}</span>
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
			<tr><th>{L_WIO}</th></tr>
			<tr>
				<td class="row1"><span class="post-text" id="online_list"><!-- BEGIN online_list -->&nbsp;&#8226;&nbsp;{online_list.USERNAME}<br /><!-- END online_list --></span></td>
			</tr>
			<tr>
				<td class="row2 row-center">
					<span class="gensmall">
						{L_TOTAL}:<b><span id="total_c">{TOTAL_COUNTER}</span></b> {L_USERS}:<b><span id="users_c">{REGISTERED_COUNTER}</span></b> {L_GUESTS}:<b><span id="guests_c">{GUEST_COUNTER}</span></b><br>{L_SHOUTBOX_ONLINE_EXPLAIN}
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