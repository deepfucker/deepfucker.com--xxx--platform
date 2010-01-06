<?php
/* 
 *******************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com & (Arslan Hassan). All rights reserved.
 | @ Author : ArslanHassan
 | @ Software : ClipBucket , © PHPBucket.com
 *******************************************
*/

require_once '../includes/admin_config.php';
$userquery->login_check('admin_access');
$pages->page_redir();


//Form Processing
if(isset($_POST['add_cateogry'])){
	$userquery->add_category($_POST);
}

//Making Categoyr as Default
if(isset($_GET['make_default']))
{
	$cid = mysql_clean($_GET['make_default']);
	$userquery->make_default_category($cid);
}

//Edit Categoty
if(isset($_GET['category'])){
	assign("edit_category","show");
	if(isset($_POST['update_category']))
	{
		$userquery->update_category($_POST);
	}
	assign('cat_details',$userquery->get_category($_GET['category']));
}

//Delete Category
if(isset($_GET['delete_category'])){
	$userquery->delete_category($_GET['delete_category']);
}
	
//Assing Category Values
assign('category',$userquery->get_categories());
assign('total',$userquery->total_categories());

Assign('msg',@$msg);	
template_files('user_category.html');
display_it();

?>