<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->login_check('video_moderation');
$pages->page_redir();

$file_name = mysql_clean($_GET['file_name']);
$file_details = $myquery->file_details($file_name);
if($file_details)
{
	assign('data',$file_details);
}

subtitle("Conversion Log");
template_files('view_conversion_log.html');
display_it();
?>