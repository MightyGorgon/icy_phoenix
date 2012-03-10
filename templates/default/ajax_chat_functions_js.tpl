<script type="text/javascript"><!--//

// Error messages
const ERROR_TIMEOUT = "{L_TIMEOUT}";
const ERROR_RESPONSE = "{L_UNABLE}";

// Default jQuery AJAX settings
$.ajaxSetup({
	beforeSend: function(jqXHR, settings) { throbber(true); },
	// context: this,
	cache: false,
	complete: function(jqXHR, status) { throbber(false); },
	// data: { "a" : b }
	dataType: "xml",
	// fail/error: function(jqXHR, status, error),
	// done/success: function(data, status, jqXHR), 
	timeout: 5000,
	type: "POST",
  url: "{U_ACTION}"
});

// Parse the XML document, executing code for each matched element
// Returns true if successful, otherwise false.
function parseXMLDocument(xmlDoc, functions)
{
	if (xmlDoc)
	{
		for (var name in functions)
		{
			var values = new Object();
			var added = false;
			$(name + " > *", xmlDoc).each(function(index, found) {
				values[found.tagName] = found.textContent;
				added = true;
			});
			if (added && !functions[name](values))
			{
				return false;
			}
		}
	}
	return true;
}

// Check and display the error status (if any)
// Returns false if an error occurred, otherwise true
function checkErrorStatus(result)
{
	if (result["error_status"] != 0)
	{
		alert(result["error_msg"]);
		return false;
	}
	return true;
}

// Standard XML reponse handler - checks for errors only
function standardXMLResponseHandler(successFunction)
{
	return {
    error: function(jqXHR, status, error) {
			var message = (status == "timeout") ? ERROR_TIMEOUT : ERROR_RESPONSE;
			if (typeof(error) == "string" && error != "")
			{
				message += ": " + error;
			}
			alert(message);
			return true;
		},
		success: function(data, status, jqXHR) {
			var functions = { "status" : checkErrorStatus };
			if (parseXMLDocument(data, functions))
			{
				if (typeof(this.doneFunction) == 'function')
				{
					this.doneFunction();
				}
			}
			return true;
		},
		doneFunction: successFunction
	}; 
}

// Display/hide the throbber
function throbber(state)
{
	$("#indicator").css("visibility", state ? "visible" : "hidden");
}

<!-- IF S_ADMIN -->
// HTML id prefixes
const SHOUT_PREFIX = "{L_SHOUT_PREFIX}";

// Delete the specified shout from the database
function removeShout(shoutId)
{
	// Ask for confirmation for the deletion of the shout
	if (confirm("{L_CONFIRM}"))
	{
		var ajaxParams = standardXMLResponseHandler(function() {
			$("#" + SHOUT_PREFIX + shoutId).remove();
			// Chat archive doesn't have this variable, but Chat does
			if (typeof currentShouts != "undefined")
			{
				delete currentShouts[shoutId];
			}
		});
		ajaxParams.data = {
			sid: "{S_SID}",
			act: "del",
			xhr: "xml",
			sh: shoutId
    };
		$.ajax(ajaxParams);
	}
}
<!-- ENDIF S_ADMIN -->

// Sends the leave chat command
function leaveChat()
{
		var ajaxParams = standardXMLResponseHandler();
		ajaxParams.data = {
			sid: "{S_SID}",
			act: "leave"
 		};
		$.ajax(ajaxParams);
}
//-->
</script>
