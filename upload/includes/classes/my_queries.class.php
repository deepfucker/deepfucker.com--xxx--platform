<?php
/**
***************************************************************************************************
 * @Software    ClipBucket
 * @Authoer     ArslanHassan
 * @copyright	Copyright (c) 2007-2009 {@link http://www.clip-bucket.com}
 * @license		http://www.clip-bucket.com
 * @version		Lite
 * @since 		2007-10-15
 * @License		CBLA
 **************************************************************************************************
 This Source File Is Written For ClipBucket, Please Read its End User License First and Agree its
 Terms of use at http://clip-bucket.com/cbla
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
 **/
 
class myquery {

	function Set_Website_Details($name,$value){
		//mysql_query("UPDATE config SET value = '".$value."' WHERE name ='".$name."'");
		global $db;
		$db->update("config",array('value'),array($value)," name = '".$name."'");
	}
	
	//Updating Plugin Details
	function Set_Plugin_Details($name,$value){
	mysql_query("UPDATE plugin_config SET plugin_config_value = '".$value."' WHERE plugin_config_name ='".$name."'");
	}
	
	function Set_Email_Settings($name,$value){
	mysql_query("UPDATE email_settings config SET email_settings_value = '".$value."' WHERE email_settings_name ='".$name."'");
	}
	function Set_Email_Settings_Headers($name,$header){
	mysql_query("UPDATE email_settings config SET email_settings_headers = '".$header."' WHERE email_settings_name ='".$name."'");
	}	
	
	function Get_Website_Details(){
	$query = mysql_query("SELECT * FROM config");
	while($row = mysql_fetch_array($query)){
	$name = $row['name'];
	$data[$name] = $row['value'];
	}
	return $data;
	}
	
	function Get_Plugin_Details(){
	$query = mysql_query("SELECT * FROM plugin_config");
    if(mysql_num_rows($query) > 0)
    {
	while($row = mysql_fetch_array($query)){
	$name = $row['plugin_config_name'];
	$data[$name] = $row['plugin_config_value'];
	}
	return $data;
    }
	}
	
	function Get_Email_Settings(){
	$query = mysql_query("SELECT * FROM email_settings");
	while($row = mysql_fetch_array($query)){
	$name = $row['email_settings_name'];
	$data[$name] = $row['email_settings_value'];
	}
	return $data;
	}
	
	function Get_Email_Settings_Headers(){
	$query = mysql_query("SELECT * FROM email_settings");
	while($row = mysql_fetch_array($query)){
	$name = $row['email_settings_name'];
	$data[$name] = $row['email_settings_headers'];
	}
	return $data;
	}
	
	function Get_Advertisments(){
	$query = mysql_query("SELECT * FROM ads_data
	WHERE ad_id >= FLOOR( RAND( ) * ( SELECT MAX( ad_id ) FROM ads_data ) ) AND ad_status='1'
	ORDER BY ad_id ASC
	");
	while($row = mysql_fetch_array($query)){
		$id = $row['ad_id'];
		$imp = $row['ad_impressions']+1;
		$name = $row['ad_placement'];
		$data[$name] = stripslashes($row['ad_code']);	
		//Update IMpressions
		mysql_query("UPDATE ads_data SET ad_impressions ='".$imp."' WHERE ad_id='".$id."' ");
		
	}
	return $data;
	}
	
	function check_user($username){
		$query = mysql_query("SELECT * FROM users WHERE username ='".$username."'");
		if(mysql_num_rows($query) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function check_email($email){
	$query = mysql_query("SELECT * FROM users WHERE email ='".$email."'");
	if(mysql_num_rows($query) > 0){
	return true;
	}else{
	return false;
	}
	
	}
	
	function fetch($query){
		$fetch = mysql_fetch_array($query);
		return $fetch;
		}
	
	//This Function is used to Add Categories
	
	function AddCategory(){
	global $LANG;
		$title = mysql_clean($_POST['title']);
		$description = mysql_clean($_POST['description']);
		$file = $_FILES['category_thumb']['name'];
		$ext = substr($file, strrpos($file, '.') + 1);
		$thumb = 'no_thumb.jpg';
				if(!empty($file)){
				$image = new ResizeImage();	
				if($image->ValidateImage($_FILES['category_thumb']['tmp_name'],$ext)){
					$newfilename = RandomString(10).'.'.$ext;
					$category_thumb = '../images/category_thumbs/'.$newfilename;
					copy($_FILES['category_thumb']['tmp_name'],$category_thumb);	
					$image->CreateThumb($category_thumb,$category_thumb,120,$ext);
					$thumb = $newfilename;
						}else{
						$msg[] = e($LANG['class_error_occured']);
						$add = false;
						}
					}
			if(empty($msg)){
				mysql_query("INSERT INTO category(category_name,category_description,category_thumb,date_added)
										   VALUES('".$title."','".$description."','".$thumb."',now())");
				$add = true;
				}
			return $add;
		}
		
	//This Function Is Used to Check Category Exits or Not
	
	/*function CategoryExists($category){
	$query = mysql_query("SELECT categoryid FROM category WHERE categoryid ='".$category."'");
		if(mysql_num_rows($query)>0){
			return true;
			}else{
			return false;
			}
	}
	*/
	
	
	//This Function Is Used to Update Category
	
	function UpdateCategory($category){
	global $LANG;
		$title = mysql_clean($_POST['title']);
		$description = mysql_clean($_POST['description']);
		$file = $_FILES['category_thumb']['name'];
		$ext = substr($file, strrpos($file, '.') + 1);
		$thumb = $_POST['thumb'];
				if(!empty($file)){
					$image = new ResizeImage();	
					if($image->ValidateImage($_FILES['category_thumb']['tmp_name'],$ext)){
						if($thumb != 'no_thumb.jpg'){
						unlink('../images/category_thumbs/'.$thumb);
						}
						$newfilename = RandomString(10).'.'.$ext;
						$category_thumb = '../images/category_thumbs/'.$newfilename;
						copy($_FILES['category_thumb']['tmp_name'],$category_thumb);	
						$image->CreateThumb($category_thumb,$category_thumb,120,$ext);
						$thumb = $newfilename;
						}
					}
			if(empty($msg)){
				mysql_query("UPDATE category SET
				category_name 	 		='".$title."',
				category_description	='".$description."',
				category_thumb			='".$thumb."'
				WHERE categoryid = '".$category."'");
				$update = true;
				}
			return $update;
	}
	
	//Function Delete Category
	function DeleteCategory($category){
	global $LANG;
	mysql_query("DELETE FROM category WHERE categoryid='".$category."'");
	$msg=e($LANG['class_cat_del_msg'],m);
	return $msg;
	}
	
	//Function Used to Delete Flv File
	
	function DeleteFlv($flv){
		$file = BASEDIR.'/files/videos/'.$flv;
		if(file_exists($file) && !empty($flv)){
			unlink($file);
			}
		}
		
	//Function Used To Delete the Original Video
	
	function DeleteOriginal($file){
		$file_path = BASEDIR.'/files/original/'.$file;
		if(file_exists($file_path) && !empty($file)){
			unlink($file_path);
			}
		}
		
	//Function Used to Delete Video Thumbs
	
	function DeleteThumbs($flv){
		$thumb  = substr($flv, 0, strrpos($flv, '.'));
		$thumb1 = BASEDIR."/files/thumbs/".$thumb."-1.jpg";
		$thumb2 = BASEDIR."/files/thumbs/".$thumb."-2.jpg";
		$thumb3 = BASEDIR."/files/thumbs/".$thumb."-3.jpg";
		$thumb4 = BASEDIR."/files/thumbs/".$thumb."-big.jpg";
		
		if(file_exists($thumb1)){
		unlink($thumb1);
		}
		if(file_exists($thumb2)){
		unlink($thumb2);
		}
		if(file_exists($thumb3)){
		unlink($thumb3);
		}
		if(file_exists($thumb4)){
		unlink($thumb4);
		}
		
	}
	
	//Function Used to Check Weather Video Exists or not
	function video_exists($videoid)
	{
		return $this->VideoExists($videoid);
	}
	function VideoExists($videoid){
		global $cbvid;
		return $cbvid->exists($videoid);
	}
	
	//Function Used to Delete Video Files
	
	function DeleteVideoFiles($videoid){
		$query 	= mysql_query("SELECT * FROM video WHERE videoid ='".$videoid."'");
		$data 	= mysql_fetch_array($query);
		$flv 	= $data['flv'];
		$query 	= mysql_query("SELECT * FROM video_detail WHERE flv ='".$data['flv']."'");
		$data 	= mysql_fetch_array($query);
		
		//Updating Users Number Of  Videos Added By User
		$videos_query 	= mysql_query("SELECT * FROM video WHERE username='".@$data['username']."'");
		
		$this->DeleteOriginal($data['original']);
		$this->DeleteFlv($data['flv']);
		$this->DeleteThumbs($data['flv']);
		mysql_query("DELETE FROM video_detail WHERE flv='".$data['flv']."'");
		
		$videoscount	= mysql_num_rows($videos_query);
		$updatequery 	= mysql_query("UPDATE users SET total_videos='".$videoscount."' WHERE username = '".@$username."'");
	}

	//Function Delete Video
	
	function DeleteVideo($videoid){
	global $LANG,$stats;
	$this->DeleteVideoFiles($videoid);
	$this->DeleteFlag($videoid);
	$this->RemoveFavourite($videoid,NULL,2);
	mysql_query("DELETE FROM video WHERE videoid='".$videoid ."'");
	$msg = e($LANG['class_vdo_del_msg'],m);
	$stats->UpdateVideoRecord(2);
	return $msg;
	}
	
	//Function Used to Make Video Featured
	
	function MakeFeaturedVideo($videoid){
		global $cbvid;
		return $cbvid->action('feature',$videoid);
	}
	
	//Function Used to Make Video UnFeatured
	
	function MakeUnFeaturedVideo($videoid){
		global $cbvid;
		return $cbvid->action('unfeature',$videoid);
	}
	
	//Function Used to Activate Vide
	
	function ActivateVideo($videoid){
		global $cbvid;
		return $cbvid->action('activate',$videoid);
	}
	
	//Function Used to Deactivate Video
	
	function DeActivateVideo($videoid){
		global $cbvid;
		return $cbvid->action('deactivate',$videoid);
	}
	
	
	/**
	 * Function used to get video details
	 * from video table
	 * @param INPUT vid or videokey
	 */
	function get_video_details($vid)
	{
		global $cbvid;
		return $cbvid->get_video($vid);
	}	
	function GetVideoDetails($video){
		return $this->get_video_details($video);
	}
	
	
	//Function Used To Update Video Details
	
	function UpdateVideo($video){
	global $LANG,$Upload;

				$title 			= mysql_clean($_POST['title']);
				$description 	= mysql_clean($_POST['description']);
				$tags 			= mysql_clean($_POST['tags']);
				$broadcast 		= $_POST['broadcast'];
				$comments		= $_POST['comments'];
				$comment_voting	= $_POST['comment_voting'];
				$rating			= $_POST['rating'];
				$embedding		= $_POST['embedding'];
				$country		= mysql_clean($_POST['country']);
				$location		= mysql_clean($_POST['location']);
				
				if(!empty($_POST['year']) && !empty($_POST['day']) && !empty($_POST['month'])){
				$date			= $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
				}
				
				$sql = mysql_query("SELECT * from category");
				$total_categories = mysql_num_rows($sql);
		
				for($id=0;$id<=$total_categories;$id++){
				@$category = $_POST['category'][$id];
					if(!empty($category)){
					$selected[] = $category ;
					}
				}
				$category01		= $selected[0];
				@$category02		= $selected[1];
				@$category03		= $selected[2];
				
				//If Video Is Updated via Admin Panel
				if($_POST['admin']=='true'){
					if(!empty($_POST['duration'])){
					$add_query = ",duration='".$_POST['duration']."'";
					}
					if(!empty($_POST['embed_code'])){
					$embedCode = $Upload->CleanEmbedCode($_POST['embed_code']);
					$add_query .= ",embed_code='".$embedCode."'";
					}
					if(!empty($_POST['flv_file_url'])){
					$file_url = mysql_clean($_POST['flv_file_url']);
					$add_query .= ",flv_file_url='".$file_url."'";
					}
					if(!empty($_POST['embeded'])){
					$add_query .= ",embeded='".$_POST['embeded']."'";
					}
					$add_query .= ",status='".$_POST['status']."'";
					
					//Stats Update
					//Views
					$views = $_POST['views'];
					if(is_numeric($views) && $views > 0)
						$add_query .= ",views='".$views."'";
					//Rating
					$Totalrating = $_POST['Totalrating'];
					if(is_numeric($Totalrating)){
						$Totalrating = $Totalrating <=10 ? $Totalrating : '10';
						$Totalrating = $Totalrating >=0 ? $Totalrating : '0';
						$add_query .= ",rating='".$Totalrating."'";
					}
					//RatedBy
					$rated_by = $_POST['rated_by'];
					if(is_numeric($rated_by) && $rated_by> 0)
						$add_query .= ",rated_by='".$rated_by."'";
				}
				
				mysql_query("UPDATE video SET
				title 				= '".$title."',
				description 		= '".$description."',
				tags 				= '".$tags."',
				broadcast 			= '".$broadcast."',
				allow_comments 		= '".$comments."',
				comment_voting 		= '".$comment_voting."',
				allow_rating 		= '".$rating."',
				allow_embedding 	= '".$embedding."',
				country				= '".$country."',
				location			= '".$location."',
				category01			= '".$category01."',
				category02			= '".$category02."',
				category03			= '".$category03."',
				datecreated			= '".@$date."'
				$add_query
				WHERE videoid = '".$video."'") or die(mysql_error());
			
				$msg = e($LANG['class_vdo_update_msg'],m);
				return $msg;
		}
		
	//Function Used To Check Video Exists or No
	
	function CheckVideoExists($videokey){
	global $LANG;
	$query = mysql_query("SELECT * FROM video WHERE videokey ='".$videokey."'");
	if(mysql_num_rows($query)>0){
		return true;
		}else{
		return false;
		}
	}
	
	//Function Used To Get Video Details VIA video Key
	
	function GetVideDetails($vkey){
	global $LANG,$Cbucket;
		$query = mysql_query("SELECT * FROM video WHERE videokey = '".$vkey."' ");
		$data = mysql_fetch_array($query);
		return $data;
		}
	
	//Function Used To Update Videos Views
	
	function UpdateVideoViews($vkey){
	global $LANG,$stats;
		$data=$this->GetVideDetails($vkey);
		if(!isset($_COOKIE['video_'.$vkey])){
			$views = $data['views'] + 1;
			mysql_query("UPDATE video SET views = '".$views."',last_viewed=now() WHERE videokey = '".$vkey."'");
			$stats->UpdateVideoRecord(5);
			setcookie('video_'.$vkey,'watched',time()+3600);
		 	}
		}
		
	//Function Used To Get Category Details
	
	function GetCategory($category,$attr=NULL){
	global $LANG;
		$query = mysql_query("SELECT * FROM category WHERE categoryid='".$category."'");
		$data = mysql_fetch_array($query);
		if(empty($attr)){
		return $data;
		}else{
		return $data[$attr];
		}
	}
	
	//Function Used To Get Duration
	
	function GetVideoDetail($flv){
	global $LANG;
		$query = mysql_query("SELECT * FROM video_detail WHERE flv='".$flv."'");
		$data = mysql_fetch_array($query);
		return $data;
	}
	
	//Function Used To Rate Video
	
	function RateVideo($videoid,$userid,$rating){
	global $LANG;
	
		$query = mysql_query("select rated_by,rating,voter_ids from video where videoid='".$videoid."'");
		$data = mysql_fetch_array($query);
		$voter_id = $data['voter_ids'];
		
				$niddle = "|";
				$niddle .= $userid;
				$niddle .= "|";
				$flag = strstr($voter_id, $niddle);
				
				if(empty($flag)){
					if ($voter_id == "")
					{
							$voter_id .= "|";
					}
					$voter_id .= $userid;
					$voter_id .= "|";
					$t = $data['rated_by'] * $data['rating'];
					$newrby = ($data['rated_by'] + 1);
					$newrate = ($t + $rating) / $newrby;
					mysql_query("UPDATE  video set
									rated_by='".$newrby."',
									rating='".$newrate."',
									voter_ids='".$voter_id."' 
									WHERE videoid='".$videoid."'");
					$details=$this->GetVideoDetails($videoid);
					
					$msg = round(($details['rating']/2)*20,2);
					}else{
					$msg = "already_voted";
					}
			return $msg;
		}
	
	//Function Used To Add Comment
	
	function AddComment($videoid,$comment,$replyto=0){
	global $row,$LANG,$stats;
    if($row['user_comment_opt1'] == 'yes')
    {
			if(empty($_SESSION['username'])){
				$msg[] = e($LANG['class_comment_err']);
			}else{
			if(empty($comment)){
				$msg[] = e($LANG['class_comment_err1']);
				}
			}
			if(empty($msg)){
				mysql_query("INSERT into video_comments(comment,username,videoid,date_added,reply_to,scorer_ids)VALUES('".$comment."','".$_SESSION['username']."','".$videoid."',now(),'".$replyto."','|".$_SESSION['userid']."|')");
                mysql_query("UPDATE video SET comments_count=comments_count+1 WHERE videoid = '".$videoid."'") or die(mysql_error());
				$msg[] = e($LANG['class_comment_msg'],m);
				$stats->UpdateVideoRecord(3);
			}
     }
     else
     {
     $msg[] = "Comments Disabled";
     }
		return $msg;
		}
	
	
	
	/**
	 * Function used to delete comments
	 * @param CID
	 */
	function delete_comment($cid,$type='v',$is_reply=FALSE)
	{
		global $db,$userquery,$LANG;
		//first get comment details
		
		$cdetails = $this->get_comment($cid);
		
		switch($type)
		{
			case 'v':
			$own_id = $this->get_vid_owner($cdetails['type_id']);
			break;
			
			case 'c';
			$own_id = $this->get_vid_owner($cdetails['type_id']);
			break;
		}
			
		
		$uid = user_id();
		if(	   $uid == $cdetails['userid'] 
			|| $uid==$own_id 
			|| $userquery->permission['mod_access'] == 'yes' 
			|| $is_reply==TRUE)
		{
			$replies = $this->get_comments($cdetails['type_id'],$type,FALSE,$cid,TRUE);
			if(count($replies)>0 && is_array($replies))
			{
				foreach($replies as $reply)
				{
					$this->delete_comment($reply['comment_id'],$type,TRUE);
				}
			}
			$db->Execute("DELETE FROM comments WHERE comment_id='$cid'");
			e($LANG['usr_cmt_del_msg'],m);
		}else{
			e($LANG['no_comment_del_perm']);
		}
	}
	
	
	//function used to delete comment
	function DeleteComment($id,$videoid){
	global $LANG,$stats;
		mysql_query("DELETE FROM video_comments WHERE comment_id = '".$id."' OR reply_to='".$id."'");
        mysql_query("UPDATE video SET comments_count=comments_count-1 WHERE videoid = '".$videoid."'") or die(mysql_error());
		$stats->UpdateVideoRecord(4);
	}
	//Function Used To Rate Comments
	function RateComment($rate,$commentid){
	global $LANG;
			if(!empty($_SESSION['username']) ){
			$query = mysql_query("select score,scorer_ids from video_comments where comment_id='".$commentid."'");
			$data = mysql_fetch_array($query);
			$voter_id = $data['scorer_ids'];
			$userid = $_SESSION['userid'];
			$niddle = "|";
			$niddle .= $userid;
			$niddle .= "|";
			$flag = strstr($voter_id, $niddle);
			if(empty($flag)){
					if ($voter_id == "")
					{
							$voter_id .= "|";
					}
			$voter_id .= $userid;
			$voter_id .= "|";
			$newscore = $rate+$data['score'];
			mysql_query("UPDATE video_comments SET score='".$newscore."' , scorer_ids='".$voter_id."' WHERE comment_id ='".$commentid."'");
			}else{
			$msg = e($LANG['class_comment_err7']);
			}
			}else{
			$msg = e($LANG['class_comment_err6']);
			}
		return $msg;
		}
				
	//Function Used To Add Video To Favourites
	function AddToFavourite($userid,$videoid){
	global $LANG,$stats;
		$query = mysql_query("SELECT * FROM video_favourites WHERE userid='".$userid."' AND videoid='".$videoid."'");
		if(mysql_num_rows($query)>0){
		$msg = e($LANG['class_vdo_fav_err']);
		}else{
		if($userid !=0){
		mysql_query("INSERT INTO video_favourites(videoid,userid)VALUES('".$videoid."','".$userid."')");
		$msg = e($LANG['class_vdo_fav_msg'],m);
		$stats->UpdateVideoRecord(6);
		}
		}
	return $msg;
	}
	
	//Function Used To Flagg Video
	function FlagAsInappropriate($username,$videoid){
	global $LANG;
		$query = mysql_query("SELECT * FROM flagged_videos WHERE username='".$username."' AND videoid='".$videoid."'");
		if(mysql_num_rows($query)>0){
		$msg = e($LANG['class_vdo_flag_err']);
		}else{
		mysql_query("INSERT INTO flagged_videos(videoid,username)VALUES('".$videoid."','".$username."')");
		$msg = e($LANG['class_vdo_flag_msg'],m);
		}
	return $msg;
	}
	
	//Function Delete Flag
	function DeleteFlag($videoid){
	global $LANG;
		mysql_query("DELETE FROM flagged_videos where videoid='".$videoid."'");
		return e($LANG['class_vdo_flag_rm'],m);
	}
		
	//Function Used To Share Videos
	function ShareVideo($username,$videoid,$message,$emails){
	global $LANG;
		$userquery 		= new userquery();
		$user_data 		= $userquery->GetUserData_username($username);
		$video_data		= $this->GetVideoDetails($videoid);
		$t__o			= $emails;
		$from 		= $user_data['email'];
		$title		= TITLE;
		$baseurl	= BASEURL;
		$videotitle	= $video_data['title'];
		$videodes	= $video_data['description'];
		$videokey	= $video_data['videokey'];	
		$videothumb	= '<img src='.BASEURL.'/files/thumbs/'.substr($video_data['flv'], 0, strrpos($video_data['flv'], '.')).'-1.jpg border=1 />';
		$user_data 	= "";
		$video_data = "";
		require_once(BASEDIR.'/includes/email_templates/share_video.template.php');
		require_once(BASEDIR.'/includes/email_templates/share_video.header.php');
		send_email($from,$t__o,$subj,$body);

	}
	
	//Function Used To Send Message
	function SendMessage($to,$from,$subj,$message,$video,$reply_to=0,$redirect=NULL){
	global $LANG;
		if(empty($to)){
			$msg = e($LANG['class_send_msg_err']);
			}else{
			$userquery 		= new userquery();
			$user_data 		= $userquery->GetUserData_username($to);
			if(empty($user_data['userid'])){
				$msg = e($LANG['class_invalid_user'],m);
				}
			}
		if(empty($subj)){
			$msg = e($LANG['class_subj_err']);
			}
		if(empty($message)){
			$msg = e($LANG['class_msg_err']);
			}
		if(empty($msg)){
			if(!mysql_query("INSERT INTO messages (inbox_user,outbox_user,message,attachment,subject,reply_to,reciever,sender)VALUES('".$to."','".$from."','".$message."','".$video."','".$subj."','".$reply_to."','".$to."','".$from."')"))die(mysql_error());
			
			//Sending Email
			$users = new userquery();
			$users = $users->GetUserData_username($to);
			if($users['msg_notify'] == 'yes'){
				$email = $users['email'];
				$from_email  = TITLE."<".SUPPORT_EMAIL.">";
				$subject = $from." ".$LANG['class_sent_you_msg'];
				$messg	= $from." ".$LANG['class_sent_prvt_msg']." ".TITLE."<br />
<a href=".BASEURL."/inbox.php>".$LANG['class_click_inbox']."</a><br />
<a href=".BASEURL."/signup.php>".$LANG['class_click_login']."</a><br />
====================================<br />
".TITLE." ".$LANG['class_email_notify'];
			send_email($from_email,$email,$subject,$messg);
			}
			if($redirect==1){
			redirect_to(BASEURL.compose_msg_link.'?msg='.$LANG['class_msg_has_sent_to'].$to);
			}
		}
		
		return $msg;
	}
	
	//Functon Used To Fetch Message From MESSEGES Table
	
	function GetMsg($msgid,$user){
	global $LANG;
		$query = mysql_query("SELECT * FROM messages WHERE message_id='".$msgid."' AND inbox_user='".$user."'");
		$data = mysql_fetch_array($query);
		return $data;
	}
	
	//Functon Used To Fetch Message From MESSEGES Table
	
	function GetMsgSent($msgid,$user){
	global $LANG;
		$query = mysql_query("SELECT * FROM messages WHERE message_id='".$msgid."' AND outbox_user='".$user."'");
		$data = mysql_fetch_array($query);
		return $data;
	}
	
	//Function Used To Delete Message
	
	function DeleteMessage($msgid,$user,$box){
	global $LANG;
		$user_data = $this->GetMsg($msgid,$user);
			if(empty($user_data['subject'])|| $box=='outbox'){
				$user_data = $this->GetMsgSent($msgid,$user);
				}
			if(!empty($user_data['subject'])){
				if($box == 'inbox'){
					if(empty($user_data['outbox_user'])){
						mysql_query("DELETE FROM messages WHERE message_id='".$msgid."'");
						}else{
						mysql_query("UPDATE messages SET inbox_user='' WHERE message_id='".$msgid."'");
						}
					$msg = e($LANG['class_inbox_del_msg'],m);
					}else{
					if(empty($user_data['inbox_user'])){
						mysql_query("DELETE FROM messages WHERE message_id='".$msgid."'");
						}else{
						mysql_query("UPDATE messages SET outbox_user='' WHERE message_id='".$msgid."'");
						}
					$msg = e($LANG['class_sent_del_msg'],m);
					}
				
				}else{
				$msg = e($LANG['class_msg_exist_err']);
				}
		return $msg;
	}
				
	//Function Used to Get Subscriber List
	
	function GetSubscribers($user){
	global $LANG;
		$query = mysql_query("SELECT * FROM subscriptions  WHERE subscribed_to ='".$user."'");
		$data  = mysql_num_rows($query);
		return $data;
	}
	
		function GetSubscriptions($user){
	global $LANG;
		$query = mysql_query("SELECT * FROM subscriptions  WHERE subscribed_user ='".$user."'");
		$data  = mysql_num_rows($query);
		return $data;
	}
	
	//Function Used To Get Number Of Favourite Videos of user
			
		function GetTotalFavourites($username){
	global $LANG;
		$query = mysql_query("SELECT * FROM video_favourites WHERE userid = '".$username."'");
		return mysql_num_rows($query);
		}
		
	//Function Used To Get Numbe Of Comments on a Channel
		
		function GetTotalChannelComments($username){
	global $LANG;
		$query = mysql_query("SELECT * FROM channel_comments WHERE channel_user = '".$username."'");
		return mysql_num_rows($query);
		}
		
	//Function Used To Get Total number of videos watched by user uploaded by user
	function GetUploadedWatched($username){
	global $LANG;
		$query = mysql_query("SELECT * FROM video WHERE username='".$username."'");
			$total_views = 0;
			while($data=mysql_fetch_array($query)){
				$total_views = $total_views + $data['views'];
				}
		return $total_views;
		}
		
	//Function Used To Get Total number of Groups Create By
	function GetUserCreateGroups($username){
	global $LANG;
		$query = mysql_query("SELECT * FROM groups WHERE username='".$username."'");
		return mysql_num_rows($query);;
	}
		
	//Function Used To Get Total number 
	function GetUserJoinGroups($username){
	global $LANG;
		$query = mysql_query("SELECT * FROM group_members WHERE username='".$username."'");
		return mysql_num_rows($query);
	}
		
	//Delete Video 2
	function DeleteUserVideo($videoid,$user){
	global $LANG;
		$query = mysql_query("SELECT * FROM video WHERE videoid='".$videoid."' AND username='".$user."'");
			if(mysql_num_rows($query)>0){
				$this->DeleteVideo($videoid);
				$this->DeleteVideoFiles($videoid);
				mysql_query("DELETE FROM video WHERE videoid='".$videoid ."'");
				$msg = e($LANG['class_vdo_del_msg'],m);
			}else{
				$msg = e($LANG['class_vdo_del_err']);
			}
		//Updating Users Number Of  Videos Added By User
				$videos_query 	= mysql_query("SELECT * FROM video WHERE username='".$user."'");
				$videoscount	= mysql_num_rows($videos_query);
				$updatequery = "UPDATE users SET total_videos='".$videoscount."' WHERE username = '".$user."'";
		return $msg;
	}
	
	//Function Used To Get Total Videos
	function GetTotalVideos($user){
	global $LANG;
		$query=mysql_query("SELECT * FROM video WHERE username='".$user."'");
		return mysql_num_rows($query);
	}
	
	//Function Used To Unsubscribe To User
	function UnSubscribe($subid,$user){
	global $LANG;
		$query=mysql_query("SELECT * FROM subscriptions  WHERE subscription_id='".$subid."' AND subscribed_user='".$user."' ");
			if(mysql_num_rows($query)>0){
	global $LANG;
				mysql_query("DELETE FROM subscriptions WHERE subscription_id='".$subid."'");
				$msg = e($LANG['class_unsub_msg'],m);
			}else{
				$msg = e($LANG['class_sub_exist_err']);
			}
		return $msg;
	}
	
	//Function Used To Remove From Favourites
	function RemoveFavourite($favid,$userid=NULL,$method=1){
	global $LANG,$stats;;
		if($method == 1){
		$query  = mysql_query("SELECT * FROM video_favourites WHERE fav_id='".$favid."' AND userid='".$userid."'");
			if(mysql_num_rows($query)>0){
				mysql_query("DELETE FROM video_favourites WHERE fav_id='".$favid."'");
				$msg = e($LANG['class_vdo_rm_fav_msg'],m);
				$stats->UpdateVideoRecord(7);
			}else{
				$msg = e($LANG['class_vdo_fav_err1']);
			}
		}else{
			mysql_query("DELETE FROM video_favourites WHERE videoid='".$favid."'");
			$stats->UpdateVideoRecord(7);
		}
		return @$msg;
	}
	
	//Function Used To Remove Contact
	function RemoveUser($contactid,$user){
	global $LANG;
		$query=mysql_query("SELECT * FROM contacts WHERE contactid='".$contactid."' AND username ='".$user."'");
			if(mysql_num_rows($query)>0){
				mysql_query("DELETE from contacts WHERE contactid='".$contactid."'");
				$msg = e($LANG['class_cont_del_msg'],m);

			}else{
				$msg = e($LANG['class_cot_err']);
			}
		return $msg;
		
	}
	
	//Function Used To Varify Syntax
	function isValidSyntax($syntax){
	global $LANG;
      $pattern = "^^[_a-z0-9-]+$";
      if (eregi($pattern, $syntax)){
         return true;
      	}else {
         return false;
      	}   
	}
	
	# THIS WAS AN OLD AD SYSTEM
	
	/*//Function Used Ti add Advertisement
	
	function AddAd(){
		$type 	= mysql_clean($_POST['type']);
		$name	= mysql_clean($_POST['name']);
		$code	= $_POST['code'];
		$syntax = mysql_clean($_POST['syntax']);
				
				if(empty($name)){
					$msg = e($LANG['class_vdo_ep_add_msg']);
				}
				if(empty($code)){
					$msg = e($lANG['class_vdo_ep_err']);
				}
				$query = mysql_query("SELECT  * FROM advertisement WHERE ad_syntax = '".$syntax."'");
				if(mysql_num_rows($query)>0){ 
				$msg = "This Syntax Already Exists, Please Enter Different Syntax ";
				}
				if(empty($syntax)){
					$msg = "Please Enter Syntax For Advertisement";
				}else{
					if(!$this->isValidSyntax($syntax)){
						$msg = "Syntax Can Only Contain Underscores, Letters and Numbers";
					}
				}

				$query = mysql_query("SELECT * FROM advertisement WHERE ad_name ='".$name."'");
				if(mysql_num_rows($query)>0){
					$msg = "Error : Advertisement With This Name Already Exists";
				}
				if(empty($msg)){
				mysql_query("INSERT INTO advertisement (ad_type,ad_name,ad_syntax,ad_code)VALUES('".$type."','".$name."','".$syntax."','".$code."')");
				$msg = "Advertisment Has Been Added";
				}
		return $msg;
	}
	
	//Function Used To Change Ad Status
	
	function ChangeAdStatus($status,$id){
		if($status !==0 && $status !==1){
		$status = 0;
		}
		$status;
		mysql_query("UPDATE advertisement SET ad_status = '".$status."' WHERE ad_id ='".$id."' ");
		if($status == '0'){
		$show_status = "Deactivated";
		}else{
		$show_status = "Activated";
		}
		$msg = "Ad Has Been ".$show_status;
	return $msg;
	}
	
	//Function Used To Edit Advertisment
	
	function EditAd($id){
		$type 	= mysql_clean($_POST['type']);
		$name	= mysql_clean($_POST['name']);
		$code	= $_POST['code'];
		$syntax = mysql_clean($_POST['syntax']);	
				if(empty($name)){
					$msg = "Please Enter Name For The Advertisment";
				}
				if(empty($code)){
					$msg = "Please Enter Code For Advertisement";

				}
				$query = mysql_query("SELECT  * FROM advertisement WHERE ad_id <> '".$id."' AND ad_syntax = '".$syntax."'");
				if(mysql_num_rows($query)>0){ 
				$msg = "This Syntax Already Exists, Please Enter Different Syntax ";
				}
				if(empty($syntax)){
					$msg = "Please Enter Syntax For Advertisement";
				}else{
					if(!$this->isValidSyntax($syntax)){
						$msg = "Syntax Can Only Contain Underscores, Letters and Numbers";
					}
				}
				if(empty($msg)){
				mysql_query("UPDATE advertisement SET
				ad_type = '".$type."',
				ad_name = '".$name."',
				ad_syntax = '".$syntax."',
				ad_code	= '".$code."'
				Where ad_id = '".$id."'");
				$msg = "Advertisment Has Been Updated";
				}
		return $msg;
	}
	
	//Function Used To delete AD
	
	function DeleteAd($id){
			$query = mysql_query("SELECT * FROM advertisement WHERE ad_id ='".$id."'");
			if(mysql_num_rows($query)!=1){
				$msg = "Error : Advertisement Doesnt Exist ";
			}else{
			mysql_query("DELETE FROM advertisement WHERE ad_id='".$id."'");
				$msg = "Advertisement Has Been Deleted";
			}
		return $msg;
	}*/
	
	//Function Used To Add IN Editor Pick
	function AddToEditorPick($video){
	global $LANG;
		$data 		= $this->GetVideoDetails($video);
		$videokey	= $data['videokey'];
		$query1 		= mysql_query("SELECT * FROM editors_picks ORDER BY sort ASC");
		if(mysql_num_rows($query1)<10){
			$query = mysql_query("SELECT * FROM editors_picks  WHERE videokey = '".$videokey."'");
				if(mysql_num_rows($query)==0){
				$data = mysql_fetch_assoc($query1);
				$sort = $data['sort'];
				$sort = $sort + 1;
					mysql_query("INSERT INTO editors_picks (videokey,sort)VALUES('".$videokey."','".$sort."')");
					$msg = e($LANG['class_vdo_ep_add_msg'],m);
				}else{
					$msg = e($LANG['class_vdo_ep_err']);
				}
			}else{
			$msg = e($LANG['class_vdo_ep_err1']);
			} 
		return $msg;
	}
	
	//Function Used To Delete Video From Editors Pick
	
	function DeleteEditorPick($id){
	global $LANG;
		$query = mysql_query("SELECT * FROM editors_picks WHERE pick_id='".$id."'");
			if(mysql_num_rows($query)!=0){
				$query = mysql_query("DELETE FROM editors_picks WHERE pick_id='".$id."'");
				$msg =e($LANG['class_vdo_ep_msg'],m);
			}else{
				$msg   = e($LANG['class_vdo_exist_err']);
			}
		return $msg;
	}
	
	//Function Used To Change Website Logo Via Admin Panel
	function ChangeLogo($logo_name,$dir,$allowed_ext){
	global $LANG;
		$file = $_FILES['image_file']['name'];
		$ext = substr($file, strrpos($file, '.') + 1);
			switch($allowed_ext){
				case 1;
				$ext1 = 'gif';
				$ext2 = 'GIF';
				$msg  = e($LANG['class_img_gif_err']);
				break;
				
				case 2;
				$ext1 = 'png';
				$ext2 = 'PNG';
				$msg  = e($LANG['class_img_png_err']);
				break;
				
				case 3;
				$ext1 = 'jpg';
				$ext2 = 'JPG';
				$msg  = e($LANG['class_img_jpg_err']);
				break;
				
				default:
				$ext1 = 'gif';
				$ext2 = 'GIF';
				$msg  = e($LANG['class_img_gif_err']);

			}
				
		if($ext !=$ext1 && $ext !=$ext2){
	global $LANG;

		}else{
				if(!empty($file)){
					$image = new ResizeImage();	
					if($image->ValidateImage($_FILES['image_file']['tmp_name'],$ext)){
					$newfilename = $logo_name;
					$uploaded_thumb = $dir.'/'.$newfilename;
					copy($_FILES['image_file']['tmp_name'],$uploaded_thumb);
					if($ext1 !='png'){
					list($width, $height) = getimagesize($uploaded_thumb);
					$image->CreateThumb($uploaded_thumb,$uploaded_thumb,$width,$ext);
					}
					$msg = e($LANG['class_logo_msg'],m);
				 }else{
					$msg = e($LANG['class_error_occured']);
				 }
				 }
			
		}
			return $msg;
	}
	
	//FUNCTION USED TO MOVE VIDE UP IN EDITORS PICK
	function MovePickUp($id){
	global $LANG;
	$query = mysql_query("SELECT * FROM editors_picks WHERE pick_id ='".$id."'");
			$data = mysql_fetch_assoc($query);
			$sort = $data['sort'] - 2;
			if(!mysql_query("UPDATE editors_picks SET sort='".$sort."' WHERE pick_id ='".$data['pick_id']."'"))die(mysql_error());
			return e($LANG['editor_pic_up'],m);
			
	}
		//FUNCTION USED TO MOVE VIDE UP IN EDITORS PICK
	function MovePickDown($id){
	global $LANG;
	$query = mysql_query("SELECT * FROM editors_picks WHERE pick_id ='".$id."'");
			$data = mysql_fetch_assoc($query);
			$sort = $data['sort'] + 2;
			if(!mysql_query("UPDATE editors_picks SET sort='".$sort."' WHERE pick_id ='".$data['pick_id']."'"))die(mysql_error());
			return e($LANG['editor_pic_up'],m);
			
	}
	
	//FUNCTION USED TO CHECK , SELECTED TEMPLATE IS CORRECT OR NOT
	function IsTemplate($tempdir)
	{
		$query = mysql_query("SELECT * FROM template WHERE template_dir = '".$tempdir."'");
		if(mysql_num_rows($query)>0)
		return true;
		else
		return false;
	}
	
	//DEACTIVATING FONT
	function PluginActive($plugin_id_code,$active='yes'){
		mysql_query("UPDATE plugins SET plugin_active='".$active."' WHERE plugin_id_code='".$plugin_id_code."'");
	}
	
	//UPDATING PLUGIN LICENSE
	function UpdatePluginLicense($plugin_id_code){
		$code = mysql_clean($_POST['license_code']);
		$key = mysql_clean($_POST['license_key']);
		mysql_query("UPDATE plugins SET plugin_license_key='".$key."',
		plugin_license_code='".$code."'
	    WHERE plugin_id_code='".$plugin_id_code."'");

	}
	
	/**
	* FUNCTION USED TO GET VIDEOS FROM DATABASE
	* @param: array of query parameters array()
	* featured => '' (yes,no)
	* username => '' (TEXT)
	* title => '' (TEXT)
	* tags => '' (TEXT)
	* category => '' (INT)
	* limit => '' (OFFSET,LIMIT)
	* order=>'' (BY SORT) -- (date_added DESC)
	* extra_param=>'' ANYTHING FOR MYSQL QUERY
	* @param: boolean
	* @param: results type (results,query)
	*/
	
	function getVideoList($param=array(),$global_cond=true,$result='results')
	{
		global $db;
		
		$sql = "SELECT * FROM video";
		
		//Global Condition For Videos
		if($global_cond==true)
			$cond = "broadcast='public' AND active='yes' AND status='Successful'";
		//Checking Condition
		if(!empty($param['featured']))
		{
			$param['featured'] = 'yes' ? 'yes' : 'no';
			$cond .=" AND featured= '".$param['featured']."' ";
		}
		if(!empty($param['username']))
		{
			$username = mysql_clean($param['username']);
			$cond .=" AND featured= '".$username."' ";
		}
		if(!empty($param['category']))
		{
			$category = intval($param['category']);
			$cond .=" AND (category01= '".$category."' OR category02= '".$category."' OR category03= '".$category."') ";
		}
		if(!empty($param['tags']))
		{
			$tags = mysql_clean($param['tags']);
			$cond .=" AND tags LIKE '%".$tags."%' ";
		}
		if(!empty($param['title']))
		{
			$tags = mysql_clean($param['tags']);
			$cond .=" AND title LIKE '%".$param['title']."%' ";
		}
		
		//Adding Condition in Query
		if(!empty($cond))
			$sql .= " WHERE $cond ";
		
		//SORTING VIDEOS
		if(!empty($param['order']))
			$sort = 'ORDER BY '.$param['order'];
		
		//Adding Sorting In Query
			$sql .= $sort;
			
		//LIMITING VIDEO LIST
		if(empty($param['limit']))
			$limit = " LIMIT ". VLISTPP;
		elseif($param['limit']=='nolimit')
			$limit = '';
		else
			$limit = " LIMIT ".$param['limit'];
				
		$sql .= $limit;
		//Final Executing of Query and Returning Results
		if($result=='results')
			return $db->Execute($sql);
		else
			return $sql;
	}
	
	
	
	
	/**
	 * Function used to send subsribtion message
	 */
	function send_subscription($subscriber,$from,$video)
	{
		global $LANG;
		//First checking weather $subscriber exists or not
		$array = array('%subscriber%','%user%','%website_title%');
		$replace = array($subscriber,$from,TITLE);
		
		$to = $subscriber;
		$subj = str_replace($array,$replace,$LANG['user_subscribe_subject']);
		
		//Get Subscription Message Template
		$msg = get_subscription_template();
		$msg = str_replace($array,$replace,$msg);
		$this->SendMessage($to,$from,$subj,$msg,$video,0,0);
	}
	
	
	/**
	 * Function used to add comment
	 * This is more advance function , 
	 * in this function functions can be applied on comments
	 */
	function add_comment($comment,$obj_id,$reply_to=NULL,$type='v')
	{
		global $userquery,$eh,$db;
		//Checking maximum comments characters allowed
		if(defined("MAX_COMMENT_CHR"))
		{
			if(strlen($comment) > MAX_COMMENT_CHR)
				e(sprintf("'%d' characters allowed for comment",MAX_COMMENT_CHR));
		}
		if(empty($comment))
			e("Please enter something for comment");
		
		if($type=='video' || $type=='v')
		{
			if(!$this->video_exists($obj_id))
				e("Video does not exist");
			
			//Checking owner of video
			if(!USER_COMMENT_OWN)
			{
				if(userid()==$this->get_vid_owner($obj_id));
					e("You cannot comment on your video");
			}
		}
		
		if(!userid())
			e("You are not logged in");
		
		if(empty($eh->error_list))
		{
			$db->insert("comments",array
						 ('type,comment,type_id,userid,date_added,parent_id'),
						 array
						 ($type,$comment,$obj_id,userid(),NOW(),$reply_to));
			e("Comment has been added",m);
			return $db->insert_id();
		}
	}
	
	
	
	/**
	 * Function used to  get file details from database
	 */
	function file_details($file_name)
	{
		global $db;
		$results = $db->select("video_files","*"," src_name='$file_name'");
		if($db->num_rows==0)
			return false;
		else
		{
			return $results[0];
		}
	}
	
	
		 
	/**
	 * Function used to update video and set a thumb as default
	 * @param VID
	 * @param THUMB NUM
	 */
	function set_default_thumb($vid,$thumb)
	{
		global $db,$LANG;
		$num = get_thumb_num($thumb);
		$file = THUMBS_DIR.'/'.$thumb;
		if(file_exists($file))
		{
			$db->update("video",array("default_thumb"),array($num)," videoid='$vid'");
			e($LANG['vid_thumb_changed'],m);
		}else{
			e($LANG['vid_thumb_change_err']);
		}		
	}
	
	
	
	
	/**
	 * Function used to update video
	 */
	function update_video()
	{
		global $cbvid;
		return $cbvid->update_video();
	}
	
	
	
	/**
	 * Function used to get categorie details
	 */
	function get_category($id)
	{
		global $db;
		$results = $db->select("category","*"," categoryid='$id'");
		return $results[0];
	}
	
	
	/**
	 * Function used to get comment from its ID
	 * @param ID
	 */
	function get_comment($id)
	{
		global $db;
		$result = $db->select("comments","*"," comment_id='$id'");
		if($db->num_rows>0)
		{
			return $result[0];
		}else{
			return false;
		}
	}
	
	/**
	 * Function used to get from database
	 * @param TYPE_ID
	 * @param TYPE
	 * @param COUNT_ONLY Boolean
	 * @param PARENT_ID 
	 * @param GET_REPLYIES_ONLY Boolean
	 */
	function get_comments($type_id,$type='v',$count_only=FALSE,$parent_id=NULL,$get_reply_only=FALSE)
	{
		global $db;
		$cond = '';
		
		#Checking if user wants to get replies of comment 
		if($parent_id!=NULL && $get_reply_only)
		{
			$cond .= " AND parent_id='$parent_id'";
		}
		
		$result = $db->select("comments","*"," type='$type' AND type_id='$type_id' $cond");
		
		if($db->num_rows > 0)
		{
			if($count_only)
				return $db->num_rows;
			else
				return $result;
		}else{
			return '';
		}
		
	}
	
	
	/**
	 * Function used to get video owner
	 */
	function get_vid_owner($vid)
	{
		global $db;
		$results = $db->select("video","userid"," videoid='$vid'");
		return $results[0];
	}
	
}
?>