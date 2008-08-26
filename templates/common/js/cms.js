var xmlHttp;
var field;

function ChangeStatus(current, type, b_id, cms_type)
{
	xmlHttp = GetXmlHttpObject();
	if (xmlHttp == null)
	{
		alert ("Browser does not support HTTP Request");
		return;
	}

	switch (type)
	{
		case 0:
			field = "active_";
			break;
		case 1:
			field = "border_";
			break;
		case 2:
			field = "titlebar_";
			break;
		case 3:
			field = "local_";
			break;
		case 4:
			field = "background_";
			break;
	}

	var url = phpbb_root_path + 'cms_db_update.' + phpEx;
	var params = 'mode=update_block&type=' + type + '&b_id=' + b_id + '&status=' + document.getElementById(field + b_id).value + '&cms_type=' + cms_type;
	if (S_SID != '')
	{
		params += '&sid=' + S_SID;
	}
	url = url + "?" + params;

	xmlHttp.open("GET", url, true);
	xmlHttp.send(null);

	if (document.getElementById(field + b_id).value == 0)
	{
		current.src = "images/cms/turn_" + field + "on.png"
		document.getElementById(field + b_id).value = 1;
	}
	else
	{
		current.src = "images/cms/turn_" + field + "off.png"
		document.getElementById(field + b_id).value = 0;
	}
}

function ChangeMenuOrder(m_id)
{
	xmlHttp = GetXmlHttpObject();
	if (xmlHttp == null)
	{
		alert ("Browser does not support HTTP Request");
		return;
	}

	var url = phpbb_root_path + 'cms_db_update.' + phpEx;
	var params = 'mode=update_menu_order';
	if (S_SID != '')
	{
		params += '&sid=' + S_SID;
	}
	url = url + "?" + params;

	xmlHttp.open("GET", url, true);
	xmlHttp.send(null);
}

function GetXmlHttpObject()
{
	var xmlHttp = null;
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp = new XMLHttpRequest();
	}
	catch (e)
	{
		//Internet Explorer
		try
		{
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}