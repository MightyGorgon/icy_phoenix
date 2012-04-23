<!-- BEGIN view_shoutbox -->
<script type="text/javascript">//<![CDATA[

// Based in part on the XHTML live Chat (http://www.plasticshore.com)
// This script is published under a creative commons license
// license: http://creativecommons.org/licenses/by-nc-sa/2.0/

// Refresh interval
var REFRESH_TIME = {view_shoutbox.REFRESH_TIME};

// Error messages
var ERROR_TIMEOUT = "{L_TIMEOUT}";
var ERROR_RESPONSE = "{L_UNABLE}";

// HTML id prefixes
var SHOUT_PREFIX = "{L_SHOUT_PREFIX}";
var USER_PREFIX = "{L_USER_PREFIX}";
var ROOM_PREFIX = "{L_ROOM_PREFIX}";

<!-- BEGIN onload -->
var oldOnLoad = window.onload;
if (typeof oldOnLoad == 'function')
{
	window.onload = function(evt) {
		oldOnLoad(evt);
		chatOnLoad(evt);
		initChat(evt);
	};
}
else
{
	window.onload = function(evt) {
		chatOnLoad(evt);
		initChat(evt);
	};
}

// Both needed as Opera does not handle onbeforeload
var oldOnUnload = window.onunload;
if (typeof oldOnUnload == 'function')
{
	window.onunload = function(evt) {
		leaveChat(evt);
		chatOnUnload(evt);
		oldOnUnload(evt);
	};
}
else
{
	window.onunload = function(evt) {
		leaveChat(evt);
		chatOnUnload(evt);
	};
}

var oldOnBeforeUnload = window.onbeforeunload;
if (typeof oldOnBeforeUnload == 'function')
{
	window.onbeforeunload = function(evt) {
		leaveChat(evt);
		chatOnBeforeUnload(evt);
		oldOnBeforeUnload(evt);		
	};
}
else
{
	window.onbeforeunload = function(evt) {
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
		odd: "odd",
		even: "even"
	},

	// List of current shouts (the array is associative)
	currentShouts: new Object(),
	shoutsParsed: false,

	// List of current users (the array is associative)
	currentUsers: new Object(),
	usersParsed: false,

	// Private chat room
	chatRoom: "{view_shoutbox.CHAT_ROOM}",

	// Private chat room user id
	chatUser: "{view_shoutbox.USER_ID}",

	// Parse the XML document, executing code for each matched element
	// Returns true if successful, otherwise false.
	parseXMLDocument: function(doc, functions) {
		for (var name in functions)
		{
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
	parseJSONDocument: function(doc, functions) {
		var response = doc.response;
		for (var name in functions)
		{
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
			chat_room: this.chatRoom,
			update_mode: "{view_shoutbox.UPDATE_MODE}"
		};
	},

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
		if (jqXHR.getAllResponseHeaders() || status == "timeout")
		{
			var message = (status == "timeout") ? ERROR_TIMEOUT : ERROR_RESPONSE;
			if (typeof error == "string" && error != "")
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
		if (parseDocument.call(this, data, functions))
		{
			if (typeof this.doneFunction == 'function')
			{
				return this.doneFunction.call(this);
			}
		}
		return false;
	},

	// Check and display the shouts (if any)
	// Returns false if an error occurred, otherwise true
	checkShouts: function(shouts)	{
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
				var roomId = (typeof shout.room == 'string' && shout.room != '') ? shout.room.replace(/\|/g, '-') : 'all';
				var tableId = 'outputList-' + roomId;
				var table = $('#' + tableId);
				if (!table.length)
				{
					// add new chat tab
					table = addChatTab(shout.room);
				}
				var cssClass = this.zebra.odd;
				var firstShout = $("#" + tableId + " tr:first");
				if (firstShout.length)
				{
					cssClass = (firstShout.prop("class") == this.zebra.odd) ? this.zebra.even : this.zebra.odd;
				}
				shout.cssClass = cssClass;
				var html = insertNewShout(id, shout)
				table.prepend(html);
				highlightShout(id, this.lastId != -1);
				this.currentShouts[id] = shout;
			}
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
				this.currentUsers[id] = {
					id: user.user_id,
					username: user.username,
					link: user.user_link,
					style: user.link_style,
					isNew: true,
					valid: true
				};
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
		this.startUpdates(1000); // restart the updater 
		AjaxContext = this; // jQuery clones the context
		return true;
	},

	// update success handler
	updateSuccess: function(data, status, jqXHR) {
		this.shoutsParsed = false;
		this.usersParsed = false;
		var functions = { 
			status: this.checkErrorStatus,
			shout: this.checkShouts,
			online: this.checkOnlineUsers,
			onstats: this.checkStatistics
		};
		var parseDocument = (this.dataType == "xml") ? this.parseXMLDocument : this.parseJSONDocument;
		if (parseDocument.call(this, data, functions))
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

// Display/hide the throbber
function throbber(state)
{
	$("#indicator").css("visibility", state ? "visible" : "hidden");
}

// Cyclicly requests chat data
function receiveChatData()
{
	var context = AjaxContext;
	context.error = context.updateError;
	context.success = context.updateSuccess;
	context.doneFunction = function() {
		if (this.shoutsParsed || this.usersParsed)
		{
			chatDataChanged();
		}
		this.startUpdates(REFRESH_TIME); // restart the updater 
		AjaxContext = this;
		return true;
	};

	context.setUpdateParameters("read");
	$.ajax(context);
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
	var text = inputText.val().trim();
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
		context.doneFunction = function() {
			inputText.val("");
			inputText.focus();
			submit.attr("disabled", false);
			AjaxContext.startUpdates(100); // restart the updater 
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
	context.setParameters("leave");
	$.ajax(context);
	return true;
}
//]]>
</script>
<!-- END view_shoutbox -->
