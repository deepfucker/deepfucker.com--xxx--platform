-- Language Vars
INSERT INTO `{tbl_prefix}phrases` (`id`, `lang_iso`, `varname`, `text`) VALUES
(NULL, 'en', 'pending_requests', 'Pending requests'),
(NULL, 'en', 'friend_add_himself_error', 'You cannot add yourself as a friend');

-- New Configs

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'allow_username_spaces', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'use_playlist', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'comments_captcha', 'all');

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'player_logo_file', 'logo.png');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'logo_placement', 'br');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'buffer_time', '3');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'youtube_enabled', 'yes');

-- Contacts Table Alteration

ALTER TABLE `{tbl_prefix}contacts` ADD `request_type` ENUM( 'in', 'out' ) NOT NULL AFTER `contact_group_id` ;

-- Phrases Update
UPDATE `{table_prefix}phrases` SET text='Sorry , User Doesn`t Exist' WHERE text='Sorry , User Doesn’t Exist' ;
UPDATE `{table_prefix}phrases` SET text='Please Don`t Try To Cheat' WHERE text='Please Don’t Try To Cheat' ;
UPDATE `{table_prefix}phrases` SET text='Please Don`t Try To Cross Your Limits' WHERE text='Please Don’t Try To Cross Your Limits' ;
UPDATE `{table_prefix}phrases` SET text='Message Doesn`t Exist' WHERE text='Message Doesn’t Exist' ;
UPDATE `{table_prefix}phrases` SET text='Sorry, Video Doesn`t Exist' WHERE text='Sorry, Video Doesn’t Exist' ;
UPDATE `{table_prefix}phrases` SET text='User doesn`t have any videos' WHERE text='User doesn’t have any videos' ;
UPDATE `{table_prefix}phrases` SET text='You don`t have sufficient permissions' WHERE text='You don’t have sufficient permissions' ;
UPDATE `{table_prefix}phrases` SET text='Please enter your username and activation code in order to activate your account, 

please check your inbox for the Activation code, if you didn`t get one, please request it by filling the next form' WHERE text='Please enter your username and activation code in order to activate your account, 

please check your inbox for the Activation code, if you didn’t get one, please request it by filling the next form' ;
UPDATE `{table_prefix}phrases` SET text='register_as_our_website_member' WHERE text='register_as_our_website_member' ;
UPDATE `{table_prefix}phrases` SET text='Register as a member, it`s free and easy just ' WHERE text='Register as a member, it’s free and easy just ' ;
UPDATE `{table_prefix}phrases` SET text='email_wont_display' WHERE text='email_wont_display' ;
UPDATE `{table_prefix}phrases` SET text='Email (Wont` display)' WHERE text='Email (Wont’ display)' ;

-- UPDATING DATE-FORMAT
UPDATE `{table_prefix}config` SET date_format='Y-m-d' WHERE date_format='m-d-Y';