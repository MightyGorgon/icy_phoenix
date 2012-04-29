<!-- BEGIN view_shoutbox -->
<script type="text/javascript">
// <![CDATA[

// Based in part on the XHTML live Chat (http://www.plasticshore.com)
// This script is published under a creative commons license
// license: http://creativecommons.org/licenses/by-nc-sa/2.0/

// Initialises the form and starts the polling object
function initChat(evt)
{
<!-- BEGIN shout_allowed -->
	// this non standard attribute prevents firefox autofill function clashing with this script
	$("#chatbarText").attr("autocomplete", "off");
	checkStatus(""); // sets the initial value and state of the input comment
<!-- END shout_allowed -->
<!-- BEGIN guest_shouter -->
	checkName(); // checks the initial value of the input name
<!-- END guest_shouter -->
	addChatTab("", ""); // add public tab
	if (AjaxContext.chatRoom != "") // add private tab
	{
		addChatTab(AjaxContext.chatRoom, AjaxContext.privateUsers);
	}
	activateChatTab(AjaxContext.chatRoom);
	receiveChatData(); // initiates the first data query
	return true;
}

<!-- BEGIN shout_allowed -->
// Disable the submit button if there is no message
function checkStatus(focusState)
{
	var text = $("#chatbarText");
	var submit = $("#submit");
	submit.attr("disabled", ((text.val().trim() != "") || (focusState == "active")) ? false : true);
}
<!-- END shout_allowed -->

<!-- BEGIN guest_shouter -->
// Autoassigns a random name to a new user
function checkName()
{
	var name = $("#name");
	if (name.val() == "")
	{
		name.val("guest_" + Math.floor(Math.random() * 10000));
	}
}
<!-- END guest_shouter -->

// Convert the room to an identifier
function roomToId(room)
{
	return (typeof room == "string" && room != "") ? room.replace("|", "-") : "public";
}

// Create new chatroom window
function addChatTab(room, users)
{
	// already exists?
	var roomId = roomToId(room);
	var tableId = "outputList-" + roomId;
	var table = $("#" + tableId);
	if (table.length)
	{
		return table;
	}

	// tab title
	var title = PUBLIC_CHATROOM;
	if (roomId != "public")
	{
		// find all users participating in conversation
		var list = room.split("|");
		var usernames = "";
		var comma = "";
		for (var i = 0; i < list.length; i++)
		{
			var id = parseInt(list[i]);
			usernames += comma + insertChatTabUser(users[id]);
			comma = ", ";
		}
		title = PRIVATE_CHATROOM + " (" + usernames + ")";
	}
	// add tab
	var html = insertChatTab(roomId, room, title);
	if (roomId == "public")
	{
		$("#shoutsTabs").prepend(html);
	}
	else
	{
		$("#shoutsTabs").append(html);
	}

	// add tab container table
	html = insertChatContainer(tableId);
	$("#shoutsContainer").prepend(html);
	return $("#" + tableId).data("room", room);
}

// Activate the chatroom window
function activateChatTab(room)
{
	// find tab
	var roomId = roomToId(room);
	var tab = $("#chat-tab-" + roomId);
	if (!tab.length || tab.hasClass("active")) {
		return;
	}

	// hide active tab and table
	var oldId = roomToId(AjaxContext.chatRoom);
	$("#chat-tab-" + oldId).removeClass("active");
	$("#outputList-" + oldId).hide();

	// show new tab and table
	if (tab.hasClass("new-shout"))
	{
		tab.removeClass("new-shout");
	}
	tab.addClass("active");
	$("#outputList-" + roomId).show();
	AjaxContext.chatRoom = room;
}

// Add and activate chat room  - used to open (or reopen) a private chat room
function addAndActivateChatTab(room)
{
	addChatTab(room, AjaxContext.privateUsers);
	activateChatTab(room);
}

// A new shout has been added to a chatroom window
function chatTabNewShout(room)
{
	var roomId = roomToId(room);
	var tab = $("#chat-tab-" + roomId);
	if (!tab.length || tab.hasClass("active") || tab.hasClass("new-shout"))
	{
		return;
	}
	tab.addClass("new-shout");
}

// ]]>
</script>
<!-- END view_shoutbox -->
