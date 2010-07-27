<?php
/* 
 ****************************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.					
 | @ Author : ArslanHassan														
 | @ Software : ClipBucket , � PHPBucket.com									
 ****************************************************************************
*/

require 'includes/config.inc.php';
$pages->page_redir();

$tid = $_GET['tid'];
$tdetails = $cbgroup->get_topic_details($tid);

if($tdetails)
{
	$grp_details = $cbgroup->get_group_details($tdetails['group_id']);
	if($grp_details['post_type'] == 1 && !$cbgroup->is_member(userid(),$grp_details['group_id'],TRUE)) {
		if($cbgroup->is_member(userid(),$grp_details['group_id']))
			e(lang("You are still unactive. Please contact group owner if you think a handsome ammount of time have passed since you joined."));			
		else
			e(lang("To view topics, please join group."));
		$Cbucket->show_page = false;
	} else {
		assign('group',$grp_details);
		assign('topic',$tdetails);
		subtitle($grp_details['group_name']);
		//Calling all functions when a topic is called
		call_view_topic_functions($tdetails);
	}
}

template_files('view_topic.html');
display_it();
?>