<?php

// eXtreme Styles mod cache. Generated on Tue, 01 Oct 2013 13:42:23 +0000 (time = 1380634943)

if (!defined('IN_ICYPHOENIX')) exit;

?><script type="text/javascript">
// <![CDATA[
function checkSearch()
{
	if (document.search_block.search_engine.value == 'google')
	{
		window.open('http://www.google.com/search?q=' + document.search_block.search_keywords.value, '_google', '');
		return false;
	}
	else
	{
		return true;
	}
}
// ]]>
</script>

<form name="search_block" method="post" action="<?php echo isset($this->vars['U_SEARCH']) ? $this->vars['U_SEARCH'] : $this->lang('U_SEARCH'); ?>" onsubmit="return checkSearch()">
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr><td align="center"><br /><span class="gensmall"><?php echo isset($this->vars['L_SEARCH2']) ? $this->vars['L_SEARCH2'] : $this->lang('L_SEARCH2'); ?></span></td></tr>
	<tr><td align="center"><input class="post search" type="text" name="search_keywords" size="20" onclick="if(this.value=='<?php echo isset($this->vars['L_SEARCH']) ? $this->vars['L_SEARCH'] : $this->lang('L_SEARCH'); ?>')this.value='';" onblur="if(this.value=='')this.value='<?php echo isset($this->vars['L_SEARCH']) ? $this->vars['L_SEARCH'] : $this->lang('L_SEARCH'); ?>';" value="<?php echo isset($this->vars['L_SEARCH']) ? $this->vars['L_SEARCH'] : $this->lang('L_SEARCH'); ?>" /></td></tr>
	<tr><td align="center"><span class="gensmall"><?php echo isset($this->vars['L_SEARCH_AT']) ? $this->vars['L_SEARCH_AT'] : $this->lang('L_SEARCH_AT'); ?></span></td></tr>
	<tr>
		<td align="center"><select class="post" name="search_engine">
			<option value="site"><?php echo isset($this->vars['L_FORUM_OPTION']) ? $this->vars['L_FORUM_OPTION'] : $this->lang('L_FORUM_OPTION'); ?></option>
			<option value="google">Google</option>
		</select></td>
	</tr>
	<tr><td align="center"><a href="<?php echo isset($this->vars['U_SEARCH']) ? $this->vars['U_SEARCH'] : $this->lang('U_SEARCH'); ?>" class="gensmall"><?php echo isset($this->vars['L_ADVANCED_SEARCH']) ? $this->vars['L_ADVANCED_SEARCH'] : $this->lang('L_ADVANCED_SEARCH'); ?></a></td></tr>
	<tr><td align="center"><br /><input class="mainoption" type="submit" value="<?php echo isset($this->vars['L_SEARCH']) ? $this->vars['L_SEARCH'] : $this->lang('L_SEARCH'); ?>" /><br /><br /></td></tr>
</table>
<input type="hidden" name="search_fields" value="all" />
<input type="hidden" name="show_results" value="topics" />
</form>