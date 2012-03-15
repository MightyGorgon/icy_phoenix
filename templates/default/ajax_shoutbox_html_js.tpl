<!-- BEGIN view_shoutbox -->
<script type="text/javascript" src="{T_COMMON_TPL_PATH}js/fat.js"></script>
<script type="text/javascript">//<![CDATA[

//
// window event callback functions
// If not used just leave the return statement 
//

<!-- BEGIN onload -->
// window.onload event
function chatOnLoad(evt)
{
	get_colors();
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

//
// DOM insertion callbacks
//

// Write the HTML as a string for a new shout
function insertNewShout(id, shout)
{
	var output = "<tr id=\"" + id + "\"";
	output += " style=\"background-color: " +
		((current_class == "row2") ? row1_color : row2_color) + ";\">";
	output += "<td width=\"140\"><div style=\"text-align: center;\"><span class=\"gensmall\"><i>" +
		shout.date + "<\/i><\/span><\/div><\/td>";
	output += "<td><div class=\"post-text post-text-hide-flow post-text-chat\"><b>" +
		shout.shouter_name + "</b>: " + shout.text + "<\/div><\/td>";

	<!-- BEGIN user_is_admin -->
	output += "<td width=\"100\"><div style=\"text-align: center;\">" +
		"<span class=\"gensmall\"><a href=\"#\" onclick=\"javascript:removeShout(" +
		shout.id + ");\">" + '{DELETE_IMG}' + "<\/a><\/span><\/div><\/td>";
	<!-- END user_is_admin -->

	output += "</tr>"
	return output;
}

// Write the HTML as a string for a new user
function insertNewUser(id, user)
{
	var output = "<span id=\"" + id + "\"";
	output += " style=\"text-align: left; display: " +
		((show_inline == true) ? "inline" : "block") + "; margin-right: 5px;\">";
	output += "<a href=\"" + user.link + " class=\"gensmall\" target=\"_blank\" " +
		user.style + ">" + user.username + "<\/a>";
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

// Highlight the newly added user
function highlightUser(id)
{
	Fat.fade_element(id, 30, 2000, '#ff5500');
}

// Signal that something chaged on the page
function chatDataChanged()
{
	playsound(sound_url);
}

//
// default style specific code
//

var show_inline = true;
var current_class = 'row2';
var cssRules, row1_color, row2_color, update_online;

// Get the background colors from css classes
function get_colors()
{
	var rules = new Array();
	if (document.styleSheets[0].cssRules)
	{
		rules = document.styleSheets[0].cssRules
	}
	else if (document.styleSheets[0].rules)
	{
		rules = document.styleSheets[0].rules
	}

	for (x = 0; x < rules.length; x++)
	{
		if(rules[x].selectorText == "td.row1")
		{
			row1_color = rules[x].style.backgroundColor;
		}
		else if (rules[x].selectorText == "td.row2")
		{
			row2_color = rules[x].style.backgroundColor;
		}
	}
}

function classChanger(id)
{
	var obj = document.getElementById(id);
	obj.childNodes[0].className = current_class;
	obj.childNodes[1].className = current_class;
	<!-- BEGIN user_is_admin -->
	obj.childNodes[2].className = current_class;
	<!-- END user_is_admin -->

	if(current_class == "row2")
	{
		current_class = "row1";
	}
	else
	{
		current_class = "row2";
	}
}

var sound_ver = parseInt(navigator.appVersion);
var sound_ie4 =  ((sound_ver > 3) && (navigator.appName != "Netscape")) ? 1 : 0;
var sound_ns3 = ((sound_ver == 3) && (navigator.appName == "Netscape")) ? 1 : 0;
var sound_ns4 =  ((sound_ver > 3) && (navigator.appName == "Netscape")) ? 1 : 0;
var sound_flag = ((sound_ns3 || sound_ns4) && (!navigator.javaEnabled() || !navigator.mimeTypes['audio/wav'])) ? false : true;

var sound_url = "{FULL_SITE_PATH}/templates/default/notify.wav";

function playsound(soundfile)
{
	if (sound_flag)
	{
		$("#notify").empty().prepend("<embed src=\"" + soundfile + "\" autostart=\"true\" hidden=\"true\" loop=\"false\">");
	}
}

//]]>
</script>
<!-- END view_shoutbox -->
