<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , � PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$pages->page_redir();
$userquery->login_check('admin_access');

//Removing Placement
if(isset($_GET['remove'])){
	$placement = mysql_clean($_GET['remove']);
	$msg =$ads_query->RemovePlacement($placement); 
}

//Adding Placement
if(isset($_POST['AddPlacement'])){
	$placement_name = mysql_clean($_POST['placement_name']);
	$placement_code = mysql_clean($_POST['placement_code']);
	$array = array($placement_name,$placement_code);
	$msg = $ads_query->AddPlacement($array);
}

//Getting List Of Placement
$sql = "SELECT * FROM ads_placements";
$ads_exec = $db->Execute($sql);
$ads_placements = $ads_exec->getrows();
$total_placements = $ads_exec->recordcount() + 0;
	//Getting total Ads in each placement
		for($id=0;$id<=$total_placements;$id++){
			$query = mysql_query("SELECT * FROM ads_data WHERE ad_placement='".@$ads_placements[$id]['placement']."'");
			$ads_placements[$id]['total_ads'] = mysql_num_rows($query);
		}
				
Assign('ads_placements',$ads_placements);
	
	
subtitle("Add Advertisment Placement");
template_files('ads_add_placements.html');
display_it();

?>