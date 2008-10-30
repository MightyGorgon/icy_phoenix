<script type="text/javascript">
<!--
	function __off(n)
	{
		if(n && n.style)
		{
			if('none' != n.style.display)
			{
				n.style.display = 'none';
			}
		}
	}

	function __on(n)
	{
		if(n && n.style)
		{
			if('none' == n.style.display)
			{
				n.style.display = '';
			}
		}
	}

	function __toggle(n)
	{
		if(n && n.style)
		{
			if('none' == n.style.display)
			{
				n.style.display = '';
			}
			else
			{
				n.style.display = 'none';
			}
		}
	}

//&#149;

	function onoff(objName,bObjState)
	{
		var sVar = ''+objName;
		var sOn = ''+objName+'_on';
		var sOff = ''+objName+'_off';
		var sOnStyle = bObjState ? ' style="display:none;" ':'';
		var sOffStyle = !bObjState ? ' style="display:none;" ':'';
		var sSymStyle = ' style="text-align: center;width: 13;height: 13;font-family: Arial,Verdana;font-size: 7pt;border-style: solid;border-width: 1;cursor: hand;color: #003344;background-color: #CACACA;" ';

		if( (navigator.userAgent.indexOf("MSIE") >= 0) && document && document.body && document.body.style)
		{
				document.write( '<span '+sOnStyle+'onclick="__on('+sVar+');__off('+sOn+');__on('+sOff+');" id="'+sOn+'" title="Click here to show details"'+sSymStyle+'>+<\/span>' +
					'<span '+sOffStyle+'onclick="__off('+sVar+');__off('+sOff+');__on('+sOn+');" id="'+sOff+'" title="Click here to hide details"'+sSymStyle+'>-<\/span>' );
		}
		else
		{
			document.write('<span id="' + objName + '_on" onclick="__on(document.getElementById(\'' + objName + '\'));__off(document.getElementById(\'' + objName + '_on\'));__on(document.getElementById(\'' + objName + '_off\'));" title="Click here to show details" style="text-align: center;width: 13;height: 13;font-family: monospace;font-size: 7pt;border-style: solid;border-width: 1;cursor: pointer;color: #003344;background-color: #CACACA;' + (bObjState ? ' display:none;' : '') + '">&nbsp;+&nbsp;</span>');
			document.write('<span id="' + objName + '_off" onclick="__off(document.getElementById(\'' + objName + '\'));__on(document.getElementById(\'' + objName + '_on\'));__off(document.getElementById(\'' + objName + '_off\'));" title="Click here to show details" style="text-align: center;width: 13;height: 13;font-family: monospace;font-size: 7pt;border-style: solid;border-width: 1;cursor: pointer;color: #003344;background-color: #CACACA;' + (!bObjState ? ' display:none;' : '') + '">&nbsp;&minus;&nbsp;</span>');
		}
	}
// -->
</script>
<h1>{ADMIN_VOTING_ICON}{L_ADMIN_VOTE_TITLE}</h1>
<p>{L_ADMIN_VOTE_EXPLAIN}</p>
<br />
<form method="post" name="vote_list" action="{S_MODE_ACTION}">
<div style="text-align:right;padding:3px;">
<span class="genmed"><strong>{L_SELECT_SORT_FIELD}:</strong>&nbsp;{S_FIELD_SELECT}&nbsp;&nbsp;<strong>{L_SORT}:</strong>&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></span>
</div>
</form>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th height="20" nowrap="nowrap">{L_VOTE_ID}</th>
	<th height="20" nowrap="nowrap">{L_POLL_TOPIC}</th>
	<th height="20" nowrap="nowrap">{L_VOTE_USERNAME}</th>
	<th height="20" nowrap="nowrap">{L_VOTE_END_DATE}</th>
</tr>
<!-- BEGIN votes -->
<tr>
	<td class="{votes.COLOR} row-center"><span class="gensmall">{votes.VOTE_ID}</span></td>
	<td class="{votes.COLOR}"><span class="genmed">
		<script type="text/javascript">
		<!--
		onoff('vote{votes.VOTE_ID}_switch', false);
		//-->
		</script>
		<a href="{votes.LINK}">{votes.DESCRIPTION}</a></span><br />
	</td>
	<td class="{votes.COLOR}"><span class="gensmall">{votes.USER}</span></td>
	<td class="{votes.COLOR} row-center"><span class="gensmall">{votes.VOTE_DURATION}</span></td>
</tr>
<tr id="vote{votes.VOTE_ID}_switch" style="display:none;">
	<td class="row2" colspan="4" style="padding:0px;">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<!-- BEGIN detail -->
		<tr>
			<td class="row1">{votes.detail.OPTION} ({votes.detail.RESULT})</td>
			<td class="row2"><span class="gensmall">{votes.detail.USER}</span></td>
		</tr>
		<!-- END detail -->
		</table>
	</td>
</tr>
<tr><td class="spaceRow" colspan="4"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END votes -->
<tr><td class="cat" colspan="4">&nbsp;</td></tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<br />

<div align="center"><span class="copyright">Admin Voting v1.1.8 &copy 2002 <a href="mailto:ErDrRon@aol.com">ErDrRon</a></span></div>
