<?php exit; ?>
1411832049
SELECT aa.forum_id, g.group_id, g.group_name, g.group_color FROM ip_auth_access aa, ip_user_group ug, ip_groups g WHERE aa.auth_mod = 1 AND g.group_single_user = 0 AND g.group_type <> 2 AND ug.group_id = aa.group_id AND g.group_id = aa.group_id GROUP BY g.group_id, g.group_name, aa.forum_id ORDER BY aa.forum_id, g.group_id
379
a:3:{i:0;a:4:{s:8:"forum_id";s:1:"4";s:8:"group_id";s:1:"4";s:10:"group_name";s:12:"Modérateurs";s:11:"group_color";s:7:"#206cac";}i:1;a:4:{s:8:"forum_id";s:1:"7";s:8:"group_id";s:1:"4";s:10:"group_name";s:12:"Modérateurs";s:11:"group_color";s:7:"#206cac";}i:2;a:4:{s:8:"forum_id";s:1:"7";s:8:"group_id";s:1:"7";s:10:"group_name";s:5:"Staff";s:11:"group_color";s:7:"#504de3";}}