<!-- BEGIN view_shoutbox -->
<script type="text/javascript" src="{T_COMMON_TPL_PATH}js/fat.js"></script>
<script type="text/javascript">
// <![CDATA[

//
// window event callback functions
// If not used just leave the return statement 
//

<!-- BEGIN onload -->
// window.onload event
function chatOnLoad(evt)
{
	return true;
}

// window.onbeforeunload event
function chatOnBeforeUnload(evt)
{
	return true;
}

// window.onunload event
function chatOnUnload(evt)
{
	return true;
}
<!-- END onload -->

// Shout row 'zebra' classes
AjaxContext.zebra = {
	odd: "row1",
	even: "row2"
};

//
// DOM insertion callbacks
//

// Write the HTML for a new chatroom user
function insertChatTabUser(user)
{
	var output = "<span" + user.style + ">" + user.username + "</span>";
	return output;
}

// Write the HTML for a new chatroom tab
function insertChatTab(roomId, room, title)
{
	var output = " <a href=\"#\" id=\"chat-tab-" + roomId + "\" style=\"white-space: nowrap;\" onclick=\"javascript:activateChatTab('" + room + "');\">" + title + "</a> ";
	return output;
}

// Write the HTML for a new chatroom container
function insertChatContainer(tableId)
{
	var output = "<table id=\"" + tableId + "\" class=\"shoutlist forumline\" width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" style=\"display: none;\"></table>";
	return output;
}

// Write the HTML as a string for a new shout
function insertNewShout(id, shout)
{
	var output = "<tr id=\"" + id + "\" class=\"" + shout.cssClass + "\">";
	output += "<td width=\"140\"><div style=\"text-align: center;\"><span class=\"gensmall\"><i>" +
		shout.date + "<\/i><\/span><\/div><\/td>";
	output += "<td><div class=\"post-text post-text-hide-flow post-text-chat\"><b>" +
		shout.shouter_name + "</b>: " + shout.msg + "<\/div><\/td>";

	<!-- BEGIN user_is_admin -->
	output += "<td width=\"100\"><div style=\"text-align: center;\">" +
		"<span class=\"gensmall\"><a href=\"#\" onclick=\"javascript:removeShout(" +
		shout.id + ");\">" + '{DELETE_IMG}' + "<\/a><\/span><\/div><\/td>";
	<!-- END user_is_admin -->

	output += "</tr>";
	return output;
}

// Write the HTML as a string for a new online user
function insertNewUser(id, user)
{
	var output = "<span id=\"" + id + "\"";
	output += " style=\"text-align: left; display: inline; margin-right: 5px;\">";
	if (user.link != "")
	{
		output += "<a href=\"#\" onclick=\"" + user.link + "\" class=\"gensmall\"" +
			" alt=\"" + START_PRIVATE_CHAT + "\" title=\"" + START_PRIVATE_CHAT + 
			"\"" + user.style + ">" + user.username + "<\/a><span class=\"comma\">,</span>";
	}
	else
	{
		output += "<span class=\"gensmall\"" + user.style + ">" + user.username + "<\/span><span class=\"comma\">,</span>";
	}
	output += "</span>";
	return output;
}

// Highlight the newly added shout
function highlightShout(id, isNew)
{
	if (isNew)
	{
		Fat.fade_element(id, 30, 2000, '#ff5500');
		setTimeout("classChanger('" + id + "');", 2000);
	}
	else
	{
		classChanger(id);
	}
}

// Highlight the newly added online user
function highlightUser(id)
{
	Fat.fade_element(id, 30, 2000, '#ff5500');
}

// Signal that something changed on the page
function chatDataChanged()
{
	$('#online_list > span > .comma').show();
	$('#online_list > span:last > .comma').hide();
	playsound();
}

function classChanger(id)
{
	var item = $('#' + id);
	item.find('td').prop('class', item.data('class'));
}

function playsound()
{
	$("#ac_notify").jPlayer("play");
}

// ]]>
</script>
<!-- END view_shoutbox -->
