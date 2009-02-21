## Better to leave these input at the beginning... so they will be inserted as first values into tables
## Roll on version
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('version', '.0.23');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ip_version', '1.2.20.47');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('fap_version', '1.5.0');
## INSERT INTO phpbb_link_config (config_name, config_value) VALUES ('site_logo', 'http://www.mightygorgon.com/images/banners/banner_mightygorgon.gif');
## INSERT INTO phpbb_link_config (config_name, config_value) VALUES ('site_url', 'http://www.mightygorgon.com/');


## `phpbb_acronyms`
##

## `phpbb_adminedit`
##

## `phpbb_album`
##

## `phpbb_album_cat`
##
INSERT INTO `phpbb_album_cat` (`cat_id`, `cat_title`, `cat_desc`, `cat_order`, `cat_view_level`, `cat_upload_level`, `cat_rate_level`, `cat_comment_level`, `cat_edit_level`, `cat_delete_level`, `cat_view_groups`, `cat_upload_groups`, `cat_rate_groups`, `cat_comment_groups`, `cat_edit_groups`, `cat_delete_groups`, `cat_moderator_groups`, `cat_approval`, `cat_parent`, `cat_user_id`) VALUES (1, 'Test Cat 1', 'Test Cat 1', 10, -1, 0, 0, 0, 0, 2, '', '', '', '', '', '', '', 0, 0, 0);

## `phpbb_album_comment`
##

## `phpbb_album_config`
##
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_pics', '1024');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('user_pics_limit', '-1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('mod_pics_limit', '-1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_file_size', '128000');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_width', '1024');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_height', '768');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('rows_per_page', '5');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('cols_per_page', '4');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('fullpic_popup', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('thumbnail_quality', '75');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('thumbnail_size', '125');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('thumbnail_cache', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('sort_method', 'pic_time');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('sort_order', 'DESC');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('jpg_allowed', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('png_allowed', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('gif_allowed', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('desc_length', '512');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hotlink_prevent', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hotlink_allowed', 'mightygorgon.com,icyphoenix.com');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_gallery', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_gallery_private', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_gallery_limit', '-1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_gallery_view', '-1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('rate', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('rate_scale', '10');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('comment', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('gd_version', '2');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('album_version', '.0.56');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_thumb', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_total_pics', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_total_comments', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_comments', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_last_comment', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_last_pic', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_pics', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_recent_in_subcats', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_recent_instead_of_nopics', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('line_break_subcats', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_subcats', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_allow_gallery_mod', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_allow_sub_categories', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_sub_category_limit', '-1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_show_subcats_in_index', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_show_recent_in_subcats', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_show_recent_instead_of_nopics', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_personal_gallery_link', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('album_category_sorting', 'cat_order');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('album_category_sorting_direction', 'ASC');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('album_debug_mode', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_all_in_personal_gallery', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('new_pic_check_interval', '1D');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('index_enable_supercells', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('email_notification', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_download', '2');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_slideshow', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_pic_size_on_thumb', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hon_rate_users', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hon_rate_where', '');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hon_rate_sep', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hon_rate_times', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('disp_watermark_at', '3');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('wut_users', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('use_watermark', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('rate_type', '2');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('disp_rand', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('disp_mostv', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('disp_high', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('disp_late', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('img_cols', '4');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('img_rows', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('midthumb_use', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('midthumb_height', '450');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('midthumb_width', '600');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('midthumb_cache', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_files_to_upload', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_pregenerated_fields', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('dynamic_fields', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('pregenerate_fields', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('propercase_pic_title', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_last_pic_lv', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_pics_approval', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_img_no_gd', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('dynamic_pic_resampling', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_file_size_resampling', '1024000');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('switch_nuffload', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('path_to_bin', './cgi-bin/');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('perl_uploader', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_progress_bar', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('close_on_finish', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_pause', '5');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('simple_format', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('multiple_uploads', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_uploads', '5');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('zip_uploads', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('resize_pic', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('resize_width', '600');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('resize_height', '600');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('resize_quality', '70');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_pics_nav', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_inline_copyright', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('enable_nuffimage', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('enable_sepia_bw', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_allow_avatar_gallery', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_gif_mid_thumb', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('slideshow_script', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_exif', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('quick_thumbs', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('set_memory', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('lb_preview', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('use_old_pics_gen', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_last_comments', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('enable_mooshow', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('invert_nav_arrows', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_otf_link', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_all_pics_link', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_personal_galleries_link', '1');

## `phpbb_album_rate`
##

## `phpbb_attach_quota`
##

## `phpbb_attachments`
##

## `phpbb_attachments_config`
##
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('upload_dir', 'files');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('upload_img', 'images/attach_post.png');
##INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('topic_icon', 'images/icon_clip.png');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('topic_icon', 'images/disk_multiple.png');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('display_order', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('max_filesize', '262144');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('attachment_quota', '52428800');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('max_filesize_pm', '262144');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('max_attachments', '3');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('max_attachments_pm', '1');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('disable_mod', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('allow_pm_attach', '1');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('attachment_topic_review', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('allow_ftp_upload', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('show_apcp', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('attach_version', '2.4.5');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('default_upload_quota', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('default_pm_quota', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('ftp_server', '');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('ftp_path', '');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('download_path', '');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('ftp_user', '');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('ftp_pass', '');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('ftp_pasv_mode', '1');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('img_display_inlined', '1');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('img_max_width', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('img_max_height', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('img_link_width', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('img_link_height', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('img_create_thumbnail', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('img_min_thumb_filesize', '12000');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('img_imagick', '');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('use_gd2', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('wma_autoplay', '0');
INSERT INTO `phpbb_attachments_config` (`config_name`, `config_value`) VALUES ('flash_autoplay', '0');

## `phpbb_attachments_desc`
##

## `phpbb_auth_access`
##

## `phpbb_autolinks`
##

## `phpbb_banlist`
##

## `phpbb_bookmarks`
##

## `phpbb_bots`
##

INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yahoo! Slurp', '<b style="color:#d22;">Yahoo!</b><b style="color:#24b;"> Slurp</b>', 'Yahoo! Slurp', '66.106, 68.142, 72.30, 74.6, 202.160.180');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google', '<b style="color:#24b;">G</b><b style="color:#d22;">o</b><b style="color:#eb0;">o</b><b style="color:#24b;">g</b><b style="color:#393;">l</b><b style="color:#d22;">e</b>', 'Googlebot', '66.249');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MSN', '<b style="color:#468;">MSN</b>', 'msnbot/', '207.66.146, 207.46, 65.54.188, 65.54.246, 65.54.165, 65.55.210, 65.55.213');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('LiveBot', '<b style="color:#468;">LiveBot</b>', 'LiveBot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('AdsBot [Google]', '', 'AdsBot-Google', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google Adsense', '<b style="color:#24b;">G</b><b style="color:#d22;">o</b><b style="color:#eb0;">o</b><b style="color:#24b;">g</b><b style="color:#393;">l</b><b style="color:#d22;">e</b><b style="color:#d22;"> Adsense</b>', 'Mediapartners-Google', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yahoo! DE Slurp', '<b style="color:#d22;">Yahoo!</b><b style="color:#24b;"> DE Slurp</b><b style="color:#888;"> [Bot]</b>', 'Yahoo! DE Slurp', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yahoo MMCrawler', '<b style="color:#d22;">Yahoo!</b><b style="color:#24b;"> MMCrawler</b>', 'Yahoo-MMCrawler/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('YahooSeeker', '', 'YahooSeeker/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google Desktop', '<b style="color:#24b;">G</b><b style="color:#d22;">o</b><b style="color:#eb0;">o</b><b style="color:#24b;">g</b><b style="color:#393;">l</b><b style="color:#d22;">e</b><b style="color:#d22;"> Desktop</b>', 'Google Desktop', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google Feedfetcher', '<b style="color:#24b;">G</b><b style="color:#d22;">o</b><b style="color:#eb0;">o</b><b style="color:#24b;">g</b><b style="color:#393;">l</b><b style="color:#d22;">e</b><b style="color:#d22;"> Feedfetcher</b>', 'Feedfetcher-Google', '72.14.199');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MSN NewsBlogs', '<b style="color:#468;">MSN NewsBlogs</b>', 'msnbot-NewsBlogs/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MSNbot Media', '<b style="color:#468;">MSNbot Media</b>', 'msnbot-media/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Alexa', '', 'ia_archiver', '207.209.238');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Alta Vista', '', 'Scooter/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('AllTheWeb', '', 'alltheweb', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Arianna', '', 'www.arianna.it', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ask Jeeves', '', 'Ask Jeeves', '65.214.44');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ask Jeeves', '', 'teoma', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Baidu [Spider]', '', 'Baiduspider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Become', '', 'BecomeBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Bloglines', '', 'Bloglines/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Charlotte', '', 'Charlotte/1.1', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('DotBot', '', 'dotnetdotcom.org/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('eBay', '', '', '212.222.51');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('eDintorni Crawler', '', 'eDintorni', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Exabot', '', 'Exabot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('FAST Enterprise [Crawler]', '', 'FAST Enterprise Crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('FAST WebCrawler [Crawler]', '', 'FAST-WebCrawler/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('FeedBurner', '', 'FeedBurner/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Feedreader', '', 'Feedreader', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Francis', '', 'http://www.neomo.de/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Gigablast', '', '', '66.154.102, 66.154.103');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Gigabot', '', 'Gigabot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Heise IT-Markt [Crawler]', '', 'heise-IT-Markt-Crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Heritrix [Crawler]', '', 'heritrix/1.', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('JetBot', '', 'Jetbot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('IBM Research', '', 'ibm.com/cs/crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ICCrawler - ICjobs', '', 'ICCrawler - ICjobs', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ichiro [Crawler]', '', 'ichiro/2', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('IEAutoDiscovery', '', 'IEAutoDiscovery', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Indy Library', '', 'Indy Library', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Infoseek', '', 'Infoseek', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Inktomi', '', '', '66.94.229, 66.228.165');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('LookSmart', '', 'MARTINI', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Lycos', '', 'Lycos', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MagpieRSS', '', 'MagpieRSS', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Majestic-12', '', 'MJ12bot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Metager', '', 'MetagerBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Microsoft Research', '', 'MSRBOT', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Netvibes', '', 'Netvibes', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('NewsGatorOnline', '', 'NewsGatorOnline/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('NG-Search', '', 'NG-Search/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Noxtrum [Crawler]', '', 'noxtrumbot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Nutch', '', 'http://lucene.apache.org/nutch/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Nutch/CVS', '', 'NutchCVS/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Omgili', '', 'omgilibot/0.3', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('OmniExplorer', '', 'OmniExplorer_Bot/', '65.19.150');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Online link [Validator]', '', 'online link validator', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Perl Script', '', 'libwww-perl/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Pompos', '', '', '212.27.41');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('psbot [Picsearch]', '', 'psbot/0', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ScoutJet', '', 'http://www.scoutjet.com/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Seekport', '', 'Seekbot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Sensis [Crawler]', '', 'Sensis Web Crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('SEO Crawler [Crawler]', '', 'SEO search Crawler/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Seoma [Crawler]', '', 'Seoma', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('SEOSearch [Crawler]', '', 'SEOsearch/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snap Bot', '', 'Snapbot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snappy', '', 'Snappy/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snarfer', '', 'Snarfer/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Speedy Spider', '', 'Speedy Spider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Steeler [Crawler]', '', 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Synoo', '', 'SynooBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Telekom', '', 'crawleradmin.t-info@telekom.de', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('TurnitinBot', '', 'TurnitinBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Twiceler', '', 'Twiceler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Virgilio', '', '', '212.48.8');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Voyager', '', 'voyager/1.0', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Voila', '', 'VoilaBot', '195.101.94');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3 [Sitesearch]', '', 'W3 SiteSearch Crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3C [Linkcheck]', '', 'W3C-checklink/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3C [Validator]', '', 'W3C_', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WikioFeedBot', '', 'WikioFeedBot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WiseNut', '', 'http://www.WISEnutbot.com', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('YaCy', '', 'yacybot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yandex', '', 'Yandex/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yanga WorldSearch', '', 'Yanga WorldSearch Bot', '');

## `phpbb_captcha_config`
##
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('width', '316');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('height', '61');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('background_color', '#E5ECF9');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('jpeg', '0');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('jpeg_quality', '50');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('pre_letters', '0');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('pre_letters_great', '0');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('font', '0');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('chess', '2');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('ellipses', '2');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('arcs', '2');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('lines', '2');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('image', '0');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('gammacorrect', '1.4');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('foreground_lattice_x', '0');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('foreground_lattice_y', '0');
INSERT INTO `phpbb_captcha_config` (`config_name`, `config_value`) VALUES ('lattice_color', '#FFFFFF');

## `phpbb_categories`
##
INSERT INTO `phpbb_categories` (`cat_id`, `cat_main`, `cat_main_type`, `cat_title`, `cat_order`, `cat_desc`, `icon`) VALUES (1, 0, NULL, 'Test category 1', 10, '', NULL);

## `phpbb_cms_block_position`
##

INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (1, 'header', 'hh', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (2, 'headerleft', 'hl', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (3, 'headercenter', 'hc', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (4, 'footercenter', 'fc', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (5, 'footerright', 'fr', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (6, 'footer', 'ff', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (7, 'ghtop', 'gt', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (8, 'ghbottom', 'gb', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (9, 'ghleft', 'gl', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (10, 'ghright', 'gr', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (11, 'left', 'l', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (12, 'center', 'c', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (13, 'right', 'r', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (14, 'xsnews', 'x', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (15, 'nav', 'n', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (16, 'centerbottom', 'b', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (17, 'left', 'l', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (18, 'center', 'c', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (19, 'xsnews', 'x', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (20, 'nav', 'n', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (21, 'centerbottom', 'b', 2);

## `phpbb_cms_block_variable`
##

INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (1, 0, 'Default Portal', 'Default Portal', 'default_portal', '', '', 1, '@Portal Config');
##INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (2, 0, 'CMS Style', 'Select the CMS Style', 'cms_style', 'Yes,No', '1,0', 3, '@Portal Config');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (3, 0, 'Header width', 'Width of forum-wide left column in pixels', 'header_width', '', '', 1, '@Portal Config');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (4, 0, 'Footer width', 'Width of forum-wide right column in pixels', 'footer_width', '', '', 1, '@Portal Config');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (11, 3, 'Number of recent topics', 'number of topics displayed', 'md_num_recent_topics', '', '', 1, 'recent_topics');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (12, 3, 'Recent Topics Style', 'choose static display or scrolling display', 'md_recent_topics_style', 'Scroll,Static', '1,0', 3, 'recent_topics');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (13, 4, 'Poll Bar Length', 'decrease/increase the value for 1 vote bar length', 'md_poll_bar_length', '', '', 1, 'poll');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (14, 4, 'Poll Forum ID(s)', 'comma delimited', 'md_poll_forum_id', '', '', 1, 'poll');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (15, 8, 'Number of Top Posters', '', 'md_total_poster', '', '', 1, 'top_posters');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (16, 9, 'Search option text', 'Text displayed as the default option', 'md_search_option_text', '', '', 1, 'search');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (17, 11, 'Category to retrieve pics from', 'Enter 0 for all categories or comma delimited entries', 'md_cat_id', '', '', 1, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (18, 11, 'Display from what galleries?', '', 'md_pics_all', 'Public,Public and Personal', '0,1', 3, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (19, 11, 'Random or newest pics?', '', 'md_pics_sort', 'Newest,Random', '0,1', 3, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (20, 11, 'Number of images to display', '', 'md_pics_number', '', '', 1, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (21, 11, 'Number of columns', '', 'md_pics_cols_number', '1,2,3,4,5', '1,2,3,4,5', 3, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (22, 11, 'Number of rows', '', 'md_pics_rows_number', '1,2,3,4', '1,2,3,4', 3, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (23, 12, 'Links -> Style', 'choose static display or scrolling display', 'md_links_style', 'Scroll,Static', '1,0', 3, 'links');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (24, 12, 'Links -> Own (Top)', 'show your own link button above other buttons', 'md_links_own1', 'Yes,No', '1,0', 3, 'links');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (25, 12, 'Links -> Own (Bottom)', 'show your own link button below other buttons', 'md_links_own2', 'Yes,No', '1,0', 3, 'links');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (26, 12, 'Links -> Code', 'show HTML for your own link button', 'md_links_code', 'Yes,No', '1,0', 3, 'links');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (27, 14, 'Maximum Words', 'Select the maximum number of words to display', 'md_wordgraph_words', '', '', 1, 'wordgraph');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (28, 14, 'Enable Word Counts', 'Display the total number of words next to each word', 'md_wordgraph_count', 'Yes,No', '1,0', 3, 'wordgraph');

## `phpbb_cms_blocks`
##

INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (1, 'Nav Links', '', 'hl', 1, 1, 'blocks_imp_nav_links', 0, 0, 0, 0, 0, 0, 0, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (2, 'Nav Links', '', 'l', 1, 1, 'blocks_imp_nav_links', 0, 1, 0, 0, 0, 0, 0, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (3, 'Recent', '', 'l', 3, 0, 'blocks_imp_recent_topics', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (4, 'Poll', '', 'r', 4, 1, 'blocks_imp_poll', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (5, 'Welcome', '', 'c', 1, 1, 'blocks_imp_welcome', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (6, 'News', '', 'x', 1, 1, 'blocks_imp_news', 0, 1, 0, 0, 0, 0, 0, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (7, 'User Block', '', 'r', 1, 1, 'blocks_imp_user_block', 0, 1, 1, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (8, 'Top Posters', '', 'r', 5, 1, 'blocks_imp_top_posters', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (9, 'Search', '', 'l', 1, 1, 'blocks_imp_search', 0, 1, 1, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (10, 'Who is Online', '', 'r', 2, 1, 'blocks_imp_online_users', 0, 1, 1, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (11, 'Album', '', 'l', 2, 1, 'blocks_imp_album', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (12, 'Links', '', 'l', 4, 1, 'blocks_imp_links', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (13, 'Statistics', '', 'r', 3, 1, 'blocks_imp_statistics', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (14, 'Wordgraph', '', 'b', 2, 1, 'blocks_imp_wordgraph', 0, 1, 0, 0, 0, 0, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (15, 'Welcome', '<table class=\\"empty-table\\" width=\\"100%\\" cellspacing=\\"0\\" cellpadding=\\"0\\" border=\\"0\\">\r\n	<tr>\r\n		<td width=\\"5%\\"><img src=\\"images/icy_phoenix_small.png\\" alt=\\"\\" /></td>\r\n		<td width=\\"90%\\" align=\\"center\\"><div class=\\"post-text\\">Welcome To <b>Icy Phoenix</b></div><br /><br /></td>\r\n		<td width=\\"5%\\"><img src=\\"images/icy_phoenix_small_l.png\\" alt=\\"\\" /></td>\r\n	</tr>\r\n</table>', 'c', 2, 1, '', 0, 1, 0, 1, 1, 1, 1, '');

## `phpbb_cms_config`
##

INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (1, 0, 'default_portal', '1');
##INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (2, 0, 'cms_style', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (3, 0, 'header_width', '180');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (4, 0, 'footer_width', '150');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (11, 3, 'md_recent_topics_style', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (12, 3, 'md_num_recent_topics', '10');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (13, 4, 'md_poll_bar_length', '65');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (14, 4, 'md_poll_forum_id', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (15, 8, 'md_total_poster', '5');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (16, 9, 'md_search_option_text', 'Icy Phoenix');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (17, 11, 'md_cat_id', '0');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (18, 11, 'md_pics_all', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (19, 11, 'md_pics_sort', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (20, 11, 'md_pics_number', '3');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (21, 11, 'md_pics_cols_number', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (22, 11, 'md_pics_rows_number', '3');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (23, 12, 'md_links_style', '0');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (24, 12, 'md_links_own1', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (25, 12, 'md_links_own2', '0');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (26, 12, 'md_links_code', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (27, 14, 'md_wordgraph_words', '250');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (28, 14, 'md_wordgraph_count', '1');

## `phpbb_cms_layout`
##

INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `template`, `global_blocks`, `view`, `groups`) VALUES (1, '3 Columns', '3_column.tpl', 0, 0, '');
INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `template`, `global_blocks`, `view`, `groups`) VALUES (2, '2 Columns', '2_column.tpl', 0, 0, '');
##INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `template`, `global_blocks`, `view`, `groups`) VALUES (3, 'Central Block', 'central_block.tpl', 0, 0, '');
##INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `template`, `global_blocks`, `view`, `groups`) VALUES (4, 'Quad Layout', 'quad_layout.tpl', 0, 0, '');
##INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `template`, `global_blocks`, `view`, `groups`) VALUES (5, 'Portal Body', 'portal_body.tpl', 0, 0, '');

## `phpbb_cms_layout_special`
##

INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('1', 'forum', 'forum', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('2', 'viewf', 'viewforum', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('3', 'viewt', 'viewtopic', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('4', 'viewonline', 'viewonline', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('5', 'search', 'search', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('6', 'profile', 'profile', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('7', 'memberlist', 'memberlist', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('8', 'group_cp', 'groupcp', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('9', 'faq', 'faq', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('10', 'rules', 'rules', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('11', 'download', 'dload', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('12', 'album', 'album', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('13', 'links', 'links', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('14', 'statistics', 'statistics', 0, 0, '');
#INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('15', 'site_hist', 'site_hist', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('16', 'calendar', 'calendar', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('17', 'recent', 'recent', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('18', 'referrers', 'referrers', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('19', 'shoutbox', 'shoutbox_max', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('20', 'kb', 'kb', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('21', 'contact_us', 'contact_us', 0, 0, '');

## `phpbb_cms_nav_menu`
##

INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (1, 1, 0, 0, 0, 0, 0, 0, NULL, 'main_links', 'Main Links', 'Main Links Block', NULL, 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (2, 0, 1, 1, 0, 0, 1, 1, './images/menu/application_view_tile.png', 'main_links', 'Main Links', 'Main Links', '', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (3, 0, 1, 2, 0, 0, 1, 2, './images/menu/newspaper.png', 'news', 'News', 'News', '', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (4, 0, 1, 3, 0, 0, 1, 3, './images/menu/information.png', 'info_links', 'Info', 'Info', '', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (5, 0, 1, 4, 0, 0, 1, 4, './images/menu/group.png', 'users_links', 'Users', 'Users & Groups', '', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (6, 0, 1, 5, 0, 0, 1, 5, './images/menu/main.png', 'tools_links', 'Tools', 'Tools', '', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (7, 0, 1, 0, 1, 1, 1, 1, '', '', 'ACP', 'ACP', 'adm/index.php', 0, 4, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (8, 0, 1, 0, 1, 2, 1, 2, '', '', 'CMS', 'CMS', 'cms.php', 0, 4, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (9, 0, 1, 0, 1, 3, 1, 3, '', '', 'Home', 'Home Page', 'index.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (10, 0, 1, 0, 1, 4, 1, 4, '', '', 'Profile', 'Profile', 'profile_main.php', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (11, 0, 1, 0, 1, 5, 1, 5, '', '', 'Forum', 'Forum', 'forum.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (12, 0, 1, 0, 1, 6, 1, 6, '', '', 'FAQ', 'FAQ', 'faq.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (13, 0, 1, 0, 1, 7, 1, 7, '', '', 'Search', 'Search', 'search.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (14, 0, 1, 0, 1, 8, 1, 8, '', '', 'Sitemap', 'Sitemap', 'sitemap.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (15, 0, 1, 0, 1, 9, 1, 9, '', '', 'Album', 'Album', 'album.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (16, 0, 1, 0, 1, 10, 1, 10, '', '', 'Calendar', 'Calendar', 'calendar.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (17, 0, 1, 0, 1, 11, 1, 11, '', '', 'Downloads', 'Downloads', 'dload.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (18, 0, 1, 0, 1, 12, 1, 12, '', '', 'Bookmarks', 'Bookmarks', 'search.php?search_id=bookmarks', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (19, 0, 1, 0, 1, 13, 1, 13, '', '', 'Drafts', 'Drafts', 'drafts.php', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (20, 0, 1, 0, 1, 14, 1, 14, '', '', 'Posted Images', 'Posted Images', 'posted_img_list.php', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (21, 0, 1, 0, 1, 15, 1, 15, '', '', 'Chat', 'Chat', 'ajax_chat.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (22, 0, 1, 0, 1, 16, 1, 16, '', '', 'Links', 'Links', 'links.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (23, 0, 1, 0, 1, 17, 1, 17, '', '', 'Knowledge Base', 'Knowledge Base', 'kb.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (24, 0, 1, 0, 1, 18, 1, 18, '', '', 'Contact Us', 'Contact Us', 'contact_us.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (25, 0, 1, 0, 1, 19, 1, 19, '', '', 'Rules', 'Rules', 'rules.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (26, 0, 1, 0, 1, 21, 1, 21, '', '', 'Sudoku', 'Sudoku', 'sudoku.php', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (27, 0, 1, 0, 2, 22, 1, 1, '', '', 'News Categories', 'News Categories', 'index.php?news=categories', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (28, 0, 1, 0, 2, 23, 1, 2, '', '', 'News Archives', 'News Archives', 'index.php?news=archives', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (29, 0, 1, 0, 2, 24, 1, 3, '', '', 'New Messages', 'New Messages', 'search.php?search_id=newposts', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (30, 0, 1, 0, 2, 25, 1, 4, '', '', 'Unread Posts', 'Unread Posts', 'search.php?search_id=upi2db&s2=new', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (31, 0, 1, 0, 2, 26, 1, 5, '', '', 'Marked Posts', 'Marked Posts', 'search.php?search_id=upi2db&s2=mark', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (32, 0, 1, 0, 2, 27, 1, 6, '', '', 'Permanent Read', 'Permanent Read', 'search.php?search_id=upi2db&s2=perm', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (33, 0, 1, 0, 2, 29, 1, 7, '', '', 'Digests', 'Digests', 'digests.php', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (34, 0, 1, 0, 3, 0, 1, 1, '', '', 'Credits', 'Credits', 'credits.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (35, 0, 1, 0, 3, 31, 1, 2, '', '', 'Http Referrers', 'Http Referrers', 'referrers.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (36, 0, 1, 0, 3, 32, 1, 3, '', '', 'Who Is Online', 'Who Is Online', 'viewonline.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (37, 0, 1, 0, 3, 33, 1, 4, '', '', 'Statistics', 'Statistics', 'statistics.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (38, 0, 1, 0, 3, 35, 1, 5, '', '', 'Delete Cookies', 'Delete Cookies', 'remove_cookies.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (39, 0, 1, 0, 4, 36, 1, 1, '', '', 'Memberlist', 'Memberlist', 'memberlist.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (40, 0, 1, 0, 4, 37, 1, 2, '', '', 'Usergroups', 'Usergroups', 'groupcp.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (41, 0, 1, 0, 4, 38, 1, 3, '', '', 'Ranks', 'Ranks', 'ranks.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (42, 0, 1, 0, 4, 39, 1, 4, '', '', 'Staff', 'Staff', 'memberlist.php?mode=staff', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (43, 0, 1, 0, 5, 40, 1, 1, './images/menu/palette.png', '', 'Style', 'Style', '', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (44, 0, 1, 0, 5, 41, 1, 2, '', '', 'Language', 'Language', '', 0, 1, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (45, 0, 1, 0, 5, 42, 1, 3, './images/menu/feed.png', '', 'RSS News Feeds', 'RSS News Feeds', '', 0, 0, 0);

## `phpbb_config`
##

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('config_id', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('board_disable', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('sitename', 'yourdomain.com');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_desc', 'Icy Phoenix Rocks!');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cookie_name', 'ip_cookie');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cookie_path', '/');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cookie_domain', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cookie_secure', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('session_length', '3600');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_html', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_html_tags', 'b,i,u,pre,table,tr,td');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_bbcode', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_smilies', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_sig', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_namechange', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_theme_create', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_avatar_local', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_avatar_remote', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_avatar_upload', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_confirm', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('override_user_style', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('posts_per_page', '15');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('topics_per_page', '50');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('hot_threshold', '25');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_poll_options', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_sig_chars', '255');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_inbox_privmsgs', '50');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_sentbox_privmsgs', '25');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_savebox_privmsgs', '50');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('board_email_sig', 'Thanks, The Management');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('board_email', 'youraddress@yourdomain.com');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smtp_delivery', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smtp_host', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smtp_username', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smtp_password', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('sendmail_fix', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('require_activation', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('flood_interval', '15');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('board_email_form', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('avatar_filesize', '15000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('avatar_max_width', '120');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('avatar_max_height', '120');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('avatar_path', 'images/avatars');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('avatar_gallery_path', 'images/avatars/gallery');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilies_path', 'images/smiles');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_style', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_dateformat', 'D d M, Y H:i');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('board_timezone', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('prune_enable', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('privmsg_disable', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('gzip_compress', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('coppa_fax', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('coppa_mail', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('record_online_users', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('record_online_date', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('server_name', 'www.mysite.com');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('server_port', '80');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('script_path', '/xs/');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('sig_line', '____________');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('birthday_required', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('birthday_greeting', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_user_age', '100');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('min_user_age', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('birthday_check_day', '7');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bluecard_limit', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bluecard_limit_2', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_user_bancard', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('report_forum', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('index_rating_return', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('min_rates_number', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('rating_max', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_ext_rating', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('large_rating_return_limit', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('check_anon_ip_when_rating', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_rerate', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('header_rating_return_limit', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_time_mode', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_dst_time_lag', '60');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('search_flood_interval', '15');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('rand_seed', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_news', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_item_trim', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_title_trim', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_item_num', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_path', 'images/news');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_rss', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_rss_desc', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_rss_language', 'en_us');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_rss_ttl', '60');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_rss_cat', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_rss_image', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_rss_image_desc', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_rss_item_count', '15');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_rss_show_abstract', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_base_url', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('news_index_file', 'index.php');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_rebuild_end', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_rebuild_pos', '-1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_rebuildcfg_maxmemory', '500');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_rebuildcfg_minposts', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_rebuildcfg_php3only', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_rebuildcfg_php3pps', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_rebuildcfg_php4pps', '8');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_rebuildcfg_timelimit', '240');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_rebuildcfg_timeoverwrite', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_disallow_postcounter', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('dbmtnc_disallow_rebuild', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_avatar_guests_url', 'images/avatars/default_avatars/guest.gif');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_avatar_users_url', 'images/avatars/default_avatars/member.gif');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_gravatars', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('gravatar_rating', 'PG');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('gravatar_default_image', 'images/avatars/default_avatars/member.gif');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_avatar_set', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bin_forum', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('liw_enabled', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('liw_sig_enabled', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('liw_max_width', '500');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('liw_attach_enabled', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xs_news_version', '2.0.3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('board_disable_message', 'Site disabled');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('board_disable_mess_st', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('sitemap_announce_priority', '1.0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('sitemap_default_priority', '0.5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('sitemap_sort', 'DESC');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('sitemap_sticky_priority', '0.75');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('sitemap_topic_limit', '250');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('registration_status', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('registration_closed', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('prune_shouts', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xs_shownav', '17');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_avatar_generator', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('avatar_generator_template_path', 'images/avatars/generator_templates');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('avatar_generator_version', '2.0.2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_login_attempts', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('login_reset_time', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('hidde_last_logon', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('online_time', '60');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('gzip_level', '9');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('gender_required', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilie_columns', '6');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilie_rows', '6');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilie_window_columns', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilie_single_row', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilie_window_rows', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_autologin', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_autologin_time', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('autolink_first', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilies_insert', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('sudoku_version', '1.0.6');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('yahoo_search_savepath', 'cache');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('yahoo_search_additional_urls', 'http://www.icyphoenix.com');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('yahoo_search_compress', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('yahoo_search_compression_level', '9');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_link_bookmarks', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('visit_counter', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('word_graph_max_words', '250');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('word_graph_word_counts', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('search_min_chars', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_registration_ip_check', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('extra_max', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('extra_display', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_max_permanent_topics', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_del_mark', '60');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_del_perm', '120');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_max_mark_posts', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_unread_color', 'aaffcc');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_edit_color', 'ffccaa');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_mark_color', 'ffffaa');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_auto_read', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_edit_as_new', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_last_edit_as_new', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_on', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_edit_topic_first', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_no_group_min_regdays', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_no_group_min_posts', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_no_group_upi2db_on', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_install_time', '1220000000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_delete_old_data', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_max_new_posts', '1000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_version', '3.0.7');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('use_captcha', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('url_rw', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xmas_fx', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('switch_header_table', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('header_table_text', 'Text');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('fast_n_furious', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('new_msgs_mumber', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('index_last_msgs', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('portal_last_msgs', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('online_last_msgs', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('index_shoutbox', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('portal_shoutbox', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('online_shoutbox', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('last_msgs_n', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('last_msgs_x', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('posts_precompiled', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('index_links', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('index_birthday', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_history', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilies_topic_title', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('html_email', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('config_cache', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('admin_protect', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_ftr', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_logins', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('last_logins_n', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('edit_notes', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('edit_notes_n', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('quote_iterations', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('page_gen', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('birthday_viewtopic', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_shoutbox', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('split_ga_ann_sticky', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('email_notification_html', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('select_theme', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('select_lang', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_icons', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_random_quote', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_portal', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_forum', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_viewf', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_viewt', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_faq', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_memberlist', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_group_cp', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_profile', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_search', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_album', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_links', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_calendar', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_attachments', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_download', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_kb', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_ranks', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_statistics', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_recent', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_referrers', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_rules', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_shoutbox', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_viewonline', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_custom_pages', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_ajax_chat', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_ajax_chat_archive', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_portal', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_forum', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_viewf', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_viewt', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_faq', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_memberlist', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_group_cp', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_profile', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_search', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_album', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_links', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_calendar', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_attachments', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_download', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_kb', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_ranks', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_statistics', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_recent', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_referrers', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_rules', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_shoutbox', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_viewonline', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_custom_pages', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_ajax_chat', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_ajax_chat_archive', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('visit_counter_switch', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('emails_only_to_admins', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('no_right_click', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('gd_version', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_img_no_gd', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_pic_size_on_thumb', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('thumbnail_posts', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('thumbnail_cache', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('thumbnail_quality', '75');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('thumbnail_size', '400');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_html_guests', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_email_error', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('switch_header_dropdown', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('switch_poster_info_topic', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('switch_bbcb_active_content', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('thumbnail_lightbox', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_quick_quote', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_xs_version_check', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_all_bbcode', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_digests', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('forum_wordgraph', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_topics', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_stopwords', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_ignore_forums_ids', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_sort_type', 'relev');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_max_topics', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('digests_php_cron', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('digests_last_send_time', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('shoutbox_floodinterval', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('display_shouts', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('stored_shouts', '1000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('shoutbox_refreshtime', '5000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('shout_allow_guest', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xmas_gfx', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('google_bot_detector', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('logs_path', 'logs');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_contact_us', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_contact_us', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_max_new_posts_admin', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_max_new_posts_mod', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('url_rw_guests', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('lofi_bots', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_checks_register', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('inactive_users_memberlists', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_pic_upload', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_postimage_org', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_new_messages_number', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_thanks_topics', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_features', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_rss_forum_icon', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_acronyms', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_autolinks', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_censor', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_topic_view', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smart_header', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('page_title_simple', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_referrers', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('digests_php_cron_lock', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('mg_log_actions', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('active_users_color', '#224455');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('active_users_legend', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bots_color', '#888888');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bots_legend', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_social_bookmarks', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_forums_online_users', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cms_dock', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_drafts', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('main_admin_id', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_mods_edit_admin_posts', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('force_large_caps_mods', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_colorpicker', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('always_show_edit_by', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_new_reply_posting', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_chat_online', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_zebra', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_mods_view_self', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_own_icons', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_thanks_profile', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_thanks_viewtopic', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('index_top_posters', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_upi2db', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('last_user_id', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('write_errors_log', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('write_digests_log', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('no_bump', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('link_this_topic', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cms_style', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_alpha_bar', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('db_log_actions', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_topic_description', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bots_reg_auth', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_global_switch', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_lock', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_queue_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_queue_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_digests_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_digests_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_files_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_files_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_database_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_database_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_cache_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_cache_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_sql_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_sql_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_users_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_users_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_topics_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_topics_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_sessions_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_sessions_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_db_count', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_db_show_begin_for', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_db_show_not_optimized', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('rand_seed_last_update', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('gsearch_guests', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_glt', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_glb', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_glh', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_glf', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_fix', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_fit', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_fib', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vfx', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vft', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vfb', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vtx', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vtt', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vtb', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_nmt', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_nmb', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('adsense_code', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('google_analytics', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('thumbnail_highslide', '1');
## CASH - BEGIN
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_disable', 0);
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_display_after_posts', 1);
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_post_message', 'You earned %s for that post');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_disable_spam_num', 10);
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_disable_spam_time', 24);
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_disable_spam_message', 'You have exceeded the alloted amount of posts and will not earn anything for your post');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_installed', 'yes');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_version', '2.2.3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_adminbig', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_adminnavbar', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('points_name', 'Points');
## CASH - END

## `phpbb_ctracker_config`
##

INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('ipblock_enabled', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('ipblock_logsize', '100');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('auto_recovery', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('vconfirm_guest', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('autoban_mails', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('detect_misconfiguration', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('search_time_guest', '30');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('search_time_user', '20');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('search_count_guest', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('search_count_user', '4');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('massmail_protection', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('reg_protection', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('reg_blocktime', '30');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('reg_lastip', '0.0.0.0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pwreset_time', '20');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('massmail_time', '20');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('spammer_time', '30');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('spammer_postcount', '4');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('spammer_blockmode', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('loginfeature', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_reset_feature', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('reg_last_reg', '1155944976');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('login_history', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('login_history_count', '10');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('login_ip_check', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_validity', '30');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_complex_min', '4');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_complex_mode', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_control', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_complex', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('last_file_scan', '1156000091');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('last_checksum_scan', '1156000082');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('logsize_logins', '100');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('logsize_spammer', '100');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('reg_ip_scan', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('global_message', 'Hello world!');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('global_message_type', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('logincount', '2');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('search_feature_enabled', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('spam_attack_boost', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('spam_keyword_det', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('footer_layout', '6');

## `phpbb_ctracker_ipblocker`
##

INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (1, '*WebStripper*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (2, '*NetMechanic*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (3, '*CherryPicker*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (4, '*EmailCollector*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (5, '*EmailSiphon*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (6, '*WebBandit*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (7, '*EmailWolf*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (8, '*ExtractorPro*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (9, '*SiteSnagger*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (10, '*CheeseBot*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (11, '*ia_archiver*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (12, '*Website Quester*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (13, '*WebZip*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (14, '*moget*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (15, '*WebSauger*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (16, '*WebCopier*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (17, '*WWW-Collector*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (18, '*InfoNaviRobot*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (19, '*Harvest*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (20, '*Bullseye*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (21, '*LinkWalker*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (22, '*LinkextractorPro*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (23, '*Proxy*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (24, '*BlowFish*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (25, '*WebEnhancer*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (26, '*TightTwatBot*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (27, '*LinkScan*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (28, '*WebDownloader*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (29, 'lwp');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (30, '*BruteForce*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (31, 'lwp-*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (32, '*anonym*');

## `phpbb_confirm`
##

## `phpbb_disallow`
##

## `phpbb_extension_groups`
##
INSERT INTO `phpbb_extension_groups` (`group_id`, `group_name`, `cat_id`, `allow_group`, `download_mode`, `upload_icon`, `max_filesize`, `forum_permissions`) VALUES (1, 'Images', 1, 1, 1, '', 0, '');
INSERT INTO `phpbb_extension_groups` (`group_id`, `group_name`, `cat_id`, `allow_group`, `download_mode`, `upload_icon`, `max_filesize`, `forum_permissions`) VALUES (2, 'Archives', 0, 1, 1, '', 0, '');
INSERT INTO `phpbb_extension_groups` (`group_id`, `group_name`, `cat_id`, `allow_group`, `download_mode`, `upload_icon`, `max_filesize`, `forum_permissions`) VALUES (3, 'Plain Text', 0, 0, 1, '', 0, '');
INSERT INTO `phpbb_extension_groups` (`group_id`, `group_name`, `cat_id`, `allow_group`, `download_mode`, `upload_icon`, `max_filesize`, `forum_permissions`) VALUES (4, 'Documents', 0, 0, 1, '', 0, '');
INSERT INTO `phpbb_extension_groups` (`group_id`, `group_name`, `cat_id`, `allow_group`, `download_mode`, `upload_icon`, `max_filesize`, `forum_permissions`) VALUES (5, 'Real Media', 0, 0, 2, '', 0, '');
INSERT INTO `phpbb_extension_groups` (`group_id`, `group_name`, `cat_id`, `allow_group`, `download_mode`, `upload_icon`, `max_filesize`, `forum_permissions`) VALUES (6, 'Streams', 2, 0, 1, '', 0, '');
INSERT INTO `phpbb_extension_groups` (`group_id`, `group_name`, `cat_id`, `allow_group`, `download_mode`, `upload_icon`, `max_filesize`, `forum_permissions`) VALUES (7, 'Flash Files', 3, 0, 1, '', 0, '');

## `phpbb_extensions`
##
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (1, 1, 'gif', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (2, 1, 'png', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (3, 1, 'jpeg', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (4, 1, 'jpg', '');
#INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (5, 1, 'tif', '');
#INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (6, 1, 'tga', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (7, 2, 'gtar', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (8, 2, 'gz', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (9, 2, 'tar', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (10, 2, 'zip', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (11, 2, 'rar', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (12, 2, 'ace', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (13, 3, 'txt', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (14, 3, 'c', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (15, 3, 'h', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (16, 3, 'cpp', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (17, 3, 'hpp', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (18, 3, 'diz', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (19, 4, 'xls', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (20, 4, 'doc', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (21, 4, 'dot', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (22, 4, 'pdf', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (23, 4, 'ai', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (24, 4, 'ps', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (25, 4, 'ppt', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (26, 5, 'rm', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (27, 6, 'wma', '');
INSERT INTO `phpbb_extensions` (`ext_id`, `group_id`, `extension`, `comment`) VALUES (28, 7, 'swf', '');

## `phpbb_flags`
##
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (1, 'Afghanistan', 'afghanistan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (2, 'AI', 'ai.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (3, 'Albania', 'albania.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (4, 'Algeria', 'algeria.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (5, 'AN', 'an.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (6, 'Andorra', 'andorra.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (7, 'Antiguabarbuda', 'antiguabarbuda.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (8, 'AO', 'ao.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (9, 'Argentina', 'argentina.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (10, 'Armenia', 'armenia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (11, 'AS', 'as.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (12, 'Australia', 'australia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (13, 'Austria', 'austria.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (14, 'AW', 'aw.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (15, 'AX', 'ax.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (16, 'Azerbaijan', 'azerbaijan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (17, 'Bahamas', 'bahamas.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (18, 'Bahrain', 'bahrain.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (19, 'Bangladesh', 'bangladesh.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (20, 'Barbados', 'barbados.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (21, 'Belarus', 'belarus.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (22, 'Belgium', 'belgium.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (23, 'Belize', 'belize.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (24, 'Benin', 'benin.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (25, 'Bhutan', 'bhutan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (26, 'BM', 'bm.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (27, 'Bolivia', 'bolivia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (28, 'Bosnia Herzegovina', 'bosnia_herzegovina.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (29, 'Botswana', 'botswana.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (30, 'Brazil', 'brazil.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (31, 'Brunei', 'brunei.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (32, 'Bulgaria', 'bulgaria.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (33, 'Burkinafaso', 'burkinafaso.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (34, 'Burma', 'burma.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (35, 'Burundi', 'burundi.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (36, 'BV', 'bv.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (37, 'Cambodia', 'cambodia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (38, 'Cameroon', 'cameroon.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (39, 'Canada', 'canada.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (40, 'CC', 'cc.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (41, 'Central African Republic', 'central_african_republic.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (42, 'Chad', 'chad.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (43, 'Chile', 'chile.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (44, 'China', 'china.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (45, 'CK', 'ck.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (46, 'Columbia', 'columbia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (47, 'Comoros', 'comoros.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (48, 'Congo', 'congo.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (49, 'Costa Rica', 'costa_rica.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (50, 'Croatia', 'croatia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (51, 'Cuba', 'cuba.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (52, 'CV', 'cv.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (53, 'CX', 'cx.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (54, 'Cyprus', 'cyprus.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (55, 'Czech Republic', 'czech_republic.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (56, 'Dem Rep Congo', 'dem_rep_congo.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (57, 'Denmark', 'denmark.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (58, 'Djibouti', 'djibouti.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (59, 'Dominica', 'dominica.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (60, 'Dominican Rep', 'dominican_rep.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (61, 'Ecuador', 'ecuador.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (62, 'Egypt', 'egypt.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (63, 'EH', 'eh.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (64, 'Elsalvador', 'elsalvador.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (65, 'England', 'england.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (66, 'Eq Guinea', 'eq_guinea.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (67, 'Eritrea', 'eritrea.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (68, 'Estonia', 'estonia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (69, 'Ethiopia', 'ethiopia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (70, 'FAM', 'fam.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (71, 'Fiji', 'fiji.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (72, 'Finland', 'finland.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (73, 'FK', 'fk.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (74, 'FO', 'fo.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (75, 'France', 'france.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (76, 'Gabon', 'gabon.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (77, 'Gambia', 'gambia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (78, 'Georgia', 'georgia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (79, 'Germany', 'germany.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (80, 'Ghana', 'ghana.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (81, 'GI', 'gi.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (82, 'GL', 'gl.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (83, 'GP', 'gp.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (84, 'Greece', 'greece.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (85, 'Grenada', 'grenada.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (86, 'Grenadines', 'grenadines.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (87, 'GS', 'gs.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (88, 'GU', 'gu.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (89, 'Guatemala', 'guatemala.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (90, 'Guinea', 'guinea.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (91, 'Guinea Bissau', 'guinea_bissau.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (92, 'Guyana', 'guyana.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (93, 'Haiti', 'haiti.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (94, 'Honduras', 'honduras.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (95, 'Hong Kong', 'hong_kong.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (96, 'Hungary', 'hungary.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (97, 'Iceland', 'iceland.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (98, 'India', 'india.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (99, 'Indonesia', 'indonesia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (100, 'IO', 'io.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (101, 'Iran', 'iran.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (102, 'Iraq', 'iraq.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (103, 'Ireland', 'ireland.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (104, 'Israel', 'israel.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (105, 'Italia', 'italia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (106, 'Ivory Coast', 'ivory_coast.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (107, 'Jamaica', 'jamaica.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (108, 'Japan', 'japan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (109, 'Jordan', 'jordan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (110, 'Kazakhstan', 'kazakhstan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (111, 'Kenya', 'kenya.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (112, 'Kiribati', 'kiribati.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (113, 'KP', 'kp.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (114, 'Kuwait', 'kuwait.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (115, 'KY', 'ky.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (116, 'Kyrgyzstan', 'kyrgyzstan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (117, 'Laos', 'laos.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (118, 'Latvia', 'latvia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (119, 'Lebanon', 'lebanon.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (120, 'Liberia', 'liberia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (121, 'Libya', 'libya.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (122, 'Liechtenstein', 'liechtenstein.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (123, 'Lithuania', 'lithuania.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (124, 'LS', 'ls.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (125, 'Luxembourg', 'luxembourg.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (126, 'Macau', 'macau.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (127, 'Madagascar', 'madagascar.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (128, 'Malawi', 'malawi.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (129, 'Malaysia', 'malaysia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (130, 'Maldives', 'maldives.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (131, 'Mali', 'mali.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (132, 'Malta', 'malta.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (133, 'Mauritania', 'mauritania.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (134, 'Mauritius', 'mauritius.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (135, 'Mexico', 'mexico.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (136, 'MH', 'mh.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (137, 'Micronesia', 'micronesia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (138, 'MK', 'mk.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (139, 'Moldova', 'moldova.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (140, 'Monaco', 'monaco.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (141, 'Mongolia', 'mongolia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (142, 'Morocco', 'morocco.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (143, 'Mozambique', 'mozambique.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (144, 'MP', 'mp.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (145, 'MS', 'ms.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (146, 'Namibia', 'namibia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (147, 'Nauru', 'nauru.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (148, 'NC', 'nc.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (149, 'Nepal', 'nepal.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (150, 'Netherlands', 'netherlands.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (151, 'New Zealand', 'new_zealand.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (152, 'NF', 'nf.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (153, 'Nicaragua', 'nicaragua.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (154, 'Niger', 'niger.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (155, 'Nigeria', 'nigeria.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (156, 'Norway', 'norway.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (157, 'NU', 'nu.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (158, 'Oman', 'oman.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (159, 'Pakistan', 'pakistan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (160, 'Panama', 'panama.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (161, 'Papua New Guinea', 'papua_new_guinea.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (162, 'Paraguay', 'paraguay.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (163, 'Peru', 'peru.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (164, 'PF', 'pf.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (165, 'Philippines', 'philippines.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (166, 'PM', 'pm.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (167, 'PN', 'pn.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (168, 'Poland', 'poland.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (169, 'Portugal', 'portugal.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (170, 'PS', 'ps.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (171, 'Puerto Rico', 'puerto_rico.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (172, 'PW', 'pw.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (173, 'Qatar', 'qatar.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (174, 'Quebec', 'quebec.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (175, 'Romania', 'romania.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (176, 'Russia', 'russia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (177, 'Rwanda', 'rwanda.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (178, 'Sao Tome', 'sao_tome.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (179, 'Saudi Arabia', 'saudi_arabia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (180, 'Scotland', 'scotland.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (181, 'Senegal', 'senegal.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (182, 'Serbia', 'serbia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (183, 'Seychelles', 'seychelles.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (184, 'SH', 'sh.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (185, 'Sierraleone', 'sierraleone.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (186, 'Singapore', 'singapore.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (187, 'Slovakia', 'slovakia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (188, 'Slovenia', 'slovenia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (189, 'SM', 'sm.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (190, 'Solomon Islands', 'solomon_islands.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (191, 'Somalia', 'somalia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (192, 'South Africa', 'south_africa.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (193, 'South Korea', 'south_korea.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (194, 'Spain', 'spain.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (195, 'Sri Lanka', 'sri_lanka.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (196, 'Stkitts Nevis', 'stkitts_nevis.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (197, 'Stlucia', 'stlucia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (198, 'Sudan', 'sudan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (199, 'Suriname', 'suriname.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (200, 'Sweden', 'sweden.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (201, 'Switzerland', 'switzerland.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (202, 'Syria', 'syria.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (203, 'SZ', 'sz.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (204, 'Taiwan', 'taiwan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (205, 'Tajikistan', 'tajikistan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (206, 'Tanzania', 'tanzania.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (207, 'TF', 'tf.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (208, 'Thailand', 'thailand.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (209, 'TK', 'tk.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (210, 'TL', 'tl.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (211, 'Togo', 'togo.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (212, 'Tonga', 'tonga.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (213, 'Trinidat And Tobago', 'trinidat_and_tobago.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (214, 'Tunisia', 'tunisia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (215, 'Turkey', 'turkey.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (216, 'Turkmenistan', 'turkmenistan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (217, 'Tuvala', 'tuvala.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (218, 'TV', 'tv.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (219, 'Uganda', 'uganda.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (220, 'UK', 'uk.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (221, 'Ukraine', 'ukraine.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (222, 'UM', 'um.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (223, 'United Arabic Emirates', 'united_arabic_emirates.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (224, 'Uruguay', 'uruguay.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (225, 'USA', 'usa.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (226, 'Uzbekistan', 'uzbekistan.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (227, 'Vanuatu', 'vanuatu.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (228, 'Vatican', 'vatican.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (229, 'Venezuela', 'venezuela.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (230, 'VG', 'vg.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (231, 'VI', 'vi.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (232, 'Vietnam', 'vietnam.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (233, 'Wales', 'wales.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (234, 'Western Samoa', 'western_samoa.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (235, 'WF', 'wf.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (236, 'Yemen', 'yemen.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (237, 'YT', 'yt.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (238, 'Yugoslavia', 'yugoslavia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (239, 'Zambia', 'zambia.png');
INSERT INTO `phpbb_flags` (`flag_id`, `flag_name`, `flag_image`) VALUES (240, 'Zimbabwe', 'zimbabwe.png');

## `phpbb_forbidden_extensions`
##
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (1, 'php');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (2, 'php3');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (3, 'php4');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (4, 'phtml');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (5, 'pl');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (6, 'asp');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (7, 'cgi');

## `phpbb_force_read`
##
INSERT INTO `phpbb_force_read` (`topic_number`, `message`, `install_date`, `active`, `effected`) VALUES (1, 'Before going on... please make sure you have read and understood this post. It contains important informations regarding this site.', 0, 1, 1);

## `phpbb_force_read_users`
##

## `phpbb_forum_prune`
##

## `phpbb_forums`
##
INSERT INTO `phpbb_forums` (`forum_id`, `cat_id`, `main_type`, `forum_name`, `forum_desc`, `forum_status`, `forum_order`, `forum_posts`, `forum_topics`, `forum_last_post_id`, `forum_notify`, `forum_postcount`, `forum_link`, `forum_link_internal`, `forum_link_hit_count`, `forum_link_hit`, `icon`, `prune_next`, `prune_enable`, `auth_view`, `auth_read`, `auth_post`, `auth_reply`, `auth_edit`, `auth_delete`, `auth_sticky`, `auth_announce`, `auth_globalannounce`, `auth_news`, `auth_cal`, `auth_vote`, `auth_pollcreate`, `auth_attachments`, `auth_download`, `auth_ban`, `auth_greencard`, `auth_bluecard`, `auth_rate`) VALUES (1, 1, 'c', 'Test Forum 1', 'This is just a test forum.', 0, 10, 2, 2, 2, 1, 1, NULL, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, 1, 1, 3, 1, 3, 3, 3, 1, 3, 1, 0, 3, 3, 1, 1);

## `phpbb_forums_rules`
##
INSERT INTO `phpbb_forums_rules` (`forum_id`, `rules`) VALUES (1, '');

## `phpbb_forums_watch`
##

## `phpbb_google_bot_detector`
##

## `phpbb_groups`
##
INSERT INTO `phpbb_groups` (`group_id`, `group_type`, `group_name`, `group_description`, `group_moderator`, `group_single_user`, `group_color`, `group_count`, `group_count_max`, `group_count_enable`, `upi2db_on`, `upi2db_min_posts`, `upi2db_min_regdays`) VALUES (1, 1, 'Anonymous', 'Personal User', 0, 1, '', 99999999, 99999999, 0, 1, 0, 0);
INSERT INTO `phpbb_groups` (`group_id`, `group_type`, `group_name`, `group_description`, `group_moderator`, `group_single_user`, `group_color`, `group_count`, `group_count_max`, `group_count_enable`, `upi2db_on`, `upi2db_min_posts`, `upi2db_min_regdays`) VALUES (2, 1, 'Admin', 'Personal User', 0, 1, '', 99999999, 99999999, 0, 1, 0, 0);
INSERT INTO `phpbb_groups` (`group_id`, `group_type`, `group_name`, `group_description`, `group_moderator`, `group_single_user`, `group_color`, `group_legend`, `group_legend_order`, `group_count`, `group_count_max`, `group_count_enable`, `upi2db_on`, `upi2db_min_posts`, `upi2db_min_regdays`) VALUES (3, 1, 'Administrators', 'Administrators', 2, 0, '#DD2222', '1', '1', 99999999, 99999999, 0, 1, 0, 0);
INSERT INTO `phpbb_groups` (`group_id`, `group_type`, `group_name`, `group_description`, `group_moderator`, `group_single_user`, `group_color`, `group_legend`, `group_legend_order`, `group_count`, `group_count_max`, `group_count_enable`, `upi2db_on`, `upi2db_min_posts`, `upi2db_min_regdays`) VALUES (4, 1, 'Moderators', 'Moderators', 2, 0, '#228844', '1', '2', 99999999, 99999999, 0, 1, 0, 0);
INSERT INTO `phpbb_groups` (`group_id`, `group_type`, `group_name`, `group_description`, `group_moderator`, `group_single_user`, `group_color`, `group_legend`, `group_legend_order`, `group_count`, `group_count_max`, `group_count_enable`, `upi2db_on`, `upi2db_min_posts`, `upi2db_min_regdays`) VALUES (5, 0, 'Users', 'All Users', 2, 0, '#224488', '1', '3', 0, 99999999, 0, 1, 0, 0);

## `phpbb_hacks_list`
##

## `phpbb_jr_admin_users`
##

## `phpbb_kb_articles`
##
INSERT INTO `phpbb_kb_articles` (`article_id`, `article_category_id`, `article_title`, `article_description`, `article_date`, `article_author_id`, `username`, `article_body`, `article_type`, `approved`, `topic_id`, `views`, `article_rating`, `article_totalvotes`) VALUES (1, 1, 0x546573742041727469636c65, 0x54686973206973206120746573742061727469636c6520666f7220796f7572204b42, 0x31303537373038323335, 2, '', 'This is a test article for your Knowledge Base. This MOD is based on code written by wGEric &lt; eric@egcnetwork.com &gt; (Eric Faerber) - http://eric.best-1.biz/, now supervised by _Haplo &lt; jonohlsson@hotmail.com &gt; (Jon Ohlsson) - http://www.mx-system.com/ \r\n\r\nBe sure you add categories and article types in the ACP and also change the Configuration to your liking.\r\n\r\nHave fun and enjoy your new Knowledge Base!  :D', 1, 1, 0, 0, 0.0000, 0);

## `phpbb_kb_categories`
##
INSERT INTO `phpbb_kb_categories` (`category_id`, `category_name`, `category_details`, `number_articles`, `parent`, `cat_order`, `auth_view`, `auth_post`, `auth_rate`, `auth_comment`, `auth_edit`, `auth_delete`, `auth_approval`, `auth_approval_edit`, `auth_view_groups`, `auth_post_groups`, `auth_rate_groups`, `auth_comment_groups`, `auth_edit_groups`, `auth_delete_groups`, `auth_approval_groups`, `auth_approval_edit_groups`, `auth_moderator_groups`, `comments_forum_id`) VALUES (1, 0x546573742043617465676f72792031, 0x54686973206973206120746573742063617465676f7279, 0, 0, 10, 0, 0, 0, 0, 0, 2, 0, 0, '', '', '', '', '', '', '', '', '', 1);

## `phpbb_kb_config`
##
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('allow_new', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('notify', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('admin_id', '2');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('show_pretext', '0');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('pt_header', 'Article Submission Instructions');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('pt_body', 'Please check your references and include as much information as you can.');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('use_comments', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('del_topic', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('use_ratings', '0');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('comments_show', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('bump_post', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('stats_list', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('header_banner', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('votes_check_userid', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('votes_check_ip', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('art_pagination', '5');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('comments_pagination', '5');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('news_sort', 'Alphabetic');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('news_sort_par', 'ASC');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('wysiwyg', '0');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('wysiwyg_path', 'modules/');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('allow_html', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('allow_bbcode', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('allow_smilies', '1');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('formatting_fixup', '0');
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('allowed_html_tags', 'b,i,u,a');

## `phpbb_kb_custom`
##

## `phpbb_kb_customdata`
##

## `phpbb_kb_results`
##
INSERT INTO `phpbb_kb_results` (`search_id`, `session_id`, `search_array`) VALUES (2007033495, 'e759246991a84e7075c17ad56e50972e', 'a:7:{s:14:"search_results";s:0:"";s:17:"total_match_count";i:0;s:12:"split_search";a:1:{i:0;s:4:"test";}s:7:"sort_by";i:0;s:8:"sort_dir";s:4:"DESC";s:12:"show_results";s:5:"posts";s:12:"return_chars";N;}');

## `phpbb_kb_search`
##

## `phpbb_kb_types`
##
INSERT INTO `phpbb_kb_types` (`id`, `type`) VALUES (1, 0x5465737420547970652031);

## `phpbb_kb_votes`
##

## `phpbb_kb_wordlist`
##

## `phpbb_kb_wordmatch`
##

## `phpbb_link_categories`
##
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (1, 'Arts', 1);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (2, 'Business', 2);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (3, 'Children and Teens', 3);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (4, 'Computers', 4);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (5, 'Games', 5);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (6, 'Health', 6);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (7, 'Home', 7);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (8, 'News', 8);

## `phpbb_link_config`
##
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('width', '88');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('height', '31');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('linkspp', '10');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('display_interval', '6000');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('display_logo_num', '10');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('display_links_logo', '1');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('email_notify', '1');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('pm_notify', '0');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('lock_submit_site', '0');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('allow_no_logo', '0');

## `phpbb_links`
##
INSERT INTO `phpbb_links` (`link_id`, `link_title`, `link_desc`, `link_category`, `link_url`, `link_logo_src`, `link_joined`, `link_active`, `link_hits`, `user_id`, `user_ip`, `last_user_ip`) VALUES (1, 'Icy Phoenix Official Website', 'Icy Phoenix', 4, 'http://www.icyphoenix.com/', 'images/links/banner_ip.gif', 1125353670, 1, 0, 2, '', '');
INSERT INTO `phpbb_links` (`link_id`, `link_title`, `link_desc`, `link_category`, `link_url`, `link_logo_src`, `link_joined`, `link_active`, `link_hits`, `user_id`, `user_ip`, `last_user_ip`) VALUES (2, 'Mighty Gorgon Community', 'Mighty Gorgon Community', 4, 'http://www.mightygorgon.com/', 'images/links/banner_mightygorgon.gif', 1125353670, 1, 0, 2, '', '');
INSERT INTO `phpbb_links` (`link_id`, `link_title`, `link_desc`, `link_category`, `link_url`, `link_logo_src`, `link_joined`, `link_active`, `link_hits`, `user_id`, `user_ip`, `last_user_ip`) VALUES (3, 'phpBB Official Website', 'Official phpBB Website', 4, 'http://www.phpbb.com/', 'images/links/banner_phpbb88a.gif', 1125353670, 1, 0, 2, '', '');

## `phpbb_liw_cache`
##

## `phpbb_logins`
##

## `phpbb_news`
##
INSERT INTO `phpbb_news` (`news_id`, `news_category`, `news_image`) VALUES (1, 'News', '48_icy_phoenix.png');

## `phpbb_notes`
##
INSERT INTO `phpbb_notes` (`id`, `text`) VALUES (1, 'Write here your notes');

## `phpbb_pa_auth`
##

## `phpbb_pa_cat`
##
INSERT INTO `phpbb_pa_cat` (`cat_id`, `cat_name`, `cat_desc`, `cat_parent`, `parents_data`, `cat_order`, `cat_allow_file`, `cat_allow_ratings`, `cat_allow_comments`, `cat_files`, `cat_last_file_id`, `cat_last_file_name`, `cat_last_file_time`, `auth_view`, `auth_read`, `auth_view_file`, `auth_edit_file`, `auth_delete_file`, `auth_upload`, `auth_download`, `auth_rate`, `auth_email`, `auth_view_comment`, `auth_post_comment`, `auth_edit_comment`, `auth_delete_comment`) VALUES (1, 'My Category', '', 0, '', 1, 0, 0, 0, 0, 0, '0', 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO `phpbb_pa_cat` (`cat_id`, `cat_name`, `cat_desc`, `cat_parent`, `parents_data`, `cat_order`, `cat_allow_file`, `cat_allow_ratings`, `cat_allow_comments`, `cat_files`, `cat_last_file_id`, `cat_last_file_name`, `cat_last_file_time`, `auth_view`, `auth_read`, `auth_view_file`, `auth_edit_file`, `auth_delete_file`, `auth_upload`, `auth_download`, `auth_rate`, `auth_email`, `auth_view_comment`, `auth_post_comment`, `auth_edit_comment`, `auth_delete_comment`) VALUES (2, 'Test Category', 'Just a test category', 1, '', 2, 1, 0, 0, 0, 0, '0', 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

## `phpbb_pa_comments`
##

## `phpbb_pa_config`
##
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('allow_comment_images', '0');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('no_comment_image_message', '[No image please]');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('allow_smilies', '1');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('allow_comment_links', '1');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('no_comment_link_message', '[No links please]');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('settings_disable', '0');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('allow_html', '1');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('allow_bbcode', '1');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('settings_topnumber', '10');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('settings_newdays', '1');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('settings_stats', '');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('settings_viewall', '1');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('settings_dbname', 'Download Database');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('settings_dbdescription', '');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('max_comment_chars', '5000');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('tpl_php', '0');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('settings_file_page', '20');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('hotlink_prevent', '1');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('hotlink_allowed', '');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('sort_method', 'file_time');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('sort_order', 'DESC');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('auth_search', '0');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('auth_stats', '0');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('auth_toplist', '0');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('auth_viewall', '0');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('max_file_size', '262144');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('upload_dir', 'downloads/');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('screenshots_dir', 'files/screenshots/');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('forbidden_extensions', 'php, php3, php4, phtml, pl, asp, aspx, cgi');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('need_validation', '0');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('validator', 'validator_admin');
INSERT INTO `phpbb_pa_config` (`config_name`, `config_value`) VALUES ('pm_notify', '0');

## `phpbb_pa_custom`
##

## `phpbb_pa_customdata`
##

## `phpbb_pa_download_info`
##

## `phpbb_pa_files`
##

## `phpbb_pa_license`
##

## `phpbb_pa_mirrors`
##

## `phpbb_pa_votes`
##

## `phpbb_posts`
##
INSERT INTO `phpbb_posts` (`post_id`, `topic_id`, `forum_id`, `poster_id`, `post_time`, `poster_ip`, `post_username`, `enable_bbcode`, `enable_html`, `enable_smilies`, `enable_sig`, `post_edit_time`, `post_edit_count`, `post_attachment`, `post_bluecard`, `enable_autolinks_acronyms`, `post_subject`, `post_text`, `post_text_compiled`, `edit_notes`) VALUES (1, 1, 1, 2, 1137567600, '7F000001', '', 1, 0, 1, 0, 1129068420, 0, 0, NULL, 1, 'Welcome to Icy Phoenix', 'If you can read this Topic it seems that you have successfully installed your new Forum using [b]Icy Phoenix[/b]. You should now visit the Admin Control Panel to configure some Settings. In ACP you can set the main settings and preferences for the whole sites (styles, languages, time, forums, download, users, album, etc.) while in CMS section you can configure options regarding the site pages (define permissions, add blocks, create new pages, create new menu, etc.). You may also want to configure [b].htaccess[/b] and [b]lang_main_settings.php[/b] (for each installed lang) to fine tune some other preferences, like error reporting, url rewrite, keywords, welcome message, charset and so on. Since everything seems to work fine you are now free to delete this Topic, this Forum and also the Category.\r\n\r\nShould you need any help you can refer to [url]http://www.icyphoenix.com/[/url] for support.\r\n\r\nThank you for choosing Icy Phoenix and remember to backup your db periodically.', '', NULL);
INSERT INTO `phpbb_posts` (`post_id`, `topic_id`, `forum_id`, `poster_id`, `post_time`, `poster_ip`, `post_username`, `enable_bbcode`, `enable_html`, `enable_smilies`, `enable_sig`, `post_edit_time`, `post_edit_count`, `post_attachment`, `post_bluecard`, `enable_autolinks_acronyms`, `post_subject`, `post_text`, `post_text_compiled`, `edit_notes`) VALUES (2, 2, 1, 2, 1137567600, '7f000001', '', 1, 0, 1, 0, 1129111805, 0, 0, NULL, 1, 'Sample News Post in Home Page', 'As you can see this Topic is Attached to a News Category which is displayed in the Home Page. You can simply create News Postings in Home Page by Posting a Topic and select the News Category into which the News Message should be posted.\r\n\r\nHave Fun...', '', NULL);

## `phpbb_privmsgs`
##

## `phpbb_privmsgs_archive`
##

## `phpbb_profile_fields`
##

## `phpbb_profile_view`
##

## `phpbb_quota_limits`
##
INSERT INTO `phpbb_quota_limits` (`quota_limit_id`, `quota_desc`, `quota_limit`) VALUES (1, 'Low', 262144);
INSERT INTO `phpbb_quota_limits` (`quota_limit_id`, `quota_desc`, `quota_limit`) VALUES (2, 'Medium', 2097152);
INSERT INTO `phpbb_quota_limits` (`quota_limit_id`, `quota_desc`, `quota_limit`) VALUES (3, 'High', 5242880);

## `phpbb_ranks`
##
INSERT INTO `phpbb_ranks` (`rank_id`, `rank_title`, `rank_min`, `rank_special`, `rank_image`) VALUES (1, 'Site Admin', -1, 1, 'images/ranks/rank_admin.png');
INSERT INTO `phpbb_ranks` (`rank_id`, `rank_title`, `rank_min`, `rank_special`, `rank_image`) VALUES (2, 'Developer', -1, 1, 'images/ranks/rank_developer.png');
INSERT INTO `phpbb_ranks` (`rank_id`, `rank_title`, `rank_min`, `rank_special`, `rank_image`) VALUES (3, 'Moderator', -1, 1, 'images/ranks/rank_moderator.png');
INSERT INTO `phpbb_ranks` (`rank_id`, `rank_title`, `rank_min`, `rank_special`, `rank_image`) VALUES (4, 'Coder', -1, 1, 'images/ranks/rank_coder.png');
INSERT INTO `phpbb_ranks` (`rank_id`, `rank_title`, `rank_min`, `rank_special`, `rank_image`) VALUES (5, 'Supporter', -1, 1, 'images/ranks/rank_supporter.png');

## `phpbb_rate_results`
##

## `phpbb_referrers`
##
INSERT INTO `phpbb_referrers` (`referrer_id`, `referrer_host`, `referrer_url`, `referrer_ip`, `referrer_hits`, `referrer_firstvisit`, `referrer_lastvisit`) VALUES (1, 'www.icyphoenix.com', 'http://icyphoenix.com', '7f000001', 1, 1121336515, 1121336515);

## `phpbb_search_results`
##

## `phpbb_search_wordlist`
##

## `phpbb_search_wordmatch`
##

## `phpbb_sessions`
##

## `phpbb_sessions_keys`
##

## `phpbb_shout`
##
INSERT INTO `phpbb_shout` (`shout_id`, `shout_username`, `shout_user_id`, `shout_group_id`, `shout_session_time`, `shout_ip`, `shout_text`, `shout_active`, `enable_bbcode`, `enable_html`, `enable_smilies`, `enable_sig`) VALUES (1, '', 2, 0, 1129051367, '7f000001', 'Welcome to [b]Icy Phoenix[/b]', 0, 1, 0, 1, 0);

## `phpbb_site_history`
##

## `phpbb_smilies`
##
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (1, ':D', 'icon_biggrin.gif', 'Very Happy', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (2, ':-D', 'icon_biggrin.gif', 'Very Happy', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (3, ':grin:', 'icon_biggrin.gif', 'Very Happy', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (4, ':)', 'icon_smile.gif', 'Smile', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (5, ':-)', 'icon_smile.gif', 'Smile', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (6, ':smile:', 'icon_smile.gif', 'Smile', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (7, ':(', 'icon_sad.gif', 'Sad', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (8, ':-(', 'icon_sad.gif', 'Sad', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (9, ':sad:', 'icon_sad.gif', 'Sad', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (10, ':o', 'icon_surprised.gif', 'Surprised', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (11, ':-o', 'icon_surprised.gif', 'Surprised', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (12, ':eek:', 'icon_surprised.gif', 'Surprised', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (13, ':shock:', 'icon_eek.gif', 'Shocked', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (14, ':?', 'icon_confused.gif', 'Confused', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (15, ':-?', 'icon_confused.gif', 'Confused', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (16, ':???:', 'icon_confused.gif', 'Confused', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (17, '8)', 'icon_cool.gif', 'Cool', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (18, '8-)', 'icon_cool.gif', 'Cool', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (19, ':cool:', 'icon_cool.gif', 'Cool', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (20, ':lol:', 'icon_lol.gif', 'Laughing', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (21, ':x', 'icon_mad.gif', 'Mad', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (22, ':-x', 'icon_mad.gif', 'Mad', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (23, ':mad:', 'icon_mad.gif', 'Mad', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (24, ':P', 'icon_razz.gif', 'Razz', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (25, ':-P', 'icon_razz.gif', 'Razz', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (26, ':razz:', 'icon_razz.gif', 'Razz', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (27, ':oops:', 'icon_redface.gif', 'Embarassed', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (28, ':cry:', 'icon_cry.gif', 'Crying or Very sad', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (30, ':twisted:', 'icon_twisted.gif', 'Twisted Evil', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (32, ':wink:', 'icon_wink.gif', 'Wink', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (33, ';)', 'icon_wink.gif', 'Wink', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (34, ';-)', 'icon_wink.gif', 'Wink', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (35, ':!:', 'icon_exclaim.gif', 'Exclamation', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (36, ':?:', 'icon_question.gif', 'Question', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (37, ':idea:', 'icon_idea.gif', 'Idea', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (38, ':arrow:', 'icon_arrow.gif', 'Arrow', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (39, ':|', 'icon_neutral.gif', 'Neutral', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (40, ':-|', 'icon_neutral.gif', 'Neutral', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (41, ':neutral:', 'icon_neutral.gif', 'Neutral', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (42, ':mricy:', 'icon_mricy.gif', 'Mr. Ycy', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (43, ':mrblue:', 'icon_mrblue.gif', 'Mr. Blue', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (44, ':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (45, ':mrorange:', 'icon_mrorange.gif', 'Mr. Orange', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (46, ':mrviolet:', 'icon_mrviolet.gif', 'Mr. Violet', 0);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (47, ':mryellow:', 'icon_mryellow.gif', 'Mr. Yellow', 0);

## `phpbb_stats_config`
##
INSERT INTO `phpbb_stats_config` (`config_name`, `config_value`) VALUES ('return_limit', '10');
INSERT INTO `phpbb_stats_config` (`config_name`, `config_value`) VALUES ('version', '2.1.5');
INSERT INTO `phpbb_stats_config` (`config_name`, `config_value`) VALUES ('modules_dir', 'includes/stats_modules');
INSERT INTO `phpbb_stats_config` (`config_name`, `config_value`) VALUES ('page_views', '0');

## `phpbb_stats_modules`
##

## `phpbb_sudoku_sessions`
##

## `phpbb_sudoku_solutions`
##
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 1, '7a1a5a2a3a4a9a8a6', '6a4a3a1a8a9a5a7a2', '8a2a9a6a7a5a1a3a4', '8a7a9a3a6a1a4a5a2', '4a5a1a2a9a8a7a3a6', '2a6a3a5a4a7a9a8a1', '5a9a8a1a4a7a6a2a3', '3a6a7a8a2a5a9a1a4', '4a1a2a3a9a6a7a5a8');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 2, '6a1a3a8a4a7a2a5a9', '8a4a9a5a2a3a1a6a7', '7a2a5a1a6a9a4a3a8', '4a2a6a7a8a5a3a9a1', '9a5a1a6a3a2a4a7a8', '8a7a3a9a1a4a6a5a2', '5a7a4a9a6a8a1a3a2', '3a8a6a2a1a5a7a9a4', '2a9a1a3a4a7a5a8a6');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 3, '1a5a7a4a8a2a9a6a3', '2a6a4a9a7a3a5a1a8', '8a9a3a5a6a1a4a7a2', '2a9a5a3a1a8a6a7a4', '8a4a1a6a9a7a3a5a2', '6a3a7a2a4a5a9a1a8', '5a3a9a7a4a6a8a2a1', '7a8a6a1a2a5a4a3a9', '1a2a4a3a8a9a7a5a6');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 4, '9a6a1a2a5a3a7a8a4', '4a5a7a9a8a6a1a2a3', '3a8a2a4a7a1a6a9a5', '4a2a6a3a1a8a5a7a9', '5a3a9a7a4a2a6a1a8', '7a1a8a9a5a6a2a3a4', '1a9a7a6a3a5a8a4a2', '2a6a5a8a9a4a3a7a1', '8a4a3a1a2a7a5a6a9');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 5, '4a2a7a5a1a9a6a8a3', '3a6a1a8a2a7a5a4a9', '8a5a9a4a3a6a7a1a2', '3a6a1a2a4a5a9a7a8', '9a5a8a7a1a6a4a3a2', '2a4a7a9a8a3a5a6a1', '8a3a2a7a9a4a1a5a6', '6a7a5a1a8a3a2a9a4', '1a9a4a6a2a5a3a7a8');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 6, '8a6a9a3a5a2a7a1a4', '3a7a2a4a1a6a8a5a9', '1a5a4a9a7a8a6a3a2', '5a2a1a4a3a7a6a9a8', '9a4a7a2a6a8a1a3a5', '8a6a3a5a1a9a4a2a7', '2a4a6a1a7a3a9a8a5', '7a8a1a5a9a4a6a2a3', '3a9a5a2a8a6a7a4a1');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 7, '3a2a4a7a8a5a6a1a9', '1a7a8a9a4a6a3a5a2', '5a9a6a2a1a3a7a8a4', '9a5a3a4a6a1a8a7a2', '7a1a4a2a8a3a6a9a5', '8a6a2a9a5a7a4a3a1', '2a3a8a5a9a7a1a4a6', '5a6a7a4a3a1a8a2a9', '1a4a9a6a2a8a3a7a5');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 8, '8a7a9a5a2a1a4a6a3', '4a5a1a9a3a6a2a7a8', '2a3a6a7a8a4a5a9a1', '2a8a6a3a4a7a1a9a5', '1a4a7a5a9a2a6a8a3', '9a5a3a1a6a8a4a7a2', '7a1a2a6a3a4a9a5a8', '8a6a5a7a2a9a3a1a4', '3a4a9a8a1a5a6a2a7');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 9, '4a2a3a5a7a8a1a6a9', '6a7a5a2a9a1a3a4a8', '1a8a9a3a6a4a7a5a2', '9a8a4a7a3a2a6a5a1', '1a5a2a8a6a4a7a3a9', '6a3a7a5a9a1a4a2a8', '8a1a7a2a4a5a3a9a6', '5a2a6a9a1a3a4a8a7', '9a4a3a8a7a6a2a1a5');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 10, '5a7a3a2a6a8a1a4a9', '9a6a4a3a5a1a8a2a7', '1a8a2a9a7a4a6a3a5', '6a2a4a9a8a5a3a1a7', '7a3a9a4a1a2a6a8a5', '5a1a8a7a6a3a2a4a9', '4a3a2a8a9a1a7a5a6', '1a9a6a5a7a3a2a4a8', '8a5a7a4a2a6a3a9a1');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 11, '6a8a2a4a3a9a5a1a7', '7a5a1a2a6a8a4a9a3', '4a3a9a7a1a5a6a2a8', '2a9a3a7a4a8a1a5a6', '5a1a4a3a2a6a8a7a9', '8a7a6a9a5a1a3a4a2', '8a7a1a3a6a5a9a2a4', '6a3a5a9a4a2a1a8a7', '2a9a4a1a8a7a5a6a3');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 12, '1a4a6a5a2a8a3a9a7', '8a7a9a4a6a3a5a1a2', '5a3a2a9a7a1a4a6a8', '9a1a2a7a3a4a6a8a5', '7a5a6a1a2a8a3a9a4', '8a4a3a6a5a9a2a1a7', '8a5a9a4a6a1a2a7a3', '6a3a1a2a8a7a9a4a5', '7a2a4a3a9a5a1a8a6');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 13, '4a6a8a5a9a2a1a3a7', '7a1a5a6a3a8a4a2a9', '2a3a9a7a1a4a6a8a5', '6a1a4a9a8a5a7a2a3', '9a5a3a2a7a6a8a4a1', '8a7a2a1a4a3a9a5a6', '8a5a9a3a7a6a2a4a1', '1a6a4a5a8a2a3a9a7', '3a2a7a4a9a1a5a6a8');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 14, '6a7a9a4a3a2a5a1a8', '8a5a3a9a1a7a6a4a2', '2a1a4a8a6a5a9a7a3', '7a2a5a9a6a1a8a4a3', '4a6a1a2a3a8a5a7a9', '3a9a8a4a5a7a6a2a1', '3a8a7a2a9a4a1a5a6', '1a9a6a7a8a5a3a2a4', '5a4a2a1a3a6a7a8a9');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 15, '1a4a9a5a7a6a2a8a3', '2a3a5a9a1a8a6a7a4', '7a6a8a2a4a3a5a9a1', '4a6a1a9a5a2a7a3a8', '5a2a9a3a8a7a1a4a6', '3a8a7a6a1a4a9a2a5', '6a1a4a8a9a7a3a2a5', '7a9a3a4a5a2a8a6a1', '8a5a2a1a3a6a4a7a9');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 16, '5a9a1a6a3a4a8a2a7', '3a4a6a7a8a2a5a1a9', '8a2a7a9a5a1a4a3a6', '9a5a8a7a6a3a4a1a2', '4a3a7a9a2a1a6a5a8', '1a6a2a5a4a8a7a9a3', '2a4a9a3a8a5a1a7a6', '8a7a3a1a6a4a2a9a5', '6a1a5a2a7a9a3a8a4');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 17, '5a4a6a7a9a1a3a2a8', '8a9a3a4a5a2a6a1a7', '1a7a2a8a3a6a5a4a9', '8a3a2a6a7a9a1a5a4', '7a6a9a1a4a5a3a2a8', '4a1a5a3a2a8a6a9a7', '2a6a3a4a8a7a9a1a5', '5a7a1a9a3a6a2a8a4', '9a8a4a2a5a1a7a6a3');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 18, '5a1a7a4a6a9a8a3a2', '8a6a3a5a2a7a1a4a9', '9a2a4a3a8a1a6a7a5', '3a8a1a2a5a6a7a9a4', '4a5a6a7a9a8a2a3a1', '2a9a7a4a1a3a8a5a6', '1a7a3a6a2a8a9a4a5', '9a8a4a3a1a5a6a7a2', '5a6a2a7a4a9a1a3a8');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 19, '3a8a1a7a2a4a9a5a6', '5a7a2a9a6a1a8a4a3', '9a6a4a5a8a3a7a1a2', '2a3a9a8a1a5a4a6a7', '6a8a5a4a2a7a3a1a9', '1a4a7a6a3a9a8a2a5', '6a4a3a5a7a2a1a9a8', '7a9a8a1a3a6a2a5a4', '2a5a1a4a9a8a3a7a6');
INSERT INTO `phpbb_sudoku_solutions` (`game_pack`, `game_num`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 20, '3a2a5a8a7a1a6a9a4', '1a6a7a9a4a3a8a2a5', '9a4a8a2a6a5a3a7a1', '2a8a6a1a5a7a4a3a9', '5a3a4a2a9a8a7a1a6', '1a9a7a4a3a6a8a5a2', '9a6a8a5a4a3a7a1a2', '3a7a1a6a8a2a4a5a9', '5a2a4a7a1a9a6a8a3');

## `phpbb_sudoku_starts`
##
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 1, 1, 'xaxaxa2axa4a9a8ax', '6axa3axaxaxa5axa2', 'xaxaxa6axa5axa3a4', '8axa9axaxaxa4axa2', 'xaxaxa2axa8axaxax', '2axa3axaxaxa9axa1', '5a9axa1axa7axaxax', '3axa7axaxaxa9axa4', 'xa1a2a3axa6axaxax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 2, 1, 'xaxaxa8axa7a2a5ax', '8axa9a5axa3axaxax', 'xaxaxa1axa9axa3a8', 'xa2a6axaxaxaxa9a1', 'xa5axaxaxaxaxa7ax', '8a7axaxaxaxa6a5ax', '5a7axa9axa8axaxax', 'xaxaxa2axa5a7axa4', 'xa9a1a3axa7axaxax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 3, 1, 'xaxaxaxaxa2axa6ax', 'xaxaxa9axa3a5axa8', 'xaxaxa5axaxaxa7ax', 'xa9a5axa1axaxa7a4', '8axa1a6axa7a3axa2', '6a3axaxa4axa9a1ax', 'xa3axaxaxa6axaxax', '7axa6a1axa5axaxax', 'xa2axa3axaxaxaxax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 4, 1, 'xaxa1axa5a3a7axax', '4axa7a9axa6a1axa3', '3axaxa4a7axaxaxa5', 'xaxa6a3a1a8axaxax', 'xaxaxaxaxaxaxa1ax', '7axaxa9a5a6axaxax', 'xa9axa6axaxaxa4ax', '2axa5axaxaxaxaxax', 'xa4axaxaxa7axa6ax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 5, 1, 'xa2axa5axaxaxaxax', 'xaxaxaxaxa7axa4a9', 'xaxaxa4axaxa7a1a2', 'xaxaxaxaxa5axa7a8', 'xaxaxaxaxa6axa3ax', '2axaxaxa8axaxa6a1', 'xa3a2axaxa4axaxa6', '6axaxaxa8a3axaxa4', 'xaxa4axaxa5a3a7ax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 6, 2, 'xaxaxaxaxaxaxa1ax', 'xaxaxaxa1axa8axa9', 'xa5axa9axa8axa3ax', '5axa1axa3a7a6axa8', '9a4axaxaxa8axaxa5', '8axaxaxa1axa4axax', 'xaxa6axaxaxa9axax', '7a8a1axa9axa6axa3', 'xaxaxa2axaxaxaxax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 7, 2, '3a2axaxaxa5axa1ax', 'xa7axaxaxaxa3axa2', 'xa9a6a2axaxaxa8ax', 'xa5a3a4axaxaxa7a2', 'xaxaxa2axa3axaxax', '8a6axaxaxa7a4a3ax', 'xa3axaxaxa7a1a4ax', '5axa7axaxaxaxa2ax', 'xa4axa6axaxaxa7a5');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 8, 2, '8axa9a5axaxaxaxa3', '4a5a1a9axa6a2axa8', '2axa6axaxaxa5axax', 'xa8axa3axaxaxa9ax', 'xaxaxaxaxaxaxaxax', 'xa5axaxaxa8axa7ax', 'xaxa2a6axaxa9axa8', '8axa5a7axa9a3a1a4', '3axaxaxaxa5a6axa7');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 9, 2, 'xaxaxaxaxaxaxaxax', 'xaxaxaxaxa1a3a4ax', 'xa8axaxaxa4a7a5ax', 'xaxa4axaxa2axa5ax', 'xaxa2axa6axa7axax', '6axaxaxa9a1axaxax', 'xaxa7a2axa5axa9ax', '5axaxaxa1axaxa8ax', 'xaxa3axaxa6a2a1ax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 10, 2, 'xaxa3axaxaxa1axax', 'xaxaxa3a5axaxaxa7', 'xaxaxaxaxa4a6axa5', 'xa2axaxa8axaxaxa7', 'xaxaxaxaxaxaxaxax', 'xaxa8a7a6a3a2a4ax', 'xaxa2axaxaxaxa5a6', 'xa9a6axa7a3a2a4ax', 'xa5a7a4axa6a3a9ax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 11, 3, 'xaxa2a4axaxaxa1a7', '7axa1a2axa8axaxax', '4axaxaxaxa5a6a2ax', '2a9axaxaxaxa1a5ax', 'xaxaxa3axa6axaxax', 'xa7a6axaxaxaxa4a2', 'xa7a1a3axaxaxaxa4', 'xaxaxa9axa2a1axa7', '2a9axaxaxa7a5axax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 12, 3, 'xa4axaxa2axaxaxa7', 'xaxaxaxa6axaxaxax', '5a3axaxaxa1a4axa8', 'xa1axaxa3axa6axa5', '7a5axaxaxa8axaxa4', 'xaxaxaxa5axaxaxax', '8axaxaxaxaxaxaxa3', '6axaxaxa8a7a9axax', '7axaxaxa9a5axaxax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 13, 3, 'xaxa8a5a9axa1axax', 'xaxaxa6axa8a4axa9', '2axaxaxa1a4axaxa5', '6axa4axa8axa7axa3', 'xaxaxaxaxaxaxaxax', '8axa2axa4axa9axa6', '8axaxa3a7axaxaxa1', '1axa4a5axa2axaxax', 'xaxa7axa9a1a5axax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 14, 4, 'xa7axaxa3a2axa1a8', '8axa3axaxaxaxa4ax', 'xa1axa8a6axa9a7ax', 'xaxaxaxaxa1axaxax', '4axa1axaxaxa5axa9', 'xaxaxa4axaxaxaxax', 'xa8a7axa9a4axa5ax', 'xa9axaxaxaxa3axa4', '5a4axa1a3axaxa8ax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 15, 4, 'xaxaxaxa7axaxa8a3', '2axa5axa1axaxa7ax', 'xaxaxaxa4axa5a9ax', 'xaxa1axaxa2axaxa8', 'xaxaxa3axa7axaxax', '3axaxa6axaxa9axax', 'xa1a4axa9axaxaxax', 'xa9axaxa5axa8axa1', '8a5axaxa3axaxaxax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 16, 2, 'xa9axaxaxaxaxaxa7', 'xaxaxaxaxaxaxa1ax', 'xa2axaxaxaxa4axax', 'xaxaxaxaxa3axa1ax', 'xa3axa9axa1a6axa8', 'xaxaxa5axaxaxa9ax', 'xa4a9a3a8axaxa7ax', '8axa3a1axa4a2axa5', '6a1axaxa7a9axa8ax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 17, 2, 'xaxa6a7a9axaxaxax', 'xaxaxaxaxa2a6a1ax', 'xaxaxaxa3axaxaxa9', 'xa3axaxa7axa1a5ax', '7a6axaxa4axaxa2a8', 'xa1a5axa2axaxa9ax', '2axaxaxa8axaxaxax', 'xa7a1a9axaxaxaxax', 'xaxaxaxa5a1a7axax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 18, 2, '5axaxaxaxa9axa3ax', 'xa6axa5axa7axa4ax', 'xaxa4a3axaxaxa7ax', 'xa8a1a2axaxaxa9a4', '4axa6axaxaxa2axa1', '2a9axaxaxa3a8a5ax', 'xa7axaxaxa8a9axax', 'xa8axa3axa5axa7ax', 'xa6axa7axaxaxaxa8');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 19, 2, 'xaxa1axa2axa9axax', 'xaxaxa9a6a1a8axa3', '9axaxaxa8axaxaxa2', 'xa3a9axa1axaxa6a7', '6a8a5a4axa7a3a1a9', '1a4axaxa3axa8a2ax', '6axaxaxa7axaxaxa8', '7axa8a1a3a6axaxax', 'xaxa1axa9axa3axax');
INSERT INTO `phpbb_sudoku_starts` (`game_pack`, `game_num`, `game_level`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`, `line_6`, `line_7`, `line_8`, `line_9`) VALUES (1, 20, 2, 'xaxaxaxa7axa6axax', 'xa6axaxaxa3a8a2a5', 'xa4axaxaxa5axaxax', 'xaxaxaxa5axa4axax', 'xaxaxaxaxaxa7axax', '1a9axa4axa6a8axax', '9a6axaxaxa3a7axa2', 'xaxaxaxa8axa4axax', 'xaxaxaxa1axa6axax');

## `phpbb_sudoku_stats`
##

## `phpbb_sudoku_users`
##

## `phpbb_thanks`
##

## `phpbb_themes`
##
INSERT INTO `phpbb_themes` (`themes_id`, `template_name`, `style_name`, `head_stylesheet`, `body_background`, `body_bgcolor`, `tr_class1`, `tr_class2`, `tr_class3`, `td_class1`, `td_class2`, `td_class3`) VALUES (1, 'icy_phoenix', 'Frozen Phoenix', 'style_cyan.css', 'cyan', '', 'row1', 'row2', 'row3', 'row1', 'row2', 'row3');

## `phpbb_title_infos`
##

## `phpbb_topic_view`
##

## `phpbb_topics`
##
INSERT INTO `phpbb_topics` (`topic_id`, `forum_id`, `topic_title`, `topic_desc`, `topic_poster`, `topic_time`, `topic_views`, `topic_replies`, `topic_status`, `topic_vote`, `topic_type`, `topic_first_post_id`, `topic_last_post_id`, `topic_moved_id`, `topic_attachment`, `title_compl_infos`, `news_id`, `topic_calendar_time`, `topic_calendar_duration`, `topic_rating`, `topic_show_portal`) VALUES (1, 1, 'Welcome to Icy Phoenix', '', 2, 1137567600, 0, 0, 0, 0, 0, 1, 1, 0, 0, NULL, 0, NULL, NULL, 0, 0);
INSERT INTO `phpbb_topics` (`topic_id`, `forum_id`, `topic_title`, `topic_desc`, `topic_poster`, `topic_time`, `topic_views`, `topic_replies`, `topic_status`, `topic_vote`, `topic_type`, `topic_first_post_id`, `topic_last_post_id`, `topic_moved_id`, `topic_attachment`, `title_compl_infos`, `news_id`, `topic_calendar_time`, `topic_calendar_duration`, `topic_rating`, `topic_show_portal`) VALUES (2, 1, 'Sample News Post in Portal', '', 2, 1137567600, 1, 0, 0, 0, 4, 2, 2, 0, 0, NULL, 1, 0, 0, 0, 0);

## `phpbb_topics_watch`
##

## `phpbb_upi2db_always_read`
##

## `phpbb_upi2db_last_posts`
##

## `phpbb_upi2db_unread_posts`
##

## `phpbb_user_group`
##
INSERT INTO `phpbb_user_group` (`group_id`, `user_id`, `user_pending`) VALUES (1, -1, 0);
INSERT INTO `phpbb_user_group` (`group_id`, `user_id`, `user_pending`) VALUES (2, 2, 0);
INSERT INTO `phpbb_user_group` (`group_id`, `user_id`, `user_pending`) VALUES (3, 2, 0);
INSERT INTO `phpbb_user_group` (`group_id`, `user_id`, `user_pending`) VALUES (4, 2, 0);
INSERT INTO `phpbb_user_group` (`group_id`, `user_id`, `user_pending`) VALUES (5, 2, 0);

## `phpbb_users`
##
#INSERT INTO `phpbb_users` (`user_id`, `user_active`, `username`, `user_password`, `user_session_time`, `user_session_page`, `user_http_agents`, `user_lastvisit`, `user_regdate`, `user_level`, `user_cms_level`, `user_posts`, `user_timezone`, `user_style`, `user_lang`, `user_dateformat`, `user_new_privmsg`, `user_unread_privmsg`, `user_last_privmsg`, `user_emailtime`, `user_viewemail`, `user_profile_view_popup`, `user_attachsig`, `user_setbm`, `user_allowhtml`, `user_allowbbcode`, `user_allowsmile`, `user_allowavatar`, `user_allow_pm`, `user_allow_viewonline`, `user_notify`, `user_notify_pm`, `user_popup_pm`, `user_rank`, `user_rank2`, `user_rank3`, `user_rank4`, `user_rank5`, `user_avatar`, `user_avatar_type`, `user_email`, `user_icq`, `user_website`, `user_from`, `user_sig`, `user_aim`, `user_yim`, `user_msnm`, `user_occ`, `user_interests`, `user_actkey`, `user_newpasswd`, `user_birthday`, `user_next_birthday_greeting`, `user_sub_forum`, `user_split_cat`, `user_last_topic_title`, `user_sub_level_links`, `user_display_viewonline`, `user_color_group`, `user_color`, `user_gender`, `user_lastlogon`, `user_totaltime`, `user_totallogon`, `user_totalpages`, `user_calendar_display_open`, `user_calendar_header_cells`, `user_calendar_week_start`, `user_calendar_nb_row`, `user_calendar_birthday`, `user_calendar_forum`, `user_warnings`, `user_time_mode`, `user_dst_time_lag`, `user_pc_timeOffsets`, `user_skype`, `user_registered_ip`, `user_registered_hostname`, `user_profile_view`, `user_last_profile_view`, `user_topics_per_page`, `user_hot_threshold`, `user_posts_per_page`, `user_allowswearywords`, `user_showavatars`, `user_showsignatures`, `user_login_tries`, `user_last_login_try`, `user_sudoku_playing`, `user_from_flag`, `user_phone`, `user_selfdes`, `user_upi2db_which_system`, `user_upi2db_disable`, `user_upi2db_datasync`, `user_upi2db_new_word`, `user_upi2db_edit_word`, `user_upi2db_unread_color`, `user_personal_pics_count`) VALUES (-2, 0, 'Bot', '', 0, 0, '', 0, 0, 0, 0, 0, 0.00, NULL, '', '', 0, 0, 0, NULL, 0, 0, 0, 0, 1, 1, 1, 1, 0, 1, 0, 0, 0, NULL, -1, -2, -2, -2, '', 0, '', '', '', '', '', '', '', '', '', '', '', '', 999999, 0, 1, 1, 1, 2, 2, 0, '', 0, 0, 0, 0, 0, 0, 0, 1, 5, 1, 1, 0, 2, 60, '0', NULL, NULL, NULL, 0, 0, '50', '15', '15', 0, 1, 1, 0, 0, 0, NULL, NULL, NULL, 1, 0, 0, 1, 1, 1, 0);
INSERT INTO `phpbb_users` (`user_id`, `user_active`, `username`, `user_password`, `user_session_time`, `user_session_page`, `user_http_agents`, `user_lastvisit`, `user_regdate`, `user_level`, `user_cms_level`, `user_posts`, `user_timezone`, `user_style`, `user_lang`, `user_dateformat`, `user_new_privmsg`, `user_unread_privmsg`, `user_last_privmsg`, `user_emailtime`, `user_viewemail`, `user_profile_view_popup`, `user_attachsig`, `user_setbm`, `user_allowhtml`, `user_allowbbcode`, `user_allowsmile`, `user_allowavatar`, `user_allow_pm`, `user_allow_viewonline`, `user_notify`, `user_notify_pm`, `user_popup_pm`, `user_rank`, `user_rank2`, `user_rank3`, `user_rank4`, `user_rank5`, `user_avatar`, `user_avatar_type`, `user_email`, `user_icq`, `user_website`, `user_from`, `user_sig`, `user_aim`, `user_yim`, `user_msnm`, `user_occ`, `user_interests`, `user_actkey`, `user_newpasswd`, `user_birthday`, `user_next_birthday_greeting`, `user_sub_forum`, `user_split_cat`, `user_last_topic_title`, `user_sub_level_links`, `user_display_viewonline`, `user_color_group`, `user_color`, `user_gender`, `user_lastlogon`, `user_totaltime`, `user_totallogon`, `user_totalpages`, `user_calendar_display_open`, `user_calendar_header_cells`, `user_calendar_week_start`, `user_calendar_nb_row`, `user_calendar_birthday`, `user_calendar_forum`, `user_warnings`, `user_time_mode`, `user_dst_time_lag`, `user_pc_timeOffsets`, `user_skype`, `user_registered_ip`, `user_registered_hostname`, `user_profile_view`, `user_last_profile_view`, `user_topics_per_page`, `user_hot_threshold`, `user_posts_per_page`, `user_allowswearywords`, `user_showavatars`, `user_showsignatures`, `user_login_tries`, `user_last_login_try`, `user_sudoku_playing`, `user_from_flag`, `user_phone`, `user_selfdes`, `user_upi2db_which_system`, `user_upi2db_disable`, `user_upi2db_datasync`, `user_upi2db_new_word`, `user_upi2db_edit_word`, `user_upi2db_unread_color`, `user_personal_pics_count`) VALUES (-1, 0, 'Anonymous', '', 0, 0, '', 0, 0, 0, 0, 0, 0.00, NULL, '', '', 0, 0, 0, NULL, 0, 0, 0, 0, 1, 1, 1, 1, 0, 1, 0, 0, 0, NULL, -1, -2, -2, -2, '', 0, '', '', '', '', '', '', '', '', '', '', '', '', 999999, 0, 1, 1, 1, 2, 2, 0, '', 0, 0, 0, 0, 0, 0, 0, 1, 5, 1, 1, 0, 2, 60, '0', NULL, NULL, NULL, 0, 0, '50', '15', '15', 0, 1, 1, 0, 0, 0, NULL, NULL, NULL, 1, 0, 0, 1, 1, 1, 0);
INSERT INTO `phpbb_users` (`user_id`, `user_active`, `username`, `user_password`, `user_session_time`, `user_session_page`, `user_http_agents`, `user_lastvisit`, `user_regdate`, `user_level`, `user_cms_level`, `user_posts`, `user_timezone`, `user_style`, `user_lang`, `user_dateformat`, `user_new_privmsg`, `user_unread_privmsg`, `user_last_privmsg`, `user_emailtime`, `user_viewemail`, `user_profile_view_popup`, `user_attachsig`, `user_setbm`, `user_allowhtml`, `user_allowbbcode`, `user_allowsmile`, `user_allowavatar`, `user_allow_pm`, `user_allow_viewonline`, `user_notify`, `user_notify_pm`, `user_popup_pm`, `user_rank`, `user_rank2`, `user_rank3`, `user_rank4`, `user_rank5`, `user_avatar`, `user_avatar_type`, `user_email`, `user_icq`, `user_website`, `user_from`, `user_sig`, `user_aim`, `user_yim`, `user_msnm`, `user_occ`, `user_interests`, `user_actkey`, `user_newpasswd`, `user_birthday`, `user_next_birthday_greeting`, `user_sub_forum`, `user_split_cat`, `user_last_topic_title`, `user_sub_level_links`, `user_display_viewonline`, `user_color_group`, `user_color`, `user_gender`, `user_lastlogon`, `user_totaltime`, `user_totallogon`, `user_totalpages`, `user_calendar_display_open`, `user_calendar_header_cells`, `user_calendar_week_start`, `user_calendar_nb_row`, `user_calendar_birthday`, `user_calendar_forum`, `user_warnings`, `user_time_mode`, `user_dst_time_lag`, `user_pc_timeOffsets`, `user_skype`, `user_registered_ip`, `user_registered_hostname`, `user_profile_view`, `user_last_profile_view`, `user_topics_per_page`, `user_hot_threshold`, `user_posts_per_page`, `user_allowswearywords`, `user_showavatars`, `user_showsignatures`, `user_login_tries`, `user_last_login_try`, `user_sudoku_playing`, `user_from_flag`, `user_phone`, `user_selfdes`, `user_upi2db_which_system`, `user_upi2db_disable`, `user_upi2db_datasync`, `user_upi2db_new_word`, `user_upi2db_edit_word`, `user_upi2db_unread_color`, `user_personal_pics_count`) VALUES (2, 1, 'Admin', '21232f297a57a5a743894a0e4a801fc3', 0, 0, '', 0, 0, 1, 5, 2, 0.00, 1, 'english', 'd M Y h:i a', 0, 0, 0, NULL, 1, 0, 0, 0, 0, 1, 1, 1, 1, 1, 0, 1, 1, 1, -1, -2, -2, -2, '', 0, 'admin@yourdomain.com', '', '', '', '', '', '', '', '', '', '', '', 999999, 0, 1, 1, 1, 2, 2, 3, '#DD2222', 0, 0, 0, 0, 0, 0, 0, 1, 5, 1, 1, 0, 2, 60, '0', NULL, NULL, NULL, 0, 0, '50', '15', '15', 0, 1, 1, 0, 0, 0, NULL, NULL, NULL, 1, 0, 0, 1, 1, 1, 0);

## `phpbb_vote_desc`
##

## `phpbb_vote_results`
##

## `phpbb_vote_voters`
##

## `phpbb_words`
##

## `phpbb_xs_news`
##

## `phpbb_xs_news_cfg`
##
INSERT INTO `phpbb_xs_news_cfg` (`config_name`, `config_value`) VALUES ('xs_show_news', '0');
INSERT INTO `phpbb_xs_news_cfg` (`config_name`, `config_value`) VALUES ('xs_show_ticker', '0');
INSERT INTO `phpbb_xs_news_cfg` (`config_name`, `config_value`) VALUES ('xs_news_dateformat', '2');
INSERT INTO `phpbb_xs_news_cfg` (`config_name`, `config_value`) VALUES ('xs_show_ticker_subtitle', '0');
INSERT INTO `phpbb_xs_news_cfg` (`config_name`, `config_value`) VALUES ('xs_show_news_subtitle', '0');

## `phpbb_xs_news_xml`
##
INSERT INTO `phpbb_xs_news_xml` (`xml_id`, `xml_title`, `xml_show`, `xml_feed`, `xml_is_feed`, `xml_width`, `xml_height`, `xml_font`, `xml_speed`, `xml_direction`) VALUES (1, 'BBC News UK Edition', 1, 'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/world/rss.xml', 1, '98%', '20', '0', '3', 0);
INSERT INTO `phpbb_xs_news_xml` (`xml_id`, `xml_title`, `xml_show`, `xml_feed`, `xml_is_feed`, `xml_width`, `xml_height`, `xml_font`, `xml_speed`, `xml_direction`) VALUES (2, 'Simple Text Test', 1, 'This is just some text I want to scroll, it could contain just about anything you like', 0, '98%', '20', '13', '3', 0);
INSERT INTO `phpbb_xs_news_xml` (`xml_id`, `xml_title`, `xml_show`, `xml_feed`, `xml_is_feed`, `xml_width`, `xml_height`, `xml_font`, `xml_speed`, `xml_direction`) VALUES (3, 'Exchange', 1, 'http://rss.msexchange.org/allnews.xml', 1, '98%', '20', '0', '3', 0);

## DOWNLOADS - BEGIN
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_mod_version', '5.3.0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('delay_auto_traffic', '30');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('delay_post_traffic', '30');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('disable_email', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('disable_popup', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('disable_popup_notify', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_click_reset_time', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_direct', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_edit_time', '3');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_links_per_page', '10');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_method', '2');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_method_quota', '2097152');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_new_time', '3');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_posts', '25');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_stats_perm', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('download_dir', 'downloads/');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('download_vc', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('edit_own_downloads', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('enable_post_dl_traffic', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('guest_stats_show', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('hotlink_action', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('icon_free_for_reg', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('latest_comments', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('limit_desc_on_index', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('newtopic_traffic', '524288');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('overall_traffic', '104857600');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('physical_quota', '524288000');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('prevent_hotlink', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('recent_downloads', '10');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('remain_traffic', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('reply_traffic', '262144');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('report_broken', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('report_broken_lock', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('report_broken_message', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('report_broken_vc', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('shorten_extern_links', '10');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('show_footer_legend', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('show_footer_stat', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('show_real_filetime', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('stop_uploads', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('sort_preform', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('thumb_fsize', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('thumb_xsize', '200');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('thumb_ysize', '150');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('traffic_retime', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('upload_traffic_count', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('use_ext_blacklist', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('use_hacklist', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('user_dl_auto_traffic', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('user_traffic_once', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('user_download_limit_flag', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('user_download_limit', '30');

INSERT INTO phpbb_dl_banlist (user_agent) VALUES ('n/a');

INSERT INTO phpbb_dl_ext_blacklist (extention) VALUES
	('asp'), ('cgi'), ('dhtm'), ('dhtml'), ('exe'), ('htm'), ('html'), ('jar'), ('js'), ('php'), ('php3'), ('pl'), ('sh'), ('shtm'), ('shtml');
## DOWNLOADS - END

