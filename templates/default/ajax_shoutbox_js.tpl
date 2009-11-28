<!-- BEGIN view_shoutbox -->
<script type="text/javascript" src="{T_COMMON_TPL_PATH}js/fat.js"></script>
<script type="text/javascript">
// <![CDATA[
// Based in the  XHTML live Chat (http://www.plasticshore.com)
// This script is published under a creative commons license
// license: http://creativecommons.org/licenses/by-nc-sa/2.0/
var current_class = 'row2';
var counter = 0;
var ChatActionurl = "{view_shoutbox.U_ACTION}";
var ChatRoom = "{view_shoutbox.CHAT_ROOM}";
var refresh_time = {view_shoutbox.REFRESH_TIME} * 1;
var sendTimeout, receiveTimeout, cssRules, row1_color, row2_color, update_online;
var lastID = -1; //initial value will be replaced by the latest known id

// NEW - BEGIN
var receiveDoAbort = 0;
var sendDoAbort = 0;
// Timeout time for XML Requests
var TIMEOUT = 6000;
// Has to do with the session updates
var rc = 0;
var su = (parseInt(30000 / refresh_time) - 2);
// NEW - END

<!-- BEGIN onload -->
var oldonload = window.onload;
if(typeof(oldonload) == 'function')
{
	window.onload = function(){oldonload();initJavaScript()};
}
else
{
	window.onload = function(){initJavaScript()};
}
//window.onload = initJavaScript;
<!-- END onload -->

// Initiates the two objects for sending and receiving data
var httpReceiveChat = getHTTPObject();
var httpSendChat = getHTTPObject();

function initJavaScript()
{
	if(document.forms['chatForm'])
	{
		document.forms['chatForm'].elements['chatbarText'].setAttribute('autocomplete', 'off'); //this non standard attribute prevents firefox' autofill function to clash with this script
		checkStatus(''); //sets the initial value and state of the input comment
		checkName(); //checks the initial value of the input name
	}
	get_colors();
	receiveChatText(); //initiates the first data query

	// Get background colors from classes
	if (!document.styleSheets)
	{
		return;
	}
}

// Get the background colors from css classes
function get_colors()
{
	var prefix;
	var theRules = new Array();
	if (document.styleSheets[0].cssRules)
	{
		theRules = document.styleSheets[0].cssRules
		prefix = 'td.';
	}
	else if (document.styleSheets[0].rules)
	{
		theRules = document.styleSheets[0].rules
		prefix = 'td.';
	}

	for (x = 0; x < theRules.length; x++)
	{
		if(theRules[x].selectorText == (prefix + 'row1'))
		{
			row1_color = theRules[x].style['backgroundColor'];
		}
		else if (theRules[x].selectorText == (prefix + 'row2'))
		{
			row2_color = theRules[x].style['backgroundColor'];
		}
	}
}

// Timeout Prevention
function ajaxTimeout(obj)
{
	var xhr, doAbort;

	// Wich Object timeout?
	if (obj == 1)
	{
		xhr = httpReceiveChat;
		doAbort = receiveDoAbort;
	}
	else
	{
		xhr = httpSendChat;
		doAbort = sendDoAbort;
	}

	// Abort it to try again later
	doAbort = 1;  //zz set to 1 so the handleResponse will just exit
	xhr.abort();

	// NEW - BEGIN
	// Do specific things depending on the type of timeout
	if (obj == 1)
	{
		setTimeout('receiveChatText();', refresh_time);
	}
	else if (obj == 2)
	{
		alert("{L_UNABLE}");
	}
	else if (obj == 3)
	{
		setTimeout('sendComment();', refresh_time);
	}
	// NEW - END
}

// Initiates the first data query
function receiveChatText()
{
	if ((httpReceiveChat.readyState == 4) || (httpReceiveChat.readyState == 0))
	{
		var param = 'lastID=' + lastID + '&act=read&chat_room=' + ChatRoom + '&rand=' + Math.floor(Math.random() * 1000000);
		if (rc == 0)
		{
			param = param + '&su=1';
		}
		httpReceiveChat.open("POST", ChatActionurl, true);
		httpReceiveChat.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		httpReceiveChat.onreadystatechange = new Function("handlehResponse(httpReceiveChat)");
		// Timeout Prevention
		//receiveTimeout = setTimeout("ajaxTimeout(1);", (refresh_time / 2));
		//indicator_switch();
		//httpReceiveChat.send(param);
	// NEW - BEGIN
		receiveTimeout = setTimeout("ajaxTimeout(1);", TIMEOUT);
		indicator_switch(1);
		// Reset the timeout variable
		receiveDoAbort=0;
		httpReceiveChat.send(param);
		if (rc == su)
		{
			rc = 0;
		}
		else
		{
			rc++;
		}
	}
	else
	{
		// We need to call this again or it wont be called
		setTimeout('receiveChatText();', refresh_time);
	}
	// NEW - END
}

function handlehResponse(obj)
{
	var doAbort;
	if (obj == httpReceiveChat)
	{
		doAbort = receiveDoAbort;
	}
	else
	{
		doAbort = sendDoAbort;
	}

	if ((doAbort == 0) && (obj.readyState == 4))
	{
		// Prevent Javascript errors when request fails
		try
		{
			if (obj.status == 200)
			{
				var updating_s = 0;
				indicator_switch(0);

				// We didn't timeout
				if (obj == httpReceiveChat)
				{
					clearTimeout(receiveTimeout);
				}
				else
				{
					clearTimeout(sendTimeout);
					document.forms['chatForm'].elements['chatbarText'].value = '';
					document.forms['chatForm'].elements['chatbarText'].focus();
					document.forms['chatForm'].elements['submit'].disabled = false;
				}

				var xmldoc;
				var arr = new Array();
				if (xmldoc = obj.responseXML)
				{
					var root, field;
					root = xmldoc.getElementsByTagName('response').item(0);
					for (var iNode = 0; iNode < root.childNodes.length; iNode++)
					{
						var node = root.childNodes.item(iNode);
						if(node.tagName != undefined)
						{
							for (i = 0; i < node.childNodes.length; i++)
							{
								var sibl = node.childNodes.item(i);
								for (x = 0; x < sibl.childNodes.length; x++)
								{
									if (sibl.childNodes.length > 0)
									{
										sibl2 = sibl.childNodes.item(0);
										field = sibl.tagName;
										if (field != undefined)
										{
											arr[field] = sibl2.data;
											if ((arr['error_status'] == 1) && (field == 'error_msg'))
											{
												alert(arr['error_msg']);
												return false;
											}
										}
									}
								}
							}
						}
						// Add Content
						if ((node.tagName == 'shout') && (arr['id'] > lastID))
						{
							//var shouter = arr['shouter'];
							var shouter = (arr['shouter_link'] != -1) ? '<a href="' + arr['shouter_link'] + '" ' + arr['shouter_color'] + '>' + arr['shouter'] + '<\/a>' : arr['shouter'];
							var message = unescape(arr['msg']);
							// clean escaped http
							message = message.replace(/http\:\_\/\_\//g, 'http://');
							//message = message.replace(/www\./g, 'http://www.');
							//message = message.replace(/http:\/\/http:\/\//g, 'http://');
							//alert(message);
							insertNewContent(arr['id'], shouter, message, arr['date'], lastID);
						}
						// Online List
						if ((node.tagName == 'online') && (update_online == true))
						{
							updating_s = 1;
							if (array_search(arr['username']) == false)
							{
								var temp = new Array;
								temp['id'] = arr['user_id'];
								temp['username'] = arr['username'];
								temp['link'] = arr['user_link'];
								temp['style'] = arr['link_style'];
								dbonline.push(temp);
							}
							arr['link_style'] = '';
						}
						if ((node.tagName == 'onstats') && (update_online == true))
						{
							var on_total = arr['total'];
							var on_guests = arr['guests'];
							var on_regs = arr['reg'];
							update_counters(on_total, on_regs, on_guests);
						}
					}
				}
			}
			// We need this for preventing an error when the request fails
			// Start the timer for the next request to read data
			if (obj == httpReceiveChat)
			{
				// Get the last id of this round or else keep the lastId untouch
				if ('id' in arr)
				{
					lastID = arr['id'] * 1;
				}
				else
				{
					lastID = lastID;
				}
				setTimeout('receiveChatText();', refresh_time);
				// Only update the online list when is loaded.
				if ((update_online == true) && (updating_s == 1))
				{
					updateOnline();
				}
			}
		}
		catch (e)
		{
			if (obj == httpReceiveChat)
			{
				// Don't loose the failed session update
				if (rc == 0)
				{
					rc = su;
				}
			}
		}
	}
}

function indicator_switch(state)
{
	if(document.getElementById("act_indicator"))
	{
		var img = document.getElementById("act_indicator");
		if(state == 1)
		{
			img.style.visibility = "visible";
		}
		else
		{
			img.style.visibility = "hidden"
		}
	}
}

// Lets put the shouts to the table
function insertNewContent(liId, liName, liText, liTime, last_id)
{
	// Row Id
	var id = 'row_' + counter;

	// Get a reference to the table
	var tableRef = document.getElementById("outputList");

	// Insert a row in the table at row index 0
	var newRow = tableRef.insertRow(0);

	// Put some attributes to the row
	newRow.setAttribute('id', id);

	if(current_class == "row2")
	{
		newRow.style.backgroundColor = row1_color;
	}
	else
	{
		newRow.style.backgroundColor = row2_color;
	}

	// Insert 2 cells in the row
	var newCell = newRow.insertCell(0);
	var newCell2 = newRow.insertCell(1);

	// Put some attributes to the rows
	newCell.setAttribute('width', 140);

	// Append a text node to the cell
	newCell.innerHTML = '';
	//newCell.innerHTML = newCell.innerHTML + '<div style="text-align: center;"><b>' + liName + '<\/b><\/div>';
	//newCell.innerHTML = newCell.innerHTML + '<div style="text-align: center;"><span class="gensmall"><i>' + liTime + '<\/i><\/span><\/div><br \/>';
	newCell.innerHTML = newCell.innerHTML + '<div style="text-align: center;"><span class="gensmall"><i>' + liTime + '<\/i><\/span><\/div>';
	<!-- BEGIN user_is_admin -->
	//newCell.innerHTML = newCell.innerHTML + '<div style="text-align: center;"><span class="gensmall"><a href="#" onclick="removeShout(\'outputList\', this.parentNode.parentNode.rowIndex, ' + liId + ')">[ {L_DELETE} ]<\/a><\/span><\/div>';
	<!-- END user_is_admin -->

	//newCell2.innerHTML = '<div class="post-text post-text-hide-flow">' + liText + '<\/div>';
	newCell2.innerHTML = '<div class="post-text post-text-hide-flow">' + '<b>' + liName + '<\/b>' + ': ' + liText + '<\/div>';

	<!-- BEGIN user_is_admin -->
	var newCell3 = newRow.insertCell(2);
	newCell3.setAttribute('width', 100);
	newCell3.innerHTML = '<div style="text-align: center;"><span class="gensmall"><a href="#" onclick="removeShout(\'outputList\', this.parentNode.parentNode.parentNode.parentNode.rowIndex, ' + liId + ')">[ {L_DELETE} ]<\/a><\/span><\/div>';
	<!-- END user_is_admin -->

	// Add 1 to counter
	counter++;

	// Fade effect
	// We don't want to fade the first pack of messages
	if(last_id != -1)
	{
		//Fat.fade_element(id, 30, 2000, '{T_FONTCOLOR3}');
		Fat.fade_element(id, 30, 2000, '#ff5500');
		// Finally we can set the cells classes after the fade
		setTimeout("classChanger('" + newRow.id + "');", 2000);
		return true;
	}
	// Finally we can set the cells classes after the fade
	classChanger(newRow.id);
}

function classChanger(obj_id)
{
	var obj = document.getElementById(obj_id);
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

<!-- BEGIN user_is_admin -->
function removeShout (tblId, index, id)
{
	// Ask for confirmation for the deletion of the shout
	if(confirm("{L_CONFIRM}"))
	{
		// Delete the data from the Database
		deleteShout(id);

		// Remove row
		removeRow(tblId, index);
	}
}
// Remove row from table
function removeRow (tblId, index)
{
	var tbl = document.getElementById(tblId);
	// Set the index to 0 if no index have been entered
	if(!index)
	{
		var index = 0;
	}

	// Try to delete the row, if error occurs, alert the user
	try
	{
		tbl.deleteRow(index);
	}
	catch (ex)
	{
		alert(ex);
	}
}

// Delete shout from DB
function deleteShout(shout_id)
{
	param = 'act=del&sh=' + shout_id;
	httpSendChat.open("POST", ChatActionurl, true);
	httpSendChat.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpSendChat.onreadystatechange = new Function("handlehResponse(httpSendChat)");
	// Timeout Prevention
	//sendTimeout = setTimeout("ajaxTimeout(2);", (refresh_time / 2));
	sendTimeout = setTimeout("ajaxTimeout(2);", TIMEOUT);
	sendDoAbort = 0;
	httpSendChat.send(param);
}
<!-- END user_is_admin -->

// Stores a new comment on the server
function sendComment()
{
	currentChatText = encodeURIComponent(document.forms['chatForm'].elements['chatbarText'].value);
	if ((currentChatText != '') && ((httpSendChat.readyState == 4) || (httpSendChat.readyState == 0)))
	{
		if(document.forms['chatForm'].elements['name'])
		{
			currentName = encodeURIComponent(document.forms['chatForm'].elements['name'].value);
		}
		else
		{
			// We put a letter for not sending the string empty.
			// The server will see if the user is logged in or not.
			currentName = "e";
		}
		param = 'act=add&chat_room=' + ChatRoom + '&nm=' + currentName + '&co=' + currentChatText;
		httpSendChat.open("POST", ChatActionurl, true);
		httpSendChat.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		httpSendChat.onreadystatechange = new Function("handlehResponse(httpSendChat)");
		// Timeout Prevention
		sendTimeout = setTimeout("ajaxTimeout(3);", TIMEOUT);
		sendDoAbort = 0;
		httpSendChat.send(param);

		// Disable the submit button
		document.forms['chatForm'].elements['submit'].disabled = true;
	}
	else if (currentChatText != '')
	{
		alert("{L_UNABLE}");
	}
}

//does clever things to the input and submit
function checkStatus(focusState)
{
	currentChatText = document.forms['chatForm'].elements['chatbarText'];
	oSubmit = document.forms['chatForm'].elements['submit'];
	if ((currentChatText.value != '') || (focusState == 'active'))
	{
		oSubmit.disabled = false;
	}
	else
	{
		oSubmit.disabled = true;
	}
}

// Autoassigns a random name to a new user
function checkName()
{
	if(document.forms['chatForm'].elements['name'])
	{
		currentName = document.forms['chatForm'].elements['name'];
		if (currentName.value == '')
		{
			currentName.value = 'guest_' + Math.floor(Math.random() * 10000);
		}
	}
}


//initiates the XMLHttpRequest object
//as found here: http://www.webpasties.com/xmlHttpRequest
function getHTTPObject()
{
	var xmlhttp;
	/*@cc_on
	@if (@_jscript_version >= 5)
		try
		{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			try
			{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (E)
			{
				xmlhttp = false;
			}
		}
	@else
	xmlhttp = false;
	@end @*/
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined')
	{
		try
		{
			xmlhttp = new XMLHttpRequest();
		}
		catch (e)
		{
			xmlhttp = false;
		}
	}
	return xmlhttp;
}
</script>

<!-- ONLINE BLOCK - BEGIN -->
<script type="text/javascript">
<!--
var displayed = new Array();
// Array to store the users online from the XmlRquest
var dbonline = new Array();
var on_total, on_reg, on_guest = 0;

// Tell the script to update the online list
update_online = true;
show_inline = true;

function update_counters (ctotal, cusers, cguest)
{
	var total_c = document.getElementById('total_c').innerHTML = ctotal;
	var user_c = document.getElementById('user_c').innerHTML = cusers;
	var guest_c = document.getElementById('guests_c').innerHTML = cguest;
}

// Searchs for search_term in all values of array
function in_array(search_term, array)
{
	var i = array.length;
	if (i > 0)
	{
		do
		{
			if (array[i] === search_term)
			{
				return true;
			}
		} while (i--);
	}
	return false;
}

// Searchs for obj in all username values of dbonline
function array_search(obj)
{
	for (var x = 0; x < dbonline.length; x++)
	{
		if (dbonline[x]['username'] == obj)
		{
			return true;
		}
	}
	return false;
}

// Get users already displayed as online
function get_displayed()
{
	displayed.length = 0;
	var y = 0;
	var list = document.getElementById('online_list');
	for (var x = 0; x < list.childNodes.length; x++)
	{
		if(list.childNodes[x].childNodes[0].tagName == 'A')
		{
			displayed[x] = list.childNodes[x].childNodes[0].innerHTML;
		}
		else if (list.childNodes[x].childNodes[0].tagName == 'B')
		{
			displayed[x] = list.childNodes[x].childNodes[0].childNodes[0].innerHTML;
		}
	}
}

function updateOnline()
{
	// Get users already displayed
	get_displayed()

	// Remove users offline
	for (var x = 0; x < displayed.length; x++)
	{
		if (!(array_search(displayed[x])))
		{
			cont = document.getElementById(displayed[x]);
			cont.parentNode.removeChild(cont);
		}
	}

	// Add new users online
	var list = document.getElementById('online_list');
	for (var y = 0; y < dbonline.length; y++)
	{

		if (!(in_array(dbonline[y]['username'], displayed)))
		{
			var li = document.createElement('span');
			var style, bb, be;
			li.setAttribute('id', dbonline[y]['username'])
			//li.setAttribute('align', 'left')
			if (show_inline == true)
			{
				li.setAttribute('style', 'text-align: left; display: inline; margin-right: 5px;')
			}
			else
			{
				li.setAttribute('style', 'text-align: left; display: block; margin-right: 5px;')
			}
			if (dbonline[y]['style'] != '')
			{
				style = dbonline[y]['style'];
				//bb = '&nbsp;&#8226;&nbsp;';
				bb = '';
				be = '';
			}
			else
			{
				style = '';
				bb = '';
				be = '';
			}

			// Remove this if you want to display each user on a new line!
			if ((y != (dbonline.length - 1)) && (show_inline == true))
			{
				be = ', ';
			}

			//li.innerHTML = bb + '<a href="' + dbonline[y]['link'] + '" class="postlink" target="_blank"' + style + '>' + dbonline[y]['username'] + '<\/a>' + be;
			li.innerHTML = bb + '<a href="' + dbonline[y]['link'] + '" class="gensmall" target="_blank"' + dbonline[y]['style'] + '>' + dbonline[y]['username'] + '<\/a>' + be;
			list.appendChild(li);
			Fat.fade_element(dbonline[y]['username'], 30, 2000, '#ff5500');
		}
	}

	// Reset the dbonline array
	dbonline.length = 0;
}
//-->
// ]]>
</script>
<!-- ONLINE BLOCK - END -->
<!-- END view_shoutbox -->