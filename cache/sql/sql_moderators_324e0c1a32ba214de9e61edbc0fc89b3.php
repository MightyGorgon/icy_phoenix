<?php exit; ?>
1411832049
SELECT aa.forum_id, u.user_id, u.username, u.user_active, u.user_color FROM ip_auth_access aa, ip_user_group ug, ip_groups g, ip_users u WHERE aa.auth_mod = 1 AND g.group_single_user = 1 AND ug.group_id = aa.group_id AND g.group_id = aa.group_id AND u.user_id = ug.user_id GROUP BY u.user_id, u.username, aa.forum_id ORDER BY aa.forum_id, u.user_id
6
a:0:{}