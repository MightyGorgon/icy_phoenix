<?xml version="1.0" ?>
<response>
	<!-- BEGIN room_users -->
	<user>
		<user_id>{room_users.USER_ID}</user_id>
		<username>{room_users.USERNAME}</username>
		<user_style>{room_users.USER_STYLE}</user_style>
	</user>
	<!-- END room_users -->
	<!-- BEGIN shouts -->
	<shout>
		<id>{shouts.ID}</id>
		<room>{shouts.ROOM}</room>
		<shouter>{shouts.SHOUTER}</shouter>
		<shouter_id>{shouts.SHOUTER_ID}</shouter_id>
		<shouter_link>{shouts.SHOUTER_LINK}</shouter_link>
		<shouter_color>{shouts.SHOUTER_COLOR}</shouter_color>
		<msg>{shouts.MESSAGE}</msg>
		<date>{shouts.DATE}</date>
	</shout>
	<!-- END shouts -->
	<!-- BEGIN online_list -->
	<online>
		<user_id>{online_list.USER_ID}</user_id>
		<username>{online_list.USERNAME}</username>
		<user_style>{online_list.USER_STYLE}</user_style>
		<chat_link>{online_list.CHAT_LINK}</chat_link>
	</online>
	<!-- END online_list -->
	<!-- BEGIN online_stats -->
	<onstats>
		<total>{online_stats.TOTAL}</total>
		<guests>{online_stats.GUESTS}</guests>
		<reg>{online_stats.REG}</reg>
		<sig>{online_stats.SIG}</sig>
	</onstats>
	<!-- END online_stats -->
	<status>
		<error_status>{ERROR_STATUS}</error_status>
		<error_msg>{ERROR_MSG}</error_msg>
	</status>
</response>
