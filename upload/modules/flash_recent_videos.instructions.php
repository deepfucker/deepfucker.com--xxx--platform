<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Module : Flash Recent Videos																	|
 | @ Module File : flash_recent_videos.instructions.php {INSTRUCTIONS FILE}							|
 | @ Date : Jan, 02 2008																			|
 | @ License: Addon With ClipBucket																	|
 ****************************************************************************************************
*/
		
        $code = 
			'<div id="flash_recent_videos">
			This content requires JavaScript and Macromedia Flash Player 7 or higher. <a href=http://www.macromedia.com/go/getflash/>Get Flash</a><br/><br/>
			</div>
			<script type="text/javascript">
				<!--
     			var player = new FlashObject("'.BASEURL.'/modules/flash_recent_videos.swf?x='.BASEURL.'/modules/flash_recent_videos.php&amp;t='.$LANG['videos_being_watched'].'", "base", "550", "140", "6", "#FFFFFF");				
				player.write("flash_recent_videos");	
				//-->
			</script>	';
		
		Assign('flash_recent_videos',$code);
	
?>