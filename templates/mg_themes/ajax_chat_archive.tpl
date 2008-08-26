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
<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="#" class="nav-current">{L_ARCHIVE}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;<a href="{U_AJAX_SHOUTBOX}">{L_AJAX_SHOUTBOX}</a>
	</div>
</div>

<table align="center" width="100%" cellspacing="0" cellpadding="0">
<tr valign="top">
	<td width="67%">
		<table class="forumline" align="center" width="100%" cellspacing="0" cellpadding="0">
			<tr><th colspan="2">{L_SHOUTS}</th></tr>
			<!-- BEGIN pag -->
			<tr><td class="row2 row-center" colspan="2"><span class="postbody">{pag.PAGINATION}</span></td></tr>
			<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
			<!-- END pag -->
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
					<td class="row1"><div class="post-text">{shouts.MESSAGE}</div></td>
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
				<td class="row1"><span class="forumlink">{L_TOTAL_SHOUTS}</span></td>
				<td class="row1"><span class="post-text">{TOTAL_SHOUTS}</span></td>
			</tr>
			<tr>
				<td class="row2"><span class="forumlink">{L_STORED_SHOUTS}</span></td>
				<td class="row2"><span class="post-text">{STORED_SHOUTS}</span></td>
			</tr>
			<tr>
				<td class="row1"><span class="forumlink">{L_MY_SHOUTS}</span></td>
				<td class="row1"><span class="post-text">{MY_SHOUTS}</span></td>
			</tr>
			<tr>
				<td class="row2"><span class="forumlink">{L_TODAY_SHOUTS}</span></td>
				<td class="row2"><span class="post-text">{TODAY_SHOUTS}</span></td>
			</tr>
		</table>
		<br /><br />
		<table class="forumline" align="center" width="100%" cellspacing="0" cellpadding="0">
			<tr><th>{L_WIO}</th></tr>
			<tr>
				<td class="row1"><span class="post-text" id="online_list">
					<!-- BEGIN online_list -->
					&nbsp;&#8226;&nbsp;{online_list.USERNAME}<br />
					<!-- END online_list -->
				</span></td>
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
				<td class="cat"><span class="cattitle">{L_USERNAME}</span></td>
				<td class="cat"><span class="cattitle">{L_SHOUTS}</span></td>
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