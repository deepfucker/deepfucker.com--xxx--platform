<?php
/**
 * ALL LINKS ARE DEFINED HERE
 * YOU CAN CHANGE THEM IF REQUIRED
 * ARRAY( [name]=> Array([Non SEO Link], [SEO Link])) - Without BASEURL
 */

$Cbucket->links  = array
(
'channels'	=>array('channels.php','channels'),
'create_group'=>array('create_group.php','create_group'),
'groups'	=>array('groups.php','groups'),
'inbox'		=>array('private_message.php?mode=inbox','private_message.php?mode=inbox'),
'login'		=>array('signup.php','login'),
'logout'	=>array('logout.php','logout'),
'my_account'=>array('myaccount.php','my_account'),
'my_videos'	=>array('manage_videos.php','manage_videos.php'),
'notifications'=>array('private_message.php?mode=notification','private_message.php?mode=notification'),
'signup'	=>array('signup.php','signup'),
'upload'	=>array('upload.php','upload'),
'videos'	=>array('videos.php','videos'),

);


/**
 * Sortings
 */
function sorting_links()
{
	$array = array
	('most_recent' 	=> 'Recent',
	 'most_viewed'	=> 'Viewed',
	 'featured'		=> 'Featured',
	 'top_rated'	=> 'Top Rated',
	 'most_commented'	=> 'Commented',
	 );
	return $array;
}

function time_links()
{
	$array = array
	('all_time' 	=> 'All Time',
	 'today'		=> 'Today',
	 'yesterday'	=> 'Yesterday',
	 'this_week'	=> 'This Week',
	 'last_week'	=> 'Last Week',
	 'this_month'	=> 'This Month',
	 'last_month'	=> 'Last Month',
	 'this_year'	=> 'This Year',
	 'last_year'	=> 'Last Year',
	 );
	return $array;
}
?>