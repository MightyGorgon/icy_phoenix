<?php

// eXtreme Styles mod cache. Generated on Tue, 01 Oct 2013 13:44:17 +0000 (time = 1380635057)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php if ($this->vars['S_LOGGED_IN']) {  ?>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
		<br /><?php echo isset($this->vars['AVATAR_IMG']) ? $this->vars['AVATAR_IMG'] : $this->lang('AVATAR_IMG'); ?><br /><br />
		<span class="name"><?php echo isset($this->vars['U_NAME_LINK']) ? $this->vars['U_NAME_LINK'] : $this->lang('U_NAME_LINK'); ?></span><br />
		<span class="gensmall"><?php echo isset($this->vars['LAST_VISIT_DATE']) ? $this->vars['LAST_VISIT_DATE'] : $this->lang('LAST_VISIT_DATE'); ?></span><br />
		<span class="gensmall"><a href="<?php echo isset($this->vars['U_SEARCH_NEW']) ? $this->vars['U_SEARCH_NEW'] : $this->lang('U_SEARCH_NEW'); ?>"><?php echo isset($this->vars['L_NEW_SEARCH']) ? $this->vars['L_NEW_SEARCH'] : $this->lang('L_NEW_SEARCH'); ?></a></span><br /><br />
	</td>
</tr>
</table>
<?php } else { ?>
<form method="post" action="<?php echo isset($this->vars['S_LOGIN_ACTION']) ? $this->vars['S_LOGIN_ACTION'] : $this->lang('S_LOGIN_ACTION'); ?>">
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
	<br />
	<?php echo isset($this->vars['AVATAR_IMG']) ? $this->vars['AVATAR_IMG'] : $this->lang('AVATAR_IMG'); ?><br /><br /><span class="gensmall">
	<input type="hidden" name="redirect" value="<?php echo isset($this->vars['U_PORTAL_NOSID']) ? $this->vars['U_PORTAL_NOSID'] : $this->lang('U_PORTAL_NOSID'); ?>" />
	<?php echo isset($this->vars['L_USERNAME']) ? $this->vars['L_USERNAME'] : $this->lang('L_USERNAME'); ?>:<br />
	<input class="post" type="text" name="username" size="15" />
	<br />
	<?php echo isset($this->vars['L_PASSWORD']) ? $this->vars['L_PASSWORD'] : $this->lang('L_PASSWORD'); ?>:<br />
	<input class="post" type="password" name="password" size="15" />
	<br />
	</span>
	<?php

$switch_allow_autologin_count = ( isset($this->_tpldata['switch_allow_autologin.']) ) ? sizeof($this->_tpldata['switch_allow_autologin.']) : 0;
for ($switch_allow_autologin_i = 0; $switch_allow_autologin_i < $switch_allow_autologin_count; $switch_allow_autologin_i++)
{
 $switch_allow_autologin_item = &$this->_tpldata['switch_allow_autologin.'][$switch_allow_autologin_i];
 $switch_allow_autologin_item['S_ROW_COUNT'] = $switch_allow_autologin_i;
 $switch_allow_autologin_item['S_NUM_ROWS'] = $switch_allow_autologin_count;

?>
	<br />
	<input class="text" type="checkbox" name="autologin" /><span class="gensmall">&nbsp;<?php echo isset($this->vars['L_REMEMBER_ME']) ? $this->vars['L_REMEMBER_ME'] : $this->lang('L_REMEMBER_ME'); ?></span><br />
	<?php

} // END switch_allow_autologin

if(isset($switch_allow_autologin_item)) { unset($switch_allow_autologin_item); } 

?>
	<br />
	<input type="submit" class="mainoption" name="login" value="<?php echo isset($this->vars['L_LOGIN']) ? $this->vars['L_LOGIN'] : $this->lang('L_LOGIN'); ?>" /><br /><br />
	<a href="<?php echo isset($this->vars['U_SEND_PASSWORD']) ? $this->vars['U_SEND_PASSWORD'] : $this->lang('U_SEND_PASSWORD'); ?>" class="gensmall"><?php echo isset($this->vars['L_SEND_PASSWORD']) ? $this->vars['L_SEND_PASSWORD'] : $this->lang('L_SEND_PASSWORD'); ?></a><br /><br />
	<span class="gensmall"><?php echo isset($this->vars['L_REGISTER_NEW_ACCOUNT']) ? $this->vars['L_REGISTER_NEW_ACCOUNT'] : $this->lang('L_REGISTER_NEW_ACCOUNT'); ?></span><br /><br />
	</td>
</tr>
</table>
</form>
<?php } ?>