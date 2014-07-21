<!-- BEGIN cashrow -->
<tr>
	<td class="row2"><b><span class="genmed">{cashrow.CASH_NAME}:</span></b></td>
	<td class="row1"><span class="genmed"><b>{cashrow.CASH_AMOUNT}</b></span></td>
</tr>
<!-- END cashrow -->
<!-- BEGIN switch_cashlinkson -->
<tr>
	<td class="row2">&nbsp;</td>
	<td class="row1">
		<span class="genmed">
		<!-- IF cashrow.CASH_AMOUNT --><b>{cashrow.CASH_AMOUNT}</b><!-- ENDIF -->
		<!-- BEGIN cashlinks -->
		[ <a href="{switch_cashlinkson.cashlinks.U_LINK}">{switch_cashlinkson.cashlinks.L_NAME}</a> ]
		<!-- END cashlinks -->
		</span>
	</td>
</tr>
<!-- END switch_cashlinkson -->