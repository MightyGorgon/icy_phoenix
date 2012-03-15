<!-- BEGIN view_shoutbox -->
<script type="text/javascript">//<![CDATA[

// Error messages
var ERROR_TIMEOUT = "{L_TIMEOUT}";
var ERROR_RESPONSE = "{L_UNABLE}";

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
var ajaxContext = {

	// The data type to use
	dataType: "{view_shoutbox.RESPONSE_TYPE}",

	// initial value will be replaced by the latest known shout id
	lastId: -1,

	// initial value will be replaced by the latest user list signature
	lastSig: "",

	// List of current shouts (the array is associative)
	currentShouts: new Object(),
	shoutsParsed: false,

	// List of current users (the array is associative)
	currentUsers: new Object(),
	usersParsed: false,

	// Parse the XML document, executing code for each matched element
	// Returns true if successful, otherwise false.
	parseXMLDocument: function(doc, functions)
	{
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
	parseJSONDocument: function(doc, functions)
	{
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
			act: action
		};
	},

	// update timer
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
		// null function here, used in chat code
	},

	// Check and display the error status (if any)
	// Returns false if an error occurred, otherwise true
	checkErrorStatus: function(result)
	{
		if (result.error_status != 0)
		{
			alert(result.error_msg);
			return false;
		}
		return true;
	},

	// Standard error handler
	stdError: function(jqXHR, status, error) {
		var message = (status == "timeout") ? ERROR_TIMEOUT : ERROR_RESPONSE;
		if (typeof error == "string" && error != "")
		{
			message += "(" + error + ")";
		}
		alert(status + ": " + message);
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
	}
};

// Display/hide the throbber
function throbber(state)
{
	$("#indicator").css("visibility", state ? "visible" : "hidden");
}

// HTML id prefixes
var SHOUT_PREFIX = "{L_SHOUT_PREFIX}";
var USER_PREFIX = "{L_USER_PREFIX}";

<!-- BEGIN user_is_admin -->
// Delete the specified shout from the database
function removeShout(shoutId)
{
	// Ask for confirmation for the deletion of the shout
	if (confirm("{L_CONFIRM}"))
	{
		var context = ajaxContext;
		context.stopUpdates();
		context.error = function(jqXHR, status, error) {
			this.stdError(jqXHR, status, error);
			this.startUpdates(1000); // restart the updater 
			ajaxContext = this; // jQuery clones the context
			return true;
		};
		context.success = ajaxContext.stdSuccess;	
		context.doneFunction = function() {
			$("#" + SHOUT_PREFIX + shoutId).remove();
			delete this.currentShouts[shoutId];
			this.startUpdates(1000); // restart the updater 
			ajaxContext = this; // jQuery clones the context
			return true;
		};

		context.setParameters("del");
		context.data.sh = shoutId;
		$.ajax(context);
	}
}
<!-- END user_is_admin -->

// Sends the leave chat command
function leaveChat(evt)
{
	var context = ajaxContext;
	context.stopUpdates();
	// deaf and dumb to errors and success
	context.error = function(jqXHR, status, error) {
		ajaxContext = this; // jQuery clones the context
		return true;
	};
	context.success = function(data, status, jqXHR) {
		ajaxContext = this; // jQuery clones the context
		return true;
	};
	context.doneFunction = undefined;

	context.setParameters("leave");
	$.ajax(context);
	return true;
}
//]]>
</script>
<!-- END view_shoutbox -->
