<?php

/**
 * This class is used to update clipbucket daily stats
 */

//including config file..
include("../includes/config.inc.php");

//Now Gathering All Data
$date = date("Y-m-d");

//Videos
$videos['uploads'] = $cbvid->get_videos(array("count_only"=>true,"date_span"=>"today"));
$videos['processing'] = $cbvid->get_videos(array("count_only"=>true,"status"=>"Processing","date_span"=>"today"));
$videos['active'] = $cbvid->get_videos(array("count_only"=>true,"active"=>"yes","date_span"=>"today"));
//Views
$vid_views = $db->select("video","SUM(views) as total_views"," date_added LIKE '%$date%'");
$videos['views'] = $vid_views[0]['total_views'];
//Total Comments
$vid_comments = $db->select("video","SUM(comments_count) as total_comments"," date_added LIKE '%$date%'");
$videos['comments'] = $vid_comments[0]['total_comments'];


/**
 * Testing
 * echo json_encode($videos);
 * PASSED
 */

//Users
$users['signups'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"today"));
$users['inactive'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"today","status"=>'ToActivate'));
$users['active'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"today","status"=>'Ok'));
//Views
$user_views = $db->select("users","SUM(profile_hits) as total_views"," doj LIKE '%$date%'");
$users['views'] = $user_views[0]['total_views'];
//Total Comments
$user_comments = $db->select("users","SUM(comments_count) as total_comments"," doj LIKE '%$date%'");
$users['comments'] = $user_comments[0]['total_comments'];


/**
 * Testing
 * echo json_encode($users);
 * PASSED
 */
 
 
//Groups
$groups['created'] = $cbgroup->get_groups(array("count_only"=>true,"date_span"=>"today"));
$groups['active'] = $cbgroup->get_groups(array("count_only"=>true,"date_span"=>"today","active"=>"yes"));
//Total Views
$group_views = $db->select("groups","SUM(total_views) as the_views"," date_added LIKE '%$date%'");
$groups['views'] = $group_views[0]['the_views'];
//Total Discussion
$group_topics = $db->select("groups","SUM(total_topics) as the_topics"," date_added LIKE '%$date%'");
$groups['total_topics'] = $group_topics[0]['the_topics'];
//TOtal Comments
$group_discussions = $db->select("group_topics","SUM(total_replies) as the_discussions"," date_added LIKE '%$date%'");
$groups['total_discussions'] = $group_discussions[0]['the_discussions'];

/**
 * TESTING GROUPS
 * echo json_encode($groups);
 * PASSED 
 */

$fields = array('video_stats','user_stats','group_stats');
$values = array('|no_mc|'.json_encode($videos),'|no_mc|'.json_encode($users),'|no_mc|'.json_encode($groups));

//Checking If there is already a row of the same date, then update it otherwise insert data
$result = $db->select("cb_stats","stat_id"," date_added LIKE '%$date%'");
if($db->num_rows>0)
{
	$result = $result[0];
	$db->update('cb_stats',$fields,$values," stat_id='".$result['stat_id']."'");
}else
{
	$fields[] = 'date_added';
	$values[] = $date;
	$db->insert('cb_stats',$fields,$values);
}
?>