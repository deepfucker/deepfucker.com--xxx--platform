<?php
/* 
**************************
* @ Author : Arslan Hassan
* @ Software : ClipBucket
* @ Since : 2007
* @ Modified : 06-08-2009
* @ license : CBLA
**************************
-- Do not use it for commercial use
Notice : Maintain this section
*/
 
define('NO_AVATAR','no_avatar.png'); //if there is no avatar or profile pic, this file will be used
define('AVATAR_SIZE',250);
define('AVATAR_SMALL_SIZE',40);
define('BG_SIZE',1200);

class userquery extends CBCategory{
	
	var $userid = '';
	var $username = '';
	var $level = '';
	var $permissions = '';
	var $access_type_list = array(); //Access list
	var $usr_levels = array();
	var $signup_plugins = array(); //Signup Plugins
	var $custom_signup_fields = array();
	var $delete_user_functions = array();
	var $user_manager_functions = array();
	var $user_exist = '';
	
	var $user_sessions = array();
	
	var $dbtbl = array(
					   'user_permission_type'	=> 'user_permission_types',
					   'user_permissions'		=> 'user_permissions',
					   'user_level_permission'	=> 'user_levels_permissions',
					   'user_profile'			=> 'user_profile',
					   'users'					=> 'users',
					   'action_log'				=> 'action_log',
					   'subtbl'					=> 'subscriptions',
					   'contacts'				=> 'contacts',
					   );
	
	var $udetails = array();
	
	function userquery()
	{
		$this->cat_tbl = 'user_categories';
	}
	
	
	function init()
	{
		global $sess;

		$this->userid = $sess->get('userid');
		$this->username = $sess->get('username');
		$this->level = $sess->get('level');
		
		//Setting Access
		//Get list Of permission
		//$perms = $this->get_permissions();
		//foreach($perms as $perm)
		//{
		//	$this->add_access_type($perm['permission_code'],$perm['permission_name']);
		//}
		
		/*$this->add_access_type('admin_access','Admin Access');
		$this->add_access_type('upload_access','Upload Access');
		$this->add_access_type('channel_access','Channel Access');
		$this->add_access_type('mod_access','Moderator Access');*/
		
		//Fetching List Of User Levels
		$levels = $this->get_levels();
		foreach($levels as $level)
		{
			$this->usr_levels[$level['user_level_id']]=$level["user_level_name"];
		}
		
		if(user_id())
		{
			$this->udetails = $this->get_user_details(userid());
			$this->permission = $this->get_user_level(userid());
			
			
			//exit();
			
			if($sess->get("dummy_username")=="")
				$this->UpdateLastActive(userid());
		}else
			$this->permission = $this->get_user_level(4,TRUE);
			
		
		
		//Adding Actions such Report, share,fav etc
		$this->action = new cbactions();
		$this->action->type = 'u';
		$this->action->name = 'user';
		$this->action->obj_class = 'userquery';
		$this->action->check_func = 'user_exists';
		$this->action->type_tbl = $this->dbtbl['users'];
		$this->action->type_id_field = 'userid';
	}
	
	/**
	 * Function used to create user session key
	 */
	function create_session_key($session,$pass)
	{
		$newkey = $session.$pass;
		$newkey = md5($newkey);
		return $newkey;
	}
	
	/**
	 * Function used to create user session code
	 * just for session authentication incase user wants to login again
	 */
	function create_session_code()
	{
		$code = rand(10000,99999);
		return $code;
	}
	
	/**
	 * Neat and clean function to login user
	 * this function was made for v2.x with User Level System
	 * param VARCHAR $username
	 * param TEXT $password
	 */
	function login_user($username,$password)
	{
		global $LANG,$sess,$cblog,$db;
		//Now checking if user exists or not
		$pass = pass_code($password);
		$udetails = $this->get_user_with_pass($username,$pass);
		
		//Inerting Access Log
		$log_array = array('username'=>$username);
		
		//First we will check weather user is already logged in or not
		if($this->login_check())
			$msg[] = e(lang('you_already_logged'));
		elseif(!$this->user_exists($username))
			$msg[] = e(lang('user_doesnt_exist'));
		elseif(!$udetails)
			$msg[] = e(lang('usr_login_err'));
		elseif(strtolower($udetails['usr_status']) != 'ok')
			$msg[] = e(lang('user_inactive_msg'));
		elseif($udetails['ban_status'] == 'yes')
			$msg[] = e(lang('usr_ban_err'));
		else
		{
			
			$log_array['userid'] = $userid  = $udetails['userid'];
			$log_array['useremail'] = $udetails['email'];
			$log_array['success'] = 1;
			
			$log_array['level'] = $level  = $udetails['level'];
			
			//Adding Sessing In Database 
			//$sess->add_session($userid,'logged_in');
			
			$sess->set('username',$username);
			$sess->set('level',$level);
			$sess->set('userid',$userid);
			
			//Starting special sessions for security
			$sess->set('user_session_key',$udetails['user_session_key']);
			$sess->set('user_session_code',$udetails['user_session_code']);
			
			//Setting Vars
			$this->userid = $sess->get('userid');
			$this->username = $sess->get('username');
			$this->level = $sess->get('level');
			
			//Updating User last login , num of visist and ip
			$db->update(tbl('users'),
						array(
							  'num_visits','last_logged','ip'
							  ),
						array(
							  '|f|num_visits+1',NOW(),$_SERVER['REMOTE_ADDR']
							  ),
						"userid='".$userid."'"
						);
			$this->init();
			//Logging Actiong
			$cblog->insert('login',$log_array);
			
			return true;
		}
		
		//Error Loging
		if(!empty($msg))
		{
			//Loggin Action
			$log_array['success'] = no;
			$log_array['details'] = $msg[0];
			$cblog->insert('login',$log_array);
		}
	}
	
	/**
	 * Function used to check weather user is login or not
	 * it will also check weather user has access or not
	 * @param VARCHAR acess type it can be admin_access, upload_acess etc
	 * you can either set it as level id
	 */
	function login_check($access=NULL,$check_only=FALSE)
	{
		global $LANG,$Cbucket,$sess;
		
		
		//First check weather userid is here or not
		if(!userid())
		{
			if(!$check_only)
			e(lang('you_not_logged_in'));
			return false;
		}
		elseif(!$this->session_auth(userid()))
		{
			
			if(!$check_only)
			e(lang('usr_invalid_session_err'));
			return false;
		}
		//Now Check if logged in user exists or not
		elseif(!$this->user_exists(userid(),TRUE))
		{
			if(!$check_only)
			e(lang('invalid_user'));
			return false;
		}
		//Now Check logged in user is banned or not
		elseif($this->is_banned(userid())=='yes')
		{
			if(!$check_only)
			e(lang('usr_ban_err'));
			return false;
		}
		
		//Now user have passed all the stages, now checking if user has level access or not
		elseif($access)
		{	
			//$access_details = $this->get_user_level(userid());
			$access_details = $this->permission;
			
			if(is_numeric($access))
			{
				if($access_details['level_id'] == $access)
				{
					return true;
				}else{
					
					if(!$check_only)
					e(lang('insufficient_privileges'));
					$Cbucket->show_page(false);
					return false;
				}
			}else
			{
				if($access_details[$access] == 'yes')
				{
					return true;
				}
				else
				{
					if(!$check_only)
					{
						e(lang('insufficient_privileges'));
						$Cbucket->show_page(false);
					}
					return false;
				}
			}
		}
		else
		{
			return true;
		}
	}
	
	/**
	 * This function was used to check
	 * user is logged in or not -- for v1.7.x and old
	 * it has been replaced by login_check in v2
	 * this function is sitll in use so
	 * we are just replace the lil code of it
	 */
	function logincheck($access=NULL,$redirect=TRUE)
	{
		
		if(!$this->login_check($access))
		{
			if($redirect==TRUE)
				redirect_to(BASEURL.signup_link);
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/** 
	 * Function used to authenticate user session
	 */
	function session_auth($uid)
	{
		global $sess;
		
		return true;
		/*if($sess->get('user_session_key') == $ufields['user_session_key'] 
			&& $sess->get('user_session_code') == $ufields['user_session_code'])*/
		if($this->user_sessions['key']=='')
		{
			$ufields = $this->get_user_fields($uid,'user_session_key,user_session_code');
			//echo test;
			$this->user_sessions['key'] =  $ufields['user_session_key'];
			$this->user_sessions['code'] =  $ufields['user_session_code'];
		}
		
		if($this->user_sessions['key']==$sess->get('user_session_key')
			&& $this->user_sessions['code']==$sess->get('user_session_code')
			  ||( has_access("admin_access") && $sess->get("dummy_username")!=""))
		
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to get user details using username and password
	 */
	function get_user_with_pass($username,$pass)
	{
		global $db;
		$results = $db->select(tbl("users"),
							   "userid,email,level,usr_status,user_session_key,user_session_code",
							   "(username='$username' OR userid='$username') AND password='$pass'");
		if($db->num_rows > 0)
		{
			return $results[0];
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to check weather user is banned or not
	 */
	function is_banned($uid)
	{
		global $db;
		//echo $this->udetails['ban_status'];
		if(empty($this->udetails['ban_status']) && userid())
			$this->udetails['ban_status'] = $this->get_user_field($uid,'ban_status');
		return $this->udetails['ban_status'];
	}
	
	function admin_check()
	{
		return $this->login_check('admin_access');
	}

	/**
	 * Function used to check user is admin or not
	 * @param BOOLEAN if true, after checcking user will be redirected to login page if needed
	 */
	function admin_login_check($check_only=false)
	{
		if(!$this->login_check('admin_access'))
		{
			if($check_only==FALSE)
				redirect_to('login.php');
			return false;
		}else{
			return true;
		}
	}
		
	//This Function Is Used to Logout
	function logout($page='login.php')
	{
		global $sess;

		$sess->un_set('username');
		$sess->un_set('level');
		$sess->un_set('userid');
		$sess->un_set('user_session_key');
		$sess->un_set('user_session_code');
		//$sess->remove_session(userid());
	}
	
 
 
	/**
	 * Function used to delete user
	 */
	function delete_user($uid)
	{
		global $db;
		
		if($this->user_exists($uid))
		{
			
			$udetails = $this->get_user_details($uid);

			if(userid()!=$uid&&has_access('admin_access',true)&&$uid!=1)
			{
				//list of functions to perform while deleting a video
				$del_user_funcs = $this->delete_user_functions;
				if(is_array($del_user_funcs))
				{
					foreach($del_user_funcs as $func)
					{
						if(function_exists($func))
						{
							$func($udetails);
						}
					}
				}
				
				//Removing Subsriptions and subscribers
				$this->remove_user_subscriptions($uid);
				$this->remove_user_subscribers($uid);
				
				//Changing User Videos To Anonymous
				$db->execute("UPDATE ".tbl("video")." SET userid='".$this->get_anonymous_user()."' WHERE userid='".$uid."'");
				//Changing User Group To Anonymous
				$db->execute("UPDATE ".tbl("groups")." SET userid='".$this->get_anonymous_user()."' WHERE userid='".$uid."'");
				//Deleting User Contacts
				$this->remove_contacts($uid);
				
				//Deleting User PMS
				$this->remove_user_pms($uid);
				//Changing From Messages to Anonymous
				$db->execute("UPDATE ".tbl("messages")." SET message_from='".$this->get_anonymous_user()."' WHERE message_from='".$uid."'");
				//Finally Removing Database entry of user
				$db->execute("DELETE FROM ".tbl("users")." WHERE userid='$uid'");
				$db->execute("DELETE FROM ".tbl("user_profile")." WHERE userid='$uid'");
				
				e(lang("usr_del_msg"),"m");
			}else{
				e(lang("you_cant_delete_this_user"));
			}
		}else{
			e(lang("user_doesnt_exist"));
		}
	}
	
	/**
	 * Remove all user subscriptions
	 */
	function remove_user_subscriptions($uid)
	{
		global $db;
		if(!$this->user_exists($uid))
			e(lang("user_doesnt_exist"));
		elseif(!has_access('admin_access'))
			e(lang("you_dont_hv_perms"));
		else
		{
			$db->execute("DELETE FROM ".tbl($this->dbtbl['subtbl'])." WHERE userid='$uid'");
			e(lang("user_subs_hv_been_removed"),"m");
		}
	}
	
	/**
	 * Remove all user subscribers
	 */
	function remove_user_subscribers($uid)
	{
		global $db;
		if(!$this->user_exists($uid))
			e(lang("user_doesnt_exist"));
		elseif(!has_access('admin_access'))
			e(lang("you_dont_hv_perms"));
		else
		{
			$db->execute("DELETE FROM ".tbl($this->dbtbl['subtbl'])." WHERE subscribed_to='$uid'");
			e(lang("user_subsers_hv_removed"),"m");
		}
	}
	
	
	//Delete User
	function DeleteUser($id){
		return $this->delete_user($id);
	}
		
	//Check User Exists or Not
	function Check_User_Exists($id,$global=false){
		global $db;
		
		if($global)
		{
			if(empty($this->user_exist))
			{
				$result = $db->count(tbl($this->dbtbl['users']),"userid"," userid='".$id."' OR username='".$id."'");
				if($result>0)
				{
					$this->user_exist = 'yes';
				}else{
					$this->user_exist = 'no';
				}	
			}
			
			if($this->user_exist=='yes')
				return true;
			else
				return false;
		}else
		{
			$result = $db->count(tbl($this->dbtbl['users']),"userid"," userid='".$id."' OR username='".$id."'");
			if($result>0)
			{
				return true;
			}else{
				return false;
			}	
		}
		
	}
	
	function user_exists($username,$global=false)
	{
		return $this->Check_User_Exists($username,$global);
	}
	
	/**
	 * Function used to get user details using userid
	 */
	function get_user_details($id=NULL)
	{
		global $db;
		/*if(!$id)
			$id = userid();*/
			
		$results = $db->select(tbl('users'),'*'," userid='$id' OR username='".$id."' OR email='".$id."'");
		return $results[0];		
	}function GetUserData($id=NULL){ return $this->get_user_details($id); }
	

	

	//Function Used To Activate User
	function activate_user_with_avcode($user,$avcode)
	{
		global $eh;
		$data = $this->get_user_details($user);
		if(!$data  || !$user)
			e(lang("usr_exist_err"));
		elseif($udetails['usr_status']=='Ok')
			e(lang('usr_activation_err'));
		elseif($udetails['ban_status']=='yes')
			e(lang('ban_status'));
		elseif($data['avcode'] !=$avcode)
			e(lang('avcode_incorrect'));
		else
		{
			$this->action('activate',$data['userid']);
			$eh->flush();
			e(lang("usr_activation_msg"),"m");
			
			if($data['welcome_email_sent']=='no')
				$this->send_welcome_email($data,TRUE);
		}
	}
	
	
	/**
	 * Function used to send activation code
	 * to user
	 * @param : $usenrma,$email or $userid
	 */
	function send_activation_code($email)
	{
		global $db,$cbemail;
		$udetails = $this->get_user_details($email);
		
		if(!$udetails || !$email)
			e(lang("usr_exist_err"));
		elseif($udetails['usr_status']=='Ok')
			e(lang('usr_activation_err'));
		elseif($udetails['ban_status']=='yes')
			e(lang('ban_status'));
		else
		{
			$tpl = $cbemail->get_template('avcode_request_template');
			$more_var = array
			('{username}'	=> $udetails['username'],
			 '{email}'		=> $udetails['email'],
			 '{avcode}'		=> $udetails['avcode']
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			//Now Finally Sending Email
			cbmail(array('to'=>$udetails['email'],'from'=>SUPPORT_EMAIL,'subject'=>$subj,'content'=>$msg));
			e(lang('usr_activation_em_msg'),"m");
		}
	}
	function SendActivation($email)
	{
		return $this->send_activation_code($email);
	}
	
	/**
	 * Function used to send welcome email
	 */
	function send_welcome_email($user,$update_email_status=FALSE)
	{
		global $db,$cbemail;
		
		if(!is_array($user))
			$udetails = $this->get_user_details($user);
		else
			$udetails = $user;
		
		if(!$udetails)
			e(lang("usr_exist_err"));
		else
		{
			$tpl = $cbemail->get_template('welcome_message_template');
			$more_var = array
			('{username}'	=> $udetails['username'],
			 '{email}'		=> $udetails['email'],
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			//Now Finally Sending Email
			cbmail(array('to'=>$udetails['email'],'from'=>WELCOME_EMAIL,'subject'=>$subj,'content'=>$msg));
			
			if($update_email_status)
				$db->update(tbl($this->dbtbl['users']),array('welcome_email_sent'),array("yes")," userid='".$udetails['userid']."' ");
		}
	}
	
	
	/**
	 * Function used to change user password
	 */
	function ChangeUserPassword($array)
	{
		global $db;
		
		$old_pass 	= $array['old_pass'];
		$new_pass 	= $array['new_pass'];
		$c_new_pass	= $array['c_new_pass'];
		
		$uid = $array['userid'];
		
		if(!$this->get_user_with_pass($uid,pass_code($old_pass)))
			e(lang('usr_pass_err'));
		elseif(empty($new_pass))
			e(lang('usr_pass_err2'));
		elseif($new_pass != $c_new_pass)
			e(lang('usr_cpass_err1'));
		else
		{
			$db->update(tbl($this->dbtbl['users']),array('password'),array(pass_code($array['new_pass']))," userid='".$uid."'");
			e(lang("usr_pass_email_msg"),"m");

		}
		
		return $msg;
	}
	function change_user_pass($array){ return $this->ChangeUserPassword($array); }
	function change_password($array){ return $this->ChangeUserPassword($array); }
	
	/**
	 * Function used to add contact
	 */
	function add_contact($uid,$fid)
	{
		global $cbemail,$db;
		
		$friend = $this->get_user_details($fid);
		$sender = $this->get_user_details($uid);
		
		if(!$friend)
			e(lang('usr_exist_err'));
		elseif($this->is_requested_friend($uid,$fid))
			e(lang("you_already_sent_frend_request"));
		elseif($this->is_requested_friend($uid,$fid,"in"))
		{
			$this->confirm_friend($fid,$uid);
			e(lang("friend_added"));
		}else
		{
			$db->insert(tbl($this->dbtbl['contacts']),array('userid','contact_userid','date_added'),
												 array($uid,$fid,now()));
			$insert_id = $db->insert_id();
			
			e(lang("friend_request_sent"),"m");
			
			//Sending friendship request email
			$tpl = $cbemail->get_template('friend_request_email');
			
			
			$more_var = array
			(
			 '{reciever}'	=> $friend['username'],
			 '{sender}'		=> $sender['username'],
			 '{sender_link}'=> $this->profile_link($sender),
			 '{request_link}'=> BASEURL.'/manage_contacts.php?mode=request&confirm='.$insert_id
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			//Now Finally Sending Email
			cbmail(array('to'=>$friend['email'],'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));		
		}
		
	}
	
	/**
	 * Function used to check weather users are confirmed friends or not
	 */
	function is_confirmed_friend($uid,$fid)
	{
		global $db;
		$count = $db->count(tbl($this->dbtbl['contacts']),"contact_id",
					" (userid='$uid' AND contact_userid='$fid') OR (userid='$fid' AND contact_userid='$uid') AND confirmed='yes'" );
		if($count[0]>0)
			return true;
		else
			return false;
	}
	
	/**
	 * function used to check weather users are firends or not
	 */
	function is_friend($uid,$fid)
	{
		global $db;
		$count = $db->count(tbl($this->dbtbl['contacts']),"contact_id",
					" (userid='$uid' AND contact_userid='$fid') OR (userid='$fid' AND contact_userid='$uid')" );
		if($count[0]>0)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to check weather user has already requested friendship or not
	 */
	function is_requested_friend($uid,$fid,$type='out',$confirm=NULL)
	{
		global $db;
		
		$query = "";
		if($confirm)
			$query = " AND confirmed='$confirm' ";
			
		if($type=='out')
			$count = $db->count(tbl($this->dbtbl['contacts']),"contact_id"," userid='$uid' AND contact_userid='$fid' $query" );
			
		else
			$count = $db->count(tbl($this->dbtbl['contacts']),"contact_id"," userid='$fid' AND contact_userid='$uid' $query" );

		if($count[0]>0)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to confirm friend
	 */
	function confirm_friend($uid,$rid,$msg=TRUE)
	{
		global $cbemail,$db;
		if(!$this->is_requested_friend($rid,$uid,'out','no'))
		{
			if($msg)
			e(lang("friend_confirm_error"));
		}else
		{
			$db->update(tbl($this->dbtbl['contacts']),array('confirmed'),array("yes")," userid='$rid' AND contact_userid='$uid' " );
			if($msg)
				e(lang("friend_confirmed"),"m");
			//Sending friendship confirmation email
			$tpl = $cbemail->get_template('friend_confirmation_email');
			
			$friend = $this->get_user_details($rid);
			$sender = $this->get_user_details($uid);
			
			$more_var = array
			(
			 '{reciever}'	=> $friend['username'],
			 '{sender}'		=> $sender['username'],
			 '{sender_link}'=> $this->profile_link($sender),
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			
			//Now Finally Sending Email
			cbmail(array('to'=>$friend['email'],'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));	
			
			
			//Loggin Friendship
			
			$log_array = array
			(
			 'success'=>'yes',
			 'action_obj_id' => $friend['userid'],
			 'details'=>"friend with ".$friend['username']
			 );
			
			insert_log('add_friend',$log_array);
			
			$log_array = array
			(
			 'success'=>'yes',
			 'username' => $friend['username'],
			 'userid' => $friend['userid'],
			 'userlevel' => $friend['level'],
			 'useremail' => $friend['email'],
			 'action_obj_id' => $insert_id,
			 'details'=> "friend with ".userid()
			);
			
			//Login Upload
			insert_log('add_friend',$log_array);
		}	
	}
		
	/**
	 * Function used to confirm request
	 */
	function confirm_request($rid,$uid=NULL)
	{
		global $db;
		
		if(!$uid)
			$uid = userid();
			
		$result = $db->select(tbl($this->dbtbl['contacts']),"*"," contact_id='$rid'");
		$result = $result[0];
		
		if($db->num_rows==0)
			e(lang("friend_request_not_found"));
		elseif($uid!=$result['contact_userid'])
			e(lang("you_cant_confirm_this_request"));
		elseif($result['confirmed']=='yes')
			e(lang("friend_request_already_confirmed"));
		else
		{
			$this->confirm_friend($uid,$result['userid']);
		}
	}
		
	
	/**
	 * Function used to get user contacts
	 */
	function get_contacts($uid,$group=0,$confirmed=NULL,$count_only=false)
	{
		global $db;
		
		$query = "";
		if($confirmed)
			$query = " AND confirmed='$confirmed' ";
		
		if(!$count_only)
		{
			$result = $db->select(tbl($this->dbtbl['contacts']),"*",
											   " (userid='$uid' OR contact_userid='$uid') $query AND contact_group_id='$group' ");
			if($db->num_rows>0)
				return $result;
			else
				return false;
		}else{
			return $db->count(tbl($this->dbtbl['contacts']),"*",
											   " (userid='$uid' OR contact_userid='$uid') $query AND contact_group_id='$group' ");
		}
	}
	
	/**
	 * Function used to get pending contacts
	 */
	function get_pending_contacts($uid,$group=0)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['contacts']),"*"," userid='$uid' AND confirmed='no' AND contact_group_id='$group' ");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Function used to get pending contacts
	 */
	function get_requested_contacts($uid,$group=0)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['contacts']),"*"," contact_userid='$uid' AND confirmed='no' AND contact_group_id='$group' ");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	
	/**
	 * Function used to remove user from contact list
	 * @param fid {id of friend that user wants to remove}
	 * @param uid {id of user who is removing other from friendlist}
	 */
	function remove_contact($fid,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid = userid();
		if(!$this->is_friend($fid,$uid))
			e(lang("user_no_in_contact_list"));
		else
		{
			$db->Execute("DELETE from ".tbl($this->dbtbl['contacts'])." WHERE 
						(userid='$uid' AND contact_userid='$fid') OR (userid='$fid' AND contact_userid='$uid')" );
			e(lang("user_removed_from_contact_list"),"m");
		}
	}
		
	/**
	 * Funcion used to increas user total_watched field
	 */
	function increment_watched_vides($userid)
	{
		global $db;
		$db->update(tbl($this->dbtbl['users']),array('total_watched'),array('|f|total_watched+1')," userid='$userid'");
	}
			
	/**
	 * Old Function : GetNewMsgs
	 * This function is used to get user messages
	 * @param : user
	 * @param : sent/inbox 
	 * @param : count (TRUE : FALSE)
	 */
		 
	function get_pm_msgs($user,$box='inbox',$count=FALSE){
		global $db,$eh,$LANG;
		if(!$user)
			$user = user_id();	
		if(!user_id())
		{
			$eh->e(lang('you_not_logged_in'));
		}else{
			switch($box)
			{
				case 'inbox':
				default:
				$boxtype = 'inbox';
				break;
				
				case 'sent':
				case 'outbox':
				$boxtype = 'outbox';
				break;
			}
			
			if($count)
				$status_query = " AND status = '0' ";
				
			$results = $db->select(tbl("messages"),
						" message_id ",
						"(".$boxtype."_user = '$user' OR ".$boxtype."_user_id = '$user') $status_query");
			
	
			if($db->num_rows > 0)
			{
				if($count)
				return $db->num_rows;
				else
				return $results;
			}
			else
			{
				return false;
			}
		}
	}
	function GetNewMsgs($user)
	{
		$msgs = $this->get_pm_msgs($user,'inbox',TRUE);
		if($msgs)
			return $msgs;
		else
			return 0;
	}
		
		
	/**
	 * Function used to subscribe user
	 */
	function subscribe_user($to,$user=NULL)
	{
		if(!$user)
			$user = userid();
		global $db;
		
		$to_user = $this->get_user_details($to);
		
		if(!$this->user_exists($to))
			e(lang('usr_exist_err'));
		elseif(!$user)
			e(sprintf(lang('please_login_subscribe'),$to_user['username']));
		elseif($this->is_subscribed($to,$user))
			e(sprintf(lang("usr_sub_err"),$to_user['username']));
		else
		{
			$db->insert(tbl($this->dbtbl['subtbl']),array('userid','subscribed_to','date_added'),
											   array($user,$to,NOW()));
			$db->update(tbl($this->dbtbl['users']),array('subscribers'),
											   array($this->get_user_subscribers($to,true))," userid='$to' ");
			$db->update(tbl($this->dbtbl['users']),array('total_subscriptions'),
											   array($this->get_user_subscriptions($user,'count'))," userid='$user' ");
			//Loggin Comment			
			$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "subsribed to ".$to_user['username'],
			 'action_obj_id' => $to_user['userid'],
			 'action_done_id' => $db->insert_id(),
			);
			insert_log('subscribe',$log_array);
			
			e(sprintf(lang('usr_sub_msg'),$to_user['username']),'m');
		}			
	}
	function SubscribeUser($sub_user,$sub_to){return $this->subscribe_user($sub_to,$sub_user);}
		
	/**
	 * Function used to check weather user is already subscribed or not
	 */
	function is_subscribed($to,$user=NULL)
	{
		if(!$user)
			$user = userid();
		global $db;
		
		if(!$user)
			return false;
		$result = $db->select(tbl($this->dbtbl['subtbl']),"*"," subscribed_to='$to' AND userid='$user'");
		if($db->num_rows>0)
			return $result;
		else
			return false;			
	}
	
	/**
	 * Function used to remove user subscription
	 */
	function remove_subscription($subid,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid = userid();
		if($this->is_subscribed($subid,$uid))
		{
			$db->execute("DELETE FROM ".tbl($this->dbtbl['subtbl'])." WHERE userid='$uid' AND subscribed_to='$subid'");
			e(lang("class_unsub_msg"),"m");
			
			$db->update(tbl($this->dbtbl['users']),array('subscribers'),
											   array($this->get_user_subscribers($subid,true))," userid='$subid' ");
			$db->update(tbl($this->dbtbl['users']),array('total_subscriptions'),
											   array($this->get_user_subscriptions($uid,'count'))," userid='$uid' ");
			
			
			return true;
		}else
			e(lang("you_not_subscribed"));
		
		return false;
	}function unsubscribe_user($subid,$uid=NULL){ return $this->remove_subscription($subid,$uid); }
	
	
	/**
	 * Function used to get user subscibers
	 * @param userid
	 */
	function get_user_subscribers($id,$count=false)
	{
		global $db;
		if(!$count)
		{
			$result = $db->select(tbl($this->dbtbl['subtbl']),"*"," subscribed_to='$id' ");
			if($db->num_rows>0)
				return $result;
			else
				return false;	
		}else
			return $db->count(tbl($this->dbtbl['subtbl']),"subscription_id"," subscribed_to='$id' ");
	}
	
	/**
	 * function used to get user subscribers with details
	 */
	function get_user_subscribers_detail($id,$limit=NULL)
	{
		global $db;
		$result = $db->select(tbl("users,".$this->dbtbl['subtbl']),"*"," ".tbl("subscriptions.subscribed_to")." = '$id' AND ".tbl("subscriptions.userid")."=".tbl("users.userid"),$limit);
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Function used to get user subscriptions
	 */
	function get_user_subscriptions($id,$limit=NULL)
	{	
		global $db;
		if($limit!='count')
		{
			$result = $db->select(tbl("users,".$this->dbtbl['subtbl']),"*"," ".tbl("subscriptions.userid")." = '$id' AND ".tbl("subscriptions.subscribed_to")."=".tbl("users.userid"),$limit);
			if($db->num_rows>0)
				return $result;
			else
				return false;
		}else
		{
			$result =  $db->count(tbl($this->dbtbl['subtbl']),"subscription_id"," userid = '$id'");
			return $result;
		}
	}
	
	

	
	/**
	 * Function used to reset user password
	 * it has two steps
	 * 1 to send confirmation
	 * 2 to reset the password
	 */
	 
	function reset_password($step,$input,$code=NULL)
	{
		global $cbemail,$db;
		switch($step)
		{
			case 1:
			{
				$udetails = $this->get_user_details($input);
				if(!$udetails)
					e(lang('usr_exist_err'));
				 //verifying captcha...
				elseif(!verify_captcha())
					e(lang('usr_ccode_err'));
				else
				{
					//Sending confirmation email
					$tpl = $cbemail->get_template('password_reset_request');
					$more_var = array
					('{username}'	=> $udetails['username'],
					 '{email}'		=> $udetails['email'],
					 '{avcode}'		=> $udetails['avcode'],
					 '{userid}'		=> $udetails['userid'],
					);
					if(!is_array($var))
						$var = array();
					$var = array_merge($more_var,$var);
					$subj = $cbemail->replace($tpl['email_template_subject'],$var);
					$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
					
					//Now Finally Sending Email
					cbmail(array('to'=>$udetails['email'],'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));
				
					e(lang('usr_rpass_email_msg'),"m");
				}
			}
			break;
			case 2:
			{
				$udetails = $this->get_user_details($input);
				if(!$udetails)
					e(lang('usr_exist_err'));
				 //verifying captcha...
				elseif($udetails['avcode'] !=$code)
					e(lang('usr_ccode_err'));
				else
				{
					$newpass = RandomString(6);
					$pass 	 = pass_code($newpass);
					$avcode = RandomString(10);
					$db->update(tbl($this->dbtbl['users']),array('password','avcode'),array($pass,$avcode)," userid='".$udetails['userid']."'");
					//sending new password email...
					//Sending confirmation email
					$tpl = $cbemail->get_template('password_reset_details');
					$more_var = array
					('{username}'	=> $udetails['username'],
					 '{email}'		=> $udetails['email'],
					 '{avcode}'		=> $udetails['avcode'],
					 '{userid}'		=> $udetails['userid'],
					 '{password}'	=> $newpass,
					);
					if(!is_array($var))
						$var = array();
					$var = array_merge($more_var,$var);
					$subj = $cbemail->replace($tpl['email_template_subject'],$var);
					$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
					
					//Now Finally Sending Email
					cbmail(array('to'=>$udetails['email'],'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));
					e(lang('usr_pass_email_msg'),m);
				}
			}
			break;
		}
	}
										
	/**
	 * Function used to recover username
	 */
	function recover_username($email)
	{
		global $cbemail;
		$udetails = $this->get_user_details($email);
		if(!$udetails)
			e(lang('usr_exist_err'));
		elseif(!verify_captcha())
					e(lang('usr_ccode_err'));
		else
		{
			$tpl = $cbemail->get_template('forgot_username_request');
			$more_var = array
			(
			 '{username}'	=> $udetails['username'],
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			//Now Finally Sending Email
			cbmail(array('to'=>$udetails['email'],'from'=>SUPPORT_EMAIL,'subject'=>$subj,'content'=>$msg));
			e(lang('usr_pass_email_msg'),m);
			e(lang("usr_uname_email_msg"),"m");
		}
	return $msg;
	
	
	}
	
	//FUNCTION USED TO UPDATE LAST ACTIVE FOR OF USER
	// @ Param : username
	function UpdateLastActive($username)
	{
		global $db;
		$sql = "UPDATE ".tbl("users")." SET last_active = '".NOW()."' WHERE username='".$username."' OR userid='".$username."' ";
		$db->Execute($sql);
	}

	
	/**
	 * FUNCTION USED TO GE USER THUMBNAIL
	 * @param : thumb file
	 * @param : size (NULL,small)
	 */
	function getUserThumb($udetails,$size='',$uid=NULL,$just_file=false)
	{
		
		$remote = false;
		if(empty($udetails['userid']))
			$udetails = $this->get_user_details($uid);
		//$thumbnail = $udetails['avatar'] ? $udetails['avatar'] : NO_AVATAR;
		$thumbnail = $udetails['avatar'];
		$thumb_file = USER_THUMBS_DIR.'/'.$thumbnail;
		if(file_exists($thumb_file) && $thumbnail!='')
			$thumb_file = USER_THUMBS_URL.'/'.$thumbnail;
		elseif(!empty($udetails['avatar_url']))
		{
			$thumb_file = $udetails['avatar_url'];
			$remote  = true;
		}else
			$thumb_file = USER_THUMBS_URL.'/'.NO_AVATAR;
		$ext = GetExt($thumb_file);
		$file = getName($thumb_file);
		
		if(!$remote)
		{
			if(!empty($size))
				$thumb = USER_THUMBS_URL.'/'.$file.'-'.$size.'.'.$ext;
			else
				$thumb = USER_THUMBS_URL.'/'.$file.'.'.$ext;
		}else
			$thumb = $thumb_file;
		
		if($just_file)
			return $file.'.'.$ext;
			
		return $thumb;
	}
	function avatar($udetails,$size='',$uid=NULL)
	{
		return $this->getUserThumb($udetails,$size,$uid);
	}
	
	/**
	 * Function used to get user Background
	 * @param : bg file
	 */
	function getUserBg($udetails)
	{
		$remote = false;
		if(empty($udetails['userid']))
			$udetails = $this->get_user_details($uid);
		//$thumbnail = $udetails['avatar'] ? $udetails['avatar'] : 'no_avatar.jpg';
		$file = $udetails['background'];
		$bgfile = USER_BG_DIR.'/'.$file;
		if(file_exists($bgfile) && $file)
			$thumb_file = USER_BG_URL.'/'.$file;
		elseif(!empty($udetails['background_url']))
		{
			$thumb_file = $udetails['background_url'];
			$remote  = true;
		}else
			return false;

		return $thumb_file;
	}	
	
	

	/**
	 * Function used to get user subscriber's list
	 * @param VARCHAR//INT username or userid , both works fine
	 */
	function get_user_subscriber($username)
	{
		global $db;
		$results = $db->Execute("SELECT * FROM ".tbl("subscriptions")." WHERE subsctibe_to='$username'");
		if($results->recordcount() > 0)
			return $results->getrows();
		else
			return false;
	}
	
	
	
	/**
	 * Function used to get user field
	 * @ param INT userid 
	 * @ param FIELD name
	 */
	function get_user_field($uid,$field)
	{
		global $db;
		$results = $db->select(tbl('users'),$field,"userid='$uid' OR username='$uid'");
		
		if($db->num_rows>0)
		{
			return $results[0];
		}else{
			return false;
		}
	}function get_user_fields($uid,$field){return $this->get_user_field($uid,$field);}
	
	
	/**
	 * This function will return
	 * user field without array
	 */
	function get_user_field_only($uid,$field)
	{
		$fields = $this->get_user_field($uid,$field);
		return $fields[$field];
	}
	
	/**
	 * Function used to get user level and its details
	 * @param INT userid
	 */
	function get_user_level($uid,$is_level=false)
	{
		global $db;
		
		if($is_level)
			$level = $uid;
		else
		{
			$level = $this->udetails['level'];
		}
		
		
		$result = $db->select(tbl('user_levels,user_levels_permissions'),'*',
							  tbl("user_levels_permissions.user_level_id")."='".$level."' 
							  AND ".tbl("user_levels_permissions.user_level_id")." = ".tbl("user_levels.user_level_id"));
		
		/*		
		pr($result);
		$results = $db->select(tbl('user_levels'),'*'," user_level_id='".$level['level']."'");
		if($db->num_rows == 0)
		 //incase user level is not valid, it will consider it as registered user
			$u_level['user_level_id'] = 3;
		else
			$u_level = $results[0];
			
		//Now Getting Access Details
		$access_results = $db->select("user_levels_permissions","*",
									  "user_level_id = '".$u_level['user_level_id']."'");
		$a_results = $access_results[0];*/
		
		//Now Merging the two arrays
		$user_level = $result[0];
		//pr($user_level);
		return $user_level;
	}
	
	
	/**
	 * Function used to get all levels
	 * @param : filter
	 */
	function get_levels($filter=NULL)
	{
		global $db;
		$results = $db->select(tbl("user_levels"),"*",NULL,NULL," user_level_id ASC" );
		if($db->num_rows > 0)
		{
			return $results;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to get level details
	 * @param : level_id INT
	 */
	function get_level_details($lid)
	{
		global $db;
		$results = $db->select(tbl("user_levels"),"*"," user_level_id='$lid' ");
		if($db->num_rows > 0 )
		{
			return $results[0];
		}else{
			e(lang("cant_find_level"));
			return false;
		}
	}
	
	/**
	 * Function used to get users of particular level
	 * @param : level_id
	 * @param : count BOOLEAN (if TRUE it will return NUMBERS)
	 */
	function get_level_users($id,$count=FALSE)
	{
		global $db;
		$results = $db->select(tbl("users"),"level"," level='$id'");
		if($db->num_rows>0)
		{
			if($count)
				return $db->num_rows;
			else
				return $results;
		}else{
			return 0;
		}
	}
	
	
	/**
	 * Function used to add user level
	 */
	function add_user_level($array)
	{
		global $db;
		if(!is_array($array))
			$array = $_POST;
		$level_name = mysql_clean($array['level_name']);
		if(empty($level_name))
			e(lang("please_enter_level_name"));
		else
		{
			$db->insert(tbl("user_levels"),array('user_level_name'),array($level_name));
			$iid = $db->insert_id();
			
			$fields_array[] = 'user_level_id';
			$value_array[] = $iid;
			foreach($this->get_access_type_list() as $access => $name)
			{
				$fields_array[] = $access;
				$value_array[] = $array[$access] ? $array[$access] : 'no';
			}
			$db->insert(tbl("user_levels_permissions"),$fields_array,$value_array);		
			return true;
		}
	}
	
	/**
	 * Function usewd to get level permissions
	 */
	function get_level_permissions($id)
	{
		global $db;
		$results = $db->select(tbl("user_levels_permissions"),"*"," user_level_id = '$id'");		
		if($db->num_rows>0)
			return $results[0];
		else
			return false;
	}
	
	/**
	 * Function used to get custom permissions
	 */
	function get_access_type_list()
	{
		if(!$this->access_type_list)
		{
			$perms = $this->get_permissions();
			foreach($perms as $perm)
			{
				$this->add_access_type($perm['permission_code'],$perm['permission_name']);
			}
		}
		return $this->access_type_list;
	}
	
	/**
	 * Function used to add new custom permission
	 */
	function add_access_type($access,$name)
	{
		if(!empty($access) && !empty($name))
			$this->access_type_list[$access] = $name;
	}
	
	/**
	 * Function get access
	 */
	function get_access($access)
	{
		return $this->access_type_list[$access];
	}
	
	/**
	 * Function used to update user level
	 * @param INT level_id
	 * @param ARRAY perm_level
	 */
	function update_user_level($id,$array)
	{
		global $db;
		if(!is_array($array))
			$array = $_POST;
		
		//First Checking Level
		$level = $this->get_level_details($id);
		if($level)
		{
			foreach($this->get_access_type_list() as $access => $name)
			{
				$fields_array[] = $access;
				$value_array[] = $array[$access];
			}
			
			//Checking level Name
			if(!empty($array['level_name']))
			{
				$level_name = mysql_clean($array['level_name']);
				//Upadting Now
				$db->update(tbl("user_levels"),array("user_level_name"),array($level_name)," user_level_id = '$id'");
			}
			
			//Updating Permissions
			$db->update(tbl("user_levels_permissions"),$fields_array,$value_array," user_level_id = '$id'");
			
			e(lang("level_updated"),m);
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to delete user levels
	 * @param INT level_id
	 */
	function delete_user_level($id)
	{
		global $db;
		$level_details = $this->get_level_details($id);
		$de_level = $this->get_level_details(3);
		if($level_details)
		{
			//CHeck if leve is deleteable or not
			if($level_details['user_level_is_default']=='no')
			{
				$db->delete(tbl("user_levels"),array("user_level_id"),array($id));
				$db->delete(tbl("user_levels_permissions"),array("user_level_id"),array($id));
				e(sprintf(lang("level_del_sucess"),$de_level['user_level_name']));
				
				$db->update(tbl("users"),array("level"),array(3)," level='$id'");
				return true;
				
			}else{
				e(lang("level_not_deleteable"));
				return false;
			}
		}
	}
	
	
	/**
	 * Function used to count total video comments
	 */
	function count_profile_comments($id)
	{
		global $db;
		$total_comments = $db->count(tbl('comments'),"comment_id","type='c' AND type_id='$id'");
		return $total_comments;
	}
	function count_channel_comments($id){ return $this->count_profile_comments($id); }
	
	/**
	 * Function used to count total comments made by users
	 */
	function count_comments_by_user($uid)
	{
		global $db;
		$total_comments = $db->count(tbl('comments'),"comment_id","userid='$uid'");
		return $total_comments;
	}
	
	/**
	 * Function used to update user comments
	 */
	function update_comments_by_user($uid)
	{
		global $db;
		$total_comments = $this->count_comments_by_user($id);
		$db->update(tbl("users"),array("total_comments"),array($total_comments)," userid='$id'");
	}
	
	/**
	 * Function used to update user comments count
	 */
	function update_comments_count($id)
	{
		global $db;
		$total_comments = $this->count_profile_comments($id);
		$db->update(tbl("users"),array("comments_count"),array($total_comments)," userid='$id'");
	
	}
	/**
	 * Function used to add comment on users profile
	 */
	function add_comment($comment,$obj_id,$reply_to=NULL,$type='c')
	{
		global $myquery;
		if(!$this->user_exists($obj_id))
			e(lang("usr_exist_err"));
		else
		{
			$add_comment = $myquery->add_comment($comment,$obj_id,$reply_to,$type,$obj_id);
		}
		if($add_comment)
		{
			//Loggin Comment			
			$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "comment on a profile",
			 'action_obj_id' => $obj_id,
			 'action_done_id' => $add_comment,
			);
			insert_log('profile_comment',$log_array);
			
			//Updating Number of comments of video
			$this->update_comments_count($obj_id);
		}
		return $add_comment;
	}
	
	/**
	 * Function used to remove video comment
	 */
	function delete_comment($cid,$is_reply=FALSE)
	{
		global $myquery,$db;
		$remove_comment =  $myquery->delete_comment($cid,'c',$is_reply);
		if($remove_comment)
		{
			//Updating Number of comments of video
			$this->update_comments_count($obj_id);
		}
		return $remove_comment;
	}
	
	
	
	/**
	 * Function used to get number of videos uploaded by user
	 * @param INT userid
	 * @param Conditions
	 */
	function get_user_vids($uid,$cond=NULL,$count_only=false)
	{
		global $db;
		if($cond!=NULL)
			$cond = " AND $cond ";
			
		$results = $db->select(tbl("video"),"*"," userid = '$uid' $cond");
		if($db->num_rows > 0)
		{
			if($count_only)
				return $db->num_rows;
			else
				return $results[0];
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to get logged in username
	 */
	function get_logged_username()
	{
		return $this->get_user_field_only(user_id(),'username');
	}

	/**
	 * FUnction used to get username from userid
	 */
	function get_username($uid)
	{
		return $this->get_user_field_only($uid,'username');
	}
	
	/**
	 * Function used to create profile link
	 */
	function profile_link($udetails)
	{
		if(!is_array($udetails) && is_numeric($udetails))
			$udetails = $this->get_user_details($udetails);
		if(SEO!="yes")
			return BASEURL.'/view_channel.php?user='.$udetails['username'];
		else
			return BASEURL.'/user/'.$udetails['username'];
	}
	function get_user_link($u)
	{
		return $this->profile_link($u);
	}
	
	
	/**
	 * Function used to get permission types
	 */
	function get_level_types()
	{
		global $db;
		return $db->select(tbl($this->dbtbl['user_permission_type']),"*");
	}
	
	/**
	 * Function used to check weather level type exists or not
	 */
	function level_type_exists($id)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['user_permission_type']),"*"," user_permission_type_id='".$id."' OR user_permission_type_name='$id'");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Function used to add new permission
	 */
	function add_new_permission($array)
	{
		global $db;
		if(empty($array['code']))
			e(lang("perm_code_empty"));
		elseif(empty($array['name']))
			e(lang("perm_name_empty"));
		elseif($this->permission_exists($array['code']))
			e(lang("perm_already_exist"));
		elseif(!$this->level_type_exists($array['type']))
			e(lang("perm_type_not_valid"));
		else
		{
			$type = $this->level_type_exists($array['type']);
			$typeid = $type['user_permission_type_id'];
			$code = mysql_clean($array['code']);
			$name = mysql_clean($array['name']);
			$desc = mysql_clean($array['desc']);
			$default = mysql_clean($array['default']);
			$default = $default ? $default : "yes";
			$db->insert(tbl($this->dbtbl['user_permissions']),
						array('permission_type','permission_code','permission_name','permission_desc','permission_default'),
						array($typeid,$code,$name,$desc,$default));
			$db->execute("ALTER TABLE `".tbl($this->dbtbl['user_level_permission'])."` ADD `".$code."` ENUM( 'yes', 'no' ) NOT NULL DEFAULT '".$default."'");
			e(lang("perm_added"),"m");
		}
	}
	
	/**
	 * Function used to check permission exists or not
	 * @Param permission code
	 */
	function permission_exists($code)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['user_permissions']),"*"," permission_code='".$code."' OR permission_id='".$code."'");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Function used to get permissions
	 */
	function get_permissions($type=NULL)
	{
		global $db;
		if($type)
			$cond = " permission_type ='$type'";
		$result = $db->select(tbl($this->dbtbl['user_permissions']),"*",$cond);
		if($db->num_rows>0)
		{
			return $result;
		}else
		{
			return false;
		}
	}
	
	/**
	 * Function used to remove Permission
	 */
	function remove_permission($id)
	{
		global $db;
		$permission = $this->permission_exists($id);
		if($permission)
		{
			$field = $permission['permission_code'];
			$db->delete(tbl($this->dbtbl['user_permissions']),array("permission_id"),array($id));
			$db->execute("ALTER TABLE `".tbl($this->dbtbl['user_level_permission'])."` DROP `".$field."` ");
			e(lang("perm_deleted"),"m");
		}else
			e(lang("perm_doesnt_exist"));
	}
	
	
	/**
	 * Function used to check weather current user has permission
	 * to view page or not
	 * it will also check weather current page requires login 
	 * if login is required, user will be redirected to signup page
	 */
	function perm_check($access='',$check_login=FALSE,$control_page=true)
	{
		global $Cbucket;
		/*if($check_login)
		{
			return $this->login_check($access);
		}else
		{*/
			$access_details = $this->permission;
			if(is_numeric($access))
			{
				if($access_details['level_id'] == $access)
				{
					return true;
				}else{
					if(!$check_only)
					e(lang('insufficient_privileges'));
					
					if($control_page)
					$Cbucket->show_page(false);
					return false;
				}
			}else
			{
				
				if($access_details[$access] == 'yes')
				{
					return true;
				}
				else
				{
					
					if(!$check_login)
						e(lang('insufficient_privileges'));
					else
					{	if(userid())
							e(lang('insufficient_privileges'));
						else
							e(sprintf(lang('insufficient_privileges_loggin'),cblink(array('name'=>'signup')),cblink(array('name'=>'signup'))));
					}
					
					if($control_page)
					$Cbucket->show_page(false);
					return false;
				}
			}
		//}
	}
	
	
	/** 
	 * Function used to get user profile details
	 */
	function get_user_profile($uid)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['user_profile']),"*"," userid='$uid'");
		if($db->num_rows>0)
		{
			return $result[0];
		}else
			return false;
	}
	
	
	
	/**
	 * FUnction loading personal details
	 */
	function load_personal_details($default)
	{
		
		$user_vids = get_videos(array('user'=>$default['userid']));
		if(is_array($user_vids))
		foreach($user_vids as $user_vid)
		{
			$usr_vids[$user_vid['videoid']] =  $user_vid['title'];
		}
		
		if(!$default)
			$default = $_POST;
		$profile_fields = array
		(
		'first_name' => array(
						  'title'=> lang("user_fname"),
						  'type'=> "textfield",
						  'name'=> "first_name",
						  'id'=> "first_name",
						  'value'=> $default['first_name'],
						  'db_field'=>'first_name',
						  'required'=>'no',
						  'syntax_type'=> 'name',
						  'auto_view'=>'yes'
						  ),
		'last_name' => array(
						  'title'=> lang("user_lname"),
						  'type'=> "textfield",
						  'name'=> "last_name",
						  'id'=> "last_name",
						  'value'=> $default['last_name'],
						  'db_field'=>'last_name',
						  'syntax_type'=> 'name',
						  'auto_view'=>'yes'
						  ),
		'profile_title' => array(
						  'title'=>  lang("profile_title"),
						  'type'=> "textfield",
						  'name'=> "profile_title",
						  'id'=> "last_name",
						  'value'=> $default['profile_title'],
						  'db_field'=>'profile_title',
						  'auto_view'=>'no'
		
						  ),
		'profile_desc' => array(
						  'title'=>  lang("profile_desc"),
						  'type'=> "textarea",
						  'name'=> "profile_desc",
						  'id'=> "last_name",
						  'value'=> $default['profile_desc'],
						  'db_field'=>'profile_desc',
						  'auto_view'=>'no'
		
						  ),
		'relation_status' => array(
						  'title'=>  lang("user_relat_status"),
						  'type'=> "dropdown",
						  'name'=> "relation_status",
						  'id'=> "last_name",
						  'value'=> array(lang('usr_arr_single')=>lang('usr_arr_single'),
										  lang('usr_arr_married')=>lang('usr_arr_married'),
										  lang('usr_arr_comitted')=>lang('usr_arr_comitted'),
										  lang('usr_arr_open_relate')=>lang('usr_arr_open_relate')),
						  'checked'=> $default['relation_status'],
						  'db_field'=>'relation_status',
						  'auto_view'=>'yes',
						  'return_checked'	=> true,
		
						  ),
		'show_dob' => array(
						  'title'=>  lang("show_dob"),
						  'type'=> "radiobutton",
						  'name'=> "show_dob",
						  'id'=> "show_dob",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked'	=> $default['show_dob'],
						  'db_field'=>'show_dob',
						  'syntax_type'=> 'name',
						  'auto_view'=>'no'
						  ),
		'about_me' => array(
						  'title'=>  lang("user_about_me"),
						  'type'=> "textarea",
						  'name'=> "about_me",
						  'id'=> "about_me",
						  'value'=> $default['about_me'],
						  'db_field'=>'about_me',
						  'auto_view'=>'yes',
						  ),
		'profile_tags' => array(
						  'title'=>  lang("profile_tags"),
						  'type'=> "textfield",
						  'name'=> "profile_tags",
						  'id'=> "profile_tags",
						  'value'=> $default['profile_tags'],
						  'db_field'=>'profile_tags',
						  'auto_view'=>'no'
						  ),
		'web_url' => array(
						  'title'=>  lang("website"),
						  'type'=> "textfield",
						  'name'=> "web_url",
						  'id'=> "web_url",
						  'value'=> $default['web_url'],
						  'db_field'=>'web_url',
						  'auto_view'=>'yes',
						  'display_function'=>'outgoing_link'
						  ),
		'profile_video' => array(
						  'title' => lang('Profile Video'),
						  'type' => 'dropdown',
						  'name' => 'profile_video',
						  'id' => 'profile_video',
						  'value' => $usr_vids,
						  'checked' => $default['profile_video'],
						  'db_field' => 'profile_video',
						  'auto_view' => 'no',

						  )
		
		);
		
		return $profile_fields;
	}
	
	
	/**
	 * function used to load location fields
	 */
	function load_location_fields($default)
	{
		if(!$default)
			$default = $_POST;
		$other_details = array
		(
		'postal_code' => array(
						  'title'=>  lang("postal_code"),
						  'type'=> "textfield",
						  'name'=> "postal_code",
						  'id'=> "postal_code",
						  'value'=> $default['postal_code'],
						  'db_field'=>'postal_code',
						  ),
		'hometown' => array(
						  'title'=>  lang("hometown"),
						  'type'=> "textfield",
						  'name'=> "hometown",
						  'id'=> "hometown",
						  'value'=> $default['hometown'],
						  'db_field'=>'hometown',
						  ),
		'city' => array(
						  'title'=>  lang("city"),
						  'type'=> "textfield",
						  'name'=> "city",
						  'id'=> "city",
						  'value'=> $default['city'],
						  'db_field'=>'city',
						  ),
		);
		return $other_details;
	}
	
	
	/**
	 * Function used to load experice fields
	 */
	function load_other_fields($default)
	{
		if(!$default)
			$default = $_POST;
		$more_details = array
		(
		'education' => array(
						  'title'=>  lang("education"),
						  'type'=> "dropdown",
						  'name'=> "education",
						  'id'=> "education",
						  'value'=> array(lang('usr_arr_no_ans')=>lang('usr_arr_no_ans'),
										  lang('usr_arr_elementary')=>lang('usr_arr_elementary'),
										  lang('usr_arr_hi_school')=>lang('usr_arr_hi_school'),
										  lang('usr_arr_some_colg')=>lang('usr_arr_some_colg'),
										  lang('usr_arr_assoc_deg')=>lang('usr_arr_assoc_deg'),
										  lang('usr_arr_bach_deg')=>lang('usr_arr_bach_deg'),
										  lang('usr_arr_mast_deg')=>lang('usr_arr_mast_deg'),
										  lang('usr_arr_phd')=>lang('usr_arr_phd'),
										  lang('usr_arr_post_doc')=>lang('usr_arr_post_doc'),
										  ),
						  'checked'=>$default['education'],
						  'db_field'=>'education',
						  ),
		'schools' => array(
						  'title'=>  lang("schools"),
						  'type'=> "textarea",
						  'name'=> "schools",
						  'id'=> "schools",
						  'value'=> $default['schools'],
						  'db_field'=>'schools',
						  ),
		'occupation' => array(
						  'title'=>  lang("occupation"),
						  'type'=> "textarea",
						  'name'=> "occupation",
						  'id'=> "occupation",
						  'value'=> $default['occupation'],
						  'db_field'=>'occupation',
						  ),
		'companies' => array(
						  'title'=>  lang("companies"),
						  'type'=> "textarea",
						  'name'=> "companies",
						  'id'=> "companies",
						  'value'=> $default['companies'],
						  'db_field'=>'companies',
						  ),
		'hobbies' => array(
						  'title'=>  lang("hobbies"),
						  'type'=> "textarea",
						  'name'=> "hobbies",
						  'id'=> "hobbies",
						  'value'=> $default['hobbies'],
						  'db_field'=>'hobbies',
						  ),
		'fav_movies' => array(
						  'title'=>  lang("user_fav_movs_shows"),
						  'type'=> "textarea",
						  'name'=> "fav_movies",
						  'id'=> "fav_movies",
						  'value'=> $default['fav_movies'],
						  'db_field'=>'fav_movies',
						  ),
		'fav_music' => array(
						  'title'=>  lang("user_fav_music"),
						  'type'=> "textarea",
						  'name'=> "fav_music",
						  'id'=> "fav_music",
						  'value'=> $default['fav_music'],
						  'db_field'=>'fav_music',
						  ),
		'fav_books' => array(
						  'title'=>  lang("user_fav_books"),
						  'type'=> "textarea",
						  'name'=> "fav_books",
						  'id'=> "fav_books",
						  'value'=> $default['fav_books'],
						  'db_field'=>'fav_books',
						  ),
		
		);
		return $more_details;
	}
	
	
	/**
	 * Function used to load privacy fields
	 */
	function load_privacy_field($default)
	{
		if(!$default)
			$default = $_POST;
			
		$privacy = array
		(
		'online_status' => array(
						  'title'=>  lang("online_status"),
						  'type'=> "dropdown",
						  'name'=> "privacy",
						  'id'=> "privacy",
						  'value'=> array('online'=>lang('online'),'offline'=>lang('offline'),'custom'=>lang('custom')),
						  'checked'=>$default['online_status'],
						  'db_field'=>'online_status',
						  ),
		'show_profile' => array(
						  'title'=>  lang("show_profile"),
						  'type'=> "dropdown",
						  'name'=> "show_profile",
						  'id'=> "show_profile",
						  'value'=> array('all'=>lang('all'),'members'=>lang('members'),'friends'=>lang('friends')),
						  'checked'=>$default['show_profile'],
						  'db_field'=>'show_profile',
						  ),
		'allow_comments'=>array(
						  'title'=>  lang("vdo_allow_comm"),
						  'type'=> "radiobutton",
						  'name'=> "allow_comments",
						  'id'=> "allow_comments",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['allow_comments']),
						  'db_field'=>'allow_comments',
						  ),
		'allow_ratings'=>array(
						  'title'=>  lang("allow_ratings"),
						  'type'=> "radiobutton",
						  'name'=> "allow_ratings",
						  'id'=> "allow_ratings",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['allow_ratings']),
						  'db_field'=>'allow_ratings',
						  ),
		);
		
		return $privacy;
	}
	
	/**
	 * User Profile Fields
	 */
	function load_profile_fields($default)
	{
		if(!$default)
			$default = $_POST;
		
		$profile_fields = $this->load_personal_details($default);
		$other_details = $this->load_location_fields($default);
		$more_details = $this->load_other_fields($default);
		$privacy = $this->load_privacy_field($default);		
		return array_merge($profile_fields,$other_details,$more_details,$privacy);
	}
	
	
	
	
	
	/**
	 * Function used to update use details
	 */
	function update_user($array)
	{
		global $LANG,$db,$signup,$Upload;
		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);

		$userfields = $this->load_profile_fields($array);
		$signup_fields = $this->load_signup_fields($array);
		$cat_field = $signup_fields['cat'];
		array_merge($userfields,$cat_field);
		validate_cb_form($userfields,$array);
		
		foreach($userfields as $field)
		{
			$name = formObj::rmBrackets($field['name']);
			$val = $array[$name];
			
			if($field['use_func_val'])
				$val = $field['validate_function']($val);
			
			
			if(!empty($field['db_field']))
			$query_field[] = $field['db_field'];
			
			if(is_array($val))
			{
				$new_val = '';
				foreach($val as $v)
				{
					$new_val .= "#".$v."# ";
				}
				$val = $new_val;
			}
			if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
				$val = mysql_clean($val);
			else
				$val = apply_func($field['clean_func'],$val);
			
			if(!empty($field['db_field']))
			$query_val[] = $val;
		}
		
		
		//Category
		if($cat_field)
		{
			$field = $cat_field;
			$name = formObj::rmBrackets($field['name']);
			$val = $array[$name];
			
			if($field['use_func_val'])
				$val = $field['validate_function']($val);
			
			
			if(!empty($field['db_field']))
			$uquery_field[] = $field['db_field'];
			
			if(is_array($val))
			{
				$new_val = '';
				foreach($val as $v)
				{
					$new_val .= "#".$v."# ";
				}
				$val = $new_val;
			}
			if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
				$val = mysql_clean($val);
			else
				$val = apply_func($field['clean_func'],$val);
			
			if(!empty($field['db_field']))
			$uquery_val[] = $val;
		}
		

		
		//updating user detail
		if(has_access('admin_access',TRUE) && isset($array['admin_manager']))
		{
			//Checking Username
			if(empty($array['username']))
				e(lang('usr_uname_err'));
			elseif($array['dusername'] != $array['username'] && $this->username_exists($array['username']))
				e(lang('usr_uname_err2'));
			elseif(!username_check($array['username']))
				e(lang('usr_uname_err3'));
			else
				$username = $array['username'];
			
			//Checking Email
			if(empty($array['email']))
				e(lang('usr_email_err1'));
			elseif(!is_valid_syntax('email',$array['email']))
				e(lang('usr_email_err2'));
			elseif(email_exists($array['email']) && $array['email'] != $array['demail'])
				e(lang('usr_email_err3'));
			else
				$email = $array['email'];
				
			$uquery_field[] = 'username';
			$uquery_val[]	= $username;
			
			$uquery_field[] = 'email';
			$uquery_val[]	= $email;
			
			//Changning Password
			if(!empty($array['pass']))
			{
				if($array['pass']!=$array['cpass'])
					e(lang("pass_mismatched"));
				else
					$pass = pass_code($array['pass']);
				$uquery_field[] = 'password';
				$uquery_val[]	= $pass;
			}
			
			//Changing User Level
			$uquery_field[] = 'level';
			$uquery_val[] = $array['level'];
			
			//Checking for user stats
			$uquery_field[] = 'profile_hits';
			$uquery_val[] = $array['profile_hits'];
			$uquery_field[] = 'total_watched';
			$uquery_val[] = $array['total_watched'];
			$uquery_field[] = 'total_videos';
			$uquery_val[] = $array['total_videos'];
			$uquery_field[] = 'total_comments';
			$uquery_val[] = $array['total_comments'];
			$uquery_field[] = 'subscribers';
			$uquery_val[] = $array['subscribers'];
			$uquery_field[] = 'rating';
			
			$rating = $array['rating'];
			if($rating<1 || $rating>10)
				$rating = 1;
			$uquery_val[] = $rating ;
			$uquery_field[] = 'rated_by';
			$uquery_val[] = $array['rated_by'];
			
		}
		
		//Changing Gender
		if($array['sex'])
		{
			$uquery_field[] = 'sex';
			$uquery_val[] = mysql_clean($array['sex']);
		}
		
		//Changing Country
		if($array['country'])
		{
			$uquery_field[] = 'country';
			$uquery_val[] = mysql_clean($array['country']);
		}
		
		//Updating User Avatar
		if($array['avatar_url'])
		{
			$uquery_field[] = 'avatar_url';
			$uquery_val[] = $array['avatar_url'];
		}
		
		//Deleting User Avatar
		if($array['delete_avatar']=='yes')
		{
			$file = USER_THUMBS_DIR.'/'.$array['avatar_file_name'];
			if(file_exists($file) && $array['avatar_file_name'] !='')
				unlink($file);
		}
		
		//Deleting User Bg
		if($array['delete_bg']=='yes')
		{
			$file = USER_THUMBS_DIR.'/'.$array['bg_file_name'];
			if(file_exists($file) && $array['bg_file_name'] !='')
				unlink($file);
		}
		
		
		if(isset($_FILES['avatar_file']['name']))
		{
			$file = $Upload->upload_user_file('a',$_FILES['avatar_file'],$array['userid']);
			if($file)
			{
				$uquery_field[] = 'avatar';
				$uquery_val[] = $file;
			}
		}
		
		
		//Updating User Background
		if($array['background_url'])
		{
			$uquery_field[] = 'background_url';
			$uquery_val[] = $array['background_url'];
		}
		
		if($array['background_color'])
		{
			$uquery_field[] = 'background_color';
			$uquery_val[] = $array['background_color'];
		}
		
		if($array['background_repeat'])
		{
			$uquery_field[] = 'background_repeat';
			$uquery_val[] = $array['background_repeat'];
		}
		
		
		if(isset($_FILES['background_file']['name']))
		{
			$file = $Upload->upload_user_file('b',$_FILES['background_file'],$array['userid']);
			if($file)
			{
				$uquery_field[] = 'background';
				$uquery_val[] = $file;
			}
		}
		
		if(!error() && is_array($uquery_field))
		{
			$db->update(tbl($this->dbtbl['users']),$uquery_field,$uquery_val," userid='".mysql_clean($array['userid'])."'");
			e(lang("usr_upd_succ_msg"),'m');
		}
		
		
		
		//updating user profile
		if(!error())
		{
			$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "updated profile"
			);
			//Login Upload
			insert_log('profile_update',$log_array);
			
			$db->update(tbl($this->dbtbl['user_profile']),$query_field,$query_val," userid='".mysql_clean($array['userid'])."'");
			e(lang("usr_pof_upd_msg"),'m');
		}
	}
	
	
	/**
	 * Function used to update user avatar and background only
	 */
	function update_user_avatar_bg($array)
	{
		global $db,$signup,$Upload;
		//Updating User Avatar
		$uquery_field[] = 'avatar_url';
		$uquery_val[] = mysql_clean($array['avatar_url']);
	
		
		//Deleting User Avatar
		if($array['delete_avatar']=='yes')
		{
			$file = USER_THUMBS_DIR.'/'.$array['avatar_file_name'];
			if(file_exists($file) && $array['avatar_file_name'] !='')
				unlink($file);
		}
		
		//Deleting User Bg
		if($array['delete_bg']=='yes')
		{
			$file = USER_THUMBS_DIR.'/'.$array['bg_file_name'];
			if(file_exists($file) && $array['bg_file_name'] !='')
				unlink($file);
		}
		
		
		if(isset($_FILES['avatar_file']['name']))
		{
			$file = $Upload->upload_user_file('a',$_FILES['avatar_file'],$array['userid']);
			if($file)
			{
				$uquery_field[] = 'avatar';
				$uquery_val[] = $file;
			}
		}
		
		
		//Updating User Background
		$uquery_field[] = 'background_url';
		$uquery_val[] = mysql_clean($array['background_url']);
		
		$uquery_field[] = 'background_color';
		$uquery_val[] = mysql_clean($array['background_color']);
		
		if($array['background_repeat'])
		{
			$uquery_field[] = 'background_repeat';
			$uquery_val[] = mysql_clean($array['background_repeat']);
		}
		
		//Background ATtachement
		$uquery_field[] = 'background_attachement';
		$uquery_val[] = mysql_clean($array['background_attachement']);
		
		
		if(isset($_FILES['background_file']['name']))
		{
			
			$file = $Upload->upload_user_file('b',$_FILES['background_file'],$array['userid']);
			if($file)
			{
				$uquery_field[] = 'background';
				$uquery_val[] = mysql_clean($file);
			}
		}
		
		$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "updated profile"
			);
			//Login Upload
			insert_log('profile_update',$log_array);
			
		$db->update(tbl($this->dbtbl['users']),$uquery_field,$uquery_val," userid='".mysql_clean($array['userid'])."'");
		e(lang("usr_avatar_bg_update"),'m');

	}
	
	
	/**
	 * Function used to check weather username exists or not
	 */
	function username_exists($i)
	{
		global $db;
		$db->select(tbl($this->dbtbl['users']),"username"," username='$i'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	}
	
	/**
	 * function used to check weather email exists or not
	 */
	 function email_exists($i)
	{
		global $db;
		$db->select(tbl($this->dbtbl['users']),"email"," email='$i'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	}
	
	
	/**
	 * Function used to get user access log
	 */
	function get_user_action_log($uid,$limit=NULL)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['action_log']),"*"," action_userid='$uid'",$limit," date_added DESC");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Load Custom Profile Field
	 */
	function load_custom_profile_fields($array)
	{
		return false;
	}
	
	/**
	 * Load Custom Signup Field
	 */
	function load_custom_signup_fields($array)
	{
		return false;
	}
	
	
	/**
	 * Function used to get channel links
	 * ie Playlist, favorites etc etc
	 */
	function get_inner_channel_top_links($u)
	{
		return array(lang('upload')=>array('link'=>$this->get_user_videos_link($u)),
					 lang('favorites')=>array('link'=>cblink(array('name'=>'user_favorites')).$u['username']),
					 lang('contacts')=>array('link'=>cblink(array('name'=>'user_contacts')).$u['username']),
					 );
	}
	
	/**
	 * Function used to get user channel action links
	 * ie Add to friends, send message etc etc
	 */
	function get_channel_action_links($u)
	{
		return array(lang('Send Message')=>array('link'=>cblink(array('name'=>'compose_new','extra_params'=>'to='.$u['username']))),
					 lang('Add as friend')=>array('link'=>'javascript:void(0)','onclick'=>"add_friend('".$u['userid']."','result_cont')"),
					 lang('Block user')=>array('link'=>'javascript:void(0)','onclick'=>"block_user('".$u['username']."','result_cont')")
					 );
	}
	
	/**
	 * Function used to get user videos link
	 */
	function get_user_videos_link($u)
	{
		return cblink(array('name'=>'user_videos')).$u['username'];
	}	
	
	
	/**
	 * Function used to get user channel video
	 */
	function get_user_profile_video($u)
	{
		global $db,$cbvid;
		if(empty($u['profile_video'])&&!$cbvid->video_exists($u))
		{
			$u = $this->get_user_profile($u);
		}
		
		if($cbvid->video_exists($u['profile_video']))
			return $cbvid->get_video_details($u['profile_video']);
		else
			return false;
	}
	
	
	/**
	 * My Account links
	 */
	function my_account_links()
	{
		$array = array
		(
		 'Account'	=>array
		 			('My Account'	=> 'myaccount.php',
					 'Ban users'	=> 'edit_account.php?mode=ban_users',
					 'Change Password'	=>'edit_account.php?mode=change_password',
					 'Change Email' 	=>'edit_account.php?mode=change_email',
					 ),
		 'Profile'	=>array
		 			('Profile Settings'	=>'edit_account.php',
					 'Change Avatar' 	=> 'edit_account.php?mode=avatar_bg',
					 'Change Background' => 'edit_account.php?mode=avatar_bg',
					 ),
		'Videos' =>array
					(
					 'Uploaded Videos'=>'manage_videos.php',
					 'Favorite Videos'=>'manage_videos.php?mode=favorites',
					 ),
		'Groups' =>array
					(
					 'Manage Groups'=>'manage_groups.php',
					 'Create new group'=>cblink(array('name'=>'create_group')),
					 'Joined Groups'=>'manage_groups.php?mode=joined',
					 ),
		'Playlist'=>array
					(
					 'Manage Playlists'=>'manage_playlists.php',
					 'Video Playlists'=>'manage_playlists.php?mode=manage_video_playlist',
					 ),
		'Messages' => array
					(
					 'Inbox'	=> 'private_message.php?mode=inbox',
					 'Notifications' => 'private_message.php?mode=notification',
					 'Sent'	=> 'private_message.php?mode=sent',
					 'Compose New'=> cblink(array('name'=>'compose_new')),
					 ),
		'Contacts'	=>array
					(
					 'Manage contacts' => 'manage_contacts.php?mode=manage',
					 'Add new group'=> 'manage_contacts.php?mode=new_group',
					 )
		);
		
		return $array;
	}
	
	
	/**
	 * Function used to change email
	 */
	function change_email($array)
	{
		global $db;
		//function used to change user email
		if(!isValidEmail($array['new_email']) || $array['new_email']=='')
			e(lang("usr_email_err2"));
		elseif($array['new_email']!=$array['cnew_email'])
			e(lang('user_email_confirm_email_err'));
		elseif(!$this->user_exists($array['userid']))	
			e(lang('usr_exist_err'));
		else
		{
			$db->update(tbl($this->dbtbl['users']),array('email'),array($array['new_email'])," userid='".$array['userid']."'");
			e(lang("email_change_msg"),"m");
		}
	}
	
	/**
	 * Function used to ban users
	 */
	function ban_users($users,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid  = userid();
		$users_array = explode(',',$users);
		$new_users = array();
		foreach($users_array as $user)
		{
			if($user!=username() && !is_numeric($user) && $this->user_exists($user))
			{
				$new_users[] = $user;
			}
		}	
		if(count($new_users)>0)
		{
			$new_users = array_unique($new_users);
			$banned_users = implode(',',$new_users);
			$db->update(tbl($this->dbtbl['users']),array('banned_users'),array($banned_users)," userid='$uid'");
			e(lang("user_ban_msg"),"m");
		}else{
			e(lang("no_user_ban_msg"),"m");
		}
	}
	
	/**
	 * Function used to ban single user
	 */
	function ban_user($user)
	{
		global $db;
		$uid  = userid();
		if($user!=username() && !is_numeric($user) && $this->user_exists($user))
		{
			$banned_users = $this->udetails['banned_users'];
			if($banned_users)
				$banned_users .= ",$user";
			else
				$banned_users = "$user";
			
			if(!$this->is_user_banned($user))
			{
				$db->update(tbl($this->dbtbl['users']),array('banned_users'),array($banned_users)," userid='$uid'");
				e(lang("user_blocked"),"m");
			}else
				e(lang("user_already_blocked"));
		}else
		{
			e(lang("you_cant_del_user"));
		}
	}
	
	
	
	/**
	 * Function used to check weather user is banned or not
	 */
	function is_user_banned($ban,$user=NULL)
	{
		global $db;
		if(!$user)
			$user = userid();
		$result = $db->count(tbl($this->dbtbl['users']),"userid"," banned_users LIKE '%$ban%' AND (username='$user' OR userid='$user') ");
		if($result)
			return true;
		else
			return false;
	}
	
	/**
	 * function used to get user details with profile
	 */
	function get_user_details_with_profile($uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid = userid();
		$result = $db->select(tbl($this->dbtbl['users'].",".$this->dbtbl['user_profile']),"*",tbl($this->dbtbl['users']).".userid ='$uid' AND ".tbl($this->dbtbl['users']).".userid = ".tbl($this->dbtbl['user_profile']).".userid");
		return $result[0];
	}
	
	
	function load_signup_fields($default=NULL)
	{
		global $LANG,$Cbucket;
		/**
		 * this function will create initial array for user fields
		 * this will tell 
		 * array(
		 *       title [text that will represents the field]
		 *       type [type of field, either radio button, textfield or text area]
		 *       name [name of the fields, input NAME attribute]
		 *       id [id of the fields, input ID attribute]       
		 *       value [value of the fields, input VALUE attribute]
		 *       size
		 *       class
		 *       label
		 *       extra_params
		 *       hint_1 [hint before field]
		 *       hint_2 [hint after field]
		 *       anchor_before [anchor before field]
		 *       anchor_after [anchor after field]
		 *      )
		 */
		 
		
		if(empty($default))
			$default = $_POST;
			
		$username = $default['username'];
		$email = $default['email'];
		$dcountry = $default['country'] ? $default['country'] : $Cbucket->configs['default_country_iso2'];
		$dob = $default['dob'];
	
			
		 $dob =  $dob ? date("d-m-Y",strtotime($dob)) : '14-14-1989';
		 
		 $user_signup_fields = array
		 (
		  'username' => array(
							  'title'=> lang('username'),
							  'type'=> "textfield",
							  'name'=> "username",
							  'id'=> "username",
							  'value'=> $username,
							  'hint_2'=> lang('user_allowed_format'),
							  'db_field'=>'username',
							  'required'=>'yes',
							  'syntax_type'=> 'username',
							  'validate_function'=> 'username_check',
							  'function_error_msg' => lang('user_contains_disallow_err'),
							  'db_value_check_func'=> 'user_exists',
							  'db_value_exists'=>false,
							  'db_value_err'=>lang('usr_uname_err2')
							  ),
		  'email' => array(
							  'title'=> lang('email'),
							  'type'=> "textfield",
							  'name'=> "email",
							  'id'=> "email",
							  'value'=> $email,
							  'db_field'=>'email',
							  'required'=>'yes',
							  'syntax_type'=> 'email',
							  'db_value_check_func'=> 'email_exists',
							  'db_value_exists'=>false,
							  'db_value_err'=>lang('usr_email_err3')
							  ),
		  'password' => array(
							  'title'=> lang('password'),
							  'type'=> "password",
							  'name'=> "password",
							  'id'=> "password",
							  'db_field'=>'password',
							  'required'=>'yes',
							  'invalid_err'=>lang('usr_pass_err2'),
							  'relative_to' => 'cpassword',
							  'relative_type' => 'exact',
							  'relative_err' => lang('usr_pass_err3'),
							  'validate_function' => 'pass_code',
							  'use_func_val'=>true
							  ),
		  'cpassword' => array(
							  'title'=> lang('user_confirm_pass'),
							  'type'=> "password",
							  'name'=> "cpassword",
							  'id'=> "cpassword",
							  'required'=>'no',
							  'invalid_err'=>lang('usr_cpass_err'),
							  ),
		  'country'	=> array(
							 'title'=> lang('country'),
							 'type' => 'dropdown',
							 'value' => $Cbucket->get_countries(iso2),
							 'id'	=> 'country',
							 'name'	=> 'country',
							 'checked'=> $dcountry,
							 'db_field'=>'country',
							 'required'=>'yes',
							 ),
		  'gender' => array(
							'title' => lang('gender'),
							'type' => 'radiobutton',
							'name' => 'gender',
							'id' => 'gender',
							'value' => array('Male'=>lang('male'),'Female'=>lang('female')),
							'sep'=> '&nbsp;',
							'checked'=>'Male',
							'db_field'=>'sex',
							'required'=>'yes',
							),
		  'dob'	=> array(
						 'title' => lang('user_date_of_birth'),
						 'type' => 'textfield',
						 'name' => 'dob',
						 'id' => 'dob',
						 'class'=>'date_field',
						 'anchor_after' => 'date_picker',
						 'value'=> $dob,
						 'db_field'=>'dob',
						 'required'=>'yes',
						 ),
		  'cat'		=> array('title'=> lang('Category'),
							 'type'=> 'dropdown',
							 'name'=> 'category',
							 'id'=> 'category',
							 'value'=> array('category',$default['category']),
							 'db_field'=>'category',
							 'checked'=>$default['category'],
							 'required'=>'yes',
							 'invalid_err'=>lang("Please select your category"),
							 'display_function' => 'convert_to_categories',
							 'category_type'=>'user',
							 )
		  );

		 return $user_signup_fields;
	}
	
	
	/**
	 * Function used to validate Signup Form
	 */
	function validate_form_fields($array=NULL)
	{
		global $userquery;
		$fields = $this->load_signup_fields($array);

		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);

		//Mergin Array
		$signup_fields = array_merge($fields,$this->custom_signup_fields);
		
		validate_cb_form($signup_fields,$array);
		
	}
	
	
	/**
	 * Function used to validate signup form
	 */
	function signup_user($array=NULL)
	{
		global $LANG,$db,$userquery;
		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
		$this->validate_form_fields($array);
		
		//checking terms and policy agreement
		if($array['agree']!='yes' && !has_access('admin_access',true))
			e(lang('usr_ament_err'));
		
		if(!verify_captcha())
					e(lang('usr_ccode_err'));
		if(!error())
		{
			$signup_fields = $this->load_signup_fields($array);
			
			//Adding Custom Signup Fields
			if(count($this->custom_signup_fields)>0)
				$signup_fields = array_merge($signup_fields,$this->custom_signup_fields);
			
			foreach($signup_fields as $field)
			{
				$name = formObj::rmBrackets($field['name']);
				$val = $array[$name];
				
				if($field['use_func_val'])
					$val = $field['validate_function']($val);
				
				
				if(!empty($field['db_field']))
				$query_field[] = $field['db_field'];
				
				if(is_array($val))
				{
					$new_val = '';
					foreach($val as $v)
					{
						$new_val .= "#".$v."# ";
					}
					$val = $new_val;
				}
				if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
					$val = mysql_clean($val);
				else
					$val = apply_func($field['clean_func'],$val);
				
				if(!empty($field['db_field']))
				$query_val[] = $val;
				
			}
			
			// Setting Verification type
			if(EMAIL_VERIFICATION == '1'){
				$usr_status = 'ToActivate';
				$welcome_email = 'no';
			}else{
				$usr_status = 'Ok';
				$welcome_email = 'yes';
			}
			
			if(has_access('admin_access',true))
			{
				if($array['active']=='Ok')
				{
					$usr_status = 'Ok';
					$welcome_email = 'yes';
				}else{
					$usr_status = 'ToActivate';
					$welcome_email = 'no';
				}
				
				$query_field[] = "level";
				$query_val[] = $array['level'];
			}

			$query_field[] = "usr_status";
			$query_val[] = $usr_status;

			$query_field[] = "	welcome_email_sent";
			$query_val[] = $welcome_email;
			
			//Creating AV Code
			$avcode		= RandomString(10);
			$query_field[] = "avcode";
			$query_val[] = $avcode;
			
			
			
			//Signup IP
			$signup_ip	= $_SERVER['REMOTE_ADDR'];
			$query_field[] = "signup_ip";
			$query_val[] = $signup_ip;
			
			//Date Joined
			$now = NOW();
			$query_field[] = "doj";
			$query_val[] = $now;
			
			
			/**
			 * A VERY IMPORTANT PART OF
			 * OUR SIGNUP SYSTEM IS
			 * SESSION KEY AND CODE
			 * WHEN A USER IS LOGGED IN
			 * IT IS ONLY VALIDATED BY
			 * ITS SIGNUP KEY AND CODE 
			 *
			 */
			$sess_key = $this->create_session_key($_COOKIE['PHPSESSID'],$array['password']);
			$sess_code = $this->create_session_code();
			
			$query_field[] = "user_session_key";
			$query_val[] = $sess_key;
			
			$query_field[] = "user_session_code";
			$query_val[] = $sess_code;
			
			$query = "INSERT INTO ".tbl("users")." (";
			$total_fields = count($query_field);
			
			//Adding Fields to query
			$i = 0;
			foreach($query_field as $qfield)
			{
				$i++;
				$query .= $qfield;
				if($i<$total_fields)
				$query .= ',';
			}
			
			$query .= ") VALUES (";
			
			$i = 0;
			//Adding Fields Values to query
			foreach($query_val as $qval)
			{
				$i++;
				$query .= "'$qval'";
				if($i<$total_fields)
				$query .= ',';
			}
			
			//Finalzing Query
			$query .= ")";
			
			$db->Execute($query);
			$insert_id = $db->insert_id();
			$db->insert(tbl($userquery->dbtbl['user_profile']),array("userid"),array($insert_id));
			
			if(!has_access('admin_access',true) && EMAIL_VERIFICATION)
			{
				global $cbemail;
				$tpl = $cbemail->get_template('email_verify_template');
				$more_var = array
				('{username}'	=> post('username'),
				 '{password}'	=> post('password'),
				 '{email}'		=> post('email'),
				 '{avcode}'		=> $avcode,
				);
				if(!is_array($var))
					$var = array();
				$var = array_merge($more_var,$var);
				$subj = $cbemail->replace($tpl['email_template_subject'],$var);
				$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
				
				//Now Finally Sending Email
				cbmail(array('to'=>post('email'),'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));
			}
			elseif(!has_access('admin_access',true))
			{
				$this->send_welcome_email($insert_id);
			}
			
			$log_array = array
			('username'	=> $array['username'],
			 'userid'	=> $insert_id,
			 'userlevel'=> $array['level'],
			 'useremail'=> $array['email'],
			 'success'=>'yes',
			 'details'=> sprintf("%s signed up",$array['username']));
			 
			//Login Signup
			insert_log('signup',$log_array);
			
			return $insert_id;
		}
		
		return false;
	}
	
	
	
		
	//Duplicate User Check
	function duplicate_user($name){
		global $myquery;
		if($myquery->check_user($name)){
			return true;
		}else{
			return false;
		}
	}
	
	function duplicate_email($name){
		$myquery =  new myquery();
		if($myquery->check_email($name)){
		return true;
		}else{
		return false;
		}
	}
	
	//Validate Email
	
	function isValidEmail($email){
      return isValidEmail($email);
   }
	
	//Validate Username
	function isValidUsername($uname){
		return $this->is_username($uname);
	}
   
    /**
	  * Function used to make username valid
	  * this function will also check if username is banned or not
	  * it will also filter the username and also filter its patterns
	  * as given in administratio panel
	  */
	 function is_username($username)
	 {
		 global $Cbucket;
		//Our basic pattern for username is
		//$pattern = "^^[_a-z0-9-]+$";
		$pattern = "^^[_a-z0-9-]+$"; 
		//Now we will check if admin wants to change the pattern
		if (eregi($pattern, $username)){
			return true;
		}else {
			return false;
		}  
		
	 }
	
	
	/**
	 * Function used to get users
	 */
	function get_users($params=NULL,$force_admin=FALSE)
	{
		global $db;
		
		$limit = $params['limit'];
		$order = $params['order'];
		
		$cond = "";
		if(!has_access('admin_access',TRUE) && !$force_admin)
			$cond .= " 	usr_status='Ok' AND ban_status ='no' ";
		else
		{
			if($params['ban'])
				$cond .= " ban_status ='".$params['ban']."'";
				
			if($params['status'])
			{
				if($cond!='')
					$cond .=" AND ";
				$cond .= " 	usr_status='".$params['status']."'";
			}
		}
		
		//Setting Category Condition
		if(!is_array($params['category']))
			$is_all = strtolower($params['category']);
			
		if($params['category'] && $is_all!='all')
		{
			if($cond!='')
				$cond .= ' AND ';
				
			$cond .= " (";
			
			if(!is_array($params['category']))
			{
				$cats = explode(',',$params['category']);
			}else
				$cats = $params['category'];
				
			$count = 0;
			
			foreach($cats as $cat_params)
			{
				$count ++;
				if($count>1)
				$cond .=" OR ";
				$cond .= " category LIKE '%$cat_params%' ";
			}
			
			$cond .= ")";
		}
		
		//date span
		if($params['date_span'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".cbsearch::date_margin("doj",$params['date_span']);
		}
		
		/*//uid 
		if($params['user'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " userid='".$params['user']."'";
		}
		
		$tag_n_title='';
		//Tags
		if($params['tags'])
		{
			//checking for commas ;)
			$tags = explode(",",$params['tags']);
			if(count($tags)>0)
			{
				if($tag_n_title!='')
					$tag_n_title .= ' OR ';
				$total = count($tags);
				$loop = 1;
				foreach($tags as $tag)
				{
					$tag_n_title .= " tags LIKE '%".$tag."%'";
					if($loop<$total)
					$tag_n_title .= " OR ";
					$loop++;
					
				}
			}else
			{
				if($tag_n_title!='')
					$tag_n_title .= ' OR ';
				$tag_n_title .= " tags LIKE '%".$params['tags']."%'";
			}
		}
		//TITLE
		if($params['title'])
		{
			if($tag_n_title!='')
				$tag_n_title .= ' OR ';
			$tag_n_title .= " title LIKE '%".$params['tags']."%'";
		}
		
		if($tag_n_title)
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ($tag_n_title) ";
		}*/
		
		//FEATURED
		if($params['featured'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " featured = '".$params['featured']."' ";
		}
		
		//Email
		if($params['email'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " email = '".$params['email']."' ";
		}
		
		//Exclude Users
		if($params['exclude'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " userid <> '".$params['exclude']."' ";
		}
		
		//Getting specific User
		if($params['userid'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " userid = '".$params['userid']."' ";
		}
		
		//Sex
		if($params['gender'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " sex = '".$params['gender']."' ";
		}
		
		//Level
		if($params['level'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " level = '".$params['level']."' ";
		}
		
		$result = $db->select(tbl('users'),'*',$cond,$limit,$order);
		
		
		if($params['count_only'])
			return $result = $db->count(tbl('users'),'*',$cond);
		if($params['assign'])
			assign($params['assign'],$result);
		else
			return $result;
	}
	
	
	
	
	/**
	 * Function used to perform several actions with a video
	 */
	function action($case,$uid)
	{
		global $db;
		if(!$this->user_exists($uid))
			return false;
		//Lets just check weathter user exists or not
		$tbl = tbl($this->dbtbl['users']);
		switch($case)
		{
			//Activating a user
			case 'activate':
			case 'av':
			case 'a':
			{
				$avcode = RandomString(10);
				$db->update($tbl,array('usr_status','avcode'),array('Ok',$avcode)," userid='$uid' ");
				e(lang("User has been activated"),m);
			}
			break;
			
			//Deactivating a user
			case "deactivate":
			case "dav":
			case "d":
			{
				$avcode = RandomString(10);
				$db->update($tbl,array('usr_status','avcode'),array('ToActivate',$avcode)," userid='$uid' ");
				e(lang("User has been deactivated"),m);
			}
			break;
			
			//Featuring user
			case "feature":
			case "featured":
			case "f":
			{
				$db->update($tbl,array('featured','featured_date'),array('yes',now())," userid='$uid' ");
				e(lang("User has been set as featured"),m);
			}
			break;
			
			
			//Unfeatured user
			case "unfeature":
			case "unfeatured":
			case "uf":
			{
				$db->update($tbl,array('featured'),array('no')," userid='$uid' ");
				e(lang("User has been removed from featured users"),m);
			}
			break;
			
			//Ban User
			case "ban":
			case "banned":
			{
				$db->update($tbl,array('ban_status'),array('yes')," userid='$uid' ");
				e(lang("User has been banned"),m);
			}
			break;
			
			
			//Ban User
			case "unban":
			case "unbanned":
			{
				$db->update($tbl,array('ban_status'),array('no')," userid='$uid' ");
				e(lang("User has been unbanned"),m);
			}
			break;
		}
	}
	
	
	/**
	 * Is Registeration allowed
	 */
	 function is_registeration_allowed()
	 {
		if(ALLOW_REGISTERATION == 1 )
			return true;
		else
			return false;
	 }
	 
	/**
	 * Function used to use to initialize search object for video section
	 * op=>operator (AND OR)
	 */
	function init_search()
	{
		$this->search = new cbsearch;
		$this->search->db_tbl = "users";
		$this->search->columns =array(
			array('field'=>'username','type'=>'LIKE','var'=>'%{KEY}%'),
		);
		$this->search->cat_tbl = $this->cat_tbl;

		$this->search->display_template = LAYOUT.'/blocks/user.html';
		$this->search->template_var = 'user';
		$this->search->multi_cat = false;
		$this->search->date_added_colum = 'doj';
		
		/**
		 * Setting up the sorting thing
		 */
		
		$sorting	= 	array(
						'doj'	=> lang("date_added"),
						'profile_hits'		=> lang("views"),
						'total_comments'  => lang("comments"),
						'total_videos' 	=> lang("videos"),
						);
		
		$this->search->sorting	= array(
						'doj'=> " doj DESC",
						'profile_hits'		=> " profile_hits DESC",
						'total_comments'  => " total_comments DESC ",
						'total_videos' 	=> " total_videos DESC",
						);
		/**
		 * Setting Up The Search Fields
		 */
		 
		$default = $_GET;
		if(is_array($default['category']))
			$cat_array = array($default['category']);		
		$uploaded = $default['datemargin'];
		$sort = $default['sort'];
		
		$this->search->search_type['users'] = array('title'=>lang('users'));
		
		$fields = array(
		'query'	=> array(
						'title'=> lang('keywords'),
						'type'=> 'textfield',
						'name'=> 'query',
						'id'=> 'query',
						'value'=>cleanForm($default['query'])
						),
		'category'	=>  array(
						'title'		=> lang('category'),
						'type'		=> 'checkbox',
						'name'		=> 'category[]',
						'id'		=> 'category',
						'value'		=> array('category',$cat_array),
						'category_type'=>'user',
						),
		'date_margin'	=>  array(
						'title'		=> lang('joined'),
						'type'		=> 'dropdown',
						'name'		=> 'datemargin',
						'id'		=> 'datemargin',
						'value'		=> $this->search->date_margins(),
						'checked'	=> $uploaded,
						),
		'sort'		=> array(
						'title'		=> lang('sort_by'),
						'type'		=> 'dropdown',
						'name'		=> 'sort',
						'value'		=> $sorting,
						'checked'	=> $sort
							)
		);

		$this->search->search_type['users']['fields'] = $fields;
	}
	
	
	
	/**
	  * Function used to get number of users online
	  */
	 function get_online_users()
	 {
		 global $db;
		 $pattern = date("Y-m-s H:i:s");
		 $results =  $db->select(tbl("users"),'*'," TIMESTAMPDIFF(MINUTE,last_active,'".NOW()."')  < 6 ");
		
	 	 return $results;
	 }
	 
	 
	 
	 
	/**
	 * Function will let admin to login as user
	 */
	function login_as_user($id)
	{
		global $sess;
		$udetails = $this->get_user_details($id);
		if($udetails)
		{
			$sess->set('dummy_username',$sess->get("username"));
			$sess->set('dummy_level',$sess->get("level"));
			$sess->set('dummy_userid',$sess->get("userid"));
			$sess->set('dummy_user_session_key',$sess->get("user_session_key"));
			$sess->set('dummy_user_session_code',$sess->get("user_session_code"));
			
			$sess->set('username',$udetails['username']);
			$sess->set('level',$udetails['level']);
			$sess->set('userid',$udetails['userid']);
			$sess->set('user_session_key',$udetails['session_key']);
			$sess->set('user_session_code',$udetails['session_code']);
			
			
			
			return true;
		}else
			e(lang("usr_exist_err"));
	}
	
	/**
	 * Function used to revert back to admin
	 */
	function revert_from_user()
	{
		global $sess;
		if($this->is_admin_logged_as_user())
		{
			$sess->set('username',$sess->get("dummy_username"));
			$sess->set('level',$sess->get("dummy_level"));
			$sess->set('userid',$sess->get("dummy_userid"));
			$sess->set('user_session_key',$sess->get("dummy_user_session_key"));
			$sess->set('user_session_code',$sess->get("dummy_user_session_code"));
			
			$sess->un_set('dummy_username');
			$sess->un_set('dummy_level');
			$sess->un_set('dummy_userid');
			$sess->un_set('dummy_user_session_key');
			$sess->un_set('dummy_user_session_code');
		}
	}
	
	/**
	 * Function used to check weather user is logged in as admin or not
	 */
	function is_admin_logged_as_user()
	{
		 global $sess;
		 if($sess->get("dummy_username")!="")
		 {
			 return true;
		 }
		return false;
	}
	
	
	/**
	 * Function used to get anonymous user
	 */
	function get_anonymous_user()
	{
		$uid = config('anonymous_id');
		if($this->user_exists($uid))
			return $uid;
		else
		{
			$result = $db->select(tbl("users"),"userid"," level='6' ");
			return $result[0]['userid'];
		}
	}
	
	
	/**
	 * Function used to delete user videos
	 */
	function delete_user_vids($uid)
	{
		global $cbvid,$eh;
		$vids = get_videos(array('user'=>$uid));
		foreach($vids as $vid)
			$cbvid->delete_video($vid['videoid']);
		$eh->flush_msg();
		e(lang("user_vids_hv_deleted"),"m");	
	}
	
	/**
	 * Function used to remove user contacts
	 */
	function remove_contacts($uid)
	{
		global $eh;
		$contacts = $this->get_contacts($uid);
		if(is_array($contacts))
		foreach($contacts as $contact)
		{
			$this->remove_contact($contact['userid'],$contact['contact_userid']);
		}
		$eh->flush_msg();
		e(lang("user_contacts_hv_removed"),"m");
	}
	
	/**
	 * Function used to remove user private messages
	 */
	function remove_user_pms($uid,$box='both')
	{
		global $db,$cbpm,$eh;
		
		if($box=="inbox" || $box=="both")
		{
			$inboxs = $cbpm->get_user_inbox_messages($uid);
			if(is_array($inboxs))
			foreach($inboxs as $inbox)
			{
				$cbpm->delete_msg($inbox['message_id'],$uid);
			}
			$eh->flush_msg();
			e(lang("all_user_inbox_deleted"),"m");
		}
		if($box=="sent" || $box=="both")
		{
			$outs = $cbpm->get_user_outbox_messages($uid);
			if(is_array($outs))
			foreach($outs as $out)
			{
				$cbpm->delete_msg($out['message_id'],$uid,'out');
			}
			$eh->flush_msg();
			e(lang("all_user_sent_messages_deleted"),"m");

		}		
	}
}
?>