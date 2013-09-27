<?php

// eXtreme Styles mod cache. Generated on Fri, 27 Sep 2013 15:34:12 +0000 (time = 1380296052)

if (!defined('IN_ICYPHOENIX')) exit;

?><table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="190" valign="top">
	<?php echo isset($this->vars['IMG_THL']) ? $this->vars['IMG_THL'] : $this->lang('IMG_THL'); ?><?php echo isset($this->vars['IMG_THC']) ? $this->vars['IMG_THC'] : $this->lang('IMG_THC'); ?><span class="forumlink"><?php echo isset($this->vars['L_LINKS']) ? $this->vars['L_LINKS'] : $this->lang('L_LINKS'); ?></span><?php echo isset($this->vars['IMG_THR']) ? $this->vars['IMG_THR'] : $this->lang('IMG_THR'); ?><table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1g row-center">
		<form name="select_all" action="">
			<br /><div class="gensmall"><div class="center-block"><img src="<?php echo isset($this->vars['U_SITE_LOGO']) ? $this->vars['U_SITE_LOGO'] : $this->lang('U_SITE_LOGO'); ?>" alt="<?php echo isset($this->vars['SITENAME']) ? $this->vars['SITENAME'] : $this->lang('SITENAME'); ?>" /></div><br />
			<?php echo isset($this->vars['L_LINK_US_EXPLAIN']) ? $this->vars['L_LINK_US_EXPLAIN'] : $this->lang('L_LINK_US_EXPLAIN'); ?></div><br />
			<textarea cols="15" rows="2" class="post" style="width: 160px" readonly="readonly" name="text_area" onclick="this.form.text_area.focus();this.form.text_area.select();"><?php echo isset($this->vars['LINK_US_SYNTAX']) ? $this->vars['LINK_US_SYNTAX'] : $this->lang('LINK_US_SYNTAX'); ?></textarea>
		</form>
		</td>
	</tr>
	</table><?php echo isset($this->vars['IMG_TFL']) ? $this->vars['IMG_TFL'] : $this->lang('IMG_TFL'); ?><?php echo isset($this->vars['IMG_TFC']) ? $this->vars['IMG_TFC'] : $this->lang('IMG_TFC'); ?><?php echo isset($this->vars['IMG_TFR']) ? $this->vars['IMG_TFR'] : $this->lang('IMG_TFR'); ?>
	<?php

$lock_count = ( isset($this->_tpldata['lock.']) ) ? sizeof($this->_tpldata['lock.']) : 0;
for ($lock_i = 0; $lock_i < $lock_count; $lock_i++)
{
 $lock_item = &$this->_tpldata['lock.'][$lock_i];
 $lock_item['S_ROW_COUNT'] = $lock_i;
 $lock_item['S_NUM_ROWS'] = $lock_count;

?>
	<?php

$logout_count = ( isset($lock_item['logout.']) ) ? sizeof($lock_item['logout.']) : 0;
for ($logout_i = 0; $logout_i < $logout_count; $logout_i++)
{
 $logout_item = &$lock_item['logout.'][$logout_i];
 $logout_item['S_ROW_COUNT'] = $logout_i;
 $logout_item['S_NUM_ROWS'] = $logout_count;

?>
	<?php echo isset($this->vars['IMG_THL']) ? $this->vars['IMG_THL'] : $this->lang('IMG_THL'); ?><?php echo isset($this->vars['IMG_THC']) ? $this->vars['IMG_THC'] : $this->lang('IMG_THC'); ?><span class="forumlink"><?php echo isset($this->vars['L_LOGIN']) ? $this->vars['L_LOGIN'] : $this->lang('L_LOGIN'); ?></span><?php echo isset($this->vars['IMG_THR']) ? $this->vars['IMG_THR'] : $this->lang('IMG_THR'); ?><table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1g-left" width="190">
		<form method="post" action="<?php echo isset($this->vars['S_LOGIN_ACTION']) ? $this->vars['S_LOGIN_ACTION'] : $this->lang('S_LOGIN_ACTION'); ?>">
			<?php echo isset($this->vars['L_LINK_REGISTER_GUEST_RULE']) ? $this->vars['L_LINK_REGISTER_GUEST_RULE'] : $this->lang('L_LINK_REGISTER_GUEST_RULE'); ?><br /><br />
			<div class="center-block-text">
				<?php echo isset($this->vars['L_USERNAME']) ? $this->vars['L_USERNAME'] : $this->lang('L_USERNAME'); ?>:<br /><input class="post" type="text" name="username" size="24" maxlength="40" value="" /><br />
				<?php echo isset($this->vars['L_PASSWORD']) ? $this->vars['L_PASSWORD'] : $this->lang('L_PASSWORD'); ?>:<br /><input class="post" type="password" name="password" size="24" maxlength="32" /><br /><br />
				<span class="gensmall">&nbsp;<input type="checkbox" name="autologin" />&nbsp;<?php echo isset($this->vars['L_REMEMBER_ME']) ? $this->vars['L_REMEMBER_ME'] : $this->lang('L_REMEMBER_ME'); ?>&nbsp;</span><br /><br />
				<input type="hidden" name="redirect" value="<?php echo isset($this->vars['U_SITE_LINKS']) ? $this->vars['U_SITE_LINKS'] : $this->lang('U_SITE_LINKS'); ?>" /><input type="submit" name="login" class="mainoption" value="<?php echo isset($this->vars['L_LOGIN']) ? $this->vars['L_LOGIN'] : $this->lang('L_LOGIN'); ?>" /><br /><br />
			</div>
		</form>
		</td>
	</tr>
	</table><?php echo isset($this->vars['IMG_TFL']) ? $this->vars['IMG_TFL'] : $this->lang('IMG_TFL'); ?><?php echo isset($this->vars['IMG_TFC']) ? $this->vars['IMG_TFC'] : $this->lang('IMG_TFC'); ?><?php echo isset($this->vars['IMG_TFR']) ? $this->vars['IMG_TFR'] : $this->lang('IMG_TFR'); ?>
	<?php

} // END logout

if(isset($logout_item)) { unset($logout_item); } 

?>

	<?php

$submit_count = ( isset($lock_item['submit.']) ) ? sizeof($lock_item['submit.']) : 0;
for ($submit_i = 0; $submit_i < $submit_count; $submit_i++)
{
 $submit_item = &$lock_item['submit.'][$submit_i];
 $submit_item['S_ROW_COUNT'] = $submit_i;
 $submit_item['S_NUM_ROWS'] = $submit_count;

?>
	<form name="linkdata" method="post" action="<?php echo isset($this->vars['U_LINK_REG']) ? $this->vars['U_LINK_REG'] : $this->lang('U_LINK_REG'); ?>">
	<?php echo isset($this->vars['IMG_THL']) ? $this->vars['IMG_THL'] : $this->lang('IMG_THL'); ?><?php echo isset($this->vars['IMG_THC']) ? $this->vars['IMG_THC'] : $this->lang('IMG_THC'); ?><span class="forumlink"><?php echo isset($this->vars['L_LINK_REGISTER']) ? $this->vars['L_LINK_REGISTER'] : $this->lang('L_LINK_REGISTER'); ?></span><?php echo isset($this->vars['IMG_THR']) ? $this->vars['IMG_THR'] : $this->lang('IMG_THR'); ?><table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1g-left" style="padding: 2px;">
			<?php echo isset($this->vars['L_LINK_REGISTER_RULE']) ? $this->vars['L_LINK_REGISTER_RULE'] : $this->lang('L_LINK_REGISTER_RULE'); ?><br /><br />
			<b><?php echo isset($this->vars['L_LINK_TITLE']) ? $this->vars['L_LINK_TITLE'] : $this->lang('L_LINK_TITLE'); ?></b><br /><input class="post" type="text" name="link_title" value="" size="15" maxlength="20" style="width: 160px" /><br /><br />
			<b><?php echo isset($this->vars['L_LINK_URL']) ? $this->vars['L_LINK_URL'] : $this->lang('L_LINK_URL'); ?></b><br /><input class="post" type="text" name="link_url" value="http://" size="15" maxlength="100" style="width: 160px" /><br /><br />
			<b><?php echo isset($this->vars['L_LINK_LOGO_SRC']) ? $this->vars['L_LINK_LOGO_SRC'] : $this->lang('L_LINK_LOGO_SRC'); ?></b><br /><input class="post" type="text" size="15" maxlength="120" style="width: 160px" name="link_logo_src" value="http://" /><br />[<a href="javascript:void(0);" onclick="var img_src=document.linkdata.link_logo_src.value;if(img_src=='http://' || img_src=='') img_src='images/links/no_logo88a.gif';_preview=window.open(img_src, '_preview', 'toolbar=no,width=200,height=100,top=300,left=300');"><?php echo isset($this->vars['L_PREVIEW']) ? $this->vars['L_PREVIEW'] : $this->lang('L_PREVIEW'); ?></a>]<br /><br />
			<b><?php echo isset($this->vars['L_LINK_CATEGORY']) ? $this->vars['L_LINK_CATEGORY'] : $this->lang('L_LINK_CATEGORY'); ?></b><br />
			<select name="link_category" style="width:160px"><option value="" selected="selected">----------------</option><?php echo isset($this->vars['LINK_CAT_OPTION']) ? $this->vars['LINK_CAT_OPTION'] : $this->lang('LINK_CAT_OPTION'); ?></select><br /><br />
			<b><?php echo isset($this->vars['L_LINK_DESC']) ? $this->vars['L_LINK_DESC'] : $this->lang('L_LINK_DESC'); ?></b><br /><textarea name="link_desc" cols="15" rows="4" class="post" style="width: 160px"></textarea><br /><br />
		</td>
	</tr>
	<tr><td class="cat"><input type="submit" name="addlink" value="<?php echo isset($this->vars['L_SUBMIT']) ? $this->vars['L_SUBMIT'] : $this->lang('L_SUBMIT'); ?>" class="mainoption" /></td></tr>
	</table><?php echo isset($this->vars['IMG_TFL']) ? $this->vars['IMG_TFL'] : $this->lang('IMG_TFL'); ?><?php echo isset($this->vars['IMG_TFC']) ? $this->vars['IMG_TFC'] : $this->lang('IMG_TFC'); ?><?php echo isset($this->vars['IMG_TFR']) ? $this->vars['IMG_TFR'] : $this->lang('IMG_TFR'); ?>
	</form>
	<?php

} // END submit

if(isset($submit_item)) { unset($submit_item); } 

?>
	<?php

} // END lock

if(isset($lock_item)) { unset($lock_item); } 

?>
	</td>
	<td width="7" nowrap="nowrap">&nbsp;</td>
