// Show / Hide - BEGIN
var PreloadFlag = false;
var expDays = 90;
var exp = new Date();
var tmp = '';
var tmp_counter = 0;
var tmp_open = 0;

exp.setTime(exp.getTime() + (expDays*24*60*60*1000));

function SetCookie(name, value)
{
	var argv = SetCookie.arguments;
	var argc = SetCookie.arguments.length;
	var expires = (argc > 2) ? argv[2] : null;
	var path = (argc > 3) ? argv[3] : null;
	var domain = (argc > 4) ? argv[4] : null;
	var secure = (argc > 5) ? argv[5] : false;
	document.cookie = name + "=" + escape(value) +
		((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
		((path == null) ? "" : ("; path=" + path)) +
		((domain == null) ? "" : ("; domain=" + domain)) +
		((secure == true) ? "; secure" : "");
}

function getCookieVal(offset)
{
	var endstr = document.cookie.indexOf(";",offset);
	if (endstr == -1)
	{
		endstr = document.cookie.length;
	}
	return unescape(document.cookie.substring(offset, endstr));
}

function GetCookie(name)
{
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while (i < clen)
	{
		var j = i + alen;
		if (document.cookie.substring(i, j) == arg)
		{
			return getCookieVal(j);
		}
		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0)
		{
			break;
		}
	}
	return null;
}

function ShowHide(id1, id2, id3)
{
	var res = expMenu(id1);
	if (id2 != '')
	{
		expMenu(id2);
	}
	if (id3 != '')
	{
		SetCookie(id3, res, exp);
	}
}

function expMenu(id)
{
	var itm = null;
	if (document.getElementById)
	{
		itm = document.getElementById(id);
	}
	else if (document.all)
	{
		itm = document.all[id];
	}
	else if (document.layers)
	{
		itm = document.layers[id];
	}
	if (!itm)
	{
		// do nothing
	}
	else if (itm.style)
	{
		if (itm.style.display == "none")
		{
			itm.style.display = "";
			return 1;
		}
		else
		{
			itm.style.display = "none";
			return 2;
		}
	}
	else
	{
		itm.visibility = "show";
		return 1;
	}
}

function showMenu(id)
{
	var itm = null;
	if (document.getElementById)
	{
		itm = document.getElementById(id);
	}
	else if (document.all)
	{
		itm = document.all[id];
	}
	else if (document.layers)
	{
		itm = document.layers[id];
	}
	if (!itm)
	{
		// do nothing
	}
	else if (itm.style)
	{
		if (itm.style.display == "none")
		{
			itm.style.display = "";
			return true;
		}
		else
		{
//			itm.style.display = "none";
			return true;
		}
	}
	else
	{
		itm.visibility = "show";
		return true;
	}
}

function hideMenu(id)
{
	var itm = null;
	if (document.getElementById)
	{
		itm = document.getElementById(id);
	}
	else if (document.all)
	{
		itm = document.all[id];
	}
	else if (document.layers)
	{
		itm = document.layers[id];
	}
	if (!itm)
	{
		// do nothing
	}
	else if (itm.style)
	{
		if (itm.style.display == "none")
		{
//			itm.style.display = "";
			return true;
		}
		else
		{
			itm.style.display = "none";
			return true;
		}
	}
	else
	{
		itm.visibility = "hide";
		return true;
	}
}
// Show / Hide - END

// Set Width - BEGIN
function setWidth(tmpWidth)
{
	cellobject = document.getElementById('var_width');
	if ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1))
	{
		cellobject.style.width = tmpWidth;
		//cellobject.style['min-width'] = tmpWidth;
	}
	else
	{
		cellobject.width = tmpWidth;
		//cellobject.['min-width'] = tmpWidth;
	}
}
// Set Width - END

// Select Text - BEGIN
function IsIEMac()
{
	// Any better way to detect IEMac?
	var ua = String(navigator.userAgent).toLowerCase();
	if( document.all && ua.indexOf("mac") >= 0 )
	{
		return true;
	}
	return false;
}

function select_text(obj)
{
	var o = document.getElementById(obj)
	if( !o ) return;
	var r, s;
	if( document.selection && !IsIEMac() )
	{
		// Works on: IE5+
		// To be confirmed: IE4? / IEMac fails?
		r = document.body.createTextRange();
		r.moveToElementText(o);
		r.select();
	}
	else if( document.createRange && (document.getSelection || window.getSelection) )
	{
		// Works on: Netscape/Mozilla/Konqueror/Safari
		// To be confirmed: Konqueror/Safari use window.getSelection ?
		r = document.createRange();
		r.selectNodeContents(o);
		s = window.getSelection ? window.getSelection() : document.getSelection();
		s.removeAllRanges();
		s.addRange(r);
	}
}
// Select Text - END


// Images, Links, Popup, FORM - BEGIN
function SetLangTheme()
{
	document.ChangeThemeLang.submit();
	return true;
}
// Images, Links, Popup, FORM - END


// Toggle / Display - BEGIN
function hdr_ref(object)
{
	if (document.getElementById)
	{
		return document.getElementById(object);
	}
	else if (document.all)
	{
		return eval('document.all.' + object);
	}
	else
	{
		return false;
	}
}

function hdr_expand(object)
{
	var object = hdr_ref(object);

	if( !object.style )
	{
		return false;
	}
	else
	{
		object.style.display = '';
	}

	if (window.event)
	{
		window.event.cancelBubble = true;
	}
}

function hdr_contract(object)
{
	var object = hdr_ref(object);

	if( !object.style )
	{
		return false;
	}
	else
	{
		object.style.display = 'none';
	}

	if (window.event)
	{
		window.event.cancelBubble = true;
	}
}

function hdr_toggle(object, open_close, open_icon, close_icon)
{
	var object = hdr_ref(object);
	var icone = hdr_ref(open_close);

	if( !object.style )
	{
		return false;
	}

	if( object.style.display == 'none' )
	{
		object.style.display = '';
		icone.src = close_icon;
	}
	else
	{
		object.style.display = 'none';
		icone.src = open_icon;
	}
}
// Toggle / Display - END


// Gradual Highlight - BEGIN
var baseopacity = 70;

function slowhigh(which2)
{
	imgobj = which2;
	browserdetect = which2.filters ? "ie" : ( (typeof which2.style.MozOpacity == "string") ? "mozilla" : "");
	instantset(baseopacity);
	highlighting=setInterval("gradualfade(imgobj)", 50);
}

function slowlow(which2)
{
	cleartimer();
	instantset(baseopacity);
}

function instantset(degree)
{
	if (browserdetect == "mozilla")
	{
		imgobj.style.MozOpacity = degree / 100;
	}
	else if (browserdetect == "ie")
	{
		imgobj.filters.alpha.opacity = degree;
	}
}

function cleartimer()
{
	if (window.highlighting)
	{
		clearInterval(highlighting);
	}
}

function gradualfade(cur2)
{
	if ( (browserdetect == "mozilla") && (cur2.style.MozOpacity < 1) )
	{
		cur2.style.MozOpacity = Math.min(parseFloat(cur2.style.MozOpacity) + 0.1, 0.99);
	}
	else if ( (browserdetect == "ie") && (cur2.filters.alpha.opacity < 100) )
	{
		cur2.filters.alpha.opacity += 10;
	}
	else if (window.highlighting)
	{
		clearInterval(highlighting);
	}
}
// Gradual Highlight - END


// Dynamic Rating - BEGIN
function set_rate(rate, max)
{
	//var rt = document.getElementById('rate_tag');
	//document.getElementById('rate_tag').innerHTML = '<span class=\"rate' + rate + '\">' + rate + '<\/span>';
	//document.getElementById('rate_img').className = ('icorate' + rate);
	//document.getElementById('rate_value').value = rate;
	document.getElementById('rating').selectedIndex = (rate - 1);
	if (rate == 0)
	{
		for (i = 1; i <= max; i++)
		{
			document.getElementById('rate' + i).className = 'img-rate-off';
		}
	}
	else
	{
		for (i = 1; i <= rate; i++)
		{
			document.getElementById('rate' + i).className = 'img-rate-on';
		}
		for (i = (rate + 1); i <= max; i++)
		{
			document.getElementById('rate' + i).className = 'img-rate-off';
		}
	}
}

function submit_rate()
{
	document.ratingform.submit();
	return true;
}
// Dynamic Rating - END
