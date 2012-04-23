<!-- BEGIN view_shoutbox -->
<script type="text/javascript">//<![CDATA[

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
	addChatTab(AjaxContext.chatRoom); // add default tab
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
		name.val('guest_' + Math.floor(Math.random() * 10000));
	}
}
<!-- END guest_shouter -->

// Create new chat window
function addChatTab(room)
{
	var roomId = (typeof room == 'string' && room != '') ? room.replace(/\|/g, '-') : 'all';
	var tableId = 'outputList-' + roomId;
	var title = 'all';
	// tab title
	if (roomId != 'all')
	{
		// find all users participating in conversation
		var list = room.split('|');
		var users = '';
		for (var i = 0; i < list.length; i++)
		{
			if (list[i] != AjaxContext.chatUser)
			{
				users += (users.length ? ', ' : '') + list[i];
			}
		}
		title = users;
	}
	// add tab
	var html = insertChatTab(roomId, room, title);
	if (roomId == 'all')
	{
		$('#shoutsTabs').prepend(html);
	}
	else
	{
		$('#shoutsTabs').append(html);
	}
	// add tab container table
	html = insertChatContainer(tableId);
	$('#shoutsContainer').prepend(html);
	return $('#' + tableId).data('room', room);
}

function activateChatTab(room)
{
	var roomId = (typeof room == 'string' && room != '') ? room.replace(/\|/g, '-') : 'all';
	var tableId = 'outputList-' + roomId;
	// find tab
	var tab = $('#chat-tab-' + roomId);
	if (!tab.length || tab.hasClass('active')) return;
	// hide active tab and table
	var oldId = (AjaxContext.chatRoom == '') ? 'all' : AjaxContext.chatRoom.replace(/\|/g, '-');
	$('#chat-tab-' + oldId).removeClass('active');
	$('#outputList-' + oldId).hide();
	// show new tab and table
	tab.addClass('active');
	$('#outputList-' + roomId).show();
	AjaxContext.chatRoom = (room === 'all') ? '' : room;
}
//]]>
</script>
<!-- END view_shoutbox -->
