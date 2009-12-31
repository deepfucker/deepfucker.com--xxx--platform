<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

define("THIS_PAGE",'channels');
define("PARENT_PAGE",'channels');

require 'includes/config.inc.php';
$userquery->perm_check('view_channel',true);

//Setting Sort
$sort = $_GET['sort'];
$u_cond = array('category'=>mysql_clean($_GET['cat']),'date_span'=>$_GET['time']);

switch($sort)
{
	case "most_recent":
	default:
	{
		$u_cond['order'] = " doj DESC ";
	}
	break;
	case "most_viewed":
	{
		$u_cond['order'] = " profile_hits DESC ";
	}
	break;
	case "featured":
	{
		$u_cond['order'] = "yes";
	}
	break;
	case "top_rated":
	{
		$u_cond['order'] = " rating DESC";
	}
	break;
	case "most_commented":
	{
		$u_cond['order'] = " total_comments DESC";
	}
	break;
}

//Getting User List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,CLISTPP);
$ulist = $u_cond;
$ulist['limit'] = $get_limit;
$users = get_users($ulist);
Assign('users', $users);	

//Collecting Data for Pagination
$ucount = $u_cond;
$ucount['count_only'] = true;
$total_rows  = get_users($ucount);
$total_pages = count_pages($total_rows,CLISTPP);

//Pagination
$pages->paginate($total_pages,$page);

subtitle(lang('channels'));
template_files('channels.html');
display_it();
?>