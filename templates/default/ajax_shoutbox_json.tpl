{"response":{
<!-- BEGIN room_users -->
<!-- IF room_users.S_FIRST_ROW -->"user":[<!-- ENDIF room_users.S_FIRST_ROW -->
{room_users.user}<!-- IF room_users.S_LAST_ROW == false -->,<!-- ENDIF room_users.S_LAST_ROW == false -->
<!-- IF room_users.S_LAST_ROW -->],<!-- ENDIF room_users.S_LAST_ROW -->
<!-- END room_users -->
<!-- BEGIN shouts -->
<!-- IF shouts.S_FIRST_ROW -->"shout":[<!-- ENDIF shouts.S_FIRST_ROW -->
{shouts.shout}<!-- IF shouts.S_LAST_ROW == false -->,<!-- ENDIF shouts.S_LAST_ROW == false -->
<!-- IF shouts.S_LAST_ROW -->],<!-- ENDIF shouts.S_LAST_ROW -->
<!-- END shouts -->
<!-- BEGIN online_list -->
<!-- IF online_list.S_FIRST_ROW -->"online":[<!-- ENDIF online_list.S_FIRST_ROW -->
{online_list.user}<!-- IF online_list.S_LAST_ROW == false -->,<!-- ENDIF online_list.S_LAST_ROW == false -->
<!-- IF online_list.S_LAST_ROW -->],<!-- ENDIF online_list.S_LAST_ROW -->
<!-- END online_list -->
<!-- BEGIN online_stats -->
"onstats":{
"total":{online_stats.TOTAL},
"guests":{online_stats.GUESTS},
"reg":{online_stats.REG},
"sig":"{online_stats.SIG}"
},
<!-- END online_stats -->
"status":{
"error_status":{ERROR_STATUS},
"error_msg":"{ERROR_MSG}"
}}}
