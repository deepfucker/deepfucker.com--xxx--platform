<?php
/* 
 *******************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com & (Arslan Hassan). All rights reserved.
 | @ Author : ArslanHassan
 | @ Software : ClipBucket , © PHPBucket.com
 *******************************************
*/

require_once '../includes/admin_config.php';

//Form Processing
if(isset($_POST['add_cateogry'])){
	$groups->add_category($_POST);
}

//Making Categoyr as Default
if(isset($_GET['make_default']))
{
	$cid = mysql_clean($_GET['make_default']);
	$groups->make_default_category($cid);
}

//Edit Categoty
if(isset($_GET['category'])){
	assign("edit_category","show");
	if(isset($_POST['update_category']))
	{
		$groups->update_category($_POST);
	}
	assign('cat_details',$groups->get_category($_GET['category']));
}

//Delete Category
if(isset($_GET['delete_category'])){
	$groups->delete_category($_GET['delete_category']);
}
	
//Assing Category Values
assign('category',$groups->get_categories());
assign('total',$groups->total_categories());

	
template_files('group_category.html'); display_it();

?>