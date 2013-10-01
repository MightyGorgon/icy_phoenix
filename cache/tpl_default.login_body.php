<?php

// eXtreme Styles mod cache. Generated on Tue, 01 Oct 2013 13:50:25 +0000 (time = 1380635425)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php  $this->set_filename('xs_include_f4082f40849fce8e74bea3619ed461bb', 'overall_header.tpl', true);  $this->pparse('xs_include_f4082f40849fce8e74bea3619ed461bb');  ?>

<form action="<?php echo isset($this->vars['S_LOGIN_ACTION']) ? $this->vars['S_LOGIN_ACTION'] : $this->lang('S_LOGIN_ACTION'); ?>" method="post">

<?php echo isset($this->vars['IMG_THL']) ? $this->vars['IMG_THL'] : $this->lang('IMG_THL'); ?><?php echo isset($this->vars['IMG_THC']) ? $this->vars['IMG_THC'] : $this->lang('IMG_THC'); ?><span class="forumlink"><?php echo isset($this->vars['L_ENTER_PASSWORD']) ? $this->vars['L_ENTER_PASSWORD'] : $this->lang('L_ENTER_PASSWORD'); ?></span><?php echo isset($this->vars['IMG_THR']) ? $this->vars['IMG_THR'] : $this->lang('IMG_THR'); ?><table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1g row-center" width="150" style="padding: 30px; width: 150px;"><img src="images/icy_phoenix_small.png" alt="" /></td>
	<td class="row1g" style="padding: 30px;">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="left" width="120" nowrap="nowrap" style="width: 120px; padding-bottom: 10px;"><span class="gen"><?php echo isset($this->vars['L_USERNAME']) ? $this->vars['L_USERNAME'] : $this->lang('L_USERNAME'); ?>:</span></td>
			<td align="left" style="padding-bottom: 10px;"><input type="text" name="username" class="post" size="32" maxlength="40" value="<?php echo isset($this->vars['USERNAME']) ? $this->vars['USERNAME'] : $this->lang('USERNAME'); ?>" /></td>
		</tr>
		<tr>
			<td align="left" nowrap="nowrap"><span class="gen"><?php echo isset($this->vars['L_PASSWORD']) ? $this->vars['L_PASSWORD'] : $this->lang('L_PASSWORD'); ?>:</span></td>
			<td align="left"><input type="password" name="password" class="post" size="32" maxlength="32" /></td>
		</tr>
		<tr>
			<td align="left">&nbsp;</td>
			<td align="left" nowrap="nowrap" style="padding-bottom: 20px;">
				<span class="gensmall">
					<a href="<?php echo isset($this->vars['U_REGISTER']) ? $this->vars['U_REGISTER'] : $this->lang('U_REGISTER'); ?>" class="gensmall"><?php echo isset($this->vars['L_REGISTER']) ? $this->vars['L_REGISTER'] : $this->lang('L_REGISTER'); ?></a>&nbsp;&#8226;&nbsp;<a href="<?php echo isset($this->vars['U_SEND_PASSWORD']) ? $this->vars['U_SEND_PASSWORD'] : $this->lang('U_SEND_PASSWORD'); ?>" class="gensmall"><?php echo isset($this->vars['L_SEND_PASSWORD']) ? $this->vars['L_SEND_PASSWORD'] : $this->lang('L_SEND_PASSWORD'); ?></a><?php if ($this->vars['S_SWITCH_RESEND_ACTIVATION_EMAIL']) {  ?>&nbsp;&#8226;&nbsp;<a href="<?php echo isset($this->vars['U_RESEND_ACTIVATION_EMAIL']) ? $this->vars['U_RESEND_ACTIVATION_EMAIL'] : $this->lang('U_RESEND_ACTIVATION_EMAIL'); ?>" class="gensmall"><?php echo isset($this->vars['L_RESEND_ACTIVATION_EMAIL']) ? $this->vars['L_RESEND_ACTIVATION_EMAIL'] : $this->lang('L_RESEND_ACTIVATION_EMAIL'); ?></a><?php } ?>
				</span>
			</td>
		</tr>
		<?php

$switch_login_type_count = ( isset($this->_tpldata['switch_login_type.']) ) ? sizeof($this->_tpldata['switch_login_type.']) : 0;
for ($switch_login_type_i = 0; $switch_login_type_i < $switch_login_type_count; $switch_login_type_i++)
{
 $switch_login_type_item = &$this->_tpldata['switch_login_type.'][$switch_login_type_i];
 $switch_login_type_item['S_ROW_COUNT'] = $switch_login_type_i;
 $switch_login_type_item['S_NUM_ROWS'] = $switch_login_type_count;

?>
		<tr>
			<td align="left">&nbsp;</td>
			<td align="left" nowrap="nowrap"><span class="genmed"><?php echo isset($this->vars['L_STATUS']) ? $this->vars['L_STATUS'] : $this->lang('L_STATUS'); ?>:&nbsp;&nbsp;<input type="radio" name="online_status" value="default" checked="checked" />&nbsp;<?php echo isset($this->vars['L_DEFAULT']) ? $this->vars['L_DEFAULT'] : $this->lang('L_DEFAULT'); ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="online_status" value="hidden" />&nbsp;<?php echo isset($this->vars['L_HIDDEN']) ? $this->vars['L_HIDDEN'] : $this->lang('L_HIDDEN'); ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="online_status" value="visible" />&nbsp;<?php echo isset($this->vars['L_VISIBLE']) ? $this->vars['L_VISIBLE'] : $this->lang('L_VISIBLE'); ?>&nbsp;&nbsp;</span><br /><br /></td>
		</tr>
		<?php

} // END switch_login_type

if(isset($switch_login_type_item)) { unset($switch_login_type_item); } 

?>
		<?php

$switch_allow_autologin_count = ( isset($this->_tpldata['switch_allow_autologin.']) ) ? sizeof($this->_tpldata['switch_allow_autologin.']) : 0;
for ($switch_allow_autologin_i = 0; $switch_allow_autologin_i < $switch_allow_autologin_count; $switch_allow_autologin_i++)
{
 $switch_allow_autologin_item = &$this->_tpldata['switch_allow_autologin.'][$switch_allow_autologin_i];
 $switch_allow_autologin_item['S_ROW_COUNT'] = $switch_allow_autologin_i;
 $switch_allow_autologin_item['S_NUM_ROWS'] = $switch_allow_autologin_count;

?>
		<tr>
			<td align="left">&nbsp;</td>
			<td align="left" nowrap="nowrap" style="padding-bottom: 10px;"><span class="genmed">&nbsp;<input type="checkbox" name="autologin" checked="checked" />&nbsp;<?php echo isset($this->vars['L_AUTOLOGIN']) ? $this->vars['L_AUTOLOGIN'] : $this->lang('L_AUTOLOGIN'); ?></span></td>
		</tr>
		<?php

} // END switch_allow_autologin

if(isset($switch_allow_autologin_item)) { unset($switch_allow_autologin_item); } 

?>
		<tr>
			<td align="left">&nbsp;</td>
			<td align="left" style="padding-bottom: 10px;"><?php echo isset($this->vars['S_HIDDEN_FIELDS']) ? $this->vars['S_HIDDEN_FIELDS'] : $this->lang('S_HIDDEN_FIELDS'); ?><input type="submit" name="login" class="mainoption" value="<?php echo isset($this->vars['L_LOGIN']) ? $this->vars['L_LOGIN'] : $this->lang('L_LOGIN'); ?>" /></td>
		</tr>
		</table>
	</td>
</tr>
</table><?php echo isset($this->vars['IMG_TFL']) ? $this->vars['IMG_TFL'] : $this->lang('IMG_TFL'); ?><?php echo isset($this->vars['IMG_TFC']) ? $this->vars['IMG_TFC'] : $this->lang('IMG_TFC'); ?><?php echo isset($this->vars['IMG_TFR']) ? $this->vars['IMG_TFR'] : $this->lang('IMG_TFR'); ?>

</form>

<?php  $this->set_filename('xs_include_d204d5c4a5a1384a11dece3a110ea58b', 'overall_footer.tpl', true);  $this->pparse('xs_include_d204d5c4a5a1384a11dece3a110ea58b');  ?>