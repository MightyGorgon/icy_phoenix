<?php exit; ?>
1412192308
SELECT a.forum_id, a.auth_read, a.auth_mod FROM ip_auth_access a, ip_user_group ug WHERE ug.user_id = 2 AND ug.user_pending = 0 AND a.group_id = ug.group_id 
406
a:5:{i:0;a:3:{s:8:"forum_id";s:1:"4";s:9:"auth_read";s:1:"1";s:8:"auth_mod";s:1:"0";}i:1;a:3:{s:8:"forum_id";s:1:"5";s:9:"auth_read";s:1:"0";s:8:"auth_mod";s:1:"0";}i:2;a:3:{s:8:"forum_id";s:1:"7";s:9:"auth_read";s:1:"0";s:8:"auth_mod";s:1:"1";}i:3;a:3:{s:8:"forum_id";s:1:"4";s:9:"auth_read";s:1:"0";s:8:"auth_mod";s:1:"1";}i:4;a:3:{s:8:"forum_id";s:1:"7";s:9:"auth_read";s:1:"0";s:8:"auth_mod";s:1:"1";}}