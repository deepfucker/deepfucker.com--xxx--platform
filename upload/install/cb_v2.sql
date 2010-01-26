-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 26, 2010 at 09:37 AM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `svn_clean`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_log`
--

DROP TABLE IF EXISTS `action_log`;
CREATE TABLE `action_log` (
  `action_id` int(255) NOT NULL AUTO_INCREMENT,
  `action_type` varchar(60) CHARACTER SET latin1 NOT NULL,
  `action_username` varchar(60) CHARACTER SET latin1 NOT NULL,
  `action_userid` int(30) NOT NULL,
  `action_useremail` varchar(200) CHARACTER SET latin1 NOT NULL,
  `action_userlevel` int(11) NOT NULL,
  `action_ip` varchar(15) CHARACTER SET latin1 NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action_success` enum('yes','no') CHARACTER SET latin1 NOT NULL,
  `action_details` text CHARACTER SET latin1 NOT NULL,
  `action_link` text NOT NULL,
  `action_obj_id` int(255) NOT NULL,
  `action_done_id` int(255) NOT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `action_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `ads_data`
--

DROP TABLE IF EXISTS `ads_data`;
CREATE TABLE `ads_data` (
  `ad_id` int(50) NOT NULL AUTO_INCREMENT,
  `ad_name` mediumtext NOT NULL,
  `ad_code` mediumtext NOT NULL,
  `ad_placement` varchar(50) NOT NULL DEFAULT '',
  `ad_category` int(11) NOT NULL DEFAULT '0',
  `ad_status` enum('0','1') NOT NULL DEFAULT '0',
  `ad_impressions` bigint(255) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `ads_data`
--

INSERT INTO `ads_data` (`ad_id`, `ad_name`, `ad_code`, `ad_placement`, `ad_category`, `ad_status`, `ad_impressions`, `date_added`) VALUES
(9, '336x280', '&lt;img src=&quot;http://www.lipsum.com/images/banners/black_336x280.gif&quot;&gt;', '336x280', 0, '1', 227, '0000-00-00 00:00:00'),
(2, 'Adbox 160x600', '&lt;img src=''http://www.lipsum.com/images/banners/grey_160x600.gif'' /&gt;\r\n', 'ad_160x600', 0, '1', 1828, '0000-00-00 00:00:00'),
(3, 'Adbox 468x60', '&lt;div style=''border:2px #333333 solid; color:#53baff; font-size:20px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:468px; height:60px; line-height:60px;'' align=&quot;center&quot;&gt;\r\n	Ad Box 468 x 60\r\n&lt;/div&gt;', 'ad_468x60', 0, '1', 1956, '0000-00-00 00:00:00'),
(4, 'Adbox 728x90', '&lt;div style=&quot;border:2px #333333 solid; color:#53baff; font-size:20px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:728px; height:90px; line-height:90px;&quot; align=&quot;center&quot;&gt;\r\n	Ad Box 728 x 90\r\n&lt;/div&gt;', 'ad_728x90', 0, '1', 694, '0000-00-00 00:00:00'),
(5, 'Adbox 120x600', '&lt;div style=&quot;border:2px #333333 solid; color:#53baff; font-size:20px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:120px; height:600px; &quot; align=&quot;center&quot;&gt;\r\n	Ad Box 120 x 600\r\n&lt;/div&gt;', 'ad_468x60', 0, '1', 688, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ads_placements`
--

DROP TABLE IF EXISTS `ads_placements`;
CREATE TABLE `ads_placements` (
  `placement_id` int(20) NOT NULL AUTO_INCREMENT,
  `placement` varchar(26) NOT NULL,
  `placement_name` varchar(50) NOT NULL,
  `disable` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`placement_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `ads_placements`
--

INSERT INTO `ads_placements` (`placement_id`, `placement`, `placement_name`, `disable`) VALUES
(1, 'ad_160x600', 'Wide Skyscrapper 160 x 600', 'yes'),
(2, 'ad_468x60', 'Banner 468 x 60', 'yes'),
(3, 'ad_300x250', 'Medium Rectangle 300 x 250', 'yes'),
(4, 'ad_728x90', 'Leader Board 728 x 90', 'yes'),
(7, 'ad_120x600', 'Skyscrapper 120 x 600', 'yes'),
(10, 'ad_300x300', 'AD 300x300', 'no'),
(11, '336x280', '336 x280 ad', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `cb_admin_notes`
--

DROP TABLE IF EXISTS `cb_admin_notes`;
CREATE TABLE `cb_admin_notes` (
  `note_id` int(225) NOT NULL AUTO_INCREMENT,
  `note` text CHARACTER SET ucs2 NOT NULL,
  `date_added` datetime NOT NULL,
  `userid` int(225) NOT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cb_admin_notes`
--


-- --------------------------------------------------------

--
-- Table structure for table `cb_editors_picks`
--

DROP TABLE IF EXISTS `cb_editors_picks`;
CREATE TABLE `cb_editors_picks` (
  `pick_id` int(225) NOT NULL AUTO_INCREMENT,
  `videoid` int(225) NOT NULL,
  `sort` bigint(5) NOT NULL DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pick_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cb_editors_picks`
--


-- --------------------------------------------------------

--
-- Table structure for table `cb_pages`
--

DROP TABLE IF EXISTS `cb_pages`;
CREATE TABLE `cb_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(225) NOT NULL,
  `page_title` varchar(225) NOT NULL,
  `page_content` text NOT NULL,
  `userid` int(225) NOT NULL,
  `active` enum('yes','no') NOT NULL,
  `delete_able` enum('yes','no') NOT NULL DEFAULT 'yes',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cb_pages`
--

INSERT INTO `cb_pages` (`page_id`, `page_name`, `page_title`, `page_content`, `userid`, `active`, `delete_able`, `date_added`) VALUES
(1, 'About us', 'About us', '<div style="margin: auto; width: 98%;"><font style="font-weight: bold;" size="4">About Us</font><hr noshade="noshade" size="1">\r\n  \r\n  \r\n    <p><span style="font-weight: bold;">ClipBucket </span>is one of the world''s best video sites . We specialize in short-form\r\noriginal content - from new, emerging talents and established Hollywood\r\nheavyweights alike. We''re committed to delivering an exceptional\r\nentertainment experience, and we do so by engaging and empowering our\r\naudience every step of the way.</p>\r\n      <p>Everyone can Watch Videos\r\non <span style="font-weight: bold;">ClipBucket</span>. People can see first-hand accounts of current events, find\r\nvideos about their hobbies and interests, and discover the\r\nquirky and unusual. As more people capture special moments on\r\nvideo,<span style="font-weight: bold;">ClipBucket </span>is empowering them to become the broadcasters of\r\ntomorrow.</p>\r\n      <p><span style="font-weight: bold;">ClipBucket </span>not only a video sharing website but\r\nalso has social network features, you can make friends,\r\nand send them videos and private messages. <span style="font-weight: bold;">ClipBucket </span><span style="font-weight: bold;"></span> also has built in\r\nrating system and comment system so that people can discuss on there\r\ninterested videos, not only comment but also, people can rate Comments.</p></div>', 1, 'yes', 'no', '2010-01-01 08:47:56'),
(2, 'Privacy Policy', 'Privacy Policy', '<h1>ClipBucket Privacy Notice - YT Version\r\n</h1>\r\n<h2>Personal Information</h2>\r\n<ul>\r\n  <li><strong>Browsing ClipBucket</strong> You can watch videos on ClipBucket without having a ClipBucket Account or a  PHPBucket Account. You also can contact us through the ClipBucket Help Center  or by emailing us directly without having to register for an account.</li>\r\n  <li><strong>Your ClipBucket Account.</strong> For some activities on ClipBucket, like uploading videos, posting  comments, flagging videos, or watching restricted videos, you need a  ClipBucket or PHPBucket Account. We ask for some personal information when  you create an account, including your email address and a password,  which is used to protect your account from unauthorized access. A  PHPBucket Account, additionally, allows you to access other PHPBucket  services that require registration.</li>\r\n  <li><strong>Usage Information.</strong> When you use ClipBucket, we may record information about your usage of the  site, such as the channels, groups and favorites you subscribe to,  which other users you communicate with, the videos you watch, the  frequency and size of data transfers, and information you display about  yourself as well as information you click on in ClipBucket (including UI  elements, settings). If you are logged in, we may associate that  information with your ClipBucket Account. In order to ensure the quality  of our service to you, we may place a tag (also called a "web beacon")  in HTML-based customer support emails or other communications with you  in order to confirm delivery.</li>\r\n  <li><strong>Content Uploaded to Site.</strong> Any personal information or video content that you voluntarily disclose  online (e.g., video comments, your profile page) may be collected and  used by others. If you download the ClipBucket Uploader, your copy  includes a unique application number. This number, and information  about your installation of the Uploader (version number, language) will  be sent to ClipBucket when the Uploader automatically checks for updates  and will be used to update your version of the Uploader.</li>\r\n</ul>\r\n<h2>Uses</h2>\r\n<ul>\r\n  <li>If  you submit personal information to ClipBucket, we may use that information  to operate, maintain, and improve the features and functionality of  ClipBucket, and to process any flagging activity or other communication  you send to us.</li>\r\n  <li>We do not use your  email address or other personal information to send commercial or  marketing messages without your consent. We may use your email address  without further consent for non-marketing or administrative purposes  (such as notifying you of major ClipBucket changes or for customer service  purposes). You also can choose how often ClipBucket sends you email  updates in your ClipBucket Account settings page.</li>\r\n  <li>We  use cookies, web beacons, and log file information to: (a) store  information so that you will not have to re-enter it during your visit  or the next time you visit ClipBucket; (b) provide custom, personalized  content and information; (c) monitor the effectiveness of our marketing  campaigns; (d) monitor aggregate metrics such as total number of  visitors and pages viewed; and (e) track your entries, submissions, and  status in promotions, sweepstakes, and contests.</li>\r\n</ul>\r\n<h2>Information That is Publicly Available</h2>\r\n<ul>\r\n  <li>When  you create a ClipBucket Account, some information about your ClipBucket  Account and your account activity will be provided to other users of  ClipBucket. This may include the date you opened your ClipBucket Account, the  date you last logged into your ClipBucket Account, your age (if you choose  to make it public), the country and the number of videos you have  watched.</li>\r\n  <li>Your ClipBucket Account name,  not your email address, is displayed to other users when you engage in  certain activities on ClipBucket, such as when you upload videos or send  messages through ClipBucket. Other users can contact you by leaving a  message or comment on the site.</li>\r\n  <li>Any  videos that you submit to ClipBucket may be redistributed through the  internet and other media channels, and may be viewed by other ClipBucket  users or the general public. </li>\r\n  <li>You  may also choose to add personal information which may include your  name, gender, profile picture or other details, that will be visible to  other users on your ClipBucket Account channel page. If you choose to add  certain features to your ClipBucket Account channel page, then these  features and your activity associated with these features will be  displayed to other users and may be aggregated and shared with your  friends or other users. Such shared activity may include your favorite  videos, videos you rated and videos that you have uploaded.</li>\r\n</ul>\r\n<h2>Your Choices</h2>\r\n<ul>\r\n  <li>If  you have a ClipBucket Account, you may update or correct your personal  profile information, email preferences and privacy settings at any time  by visiting your account profile page. </li>\r\n  <li>You  may control the information that is available to other users and your  confirmed friends at any time by editing your ClipBucket Account and the  features that are included on your channel page. If you have enabled  Active Sharing, other users may see that you, as identified by your  account name, not your email address, are watching the same video.</li>\r\n  <li>You  may, of course, decline to submit personal information through ClipBucket,  in which case you can still view videos and explore ClipBucket, but  ClipBucket may not be able to provide certain services to you. Some  advanced ClipBucket features may use other PHPBucket services like PHPBucket  Checkout or AdSense. The privacy notices of those services govern the  use of your personal information associated with them.</li>\r\n</ul>\r\n', 1, 'yes', 'no', '2010-01-01 08:52:46'),
(3, 'Terms of Serivce', 'Terms of Service', 'Write your own terms of service...', 1, 'yes', 'no', '2010-01-01 08:53:57'),
(4, 'Help', 'Help', '<span style="font-weight: bold;">How to use ClipBucket</span><br><ol><li>Articles will be written pretty soon</li></ol>', 1, 'yes', 'no', '2010-01-01 09:17:36'),
(5, '403 Error', '403 Forbidden', '<h2>403 Access Denied</h2>\r\nSorry, you cannot access this page...', 0, 'yes', 'no', '0000-00-00 00:00:00'),
(6, '404 Error', '404 Not Found', '<h2>404 Not Found</h2>\r\nwe are unable to find requested URL on server..', 0, 'yes', 'no', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cb_playlists`
--

DROP TABLE IF EXISTS `cb_playlists`;
CREATE TABLE `cb_playlists` (
  `playlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `userid` int(11) NOT NULL,
  `playlist_type` varchar(10) CHARACTER SET latin1 NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playlist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cb_playlists`
--


-- --------------------------------------------------------

--
-- Table structure for table `cb_playlist_items`
--

DROP TABLE IF EXISTS `cb_playlist_items`;
CREATE TABLE `cb_playlist_items` (
  `playlist_item_id` int(225) NOT NULL AUTO_INCREMENT,
  `object_id` int(225) NOT NULL,
  `playlist_id` int(225) NOT NULL,
  `playlist_item_type` varchar(10) CHARACTER SET latin1 NOT NULL,
  `userid` int(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playlist_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cb_playlist_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `cb_stats`
--

DROP TABLE IF EXISTS `cb_stats`;
CREATE TABLE `cb_stats` (
  `stat_id` int(255) NOT NULL AUTO_INCREMENT,
  `date_added` date NOT NULL,
  `video_stats` text NOT NULL,
  `user_stats` text NOT NULL,
  `group_stats` text NOT NULL,
  PRIMARY KEY (`stat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cb_stats`
--


-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `comment_id` int(60) NOT NULL AUTO_INCREMENT,
  `type` varchar(3) NOT NULL,
  `comment` text NOT NULL,
  `userid` int(60) NOT NULL,
  `anonym_name` varchar(255) NOT NULL,
  `anonym_email` varchar(255) NOT NULL,
  `parent_id` int(60) NOT NULL,
  `type_id` int(225) NOT NULL,
  `vote` varchar(225) NOT NULL,
  `spam_votes` int(225) NOT NULL,
  `spam_voters` text NOT NULL,
  `voters` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comment_ip` text NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `configid` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`configid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=115 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`configid`, `name`, `value`) VALUES
(1, 'site_title', 'ClipBucket v2'),
(2, 'site_slogan', 'A way to broadcast yourself'),
(3, 'baseurl', 'http://domain.tld'),
(4, 'basedir', '/home/path/to/clipbucket'),
(5, 'template_dir', 'cbv2new'),
(6, 'player_file', 'cbplayer.plug.php'),
(7, 'closed', '0'),
(8, 'closed_msg', 'We Are Updating Our Website, Please Visit us after few hours.'),
(9, 'description', 'Clip Bucket is an ultimate Video Sharing script'),
(10, 'keywords', 'clip bucket video sharing website script'),
(11, 'ffmpegpath', '/usr/local/bin/ffmpeg'),
(12, 'flvtool2path', '/usr/local/bin/flvtool2'),
(13, 'mp4boxpath', '/usr/local/bin/MP4Box'),
(14, 'vbrate', '512000'),
(15, 'srate', '22050'),
(16, 'r_height', '300'),
(17, 'r_width', '400'),
(18, 'resize', 'no'),
(19, 'mencoderpath', ''),
(20, 'keep_original', '1'),
(21, 'activation', ''),
(22, 'mplayerpath', ''),
(23, 'email_verification', '1'),
(24, 'allow_registeration', '1'),
(25, 'php_path', '/usr/local/bin/php'),
(26, 'videos_list_per_page', '25'),
(27, 'channels_list_per_page', '25'),
(28, 'videos_list_per_tab', '1'),
(29, 'channels_list_per_tab', '1'),
(30, 'video_comments', '1'),
(31, 'video_rating', '1'),
(32, 'comment_rating', '1'),
(33, 'video_download', '1'),
(34, 'video_embed', '1'),
(35, 'groups_list_per_page', '15'),
(36, 'seo', 'yes'),
(37, 'admin_pages', '50'),
(38, 'search_list_per_page', '20'),
(39, 'recently_viewed_limit', '12'),
(40, 'max_upload_size', '1000'),
(41, 'sbrate', '128000'),
(42, 'thumb_width', '120'),
(43, 'thumb_height', '90'),
(44, 'ffmpeg_type', ''),
(45, 'user_comment_opt1', ''),
(46, 'user_comment_opt2', ''),
(47, 'user_comment_opt3', ''),
(48, 'user_comment_opt4', ''),
(49, 'user_rate_opt1', ''),
(50, 'captcha_type', '1'),
(51, 'allow_upload', 'yes'),
(52, 'allowed_types', 'wmv,avi,divx,3gp,mov,mpeg,mpg,xvid,flv,asf,rm,dat,mp4'),
(53, 'version', '2.0.3'),
(54, 'version_type', 'Alpha'),
(55, 'allow_template_change', ''),
(56, 'allow_language_change', '1'),
(57, 'default_site_lang', ''),
(58, 'video_require_login', ''),
(59, 'audio_codec', 'libmp3lame'),
(60, 'con_modules_type', ''),
(61, 'remoteUpload', ''),
(62, 'embedUpload', ''),
(63, 'player_div_id', ''),
(64, 'code_dev', ' (Powered by ClipBucket)'),
(65, 'sys_os', ''),
(66, 'debug_level', ''),
(67, 'enable_troubleshooter', '1'),
(68, 'vrate', '30'),
(69, 'num_thumbs', '3'),
(70, 'big_thumb_width', '320'),
(71, 'big_thumb_height', '240'),
(72, 'user_max_chr', '15'),
(73, 'disallowed_usernames', 'shit, asshole, fucker'),
(74, 'min_age_reg', '0'),
(75, 'max_comment_chr', '800'),
(76, 'user_comment_own', '1'),
(77, 'anonym_comments', 'yes'),
(78, 'player_dir', 'cbplayer'),
(79, 'player_width', '652'),
(80, 'player_height', '308'),
(81, 'default_country_iso2', 'PK'),
(82, 'channel_player_width', '600'),
(83, 'channel_player_height', '281'),
(84, 'videos_items_grp_page', '12'),
(85, 'videos_items_hme_page', '20'),
(86, 'videos_items_columns', '9'),
(87, 'videos_items_ufav_page', '25'),
(88, 'videos_items_uvid_page', '25'),
(89, 'videos_items_search_page', '30'),
(90, 'videos_item_channel_page', '10'),
(91, 'users_items_subscriptions', '5'),
(92, 'users_items_subscibers', '5'),
(93, 'users_items_contacts_channel', '5'),
(94, 'users_items_search_page', '12'),
(95, 'users_items_group_page', '15'),
(96, 'cbhash', 'PGRpdiBhbGlnbj0iY2VudGVyIj48IS0tIERvIG5vdCByZW1vdmUgdGhpcyBjb3B5cmlnaHQgbm90aWNlIC0tPg0KUG93ZXJlZCBieSA8YSBocmVmPSJodHRwOi8vY2xpcC1idWNrZXQuY29tLyI+Q2xpcEJ1Y2tldDwvYT4gJXM8YnI+DQpDb3B5cmlnaHQgJmNvcHk7IDIwMDcgLSAyMDEwLCBDbGlwQnVja2V0DQo8IS0tIERvIG5vdCByZW1vdmUgdGhpcyBjb3B5cmlnaHQgbm90aWNlIC0tPjwvZGl2Pg=='),
(97, 'min_video_title', '4'),
(98, 'max_video_title', '60'),
(99, 'min_video_desc', '5'),
(100, 'max_video_desc', '300'),
(101, 'video_categories', '4'),
(102, 'min_video_tags', '3'),
(103, 'max_video_tags', '30'),
(104, 'video_codec', 'flv'),
(105, 'date_released', '01-05-2010'),
(106, 'date_installed', '01-05-2010'),
(107, 'date_updated', '01-05-2010'),
(112, 'website_email', 'email@website.com'),
(113, 'support_email', 'support@website.com'),
(114, 'welcome_email', 'welcome@website.com');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `contact_id` int(225) NOT NULL AUTO_INCREMENT,
  `userid` int(225) NOT NULL,
  `contact_userid` int(225) NOT NULL,
  `confirmed` enum('yes','no') NOT NULL DEFAULT 'no',
  `contact_group_id` int(225) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `contacts`
--


-- --------------------------------------------------------

--
-- Table structure for table `conversion_queue`
--

DROP TABLE IF EXISTS `conversion_queue`;
CREATE TABLE `conversion_queue` (
  `cqueue_id` int(11) NOT NULL AUTO_INCREMENT,
  `cqueue_name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `cqueue_ext` varchar(5) CHARACTER SET latin1 NOT NULL,
  `cqueue_tmp_ext` varchar(3) CHARACTER SET latin1 NOT NULL,
  `cqueue_conversion` enum('yes','no','p') CHARACTER SET latin1 NOT NULL DEFAULT 'no',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cqueue_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `conversion_queue`
--


-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `iso2` char(2) CHARACTER SET latin1 DEFAULT NULL,
  `iso3` char(3) CHARACTER SET latin1 DEFAULT NULL,
  `name_en` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `iso2`, `iso3`, `name_en`) VALUES
(1, 'AF', 'AFG', 'Afghanistan'),
(3, 'AL', 'ALB', 'Albania'),
(4, 'DZ', 'DZA', 'Algeria'),
(5, 'AS', 'ASM', 'American Samoa'),
(6, 'AD', 'AND', 'Andorra'),
(7, 'AO', 'AGO', 'Angola'),
(8, 'AI', 'AIA', 'Anguilla'),
(9, 'AQ', 'ATA', 'Antarctica'),
(10, 'AG', 'ATG', 'Antigua and Barbuda'),
(11, 'AR', 'ARG', 'Argentina'),
(12, 'AM', 'ARM', 'Armenia'),
(13, 'AW', 'ABW', 'Aruba'),
(14, 'AU', 'AUS', 'Australia'),
(15, 'AT', 'AUT', 'Austria'),
(16, 'AZ', 'AZE', 'Azerbaijan'),
(17, 'BS', 'BHS', 'Bahamas'),
(18, 'BH', 'BHR', 'Bahrain'),
(19, 'BD', 'BGD', 'Bangladesh'),
(20, 'BB', 'BRB', 'Barbados'),
(21, 'BY', 'BLR', 'Belarus'),
(22, 'BE', 'BEL', 'Belgium'),
(23, 'BZ', 'BLZ', 'Belize'),
(24, 'BJ', 'BEN', 'Benin'),
(25, 'BM', 'BMU', 'Bermuda'),
(26, 'BT', 'BTN', 'Bhutan'),
(27, 'BO', 'BOL', 'Bolivia'),
(28, 'BA', 'BIH', 'Bosnia and Herzegovina'),
(29, 'BW', 'BWA', 'Botswana'),
(30, 'BV', 'BVT', 'Bouvet Island'),
(31, 'BR', 'BRA', 'Brazil'),
(32, 'IO', 'IOT', 'British Indian Ocean Territory'),
(33, 'BN', 'BRN', 'Brunei Darussalam'),
(34, 'BG', 'BGR', 'Bulgaria'),
(35, 'BF', 'BFA', 'Burkina Faso'),
(36, 'BI', 'BDI', 'Burundi'),
(37, 'KH', 'KHM', 'Cambodia'),
(38, 'CM', 'CMR', 'Cameroon'),
(39, 'CA', 'CAN', 'Canada'),
(40, 'CV', 'CPV', 'Cape Verde'),
(41, 'KY', 'CYM', 'Cayman Islands'),
(42, 'CF', 'CAF', 'Central African Republic'),
(43, 'TD', 'TCD', 'Chad'),
(44, 'CL', 'CHL', 'Chile'),
(45, 'CN', 'CHN', 'China'),
(46, 'CX', 'CXR', 'Christmas Island'),
(47, 'CC', 'CCK', 'Cocos Islands'),
(48, 'CO', 'COL', 'Colombia'),
(49, 'KM', 'COM', 'Comoros'),
(50, 'CG', 'COG', 'Congo, Republic Of'),
(52, 'CK', 'COK', 'Cook Islands'),
(53, 'CR', 'CRI', 'Costa Rica'),
(55, 'HR', 'HRV', 'Croatia'),
(56, 'CU', 'CUB', 'Cuba'),
(57, 'CY', 'CYP', 'Cyprus'),
(58, 'CZ', 'CZE', 'Czech Republic'),
(59, 'DK', 'DNK', 'Denmark'),
(60, 'DJ', 'DJI', 'Djibouti'),
(61, 'DM', 'DMA', 'Dominica'),
(62, 'DO', 'DOM', 'Dominican Republic'),
(63, 'EC', 'ECU', 'Ecuador'),
(64, 'EG', 'EGY', 'Egypt'),
(65, 'SV', 'SLV', 'El Salvador'),
(66, 'GQ', 'GNQ', 'Equatorial Guinea'),
(67, 'ER', 'ERI', 'Eritrea'),
(68, 'EE', 'EST', 'Estonia'),
(69, 'ET', 'ETH', 'Ethiopia'),
(70, 'FO', 'FRO', 'Faeroe Islands'),
(71, 'FK', 'FLK', 'Falkland Islands'),
(72, 'FJ', 'FJI', 'Fiji'),
(73, 'FI', 'FIN', 'Finland'),
(74, 'FR', 'FRA', 'France'),
(75, 'GF', 'GUF', 'French Guiana'),
(76, 'PF', 'PYF', 'French Polynesia'),
(78, 'GA', 'GAB', 'Gabon'),
(79, 'GM', 'GMB', 'Gambia, The'),
(80, 'GE', 'GEO', 'Georgia'),
(81, 'DE', 'DEU', 'Germany'),
(82, 'GH', 'GHA', 'Ghana'),
(83, 'GI', 'GIB', 'Gibraltar'),
(84, 'GB', 'GBR', 'Great Britain'),
(85, 'GR', 'GRC', 'Greece'),
(86, 'GL', 'GRL', 'Greenland'),
(87, 'GD', 'GRD', 'Grenada'),
(88, 'GP', 'GLP', 'Guadeloupe'),
(89, 'GU', 'GUM', 'Guam'),
(90, 'GT', 'GTM', 'Guatemala'),
(91, 'GN', 'GIN', 'Guinea'),
(92, 'GW', 'GNB', 'Guinea-bissau'),
(93, 'GY', 'GUY', 'Guyana'),
(94, 'HT', 'HTI', 'Haiti'),
(95, 'HM', 'HMD', 'Heard Island'),
(96, 'HN', 'HND', 'Honduras'),
(97, 'HK', 'HKG', 'Hong Kong'),
(98, 'HU', 'HUN', 'Hungary'),
(99, 'IS', 'ISL', 'Iceland'),
(100, 'IN', 'IND', 'India'),
(101, 'ID', 'IDN', 'Indonesia'),
(102, 'IR', 'IRN', 'Iran'),
(103, 'IQ', 'IRQ', 'Iraq'),
(104, 'IE', 'IRL', 'Ireland'),
(105, 'IL', 'ISR', 'Israel'),
(106, 'IT', 'ITA', 'Italy'),
(107, 'JM', 'JAM', 'Jamaica'),
(108, 'JP', 'JPN', 'Japan'),
(109, 'JO', 'JOR', 'Jordan'),
(110, 'KZ', 'KAZ', 'Kazakhstan'),
(111, 'KE', 'KEN', 'Kenya'),
(112, 'KI', 'KIR', 'Kiribati'),
(113, 'KP', 'PRK', 'Korea'),
(114, 'KR', 'KOR', 'Korea'),
(115, 'KW', 'KWT', 'Kuwait'),
(116, 'KG', 'KGZ', 'Kyrgyzstan'),
(117, 'LA', 'LAO', 'Lao'),
(118, 'LV', 'LVA', 'Latvia'),
(119, 'LB', 'LBN', 'Lebanon'),
(120, 'LS', 'LSO', 'Lesotho'),
(121, 'LR', 'LBR', 'Liberia'),
(122, 'LY', 'LBY', 'Libya'),
(124, 'LT', 'LTU', 'Lithuania'),
(125, 'LU', 'LUX', 'Luxembourg'),
(128, 'MG', 'MDG', 'Madagascar'),
(129, 'MW', 'MWI', 'Malawi'),
(130, 'MY', 'MYS', 'Malaysia'),
(131, 'MV', 'MDV', 'Maldives'),
(132, 'ML', 'MLI', 'Mali'),
(133, 'MT', 'MLT', 'Malta'),
(134, 'MH', 'MHL', 'Marshall Islands'),
(135, 'MQ', 'MTQ', 'Martinique'),
(136, 'MR', 'MRT', 'Mauritania'),
(137, 'MU', 'MUS', 'Mauritius'),
(138, 'YT', 'MYT', 'Mayotte'),
(139, 'MX', 'MEX', 'Mexico'),
(141, 'MD', 'MDA', 'Moldova'),
(142, 'MC', 'MCO', 'Monaco'),
(143, 'MN', 'MNG', 'Mongolia'),
(144, 'MS', 'MSR', 'Montserrat'),
(145, 'MA', 'MAR', 'Morocco'),
(147, 'MM', 'MMR', 'Myanmar '),
(148, 'NA', 'NAM', 'Namibia'),
(149, 'NR', 'NRU', 'Nauru'),
(150, 'NP', 'NPL', 'Nepal'),
(151, 'NL', 'NLD', 'Netherlands'),
(152, 'AN', 'ANT', 'Netherlands Antilles'),
(153, 'NC', 'NCL', 'New Caledonia'),
(154, 'NZ', 'NZL', 'New Zealand'),
(155, 'NI', 'NIC', 'Nicaragua'),
(156, 'NE', 'NER', 'Niger'),
(157, 'NG', 'NGA', 'Nigeria'),
(158, 'NU', 'NIU', 'Niue'),
(159, 'NF', 'NFK', 'Norfolk Island'),
(160, 'MP', 'MNP', 'Northern Mariana Islands'),
(161, 'NO', 'NOR', 'Norway'),
(162, 'OM', 'OMN', 'Oman'),
(163, 'PK', 'PAK', 'Pakistan'),
(164, 'PW', 'PLW', 'Palau'),
(165, 'PS', 'PSE', 'Palestinian Territories'),
(166, 'PA', 'PAN', 'Panama'),
(167, 'PG', 'PNG', 'Papua New Guinea'),
(168, 'PY', 'PRY', 'Paraguay'),
(169, 'PE', 'PER', 'Peru'),
(170, 'PH', 'PHL', 'Philippines'),
(171, 'PN', 'PCN', 'Pitcairn'),
(172, 'PL', 'POL', 'Poland'),
(173, 'PT', 'PRT', 'Portugal'),
(174, 'PR', 'PRI', 'Puerto Rico'),
(175, 'QA', 'QAT', 'Qatar'),
(177, 'RO', 'ROU', 'Romania'),
(178, 'RU', 'RUS', 'Russian Federation'),
(179, 'RW', 'RWA', 'Rwanda'),
(180, 'SH', 'SHN', 'Saint Helena'),
(181, 'KN', 'KNA', 'Saint Kitts and Nevis'),
(182, 'LC', 'LCA', 'Saint Lucia'),
(183, 'PM', 'SPM', 'Saint Pierre and Miquelon'),
(184, 'VC', 'VCT', 'Saint Vincent '),
(185, 'WS', 'WSM', 'Samoa '),
(186, 'SM', 'SMR', 'San Marino'),
(187, 'ST', 'STP', 'Sao Tome and Principe'),
(188, 'SA', 'SAU', 'Saudi Arabia'),
(189, 'SN', 'SEN', 'Senegal'),
(190, 'CS', 'SCG', 'Serbia and Montenegro '),
(191, 'SC', 'SYC', 'Seychelles'),
(192, 'SL', 'SLE', 'Sierra Leone'),
(193, 'SG', 'SGP', 'Singapore'),
(194, 'SK', 'SVK', 'Slovakia '),
(195, 'SI', 'SVN', 'Slovenia'),
(196, 'SB', 'SLB', 'Solomon Islands'),
(197, 'SO', 'SOM', 'Somalia'),
(198, 'ZA', 'ZAF', 'South Africa'),
(199, 'GS', 'SGS', 'South Georgia'),
(200, 'ES', 'ESP', 'Spain'),
(201, 'LK', 'LKA', 'Sri Lanka'),
(202, 'SD', 'SDN', 'Sudan'),
(203, 'SR', 'SUR', 'Suriname'),
(204, 'SJ', 'SJM', 'Svalbard and Jan Mayen'),
(205, 'SZ', 'SWZ', 'Swaziland'),
(206, 'SE', 'SWE', 'Sweden'),
(207, 'CH', 'CHE', 'Switzerland'),
(208, 'SY', 'SYR', 'Syrian Arab Republic'),
(209, 'TW', 'TWN', 'Taiwan'),
(210, 'TJ', 'TJK', 'Tajikistan'),
(211, 'TZ', 'TZA', 'Tanzania'),
(212, 'TH', 'THA', 'Thailand'),
(213, 'TL', 'TLS', 'Timor-Leste'),
(214, 'TG', 'TGO', 'Togo'),
(215, 'TK', 'TKL', 'Tokelau'),
(216, 'TO', 'TON', 'Tonga'),
(217, 'TT', 'TTO', 'Trinidad and Tobago'),
(218, 'TN', 'TUN', 'Tunisia'),
(219, 'TR', 'TUR', 'Turkey'),
(220, 'TM', 'TKM', 'Turkmenistan'),
(221, 'TC', 'TCA', 'Turks and Caicos Islands'),
(222, 'TV', 'TUV', 'Tuvalu'),
(223, 'UG', 'UGA', 'Uganda'),
(224, 'UA', 'UKR', 'Ukraine'),
(225, 'AE', 'ARE', 'United Arab Emirates'),
(226, 'GB', 'GBR', 'United Kingdom'),
(227, 'US', 'USA', 'United States'),
(229, 'UY', 'URY', 'Uruguay'),
(230, 'UZ', 'UZB', 'Uzbekistan'),
(231, 'VU', 'VUT', 'Vanuatu'),
(232, 'VA', 'VAT', 'Vatican City'),
(233, 'VE', 'VEN', 'Venezuela'),
(234, 'VN', 'VNM', 'Viet Nam'),
(235, 'VG', 'VGB', 'Virgin Islands, British'),
(236, 'VI', 'VIR', 'Virgin Islands, U.S.'),
(237, 'WF', 'WLF', 'Wallis and Futuna'),
(238, 'EH', 'ESH', 'Western Sahara'),
(239, 'YE', 'YEM', 'Yemen'),
(240, 'ZM', 'ZMB', 'Zambia'),
(241, 'ZW', 'ZWE', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

DROP TABLE IF EXISTS `custom_fields`;
CREATE TABLE `custom_fields` (
  `custom_field_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_field_title` text NOT NULL,
  `custom_field_type` text NOT NULL,
  `custom_field_name` text NOT NULL,
  `custom_field_id` text NOT NULL,
  `custom_field_value` text NOT NULL,
  `custom_field_hint_1` text NOT NULL,
  `custom_field_db_field` text NOT NULL,
  `custom_field_required` enum('yes','no') NOT NULL DEFAULT 'no',
  `custom_field_validate_function` text NOT NULL,
  `custom_field_invalid_err` text NOT NULL,
  `custom_field_display_function` text NOT NULL,
  `custom_field_anchor_before` text NOT NULL,
  `custom_field_anchor_after` text NOT NULL,
  `custom_field_hint_2` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`custom_field_list_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `custom_fields`
--


-- --------------------------------------------------------

--
-- Table structure for table `editors_picks`
--

DROP TABLE IF EXISTS `editors_picks`;
CREATE TABLE `editors_picks` (
  `pick_id` int(225) NOT NULL AUTO_INCREMENT,
  `videoid` int(225) NOT NULL,
  `sort` bigint(5) NOT NULL DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pick_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `editors_picks`
--


-- --------------------------------------------------------

--
-- Table structure for table `email_settings`
--

DROP TABLE IF EXISTS `email_settings`;
CREATE TABLE `email_settings` (
  `email_settings_id` int(25) NOT NULL AUTO_INCREMENT,
  `email_settings_name` varchar(60) NOT NULL,
  `email_settings_value` mediumtext NOT NULL,
  `email_settings_headers` mediumtext NOT NULL,
  PRIMARY KEY (`email_settings_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `email_settings`
--

INSERT INTO `email_settings` (`email_settings_id`, `email_settings_name`, `email_settings_value`, `email_settings_headers`) VALUES
(1, 'website_email', 'email@example.com', ''),
(2, 'support_email', 'support@example.com', ''),
(3, 'welcome_email', 'no-reply@example.com', ''),
(4, 'email_verification_template', 'Hello $username,\r\nThank For Joining Us, Your Account Details are\r\n\r\nUsername     : $username\r\nPassword     : $password\r\nEmail        : $email\r\nDate Joined  : $cur_date\r\n\r\nYour Account Is Inactive Please Activate it by using following link \r\n\r\n<a href=$baseurl/activation.php?username=$username&avcode=$avcode>Click Here</a>\r\n\r\n$baseurl/activation.php?username=$username&avcode=$avcode\r\n\r\n====================\r\nRegards\r\n$title', '$uname''s Account Activation'),
(5, 'welcome_message_template', 'Hello $username, Welcome to $title.\r\nYou are now our member, you can now\r\n\r\n-> Upload Videos\r\n-> Share Videos\r\n-> Make Friends and Send Messeges\r\n-> Now You Have Your Own Channel\r\n\r\nTo Access Your Account Please <a href=$baseurl>Click Here</a> and login\r\n\r\nThank You For Joining Us,\r\nRegards\r\n$title Team', 'Welcome $username to $title'),
(6, 'activate_request_template', 'Hello $username,\r\n\r\nYour Activation Code is : $avcode\r\n<a href=$baseurl/activation.php>Click Here</a> To Goto Activation Page\r\n\r\nDirect Activation\r\n==========================================\r\n<a href=$baseurl/activation.php?username=$username&avcode=$avcode>Click Here</a> or Copy & Paste the following link in your browser\r\n$baseurl/activation.php?username=$username&avcode=$avcode\r\n', '$username''s  Account Activation'),
(7, 'share_video_template', '<html>\r\n<head>\r\n<style type="text/css">\r\n<!--\r\n.title {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #FFFFFF;\r\n	font-size: 16px;\r\n}\r\n.title2 {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 14px;\r\n}\r\n.messege {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 12px;\r\n}\r\n#videoThumb{\r\n	width: 120px;\r\n	padding: 2px;\r\n	margin: 3px;\r\n	border: 1px solid #F0F0F0;\r\n	text-align: center;\r\n	vertical-align: middle;\r\n}\r\nbody,td,th {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #FFFFFF;\r\n}\r\n.text {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #000000;\r\n	padding: 5px;\r\n}\r\n-->\r\n</style>\r\n</head>\r\n<body>\r\n<table width="100%" border="0" cellspacing="0" cellpadding="5">\r\n  <tr>\r\n    <td bgcolor="#53baff" ><span class="title">$title</span>share video</td>\r\n  </tr>\r\n  <tr>\r\n    <td height="20" class="messege">$username wants to share Video With You<div id="videoThumb"><a href="$baseurl/watch_video.php?v=$videokey">$videothumb<br>\r\n    watch video</a></div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class="text" ><span class="title2">Video Description</span><br>\r\n      <span class="text">$videodes</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td><span class="title2">Personal Messege</span><br>\r\n      <span class="text">$messege\r\n      </span><br>\r\n      <br>\r\n<span class="text">Thanks,</span><br> \r\n<span class="text">$username</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td bgcolor="#53baff">copyrights 2007 $title</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>', '$username Want To Share A Video With You'),
(8, 'share_picture_template', '<html>\r\n<head>\r\n<style type="text/css">\r\n<!--\r\n.title {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #FFFFFF;\r\n	font-size: 16px;\r\n}\r\n.title2 {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 14px;\r\n}\r\n.messege {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 12px;\r\n}\r\n#videoThumb{\r\n	padding: 2px;\r\n	margin: 3px;\r\n	border: 1px solid #F0F0F0;\r\n	text-align: center;\r\n	vertical-align: middle;\r\n}\r\nbody,td,th {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #FFFFFF;\r\n}\r\n.text {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #000000;\r\n	padding: 5px;\r\n}\r\n-->\r\n</style>\r\n</head>\r\n<body>\r\n<table width="100%" border="0" cellspacing="0" cellpadding="5">\r\n  <tr>\r\n    <td bgcolor="#53baff" ><span class="title">$title</span>share Picture </td>\r\n  </tr>\r\n  <tr>\r\n    <td height="20" class="messege">$username wants to share Picture With You\r\n      <div id="videoThumb"><a href="$baseurl/view_picture.php?picid=$picid">$picture<br>\r\n    View Picture</a></div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class="text" ><span class="title2">Picture Description</span><br>\r\n      <span class="text">$picdes</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td><span class="title2">Personal Messege</span><br>\r\n      <span class="text">$messege\r\n      </span><br>\r\n      <br>\r\n<span class="text">Thanks,</span><br> \r\n<span class="text">$username</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td bgcolor="#53baff">copyrights 2007 $title</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>', '$username Want To Share A  Picture With You');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `email_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_template_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `email_template_code` varchar(225) CHARACTER SET latin1 NOT NULL,
  `email_template_subject` mediumtext CHARACTER SET latin1 NOT NULL,
  `email_template` text CHARACTER SET latin1 NOT NULL,
  `email_template_allowed_tags` mediumtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`email_template_id`),
  UNIQUE KEY `email_template_code` (`email_template_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`email_template_id`, `email_template_name`, `email_template_code`, `email_template_subject`, `email_template`, `email_template_allowed_tags`) VALUES
(1, 'Share Video Template', 'share_video_template', '[{website_title}] - {username} wants to share a video with you', '<html>\r\n<head>\r\n<style type="text/css">\r\n<!--\r\n.title {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #FFFFFF;\r\n	font-size: 16px;\r\n}\r\n.title2 {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 14px;\r\n}\r\n.messege {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 12px;\r\n}\r\n#videoThumb{\r\n	width: 120px;\r\n	padding: 2px;\r\n	margin: 3px;\r\n	border: 1px solid #F0F0F0;\r\n	text-align: center;\r\n	vertical-align: middle;\r\n}\r\n#videoThumb img{border:0px}\r\nbody,td,th {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #FFFFFF;\r\n}\r\n.text {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #000000;\r\n	padding: 5px;\r\n}\r\n-->\r\n</style>\r\n</head>\r\n<body>\r\n<table width="100%" border="0" cellspacing="0" cellpadding="5">\r\n  <tr>\r\n    <td bgcolor="#53baff" ><span class="title">{website_title}</span>share video</td>\r\n  </tr>\r\n  <tr>\r\n    <td height="20" class="messege">{username} wants to share Video With You\r\n      <div id="videoThumb"><a href="{video_link}"><img src="{video_thumb}"><br>\r\n    watch video</a></div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class="text" ><span class="title2">Video Description</span><br>\r\n      <span class="text">{video_description}</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td><span class="title2">Personal Message</span><br>\r\n      <span class="text">{user_message}\r\n      </span><br>\r\n      <br>\r\n<span class="text">Thanks,</span><br> \r\n<span class="text">{username}</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td bgcolor="#53baff">copyrights {date_year} {website_title}</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>', '{website_title},{'),
(2, 'Email Verification Template', 'email_verify_template', '[{website_title}] - Account activation email', 'Hello {username},\r\nThank you for joining {website_title}, one last step is required in order to activate your account\r\n\r\n<a href=''{baseurl}/activation.php?av_username={username}&avcode={avcode}''>Click Here</a>\r\n{baseurl}/activation.php?av_username={username}&avcode={avcode}\r\n\r\nEmail           : {email}\r\nUsername        : {username}\r\nActivation code : {avcode}\r\n\r\nif above given is not working , please go here and activate it\r\n<a href=''{baseurl}/activation.php''>{baseurl}/activation.php</a>\r\n\r\n====================\r\nRegards\r\n{website_title}', ''),
(3, 'Private Message Notification', 'pm_email_message', '[{website_title}] - {sender} has sent you a private message', '{sender} has sent you a private message, \r\n\r\n{subject}\r\n"{content}"\r\n\r\nclick here to view your inbox <a href="{baseurl}/private_message.php?mode=inbox&mid={msg_id}">{baseurl}/private_message.php?mode=inbox&mid={msg_id}</a>\r\n\r\n{website_title}', ''),
(4, 'Acitvation code request template', 'avcode_request_template', '[{website_title}] - Account activation code request', 'Hello {username},\r\n\r\nYour Activation Code is : {avcode}\r\n<a href=''{baseurl}/activation.php?av_username={username}&avcode={avcode}''>Click Here</a> To goto Activation Page\r\n\r\nDirect Activation\r\n==========================================\r\nClick Here or Copy & Paste the following link in your browser\r\n{baseurl}/activation.php?av_username={username}&avcode={avcode}\r\n\r\nif above given links are not working, please go here and activate it\r\n\r\nEmail           : {email}\r\nUsername        : {username}\r\nActivation code : {avcode}\r\n\r\nif above given is not working , please go here and activate it\r\n<a href=''{baseurl}/activation.php''>{baseurl}/activation.php</a>\r\n\r\n----------------\r\nRegards\r\n{website_title}', 'username,email,avcode,doj'),
(5, 'Welcome Message Template', 'welcome_message_template', 'Welcome {username} to {website_title}', 'Hello {username},\r\nThanks for joining at {website_title}!, you are now part of our community and we hope you will enjoy your stay\r\n\r\nAll the best,\r\n{website_title}', 'username,email'),
(6, 'Password Reset Request', 'password_reset_request', '[{website_title}] - Password reset confirmation', 'Dear {username}\r\nyou have requested a password reset, please follow the link in order to reset your password\r\n<a href="{baseurl}/forgot.php?mode=reset_pass&user={userid}&avcode={avcode}">{baseurl}/forgot.php?mode=reset_pass&user={userid}&avcode={avcode}</a>\r\n\r\n-----------------------------------------\r\nIF YOU HAVE NOT REQUESTED A PASSWORD RESTE - PLEASE IGNORE THIS MESSAGE\r\n-----------------------------------------\r\nRegards\r\n{website_title}', 'username,userid,avcode'),
(7, 'Passwor Reset Details', 'password_reset_details', '[{website_title}] - Password reset details', 'Dear {username}\r\nyour password has been reset\r\nyour new password is : {password}\r\n\r\n<a href="{login_link}">click here to login to website</a>\r\n<{login_link}>\r\n\r\n---------------\r\nRegards\r\n{website_title}', 'username,password'),
(8, 'Forgot username request', 'forgot_username_request', '[{website_title}] - your {website_title} username', 'Hello,\r\nyour {website_title} username is : {username}\r\n\r\n--------------\r\nRegards\r\n{website_title}', '{username}'),
(9, 'Friend Request Email', 'friend_request_email', '[{website_title}] {username} add you as friend', 'Hi {reciever},\r\n{sender} added you as a friend on {website_title}. We need to confirm that you know {sender} in order for you to be friends on {website_title}.\r\n\r\n<a href="{sender_link}">View profile of {sender}</a> \r\n<a href="{request_link}">lick here to respond to friendship request</a>\r\n\r\nThanks,\r\n{website_title} Team', 'reciever,sender,sender_link,request_link'),
(10, 'Friend Confirmation Email', 'friend_confirmation_email', '[{website_title}] - {sender} has confirmed you as a friend', 'Hi {reciever},\r\n{sender} confirmed you as a friend on {website_title}.\r\n\r\n<a href="{sender_link}">View {sender} profile</a>\r\n\r\nThanks,\r\nThe Facebook Team', 'sender,reciever,sender_link'),
(11, 'Group Invitation', 'group_invitation', '[{website_title}] {sender} has invited you to join group &#8220;{group_name}&#8221;', '{sender} invited you to join the {website_title} group "{group_name}".\r\n\r\n{group_description}\r\n\r\nTo see more details and confirm this group invitation, follow the link below:\r\n<a href="{group_url}">{group_url}</a>\r\n\r\nThanks,\r\n{website_title}', 'sender,reciever,group_name,group_url');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE `favorites` (
  `favorite_id` int(225) NOT NULL AUTO_INCREMENT,
  `type` varchar(4) CHARACTER SET latin1 NOT NULL,
  `id` int(225) NOT NULL,
  `userid` int(225) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`favorite_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `favorites`
--


-- --------------------------------------------------------

--
-- Table structure for table `flags`
--

DROP TABLE IF EXISTS `flags`;
CREATE TABLE `flags` (
  `flag_id` int(225) NOT NULL AUTO_INCREMENT,
  `type` varchar(4) CHARACTER SET latin1 NOT NULL,
  `id` int(225) NOT NULL,
  `userid` int(225) NOT NULL,
  `flag_type` bigint(25) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`flag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `flags`
--


-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `group_id` int(225) NOT NULL AUTO_INCREMENT,
  `group_name` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(255) NOT NULL,
  `group_description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_tags` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_url` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_privacy` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `video_type` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `post_type` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `active` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `date_added` datetime NOT NULL,
  `featured` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `total_views` bigint(225) NOT NULL,
  `total_videos` int(225) NOT NULL,
  `total_members` int(225) NOT NULL,
  `total_topics` int(225) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_categories`
--

DROP TABLE IF EXISTS `group_categories`;
CREATE TABLE `group_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_categories`
--

INSERT INTO `group_categories` (`category_id`, `category_name`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Uncategorized', 'all uncategorized groups', '2009-12-29 09:50:15', '', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `group_invitations`
--

DROP TABLE IF EXISTS `group_invitations`;
CREATE TABLE `group_invitations` (
  `invitation_id` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `userid` int(255) NOT NULL,
  `invited` int(225) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`invitation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_invitations`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

DROP TABLE IF EXISTS `group_members`;
CREATE TABLE `group_members` (
  `group_mid` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`group_mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_members`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_posts`
--

DROP TABLE IF EXISTS `group_posts`;
CREATE TABLE `group_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `post_content` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_posts`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_topics`
--

DROP TABLE IF EXISTS `group_topics`;
CREATE TABLE `group_topics` (
  `topic_id` int(225) NOT NULL AUTO_INCREMENT,
  `topic_title` text NOT NULL,
  `userid` int(225) NOT NULL,
  `group_id` int(225) NOT NULL,
  `topic_post` text NOT NULL,
  `date_added` datetime NOT NULL,
  `last_poster` int(225) NOT NULL,
  `last_post_time` datetime NOT NULL,
  `total_views` bigint(225) NOT NULL,
  `total_replies` bigint(225) NOT NULL,
  `topic_icon` varchar(225) NOT NULL,
  `approved` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_topics`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_videos`
--

DROP TABLE IF EXISTS `group_videos`;
CREATE TABLE `group_videos` (
  `group_video_id` int(225) NOT NULL AUTO_INCREMENT,
  `videoid` int(255) NOT NULL,
  `group_id` int(225) NOT NULL,
  `userid` int(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_videos`
--


-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `language_id` int(9) NOT NULL AUTO_INCREMENT,
  `language_code` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `language_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `language_regex` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language_default` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`language_id`, `language_code`, `language_name`, `language_regex`, `language_default`) VALUES
(1, 'en', 'English', '/^en/i', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `message_id` int(225) NOT NULL AUTO_INCREMENT,
  `message_from` int(20) NOT NULL,
  `message_to` varchar(200) NOT NULL,
  `message_content` mediumtext NOT NULL,
  `message_type` enum('pm','notification') NOT NULL DEFAULT 'pm',
  `message_attachments` mediumtext NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message_subject` mediumtext NOT NULL,
  `message_status` enum('unread','read') NOT NULL DEFAULT 'unread',
  `reply_to` int(225) NOT NULL DEFAULT '0',
  `message_box` enum('in','out') NOT NULL DEFAULT 'in',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `module_id` int(25) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(25) NOT NULL,
  `module_file` varchar(60) NOT NULL,
  `active` varchar(5) NOT NULL,
  `module_include_file` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `modules`
--


-- --------------------------------------------------------

--
-- Table structure for table `phrases`
--

DROP TABLE IF EXISTS `phrases`;
CREATE TABLE `phrases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang_iso` varchar(5) NOT NULL DEFAULT 'en',
  `varname` varchar(250) NOT NULL DEFAULT '',
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=860 ;

--
-- Dumping data for table `phrases`
--

INSERT INTO `phrases` (`id`, `lang_iso`, `varname`, `text`) VALUES
(1, 'en', 'ad_name_error', 'Please Enter Name For The Advertisments'),
(2, 'en', 'ad_code_error', 'Error : Please Enter Code For Advertisement'),
(3, 'en', 'ad_exists_error1', 'Add Does not exists'),
(4, 'en', 'ad_exists_error2', 'Error : Advertisement With This Name Already Exists'),
(5, 'en', 'ad_add_msg', 'Advertisment Has Been Added'),
(6, 'en', 'ad_msg', 'Ad Has Been '),
(7, 'en', 'ad_update_msg', 'Advertisment Has Been Updated'),
(8, 'en', 'ad_del_msg', 'Advertisement Has Been Deleted'),
(9, 'en', 'ad_deactive', 'Deactivated'),
(10, 'en', 'ad_active', 'Activated'),
(11, 'en', 'ad_placment_delete_msg', 'Placement Has Been Removed'),
(12, 'en', 'ad_placement_err1', 'Placement already exists'),
(13, 'en', 'ad_placement_err2', 'Please Enter Name For Placement'),
(14, 'en', 'ad_placement_err3', 'Please Enter Code For Placement'),
(15, 'en', 'ad_placement_msg', 'Placement Has Been Added'),
(16, 'en', 'cat_img_error', 'Please Upload JPEG, GIF or PNG image only'),
(17, 'en', 'cat_exist_error', 'Category doesn&#8217;t exist'),
(18, 'en', 'cat_add_msg', 'Category has been added successfully'),
(19, 'en', 'cat_update_msg', 'Category has been updated'),
(20, 'en', 'grp_err', 'Group Doesn&#8217;t Exist'),
(21, 'en', 'grp_fr_msg', 'Group Has Been Set to Featured'),
(22, 'en', 'grp_fr_msg1', 'Selected Groups Have Been Removed From The Featured List'),
(23, 'en', 'grp_ac_msg', 'Selected Groups Have Been Activated'),
(24, 'en', 'grp_dac_msg', 'Selected Groups Have Been Dectivated'),
(25, 'en', 'grp_del_msg', 'Group Has Been Delete'),
(26, 'en', 'editor_pic_up', 'Video Has Been Moved Up'),
(27, 'en', 'editor_pic_down', 'Video Has Been Moved Down'),
(28, 'en', 'plugin_install_msg', 'Plugin has been installed'),
(29, 'en', 'plugin_no_file_err', 'No file was found'),
(30, 'en', 'plugin_file_detail_err', 'Unknown plugin details found'),
(31, 'en', 'plugin_installed_err', 'Plugin already installed'),
(32, 'en', 'plugin_no_install_err', 'Plugin is not installed'),
(33, 'en', 'grp_name_error', 'Please enter group name'),
(34, 'en', 'grp_name_error1', 'Group Name Already Exists'),
(35, 'en', 'grp_des_error', 'Please Enter Little Description For Group'),
(36, 'en', 'grp_tags_error', 'Please Enter Tags For Group'),
(37, 'en', 'grp_url_error', 'Please enter valid url for Group'),
(38, 'en', 'grp_url_error1', 'Please enter Valid URL name'),
(39, 'en', 'grp_url_error2', 'Group URL Already Exists, Please Choose a Different URL'),
(40, 'en', 'grp_tpc_error', 'Please enter a topic to add'),
(41, 'en', 'grp_comment_error', 'You must enter a comment'),
(42, 'en', 'grp_join_error', 'You have already joined this group'),
(43, 'en', 'grp_prvt_error', 'This Group Is Private, Please Login to View this Group'),
(44, 'en', 'grp_inact_error', 'This Group Is Inactive, Please Contact Administrator for the problem'),
(45, 'en', 'grp_join_error1', 'You Have Not Joined This Group Yet'),
(46, 'en', 'grp_exist_error', 'Sorry, Group Doesn&#8217;t Exist'),
(47, 'en', 'grp_tpc_error1', 'This Topic Is Not Approved By The Group Owner'),
(48, 'en', 'grp_cat_error', 'Please Select A Category For Your group'),
(49, 'en', 'grp_tpc_error2', 'Please enter topic to add'),
(50, 'en', 'grp_tpc_error3', 'Your Topic Requires Approval From Owner Of This Group'),
(51, 'en', 'grp_tpc_msg', 'Topic Has Been Added'),
(52, 'en', 'grp_comment_msg', 'Comment Has Been Added'),
(53, 'en', 'grp_vdo_msg', 'Videos Have Been Deleted'),
(54, 'en', 'grp_vdo_msg1', 'Videos Has Been Added Successfully'),
(55, 'en', 'grp_vdo_msg2', 'Videos Have Been Approved'),
(56, 'en', 'grp_mem_msg', 'Member Has Been Deleted'),
(57, 'en', 'grp_mem_msg1', 'Member Has Been Approved'),
(58, 'en', 'grp_inv_msg', 'Your Invitation Has Been Sent'),
(59, 'en', 'grp_tpc_msg1', 'Topic Has Been Delete'),
(60, 'en', 'grp_tpc_msg2', 'Topic Has Been Approved'),
(61, 'en', 'grp_fr_msg2', 'Group has been un featured'),
(62, 'en', 'grp_inv_msg1', 'Has Invited You To Join '),
(63, 'en', 'grp_av_msg', 'Group Has Been Activated'),
(64, 'en', 'grp_da_msg', 'Group Has Been DeActivated'),
(65, 'en', 'grp_post_msg', 'Post Has Been Delete'),
(66, 'en', 'grp_update_msg', 'Group Has Been Updated'),
(67, 'en', 'grp_owner_err', 'Only Owner Can Add Videos To This Group'),
(68, 'en', 'grp_owner_err1', 'You Are Not Group Owner'),
(69, 'en', 'grp_owner_err2', 'You Are Group Owner , You Cannot Leave Your Group'),
(70, 'en', 'grp_prvt_err1', 'This group is private, you need invitiation from its owner in order to join this group'),
(71, 'en', 'grp_rmv_msg', 'Selected Groups Have Been Removed From Your Account'),
(72, 'en', 'grp_tpc_err4', 'Sorry, Topic Doesn&#8217;t Exist'),
(73, 'en', 'grp_title_topic', 'Groups - Topic - '),
(74, 'en', 'grp_add_title', '- Add Video'),
(75, 'en', 'usr_sadmin_err', 'You Cannot Set SuperAdmin Username as Blank'),
(76, 'en', 'usr_cpass_err', 'Confirm Password Doesn&#8217;t Match'),
(77, 'en', 'usr_pass_err', 'Old password is incorrect'),
(78, 'en', 'usr_email_err', 'Please Provide A Valid Email Address'),
(79, 'en', 'usr_cpass_err1', 'Confirm password is incorrect'),
(80, 'en', 'usr_pass_err1', 'Password is Incorrect'),
(81, 'en', 'usr_cmt_err', 'You Must Login First To Comment'),
(82, 'en', 'usr_cmt_err1', 'Please Type Something In Comment Box'),
(83, 'en', 'usr_cmt_err2', 'You Cannot Post Comment on  Your Own Video'),
(84, 'en', 'usr_cmt_err3', 'You Have Already Posted a Comment on this channel.'),
(85, 'en', 'usr_cmt_err4', 'Comment Has Been Added'),
(86, 'en', 'usr_cmt_del_msg', 'Comment Has Been Deleted'),
(87, 'en', 'usr_cmt_del_err', 'An Error Occured While deleting a Comment'),
(88, 'en', 'usr_cnt_err', 'You Cannot Add Yourself as a Contact'),
(89, 'en', 'usr_cnt_err1', 'You Have Already Added This User To Your Contact List'),
(90, 'en', 'usr_sub_err', 'You are already subsctibed to %s'),
(91, 'en', 'usr_exist_err', 'User Doesnt Exist'),
(92, 'en', 'usr_ccode_err', 'You Have Entered Wrong Confirmation Code'),
(93, 'en', 'usr_exist_err1', 'Sorry, No User Exists With This Email'),
(94, 'en', 'usr_exist_err2', 'Sorry , User Doesnt Exists'),
(95, 'en', 'usr_uname_err', 'Username is empty'),
(96, 'en', 'usr_uname_err2', 'Username already exists'),
(97, 'en', 'usr_pass_err2', 'Password Is Empty'),
(98, 'en', 'usr_email_err1', 'Email is Empty'),
(99, 'en', 'usr_email_err2', 'Please Enter A Valid Email Address'),
(100, 'en', 'usr_email_err3', 'Email Address Is Already In Use'),
(101, 'en', 'usr_pcode_err', 'Postal Code Only Contains Number'),
(102, 'en', 'usr_fname_err', 'First Name Is Empty'),
(103, 'en', 'usr_lname_err', 'Last Name Is Empty'),
(104, 'en', 'usr_uname_err3', 'Username Contains Unallowed Characters'),
(105, 'en', 'usr_pass_err3', 'Passwords MisMatched'),
(106, 'en', 'usr_dob_err', 'Please Select Date Of Birth'),
(107, 'en', 'usr_ament_err', 'Sorry, you need to agree to the terms of use and privacy policy to create an account'),
(108, 'en', 'usr_reg_err', 'Sorry, Registrations Are Temporarily Not Allowed, Please Try Again Later'),
(109, 'en', 'usr_ban_err', 'User account is banned, please contact website administrator'),
(110, 'en', 'usr_login_err', 'Username Password Didn&#8217;t Match'),
(111, 'en', 'usr_sadmin_msg', 'Super Admin Has Been Updated'),
(112, 'en', 'usr_pass_msg', 'Your Password Has Been Changed'),
(113, 'en', 'usr_cnt_msg', 'This User Has Been Added To Your Contact List'),
(114, 'en', 'usr_sub_msg', 'You are now subsribed to %s'),
(115, 'en', 'usr_uname_email_msg', 'We Have Sent you an Email containing Your Usename, Please Check It'),
(116, 'en', 'usr_rpass_email_msg', 'Email Has Sent To You Please Follow the Instructions to Reset Your Password'),
(117, 'en', 'usr_pass_email_msg', 'Password has been changed successfully'),
(118, 'en', 'usr_email_msg', 'Email Settings Has Been Updated'),
(119, 'en', 'usr_del_msg', 'User Has Been Deleted Successfully'),
(120, 'en', 'usr_dels_msg', 'Selected Users Have Been Deleted'),
(121, 'en', 'usr_ac_msg', 'User Has Been Activated'),
(122, 'en', 'usr_dac_msg', 'User Has Been Deactivated'),
(123, 'en', 'usr_mem_ac', 'Selected Members Have Been Activated'),
(124, 'en', 'usr_mems_ac', 'Selected Members Have Been Dectivated'),
(125, 'en', 'usr_fr_msg', 'User Has Been Made Featured Member'),
(126, 'en', 'usr_ufr_msg', 'User Has Been Unfeatured'),
(127, 'en', 'usr_frs_msg', 'Selected Users Have Been Set As Featured'),
(128, 'en', 'usr_ufrs_msg', 'Selected Users Have Been Removed From The Featured List'),
(129, 'en', 'usr_uban_msg', 'User Has Been Banned'),
(130, 'en', 'usr_uuban_msg', 'User Has Been Unbanned'),
(131, 'en', 'usr_ubans_msg', 'Selected Members Have Been Banned'),
(132, 'en', 'usr_uubans_msg', 'Selected Members Have Been Unbanned'),
(133, 'en', 'usr_pass_reset_conf', 'Password Reset Confirmation'),
(134, 'en', 'usr_dear_user', 'Dear User'),
(135, 'en', 'usr_pass_reset_msg', 'You Requested A Password Reset, Follow The Link To Reset Your Password'),
(136, 'en', 'usr_rpass_msg', 'Password Has Been Reset'),
(137, 'en', 'usr_rpass_req_msg', 'You Requested A Password Reset, Here is your new password : '),
(138, 'en', 'usr_uname_req_msg', 'You Requested to Recover Your Username, Here is you username: '),
(139, 'en', 'usr_uname_recovery', 'Username Recovery Email'),
(140, 'en', 'usr_add_succ_msg', 'User Has Been Added, Successfully'),
(141, 'en', 'usr_upd_succ_msg', 'User has been updated'),
(142, 'en', 'usr_activation_msg', 'Your account has been activated, Now you can login to your account and upload videos'),
(143, 'en', 'usr_activation_err', 'This user is already activated'),
(144, 'en', 'usr_activation_em_msg', 'We have sent you an email containing activation code, please check your mail box'),
(145, 'en', 'usr_activation_em_err', 'Email Doesn&#8217;t Exist or User With This Email already Acitvated'),
(146, 'en', 'usr_no_msg_del_err', 'No Message Was Selected To Delete'),
(147, 'en', 'usr_sel_msg_del_msg', 'Selected Messages Have Been Deleted'),
(148, 'en', 'usr_pof_upd_msg', 'Profile has been updated'),
(149, 'en', 'usr_arr_no_ans', 'no answer'),
(150, 'en', 'usr_arr_elementary', 'Elementary'),
(151, 'en', 'usr_arr_hi_school', 'High School'),
(152, 'en', 'usr_arr_some_colg', 'Some College'),
(153, 'en', 'usr_arr_assoc_deg', 'Associates Degree'),
(154, 'en', 'usr_arr_bach_deg', 'Bachelor&#8217;s Degree'),
(155, 'en', 'usr_arr_mast_deg', 'Master&#8217;s Degree'),
(156, 'en', 'usr_arr_phd', 'Ph.D.'),
(157, 'en', 'usr_arr_post_doc', 'Postdoctoral'),
(158, 'en', 'usr_arr_single', 'Single'),
(159, 'en', 'usr_arr_married', 'Married'),
(160, 'en', 'usr_arr_comitted', 'Comitted'),
(161, 'en', 'usr_arr_open_marriage', 'Open Marriage'),
(162, 'en', 'usr_arr_open_relate', 'Open Relationship'),
(163, 'en', 'title_crt_new_msg', 'Compose new message'),
(164, 'en', 'title_forgot', 'Forgot Something? Find it now !'),
(165, 'en', 'title_inbox', ' - Inbox'),
(166, 'en', 'title_sent', ' - Sent Folder'),
(167, 'en', 'title_usr_contact', '&#8217;s Contact List'),
(168, 'en', 'title_usr_fav_vids', '%&#8217;s favorite videos'),
(169, 'en', 'title_view_channel', '&#8217;s Channel'),
(170, 'en', 'title_edit_video', 'Edit Video - '),
(171, 'en', 'vdo_title_err', 'Please Enter Video Title'),
(172, 'en', 'vdo_des_err', 'Please Enter Video Description'),
(173, 'en', 'vdo_tags_err', 'Please Enter Tags For The Video'),
(174, 'en', 'vdo_cat_err', 'Please Choose Atleast 1 Category'),
(175, 'en', 'vdo_cat_err1', 'You Can Only Choose Upto 3 Categories'),
(176, 'en', 'vdo_sub_email_msg', ' and therefore this message is sent to you automatically that '),
(177, 'en', 'vdo_has_upload_nv', 'Has Uploaded New Video'),
(178, 'en', 'vdo_del_selected', 'Selected Videos Have Been Deleted'),
(179, 'en', 'vdo_cheat_msg', 'Please Dont Try To Cheat'),
(180, 'en', 'vdo_limits_warn_msg', 'Please Dont Try To Cross Your Limits'),
(181, 'en', 'vdo_cmt_del_msg', 'Comment Has Been Deleted'),
(182, 'en', 'vdo_iac_msg', 'Video Is Inactive - Please Contact Admin For Details'),
(183, 'en', 'vdo_is_in_process', 'Video Is In Process - Please Contact Administrator for further details'),
(184, 'en', 'vdo_upload_allow_err', 'Uploading Is Not Allowed By Website Owner'),
(185, 'en', 'vdo_download_allow_err', 'Video Downloading Is Not Allowed'),
(186, 'en', 'vdo_edit_owner_err', 'You Are Not Video Owner'),
(187, 'en', 'vdo_embed_code_wrong', 'Embed Code Was Wrong'),
(188, 'en', 'vdo_seconds_err', 'Wrong Value Entered For Seconds Field'),
(189, 'en', 'vdo_mins_err', 'Wrong Value Entered For Minutes Field'),
(190, 'en', 'vdo_thumb_up_err', 'Error In Uploading Thumb'),
(191, 'en', 'class_error_occured', 'Sorry, An Error Occured'),
(192, 'en', 'class_cat_del_msg', 'Category has been deleted'),
(193, 'en', 'class_vdo_del_msg', 'Video has been deleted'),
(194, 'en', 'class_vdo_fr_msg', 'Video has been to &#8220;Featured Video&#8221;'),
(195, 'en', 'class_fr_msg1', 'Video has been removed from &#8220;Featured Videos&#8221;'),
(196, 'en', 'class_vdo_act_msg', 'Video has been activated'),
(197, 'en', 'class_vdo_act_msg1', 'Vidoe has been deactivated'),
(198, 'en', 'class_vdo_update_msg', 'Video Has Been Updated Successfully'),
(199, 'en', 'class_comment_err', 'You Must Login First To Comment'),
(200, 'en', 'class_comment_err1', 'Please Type Something In Comment Box'),
(201, 'en', 'class_comment_err2', 'You Cannot Post Comment on  Your Own Video'),
(202, 'en', 'class_comment_err3', 'You Have Already Posted a Comment, Please Wait for the others.'),
(203, 'en', 'class_comment_err4', 'You Have Already Replied To That a Comment, Please Wait for the others.'),
(204, 'en', 'class_comment_err5', 'You Cannot Post Reply To Yourself'),
(205, 'en', 'class_comment_msg', 'Comment Has Been Added'),
(206, 'en', 'class_comment_err6', 'Please login to rate comment'),
(207, 'en', 'class_comment_err7', 'You have already rated this comment'),
(208, 'en', 'class_vdo_fav_err', 'This Video is Already Added To Your Favourites'),
(209, 'en', 'class_vdo_fav_msg', 'This Video Has Been Added To Your Favourites'),
(210, 'en', 'class_vdo_flag_err', 'You Have Already Flagged This Video'),
(211, 'en', 'class_vdo_flag_msg', 'This Video Has Been Flagged As Inappropriate'),
(212, 'en', 'class_vdo_flag_rm', 'Flag(s) Has/Have Been Removed'),
(213, 'en', 'class_send_msg_err', 'Please Enter a Username or Select any User to Send Message'),
(214, 'en', 'class_invalid_user', 'Invalid Username'),
(215, 'en', 'class_subj_err', 'Message subject was empty'),
(216, 'en', 'class_msg_err', 'Please Type Something In Message Box'),
(217, 'en', 'class_sent_you_msg', 'Sent You A Message'),
(218, 'en', 'class_sent_prvt_msg', 'Sent You A Private Message on '),
(219, 'en', 'class_click_inbox', 'Please Click here To View Your Inbox'),
(220, 'en', 'class_click_login', 'Click Here To Login'),
(221, 'en', 'class_email_notify', 'Email Notification'),
(222, 'en', 'class_msg_has_sent_to', 'Message Has Been Sent To '),
(223, 'en', 'class_inbox_del_msg', 'Message Has Been Delete From Inbox '),
(224, 'en', 'class_sent_del_msg', 'Message Has Been Delete From Sent Folder'),
(225, 'en', 'class_msg_exist_err', 'Message Doesnt Exist'),
(226, 'en', 'class_vdo_del_err', 'Video does not exist'),
(227, 'en', 'class_unsub_msg', 'You Have Unsubscribed'),
(228, 'en', 'class_sub_exist_err', 'Subscription Does Not Exist'),
(229, 'en', 'class_vdo_rm_fav_msg', 'Video Has Been Removed From Favourites'),
(230, 'en', 'class_vdo_fav_err1', 'This Video Is Not In Your Favourite List'),
(231, 'en', 'class_cont_del_msg', 'Contact Has Been Delete'),
(232, 'en', 'class_cot_err', 'Sorry, This Contact Is Not In Your Contact List'),
(233, 'en', 'class_vdo_ep_add_msg', 'Video Has Been Added To Editor&#8217;s Pick'),
(234, 'en', 'class_vdo_ep_err', 'Video Is Already In The Editor&#8217;s Pick'),
(235, 'en', 'class_vdo_ep_err1', 'You Have Already Picked 10 Videos Please Delete Alteast One to Add More'),
(236, 'en', 'class_vdo_ep_msg', 'Video Has Been Removed From Editor&#8217;s Pick'),
(237, 'en', 'class_vdo_exist_err', 'Sorry, Video Doesnt Exist'),
(238, 'en', 'class_img_gif_err', 'Please Upload Gif Image Only'),
(239, 'en', 'class_img_png_err', 'Please Upload Png Image Only'),
(240, 'en', 'class_img_jpg_err', 'Please Upload Jpg Image Only'),
(241, 'en', 'class_logo_msg', 'Logo Has Been ChangedPlease Clear Cache If You Are Not Able To See Changed Logo'),
(242, 'en', 'com_forgot_username', 'Forgot Username | Password'),
(243, 'en', 'com_join_now', 'Join Now'),
(244, 'en', 'com_my_account', 'My Account'),
(245, 'en', 'com_manage_vids', 'Manage Videos'),
(246, 'en', 'com_view_channel', 'View My Channel'),
(247, 'en', 'com_my_inbox', 'My Inbox'),
(248, 'en', 'com_welcome', 'Welcome'),
(249, 'en', 'com_top_mem', 'Top Members '),
(250, 'en', 'com_vidz', 'Videos'),
(251, 'en', 'com_sign_up_now', 'Sign Up Now !'),
(252, 'en', 'com_my_videos', 'My Videos'),
(253, 'en', 'com_my_channel', 'My Channel'),
(254, 'en', 'com_my_subs', 'My Subscriptions'),
(255, 'en', 'com_user_no_contacts', 'User Does Not Have Any Contact'),
(256, 'en', 'com_user_no_vides', 'User Does Not Have Any Favourite Video'),
(257, 'en', 'com_user_no_vid_com', 'User Has No Video Comments'),
(258, 'en', 'com_view_all_contacts', 'View All Contacts of'),
(259, 'en', 'com_view_fav_all_videos', 'View All Favourite Videos Of'),
(260, 'en', 'com_login_success_msg', 'You Have Been Successfully Logged In.'),
(261, 'en', 'com_logout_success_msg', 'You Have Been Successfully Logged Out.'),
(262, 'en', 'com_not_redirecting', 'You are now Redirecting .'),
(263, 'en', 'com_not_redirecting_msg', 'if your are not redirecting'),
(264, 'en', 'com_manage_contacts', 'Manage Contacts '),
(265, 'en', 'com_send_message', 'Send Message'),
(266, 'en', 'com_manage_fav', 'Manage Favorites '),
(267, 'en', 'com_manage_subs', 'Manage Subscriptions'),
(268, 'en', 'com_subscribe_to', 'Subscribe to %s&#8217;s channel'),
(269, 'en', 'com_total_subs', 'Total Subscribtions'),
(270, 'en', 'com_total_vids', 'Total Videos'),
(271, 'en', 'com_date_subscribed', 'Date Subscribed'),
(272, 'en', 'com_search_results', 'Search Results'),
(273, 'en', 'com_advance_results', 'Advance Search'),
(274, 'en', 'com_search_results_in', 'Search Results In'),
(275, 'en', 'videos_being_watched', 'Recently Viewed...'),
(276, 'en', 'latest_added_videos', 'Recent Additions'),
(277, 'en', 'most_viewed', 'Most Viewed'),
(278, 'en', 'recently_added', 'Recently Added'),
(279, 'en', 'featured', 'Featured'),
(280, 'en', 'highest_rated', 'Highest Rated'),
(281, 'en', 'most_discussed', 'Most Discussed'),
(282, 'en', 'style_change', 'Style Change'),
(283, 'en', 'rss_feed_latest_title', 'RSS Feed for Most Recent Videos'),
(284, 'en', 'rss_feed_featured_title', 'RSS Feed for Featured Videos'),
(285, 'en', 'rss_feed_most_viewed_title', 'RSS Feed for Most Popular Videos'),
(286, 'en', 'lang_folder', 'en'),
(287, 'en', 'reg_closed', 'Registration Closed'),
(288, 'en', 'reg_for', 'Registration for'),
(289, 'en', 'is_currently_closed', 'is currently closed'),
(290, 'en', 'about_us', 'About Us'),
(291, 'en', 'account', 'Account'),
(292, 'en', 'added', 'Added'),
(293, 'en', 'advertisements', 'Advertisements'),
(294, 'en', 'all', 'All'),
(295, 'en', 'active', 'Active'),
(296, 'en', 'activate', 'Activate'),
(297, 'en', 'age', 'Age'),
(298, 'en', 'approve', 'Approve'),
(299, 'en', 'approved', 'Approved'),
(300, 'en', 'approval', 'Approval'),
(301, 'en', 'books', 'Books'),
(302, 'en', 'browse', 'Browse'),
(303, 'en', 'by', 'by'),
(304, 'en', 'cancel', 'Cancel'),
(305, 'en', 'categories', 'Categories'),
(306, 'en', 'category', 'Category'),
(307, 'en', 'channels', 'channels'),
(308, 'en', 'check_all', 'Check All'),
(309, 'en', 'click_here', 'Click Here'),
(310, 'en', 'comments', 'Comments'),
(311, 'en', 'community', 'Community'),
(312, 'en', 'companies', 'Companies'),
(313, 'en', 'contacts', 'Contacts'),
(314, 'en', 'contact_us', 'Contact Us'),
(315, 'en', 'country', 'Country'),
(316, 'en', 'created', 'Created'),
(317, 'en', 'date', 'Date'),
(318, 'en', 'date_added', 'Date Added'),
(319, 'en', 'date_joined', 'Date Joined'),
(320, 'en', 'dear', 'Dear'),
(321, 'en', 'delete', 'Delete'),
(322, 'en', 'delete_selected', 'Delete Selected'),
(323, 'en', 'des_title', 'Description:'),
(324, 'en', 'duration', 'Duration'),
(325, 'en', 'education', 'Education'),
(326, 'en', 'email', 'email'),
(327, 'en', 'embed', 'Embed'),
(328, 'en', 'embed_code', 'Embed Code'),
(329, 'en', 'favourite', 'Favorite'),
(330, 'en', 'favourited', 'Favorited'),
(331, 'en', 'favourites', 'Favorites'),
(332, 'en', 'female', 'Female'),
(333, 'en', 'filter', 'Filter'),
(334, 'en', 'forgot', 'Forgot'),
(335, 'en', 'friends', 'Friends'),
(336, 'en', 'from', 'From'),
(337, 'en', 'gender', 'Gender'),
(338, 'en', 'groups', 'Groups'),
(339, 'en', 'hello', 'Hello'),
(340, 'en', 'help', 'Help'),
(341, 'en', 'hi', 'Hi'),
(342, 'en', 'hobbies', 'Hobbies'),
(343, 'en', 'Home', 'Home'),
(344, 'en', 'inbox', 'inbox'),
(345, 'en', 'interests', 'Interests'),
(346, 'en', 'join_now', 'Join Now'),
(347, 'en', 'joined', 'Joined'),
(348, 'en', 'join', 'Join'),
(349, 'en', 'keywords', 'Keywords'),
(350, 'en', 'latest', 'Latest'),
(351, 'en', 'leave', 'Leave'),
(352, 'en', 'location', 'Location'),
(353, 'en', 'login', 'Login'),
(354, 'en', 'logout', 'Logout'),
(355, 'en', 'male', 'Male'),
(356, 'en', 'members', 'Members'),
(357, 'en', 'messages', 'Messages'),
(358, 'en', 'message', 'Message'),
(359, 'en', 'minutes', 'minutes'),
(360, 'en', 'most_members', 'Most Members'),
(361, 'en', 'most_recent', 'Most Recent'),
(362, 'en', 'most_videos', 'Most Videos'),
(363, 'en', 'music', 'Music'),
(364, 'en', 'my_account', 'My Account'),
(365, 'en', 'next', 'Next'),
(366, 'en', 'no', 'No'),
(367, 'en', 'no_user_exists', 'No User Exists'),
(368, 'en', 'no_video_exists', 'No Video Exists'),
(369, 'en', 'occupations', 'Occupations'),
(370, 'en', 'optional', 'optional'),
(371, 'en', 'owner', 'Owner'),
(372, 'en', 'password', 'password'),
(373, 'en', 'please', 'Please'),
(374, 'en', 'privacy', 'Privacy'),
(375, 'en', 'privacy_policy', 'Privacy Policy'),
(376, 'en', 'random', 'Random'),
(377, 'en', 'rate', 'Rate'),
(378, 'en', 'request', 'Request'),
(379, 'en', 'related', 'Related'),
(380, 'en', 'reply', 'Reply'),
(381, 'en', 'results', 'Results'),
(382, 'en', 'relationship', 'Relationship'),
(383, 'en', 'seconds', 'seconds'),
(384, 'en', 'select', 'Select'),
(385, 'en', 'send', 'Send'),
(386, 'en', 'sent', 'Sent'),
(387, 'en', 'signup', 'Signup'),
(388, 'en', 'subject', 'Subject'),
(389, 'en', 'tags', 'Tags'),
(390, 'en', 'times', 'Times'),
(391, 'en', 'to', 'To'),
(392, 'en', 'type', 'Type'),
(393, 'en', 'update', 'Update'),
(394, 'en', 'upload', 'Upload'),
(395, 'en', 'url', 'Url'),
(396, 'en', 'verification', 'Verification'),
(397, 'en', 'videos', 'Videos'),
(398, 'en', 'viewing', 'Viewing'),
(399, 'en', 'welcome', 'Welcome'),
(400, 'en', 'website', 'Website'),
(401, 'en', 'yes', 'Yes'),
(402, 'en', 'of', 'of'),
(403, 'en', 'on', 'on'),
(404, 'en', 'previous', 'Previous'),
(405, 'en', 'rating', 'Rating'),
(406, 'en', 'ratings', 'Ratings'),
(407, 'en', 'remote_upload', 'Remote Upload'),
(408, 'en', 'remove', 'Remove'),
(409, 'en', 'search', 'search'),
(410, 'en', 'services', 'Services'),
(411, 'en', 'show_all', 'Show All'),
(412, 'en', 'signupup', 'Sign Up'),
(413, 'en', 'sort_by', 'Sort By'),
(414, 'en', 'subscriptions', 'Subscriptions'),
(415, 'en', 'subscribers', 'Subscribers'),
(416, 'en', 'tag_title', 'Tags'),
(417, 'en', 'time', 'time'),
(418, 'en', 'top', 'Top'),
(419, 'en', 'tos_title', 'Terms of Use'),
(420, 'en', 'username', 'username'),
(421, 'en', 'views', 'Views'),
(422, 'en', 'proccession_wait', 'Processing, Please Wait'),
(423, 'en', 'mostly_viewed', 'Most Viewed'),
(424, 'en', 'most_comments', 'Most Comments'),
(425, 'en', 'group', 'Group'),
(426, 'en', 'not_logged_in', 'You are not logged in or you do not have permission to access this page. This could be due to one of several reasons:'),
(427, 'en', 'fill_auth_form', 'You are not logged in. Fill in the form below and try again.'),
(428, 'en', 'insufficient_privileges', 'You may not have sufficient privileges to access this page.'),
(429, 'en', 'admin_disabled_you', 'The site administrator may have disabled your account, or it may be awaiting activation.'),
(430, 'en', 'Recover_Password', 'Recover Password'),
(431, 'en', 'Submit', 'Submit'),
(432, 'en', 'Reset_Fields', 'Reset Fields'),
(433, 'en', 'admin_reg_req', 'The administrator may have required you to register before you can view this page.'),
(434, 'en', 'lang_change', 'Language Change'),
(435, 'en', 'lang_changed', 'Your language has been changed'),
(436, 'en', 'lang_choice', 'Language'),
(437, 'en', 'if_not_redir', 'Click here to continue if you are not automatically redirected.'),
(438, 'en', 'style_changed', 'Your style has been changed'),
(439, 'en', 'style_choice', 'Style'),
(440, 'en', 'vdo_edit_vdo', 'Edit Video'),
(441, 'en', 'vdo_stills', 'Video Stills'),
(442, 'en', 'vdo_watch_video', 'Watch Video'),
(443, 'en', 'vdo_video_details', 'Video Details'),
(444, 'en', 'vdo_title', 'Title'),
(445, 'en', 'vdo_desc', 'Description'),
(446, 'en', 'vdo_cat', 'Video Category'),
(447, 'en', 'vdo_cat_msg', 'You May Select Upto %s Categories'),
(448, 'en', 'vdo_tags_msg', 'Tags are separated by commas ie Arslan Hassan, Awsome, ClipBucket'),
(449, 'en', 'vdo_br_opt', 'Broadcast Options'),
(450, 'en', 'vdo_br_opt1', 'Public Share your video with the Everyone! (Recommended)'),
(451, 'en', 'vdo_br_opt2', 'Private Viewable by you and your friends only.'),
(452, 'en', 'vdo_date_loc', 'Date And Location'),
(453, 'en', 'vdo_date_rec', 'Date Recorded'),
(454, 'en', 'vdo_for_date', 'format MM / DD / YYYY '),
(455, 'en', 'vdo_add_eg', 'e.g London Greenland, Sialkot Mubarak Pura'),
(456, 'en', 'vdo_share_opt', 'Sharing Options'),
(457, 'en', 'vdo_allow_comm', 'Allow Comments '),
(458, 'en', 'vdo_dallow_comm', 'Do Not Allow Comments'),
(459, 'en', 'vdo_comm_vote', 'Comments Voting'),
(460, 'en', 'vdo_allow_com_vote', 'Allow Comments Voting '),
(461, 'en', 'vdo_dallow_com_vote', 'Do Not Allow Comments Voting '),
(462, 'en', 'vdo_allow_rating', 'Yes, Allow Rating on this video'),
(463, 'en', 'vdo_dallow_ratig', 'No, Do Not Allow Rating on this video'),
(464, 'en', 'vdo_embedding', 'Embedding'),
(465, 'en', 'vdo_embed_opt1', 'Yes, People can play this video on other websites'),
(466, 'en', 'vdo_embed_opt2', 'No, People cannot play this video on other websites'),
(467, 'en', 'vdo_update_title', 'Update'),
(468, 'en', 'vdo_inactive_msg', 'Your Account is Inactive Please Activate it to Upload Videos, To Activate your account Please'),
(469, 'en', 'vdo_click_here', 'Click Here'),
(470, 'en', 'vdo_continue_upload', 'Continue to Upload'),
(471, 'en', 'vdo_upload_step1', 'Video Upload'),
(472, 'en', 'vdo_upload_step2', '(Step 1/2) Filling Up Details'),
(473, 'en', 'vdo_upload_step3', '(Step 2/2)'),
(474, 'en', 'vdo_select_vdo', 'Select a video to upload.'),
(475, 'en', 'vdo_enter_remote_url', 'Enter Url Of The Video.'),
(476, 'en', 'vdo_enter_embed_code_msg', 'Enter Embed Video Code from other websites ie Youtube or Metacafe.'),
(477, 'en', 'vdo_enter_embed_code', 'Enter Embed Code'),
(478, 'en', 'vdo_enter_druation', 'Enter Duration'),
(479, 'en', 'vdo_select_vdo_thumb', 'Select Video Thumb'),
(480, 'en', 'vdo_having_trouble', 'Having Trouble?'),
(481, 'en', 'vdo_if_having_problem', 'if you having problem with the uploader'),
(482, 'en', 'vdo_clic_to_manage_all', 'Click Here To Manage All Videos'),
(483, 'en', 'vdo_manage_vdeos', 'Manage Videos '),
(484, 'en', 'vdo_status', 'Status'),
(485, 'en', 'vdo_rawfile', 'RawFile'),
(486, 'en', 'vdo_video_upload_complete', 'Video Upload - Upload Complete'),
(487, 'en', 'vdo_thanks_you_upload_complete_1', 'Thank you! Your upload is complete'),
(488, 'en', 'vdo_thanks_you_upload_complete_2', 'This video will be available in'),
(489, 'en', 'vdo_after_it_has_process', 'after it has finished processing.'),
(490, 'en', 'vdo_embed_this_video_on_web', 'Embed this video on your website.'),
(491, 'en', 'vdo_copy_and_paste_the_code', 'Copy and paste the code below to embed this video.'),
(492, 'en', 'vdo_upload_another_video', 'Upload Another Video'),
(493, 'en', 'vdo_goto_my_videos', 'Goto My Videos'),
(494, 'en', 'vdo_sperate_emails_by', 'seperate emails by commas'),
(495, 'en', 'vdo_personal_msg', 'Personal Message'),
(496, 'en', 'vdo_related_tags', 'Related Tags'),
(497, 'en', 'vdo_reply_to_this', 'Reply To This '),
(498, 'en', 'vdo_add_reply', 'Add Reply'),
(499, 'en', 'vdo_share_video', 'Share Video'),
(500, 'en', 'vdo_about_this_video', 'About This Video'),
(501, 'en', 'vdo_post_to_a_services', 'Post to an Aggregating Service'),
(502, 'en', 'vdo_commentary', 'Commentary'),
(503, 'en', 'vdo_post_a_comment', 'Post A Comment'),
(504, 'en', 'grp_add_vdo_msg', 'Add Videos To Group '),
(505, 'en', 'grp_no_vdo_msg', 'You Don&#8217;t Have Any Video'),
(506, 'en', 'grp_add_to', 'Add To Group'),
(507, 'en', 'grp_add_vdos', 'Add Videos'),
(508, 'en', 'grp_name_title', 'Group name'),
(509, 'en', 'grp_tag_title', 'Tags:'),
(510, 'en', 'grp_des_title', 'Description:'),
(511, 'en', 'grp_tags_msg', 'Enter one or more tags, separated by spaces.'),
(512, 'en', 'grp_tags_msg1', 'Enter one or more tags, separated by spaces. Tags  are keywords used to describe your group so it can be easily found by  other users. For example, if you have a group for surfers, you might  tag it: surfing, beach, waves.'),
(513, 'en', 'grp_url_title', 'Choose a unique group name URL:'),
(514, 'en', 'grp_url_msg', 'Enter 3-18 characters with no spaces (such as &#8220;skateboarding skates&#8221;), that will become part of your group&#8217;s web address. Please note, the group name URL you pick is permanent and can&#8217;t be changed.'),
(515, 'en', 'grp_cat_tile', 'Group Category:'),
(516, 'en', 'grp_vdo_uploads', 'Video Uploads:'),
(517, 'en', 'grp_forum_posting', 'Forums Posting:'),
(518, 'en', 'grp_join_opt1', 'Public, anyone can join.'),
(519, 'en', 'grp_join_opt2', 'Protected, requires founder approval to join.'),
(520, 'en', 'grp_join_opt3', 'Private, by founder invite only, only members can view group details.'),
(521, 'en', 'grp_vdo_opt1', 'Post videos immediately.'),
(522, 'en', 'grp_vdo_opt2', 'Founder approval required before video is available.'),
(523, 'en', 'grp_vdo_opt3', 'Only Founder can add new videos.'),
(524, 'en', 'grp_post_opt1', 'Post topics immediately.'),
(525, 'en', 'grp_post_opt2', 'Founder approval required before topic is available.'),
(526, 'en', 'grp_post_opt3', 'Only Founder can create a new topic.'),
(527, 'en', 'grp_crt_grp', 'Create Group'),
(528, 'en', 'grp_thumb_title', 'Group Thumb'),
(529, 'en', 'grp_upl_thumb', 'Upload Group Thumb'),
(530, 'en', 'grp_must_be', 'Must Be'),
(531, 'en', 'grp_90x90', '90  x 90 Ratio Will Give Best quality'),
(532, 'en', 'grp_thumb_warn', 'Do Not Upload Vulgur or Copyrighted Material'),
(533, 'en', 'grp_del_confirm', 'Are You Sure You Want To Delete This Group'),
(534, 'en', 'grp_del_success', 'You Have Successfully Deleted'),
(535, 'en', 'grp_click_go_grps', 'Click Here To Go To Groups'),
(536, 'en', 'grp_edit_grp_title', 'Edit Group'),
(537, 'en', 'grp_manage_vdos', 'Manage Videos'),
(538, 'en', 'grp_manage_mems', 'Manage Members'),
(539, 'en', 'grp_del_group_title', 'Delete Group'),
(540, 'en', 'grp_add_vdos_title', 'Add Videos'),
(541, 'en', 'grp_join_grp_title', 'Join Group'),
(542, 'en', 'grp_leave_group_title', 'Leave Group'),
(543, 'en', 'grp_invite_grp_title', 'Invite Members'),
(544, 'en', 'grp_view_mems', 'View Members'),
(545, 'en', 'grp_view_vdos', 'View Videos'),
(546, 'en', 'grp_create_grp_title', 'Create A New Group'),
(547, 'en', 'grp_most_members', 'Most Members'),
(548, 'en', 'grp_most_discussed', 'Most Discussed'),
(549, 'en', 'grp_invite_msg', 'Invite Users To This Group'),
(550, 'en', 'grp_invite_msg1', 'Has Invited You To Join'),
(551, 'en', 'grp_invite_msg2', 'Enter Emails or Usernames (seperate by commas)'),
(552, 'en', 'grp_url_title1', 'Group url'),
(553, 'en', 'grp_invite_msg3', 'Send Invitation'),
(554, 'en', 'grp_join_confirm_msg', 'Are You Sure You Want To Join This Group'),
(555, 'en', 'grp_join_msg_succ', 'You Have Successfully Joined'),
(556, 'en', 'grp_click_here_to_go', 'Click Here To Go To'),
(557, 'en', 'grp_leave_confirm', 'Are You Sure You Want To Leave This Group'),
(558, 'en', 'grp_leave_succ_msg', 'You Have Successfully Left'),
(559, 'en', 'grp_manage_members_title', 'Manage Members '),
(560, 'en', 'grp_for_approval', 'For Approval'),
(561, 'en', 'grp_rm_videos', 'Remove Videos'),
(562, 'en', 'grp_rm_mems', 'Remove Members'),
(563, 'en', 'grp_groups_title', 'Manage Groups'),
(564, 'en', 'grp_remove_group', 'Remove Group'),
(565, 'en', 'grp_bo_grp_found', 'No Group Found'),
(566, 'en', 'grp_joined_groups', 'Joined Groups'),
(567, 'en', 'grp_owned_groups', 'Owned Groups'),
(568, 'en', 'grp_edit_this_grp', 'Edit This Group'),
(569, 'en', 'grp_topics_title', 'Topics'),
(570, 'en', 'grp_topic_title', 'Topic'),
(571, 'en', 'grp_posts_title', 'Posts'),
(572, 'en', 'grp_discus_title', 'Discussions'),
(573, 'en', 'grp_author_title', 'Author'),
(574, 'en', 'grp_replies_title', 'Replies'),
(575, 'en', 'grp_last_post_title', 'Last Post '),
(576, 'en', 'grp_viewl_all_videos', 'View All Videos of This Group'),
(577, 'en', 'grp_add_new_topic', 'Add New Topic'),
(578, 'en', 'grp_attach_video', 'Attach Video '),
(579, 'en', 'grp_add_topic', 'Add Topic'),
(580, 'en', 'grp_please_login', 'Please Login To Post Topics'),
(581, 'en', 'grp_please_join', 'Please Join This Group To Post Topics'),
(582, 'en', 'grp_inactive_account', 'Your Account Is Inactive And Required Activation From Group Owner'),
(583, 'en', 'grp_about_this_grp', 'About This Group '),
(584, 'en', 'grp_no_vdo_err', 'This Group Has No Vidoes'),
(585, 'en', 'grp_posted_by', 'Posted by'),
(586, 'en', 'grp_add_new_comment', 'Add New Comment'),
(587, 'en', 'grp_add_comment', 'Add Comment'),
(588, 'en', 'grp_pls_login_comment', 'Please Login To Post Comments'),
(589, 'en', 'grp_pls_join_comment', 'Please Join This Group To Post Comments'),
(590, 'en', 'usr_activation_title', 'User Activation'),
(591, 'en', 'usr_actiavation_msg', 'Enter Your Username and Activation Code that has been sent to your email.'),
(592, 'en', 'usr_actiavation_msg1', 'Request Activation Code'),
(593, 'en', 'usr_activation_code_tl', 'Activation Code'),
(594, 'en', 'usr_compose_msg', 'Compose Message'),
(595, 'en', 'usr_inbox_title', 'Inbox'),
(596, 'en', 'usr_sent_title', 'Sent'),
(597, 'en', 'usr_to_title', 'To: (Enter Username)'),
(598, 'en', 'usr_or_select_frm_list', 'or select from contact list'),
(599, 'en', 'usr_attach_video', 'Attach Video'),
(600, 'en', 'user_attached_video', 'Attached Video'),
(601, 'en', 'usr_send_message', 'Send Message'),
(602, 'en', 'user_no_message', 'No Message'),
(603, 'en', 'user_delete_message_msg', 'Delete This Message'),
(604, 'en', 'user_forgot_message', 'Forgot password'),
(605, 'en', 'user_forgot_message_2', 'Dont Worry, recover it now'),
(606, 'en', 'user_pass_reset_msg', 'Password Reset'),
(607, 'en', 'user_pass_forgot_msg', 'if you have forgot your password, please enter you username and verification code in the box, and password reset instructions will be sent to your mail box.'),
(608, 'en', 'user_veri_code', 'Verification Code'),
(609, 'en', 'user_reocover_user', 'Recover Username'),
(610, 'en', 'user_user_forgot_msg', 'Forgot Username?'),
(611, 'en', 'user_recover', 'Recover'),
(612, 'en', 'user_reset', 'Reset'),
(613, 'en', 'user_inactive_msg', 'Your Account is Inactive Please Activate it , To Activate your account Please'),
(614, 'en', 'user_dashboard', 'Dash Board'),
(615, 'en', 'user_manage_prof_chnnl', 'Manage Profile &amp; Channel'),
(616, 'en', 'user_manage_friends', 'Manage Friends &amp; Contacts'),
(617, 'en', 'user_prof_channel', 'Profile/Channel'),
(618, 'en', 'user_message_box', 'Message Box'),
(619, 'en', 'user_new_messages', 'New Messages'),
(620, 'en', 'user_goto_inbox', 'Goto Inbox'),
(621, 'en', 'user_goto_sentbox', 'Goto Sent Box'),
(622, 'en', 'user_compose_new', 'Compose New Messages'),
(623, 'en', 'user_total_subs_users', 'Total Subscribed Users'),
(624, 'en', 'user_you_have', 'You Have'),
(625, 'en', 'user_fav_videos', 'Favourite Videos'),
(626, 'en', 'user_your_vids_watched', 'Your Videos Watched'),
(627, 'en', 'user_times', 'Times'),
(628, 'en', 'user_you_have_watched', 'You Have Watched'),
(629, 'en', 'user_channel_profiles', 'Channel &amp; Profile'),
(630, 'en', 'user_channel_views', 'Channel Views'),
(631, 'en', 'user_channel_comm', 'Channel Comments '),
(632, 'en', 'user_manage_prof', 'Manage Profile / Channel'),
(633, 'en', 'user_you_created', 'You Have Created'),
(634, 'en', 'user_you_joined', 'You Have Joined'),
(635, 'en', 'user_create_group', 'Create New Group'),
(636, 'en', 'user_manage_my_account', 'Manage My Account '),
(637, 'en', 'user_manage_my_videos', 'Manage My Videos'),
(638, 'en', 'user_manage_my_channel', 'Manage My Channel'),
(639, 'en', 'user_sent_box', 'My sent items'),
(640, 'en', 'user_manage_channel', 'Manage Channel'),
(641, 'en', 'user_manage_my_contacts', 'Manage My Contacts'),
(642, 'en', 'user_manage_contacts', 'Manage Contacts'),
(643, 'en', 'user_manage_favourites', 'Manage Favourite Videos'),
(644, 'en', 'user_mem_login', 'Members Login'),
(645, 'en', 'user_already_have', 'Please Login Here if You Already have an account of'),
(646, 'en', 'user_forgot_username', 'Forgot Username'),
(647, 'en', 'user_forgot_password', 'Forgot Password'),
(648, 'en', 'user_create_your', 'Create Your '),
(649, 'en', 'user_all_fields_req', 'All Fields Are Required'),
(650, 'en', 'user_valid_email_addr', 'Valid Email Address'),
(651, 'en', 'user_allowed_format', 'Letters A-Z or a-z , Numbers 0-9 and Underscores _'),
(652, 'en', 'user_confirm_pass', 'Confirm Password'),
(653, 'en', 'user_reg_msg_0', 'Register as '),
(654, 'en', 'user_reg_msg_1', 'member, its free and easy just fill out the form below'),
(655, 'en', 'user_date_of_birth', 'Date Of Birth'),
(656, 'en', 'user_enter_text_as_img', 'Enter Text As Seen In The Image'),
(657, 'en', 'user_refresh_img', 'Refresh Image'),
(658, 'en', 'user_i_agree_to_the', 'I Agree to the'),
(659, 'en', 'user_thanks_for_reg', 'Thank You For Registering on '),
(660, 'en', 'user_email_has_sent', 'An email has been sent to your inbox containing Your Account'),
(661, 'en', 'user_and_activation', '&amp; Activation'),
(662, 'en', 'user_details_you_now', 'Details. You may now do the following things on our network'),
(663, 'en', 'user_upload_share_vds', 'Upload, Share Videos'),
(664, 'en', 'user_make_friends', 'Make Friends'),
(665, 'en', 'user_send_messages', 'Send Messages'),
(666, 'en', 'user_grow_your_network', 'Grow Your Networks by Inviting more Friends'),
(667, 'en', 'user_rate_comment', 'Rate and Comment Videos'),
(668, 'en', 'user_make_customize', 'Make and Customize Your Channel'),
(669, 'en', 'user_to_upload_vid', 'To Upload Video, You Need to Activate your account first, activation details has been sent to your email account, it may take sometimes to reach your inbox'),
(670, 'en', 'user_click_to_login', 'Click here To Login To Your Account'),
(671, 'en', 'user_view_my_channel', 'View My Channel'),
(672, 'en', 'user_change_pass', 'Change Password'),
(673, 'en', 'user_email_settings', 'Email Settings'),
(674, 'en', 'user_profile_settings', 'Profile Settings'),
(675, 'en', 'user_usr_prof_chnl_edit', 'User Profile &amp; Channel Edit'),
(676, 'en', 'user_personal_info', 'Personal Information'),
(677, 'en', 'user_fname', 'First Name'),
(678, 'en', 'user_lname', 'Last Name'),
(679, 'en', 'user_gender', 'Gender'),
(680, 'en', 'user_relat_status', 'Relationship Status'),
(681, 'en', 'user_display_age', 'Display Age'),
(682, 'en', 'user_about_me', 'About Me'),
(683, 'en', 'user_website_url', 'Website Url'),
(684, 'en', 'user_eg_website', 'e.g www.cafepixie.com'),
(685, 'en', 'user_prof_info', 'Professional Information'),
(686, 'en', 'user_education', 'Education'),
(687, 'en', 'user_school_colleges', 'Schools / Colleges'),
(688, 'en', 'user_occupations', 'Occupations'),
(689, 'en', 'user_companies', 'Companies'),
(690, 'en', 'user_sperate_by_commas', 'seperate with commas'),
(691, 'en', 'user_interests_hobbies', 'Interests and Hobbies'),
(692, 'en', 'user_fav_movs_shows', 'Favorite movies &amp; shows'),
(693, 'en', 'user_fav_music', 'Favorite music'),
(694, 'en', 'user_fav_books', 'Favorite books'),
(695, 'en', 'user_user_avatar', 'User Avatar'),
(696, 'en', 'user_upload_avatar', 'Upload Avatar'),
(697, 'en', 'user_channel_info', 'Channel Info'),
(698, 'en', 'user_channel_title', 'Channel Title'),
(699, 'en', 'user_channel_description', 'Channel Description'),
(700, 'en', 'user_channel_permission', 'Channel Permissions'),
(701, 'en', 'user_allow_comments_msg', 'users can comments'),
(702, 'en', 'user_dallow_comments_msg', 'users cannot comments'),
(703, 'en', 'user_allow_rating', 'Allow Rating'),
(704, 'en', 'user_dallow_rating', 'Do Not Allow Rating'),
(705, 'en', 'user_allow_rating_msg1', 'users can rate'),
(706, 'en', 'user_dallow_rating_msg1', 'users cannot rate'),
(707, 'en', 'user_channel_feature_vid', 'Channel Featured Video'),
(708, 'en', 'user_select_vid_for_fr', 'Select Video To set as Featured'),
(709, 'en', 'user_chane_channel_bg', 'Change Channel Background'),
(710, 'en', 'user_remove_bg', 'Remove Background'),
(711, 'en', 'user_currently_you_d_have_pic', 'Currently You Don&#8217;t Have Background Picture'),
(712, 'en', 'user_change_email', 'Change Email'),
(713, 'en', 'user_email_address', 'Email Address'),
(714, 'en', 'user_new_email', 'New Email'),
(715, 'en', 'user_notify_me', 'Notify Me When User Sends Me A Message'),
(716, 'en', 'user_old_pass', 'Old Password'),
(717, 'en', 'user_new_pass', 'New Password'),
(718, 'en', 'user_c_new_pass', 'Confirm New Password'),
(719, 'en', 'user_doesnt_exist', 'User Doesn&#8217;t Exist'),
(720, 'en', 'user_do_not_have_contact', 'User Does Not Have Any Contact'),
(721, 'en', 'user_no_fav_video_exist', 'User Does Not Have Favourite Video'),
(722, 'en', 'user_have_no_vide', 'User has no videos'),
(723, 'en', 'user_s_channel', '%s&#8217;s Channel '),
(724, 'en', 'user_last_login', 'Last Login'),
(725, 'en', 'user_send_message', 'Send Message'),
(726, 'en', 'user_add_contact', 'Add Contact'),
(727, 'en', 'user_dob', 'Dob'),
(728, 'en', 'user_movies_shows', 'Movies &amp; Shows'),
(729, 'en', 'user_add_comment', 'Add Comment '),
(730, 'en', 'user_view_all_comments', 'View All Comments'),
(731, 'en', 'user_no_fr_video', 'User Has Not Selected Any Video To Set As Featured'),
(732, 'en', 'user_view_all_video_of', 'View All Videos of '),
(733, 'en', 'menu_home', 'Home'),
(734, 'en', 'menu_videos', 'Videos'),
(735, 'en', 'menu_upload', 'Upload'),
(736, 'en', 'menu_signup', 'SignUp'),
(737, 'en', 'menu_account', 'Account'),
(738, 'en', 'menu_groups', 'Groups'),
(739, 'en', 'menu_channels', 'Channels'),
(740, 'en', 'menu_community', 'Community'),
(741, 'en', 'menu_inbox', 'Inbox'),
(742, 'en', 'vdo_cat_err2', 'You cannot select more than %d categories'),
(743, 'en', 'user_subscribe_message', 'Hello %subscriber%\nYou Have Subscribed To %user% and therefore this message is sent to you automatically that %user% Has Uploaded New Video\n\n%website_title%'),
(744, 'en', 'user_subscribe_subject', '%user% has uploaded new video'),
(745, 'en', 'you_already_logged', 'You are already logged in'),
(746, 'en', 'you_not_logged_in', 'You are not logged in'),
(747, 'en', 'invalid_user', 'Invalid User'),
(748, 'en', 'vdo_cat_err3', 'Please select atleast 1 category'),
(749, 'en', 'embed_code_invalid_err', 'Invalid video embed code'),
(750, 'en', 'invalid_duration', 'Invalid duration'),
(751, 'en', 'vid_thumb_changed', 'Video default thumb has been changed'),
(752, 'en', 'vid_thumb_change_err', 'Video thumbnail was not found'),
(753, 'en', 'upload_vid_thumbs_msg', 'All video thumbs have been uploaded'),
(754, 'en', 'video_thumb_delete_msg', 'Video thumb has been deleted'),
(755, 'en', 'video_thumb_delete_err', 'Could not delete video thumb'),
(756, 'en', 'no_comment_del_perm', 'You dont have permission to delete this comment'),
(757, 'en', 'my_text_context', 'My test context'),
(758, 'en', 'user_contains_disallow_err', 'Username contains disallowed characters'),
(759, 'en', 'add_cat_erro', 'Category already exists'),
(760, 'en', 'add_cat_no_name_err', 'Please enter name for category'),
(761, 'en', 'cat_default_err', 'Default cannot be deleted, please choose other category as &#8220;default&#8221; and then delete this'),
(762, 'en', 'pic_upload_vali_err', 'Please upload valid JPG, GIF or PNG image'),
(763, 'en', 'cat_dir_make_err', 'Unable to make category thumb directory'),
(764, 'en', 'cat_set_default_ok', 'Category has been set as default'),
(765, 'en', 'vid_thumb_removed_msg', 'Video thumbs have been removed'),
(766, 'en', 'vid_files_removed_msg', 'Video files have been removed'),
(767, 'en', 'vid_log_delete_msg', 'Video log has been deleted'),
(768, 'en', 'vdo_multi_del_erro', 'Videos has have been deleted'),
(769, 'en', 'add_fav_message', 'This %s has been added to your favorites'),
(770, 'en', 'obj_not_exists', '%s does not exist'),
(771, 'en', 'already_fav_message', 'This %s is already added to your favorites'),
(772, 'en', 'obj_report_msg', 'this %s has been reported'),
(773, 'en', 'obj_report_err', 'You have already reported this %s'),
(774, 'en', 'user_no_exist_wid_username', '&#8216;%s&#8217; does not exist'),
(775, 'en', 'share_video_no_user_err', 'Please enter usernames or emails to send this %s'),
(776, 'en', 'uploaded', 'Uploaded'),
(777, 'en', 'today', 'Today'),
(778, 'en', 'yesterday', 'Yesterday'),
(779, 'en', 'thisweek', 'This Week'),
(780, 'en', 'lastweek', 'Last Week'),
(781, 'en', 'thismonth', 'This Month'),
(782, 'en', 'lastmonth', 'Last Month'),
(783, 'en', 'thisyear', 'This Year'),
(784, 'en', 'lastyear', 'Last Year'),
(785, 'en', 'favorites', 'Favorites'),
(786, 'en', 'alltime', 'All Time'),
(787, 'en', 'insufficient_privileges_loggin', 'You cannot access this page Click Here to Login or Register'),
(788, 'en', 'profile_title', 'Profile Title'),
(789, 'en', 'show_dob', 'Show Date of birth'),
(790, 'en', 'profile_tags', 'Profile Tags'),
(791, 'en', 'profile_desc', 'Profile description'),
(792, 'en', 'online_status', 'User status'),
(793, 'en', 'show_profile', 'Show profile'),
(794, 'en', 'allow_ratings', 'Allow ratings on profile'),
(795, 'en', 'postal_code', 'Postal code'),
(796, 'en', 'temp_file_load_err', 'Unable to load tempalte file &#8216;%s&#8217; in directory &#8216;%s&#8217;'),
(797, 'en', 'no_date_provided', 'No date provided'),
(798, 'en', 'second', 'second'),
(799, 'en', 'minute', 'minute'),
(800, 'en', 'bad_date', 'Never'),
(801, 'en', 'users_videos', '%s&#8217;s videos'),
(802, 'en', 'please_login_subscribe', 'Please login to subsribe %s'),
(803, 'en', 'users_subscribers', '%s&#8217;s subscribers'),
(804, 'en', 'user_no_subscribers', '%s has no subsribers'),
(805, 'en', 'user_subscriptions', '%s&#8217;s subscriptions'),
(806, 'en', 'user_no_subscriptions', '%s has no subscriptions'),
(807, 'en', 'usr_avatar_bg_update', 'User avatar and background have been updated'),
(808, 'en', 'user_email_confirm_email_err', 'Confirm email mismatched'),
(809, 'en', 'email_change_msg', 'Email has been changed successfullyrnrn'),
(810, 'en', 'no_edit_video', 'You cannot edit this video'),
(811, 'en', 'confirm_del_video', 'Are you sure you want to delete this video ?'),
(812, 'en', 'remove_fav_video_confirm', 'Are you sure you want to remove this video from your favorites ?'),
(813, 'en', 'fav_remove_msg', '%s has been removed from your favorites'),
(814, 'en', 'unknown_favorite', 'Unknown favorite %s'),
(815, 'en', 'vdo_multi_del_fav_msg', 'Videos have been removed from your favorites'),
(816, 'en', 'unknown_sender', 'Unknown Sender'),
(817, 'en', 'please_enter_message', 'Please enter something for message'),
(818, 'en', 'unknown_reciever', 'Unknown reciever'),
(819, 'en', 'no_pm_exist', 'Private message does not exist'),
(820, 'en', 'pm_sent_success', 'Private message has been sent successfully'),
(821, 'en', 'msg_delete_inbox', 'Message has been deleted from inbox'),
(822, 'en', 'msg_delete_outbox', 'Message has been deleted from your outbox'),
(823, 'en', 'private_messags_deleted', 'Private messages have been deleted'),
(824, 'en', 'ban_users', 'Ban users'),
(825, 'en', 'spe_users_by_comma', 'separate usernames by comma'),
(826, 'en', 'user_ban_msg', 'Users have been banned successfully'),
(827, 'en', 'no_user_ban_msg', 'No user is banned from your account!'),
(828, 'en', 'thnx_sharing_msg', 'Thanks for sharing this %s'),
(829, 'en', 'no_own_commen_rate', 'You cannot rate your own comment'),
(830, 'en', 'no_comment_exists', 'Comment does not exist'),
(831, 'en', 'thanks_rating_comment', 'Thanks for rating comment'),
(832, 'en', 'please_login_create_playlist', 'Please login to creat playlists'),
(833, 'en', 'play_list_with_this_name_arlready_exists', 'Playlist with name &#8216;%s&#8217; already exists'),
(834, 'en', 'please_enter_playlist_name', 'Please enter playlist name'),
(835, 'en', 'new_playlist_created', 'New playlist has been created'),
(836, 'en', 'playlist_not_exist', 'Playlist does not exist'),
(837, 'en', 'playlist_item_not_exist', 'Playlist item does not exist'),
(838, 'en', 'playlist_item_delete', 'Playlist item has been deleted'),
(839, 'en', 'play_list_updated', 'Playlist has been updated'),
(840, 'en', 'you_dont_hv_permission_del_playlist', 'You do not have permission to delete playlist'),
(841, 'en', 'playlist_delete_msg', 'Playlsit has been deleted'),
(842, 'en', 'playlist_name', 'Playlist name'),
(843, 'en', 'add_new_playlist', 'Add new playlist'),
(844, 'en', 'this_thing_added_playlist', 'This %s has been added to playlist'),
(845, 'en', 'this_already_exist_in_pl', 'This %s already exists in your playlist'),
(846, 'en', 'edit_playlist', 'Edit Playlist'),
(847, 'en', 'remove_playlist_item_confirm', 'Are you sure you want to remove this from your playlist'),
(848, 'en', 'remove_playlist_confirm', 'Are you sure you want to delete this playlist?'),
(849, 'en', 'avcode_incorrect', 'Activation code is incorrect'),
(850, 'en', 'group_join_login_err', 'Please login in order to join this group'),
(851, 'en', 'manage_playlist', 'Manage playlist'),
(852, 'en', 'my_notifications', 'My notifications'),
(853, 'en', 'users_contacts', '%s&#8217;s contacts'),
(854, 'en', 'type_flags_removed', '%s flags have been removed'),
(855, 'en', 'terms_of_serivce', 'Terms of services'),
(856, 'en', 'users', 'Users'),
(857, 'en', 'login_to_mark_as_spam', 'Please login to mark as spam'),
(858, 'en', 'no_own_commen_spam', 'You cannot mark your own comment as spam'),
(859, 'en', 'already_spammed_comment', 'You have already marked this comment as spam'),
(860, 'en', 'spam_comment_ok', 'Comment has been marked as spam');

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

DROP TABLE IF EXISTS `plugins`;
CREATE TABLE `plugins` (
  `plugin_id` int(255) NOT NULL AUTO_INCREMENT,
  `plugin_file` text NOT NULL,
  `plugin_folder` text NOT NULL,
  `plugin_version` float NOT NULL,
  `plugin_license_type` varchar(10) NOT NULL DEFAULT 'GPL',
  `plugin_license_key` varchar(5) NOT NULL,
  `plugin_license_code` text NOT NULL,
  `plugin_active` enum('yes','no') NOT NULL,
  PRIMARY KEY (`plugin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `plugins`
--

INSERT INTO `plugins` (`plugin_id`, `plugin_file`, `plugin_folder`, `plugin_version`, `plugin_license_type`, `plugin_license_key`, `plugin_license_code`, `plugin_active`) VALUES
(2, 'tester_plugin.php', '', 0, '', '', '', 'yes'),
(15, 'embed_video_mod.php', 'embed_video_mod', 0, '', '', '', 'yes'),
(14, 'editors_picks.php', 'editors_pick', 0, '', '', '', 'yes'),
(13, 'date_picker.php', 'date_picker', 0, '', '', '', 'yes'),
(12, 'cb_modules.php', 'cb_modules', 0, '', '', '', 'yes'),
(11, 'cb_bbcode.php', 'cb_bbcodes', 0, '', '', '', 'yes'),
(10, 'comment_censor.php', '', 0, '', '', '', 'yes'),
(17, 'signup_captcha.php', 'signup_captcha', 0, '', '', '', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `plugin_config`
--

DROP TABLE IF EXISTS `plugin_config`;
CREATE TABLE `plugin_config` (
  `plugin_config_id` int(223) NOT NULL AUTO_INCREMENT,
  `plugin_id_code` varchar(25) CHARACTER SET latin1 NOT NULL,
  `plugin_config_name` text CHARACTER SET latin1 NOT NULL,
  `plugin_config_value` text CHARACTER SET latin1 NOT NULL,
  `player_type` enum('built-in','plugin') CHARACTER SET latin1 NOT NULL DEFAULT 'built-in',
  `player_admin_file` text CHARACTER SET latin1 NOT NULL,
  `player_include_file` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`plugin_config_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `plugin_config`
--


-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_user` int(11) NOT NULL,
  `session_string` varchar(60) NOT NULL,
  `session_value` varchar(32) NOT NULL,
  `session_date` datetime NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
CREATE TABLE `stats` (
  `stat_id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `value` varchar(60) NOT NULL,
  PRIMARY KEY (`stat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `stats`
--


-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `subscription_id` int(225) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `subscribed_to` mediumtext NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subscription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `subscriptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `template`
--

DROP TABLE IF EXISTS `template`;
CREATE TABLE `template` (
  `template_id` int(20) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(25) NOT NULL,
  `template_dir` varchar(30) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `template`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userid` bigint(20) NOT NULL AUTO_INCREMENT,
  `category` int(20) NOT NULL,
  `featured_video` mediumtext NOT NULL,
  `username` text NOT NULL,
  `user_session_key` varchar(32) NOT NULL,
  `user_session_code` int(5) NOT NULL,
  `password` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(80) NOT NULL DEFAULT '',
  `usr_status` enum('Ok','ToActivate') NOT NULL DEFAULT 'ToActivate',
  `msg_notify` enum('yes','no') NOT NULL DEFAULT 'yes',
  `avatar` varchar(225) NOT NULL DEFAULT '',
  `avatar_url` text NOT NULL,
  `sex` enum('male','female') NOT NULL DEFAULT 'male',
  `dob` date NOT NULL DEFAULT '0000-00-00',
  `country` varchar(20) NOT NULL DEFAULT 'PK',
  `level` int(6) NOT NULL DEFAULT '2',
  `avcode` mediumtext NOT NULL,
  `doj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_logged` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `num_visits` bigint(20) NOT NULL DEFAULT '0',
  `session` varchar(32) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `signup_ip` varchar(15) NOT NULL DEFAULT '',
  `time_zone` tinyint(4) NOT NULL DEFAULT '0',
  `featured` enum('No','Yes') NOT NULL DEFAULT 'No',
  `featured_date` datetime NOT NULL,
  `profile_hits` bigint(20) DEFAULT '0',
  `total_watched` bigint(20) NOT NULL DEFAULT '0',
  `total_videos` bigint(20) NOT NULL,
  `total_comments` bigint(20) NOT NULL,
  `comments_count` bigint(20) NOT NULL,
  `ban_status` enum('yes','no') NOT NULL DEFAULT 'no',
  `upload` varchar(20) NOT NULL DEFAULT '1',
  `subscribers` varchar(25) NOT NULL DEFAULT '0',
  `background` mediumtext NOT NULL,
  `background_color` varchar(25) NOT NULL,
  `background_url` text NOT NULL,
  `background_repeat` enum('no-repeat','repeat','repeat-x','repeat-y') NOT NULL DEFAULT 'repeat',
  `background_attachement` enum('yes','no') NOT NULL DEFAULT 'no',
  `total_groups` bigint(20) NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rating` bigint(25) NOT NULL,
  `rated_by` text NOT NULL,
  `banned_users` text NOT NULL,
  `welcome_email_sent` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`userid`),
  KEY `ind_status_doj` (`doj`),
  KEY `ind_status_id` (`userid`),
  KEY `ind_hits_doj` (`profile_hits`,`doj`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `category`, `featured_video`, `username`, `user_session_key`, `user_session_code`, `password`, `email`, `usr_status`, `msg_notify`, `avatar`, `avatar_url`, `sex`, `dob`, `country`, `level`, `avcode`, `doj`, `last_logged`, `num_visits`, `session`, `ip`, `signup_ip`, `time_zone`, `featured`, `featured_date`, `profile_hits`, `total_watched`, `total_videos`, `total_comments`, `comments_count`, `ban_status`, `upload`, `subscribers`, `background`, `background_color`, `background_url`, `background_repeat`, `background_attachement`, `total_groups`, `last_active`, `rating`, `rated_by`, `banned_users`, `welcome_email_sent`) VALUES
(1, 2, '', 'admin', '777750fea4d3bd585bf47dc1873619fc', 10192, '38d8e594a1ddbd29fdba0de385d4fefa', 'admin@domain.tld', 'Ok', 'yes', '', '', 'male', '1989-10-14', 'PK', 1, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 'pub6e7fq5oj76vakuov2j03hm1', '', '', 0, 'No', '2009-12-03 15:14:20', 0, 0, 0, 0, 0, 'no', '0', '0', '', '', '', '', 'no', 0, '2010-01-17 15:16:49', 0, '0', '', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `user_categories`
--

DROP TABLE IF EXISTS `user_categories`;
CREATE TABLE `user_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_categories`
--

INSERT INTO `user_categories` (`category_id`, `category_name`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Basic User', '', '2009-12-03 12:18:15', '', 'yes'),
(2, 'Gurus', '', '2009-12-03 12:18:21', '', 'no'),
(3, 'Comedian', '', '2009-12-03 12:18:25', '', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `user_levels`
--

DROP TABLE IF EXISTS `user_levels`;
CREATE TABLE `user_levels` (
  `user_level_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_level_active` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'yes',
  `user_level_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `user_level_is_default` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`user_level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `user_levels`
--

INSERT INTO `user_levels` (`user_level_id`, `user_level_active`, `user_level_name`, `user_level_is_default`) VALUES
(4, 'yes', 'Guest', 'yes'),
(2, 'yes', 'Registered User', 'yes'),
(3, 'yes', 'Inactive User', 'yes'),
(1, 'yes', 'Administrator', 'yes'),
(5, 'yes', 'Global Moderator', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `user_levels_permissions`
--

DROP TABLE IF EXISTS `user_levels_permissions`;
CREATE TABLE `user_levels_permissions` (
  `user_level_permission_id` int(22) NOT NULL AUTO_INCREMENT,
  `user_level_id` int(22) NOT NULL,
  `admin_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `allow_video_upload` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_channel` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_group` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_videos` enum('yes','no') NOT NULL DEFAULT 'yes',
  `avatar_upload` enum('yes','no') NOT NULL DEFAULT 'yes',
  `video_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `member_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `ad_manager_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `manage_template_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `group_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `web_config_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `view_channels` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_groups` enum('yes','no') NOT NULL DEFAULT 'yes',
  `playlist_access` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_channel_bg` enum('yes','no') NOT NULL DEFAULT 'yes',
  `private_msg_access` enum('yes','no') NOT NULL DEFAULT 'yes',
  `edit_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `admin_del_access` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`user_level_permission_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `user_levels_permissions`
--

INSERT INTO `user_levels_permissions` (`user_level_permission_id`, `user_level_id`, `admin_access`, `allow_video_upload`, `view_video`, `view_channel`, `view_group`, `view_videos`, `avatar_upload`, `video_moderation`, `member_moderation`, `ad_manager_access`, `manage_template_access`, `group_moderation`, `web_config_access`, `view_channels`, `view_groups`, `playlist_access`, `allow_channel_bg`, `private_msg_access`, `edit_video`, `admin_del_access`) VALUES
(5, 5, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),
(2, 2, 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no'),
(3, 3, 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),
(1, 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),
(4, 4, 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE `user_permissions` (
  `permission_id` int(225) NOT NULL AUTO_INCREMENT,
  `permission_type` int(225) NOT NULL,
  `permission_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `permission_code` varchar(225) CHARACTER SET latin1 NOT NULL,
  `permission_desc` mediumtext CHARACTER SET latin1 NOT NULL,
  `permission_default` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_code` (`permission_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`permission_id`, `permission_type`, `permission_name`, `permission_code`, `permission_desc`, `permission_default`) VALUES
(12, 3, 'Admin Access', 'admin_access', 'User can access admin panel', 'no'),
(13, 1, 'View Video', 'view_video', 'User can view videos', 'yes'),
(11, 2, 'Allow Video Upload', 'allow_video_upload', 'Allow user to upload videos', 'yes'),
(14, 1, 'View Channel', 'view_channel', 'User Can View Channel', 'yes'),
(15, 1, 'View Group', 'view_group', 'User Can View Group', 'yes'),
(16, 1, 'View Videos Page', 'view_videos', 'User Can view videos page', 'yes'),
(17, 2, 'Allow Avatar Upload', 'avatar_upload', 'User can upload video', 'yes'),
(19, 3, 'Video Moderation', 'video_moderation', 'User Can Moderate Videos', 'no'),
(20, 3, 'Member Moderation', 'member_moderation', 'User Can Moderate Members', 'no'),
(21, 3, 'Advertisment Manager', 'ad_manager_access', 'User can change advertisment', 'no'),
(22, 3, 'Manage Templates', 'manage_template_access', 'User can manage website templates', 'no'),
(23, 3, 'Groups Moderation', 'group_moderation', 'User can moderate group', 'no'),
(24, 3, 'Website Configurations', 'web_config_access', 'User can change website settings', 'no'),
(25, 1, 'View channels', 'view_channels', 'User can channels', 'yes'),
(26, 1, 'View Groups', 'view_groups', 'User can view groups', 'yes'),
(28, 4, 'Playlist Access', 'playlist_access', 'User can access playlists', 'yes'),
(29, 2, 'Allow Channel Background', 'allow_channel_bg', 'Allow user to change channel background', 'yes'),
(30, 4, 'Private Messages', 'private_msg_access', 'User can use private messaging system', 'yes'),
(31, 4, 'Edit Video', 'edit_video', 'User can edit video', 'yes'),
(32, 3, 'Admin Delete Access', 'admin_del_access', 'User can delete comments if has admin access', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `user_permission_types`
--

DROP TABLE IF EXISTS `user_permission_types`;
CREATE TABLE `user_permission_types` (
  `user_permission_type_id` int(225) NOT NULL AUTO_INCREMENT,
  `user_permission_type_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `user_permission_type_desc` mediumtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`user_permission_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user_permission_types`
--

INSERT INTO `user_permission_types` (`user_permission_type_id`, `user_permission_type_name`, `user_permission_type_desc`) VALUES
(1, 'Viewing Permission', ''),
(2, 'Uploading Permission', ''),
(3, 'Administrator Permission', ''),
(4, 'General Permission', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE `user_profile` (
  `user_profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `profile_title` mediumtext NOT NULL,
  `profile_desc` mediumtext NOT NULL,
  `featured_video` mediumtext NOT NULL,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `avatar` varchar(225) NOT NULL DEFAULT 'no_avatar.jpg',
  `show_dob` enum('no','yes') DEFAULT 'no',
  `postal_code` varchar(20) NOT NULL DEFAULT '',
  `time_zone` tinyint(4) NOT NULL DEFAULT '0',
  `profile_tags` mediumtext,
  `web_url` varchar(200) NOT NULL DEFAULT '',
  `hometown` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `online_status` enum('online','offline','custom') NOT NULL DEFAULT 'online',
  `show_profile` enum('all','members','friends') NOT NULL DEFAULT 'all',
  `allow_comments` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `allow_ratings` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `content_filter` enum('Nothing','On','Off') NOT NULL DEFAULT 'Nothing',
  `icon_id` bigint(20) NOT NULL DEFAULT '0',
  `browse_criteria` mediumtext,
  `about_me` mediumtext NOT NULL,
  `education` varchar(3) DEFAULT NULL,
  `schools` mediumtext NOT NULL,
  `occupation` mediumtext NOT NULL,
  `companies` mediumtext NOT NULL,
  `relation_status` varchar(15) DEFAULT NULL,
  `hobbies` mediumtext NOT NULL,
  `fav_movies` mediumtext NOT NULL,
  `fav_music` mediumtext NOT NULL,
  `fav_books` mediumtext NOT NULL,
  `background` mediumtext NOT NULL,
  `profile_video` int(255) NOT NULL,
  PRIMARY KEY (`user_profile_id`),
  KEY `ind_status_id` (`userid`),
  FULLTEXT KEY `profile_tags` (`profile_tags`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`user_profile_id`, `userid`, `profile_title`, `profile_desc`, `featured_video`, `first_name`, `last_name`, `avatar`, `show_dob`, `postal_code`, `time_zone`, `profile_tags`, `web_url`, `hometown`, `city`, `online_status`, `show_profile`, `allow_comments`, `allow_ratings`, `content_filter`, `icon_id`, `browse_criteria`, `about_me`, `education`, `schools`, `occupation`, `companies`, `relation_status`, `hobbies`, `fav_movies`, `fav_music`, `fav_books`, `background`, `profile_video`) VALUES
(1, 1, '', '', '', '', '', 'no_avatar.jpg', 'yes', '0000', 0, '', '', '', '', '', 'all', 'Yes', 'Yes', 'Nothing', 0, NULL, '', 'no ', '', '', '', 'Single', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `validation_re`
--

DROP TABLE IF EXISTS `validation_re`;
CREATE TABLE `validation_re` (
  `re_id` int(25) NOT NULL AUTO_INCREMENT,
  `re_name` varchar(60) NOT NULL,
  `re_code` varchar(60) NOT NULL,
  `re_syntax` text NOT NULL,
  PRIMARY KEY (`re_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `validation_re`
--

INSERT INTO `validation_re` (`re_id`, `re_name`, `re_code`, `re_syntax`) VALUES
(1, 'Username', 'username', '^^[a-zA-Z0-9_]+$'),
(2, 'Email', 'email', '^[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,10})$'),
(3, 'Field Text', 'field_text', '^^[_a-z0-9-]+$');

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
CREATE TABLE `video` (
  `videoid` bigint(20) NOT NULL AUTO_INCREMENT,
  `videokey` mediumtext NOT NULL,
  `username` text NOT NULL,
  `userid` int(11) NOT NULL,
  `title` mediumtext NOT NULL,
  `flv` mediumtext NOT NULL,
  `file_name` varchar(32) NOT NULL,
  `description` mediumtext NOT NULL,
  `tags` mediumtext NOT NULL,
  `category` varchar(20) NOT NULL DEFAULT '0',
  `broadcast` varchar(10) NOT NULL DEFAULT '',
  `location` mediumtext,
  `datecreated` date DEFAULT NULL,
  `country` mediumtext,
  `allow_embedding` char(3) NOT NULL DEFAULT '',
  `rating` int(15) NOT NULL DEFAULT '0',
  `rated_by` varchar(20) NOT NULL DEFAULT '0',
  `voter_ids` mediumtext NOT NULL,
  `allow_comments` char(3) NOT NULL DEFAULT '',
  `comment_voting` char(3) NOT NULL DEFAULT '',
  `comments_count` int(15) NOT NULL DEFAULT '0',
  `featured` char(3) NOT NULL DEFAULT 'no',
  `featured_date` datetime NOT NULL,
  `featured_description` mediumtext NOT NULL,
  `allow_rating` char(3) NOT NULL DEFAULT '',
  `active` char(3) NOT NULL DEFAULT '0',
  `favourite_count` varchar(15) NOT NULL DEFAULT '0',
  `playlist_count` varchar(15) NOT NULL DEFAULT '0',
  `views` bigint(22) NOT NULL DEFAULT '0',
  `last_viewed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `flagged` varchar(3) NOT NULL DEFAULT 'no',
  `duration` varchar(20) NOT NULL DEFAULT '00',
  `status` enum('Successful','Processing','Failed') NOT NULL DEFAULT 'Processing',
  `flv_file_url` text,
  `default_thumb` int(3) NOT NULL DEFAULT '1',
  `uploader_ip` varchar(20) NOT NULL,
  `embed_code` text NOT NULL,
  PRIMARY KEY (`videoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `video`
--


-- --------------------------------------------------------

--
-- Table structure for table `video_categories`
--

DROP TABLE IF EXISTS `video_categories`;
CREATE TABLE `video_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `video_categories`
--

INSERT INTO `video_categories` (`category_id`, `category_name`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Uncategorized', 'all uncategorized videos', '', '', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `video_favourites`
--

DROP TABLE IF EXISTS `video_favourites`;
CREATE TABLE `video_favourites` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `videoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fav_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `video_favourites`
--


-- --------------------------------------------------------

--
-- Table structure for table `video_files`
--

DROP TABLE IF EXISTS `video_files`;
CREATE TABLE `video_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL,
  `file_conversion_log` text CHARACTER SET latin1 NOT NULL,
  `encoder` char(16) CHARACTER SET latin1 NOT NULL,
  `command_used` text CHARACTER SET latin1 NOT NULL,
  `src_path` text CHARACTER SET latin1 NOT NULL,
  `src_name` char(64) CHARACTER SET latin1 NOT NULL,
  `src_ext` char(8) CHARACTER SET latin1 NOT NULL,
  `src_format` char(32) CHARACTER SET latin1 NOT NULL,
  `src_duration` char(10) CHARACTER SET latin1 NOT NULL,
  `src_size` char(10) CHARACTER SET latin1 NOT NULL,
  `src_bitrate` char(6) CHARACTER SET latin1 NOT NULL,
  `src_video_width` char(5) CHARACTER SET latin1 NOT NULL,
  `src_video_height` char(5) CHARACTER SET latin1 NOT NULL,
  `src_video_wh_ratio` char(10) CHARACTER SET latin1 NOT NULL,
  `src_video_codec` char(16) CHARACTER SET latin1 NOT NULL,
  `src_video_rate` char(10) CHARACTER SET latin1 NOT NULL,
  `src_video_bitrate` char(10) CHARACTER SET latin1 NOT NULL,
  `src_video_color` char(16) CHARACTER SET latin1 NOT NULL,
  `src_audio_codec` char(16) CHARACTER SET latin1 NOT NULL,
  `src_audio_bitrate` char(10) CHARACTER SET latin1 NOT NULL,
  `src_audio_rate` char(10) CHARACTER SET latin1 NOT NULL,
  `src_audio_channels` char(16) CHARACTER SET latin1 NOT NULL,
  `output_path` text CHARACTER SET latin1 NOT NULL,
  `output_format` char(32) CHARACTER SET latin1 NOT NULL,
  `output_duration` char(10) CHARACTER SET latin1 NOT NULL,
  `output_size` char(10) CHARACTER SET latin1 NOT NULL,
  `output_bitrate` char(6) CHARACTER SET latin1 NOT NULL,
  `output_video_width` char(5) CHARACTER SET latin1 NOT NULL,
  `output_video_height` char(5) CHARACTER SET latin1 NOT NULL,
  `output_video_wh_ratio` char(10) CHARACTER SET latin1 NOT NULL,
  `output_video_codec` char(16) CHARACTER SET latin1 NOT NULL,
  `output_video_rate` char(10) CHARACTER SET latin1 NOT NULL,
  `output_video_bitrate` char(10) CHARACTER SET latin1 NOT NULL,
  `output_video_color` char(16) CHARACTER SET latin1 NOT NULL,
  `output_audio_codec` char(16) CHARACTER SET latin1 NOT NULL,
  `output_audio_bitrate` char(10) CHARACTER SET latin1 NOT NULL,
  `output_audio_rate` char(10) CHARACTER SET latin1 NOT NULL,
  `output_audio_channels` char(16) CHARACTER SET latin1 NOT NULL,
  `hd` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'no',
  `hq` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `src_bitrate` (`src_bitrate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `video_files`
--