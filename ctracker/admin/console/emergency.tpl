<html>
<head>
	<title>CBACK CrackerTracker - Emergency Console</title>
</head>
<body bgcolor="#000000">

<div align="center">

<img src="admin/console/console_pic.png" alt="ECON" title="ECON">

<br /><br />

<!-- BEGIN ok -->
	<font face="Verdana" size="6" color="#00CF09" style="text-decoration:blink;">Operation done successfully!</font>
	<br /><br />
<!-- END ok -->

	<font face="Verdana" size="3" color="#FFFFFF">
	Last Backup of Configuration Table: <b>{BACKUP}</b><br />
	{RESTORE_OUTPUT}
	</font>

	<br /><br />
	<br /><br />

	<form action="emergency.{PHP_EXT}?mode=psrt" method="post">
	<font face="Verdana" size="3" color="#FFFFFF">
	Manually override Board Configuration Settings<br /><br />
	</font>

	<table width="80%" cellpadding="6" cellspacing="1" border="1" align="center" bgcolor="#3F3F3F">
	<tr>
		<td><font face="Verdana" size="3" color="#FFFFFF">Server Name:</font></td>
		<td><input class="post" type="text" maxlength="255" size="40" name="server_name" /></td>
	</tr>
	<tr>
		<td><font face="Verdana" size="3" color="#FFFFFF">Port:</font></td>
		<td><input class="post" type="text" maxlength="5" size="40" name="server_port" value="80" /></td>
	</tr>
	<tr>
		<td><font face="Verdana" size="3" color="#FFFFFF">Script Path:</font></td>
		<td><input class="post" type="text" maxlength="255" size="40" name="script_path" /></td>
	</tr>
	<tr>
		<td><font face="Verdana" size="3" color="#FFFFFF">Cookie Domain (can be blank):</font></td>
		<td><input class="post" type="text" maxlength="255" size="40" name="cookie_domain" /></td>
	</tr>
	<tr>
		<td><font face="Verdana" size="3" color="#FFFFFF">Cookie Name:</font></td>
		<td><input class="post" type="text" maxlength="16" size="40" name="cookie_name" value="ct_erc_temp" /></td>
	</tr>
	<tr>
		<td><font face="Verdana" size="3" color="#FFFFFF">Cookie Path:</font></td>
		<td><input class="post" type="text" maxlength="255" size="40" name="cookie_path" /></td>
	</tr>
	<tr>
		<td><font face="Verdana" size="3" color="#FFFFFF">Cookie Secure:</font></td>
		<td><font face="Verdana" size="3" color="#FFFFFF"><input type="radio" name="cookie_secure" value="0" checked="checked" /> OFF&nbsp; &nbsp;<input type="radio" name="cookie_secure" value="1" /> ON</font></td>
	</tr>
	<tr>
		<td><font face="Verdana" size="3" color="#FFFFFF">Session Length:</font></td>
		<td><input class="post" type="text" maxlength="5" size="40" name="session_length" value="3600" /></td>
	</tr>
	<tr>
		<td align="center" colspan="2"><br /><br /><input type="Submit" name="submit" value="Do it now!"></td>
	</tr>
	</table>

	<br /><br />
	</form>

	<font face="Verdana" size="1" color="#FFFFFF">CrackerTracker Professional © 2004 - {YEAR} <a href="http://www.cback.de" target="_blank" style="color:#FDFF00">CBACK.de</a></font>
</body>
</html>