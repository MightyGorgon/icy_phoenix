<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_users_auth.png" alt="{L_CMS_PERMISSIONS}" title="{L_CMS_PERMISSIONS}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_CMS_PERMISSIONS}</h1><span class="genmed">{L_CMS_AUTH_TEXT}</span></td>
</tr>
</table>

<!-- IF S_INTRO -->
<table class="forumline">
<tr><th colspan="2">{L_CMS_PERMISSIONS}</th></tr>
<!--
<tr class="row1 row1h">
	<td class="row1" style="width: 200px;"><a href="{U_CMS_BASE_URL}&amp;pmode=setting_admin_global"><b>{L_CMS_AUTH_ADMINS}</b></a></td>
	<td class="row1">{L_CMS_AUTH_ADMINS_EXPLAIN}</td>
</tr>
-->
<tr class="row1 row1h">
	<td class="row1" style="width: 200px;"><a href="{U_CMS_BASE_URL}&amp;pmode=setting_cms_user_global"><b>{L_CMS_AUTH_CMS_USERS_GROUPS}</b></a></td>
	<td class="row1">{L_CMS_AUTH_CMS_USERS_GROUPS_EXPLAIN}</td>
</tr>
<tr class="row1 row1h">
	<td class="row1"><a href="{U_CMS_BASE_URL}&amp;pmode=setting_cms_user_local"><b>{L_CMS_AUTH_CMS_USERS_GROUPS_LOCAL}</b></a></td>
	<td class="row1">{L_CMS_AUTH_CMS_USERS_GROUPS_LOCAL_EXPLAIN}</td>
</tr>
<!--
<tr class="row1 row1h">
	<td class="row1"><a href="{U_CMS_BASE_URL}&amp;pmode=setting_mod_global"><b>{L_CMS_AUTH_MODS}</b></a></td>
	<td class="row1">{L_CMS_AUTH_MODS_EXPLAIN}</td>
</tr>
-->
<tr class="row1 row1h">
	<td class="row1"><a href="{U_CMS_BASE_URL}&amp;pmode=setting_plugins_user_global"><b>{L_CMS_AUTH_PLUGINS_USERS_GROUPS}</b></a></td>
	<td class="row1">{L_CMS_AUTH_PLUGINS_USERS_GROUPS_EXPLAIN}</td>
</tr>
<tr class="row1 row1h">
	<td class="row1"><a href="{U_CMS_BASE_URL}&amp;pmode=setting_user_global"><b>{L_CMS_AUTH_USERS_GROUPS}</b></a></td>
	<td class="row1">{L_CMS_AUTH_USERS_GROUPS_EXPLAIN}</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat tdalignc" colspan="2">&nbsp;</td></tr>
</table>

<br clear="all" />

<table class="forumline">
<tr><th colspan="2">{L_CMS_PERMISSIONS_ROLES}</th></tr>
<!--
<tr class="row1 row1h">
	<td class="row1" style="width: 200px;"><a href="{U_CMS_BASE_URL}&amp;rmode=admin_roles&amp;roles_admin=1"><b>{L_ACP_ADMIN_ROLES}</b></a></td>
	<td class="row1">{L_ACP_ADMIN_ROLES_EXPLAIN}</td>
</tr>
-->
<tr class="row1 row1h">
	<td class="row1" style="width: 200px;"><a href="{U_CMS_BASE_URL}&amp;rmode=cms_roles&amp;roles_admin=1"><b>{L_ACP_CMS_ROLES}</b></a></td>
	<td class="row1">{L_ACP_CMS_ROLES_EXPLAIN}</td>
</tr>
<!--
<tr class="row1 row1h">
	<td class="row1"><a href="{U_CMS_BASE_URL}&amp;rmode=mod_roles&amp;roles_admin=1"><b>{L_ACP_MOD_ROLES}</b></a></td>
	<td class="row1">{L_ACP_MOD_ROLES_EXPLAIN}</td>
</tr>
-->
<tr class="row1 row1h">
	<td class="row1"><a href="{U_CMS_BASE_URL}&amp;rmode=plugins_roles&amp;roles_admin=1"><b>{L_ACP_PLUGINS_ROLES}</b></a></td>
	<td class="row1">{L_ACP_PLUGINS_ROLES_EXPLAIN}</td>
</tr>
<tr class="row1 row1h">
	<td class="row1"><a href="{U_CMS_BASE_URL}&amp;rmode=user_roles&amp;roles_admin=1"><b>{L_ACP_USER_ROLES}</b></a></td>
	<td class="row1">{L_ACP_USER_ROLES_EXPLAIN}</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat tdalignc" colspan="2">&nbsp;</td></tr>
</table>
<!-- ENDIF -->

<!-- IF S_SELECT_VICTIM -->

<br clear="all" />
<h2 style="text-align: left;">{L_TITLE}</h2>
<br clear="all" />

<!-- IF S_FORUM_NAMES --><p><strong>{L_ITEMS_SELECTED}:</strong> {FORUM_NAMES}</p><!-- ENDIF -->
<br clear="all" />

<!-- IF S_SELECT_CMS -->

	<form id="select_victim_l" method="post" action="{U_ACTION}">

	<fieldset class="permissions phpbb">
		<legend class="phpbb">{L_LOOK_UP_CMS_L}</legend>
		<dl>
			<dt><label for="cmsl">{L_LOOK_UP_CMS_L}:</label><br /><br /><span class="gensmall">{L_LOOK_UP_CMS_L_EXPLAIN}</span><br />&nbsp;</dt>
			<dd style="text-align: right;">
				<select id="cmsl" name="forum_id[]">{S_CMS_L_OPTIONS}</select>
				{S_HIDDEN_FIELDS}
				{S_FORM_TOKEN}
				<input type="hidden" name="type" value="cmsl_" />
				<input type="hidden" name="id_type" value="layout" />
				<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
			</dd>
		</dl>
	</fieldset>

	</form>

	<form id="select_victim_ls" method="post" action="{U_ACTION}">

	<fieldset class="permissions phpbb">
		<legend class="phpbb">{L_LOOK_UP_CMS_S}</legend>
		<dl>
			<dt><label for="cmss">{L_LOOK_UP_CMS_S}:</label><br /><br /><span class="gensmall">{L_LOOK_UP_CMS_S_EXPLAIN}</span><br />&nbsp;</dt>
			<dd style="text-align: right;">
				<select id="cmss" name="forum_id[]">{S_CMS_S_OPTIONS}</select>
				{S_HIDDEN_FIELDS}
				{S_FORM_TOKEN}
				<input type="hidden" name="type" value="cmss_" />
				<input type="hidden" name="id_type" value="layout_special" />
				<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
			</dd>
		</dl>
	</fieldset>

	</form>

	<form id="select_victim_b" method="post" action="{U_ACTION}">

	<fieldset class="permissions phpbb">
		<legend class="phpbb">{L_LOOK_UP_CMS_B}</legend>
		<dl>
			<dt><label for="cmsb">{L_LOOK_UP_CMS_B}:</label><br /><br /><span class="gensmall">{L_LOOK_UP_CMS_B_EXPLAIN}</span><br />&nbsp;</dt>
			<dd style="text-align: right;">
				<select id="cmsb" name="forum_id[]">{S_CMS_B_OPTIONS}</select>
				{S_HIDDEN_FIELDS}
				{S_FORM_TOKEN}
				<input type="hidden" name="type" value="cmsb_" />
				<input type="hidden" name="id_type" value="block" />
				<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
			</dd>
		</dl>
	</fieldset>

	</form>

<!-- ELSEIF (S_SELECT_USER and S_CAN_SELECT_USER) or (S_SELECT_GROUP and S_CAN_SELECT_GROUP) -->

	<!-- IF S_SELECT_USER and S_CAN_SELECT_USER -->
	<form id="select_victim_user" method="post" action="{U_ACTION_USERS}">

	<fieldset class="permissions phpbb">
		<legend class="phpbb">{L_LOOK_UP_USER}</legend>
		<dl>
			<dt><label for="username">{L_FIND_USERNAME}:</label></dt>
			<dd style="text-align: right;">
				<img src="{IMG_USER_SEARCH}" alt="{L_FIND_USERNAME}" title="{L_FIND_USERNAME}" style="cursor: pointer; vertical-align: middle;" onclick="window.open('{U_FIND_USERNAME}&amp;target_form_name=select_victim_user', '_search', 'width=400,height=250,resizable=yes'); return false;" />&nbsp;<input type="text" name="username[]" id="username" maxlength="255" size="25" class="post" />&nbsp;
				{S_HIDDEN_FIELDS}
				{S_FORM_TOKEN}
				<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
			</dd>
		</dl>
	</fieldset>

	</form>
	<!-- ENDIF -->

	<!-- IF S_SELECT_GROUP and S_CAN_SELECT_GROUP -->
	<form id="select_victim_group" method="post" action="{U_ACTION_GROUPS}">

	<fieldset class="permissions phpbb">
		<legend class="phpbb">{L_LOOK_UP_GROUP}</legend>
		<dl>
			<dt><label for="group">{L_LOOK_UP_GROUP}:</label></dt>
			<dd style="text-align: right;">
				<select name="group_id[]" id="group">{S_GROUP_OPTIONS}</select>&nbsp;
				{S_HIDDEN_FIELDS}
				{S_FORM_TOKEN}
				<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
			</dd>
		</dl>
	</fieldset>

	</form>
	<!-- ENDIF -->

<!-- ELSEIF S_SELECT_USERGROUP -->

	<div id="main">
	<div style="float: left; width: 48%;">

	<!-- IF S_CAN_SELECT_USER -->

		<h2 style="text-align: left;">{L_USERS}</h2>

		<form id="users" method="post" action="{U_ACTION}">

		<fieldset class="permissions phpbb">
			<legend class="phpbb">{L_MANAGE_USERS}</legend>
			<dl>
				<dd class="full"><select style="width: 100%;" name="user_id[]" multiple="multiple" size="5" style="min-height: 200px;">{S_DEFINED_USER_OPTIONS}</select></dd>
				<!-- IF S_ALLOW_ALL_SELECT --><dd class="full" style="text-align: right;"><label><input type="checkbox" class="radio" name="all_users" value="1" /> {L_ALL_USERS}</label></dd><!-- ENDIF -->
			</dl>
		</fieldset>

		<fieldset class="permissions phpbb quick">
			{S_HIDDEN_FIELDS}
			{S_FORM_TOKEN}
			<input type="submit" class="liteoption" name="paction[delete]" value="{L_REMOVE_PERMISSIONS}" style="width: 46% !important;" /> &nbsp; <input class="mainoption" type="submit" name="submit_edit_options" value="{L_EDIT_PERMISSIONS}" style="width: 46% !important;" />
		</fieldset>
		</form>

		<form id="add_user" method="post" action="{U_ACTION}">

		<fieldset class="permissions phpbb">
			<legend class="phpbb">{L_ADD_USERS}</legend>
			<p>{L_USERNAMES_EXPLAIN}</p>
			<dl>
				<dd class="full"><textarea id="username" name="usernames" rows="5" cols="5" style="width: 100%; height: 60px;"></textarea>&nbsp;<img src="{IMG_USER_SEARCH}" alt="{L_FIND_USERNAME}" title="{L_FIND_USERNAME}" style="cursor: pointer; vertical-align: middle;" onclick="window.open('{U_FIND_USERNAME}&amp;target_form_name=add_user', '_search', 'width=400,height=250,resizable=yes'); return false;" /></dd>
			</dl>
		</fieldset>

		<fieldset class="permissions phpbb quick">
			{S_HIDDEN_FIELDS}
			{S_FORM_TOKEN}
			<input class="mainoption" type="submit" name="submit_add_options" value="{L_ADD_PERMISSIONS}" />
		</fieldset>
		</form>

	<!-- ENDIF -->

	</div>

	<div style="float: right; width: 48%">

	<!-- IF S_CAN_SELECT_GROUP -->

		<h2 style="text-align: left;">{L_USERGROUPS}</h2>

		<form id="groups" method="post" action="{U_ACTION}">

		<fieldset class="permissions phpbb">
			<legend class="phpbb">{L_MANAGE_GROUPS}</legend>
			<dl>
				<dd class="full"><select style="width: 100%;" name="group_id[]" multiple="multiple" size="5" style="min-height: 200px;">{S_DEFINED_GROUP_OPTIONS}</select></dd>
				<!-- IF S_ALLOW_ALL_SELECT --><dd class="full" style="text-align: right;"><label><input type="checkbox" class="radio" name="all_groups" value="1" /> {L_ALL_GROUPS}</label></dd><!-- ENDIF -->
			</dl>
		</fieldset>

		<fieldset class="permissions phpbb quick">
			{S_HIDDEN_FIELDS}
			{S_FORM_TOKEN}
			<input class="liteoption" type="submit" name="paction[delete]" value="{L_REMOVE_PERMISSIONS}" style="width: 46% !important;" /> &nbsp; <input class="mainoption" type="submit" name="submit_edit_options" value="{L_EDIT_PERMISSIONS}" style="width: 46% !important;" />
		</fieldset>
		</form>

		<form id="add_groups" method="post" action="{U_ACTION}">

		<fieldset class="permissions phpbb">
			<legend class="phpbb">{L_ADD_GROUPS}</legend>
			<dl>
				<dd class="full"><select name="group_id[]" style="width: 100%; height: 107px;" multiple="multiple" style="min-height: 200px;">{S_ADD_GROUP_OPTIONS}</select></dd>
			</dl>
		</fieldset>

		<fieldset class="permissions phpbb quick">
			{S_HIDDEN_FIELDS}
			{S_FORM_TOKEN}
			<input type="submit" class="mainoption" name="submit_add_options" value="{L_ADD_PERMISSIONS}" />
		</fieldset>
		</form>

	<!-- ENDIF -->

	</div>
	</div>

<!-- ELSEIF S_SELECT_USERGROUP_VIEW -->

	<div id="main">
	<div style="float: left; width: 48%;">

		<h2 style="text-align: left;">{L_USERS}</h2>

		<form id="users" method="post" action="{U_ACTION}">

		<fieldset class="permissions phpbb">
			<legend class="phpbb">{L_MANAGE_USERS}</legend>
			<dl>
				<dd class="full"><select style="width: 100%;" name="user_id[]" multiple="multiple" size="5" style="min-height: 200px;">{S_DEFINED_USER_OPTIONS}</select></dd>
			</dl>
		</fieldset>

		<fieldset class="permissions phpbb quick">
			{S_HIDDEN_FIELDS}
			{S_FORM_TOKEN}
			<input class="mainoption" type="submit" name="submit" value="{L_VIEW_PERMISSIONS}" />
		</fieldset>
		</form>

		<form id="add_user" method="post" action="{U_ACTION}">

		<fieldset class="permissions phpbb">
			<legend class="phpbb">{L_LOOK_UP_USER}</legend>
			<dl>
				<dt><label for="username">{L_FIND_USERNAME}:</label></dt>
				<dd><input type="text" id="username" name="username[]" /></dd>
				<dd>[ <a href="{U_FIND_USERNAME}" onclick="find_username(this.href); return false;">{L_FIND_USERNAME}</a> ]</dd>
				<dd class="full" style="text-align: left;"><label><input type="checkbox" class="radio" id="anonymous" name="user_id[]" value="{ANONYMOUS_USER_ID}" /> {L_SELECT_ANONYMOUS}</label></dd>
			</dl>
		</fieldset>

		<fieldset class="permissions phpbb quick">
			{S_HIDDEN_FIELDS}
			{S_FORM_TOKEN}
			<input type="submit" name="submit" value="{L_VIEW_PERMISSIONS}" class="mainoption" />
		</fieldset>
		</form>

	</div>

	<div style="float: right; width: 48%">

		<h2 style="text-align: left;">{L_USERGROUPS}</h2>

		<form id="groups" method="post" action="{U_ACTION}">

		<fieldset class="permissions phpbb">
			<legend class="phpbb">{L_MANAGE_GROUPS}</legend>
			<dl>
				<dd class="full"><select style="width: 100%;" name="group_id[]" multiple="multiple" size="5" style="min-height: 200px;">{S_DEFINED_GROUP_OPTIONS}</select></dd>
			</dl>
		</fieldset>

		<fieldset class="permissions phpbb quick">
			{S_HIDDEN_FIELDS}
			{S_FORM_TOKEN}
			<input class="mainoption" type="submit" name="submit" value="{L_VIEW_PERMISSIONS}" />
		</fieldset>
		</form>

		<form id="group" method="post" action="{U_ACTION}">

		<fieldset class="permissions phpbb">
			<legend class="phpbb">{L_LOOK_UP_GROUP}</legend>
			<dl>
				<dt><label for="group_select">{L_LOOK_UP_GROUP}:</label></dt>
				<dd><select name="group_id[]" id="group_select">{S_ADD_GROUP_OPTIONS}</select></dd>
				<dd>&nbsp;</dd>
			</dl>
		</fieldset>

		<fieldset class="permissions phpbb quick">
			{S_HIDDEN_FIELDS}
			{S_FORM_TOKEN}
			<input type="submit" name="submit" value="{L_VIEW_PERMISSIONS}" class="mainoption" />
		</fieldset>
		</form>

	</div>
	</div>

<!-- ENDIF -->

<!-- ENDIF -->

<!-- IF S_VIEWING_PERMISSIONS -->

<p style="text-align: left;">{L_EXPLAIN}</p>

<!-- INCLUDE ../common/cms/cms_permission_mask.tpl -->

<!-- ENDIF -->

<!-- IF S_SETTING_PERMISSIONS -->

<!-- IF S_PERMISSION_DROPDOWN -->
	<form id="pselect" method="post" action="{U_ACTION}">

	<fieldset class="permissions phpbb quick">
		{S_HIDDEN_FIELDS}
		{S_FORM_TOKEN}
		{L_SELECT_TYPE}: <select name="type">{S_PERMISSION_DROPDOWN}</select>&nbsp;&nbsp;<input class="liteoption" type="submit" name="submit" value="{L_GO}" />
	</fieldset>
	</form>
<!-- ENDIF -->

<!-- include tooltip file -->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/tooltip.js"></script>
<script type="text/javascript">
// <![CDATA[
	window.onload = function(){enable_tooltips_select('set-permissions', '{L_ROLE_DESCRIPTION}', 'role')};
// ]]>
</script>

<form id="set-permissions" method="post" action="{U_ACTION}">

{S_HIDDEN_FIELDS}

<!-- INCLUDE ../common/cms/cms_permission_mask.tpl -->

<br clear="all" /><br />

<fieldset class="permissions phpbb quick" style="float: {S_CONTENT_FLOW_END};">
	<input class="mainoption" type="submit" name="paction[apply_all_permissions]" value="{L_APPLY_ALL_PERMISSIONS}" />
	<input class="liteoption" type="button" name="cancel" value="{L_RESET}" onclick="document.forms['set-permissions'].reset(); init_colours(active_pmask + active_fmask);" />
	{S_FORM_TOKEN}
</fieldset>

<br clear="all" /><br />

</form>

<!-- ENDIF -->

<!-- INCLUDE ../common/cms/page_footer.tpl -->
