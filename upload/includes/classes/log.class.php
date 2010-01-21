<?php

/**
 * This class is used to keep details of each action done on a website using Clipbucket
 * this will manage log of each possible action 
 * @ Author : Arslan Hassan
 * @ since : June 14, 2009
 * @ ClipBucket : v2.x
 * @ license : CBLA
 */


class CBLogs
{
	
	
	/**
	 * Function used to insert log
	 * @param VARCHAR $type, type of action
	 * @param ARRAY $details_array , action details array
	 */
	function insert($type,$details_array)
	{
		global $db,$userquery;
		$a = $details_array;
		$ip = $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$userid = $a['userid'];
		$username = $a['username'];
		$useremail = $a['useremail'];
		$userlevel = $a['userlevel'];
		
		$userid = $userid ? $userid : $userquery['userid'];
		$username = $username ? $username : $userquery['username'];
		$useremail = $useremail ? $useremail : $userquery['email'];
		$userlevel = $userlevel ? $userlevel : $userquery['level'];
		
		$success = $a['success'];
		$details = $a['details'];
		 
		$db->insert('action_log',
		array
		(
		'action_type',
		'action_username',
		'action_userid',
		'action_useremail',
		'action_ip',
		'date_added',
		'action_success',
		'action_details',
		'action_userlevel',
		),
		array
		(
		$type,
		$username,
		$userid ,
		$useremail,
		$ip,
		NOW(),
		$success,
		$details,
		$userlevel
		)
		);
					  
	 }
	 
}

?>