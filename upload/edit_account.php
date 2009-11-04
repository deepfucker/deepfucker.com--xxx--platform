<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.											|
 | @ Author	   : ArslanHassan																		|
 | @ Software  : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';
$userquery->logincheck();

//Updating Profile
if(isset($_POST['update_profile']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->update_user($array);
}

//Updating Avatar
if(isset($_POST['update_avatar_bg']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->update_user_avatar_bg($array);
}

//Changing Email
if(isset($_POST['change_email']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->change_email($array);
}

//Changing User Password
if(isset($_POST['change_password']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->change_password($array);
}

//Banning Users
if(isset($_POST['ban_users']))
{
	$userquery->ban_users($_POST['users']);
}

$mode = $_GET['mode'];

switch($mode)
{
	case 'profile_settings':
	default:
	{
		assign('mode','profile_settings');
	}
	break;
	
	case 'avatar_bg':
	{
		assign('mode','avatar_bg');
	}
	break;
	
	case 'change_email':
	{
		assign('mode','change_email');
	}
	break;
	
	case 'change_password':
	{
		assign('mode','change_password');
	}
	break;
	
	case 'ban_users':
	{
		assign('mode','ban_users');
	}
	break;
}

$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
assign('p',$userquery->get_user_profile($udetails['userid']));

template_files('edit_account.html');
display_it();
?>