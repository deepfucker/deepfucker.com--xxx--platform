<?php
/* 
 *************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , � PHPBucket.com					
 *************************************************************
*/
require 'includes/config.inc.php';
$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
assign('p',$userquery->get_user_profile($udetails['userid']));

$gid = mysql_clean($_GET['gid']);
//get group details
$gdetails = $cbgroup->get_group_details($gid);

if($gdetails['userid'] != userid())
{
	e("You cannot edit this group");
	$Cbucket->show_page = false;
}else{
	
	//Updating Video Details
	if(isset($_POST['update_group']))
	{
		$_POST['group_id'] = $gid;
		$cbgroup->update_group();
		$gdetails = $cbgroup->get_group_details($gid);

	}
	
	assign('group',$gdetails);
}

template_files('edit_group.html');
display_it();
?>