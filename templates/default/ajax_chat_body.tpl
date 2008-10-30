<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_AJAX_SHOUTBOX}" class="nav-current">{L_AJAX_SHOUTBOX}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
		<!-- BEGIN archive_link -->
		<a href= "{U_ARCHIVE}">{L_ARCHIVE}</a>
		<!-- END archive_link -->
	</div>
</div>

{SHOUTBOX_BODY}

<br /><br />
<table class="forumline" width="40%" cellspacing="0" cellpadding="0">
	<tr><th>{L_WIO}</th></tr>
	<tr><td class="row1"><span class="post-text" id="online_list"></span></td></tr>
	<tr>
		<td class="row2">
			<span class="gensmall">
				{L_TOTAL}:<b><span id="total_c">0</span></b><br />
				{L_USERS}:<b><span id="user_c">0</span></b><br />
				{L_GUESTS}:<b><span id="guests_c">0</span></b><br />
				{L_SHOUTBOX_ONLINE_EXPLAIN}
			</span>
		</td>
	</tr>
</table>

<script type="text/javascript">
<!--
var displayed = new Array();
// Array to store the users online from the XmlRquest
var dbonline = new Array();
var on_total, on_reg, on_guest = 0;

// Tell the script to update the online list
update_online = true;

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
	for (var x=0; x < list.childNodes.length; x++)
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
			var li = document.createElement('div');
			var style, bb, be;
			li.setAttribute('id', dbonline[y]['username'])
			li.setAttribute('align', 'left')
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

			//li.innerHTML = bb + '<a href="' + dbonline[y]['link'] + '" class="postlink" target="_blank"' + style + '>' + dbonline[y]['username'] + '</a>' + be;
			li.innerHTML = bb + '<a href="' + dbonline[y]['link'] + '" class="postlink" target="_blank"' + dbonline[y]['style'] + '>' + dbonline[y]['username'] + '</a>' + be;
			list.appendChild(li);
			Fat.fade_element(dbonline[y]['username'], 30, 2000, '#FF5500');
		}
	}

	// Reset the dbonline array
	dbonline.length = 0;
}
//-->
</script>