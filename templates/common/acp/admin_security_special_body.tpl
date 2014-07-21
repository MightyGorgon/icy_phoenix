<form name="special" method="post" action="{ACTION}">
<table class="forumline">
<tr><th colspan="2">{TITLE}</th></tr>
<tr>
	<td class="row1"><span class="genmed">{USE}</span><span class="gensmall">{USE_E}</span></td>
	<td class="row2"><input type="radio" name="ps_use_special" value="1" {USE_Y} /> {L_ENABLED}  <input type="radio" name="ps_use_special" value="0" {USE_N} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{ADMIN}</span><span class="gensmall">{ADMIN_2}</span></td>
	<td class="row2"><input type="text" name="special_admin" class="post" size="5" value="{ADMIN_3}" /><span class="gensmall">{ADMIN_4}{ADMIN_5}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{MOD}</span><span class="gensmall">{MOD_2}</span></td>
	<td class="row2"><input type="text" name="special_mod" class="post" size="5" value="{MOD_3}" /><span class="gensmall">{MOD_4}{MOD_5}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{PER_PAGE}</span></td>
	<td class="row2"><input type="text" name="per_page" class="post" size="5" value="{V_PER_PAGE}" /></td>
</tr>
<tr><td class="row2" colspan="2">{WARNING}</td></tr>
<tr>
	<td class="row1"><span class="genmed">{EXP_1}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_clike_option" value="1" {BAN_1}> {ONE}  <input type="radio" name="ps_clike_option" value="2" {BLOCK_1}> {TWO}  <input type="radio" name="ps_clike_option" value="0" {IGNORE_1}> {THREE}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{EXP_2}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_union_option" value="1" {BAN_2}> {ONE}  <input type="radio" name="ps_union_option" value="2" {BLOCK_2}> {TWO}  <input type="radio" name="ps_union_option" value="0" {IGNORE_2}> {THREE}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{EXP_3}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_sql_option" value="1" {BAN_3}> {ONE}  <input type="radio" name="ps_sql_option" value="2" {BLOCK_3}> {TWO}  <input type="radio" name="ps_sql_option" value="0" {IGNORE_3}> {THREE}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{EXP_4}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_ddos_option" value="1" {BAN_4}> {ONE}  <input type="radio" name="ps_ddos_option" value="2" {BLOCK_4}> {TWO}  <input type="radio" name="ps_ddos_option" value="0" {IGNORE_4}> {THREE}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{LEVEL_EXP}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_ddos_level" value="1" {LEVEL_H}> {L_LEVEL_H}  <input type="radio" name="ps_ddos_level" value="2" {LEVEL_M}> {L_LEVEL_M}  <input type="radio" name="ps_ddos_level" value="3" {LEVEL_L}> {L_LEVEL_L}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{EXP_5}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_file_option" value="1" {BAN_5}> {ONE}  <input type="radio" name="ps_file_option" value="2" {BLOCK_5}> {TWO}  <input type="radio" name="ps_file_option" value="0" {IGNORE_5}> {THREE}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{EXP_6}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_perl_option" value="1" {BAN_6}> {ONE}  <input type="radio" name="ps_perl_option" value="2" {BLOCK_6}> {TWO}  <input type="radio" name="ps_perl_option" value="0" {IGNORE_6}> {THREE}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{EXP_7}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_chr_option" value="1" {BAN_7}> {ONE}  <input type="radio" name="ps_chr_option" value="2" {BLOCK_7}> {TWO}  <input type="radio" name="ps_chr_option" value="0" {IGNORE_7}> {THREE}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{EXP_8}</span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="ps_cback_option" value="1" {BAN_8}> {ONE}  <input type="radio" name="ps_cback_option" value="2" {BLOCK_8}> {TWO}  <input type="radio" name="ps_cback_option" value="0" {IGNORE_8}> {THREE}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_GRANT}</span></td>
	<td class="row2">{ADMIN_L_ONE}</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_DENY}</span></td>
	<td class="row2">{ADMIN_L_TWO}</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{AGENT}</span><br /><span class="gensmall">{AGENT_EXP}</span></td>
	<td class="row2"><input type="text" class="post" value="" name="block_agents" size="40"></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{AGENT_TWO}</span></td>
	<td class="row2">{AGENTS_V}</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{REFERER}</span><br /><span class="gensmall">{REFERER_EXP}</span></td>
	<td class="row2"><input type="text" class="post" value="" name="block_referers" size="40"></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{REFERER_TWO}</span></td>
	<td class="row2">{REFERER_V}</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{BACK_ON}</span></td>
	<td class="row2"><input type="radio" name="backup_on" value="1" {BACK_ON_V_Y} /> {L_ENABLED}  <input type="radio" name="backup_on" value="0" {BACK_ON_V_N} /> {L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{BACK_FOLDER}</span><br /><span class="gensmall">{BACK_FOLDER_E}</span></td>
	<td class="row2"><input type="text" name="backup_folder" class="post" value="{BACK_FOLDER_V}" size="40"></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{BACK_FILE}</span><br /><span class="gensmall">{BACK_FILE_E}</span></td>
	<td class="row2"><input type="text" name="backup_file" class="post" value="{BACK_FILE_V}" size="40"></td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{BACK_TIME}</span></td>
	<td class="row2">{BACK_V}</td>
</tr>
<tr><td class="row2" colspan="2">{BACK_TOTAL}</td></tr>
<tr>
	<th colspan="2">
		<input type="hidden" value="special" name="mode" />
		<input type="hidden" value="save_special" name="action" />
		<input type="submit" value="{L_SUBMIT}" onlick="document.special.submit()" class="mainoption" />
	</th>
</tr>
</table>
</form>

<br clear="all" />