<?php exit; ?>
1412171057
SELECT t.forum_id, t.topic_id, t.topic_title, t.topic_time, t.topic_views, t.topic_replies, t.topic_attachment, n.*, p.*, u.user_id, u.username, u.user_active, u.user_color, u.user_email, u.user_website, u.user_level, u.user_posts, u.user_rank FROM ip_topics AS t, ip_forums AS f, ip_users AS u, ip_news AS n, ip_posts AS p WHERE p.post_id = t.topic_first_post_id AND f.forum_id = t.forum_id AND u.user_id = t.topic_poster AND n.news_id = t.news_id AND t.news_id > 0 ORDER BY t.topic_time DESC LIMIT 0, 10
1458
a:1:{i:0;a:40:{s:8:"forum_id";s:1:"3";s:8:"topic_id";s:1:"2";s:11:"topic_title";s:26:"Sample News Post in Portal";s:10:"topic_time";s:10:"1241136000";s:11:"topic_views";s:1:"0";s:13:"topic_replies";s:2:"28";s:16:"topic_attachment";s:1:"0";s:7:"news_id";s:1:"1";s:13:"news_category";s:4:"News";s:10:"news_image";s:0:"";s:7:"post_id";s:1:"2";s:9:"poster_id";s:1:"2";s:9:"post_time";s:10:"1241136000";s:9:"poster_ip";s:9:"127.0.0.1";s:13:"post_username";s:0:"";s:12:"post_subject";s:26:"Sample News Post in Portal";s:9:"post_text";s:251:"As you can see this Topic is Attached to a News Category which is displayed in the Home Page. You can simply create News Postings in Home Page by Posting a Topic and select the News Category into which the News Message should be posted.

Have Fun...";s:18:"post_text_compiled";s:0:"";s:13:"enable_bbcode";s:1:"1";s:11:"enable_html";s:1:"0";s:14:"enable_smilies";s:1:"1";s:25:"enable_autolinks_acronyms";s:1:"1";s:10:"enable_sig";s:1:"0";s:10:"edit_notes";s:0:"";s:14:"post_edit_time";s:10:"1129111805";s:15:"post_edit_count";s:1:"0";s:12:"post_edit_id";s:1:"0";s:15:"post_attachment";s:1:"0";s:13:"post_bluecard";N;s:10:"post_likes";s:1:"0";s:11:"post_images";s:0:"";s:7:"user_id";s:1:"2";s:8:"username";s:5:"Vende";s:11:"user_active";s:1:"1";s:10:"user_color";s:7:"#dd2222";s:10:"user_email";s:14:"vende@thiel.fr";s:12:"user_website";s:0:"";s:10:"user_level";s:1:"1";s:10:"user_posts";s:2:"30";s:9:"user_rank";s:1:"1";}}