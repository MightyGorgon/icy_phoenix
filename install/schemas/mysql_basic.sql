## Better leaving these input at the beginning... so they will be inserted as first values into tables
## Roll on version
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('version', '.0.23');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ip_version', '2.2.2.109');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cms_version', '2.0.0');
## INSERT INTO phpbb_link_config (config_name, config_value) VALUES ('site_logo', 'http://www.icyphoenix.com/images/icy_phoenix_logo.png');
## INSERT INTO phpbb_link_config (config_name, config_value) VALUES ('site_url', 'http://www.icyphoenix.com/');


## `phpbb_acronyms`
##

## `phpbb_adminedit`
##

## `phpbb_attach_quota`
##

## `phpbb_attachments`
##

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
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Bing', '<b style="color:#468;">Bing</b>', 'bingbot/', '');
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
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('AhrefsBot', '', 'AhrefsBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Alexa', '', 'ia_archiver', '207.209.238');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Alta Vista', '', 'Scooter/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('AllTheWeb', '', 'alltheweb', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Archive ORG BOT', '', 'www.archive.org/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Arianna', '', 'www.arianna.it', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ask Jeeves', '', 'Ask Jeeves', '65.214.44');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ask Jeeves', '', 'teoma', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Baidu [Spider]', '', 'Baiduspider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Become', '', 'BecomeBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Bloglines', '', 'Bloglines/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Charlotte', '', 'Charlotte/1.1', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('DotBot', '', 'DotBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('eBay', '', '', '212.222.51');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('eDintorni Crawler', '', 'eDintorni', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Exabot', '', 'Exabot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ezooms', '', 'Ezooms/', '');
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
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Jike Spider', '', 'jikespider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('IBM Research', '', 'ibm.com/cs/crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ICCrawler - ICjobs', '', 'ICCrawler - ICjobs', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ichiro [Crawler]', '', 'ichiro/2', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('IEAutoDiscovery', '', 'IEAutoDiscovery', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Indy Library', '', 'Indy Library', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Infoseek', '', 'Infoseek', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Inktomi', '', '', '66.94.229, 66.228.165');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('LookSmart', '', 'MARTINI', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Lycos', '', 'Lycos', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Magpie Crawler', '', 'www.brandwatch.net', '');
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
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('P3W Bot', '', 'www.p3w.it', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Perl Script', '', 'libwww-perl/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Pompos', '', '', '212.27.41');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('psbot [Picsearch]', '', 'psbot/0', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Radian 6', '', 'www.radian6.com/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ScoutJet', '', 'http://www.scoutjet.com/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Seekport', '', 'Seekbot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Sensis [Crawler]', '', 'Sensis Web Crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('SEO Crawler [Crawler]', '', 'SEO search Crawler/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Seoma [Crawler]', '', 'Seoma', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('SEOSearch [Crawler]', '', 'SEOsearch/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snap Bot', '', 'Snapbot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snappy', '', 'Snappy/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snarfer', '', 'Snarfer/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Soso Spider', '', 'Sosospider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Speedy Spider', '', 'Speedy Spider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Steeler [Crawler]', '', 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Synoo', '', 'SynooBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Synthesio Crawler', '', 'synthesio', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Telekom', '', 'crawleradmin.t-info@telekom.de', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('TurnitinBot', '', 'TurnitinBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Twiceler', '', 'Twiceler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Vik Spider', '', 'vikspider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Virgilio', '', '', '212.48.8');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Voyager', '', 'voyager/1.0', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Voila', '', 'VoilaBot', '195.101.94');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3 [Sitesearch]', '', 'W3 SiteSearch Crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3C [Linkcheck]', '', 'W3C-checklink/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3C [Validator]', '', 'W3C_', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WangID Spider', '', 'WangIDSpider/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WikioFeedBot', '', 'WikioFeedBot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WiseNut', '', 'http://www.WISEnutbot.com', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('YaCy', '', 'yacybot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yandex', '', 'Yandex/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('YandexBot 3.0', '', 'yandex.com/bots', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yanga WorldSearch', '', 'Yanga WorldSearch Bot', '');

## `phpbb_cms_block_position`
##

INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (1, 'header', 'hh', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (2, 'headerleft', 'hl', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (3, 'headercenter', 'hc', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (4, 'footercenter', 'fc', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (5, 'footerright', 'fr', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (6, 'footer', 'ff', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (7, 'gheader', 'gh', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (8, 'gfooter', 'gf', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (9, 'ghtop', 'gt', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (10, 'ghbottom', 'gb', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (11, 'ghleft', 'gl', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (12, 'ghright', 'gr', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (13, 'left', 'l', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (14, 'center', 'c', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (15, 'right', 'r', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (16, 'xsnews', 'x', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (17, 'nav', 'n', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (18, 'centerbottom', 'b', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (19, 'left', 'l', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (20, 'center', 'c', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (21, 'xsnews', 'x', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (22, 'nav', 'n', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (23, 'centerbottom', 'b', 2);

## `phpbb_cms_block_settings`
##

INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(1, 0, 'Nav Links', '', 'nav_links', 0, 0, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(2, 0, 'Recent', '', 'recent_topics', 0, 0, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(3, 0, 'Poll', '', 'poll', 0, 0, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(4, 0, 'Welcome', '', 'welcome', 0, 0, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(5, 0, 'News', '', 'news', 0, 0, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(6, 0, 'User Block', '', 'user_block', 0, 1, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(7, 0, 'Top Posters', '', 'top_posters', 0, 0, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(8, 0, 'Search', '', 'search', 0, 1, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(9, 0, 'Who is Online', '', 'online_users', 0, 1, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(10, 0, 'Welcome', '<table>\r\n	<tr>\r\n		<td width="5%"><img src="images/icy_phoenix_small.png" alt="" /></td>\r\n		<td width="90%" align="center"><div class="post-text">Welcome To <b>Icy Phoenix</b></div><br /><br /></td>\r\n		<td width="5%"><img src="images/icy_phoenix_small_l.png" alt="" /></td>\r\n	</tr>\r\n</table>', '', 0, 0, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(11, 0, 'Statistics', '', 'statistics', 0, 0, '', 1);
INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(12, 0, 'Wordgraph', '', 'wordgraph', 0, 0, '', 1);

## `phpbb_cms_block_variable`
##

INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (1, 0, 'Default Portal', 'Default Portal', 'default_portal', '', '', 1, '@Portal Config');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (2, 0, 'Header width', 'Width of forum-wide left column in pixels', 'header_width', '', '', 1, '@Portal Config');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (3, 0, 'Footer width', 'Width of forum-wide right column in pixels', 'footer_width', '', '', 1, '@Portal Config');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (4, 2, 'Number of recent topics', 'number of topics displayed', 'md_num_recent_topics', '', '', 1, 'recent_topics');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (5, 2, 'Recent Topics Style', 'choose static display or scrolling display', 'md_recent_topics_style', 'Scroll,Static', '1,0', 3, 'recent_topics');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (6, 3, 'Poll To Display', 'Choose if you want to show latest poll, random or a specific poll', 'md_poll_type', 'Newest,Random,Specific', '0,1,2', 3, 'poll');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (7, 3, 'Poll Forum ID(s)', 'Type the forum ID(s) comma delimited if you\'ve choosen latest or random poll', 'md_poll_forum_id', '', '', 1, 'poll');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (8, 3, 'Poll Topic ID', 'Type the topic ID if you\'ve choosen specific poll', 'md_poll_topic_id', '', '', 1, 'poll');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (9, 3, 'Poll Bar Length', 'decrease/increase the value for 1 vote bar length', 'md_poll_bar_length', '', '', 1, 'poll');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (10, 7, 'Number of Top Posters', '', 'md_total_poster', '', '', 1, 'top_posters');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (11, 8, 'Search option text', 'Text displayed as the default option', 'md_search_option_text', '', '', 1, 'search');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (12, 12, 'Maximum Words', 'Select the maximum number of words to display', 'md_wordgraph_words', '', '', 1, 'wordgraph');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (13, 12, 'Enable Word Counts', 'Display the total number of words next to each word', 'md_wordgraph_count', 'Yes,No', '1,0', 3, 'wordgraph');

## `phpbb_cms_blocks`
##

INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(1, 1, 0, 1, 0, 'Nav Links', 'l', 1, 1, 0, 0, 0, 0);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(2, 2, 0, 1, 0, 'Recent', 'l', 3, 0, 1, 1, 1, 1);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(3, 3, 0, 1, 0, 'Poll', 'r', 4, 1, 1, 1, 1, 1);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(4, 4, 0, 1, 0, 'Welcome', 'c', 1, 1, 1, 1, 1, 1);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(5, 5, 0, 1, 0, 'News', 'x', 1, 1, 0, 0, 0, 0);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(6, 6, 0, 1, 0, 'User Block', 'r', 1, 1, 1, 1, 1, 1);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(7, 7, 0, 1, 0, 'Top Posters', 'r', 5, 1, 1, 1, 1, 1);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(8, 8, 0, 1, 0, 'Search', 'l', 1, 1, 1, 1, 1, 1);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(9, 9, 0, 1, 0, 'Who is Online', 'r', 2, 1, 1, 1, 1, 1);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(10, 10, 0, 1, 0, 'Welcome', 'c', 2, 1, 1, 1, 1, 1);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(11, 11, 0, 1, 0, 'Statistics', 'r', 3, 1, 1, 1, 1, 1);
INSERT INTO `phpbb_cms_blocks` (`bid`, `bs_id`, `block_cms_id`, `layout`, `layout_special`, `title`, `bposition`, `weight`, `active`, `border`, `titlebar`, `background`, `local`) VALUES(12, 12, 0, 1, 0, 'Wordgraph', 'b', 2, 1, 0, 0, 0, 1);

## `phpbb_cms_config`
##

INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (1, 0, 'default_portal', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (2, 0, 'header_width', '180');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (3, 0, 'footer_width', '150');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (4, 2, 'md_recent_topics_style', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (5, 2, 'md_num_recent_topics', '10');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (6, 3, 'md_poll_type', '0');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (7, 3, 'md_poll_forum_id', '');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (8, 3, 'md_poll_topic_id', '');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (9, 3, 'md_poll_bar_length', '65');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (10, 7, 'md_total_poster', '5');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (11, 8, 'md_search_option_text', 'Icy Phoenix');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (12, 12, 'md_wordgraph_words', '250');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (13, 12, 'md_wordgraph_count', '1');

## `phpbb_cms_layout`
##

INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `filename`, `template`, `layout_cms_id`, `global_blocks`, `page_nav`, `config_vars`, `view`, `groups`) VALUES(1, '3 Columns', '', '3_column.tpl', 0, 0, 1, '', 0, '');
INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `filename`, `template`, `layout_cms_id`, `global_blocks`, `page_nav`, `config_vars`, `view`, `groups`) VALUES(2, '2 Columns', '', '2_column.tpl', 0, 0, 1, '', 0, '');

## `phpbb_cms_layout_special`
##

INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('forum', 'forum', 'forum.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('viewforum', 'viewforum', 'viewforum.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('viewtopic', 'viewtopic', 'viewtopic.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('viewonline', 'viewonline', 'viewonline.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('search', 'search', 'search.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('profile', 'profile', 'profile.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('memberlist', 'memberlist', 'memberlist.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('groupcp', 'groupcp', 'groupcp.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('faq', 'faq', 'faq.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('rules', 'rules', 'rules.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('download', 'download', 'dload.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('statistics', 'statistics', 'statistics.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('calendar', 'calendar', 'calendar.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('recent', 'recent', 'recent.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('referers', 'referers', 'referers.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('shoutbox', 'shoutbox', 'shoutbox_max.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('kb', 'kb', 'kb.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('contact_us', 'contact_us', 'contact_us.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('attachments', 'attachments', 'attachments.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('ranks', 'ranks', 'ranks.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('ajax_chat', 'ajax_chat', 'ajax_chat.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('ajax_chat_archive', 'ajax_chat_archive', 'ajax_chat.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('privacy_policy', 'privacy_policy', 'privacy_policy.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('cookie_policy', 'cookie_policy', 'cookie_policy.php', 0, '', 0, '');
#INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('pic_upload', 'pic_upload', 'upload.php', 0, '', 0, '');

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
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (20, 0, 1, 0, 1, 14, 1, 14, '', '', 'Posted Images', 'Posted Images', 'images_list.php', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (21, 0, 1, 0, 1, 15, 1, 15, '', '', 'Chat', 'Chat', 'ajax_chat.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (23, 0, 1, 0, 1, 17, 1, 17, '', '', 'Knowledge Base', 'Knowledge Base', 'kb.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (24, 0, 1, 0, 1, 18, 1, 18, '', '', 'Contact Us', 'Contact Us', 'contact_us.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (25, 0, 1, 0, 1, 19, 1, 19, '', '', 'Rules', 'Rules', 'rules.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (27, 0, 1, 0, 2, 22, 1, 1, '', '', 'News Categories', 'News Categories', 'index.php?news=categories', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (28, 0, 1, 0, 2, 23, 1, 2, '', '', 'News Archives', 'News Archives', 'index.php?news=archives', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (29, 0, 1, 0, 2, 24, 1, 3, '', '', 'New Messages', 'New Messages', 'search.php?search_id=newposts', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (30, 0, 1, 0, 2, 25, 1, 4, '', '', 'Unread Posts', 'Unread Posts', 'search.php?search_id=upi2db&s2=new', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (31, 0, 1, 0, 2, 26, 1, 5, '', '', 'Marked Posts', 'Marked Posts', 'search.php?search_id=upi2db&s2=mark', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (32, 0, 1, 0, 2, 27, 1, 6, '', '', 'Permanent Read', 'Permanent Read', 'search.php?search_id=upi2db&s2=perm', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (33, 0, 1, 0, 2, 29, 1, 7, '', '', 'Digests', 'Digests', 'digests.php', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (34, 0, 1, 0, 3, 0, 1, 1, '', '', 'Credits', 'Credits', 'credits.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (35, 0, 1, 0, 3, 31, 1, 2, '', '', 'Http Referers', 'Http Referers', 'referers.php', 0, 0, 0);
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_html_tags', 'a,b,i,u,pre,table,tr,td');
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_user_bancard', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('report_forum', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bin_forum', '5');
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_link_bookmarks', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('visit_counter', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('word_graph_max_words', '250');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('word_graph_word_counts', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('search_min_chars', '3');
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('index_birthday', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_history', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilies_topic_title', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('html_email', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('config_cache', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('admin_protect', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftr_disable', '1');
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('switch_poster_info_topic', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('switch_bbcb_active_content', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_quick_quote', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_xs_version_check', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_all_bbcode', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('forum_wordgraph', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_topics', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_stopwords', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_ignore_forums_ids', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_sort_type', 'relev');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_max_topics', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('shoutbox_floodinterval', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('display_shouts', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('stored_shouts', '1000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('shout_allow_guest', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xmas_gfx', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('google_bot_detector', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('logs_path', 'logs');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_max_new_posts_admin', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_max_new_posts_mod', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('url_rw_guests', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('lofi_bots', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_checks_register', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('inactive_users_memberlists', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_pic_upload', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_postimage_org', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_new_messages_number', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_features', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_rss_forum_icon', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_acronyms', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_autolinks', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_censor', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_topic_view', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('page_title_simple', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_referers', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('mg_log_actions', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('active_users_color', '#224455');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('active_users_legend', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bots_color', '#888888');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bots_legend', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_social_bookmarks', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_forums_online_users', '0');
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_alpha_bar', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('db_log_actions', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_topic_description', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bots_reg_auth', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_global_switch', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_lock', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_lock_hour', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_queue_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_queue_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_digests_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_digests_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_files_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_files_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_birthdays_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_birthdays_last_run', '0');
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_site_history_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_site_history_last_run', '0');
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('read_only_forum', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('forum_limit_edit_time_interval', '1440');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftr_topic_number', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftr_message', 'Before going on... please make sure you have read and understood this post. It contains important informations regarding this site.');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftr_install_time', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftr_all_users', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_html_only_for_admins', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('display_tags_box', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_moderators_edit_tags', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_custom_bbcodes', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('forum_tags_type', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smtp_port', '25');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_likes_posts', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ip_admins_only', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('attachments_stats', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('robots_index_topics_no_replies', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('limit_load', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('limit_search_load', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ip_check', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('browser_check', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('referer_validation', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('force_server_vars', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('session_last_gc', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('active_sessions', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('form_token_lifetime', '7200');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_keywords', 'your keywords, comma, separated');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_keywords_switch', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_description', 'Your Site Description');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_description_switch', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_author', 'Author');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_author_switch', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_copyright', 'Copyright');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_copyright_switch', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('spam_posts_number', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('spam_disable_url', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('spam_hide_signature', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('spam_post_edit_interval', '60');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('mobile_style_disable', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('session_gc', '3600');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('session_last_visit_reset', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('check_dnsbl', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('check_dnsbl_posting', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_chat_msgs_refresh', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_chat_session_refresh', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_chat_link_type', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_chat_notification', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_chat_check_online', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('google_custom_search', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('use_jquery_tags', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('user_allow_pm_register', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_social_connect', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_facebook_login', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('facebook_app_id', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('facebook_app_secret', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('thumbnail_s_size', '120');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_list_cols', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_list_rows', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cookie_law', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_size_max_mp', '1');


## ATTACHMENTS - BEGIN
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upload_dir', 'files');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upload_img', 'images/attach_post.png');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('topic_icon', 'images/disk_multiple.png');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('display_order', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_filesize', '262144');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('attachment_quota', '52428800');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_filesize_pm', '262144');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_attachments', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_attachments_pm', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_attachments_mod', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_pm_attach', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('attachment_topic_review', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_ftp_upload', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_apcp', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('attach_version', '2.4.5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_upload_quota', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_pm_quota', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftp_server', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftp_path', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('download_path', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftp_user', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftp_pass', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftp_pasv_mode', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_display_inlined', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_max_width', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_max_height', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_link_width', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_link_height', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_create_thumbnail', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_min_thumb_filesize', '12000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_imagick', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('use_gd2', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wma_autoplay', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('flash_autoplay', '0');
## ATTACHMENTS - END
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
## XS - NEWS - BEGIN
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xs_show_news', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xs_show_ticker', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xs_news_dateformat', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xs_show_ticker_subtitle', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xs_show_news_subtitle', '0');
## XS - NEWS - END
## CTracker Settings - BEGIN
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_ipblock_enabled', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_ipblock_logsize', '100');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_auto_recovery', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_vconfirm_guest', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_autoban_mails', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_time_guest', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_time_user', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_count_guest', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_count_user', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_massmail_protection', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_protection', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_blocktime', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_lastip', '0.0.0.0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pwreset_time', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_massmail_time', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_time', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_postcount', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_blockmode', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_loginfeature', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_reset_feature', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_last_reg', '1155944976');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_login_history', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_login_history_count', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_login_ip_check', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_validity', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_complex_min', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_complex_mode', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_control', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_complex', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_last_file_scan', '1156000091');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_last_checksum_scan', '1156000082');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_logsize_logins', '100');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_logsize_spammer', '100');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_ip_scan', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_global_message', 'Hello world!');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_global_message_type', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_feature_enabled', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spam_attack_boost', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spam_keyword_det', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_footer_layout', '6');
## CTracker Settings - END
## CAPTCHA Settings - BEGIN
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_width', '316');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_height', '61');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_background_color', '#E5ECF9');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_jpeg', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_jpeg_quality', '50');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_pre_letters', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_pre_letters_great', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_font', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_chess', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_ellipses', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_arcs', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_lines', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_image', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_gammacorrect', '1.4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_foreground_lattice_x', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_foreground_lattice_y', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_lattice_color', '#FFFFFF');
## CAPTCHA Settings - END

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
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Afghanistan', 'afghanistan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('AI', 'ai.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Albania', 'albania.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Algeria', 'algeria.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('AN', 'an.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Andorra', 'andorra.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Antiguabarbuda', 'antiguabarbuda.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('AO', 'ao.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Argentina', 'argentina.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Armenia', 'armenia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('AS', 'as.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Australia', 'australia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Austria', 'austria.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('AW', 'aw.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('AX', 'ax.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Azerbaijan', 'azerbaijan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Bahamas', 'bahamas.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Bahrain', 'bahrain.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Bangladesh', 'bangladesh.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Barbados', 'barbados.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Belarus', 'belarus.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Belgium', 'belgium.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Belize', 'belize.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Benin', 'benin.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Bhutan', 'bhutan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('BM', 'bm.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Bolivia', 'bolivia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Bosnia Herzegovina', 'bosnia_herzegovina.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Botswana', 'botswana.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Brazil', 'brazil.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Brunei', 'brunei.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Bulgaria', 'bulgaria.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Burkinafaso', 'burkinafaso.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Burma', 'burma.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Burundi', 'burundi.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('BV', 'bv.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Cambodia', 'cambodia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Cameroon', 'cameroon.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Canada', 'canada.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('CC', 'cc.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Central African Republic', 'central_african_republic.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Chad', 'chad.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Chile', 'chile.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('China', 'china.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('CK', 'ck.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Columbia', 'columbia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Comoros', 'comoros.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Congo', 'congo.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Costa Rica', 'costa_rica.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Croatia', 'croatia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Cuba', 'cuba.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('CV', 'cv.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('CX', 'cx.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Cyprus', 'cyprus.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Czech Republic', 'czech_republic.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Dem Rep Congo', 'dem_rep_congo.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Denmark', 'denmark.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Djibouti', 'djibouti.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Dominica', 'dominica.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Dominican Rep', 'dominican_rep.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Ecuador', 'ecuador.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Egypt', 'egypt.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('EH', 'eh.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Elsalvador', 'elsalvador.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('England', 'england.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Eq Guinea', 'eq_guinea.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Eritrea', 'eritrea.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Estonia', 'estonia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Ethiopia', 'ethiopia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('FAM', 'fam.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Fiji', 'fiji.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Finland', 'finland.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('FK', 'fk.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('FO', 'fo.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('France', 'france.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Gabon', 'gabon.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Gambia', 'gambia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Georgia', 'georgia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Germany', 'germany.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Ghana', 'ghana.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('GI', 'gi.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('GL', 'gl.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('GP', 'gp.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Greece', 'greece.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Grenada', 'grenada.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Grenadines', 'grenadines.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('GS', 'gs.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('GU', 'gu.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Guatemala', 'guatemala.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Guinea', 'guinea.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Guinea Bissau', 'guinea_bissau.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Guyana', 'guyana.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Haiti', 'haiti.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Honduras', 'honduras.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Hong Kong', 'hong_kong.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Hungary', 'hungary.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Iceland', 'iceland.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('India', 'india.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Indonesia', 'indonesia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('IO', 'io.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Iran', 'iran.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Iraq', 'iraq.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Ireland', 'ireland.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Israel', 'israel.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Italia', 'italia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Ivory Coast', 'ivory_coast.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Jamaica', 'jamaica.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Japan', 'japan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Jordan', 'jordan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Kazakhstan', 'kazakhstan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Kenya', 'kenya.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Kiribati', 'kiribati.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('KP', 'kp.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Kuwait', 'kuwait.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('KY', 'ky.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Kyrgyzstan', 'kyrgyzstan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Laos', 'laos.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Latvia', 'latvia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Lebanon', 'lebanon.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Liberia', 'liberia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Libya', 'libya.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Liechtenstein', 'liechtenstein.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Lithuania', 'lithuania.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('LS', 'ls.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Luxembourg', 'luxembourg.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Macau', 'macau.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Madagascar', 'madagascar.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Malawi', 'malawi.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Malaysia', 'malaysia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Maldives', 'maldives.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Mali', 'mali.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Malta', 'malta.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Mauritania', 'mauritania.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Mauritius', 'mauritius.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Mexico', 'mexico.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('MH', 'mh.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Micronesia', 'micronesia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('MK', 'mk.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Moldova', 'moldova.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Monaco', 'monaco.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Mongolia', 'mongolia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Montenegro', 'montenegro.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Morocco', 'morocco.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Mozambique', 'mozambique.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('MP', 'mp.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('MS', 'ms.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Namibia', 'namibia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Nauru', 'nauru.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('NC', 'nc.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Nepal', 'nepal.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Netherlands', 'netherlands.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('New Zealand', 'new_zealand.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('NF', 'nf.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Nicaragua', 'nicaragua.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Niger', 'niger.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Nigeria', 'nigeria.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Norway', 'norway.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('NU', 'nu.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Oman', 'oman.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Pakistan', 'pakistan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Panama', 'panama.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Papua New Guinea', 'papua_new_guinea.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Paraguay', 'paraguay.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Peru', 'peru.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('PF', 'pf.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Philippines', 'philippines.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('PM', 'pm.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('PN', 'pn.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Poland', 'poland.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Portugal', 'portugal.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('PS', 'ps.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Puerto Rico', 'puerto_rico.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('PW', 'pw.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Qatar', 'qatar.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Quebec', 'quebec.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Romania', 'romania.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Russia', 'russia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Rwanda', 'rwanda.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Sao Tome', 'sao_tome.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Saudi Arabia', 'saudi_arabia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Scotland', 'scotland.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Senegal', 'senegal.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Serbia', 'serbia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Seychelles', 'seychelles.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('SH', 'sh.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Sierraleone', 'sierraleone.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Singapore', 'singapore.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Slovakia', 'slovakia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Slovenia', 'slovenia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('SM', 'sm.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Solomon Islands', 'solomon_islands.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Somalia', 'somalia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('South Africa', 'south_africa.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('South Korea', 'south_korea.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Spain', 'spain.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Sri Lanka', 'sri_lanka.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Stkitts Nevis', 'stkitts_nevis.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Stlucia', 'stlucia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Sudan', 'sudan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Suriname', 'suriname.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Sweden', 'sweden.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Switzerland', 'switzerland.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Syria', 'syria.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('SZ', 'sz.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Taiwan', 'taiwan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Tajikistan', 'tajikistan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Tanzania', 'tanzania.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('TF', 'tf.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Thailand', 'thailand.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('TK', 'tk.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('TL', 'tl.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Togo', 'togo.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Tonga', 'tonga.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Trinidat And Tobago', 'trinidat_and_tobago.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Tunisia', 'tunisia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Turkey', 'turkey.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Turkmenistan', 'turkmenistan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Tuvala', 'tuvala.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('TV', 'tv.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Uganda', 'uganda.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('UK', 'uk.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Ukraine', 'ukraine.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('UM', 'um.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('United Arabic Emirates', 'united_arabic_emirates.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Uruguay', 'uruguay.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('USA', 'usa.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Uzbekistan', 'uzbekistan.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Vanuatu', 'vanuatu.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Vatican', 'vatican.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Venezuela', 'venezuela.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('VG', 'vg.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('VI', 'vi.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Vietnam', 'vietnam.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Wales', 'wales.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Western Samoa', 'western_samoa.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('WF', 'wf.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Yemen', 'yemen.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('YT', 'yt.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Yugoslavia', 'yugoslavia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Zambia', 'zambia.png');
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Zimbabwe', 'zimbabwe.png');

## `phpbb_forbidden_extensions`
##
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (1, 'php');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (2, 'php3');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (3, 'php4');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (4, 'phtml');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (5, 'pl');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (6, 'asp');
INSERT INTO `phpbb_forbidden_extensions` (`ext_id`, `extension`) VALUES (7, 'cgi');

## `phpbb_force_read_users`
##

## `phpbb_forum_prune`
##

## `phpbb_forums`
##
INSERT INTO `phpbb_forums` (`forum_id`, `forum_type`, `parent_id`, `left_id`, `right_id`, `forum_parents`, `main_type`, `forum_name`, `forum_order`, `forum_desc`, `icon`) VALUES (1, 0, 0, 1, 4, '', 'c', 'Test category 1', 10, '', '');
INSERT INTO `phpbb_forums` (`forum_id`, `forum_type`, `parent_id`, `left_id`, `right_id`, `forum_parents`, `main_type`, `forum_name`, `forum_order`, `forum_desc`, `icon`) VALUES (2, 0, 0, 5, 10, '', 'c', 'Reporting', 30, '', '');

INSERT INTO `phpbb_forums` (`forum_id`, `forum_type`, `parent_id`, `left_id`, `right_id`, `forum_parents`, `main_type`, `forum_name`, `forum_desc`, `forum_status`, `forum_order`, `forum_posts`, `forum_topics`, `forum_last_post_id`, `forum_notify`, `forum_postcount`, `forum_link`, `forum_link_internal`, `forum_link_hit_count`, `forum_link_hit`, `icon`, `prune_next`, `prune_enable`, `auth_view`, `auth_read`, `auth_post`, `auth_reply`, `auth_edit`, `auth_delete`, `auth_sticky`, `auth_announce`, `auth_globalannounce`, `auth_news`, `auth_cal`, `auth_vote`, `auth_pollcreate`, `auth_attachments`, `auth_download`, `auth_ban`, `auth_greencard`, `auth_bluecard`, `auth_rate`) VALUES (3, 1, 1, 2, 3, '', 'c', 'Test Forum 1', 'This is just a test forum.', 0, 20, 2, 2, 2, 1, 1, NULL, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, 1, 1, 3, 1, 3, 3, 3, 1, 3, 1, 0, 3, 3, 1, 1);
INSERT INTO `phpbb_forums` (`forum_id`, `forum_type`, `parent_id`, `left_id`, `right_id`, `forum_parents`, `main_type`, `forum_name`, `forum_desc`, `forum_status`, `forum_order`, `forum_posts`, `forum_topics`, `forum_last_post_id`, `forum_notify`, `forum_postcount`, `forum_link`, `forum_link_internal`, `forum_link_hit_count`, `forum_link_hit`, `icon`, `prune_next`, `prune_enable`, `auth_view`, `auth_read`, `auth_post`, `auth_reply`, `auth_edit`, `auth_delete`, `auth_sticky`, `auth_announce`, `auth_globalannounce`, `auth_news`, `auth_cal`, `auth_vote`, `auth_pollcreate`, `auth_attachments`, `auth_download`, `auth_ban`, `auth_greencard`, `auth_bluecard`, `auth_rate`) VALUES (4, 1, 2, 6, 7, '', 'c', 'Reporting', 'All reports should be inserted here.', 0, 40, 0, 0, 0, 1, 1, NULL, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, 1, 1, 3, 1, 3, 3, 3, 1, 3, 1, 0, 3, 3, 1, 1);
INSERT INTO `phpbb_forums` (`forum_id`, `forum_type`, `parent_id`, `left_id`, `right_id`, `forum_parents`, `main_type`, `forum_name`, `forum_desc`, `forum_status`, `forum_order`, `forum_posts`, `forum_topics`, `forum_last_post_id`, `forum_notify`, `forum_postcount`, `forum_link`, `forum_link_internal`, `forum_link_hit_count`, `forum_link_hit`, `icon`, `prune_next`, `prune_enable`, `auth_view`, `auth_read`, `auth_post`, `auth_reply`, `auth_edit`, `auth_delete`, `auth_sticky`, `auth_announce`, `auth_globalannounce`, `auth_news`, `auth_cal`, `auth_vote`, `auth_pollcreate`, `auth_attachments`, `auth_download`, `auth_ban`, `auth_greencard`, `auth_bluecard`, `auth_rate`) VALUES (5, 1, 2, 8, 9, '', 'c', 'Recycle', 'Recycle bin forum.', 0, 50, 0, 0, 0, 1, 1, NULL, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, 1, 1, 3, 1, 3, 3, 3, 1, 3, 1, 0, 3, 3, 1, 1);

## `phpbb_forums_watch`
##

## `phpbb_google_bot_detector`
##

## `phpbb_groups`
##
INSERT INTO `phpbb_groups` (`group_id`, `group_type`, `group_name`, `group_description`, `group_moderator`, `group_single_user`, `group_color`, `group_count`, `group_count_max`, `group_count_enable`, `upi2db_on`, `upi2db_min_posts`, `upi2db_min_regdays`) VALUES (1, 1, 'Anonymous', 'Personal User', 0, 1, '', 99999999, 99999999, 0, 1, 0, 0);
INSERT INTO `phpbb_groups` (`group_id`, `group_type`, `group_name`, `group_description`, `group_moderator`, `group_single_user`, `group_color`, `group_count`, `group_count_max`, `group_count_enable`, `upi2db_on`, `upi2db_min_posts`, `upi2db_min_regdays`) VALUES (2, 1, 'Admin', 'Personal User', 0, 1, '', 99999999, 99999999, 0, 1, 0, 0);
INSERT INTO `phpbb_groups` (`group_id`, `group_type`, `group_name`, `group_description`, `group_moderator`, `group_single_user`, `group_color`, `group_legend`, `group_legend_order`, `group_count`, `group_count_max`, `group_count_enable`, `upi2db_on`, `upi2db_min_posts`, `upi2db_min_regdays`) VALUES (3, 1, 'Administrators', 'Administrators', 2, 0, '#dd2222', '1', '1', 99999999, 99999999, 0, 1, 0, 0);
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
INSERT INTO `phpbb_kb_categories` (`category_id`, `category_name`, `category_details`, `number_articles`, `parent`, `cat_order`, `auth_view`, `auth_post`, `auth_rate`, `auth_comment`, `auth_edit`, `auth_delete`, `auth_approval`, `auth_approval_edit`, `auth_view_groups`, `auth_post_groups`, `auth_rate_groups`, `auth_comment_groups`, `auth_edit_groups`, `auth_delete_groups`, `auth_approval_groups`, `auth_approval_edit_groups`, `auth_moderator_groups`, `comments_forum_id`) VALUES (1, 0x546573742043617465676f72792031, 0x54686973206973206120746573742063617465676f7279, 1, 0, 10, 0, 0, 0, 0, 0, 2, 0, 0, '', '', '', '', '', '', '', '', '', 1);

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
INSERT INTO `phpbb_kb_config` (`config_name`, `config_value`) VALUES ('allowed_html_tags', 'a,b,i,u');

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

## `phpbb_tickets_cat`
##
INSERT INTO phpbb_tickets_cat (ticket_cat_title, ticket_cat_des, ticket_cat_emails) VALUES ('General', 'General', '');

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
INSERT INTO `phpbb_posts` (`post_id`, `topic_id`, `forum_id`, `poster_id`, `post_time`, `poster_ip`, `post_username`, `enable_bbcode`, `enable_html`, `enable_smilies`, `enable_sig`, `post_edit_time`, `post_edit_count`, `post_attachment`, `post_bluecard`, `enable_autolinks_acronyms`, `post_subject`, `post_text`, `post_text_compiled`, `edit_notes`) VALUES (1, 1, 3, 2, 1241136000, '127.0.0.1', '', 1, 0, 1, 0, 1129068420, 0, 0, NULL, 1, 'Welcome to Icy Phoenix', 'If you can read this Topic it seems that you have successfully installed your new Forum using [b]Icy Phoenix[/b]. You should now visit the Admin Control Panel to configure some Settings. In ACP you can set the main settings and preferences for the whole sites (styles, languages, time, forums, download, users, album, etc.) while in CMS section you can configure options regarding the site pages (define permissions, add blocks, create new pages, create new menu, etc.). You may also want to configure [b].htaccess[/b] and [b]lang_main_settings.php[/b] (for each installed lang) to fine tune some other preferences, like error reporting, url rewrite, keywords, welcome message, charset and so on. Since everything seems to work fine you are now free to delete this Topic, this Forum and also the Category.\r\n\r\nShould you need any help you can refer to [url]http://www.icyphoenix.com/[/url] for support.\r\n\r\nThank you for choosing Icy Phoenix and remember to backup your db periodically.', '', '');
INSERT INTO `phpbb_posts` (`post_id`, `topic_id`, `forum_id`, `poster_id`, `post_time`, `poster_ip`, `post_username`, `enable_bbcode`, `enable_html`, `enable_smilies`, `enable_sig`, `post_edit_time`, `post_edit_count`, `post_attachment`, `post_bluecard`, `enable_autolinks_acronyms`, `post_subject`, `post_text`, `post_text_compiled`, `edit_notes`) VALUES (2, 2, 3, 2, 1241136000, '127.0.0.1', '', 1, 0, 1, 0, 1129111805, 0, 0, NULL, 1, 'Sample News Post in Home Page', 'As you can see this Topic is Attached to a News Category which is displayed in the Home Page. You can simply create News Postings in Home Page by Posting a Topic and select the News Category into which the News Message should be posted.\r\n\r\nHave Fun...', '', '');

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

## `phpbb_referers`
##
INSERT INTO `phpbb_referers` (`id`, `host`, `url`, `ip`, `hits`, `firstvisit`, `lastvisit`) VALUES (1, 'www.icyphoenix.com', 'http://icyphoenix.com', '127.0.0.1', 1, 1121336515, 1121336515);

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
INSERT INTO `phpbb_shout` (`shout_id`, `shout_username`, `shout_user_id`, `shout_group_id`, `shout_session_time`, `shout_ip`, `shout_text`, `shout_active`, `enable_bbcode`, `enable_html`, `enable_smilies`, `enable_sig`) VALUES (1, '', 2, 0, 1129051367, '127.0.0.1', 'Welcome to [b]Icy Phoenix[/b]', 0, 1, 0, 1, 0);

## `phpbb_site_history`
##

## `phpbb_smilies`
##
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (1, ':D', 'icon_biggrin.gif', 'Very Happy', 1);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (2, ':-D', 'icon_biggrin.gif', 'Very Happy', 2);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (3, ':grin:', 'icon_biggrin.gif', 'Very Happy', 3);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (4, ':)', 'icon_smile.gif', 'Smile', 4);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (5, ':-)', 'icon_smile.gif', 'Smile', 5);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (6, ':smile:', 'icon_smile.gif', 'Smile', 6);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (7, ':(', 'icon_sad.gif', 'Sad', 7);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (8, ':-(', 'icon_sad.gif', 'Sad', 8);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (9, ':sad:', 'icon_sad.gif', 'Sad', 9);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (10, ':o', 'icon_surprised.gif', 'Surprised', 10);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (11, ':-o', 'icon_surprised.gif', 'Surprised', 11);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (12, ':eek:', 'icon_surprised.gif', 'Surprised', 12);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (13, ':shock:', 'icon_eek.gif', 'Shocked', 13);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (14, ':?', 'icon_confused.gif', 'Confused', 14);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (15, ':-?', 'icon_confused.gif', 'Confused', 15);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (16, ':???:', 'icon_confused.gif', 'Confused', 16);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (17, '8)', 'icon_cool.gif', 'Cool', 17);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (18, '8-)', 'icon_cool.gif', 'Cool', 18);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (19, ':cool:', 'icon_cool.gif', 'Cool', 19);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (20, ':lol:', 'icon_lol.gif', 'Laughing', 20);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (21, ':x', 'icon_mad.gif', 'Mad', 21);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (22, ':-x', 'icon_mad.gif', 'Mad', 22);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (23, ':mad:', 'icon_mad.gif', 'Mad', 23);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (24, ':P', 'icon_razz.gif', 'Razz', 24);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (25, ':-P', 'icon_razz.gif', 'Razz', 25);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (26, ':razz:', 'icon_razz.gif', 'Razz', 26);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (27, ':oops:', 'icon_redface.gif', 'Embarassed', 27);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (28, ':cry:', 'icon_cry.gif', 'Crying or Very sad', 28);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad', 29);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (30, ':twisted:', 'icon_twisted.gif', 'Twisted Evil', 30);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes', 31);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (32, ':wink:', 'icon_wink.gif', 'Wink', 32);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (33, ';)', 'icon_wink.gif', 'Wink', 33);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (34, ';-)', 'icon_wink.gif', 'Wink', 34);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (35, ':!:', 'icon_exclaim.gif', 'Exclamation', 35);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (36, ':?:', 'icon_question.gif', 'Question', 36);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (37, ':idea:', 'icon_idea.gif', 'Idea', 37);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (38, ':arrow:', 'icon_arrow.gif', 'Arrow', 38);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (39, ':|', 'icon_neutral.gif', 'Neutral', 39);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (40, ':-|', 'icon_neutral.gif', 'Neutral', 40);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (41, ':neutral:', 'icon_neutral.gif', 'Neutral', 41);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (42, ':mricy:', 'icon_mricy.gif', 'Mr. Icy', 42);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (43, ':mrblue:', 'icon_mrblue.gif', 'Mr. Blue', 43);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (44, ':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green', 44);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (45, ':mrorange:', 'icon_mrorange.gif', 'Mr. Orange', 45);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (46, ':mrviolet:', 'icon_mrviolet.gif', 'Mr. Violet', 46);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (47, ':mryellow:', 'icon_mryellow.gif', 'Mr. Yellow', 47);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (48, ':mri:', 'icon_mricy.gif', 'Mr. Icy', 48);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (49, ':mrb:', 'icon_mrblue.gif', 'Mr. Blue', 49);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (50, ':mrg:', 'icon_mrgreen.gif', 'Mr. Green', 50);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (51, ':mro:', 'icon_mrorange.gif', 'Mr. Orange', 51);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (52, ':mrv:', 'icon_mrviolet.gif', 'Mr. Violet', 52);
INSERT INTO `phpbb_smilies` (`smilies_id`, `code`, `smile_url`, `emoticon`, `smilies_order`) VALUES (53, ':mry:', 'icon_mryellow.gif', 'Mr. Yellow', 53);

## `phpbb_stats_config`
##
INSERT INTO `phpbb_stats_config` (`config_name`, `config_value`) VALUES ('return_limit', '10');
INSERT INTO `phpbb_stats_config` (`config_name`, `config_value`) VALUES ('version', '2.1.5');
INSERT INTO `phpbb_stats_config` (`config_name`, `config_value`) VALUES ('modules_dir', 'includes/stats_modules');
INSERT INTO `phpbb_stats_config` (`config_name`, `config_value`) VALUES ('page_views', '0');

## `phpbb_stats_modules`
##

## `phpbb_themes`
##
INSERT INTO `phpbb_themes` (`themes_id`, `template_name`, `style_name`, `head_stylesheet`, `body_background`, `body_bgcolor`, `tr_class1`, `tr_class2`, `tr_class3`, `td_class1`, `td_class2`, `td_class3`) VALUES (1, 'icy_phoenix', 'Frozen Phoenix', 'style_cyan.css', 'cyan', '', 'row1', 'row2', 'row3', 'row1', 'row2', 'row3');

## `phpbb_topic_view`
##

## `phpbb_topics`
##
INSERT INTO `phpbb_topics` (`topic_id`, `forum_id`, `topic_title`, `topic_desc`, `topic_poster`, `topic_time`, `topic_views`, `topic_replies`, `topic_status`, `topic_type`, `topic_first_post_id`, `topic_last_post_id`, `topic_moved_id`, `topic_attachment`, `topic_label_compiled`, `news_id`, `topic_calendar_time`, `topic_calendar_duration`, `topic_rating`, `topic_show_portal`) VALUES (1, 3, 'Welcome to Icy Phoenix', '', 2, 1241136000, 0, 0, 0, 0, 1, 1, 0, 0, NULL, 0, NULL, NULL, 0, 0);
INSERT INTO `phpbb_topics` (`topic_id`, `forum_id`, `topic_title`, `topic_desc`, `topic_poster`, `topic_time`, `topic_views`, `topic_replies`, `topic_status`, `topic_type`, `topic_first_post_id`, `topic_last_post_id`, `topic_moved_id`, `topic_attachment`, `topic_label_compiled`, `news_id`, `topic_calendar_time`, `topic_calendar_duration`, `topic_rating`, `topic_show_portal`) VALUES (2, 3, 'Sample News Post in Portal', '', 2, 1241136000, 0, 0, 0, 4, 2, 2, 0, 0, NULL, 1, 0, 0, 0, 0);

## `phpbb_topics_labels`
##

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
#INSERT INTO `phpbb_users` (`user_id`, `user_active`, `username`, `username_clean`, `user_password`, `user_session_time`, `user_session_page`, `user_browser`, `user_lastvisit`, `user_regdate`, `user_level`, `user_posts`, `user_timezone`, `user_style`, `user_lang`, `user_dateformat`, `user_new_privmsg`, `user_unread_privmsg`, `user_last_privmsg`, `user_emailtime`, `user_allow_viewemail`, `user_profile_view_popup`, `user_attachsig`, `user_setbm`, `user_allowhtml`, `user_allowbbcode`, `user_allowsmile`, `user_allowavatar`, `user_allow_pm`, `user_allow_viewonline`, `user_notify`, `user_notify_pm`, `user_popup_pm`, `user_rank`, `user_rank2`, `user_rank3`, `user_rank4`, `user_rank5`, `user_avatar`, `user_avatar_type`, `user_email`, `user_icq`, `user_website`, `user_from`, `user_sig`, `user_aim`, `user_yim`, `user_msnm`, `user_occ`, `user_interests`, `user_actkey`, `user_newpasswd`, `user_birthday`, `user_next_birthday_greeting`, `user_sub_forum`, `user_split_cat`, `user_last_topic_title`, `user_sub_level_links`, `user_display_viewonline`, `group_id`, `user_color`, `user_gender`, `user_totaltime`, `user_totallogon`, `user_totalpages`, `user_calendar_display_open`, `user_calendar_header_cells`, `user_calendar_week_start`, `user_calendar_nb_row`, `user_calendar_birthday`, `user_calendar_forum`, `user_warnings`, `user_time_mode`, `user_dst_time_lag`, `user_skype`, `user_registered_ip`, `user_registered_hostname`, `user_profile_view`, `user_last_profile_view`, `user_topics_per_page`, `user_hot_threshold`, `user_posts_per_page`, `user_allowswearywords`, `user_showavatars`, `user_showsignatures`, `user_login_attempts`, `user_last_login_attempt`, `user_sudoku_playing`, `user_from_flag`, `user_phone`, `user_selfdes`, `user_upi2db_which_system`, `user_upi2db_disable`, `user_upi2db_datasync`, `user_upi2db_new_word`, `user_upi2db_edit_word`, `user_upi2db_unread_color`, `user_personal_pics_count`) VALUES (-2, 0, 'Bot', 'bot', '', 0, 0, '', 0, 0, 0, 0, 0.00, NULL, '', '', 0, 0, 0, NULL, 0, 0, 0, 0, 1, 1, 1, 1, 0, 1, 0, 0, 0, NULL, -1, -2, -2, -2, '', 0, '', '', '', '', '', '', '', '', '', '', '', '', 999999, 0, 1, 1, 1, 2, 2, 0, '', 0, 0, 0, 0, 0, 0, 1, 5, 1, 1, 0, 2, 60, '', '', '', 0, 0, '50', '15', '15', 0, 1, 1, 0, 0, 0, '', '', '', 1, 0, 0, 1, 1, 1, 0);
INSERT INTO `phpbb_users` (`user_id`, `user_active`, `username`, `username_clean`, `user_password`, `user_session_time`, `user_session_page`, `user_browser`, `user_lastvisit`, `user_regdate`, `user_level`, `user_posts`, `user_timezone`, `user_style`, `user_lang`, `user_dateformat`, `user_new_privmsg`, `user_unread_privmsg`, `user_last_privmsg`, `user_emailtime`, `user_allow_viewemail`, `user_profile_view_popup`, `user_attachsig`, `user_setbm`, `user_allowhtml`, `user_allowbbcode`, `user_allowsmile`, `user_allowavatar`, `user_allow_pm`, `user_allow_viewonline`, `user_notify`, `user_notify_pm`, `user_popup_pm`, `user_rank`, `user_rank2`, `user_rank3`, `user_rank4`, `user_rank5`, `user_avatar`, `user_avatar_type`, `user_email`, `user_icq`, `user_website`, `user_from`, `user_sig`, `user_aim`, `user_yim`, `user_msnm`, `user_occ`, `user_interests`, `user_actkey`, `user_newpasswd`, `user_birthday`, `user_next_birthday_greeting`, `user_sub_forum`, `user_split_cat`, `user_last_topic_title`, `user_sub_level_links`, `user_display_viewonline`, `group_id`, `user_color`, `user_gender`, `user_totaltime`, `user_totallogon`, `user_totalpages`, `user_calendar_display_open`, `user_calendar_header_cells`, `user_calendar_week_start`, `user_calendar_nb_row`, `user_calendar_birthday`, `user_calendar_forum`, `user_warnings`, `user_time_mode`, `user_dst_time_lag`, `user_skype`, `user_registered_ip`, `user_registered_hostname`, `user_profile_view`, `user_last_profile_view`, `user_topics_per_page`, `user_hot_threshold`, `user_posts_per_page`, `user_allowswearywords`, `user_showavatars`, `user_showsignatures`, `user_login_attempts`, `user_last_login_attempt`, `user_sudoku_playing`, `user_from_flag`, `user_phone`, `user_selfdes`, `user_upi2db_which_system`, `user_upi2db_disable`, `user_upi2db_datasync`, `user_upi2db_new_word`, `user_upi2db_edit_word`, `user_upi2db_unread_color`, `user_personal_pics_count`) VALUES (-1, 0, 'Anonymous', 'anonymous', '', 0, 0, '', 0, 0, 0, 0, 0.00, NULL, '', '', 0, 0, 0, NULL, 0, 0, 0, 0, 1, 1, 1, 1, 0, 1, 0, 0, 0, NULL, -1, -2, -2, -2, '', 0, '', '', '', '', '', '', '', '', '', '', '', '', 999999, 0, 1, 1, 1, 2, 2, 0, '', 0, 0, 0, 0, 0, 0, 1, 5, 1, 1, 0, 2, 60, '', '', '', 0, 0, '50', '15', '15', 0, 1, 1, 0, 0, 0, '', '', '', 1, 0, 0, 1, 1, 1, 0);
INSERT INTO `phpbb_users` (`user_id`, `user_active`, `username`, `username_clean`, `user_password`, `user_session_time`, `user_session_page`, `user_browser`, `user_lastvisit`, `user_regdate`, `user_level`, `user_posts`, `user_timezone`, `user_style`, `user_lang`, `user_dateformat`, `user_new_privmsg`, `user_unread_privmsg`, `user_last_privmsg`, `user_emailtime`, `user_allow_viewemail`, `user_profile_view_popup`, `user_attachsig`, `user_setbm`, `user_allowhtml`, `user_allowbbcode`, `user_allowsmile`, `user_allowavatar`, `user_allow_pm`, `user_allow_viewonline`, `user_notify`, `user_notify_pm`, `user_popup_pm`, `user_rank`, `user_rank2`, `user_rank3`, `user_rank4`, `user_rank5`, `user_avatar`, `user_avatar_type`, `user_email`, `user_icq`, `user_website`, `user_from`, `user_sig`, `user_aim`, `user_yim`, `user_msnm`, `user_occ`, `user_interests`, `user_actkey`, `user_newpasswd`, `user_birthday`, `user_next_birthday_greeting`, `user_sub_forum`, `user_split_cat`, `user_last_topic_title`, `user_sub_level_links`, `user_display_viewonline`, `group_id`, `user_color`, `user_gender`, `user_totaltime`, `user_totallogon`, `user_totalpages`, `user_calendar_display_open`, `user_calendar_header_cells`, `user_calendar_week_start`, `user_calendar_nb_row`, `user_calendar_birthday`, `user_calendar_forum`, `user_warnings`, `user_time_mode`, `user_dst_time_lag`, `user_skype`, `user_registered_ip`, `user_registered_hostname`, `user_profile_view`, `user_last_profile_view`, `user_topics_per_page`, `user_hot_threshold`, `user_posts_per_page`, `user_allowswearywords`, `user_showavatars`, `user_showsignatures`, `user_login_attempts`, `user_last_login_attempt`, `user_sudoku_playing`, `user_from_flag`, `user_phone`, `user_selfdes`, `user_upi2db_which_system`, `user_upi2db_disable`, `user_upi2db_datasync`, `user_upi2db_new_word`, `user_upi2db_edit_word`, `user_upi2db_unread_color`, `user_personal_pics_count`) VALUES (2, 1, 'Admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 0, 0, '', 0, 0, 1, 2, 0.00, 1, 'english', 'd M Y h:i a', 0, 0, 0, NULL, 1, 0, 0, 0, 0, 1, 1, 1, 1, 1, 0, 1, 1, 1, -1, -2, -2, -2, '', 0, 'admin@yourdomain.com', '', '', '', '', '', '', '', '', '', '', '', 999999, 0, 1, 1, 1, 2, 2, 3, '#dd2222', 0, 0, 0, 0, 0, 0, 1, 5, 1, 1, 0, 2, 60, '', '', '', 0, 0, '50', '15', '15', 0, 1, 1, 0, 0, 0, '', '', '', 1, 0, 0, 1, 1, 1, 0);

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


## AUTH SYSTEM - BEGIN
# -- CMS related auth options
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_admin', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_settings', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_layouts', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_layouts_special', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_blocks', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_blocks_global', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_permissions', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_menu', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_ads', 1, 0, 0);

INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsl_', 0, 1, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsl_admin', 0, 1, 0);

INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmss_', 0, 1, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmss_admin', 0, 1, 0);

INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsb_', 0, 1, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsb_admin', 0, 1, 0);

# -- Admin related auth options
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_modules', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_roles', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_aauth', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_mauth', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_uauth', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_fauth', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_authgroups', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_authusers', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_viewauth', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_group', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_user', 1);

# -- Moderator related auth options
INSERT INTO phpbb_acl_options (auth_option, is_local, is_global) VALUES ('m_', 1, 1);
INSERT INTO phpbb_acl_options (auth_option, is_local, is_global) VALUES ('m_topicdelete', 1, 1);

# -- User related auth options
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('u_', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('u_html', 1);

# -- Forum related auth options
INSERT INTO phpbb_acl_options (auth_option, is_local) VALUES ('f_', 1);
INSERT INTO phpbb_acl_options (auth_option, is_local) VALUES ('f_html', 1);
INSERT INTO phpbb_acl_options (auth_option, is_local) VALUES ('f_topicdelete', 1);

# -- Plugins related auth options
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('pl_', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('pl_admin', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('pl_input', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('pl_edit', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('pl_delete', 1);

# -- Standard auth roles
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (1, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cms_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (2, 'ROLE_CMS_REVIEWER', 'ROLE_CMS_REVIEWER_DESCRIPTION', 'cms_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (3, 'ROLE_CMS_PUBLISHER', 'ROLE_CMS_PUBLISHER_DESCRIPTION', 'cms_', 3);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (4, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cmsl_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (5, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cmss_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (6, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cmsb_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (7, 'ROLE_ADMIN_FULL', 'ROLE_ADMIN_FULL_DESCRIPTION', 'a_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (8, 'ROLE_ADMIN_STANDARD', 'ROLE_ADMIN_STANDARD_DESCRIPTION', 'a_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (9, 'ROLE_MOD_FULL', 'ROLE_MOD_FULL_DESCRIPTION', 'm_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (10, 'ROLE_MOD_STANDARD', 'ROLE_MOD_STANDARD_DESCRIPTION', 'm_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (11, 'ROLE_MOD_SIMPLE', 'ROLE_MOD_SIMPLE_DESCRIPTION', 'm_', 3);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (12, 'ROLE_USER_FULL', 'ROLE_USER_FULL_DESCRIPTION', 'u_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (13, 'ROLE_USER_STANDARD', 'ROLE_USER_STANDARD_DESCRIPTION', 'u_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (14, 'ROLE_USER_LIMITED', 'ROLE_USER_LIMITED_DESCRIPTION', 'u_', 3);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (15, 'ROLE_FORUM_FULL', 'ROLE_FORUM_FULL_DESCRIPTION', 'f_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (16, 'ROLE_FORUM_STANDARD', 'ROLE_FORUM_STANDARD_DESCRIPTION', 'f_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (17, 'ROLE_FORUM_NOACCESS', 'ROLE_FORUM_NOACCES_DESCRIPTIONS', 'f_', 3);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (18, 'ROLE_PLUGINS_FULL', 'ROLE_PLUGINS_FULL_DESCRIPTION', 'pl_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (19, 'ROLE_PLUGINS_STANDARD', 'ROLE_PLUGINS_STANDARD_DESCRIPTION', 'pl_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (20, 'ROLE_PLUGINS_NOACCESS', 'ROLE_PLUGINS_NOACCESS_DESCRIPTION', 'pl_', 3);

# -- Roles data

# CMS Content Manager (cms_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 1, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'cms_%' AND is_global = 1;

# CMS Reviewer (cms_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 2, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'cms_%' AND auth_option NOT IN ('cms_admin', 'cms_settings', 'cms_permissions', 'cms_menu', 'cms_ads') AND is_global = 1;

# CMS Publisher (cms_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 3, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option = 'cms_blocks' AND is_global = 1;

# CMS Content Manager Layouts (cmsl_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 4, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'cmsl_%' AND is_local = 1;

# CMS Content Manager Special Layouts (cmss_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 5, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'cmss_%' AND is_local = 1;

# CMS Content Manager Blocks (cmsb_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 6, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'cmsb_%' AND is_local = 1;

# Full Admin (a_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 7, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'a_%';

# Standard Admin (a_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 8, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'a_%' AND auth_option NOT IN ('a_modules', 'a_aauth', 'a_roles');

# Full Moderator (m_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 9, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'm_%';

# Standard Moderator (m_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 10, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'm_%' AND auth_option NOT IN ('m_topicdelete');

# Simple Moderator (m_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 11, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'm_%' AND auth_option IN ('m_', 'm_topicdelete');

# All Features (u_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 12, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'u_%';

# Standard Features (u_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 13, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'u_%' AND auth_option NOT IN ('u_html');

# Limited Features (u_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 14, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'u_%' AND auth_option NOT IN ('u_html');

# Full Access (f_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 15, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'f_%';

# Standard Access (f_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 16, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'f_%' AND auth_option NOT IN ('f_html');

# No Access (f_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 17, auth_option_id, 0 FROM phpbb_acl_options WHERE auth_option = 'f_';

# Full Access (pl_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 18, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'pl_%';

# Standard Access (pl_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 19, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'pl_%' AND auth_option NOT IN ('pl_admin', 'pl_delete');

# No Access (pl_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 20, auth_option_id, 0 FROM phpbb_acl_options WHERE auth_option = 'pl_';

# Permissions

# Admin users - full features
#INSERT INTO phpbb_acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) SELECT user_id, 0, 0, 1, 0 FROM phpbb_users WHERE user_level = 1;
INSERT INTO phpbb_acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) SELECT user_id, 0, 0, 7, 0 FROM phpbb_users WHERE user_level = 1;
#INSERT INTO phpbb_acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) SELECT user_id, 0, 0, 18, 0 FROM phpbb_users WHERE user_level = 1;
## AUTH SYSTEM - END
