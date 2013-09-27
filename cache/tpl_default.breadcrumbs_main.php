<?php

// eXtreme Styles mod cache. Generated on Fri, 27 Sep 2013 15:34:11 +0000 (time = 1380296051)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php echo isset($this->vars['IMG_TBL']) ? $this->vars['IMG_TBL'] : $this->lang('IMG_TBL'); ?><div class="forumline nav-div">
	<p class="nav-header">
		<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_PORTAL']) ? $this->vars['U_PORTAL'] : $this->lang('U_PORTAL'); ?>"><?php echo isset($this->vars['L_HOME']) ? $this->vars['L_HOME'] : $this->lang('L_HOME'); ?></a><?php echo isset($this->vars['BREADCRUMBS_ADDRESS']) ? $this->vars['BREADCRUMBS_ADDRESS'] : $this->lang('BREADCRUMBS_ADDRESS'); ?>
	</p>
	<div class="nav-links">
		<div class="nav-links-left"><?php if ($this->vars['S_BREADCRUMBS_BOTTOM_LEFT_LINKS']) {  ?><?php echo isset($this->vars['BREADCRUMBS_BOTTOM_LEFT_LINKS']) ? $this->vars['BREADCRUMBS_BOTTOM_LEFT_LINKS'] : $this->lang('BREADCRUMBS_BOTTOM_LEFT_LINKS'); ?><br /><?php } ?><?php echo isset($this->vars['CURRENT_TIME']) ? $this->vars['CURRENT_TIME'] : $this->lang('CURRENT_TIME'); ?></div>
		<?php if ($this->vars['S_LOGGED_IN']) {  ?><a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_PRIVATEMSGS']) ? $this->vars['U_PRIVATEMSGS'] : $this->lang('U_PRIVATEMSGS'); ?>"><?php echo isset($this->vars['PRIVATE_MESSAGE_INFO']) ? $this->vars['PRIVATE_MESSAGE_INFO'] : $this->lang('PRIVATE_MESSAGE_INFO'); ?></a>&nbsp;|&nbsp;<?php } ?><a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_RECENT']) ? $this->vars['U_RECENT'] : $this->lang('U_RECENT'); ?>" class="gensmall"><?php echo isset($this->vars['L_RECENT']) ? $this->vars['L_RECENT'] : $this->lang('L_RECENT'); ?></a>&nbsp;|&nbsp;<a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_SEARCH_UNANSWERED']) ? $this->vars['U_SEARCH_UNANSWERED'] : $this->lang('U_SEARCH_UNANSWERED'); ?>"><?php echo isset($this->vars['L_SEARCH_UNANSWERED']) ? $this->vars['L_SEARCH_UNANSWERED'] : $this->lang('L_SEARCH_UNANSWERED'); ?></a>
		<?php if ($this->vars['S_BREADCRUMBS_BOTTOM_RIGHT_LINKS']) {  ?><br /><?php echo isset($this->vars['BREADCRUMBS_BOTTOM_RIGHT_LINKS']) ? $this->vars['BREADCRUMBS_BOTTOM_RIGHT_LINKS'] : $this->lang('BREADCRUMBS_BOTTOM_RIGHT_LINKS'); ?><?php } ?>
	</div>
</div><?php echo isset($this->vars['IMG_TBR']) ? $this->vars['IMG_TBR'] : $this->lang('IMG_TBR'); ?>
