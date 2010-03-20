<tr><th colspan="2">{REG_TITLE}</th></tr>
<tr>
	<td class="row1" colspan="2">
		<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<!-- BEGIN reg_option1 -->
			<td width="33%" class="row2"><input type="button" class="mainoption" value="{REG_OPTION1_NAME}" onclick="self.location.href='{REG_OPTION1_URL}';" onmouseover="'{REG_OPTION1_NAME}'" {REG_OPTION1_READONLY} />&nbsp;<span class="text_green">({REG_OPTION1_COUNT})</span><span class="gensmall">&nbsp;{REG_OPTION1_SLOTS}</span></td>
			<!-- END reg_option1 -->
			<!-- BEGIN reg_option2 -->
			<td width="34%" class="row2"><input type="button" class="mainoption" value="{REG_OPTION2_NAME}" onclick="self.location.href='{REG_OPTION2_URL}';" onmouseover="'{REG_OPTION2_NAME}'" {REG_OPTION2_READONLY} />&nbsp;<span class="text_blue">({REG_OPTION2_COUNT})</span>&nbsp;</td>
			<!-- END reg_option2 -->
			<!-- BEGIN reg_option3 -->
			<td colspan="1" width="33%" class="row2"><input type="button" class="mainoption" value="{REG_OPTION3_NAME}" onclick="self.location.href='{REG_OPTION3_URL}';" onmouseover="'{REG_OPTION3_NAME}'" {REG_OPTION3_READONLY} />&nbsp;<span class="text_red">({REG_OPTION3_COUNT})</span>&nbsp;</td>
			<!-- END reg_option3 -->
		</tr>
		<tr>
			<!-- BEGIN reg_option1 -->
			<td class="row2" valign="top">
				<table width="100%" cellspacing="2" cellpadding="2" border="0">
				<tr>
					<td><span class="gensmall">{REG_HEAD_USERNAME}</span></td>
					<td><span class="gensmall">{REG_HEAD_TIME}</span></td>
				</tr>
				{reg_option1.REG_OPTION1_DATA}
				</table>
			</td>
			<!-- END reg_option1 -->
			<!-- BEGIN reg_option2 -->
			<td class="row2" valign="top">
				<table width="100%" cellspacing="2" cellpadding="2" border="0">
				<tr>
					<td><span class="gensmall">{REG_HEAD_USERNAME}</span></td>
					<td><span class="gensmall">{REG_HEAD_TIME}</span></td>
				</tr>
				{reg_option2.REG_OPTION2_DATA}
				</table>
			</td>
			<!-- END reg_option2 -->
			<!-- BEGIN reg_option3 -->
			<td class="row2" valign="top">
				<table width="100%" cellspacing="2" cellpadding="2" border="0">
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
		<tr><td colspan="3" class="row3 row-center"><input type="button" class="mainoption" value="{reg_unregister.REG_SELF_NAME}" onclick="self.location.href='{reg_unregister.REG_SELF_URL}'" onmouseover="'{reg_unregister.REG_SELF_NAME}'" /></td></tr>
		<!-- END reg_unregister -->
		</table>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>