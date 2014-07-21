<tr><th colspan="2">{REG_TITLE}</th></tr>
<tr>
	<td class="row1" colspan="2">
		<form name="events_reg" method="post" action="{REG_S_ACTION}">
		<!-- IF S_ADMIN -->
			<b>{L_EVENTS_REG_USER}:</b>&nbsp;<input type="text" class="post" name="username" id="username" maxlength="50" size="20" {REG_S_AJAX_USER_CHECK} />&nbsp;
			<span id="username_list" style="display: none;">&nbsp;<span id="username_select">&nbsp;</span></span>
			<input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onclick="window.open('{REG_U_SEARCH_USER}', '_search', 'width=400,height=250,resizable=yes'); return false;" />
			{REG_S_SELECT}
			<input type="submit" name="submit" value="{L_GO}" class="mainoption" />
		<!-- ENDIF -->
		<table>
		<tr>
			<!-- BEGIN reg_option1 -->
			<td class="tw33pct row2"><input type="button" class="mainoption" value="{REG_OPTION1_NAME}" onclick="self.location.href='{REG_OPTION1_URL}';" onmouseover="'{REG_OPTION1_NAME}'" {REG_OPTION1_READONLY} />&nbsp;<span class="text_green">({REG_OPTION1_COUNT})</span><span class="gensmall">&nbsp;{REG_OPTION1_SLOTS}</span></td>
			<!-- END reg_option1 -->
			<!-- BEGIN reg_option2 -->
			<td class="tw34pct row2"><input type="button" class="mainoption" value="{REG_OPTION2_NAME}" onclick="self.location.href='{REG_OPTION2_URL}';" onmouseover="'{REG_OPTION2_NAME}'" {REG_OPTION2_READONLY} />&nbsp;<span class="text_blue">({REG_OPTION2_COUNT})</span>&nbsp;</td>
			<!-- END reg_option2 -->
			<!-- BEGIN reg_option3 -->
			<td class="tw33pct row2" colspan="1"><input type="button" class="mainoption" value="{REG_OPTION3_NAME}" onclick="self.location.href='{REG_OPTION3_URL}';" onmouseover="'{REG_OPTION3_NAME}'" {REG_OPTION3_READONLY} />&nbsp;<span class="text_red">({REG_OPTION3_COUNT})</span>&nbsp;</td>
			<!-- END reg_option3 -->
		</tr>
		<tr>
			<!-- BEGIN reg_option1 -->
			<td class="row2">
				<table class="s2px">
				<tr>
					<td><span class="gensmall">{REG_HEAD_USERNAME}</span></td>
					<td><span class="gensmall">{REG_HEAD_TIME}</span></td>
				</tr>
				{reg_option1.REG_OPTION1_DATA}
				</table>
			</td>
			<!-- END reg_option1 -->
			<!-- BEGIN reg_option2 -->
			<td class="row2">
				<table class="s2px">
				<tr>
					<td><span class="gensmall">{REG_HEAD_USERNAME}</span></td>
					<td><span class="gensmall">{REG_HEAD_TIME}</span></td>
				</tr>
				{reg_option2.REG_OPTION2_DATA}
				</table>
			</td>
			<!-- END reg_option2 -->
			<!-- BEGIN reg_option3 -->
			<td class="row2">
				<table class="s2px">
				<tr>
					<td><span class="gensmall">{REG_HEAD_USERNAME}</span></td>
					<td><span class="gensmall">{REG_HEAD_TIME}</span></td>
				</tr>
				{reg_option3.REG_OPTION3_DATA}
				</table>
			</td>
			<!-- END reg_option3 -->
		</tr>
		<!-- BEGIN reg_unregister -->
		<tr><td class="row3 row-center" colspan="3"><input type="button" class="mainoption" value="{reg_unregister.REG_SELF_NAME}" onclick="self.location.href='{reg_unregister.REG_SELF_URL}'" onmouseover="'{reg_unregister.REG_SELF_NAME}'" /></td></tr>
		<!-- END reg_unregister -->
		</table>
		{REG_S_HIDDEN}
		</form>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>