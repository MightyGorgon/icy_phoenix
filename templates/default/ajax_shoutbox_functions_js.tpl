<!-- BEGIN view_shoutbox -->
<script type="text/javascript">
// <![CDATA[

// Based in part on the XHTML live Chat (http://www.plasticshore.com)
// This script is published under a creative commons license
// license: http://creativecommons.org/licenses/by-nc-sa/2.0/

// Refresh interval
var REFRESH_TIME = {view_shoutbox.REFRESH_TIME};
var FLASH_TIME = (REFRESH_TIME * 3) / 4;

// Error messages
var ERROR_TIMEOUT = "{L_TIMEOUT}";
var ERROR_RESPONSE = "{L_UNABLE}";

// HTML id prefixes
var SHOUT_PREFIX = "{L_SHOUT_PREFIX}";
var USER_PREFIX = "{L_USER_PREFIX}";
var ROOM_PREFIX = "{L_ROOM_PREFIX}";

// Room titles
var PUBLIC_CHATROOM = "{L_PUBLIC_ROOM}";
var PRIVATE_CHATROOM = "{L_PRIVATE_ROOM}";
var START_PRIVATE_CHAT = "{L_START_PRIVATE_CHAT}";

<!-- BEGIN onload -->
var oldOnLoad = window.onload;
if (typeof oldOnLoad == 'function')
{
	window.onload = function(evt)
	{
		oldOnLoad(evt);
		chatOnLoad(evt);
		initChat(evt);
	};
}
else
{
	window.onload = function(evt)
	{
		chatOnLoad(evt);
		initChat(evt);
	};
}

// Both needed as Opera does not handle onbeforeload
var oldOnUnload = window.onunload;
if (typeof oldOnUnload == 'function')
{
	window.onunload = function(evt)
	{
		leaveChat(evt);
		chatOnUnload(evt);
		oldOnUnload(evt);
	};
}
else
{
	window.onunload = function(evt)
	{
		leaveChat(evt);
		chatOnUnload(evt);
	};
}

var oldOnBeforeUnload = window.onbeforeunload;
if (typeof oldOnBeforeUnload == 'function')
{
	window.onbeforeunload = function(evt)
	{
		leaveChat(evt);
		chatOnBeforeUnload(evt);
		oldOnBeforeUnload(evt);		
	};
}
else
{
	window.onbeforeunload = function(evt)
	{
		leaveChat(evt);
		chatOnBeforeUnload(evt);
	};
}
<!-- END onload -->

// Default jQuery AJAX settings
$.ajaxSetup({
	beforeSend: function(jqXHR, settings) { throbber(true); },
	cache: false,
	complete: function(jqXHR, status) { throbber(false); },
	timeout: 5000,
	type: "POST",
	url: "{view_shoutbox.U_ACTION}"
});

// The AJAX context
var AjaxContext = {

	// The data type to use
	dataType: "{view_shoutbox.RESPONSE_TYPE}",

	// initial value will be replaced by the latest known shout id
	lastId: -1,

	// initial value will be replaced by the latest user list signature
	lastSig: "",

	// shout row 'zebra' classes
	zebra: {
		odd: "shout-odd",
		even: "shout-even"
	},

	// List of current shouts (the array is associative)
	currentShouts: new Object(),
	shoutsParsed: false,

	// List of current users (the array is associative)
	currentUsers: new Object(),
	usersParsed: false,

	// List of users in the private chatrooms
	privateUsers: {PRIVATE_USERS},

	// Parse the XML document, executing code for each matched element
	// Returns true if successful, otherwise false.
	parseXMLDocument: function(doc, functionList, functions) {
		functionList = functionList.split(",");
		for (var index = 0; index < functionList.length; index++)
		{
			var name = functionList[index];
			if (functions.hasOwnProperty(name))
			{  
				var list = new Array();
				$(name, doc).each(function(index, element) {
					var values = new Object();
					// there is only one level of sub elements and they all have unique names
					$("*", element).each(function(index, child) {
						values[child.tagName] = $(child).text();
					});
					list.push(values);
				});
				if (list.length > 0)
				{
					// this is necessary to match the JSON formats 
					if (name == "status" || name == "onstats")
					{
						list = list[0];
					}
					if (!functions[name].call(this, list))
					{
						return false;
					}
				}
			}
		}
		return true;
	},

	// Parse the JSON document, executing code for each matched element
	// Returns true if successful, otherwise false.
	parseJSONDocument: function(doc, functionList, functions) {
		functionList = functionList.split(",");
		var response = doc.response;
		for (var index = 0; index < functionList.length; index++)
		{
			var name = functionList[index];
			if ((functions.hasOwnProperty(name)) && (typeof response[name] != "undefined"))
			{
				if (!functions[name].call(this, response[name]))
				{
					return false;
				}
			}
		}
		return true;
	},

	// Sets the common AJAX parameters
	setParameters: function(action) {
		this.data = {
			sid: "{S_SID}",
			act: action
		};
	},

	// Sets the common update AJAX parameters
	setUpdateParameters: function(action) {
		this.data = {
			sid: "{S_SID}",
			act: action,
			lastID: this.lastId,
			sig: this.lastSig,
			chat_room: ChatRoomContext.chatRoom,
			update_mode: "{view_shoutbox.UPDATE_MODE}"
		};
	},

	// Check and display the error status (if any)
	// Returns false if an error occurred, otherwise true
	checkErrorStatus: function(result) {
		if (result.error_status != 0)
		{
			alert(result.error_msg);
			return false;
		}
		return true;
	},

	// Standard error handler
	stdError: function(jqXHR, status, error) {
		// See http://ilikestuffblog.com/2009/11/30/how-to-distinguish-a-user-aborted-ajax-call-from-an-error/
		if (jqXHR.getAllResponseHeaders() || (status == "timeout"))
		{
			var message = (status == "timeout") ? ERROR_TIMEOUT : ERROR_RESPONSE;
			if (typeof error == "string" && (error != ""))
			{
				message += "(" + error + ")";
			}
			alert(status + ": " + message);
		}
		return true;
	},

	// Standard success processor
	stdSuccess: function(data, status, jqXHR) {
		var functions = { status: this.checkErrorStatus };
		var parseDocument = (this.dataType == "xml") ? this.parseXMLDocument : this.parseJSONDocument;
		if (parseDocument.call(this, data, "status", functions))
		{
			if (typeof this.doneFunction == 'function')
			{
				return this.doneFunction.call(this);
			}
		}
		return false;
	},

	// Read the romm user list
	// Returns false if an error occurred, otherwise true
	checkRoomUsers: function(users) {
		// add private chatroom users
		for (var index = 0; index < users.length; index++) 
		{
			var user = users[index];
			user.user_id = parseInt(user.user_id);
			this.privateUsers[user.user_id] = {
				id: user.user_id,
				username: user.username,
				style: user.user_style
			};
		}
		return true;
	},

	// Check and display the shouts (if any)
	// Returns false if an error occurred, otherwise true
	checkShouts: function(shouts)	{
		var newShouts = new Array();
		for (var index = 0; index < shouts.length; index++) 
		{
			var shout = shouts[index];
			shout.id = parseInt(shout.id);
			var id = SHOUT_PREFIX + shout.id;
			if (shout.id > this.lastId && !(id in this.currentShouts))
			{
				this.shoutsParsed = true;
				var link = shout.shouter_link;
				shout.shouter_name = (link != "-1") ? "<a href=\"" + link + "\" {S_TARGET}" + shout.shouter_color + ">" + shout.shouter + "<\/a>" : shout.shouter;
				var roomId = ChatRoomContext.roomToId(shout.room);
				var tableId = "outputList-" + roomId;
				var table = $("#" + tableId);
				if (!table.length)
				{
					// add new chat tab
					table = ChatRoomContext.addChatTab(shout.room, this.privateUsers);
				}
				ChatRoomContext.chatTabNewShout(shout.room);

				var cssClass = this.zebra.odd;
				var firstShout = $("#" + tableId + " tr:first");
				if (firstShout.length)
				{
					cssClass = (firstShout.hasClass(this.zebra.odd)) ? this.zebra.even : this.zebra.odd;
				}
				shout.cssClass = cssClass;
				var html = insertNewShout(id, shout)
				table.prepend(html);
				this.currentShouts[id] = shout;
				newShouts.push(shout);
			}
			}
		// highlight after all the shouts have been added
		for (var index = 0; index < newShouts.length; index++) 
		{
			var shout = newShouts[index];
			var id = SHOUT_PREFIX + shout.id;
			highlightShout(id, this.lastId != -1);
		}
		return true;
	},

	// Check and display the online users (if any)
	// Returns false if an error occurred, otherwise true
	checkOnlineUsers: function(users) {

		// clear the validity
		for (var id in this.currentUsers)
		{
			if (this.currentUsers.hasOwnProperty(id))
			{
				this.currentUsers[id].valid = false;
			}
		}
		// add or update current users
		for (var index = 0; index < users.length; index++) 
		{
			var user = users[index];
			user.user_id = parseInt(user.user_id);
			var id = user.username;
			if (typeof(this.currentUsers[id]) != "undefined")
			{
				this.currentUsers[id].valid = true;
			}
			else
			{
				var newUser = {
					id: user.user_id,
					username: user.username,
					link: user.chat_link,
					style: user.user_style,
					isNew: true,
					valid: true
				};
				this.currentUsers[id] = newUser;
				this.privateUsers[user.user_id] = newUser;
			}
		}
		this.usersParsed = true;
		return true;
	},

	// Check and display the online statistics (if any)
	// Returns false if an error occurred, otherwise true
	checkStatistics: function(stats) {
		$("#total_c").empty().prepend(stats.total);
		$("#users_c").empty().prepend(stats.reg);
		$("#guests_c").empty().prepend(stats.guests);
		this.lastSig = stats.sig;
		// if there are no users, delete the list
		if (stats.total == "0")
		{
			$("#online_list").html('');
		}
		return true;
	},

	// update the online users
	updateUsers: function() {
		var list = $("#online_list");
		list.html('');
		var deletedUsers = new Object();
		for (var id in this.currentUsers)
		{
			if (this.currentUsers.hasOwnProperty(id))
			{
				var user = this.currentUsers[id];
				if (user.valid)
				{
					list.append(insertNewUser(USER_PREFIX + user.id, user));
					if (user.isNew)
					{
						highlightUser(USER_PREFIX + user.id);
					}
				}
				else
				{
					deletedUsers[id] = user;
				}
				user.isNew = false;
				user.valid = false;
			}
		}
		for (var id in deletedUsers)
		{
			if (deletedUsers.hasOwnProperty(id))
			{
				delete this.currentUsers[id];
			}
		}
	},

	// update the new shouts
	updateShouts: function()
	{
		var maxId = this.lastId;
		for (var id in this.currentShouts)
		{
			if (this.currentShouts.hasOwnProperty(id))
			{  
				var shout = this.currentShouts[id]; 
				maxId = (shout.id > maxId) ? shout.id : maxId;
			}
		}
		this.lastId = maxId;
	},

	// update error handler
	updateError: function(jqXHR, status, error) {
		error = (typeof error == "string" && error != "") ? "Update: " + error : "Update";
		this.stdError(jqXHR, status, error);
		UpdaterContext.receivingChatData = false;
		UpdaterContext.startUpdates(500); // restart the updater 
		AjaxContext = this; // jQuery clones the context
		return true;
	},

	// update success handler
	updateSuccess: function(data, status, jqXHR) {
		this.shoutsParsed = false;
		this.usersParsed = false;
		var functions = { 
			user: this.checkRoomUsers,
			status: this.checkErrorStatus,
			shout: this.checkShouts,
			online: this.checkOnlineUsers,
			onstats: this.checkStatistics
		};
		var parseDocument = (this.dataType == "xml") ? this.parseXMLDocument : this.parseJSONDocument;
		if (parseDocument.call(this, data, "status,user,online,onstats,shout", functions))
		{
			if (this.shoutsParsed)
			{
				this.updateShouts();
			}
			if (this.usersParsed)
			{
				this.updateUsers();
			}
			if (typeof this.doneFunction == 'function')
			{
				return this.doneFunction.call(this);
			}
			return true;
		}
		return false;
	},

	// send comment error handler
	sendError: function(jqXHR, status, error) {
		error = (typeof error == "string" && error != "") ? "Send: " + error : "Send";
		this.stdError(jqXHR, status, error);
		$("#submit").attr("disabled", false);
		return true;
	}
};

// The Updater context
var UpdaterContext = {

	// Mutex flag for the chat data requester
	receivingChatData: false,

	// The update timer
	updaterTimer: undefined,

	// Stop the update timer
	stopUpdates: function() {
		if (typeof this.updateTimer != "undefined")
		{
			clearTimeout(this.updateTimer);
			this.updateTimer = undefined;
		} 
	},

	// Starts the update timer
	startUpdates: function(delay) {
		this.stopUpdates();
		this.updateTimer = setTimeout(receiveChatData, delay);
	}
};

// The chat room context
var ChatRoomContext = {
	// Private chat room
	chatRoom: "{view_shoutbox.CHAT_ROOM}",

	// Convert the room to an identifier
	roomToId: function(room) {
		return (typeof room == "string" && room != "") ? room.replace("|", "-") : "public";
	},

	// Create new chatroom window
	addChatTab: function(room, users) {
		// already exists?
		var roomId = this.roomToId(room);
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
	},

	// Activate the chatroom window
	activateChatTab: function(room) {
		// find tab
		var roomId = this.roomToId(room);
		var tab = $("#chat-tab-" + roomId);
		if (!tab.length || tab.hasClass("active")) {
			return;
		}

		// hide active tab and table
		var oldId = this.roomToId(this.chatRoom);
		$("#chat-tab-" + oldId).removeClass("active");
		$("#outputList-" + oldId).hide();

		// show new tab and table
		if (tab.hasClass("new-shout"))
		{
			tab.removeClass("new-shout");
		}
		tab.addClass("active");
		$("#outputList-" + roomId).show();
		this.chatRoom = room;
	},

	// Add and activate chat room  - used to open (or reopen) a private chat room
	addAndActivateChatTab: function(room) {
		this.addChatTab(room, AjaxContext.privateUsers);
		this.activateChatTab(room);
	},

	// A new shout has been added to a chatroom window
	chatTabNewShout: function(room) {
		var roomId = this.roomToId(room);
		var tab = $("#chat-tab-" + roomId);
		if (!tab.length || tab.hasClass("active") || tab.hasClass("new-shout"))
		{
			return;
		}
		tab.addClass("new-shout");
	}
};

// Display/hide the throbber
function throbber(state)
{
	$("#indicator").css("visibility", state ? "visible" : "hidden");
}

// Cyclicly requests chat data
function receiveChatData()
{
	var context = AjaxContext;
	if (!UpdaterContext.receivingChatData)
	{
		UpdaterContext.receivingChatData = true;
		UpdaterContext.stopUpdates();
	context.error = context.updateError;
	context.success = context.updateSuccess;
		context.complete = function(jqXHR, status) {
			throbber(false);
			UpdaterContext.receivingChatData = false;
		};
	context.doneFunction = function() {
		if (this.shoutsParsed || this.usersParsed)
		{
			chatDataChanged();
		}
			if (typeof UpdaterContext.updateTimer == "undefined")
			{
				UpdaterContext.startUpdates(REFRESH_TIME); // restart the updater
			}
		AjaxContext = this;
		return true;
	};

	context.setUpdateParameters("read");
	$.ajax(context);
}
	else
	{
		UpdaterContext.startUpdates(250); // restart the updater 
	}
}

<!-- BEGIN user_is_admin -->
// Delete the specified shout from the database
function removeShout(shoutId)
{
	// Ask for confirmation for the deletion of the shout
	if (confirm("{L_CONFIRM}"))
	{
		var context = jQuery.extend(new Object(), AjaxContext);
		context.error = context.stdError;
		context.success = context.stdSuccess;	
		context.complete = function(jqXHR, status) {
			throbber(false);
		};
		context.doneFunction = function() {
			$("#" + SHOUT_PREFIX + shoutId).remove();
			return true;
		};

		context.setParameters("del");
		context.data.sh = shoutId;
		$.ajax(context);
	}
}
<!-- END user_is_admin -->

<!-- BEGIN shout_allowed -->
// Stores a new comment on the server
function sendComment()
{
	var inputText = $("#chatbarText");
	var text = $.trim(inputText.val());
	if (text != "")
	{
		var context = jQuery.extend(new Object(), AjaxContext);

		// We add a letter to avoid sending the empty string.
		// The server will see if the user is logged in or not.
		var name = "e";
		<!-- BEGIN guest_shouter -->
		var inputName = $("#name");
		name = inputName.val();
		<!-- END guest_shouter -->
		var submit = $("#submit");
		context.error = AjaxContext.sendError;
		context.success = AjaxContext.stdSuccess;
		context.complete = function(jqXHR, status) {
			throbber(false);
		};
		context.doneFunction = function() {
			inputText.val("");
			inputText.focus();
			submit.attr("disabled", false);
			receiveChatData(); // show the results immediately
			return true;
		};

		context.setUpdateParameters("add");
		context.data.nm = name;
		context.data.co = text;
		submit.attr("disabled", true);
		$.ajax(context);
	}
}
<!-- END shout_allowed -->

// Sends the leave chat command
function leaveChat(evt)
{
	var context = jQuery.extend(new Object(), AjaxContext);
	context.error = function(jqXHR, status, error) {
		return true;
	};
	context.success = function(data, status, jqXHR) {
		return true;
	};
	context.complete = function(jqXHR, status) {
	};
	context.setParameters("leave");
	$.ajax(context);
	return true;
}
// ]]>
</script>

<!-- JPLAYER - START -->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jquery_jplayer_compressed.js"></script>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
	// Local copy of jQuery selectors, for performance.
	var ac_jPlayer = $("#ac_notify");

	// Instance jPlayer
	ac_jPlayer.jPlayer({
		ready: function () {
			ac_jPlayer.jPlayer("setMedia", {
				mp3: "{FULL_SITE_PATH}notify.mp3",
				wav: "{FULL_SITE_PATH}notify.wav"
			});
		},
		supplied: "mp3, wav",
		swfPath: '{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/jplayer.swf',
		wmode: "window"
	});
});
// ]]>
</script>
<!-- JPLAYER - END -->

<!-- END view_shoutbox -->
