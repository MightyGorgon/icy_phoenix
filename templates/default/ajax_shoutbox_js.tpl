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
//]]>
</script>
<!-- END view_shoutbox -->
