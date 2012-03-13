<!-- BEGIN view_shoutbox -->
<script type="text/javascript"><!--//
// Based in the  XHTML live Chat (http://www.plasticshore.com)
// This script is published under a creative commons license
// license: http://creativecommons.org/licenses/by-nc-sa/2.0/

// Refresh interval
var REFRESH_TIME = {view_shoutbox.REFRESH_TIME};

// Private chat room
ajaxContext.chatRoom = "{view_shoutbox.CHAT_ROOM}";

// Check and display the shouts (if any)
// Returns false if an error occurred, otherwise true
ajaxContext.checkShouts = function(shouts)
{
	for (var index = 0; index < shouts.length; index++) 
	{
		var shout = shouts[index];
		shout.id = parseInt(shout.id);
		var id = SHOUT_PREFIX + shout.id;
		if (shout.id > this.lastId && !(id in this.currentShouts))
		{
			this.shoutsParsed = true;
			shout.shouter = decodeURIComponent(shout.shouter.replace(/\+/g, ' ')); // doesn't seem to decode '+';
			var link = shout.shouter_link;
			shout.shouter_name = (link != "-1") ? "<a href=\"" + link + "\" " + shout.shouter_color + ">" + shout.shouter + "<\/a>" : shout.shouter;
			// clean escaped http
			shout.text = decodeURIComponent(shout.msg.replace(/\+/g, ' ')); // doesn't seem to decode '+';
			$("#output-list").prepend(insertNewShout(id, shout));
			highlightShout(id, this.lastId != -1);
			this.currentShouts[id] = shout;
		}
	}
	return true;
};

// Check and display the online users (if any)
// Returns false if an error occurred, otherwise true
ajaxContext.checkOnlineUsers = function(users)
{
	// clear the validity
	for (var id in this.currentUsers)
	{
		this.currentUsers[id].valid = false;
	}
	// add or update current users
	for (var index = 0; index < users.length; index++) 
	{
		var user = users[index];
		user.user_id = parseInt(user.user_id);
		var id = USER_PREFIX + user.username;
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
};

// Check and display the online statistics (if any)
// Returns false if an error occurred, otherwise true
ajaxContext.checkStatistics = function(stats)
{
	$("#total-count").empty().prepend(stats.total);
	$("#user-count").empty().prepend(stats.reg);
	$("#guests-count").empty().prepend(stats.guests);
	this.lastSig = stats.sig;
	return true;
};

// update the new shouts
ajaxContext.updateShouts = function()
{
	var maxId = this.lastId;
	for (var id in this.currentShouts)
	{
		var shout = this.currentShouts[id]; 
		if (shout.id > maxId)
		{
			maxId = shout.id;
		}
	}
	this.lastId = maxId;
};

// update the online users
ajaxContext.updateUsers = function()
{
	var list = $("#online-list");
	var deletedUsers = new Object();
	for (var id in this.currentUsers)
	{
		var user = this.currentUsers[id];
		if (user.isNew)
		{
			list.append(insertNewUser(id, user));
			highlightUser(id);
		}
		else if (!user.valid)
		{
			deletedUsers[id] = user;
		}
		user.isNew = false;
		user.valid = false;
	}
	for (var id in deletedUsers)
	{
		$("#" + id).remove();
		delete this.currentUsers[id];
	}
};

// Starts the update timer
ajaxContext.startUpdates = function(delay) {
	if (typeof(this.updateTimer) != "undefined")
	{
		clearTimeout(this.updateTimer);
	} 
	this.updateTimer = setTimeout(receiveChatData, delay);
};

// update error handler
ajaxContext.updateError = function(jqXHR, status, error) {
	error = ((typeof(error) == "string" && error != "")) ? "Update: " + error : "Update";
	this.stdError(jqXHR, status, error);
	this.startUpdates(1000); // restart the updater 
	ajaxContext = this; // jQuery clones the context
	return true;
};

// send comment error handler
ajaxContext.sendError = function(jqXHR, status, error) {
	error = ((typeof(error) == "string" && error != "")) ? "Send: " + error : "Send";
	this.stdError(jqXHR, status, error);
	setTimeout(sendComment, 1000); // try again
	ajaxContext = this; // jQuery clones the context
	return true;
};

// send/update parser
ajaxContext.chatSuccess = function(data, status, jqXHR) {
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
		if (typeof(this.doneFunction) == 'function')
		{
			return this.doneFunction.call(this);
		}
		return true;
	}
	return false;
};

// Sets the common chat AJAX parameters
ajaxContext.setChatParameters = function(action) {
	this.data = {
		sid: "{S_SID}",
		act: action,
		lastID: this.lastId,
		sig: this.lastSig,
		chat_room: this.chatRoom,
		su: 1
	};
};

<!-- BEGIN onload -->
var oldOnLoad = window.onload;
if (typeof(oldOnLoad) == 'function')
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
if (typeof(oldOnUnload) == 'function')
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
if (typeof(oldOnBeforeUnload) == 'function')
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
	receiveChatData(); // initiates the first data query
	return true;
}

<!-- BEGIN shout_allowed -->
// Disable the submit button if there is no message
function checkStatus(focusState)
{
	var text = $("#chatbarText");
	var submit = $("#submit");
	submit.attr("disabled", ((text.val() != "") || (focusState == "active")) ? false : true);
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

// Cyclicly requests chat data
function receiveChatData()
{
	var context = ajaxContext;
	context.stopUpdates();
	context.error = context.updateError;
	context.success = context.chatSuccess;
	context.doneFunction = function() {
		if (this.shoutsParsed || this.usersParsed)
		{
			chatDataChanged();
		}
		this.startUpdates(REFRESH_TIME); // restart the updater 
		ajaxContext = this; // jQuery clones the context
		return true;
	};

	context.setChatParameters("read");
	$.ajax(context);
}

// Stores a new comment on the server
function sendComment()
{
	var inputText = $("#chatbarText");
	var text = encodeURIComponent(inputText.val());
	if (text != "")
	{
		var context = ajaxContext;
		context.stopUpdates();

		// We put a letter for not sending the string empty.
		// The server will see if the user is logged in or not.
		var name = "e";
		<!-- BEGIN guest_shouter -->
		var inputName = $("#name");
		name = encodeURIComponent(inputName.val());
		<!-- END guest_shouter -->

		var submit = $("#submit");
		context.error = ajaxContext.sendError;
		context.success = ajaxContext.chatSuccess;
		context.doneFunction = function() {
			inputText.val("");
			inputText.focus();
			submit.attr("disabled", false);
			this.startUpdates(1000); // restart the updater 
			ajaxContext = this; // jQuery clones the context
			return true;
		};

		context.setChatParameters("add");
		context.data.nm = name;
		context.data.co = text;
		submit.attr("disabled", true);
		$.ajax(context);
	}
}
//-->
</script>
<!-- END view_shoutbox -->
