<?php

/**
###################################################################
# Copyright (c) 2008 - 2009 ClipBucket / PHPBucket
# [url]http://clip-bucket.com[/url]
# Function:         Various
# Author:           Arslan Hassan
# Language:         PHP
# License:          CBLA @ [url]http://clip-bucket.com/cbla[/url]
# Version:          2
# Notice:           Please maintain this section
####################################################################
*/

 define("SHOW_COUNTRY_FLAG",TRUE);
 require 'define_php_links.php';
 include_once 'upload_forms.php';
 
    function add_column_if_not_exist($table, $column, $column_attr){
	$exists = false;
	$columns = mysql_query("show columns from $table");
	while($c = mysql_fetch_assoc($columns)){
		if($c['Field'] == $column){
			$exists = true;
			break;
		}
	}
	if(!$exists){
		mysql_query("ALTER TABLE `$table` ADD `$column`  $column_attr");
	}
    }
 
	//This Funtion is use to get CURRENT PAGE DIRECT URL
	function curPageURL() {
 		$pageURL = 'http';
		if (@$_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
		}
		$pageURL .= "://";
 		$pageURL .= $_SERVER['SERVER_NAME'];
		$pageURL .= $_SERVER['PHP_SELF'];
		$query_string = $_SERVER['QUERY_STRING'];
		if(!empty($query_string)){
		$pageURL .= '?'.$query_string;
		}
 		return $pageURL;
	}
	
	//QuotesReplace
	function Replacer($string)
	{
	//Wp-Magic Quotes
	$string = preg_replace("/'s/", '&#8217;s', $string);
	$string = preg_replace("/'(\d\d(?:&#8217;|')?s)/", "&#8217;$1", $string);
	$string = preg_replace('/(\s|\A|")\'/', '$1&#8216;', $string);
	$string = preg_replace('/(\d+)"/', '$1&#8243;', $string);
	$string = preg_replace("/(\d+)'/", '$1&#8242;', $string);
	$string = preg_replace("/(\S)'([^'\s])/", "$1&#8217;$2", $string);
	$string = preg_replace('/(\s|\A)"(?!\s)/', '$1&#8220;$2', $string);
	$string = preg_replace('/"(\s|\S|\Z)/', '&#8221;$1', $string);
	$string = preg_replace("/'([\s.]|\Z)/", '&#8217;$1', $string);
	$string = preg_replace("/ \(tm\)/i", ' &#8482;', $string);
	$string = str_replace("''", '&#8221;', $string);

	$array = array('/& /');
	$replace = array('&amp; ') ;
	return $string = preg_replace($array,$replace,$string);
	}
	//This Funtion is used to clean a String
	
	function clean($string,$allow_html=false) {
 	 $string = stripslashes($string);
 	 //$string = htmlentities($string);
	 if($allow_html==false){
 	 $string = strip_tags($string);
	 $string =  Replacer($string);
	 }
	 //$string = utf8_encode($string);
 	 return $string;
	}
	
	//This Fucntion is for Securing Password, you may change its combination for security reason but make sure dont not rechange once you made your script run
	
	function pass_code($string) {
 	 $password = md5(md5(sha1(sha1(md5($string)))));
 	 return $password;
	}
	
	//Mysql Clean Queries
	
	function mysql_clean($id){
		$id = clean($id);
		if (get_magic_quotes_gpc())
		{
		$id = stripslashes($id);
		}
	$id = mysql_real_escape_string($id);
	return $id;
	}
	
	//Redirect Using JAVASCRIPT
	
	function redirect_to($url){
		echo '<script type="text/javascript">
		window.location = "'.$url.'"
		</script>';
		exit("Javascript is turned off, <a href='$url'>click here to go to requested page</a>");
		}
		
	//Simple Template Displaying Function
	
	function Template($template,$layout=true){
	global $admin_area;
		if($layout)
		CBTemplate::display(LAYOUT.'/'.$template);
		else
		CBTemplate::display($template);
		
		if($template == 'footer.html' && $admin_area !=TRUE){
			CBTemplate::display(BASEDIR.'/includes/templatelib/'.$template);
		}
		if($template == 'header.html'){
			CBTemplate::display(BASEDIR.'/includes/templatelib/'.$template);
		}        	
	}
	
	function Assign($name,$value)
	{
		CBTemplate::assign($name,$value);
	}
	
	//Funtion of Random String
	function RandomString($length)
	{
    // Generate random 32 charecter string
    $string = md5(time());

    // Position Limiting
    $highest_startpoint = 32-$length;

    // Take a random starting point in the randomly
    // Generated String, not going any higher then $highest_startpoint
    $randomString = substr($string,rand(0,$highest_startpoint),$length);

    return $randomString;

}


 //This Function Is Used To Display Tags Cloud
	 function TagClouds($cloudquery)
	{
			$tags = array();
			$cloud = array();
			$query = mysql_query($cloudquery);
			while ($t = mysql_fetch_array($query))
			{
					$db = explode(' ', $t[0]);
					while (list($key, $value) = each($db))
					{
							@$keyword[$value] += 1;
					}
			}
			if (is_array(@$keyword))
			{
					$minFont = 11;
					$maxFont = 22;
					$min = min(array_values($keyword));
					$max = max(array_values($keyword));
					$fix = ($max - $min == 0) ? 1 : $max - $min;
					// Display the tags
					foreach ($keyword as $tag => $count)
					{
							$size = $minFont + ($count - $min) * ($maxFont - $minFont) / $fix;
							$cloud[] = '<a class=cloudtags style="font-size: ' . floor($size) . 'px;" href="' . BASEURL.search_result.'?query=' . $tag . '" title="Tags: ' . ucfirst($tag) . ' was used ' . $count . ' times"><span>' . mysql_clean($tag) . '</span></a>';
					}
					$shown = join("\n", $cloud) . "\n";
					return $shown;
			}
		}
		

			
	/**
	 * Function used to send emails
	 * @Author : Arslan Hassan
	 * this is a very basic email function 
	 * you can extend or replace this function easily
	 * read our docs.clip-bucket.com
	 */
	function cbmail($array)
	{
		$func_array = get_functions('email_functions');
		if(is_array($func_array))
		{
			foreach($func_array as $func)
			{
				if(function_exists($func))
				{
					return $func($array);
				}
			}
		}
		
		$content = $array['content'];
		$subject = $array['subject'];
		$to		 = $array['to'];
		$from	 = $array['from'];
		
		if($array['nl2br'])
			$content = nl2br($content);
			
		//Setting Boundary
		$mime_boundary = "----ClipBucket Emailer----".md5(time());
		
		$headers  = "From: ".$from." \r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\n";
		$headers .= "Content-Transfer-Encoding: 7bit  \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		//Checking if has CC 
		if($array['cc'])
		$headers .= "cc: ".$array['cc']." \r\n";
		//Checking if has BCC 
		if($array['bcc'])
		$headers .= "Bcc: ".$array['bcc']." \r\n";
		//Setting Mailer
		$headers .= "X-Mailer: ClipBucket v2 \r\n";
  		
		//Starting Message
		if($array['user_boundary'])
		{
			$message  = "--$mime_boundary--\n";
			$message .= "Content-Type: text/html; charset=UTF-8\n";
			$message .= "Content-Transfer-Encoding: 8bit\n\n";
		}
			
		
		# CHecking Content
		if(preg_match('/<html>/',$content,$matches))
		{
			if(empty($matches[1]))
			{
				$content = wrap_email_content($content);
			}
		}
		$message .= $content;
		if($array['user_boundary'])
		{
			//Ending Message
			$message .= "--$mime_boundary--\n";
		}
			
		$email = mail ($to,$subject,$message,$headers);
		if( $email == true ){
			return true;
		}else{
			return false;
		}
	}
	function send_email($from,$to,$subj,$message)
	{
		return cbmail(array('from'=>$from,'to'=>$to,'subject'=>$subj,'content'=>$message));
	}
	
	/**
	 * Function used to wrap email content in 
	 * HTML AND BODY TAGS
	 */
	function wrap_email_content($content)
	{
		return '<html><body>'.$content.'</body></html>';
	}
	
	/**
	 * Function used to get file name
	 */
	function GetName($file){
		$path = explode('/',$file);
		if(is_array($path))
			$file = $path[count($path)-1];
		$new_name 	 = substr($file, 0, strrpos($file, '.'));
		return $new_name;
	}

        function get_elapsed_time($ts,$datetime=1)
        {
          if($datetime == 1)
          {
          $ts = date('U',strtotime($ts));
          }
          $mins = floor((time() - $ts) / 60);
          $hours = floor($mins / 60);
          $mins -= $hours * 60;
          $days = floor($hours / 24);
          $hours -= $days * 24;
          $weeks = floor($days / 7);
          $days -= $weeks * 7;
          $t = "";
          if ($weeks > 0)
            return "$weeks week" . ($weeks > 1 ? "s" : "");
          if ($days > 0)
            return "$days day" . ($days > 1 ? "s" : "");
          if ($hours > 0)
            return "$hours hour" . ($hours > 1 ? "s" : "");
          if ($mins > 0)
            return "$mins min" . ($mins > 1 ? "s" : "");
          return "< 1 min";
        }

	//Function Used TO Get Extensio Of File
		function GetExt($file){
			return substr($file, strrpos($file,'.') + 1);
			}

	function old_set_time($temps)
	{
			round($temps);
			$heures = floor($temps / 3600);
			$minutes = round(floor(($temps - ($heures * 3600)) / 60));
			if ($minutes < 10)
					$minutes = "0" . round($minutes);
			$secondes = round($temps - ($heures * 3600) - ($minutes * 60));
			if ($secondes < 10)
					$secondes = "0" .  round($secondes);
			return $minutes . ':' . $secondes;
	}
	function SetTime($sec, $padHours = true) {
	
		if($sec < 3600)
			return old_set_time($sec);
			
		$hms = "";
	
		// there are 3600 seconds in an hour, so if we
		// divide total seconds by 3600 and throw away
		// the remainder, we've got the number of hours
		$hours = intval(intval($sec) / 3600);
	
		// add to $hms, with a leading 0 if asked for
		$hms .= ($padHours)
			  ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
			  : $hours. ':';
	
		// dividing the total seconds by 60 will give us
		// the number of minutes, but we're interested in
		// minutes past the hour: to get that, we need to
		// divide by 60 again and keep the remainder
		$minutes = intval(($sec / 60) % 60);
	
		// then add to $hms (with a leading 0 if needed)
		$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
	
		// seconds are simple - just divide the total
		// seconds by 60 and keep the remainder
		$seconds = intval($sec % 60);
	
		// add to $hms, again with a leading 0 if needed
		$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
	
		return $hms;
	}
	
	//Simple Validation
	function isValidText($text){
      $pattern = "^^[_a-z0-9-]+$";
      if (eregi($pattern, $text)){
         return true;
      	}else {
         return false;
      }   
   }	
   
   //Function Used To Validate Email
	
	function isValidEmail($email){
      $pattern = "/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
	  preg_match($pattern, $email,$matches);
      if ($matches[0]!=''){
         return true;
      }
      else {
		 if(!DEVELOPMENT_MODE)
         	return false;
		 else
		 	return true;
      }   
   }

   
   	// THIS FUNCTION SETS HTMLSPECIALCHARS_DECODE IF FUNCTION DOESN'T EXIST
	// INPUT: $text REPRESENTING THE TEXT TO DECODE
	//	  $ent_quotes (OPTIONAL) REPRESENTING WHETHER TO REPLACE DOUBLE QUOTES, ETC
	// OUTPUT: A STRING WITH HTML CHARACTERS DECODED
	if(!function_exists('htmlspecialchars_decode')) {
		function htmlspecialchars_decode($text, $ent_quotes = "") {
		$text = str_replace("&quot;", "\"", $text);
		$text = str_replace("&#039;", "'", $text);
		$text = str_replace("&lt;", "<", $text);
		$text = str_replace("&gt;", ">", $text);
		$text = str_replace("&amp;", "&", $text);
		return $text;
		}
	} // END htmlspecialchars() FUNCTION
	
	//THIS FUNCTION IS USED TO LIST FILE TYPES IN FLASH UPLOAD
	//INPUT FILE TYPES
	//OUTPUT FILE TYPE IN PROPER FORMAT
	function ListFileTypes($types){
		$types_array = preg_replace('/,/',' ',$types);
		$types_array = explode(' ',$types_array);
		$list = 'Video,';
		for($i=0;$i<=count($types_array);$i++){
		if($types_array[$i]!=''){
		$list .= '*.'.$types_array[$i];
		if($i!=count($types_array))$list .= ';';
		}
		}
	return $list;
	}
	
	
	//FUNCTION USED TO FORMAT FILE SIZE
	//INPUT BYTES
	//OUTPT MB , Kib
	function formatfilesize( $data ) {
        // bytes
        if( $data < 1024 ) {
            return $data . " bytes";
        }
        // kilobytes
        else if( $data < 1024000 ) {
				return round( ( $data / 1024 ), 1 ) . "KB";
        }
        // megabytes
        else if($data < 1024000000){
            return round( ( $data / 1024000 ), 1 ) . " MB";
        }else{
			 return round( ( $data / 1024000000 ), 1 ) . " GB";
		}
    
    }
	
	/**
	 * FUNCTION USED TO GET THUMBNAIL
	 * @param ARRAY video_details, or videoid will also work
	 */
	 
	function get_thumb($vdetails,$num='default',$multi=false,$count=false,$return_full_path=true,$return_big=true){
		global $db,$Cbucket,$myquery;
		$num = $num ? $num : 'default';
		#checking what kind of input we have
		if(is_array($vdetails))
		{
			if(empty($vdetails['title']))
			{
				#check for videoid
				if(empty($vdetails['videoid']) && empty($vdetails['vid']) && empty($vdetails['videokey']))
				{
					if($multi)
						return $dthumb[0] = default_thumb();
					return default_thumb();
				}else{
					if(!empty($vdetails['videoid']))
						$vid = $vdetails['videoid'];
					elseif(!empty($vdetails['vid']))
						$vid = $vdetails['vid'];
					elseif(!empty($vdetails['videokey']))
						$vid = $vdetails['videokey'];
					else
					{
						if($multi)
							return $dthumb[0] = default_thumb();
						return default_thumb();
					}
				}
			}
		}else{
			if(is_numeric($vdetails))
				$vid = $vdetails;
			else
			{
				if($multi)
					return $dthumb[0] = default_thumb();
				return default_thumb();
			}
		}
			
		
		#checking if we have vid , so fetch the details
		if(!empty($vid))
			$vdetails = $myquery->get_video_details($vid);
		
		if(empty($vdetails['title']))
		{
			if($multi)
					return default_thumb();
			return default_thumb();
		}
			
		#Checking if there is any custom function for
		if(count($Cbucket->custom_get_thumb_funcs)>0)
		foreach($Cbucket->custom_get_thumb_funcs as $funcs)
		{
			if(function_exists($funcs))
				return $funcs($vdetails);
		}
		
		#get all possible thumbs of video
		$vid_thumbs = glob(THUMBS_DIR."/".$vdetails['file_name']."*");
		#replace Dir with URL
		foreach($vid_thumbs as $thumb)
		{
			$thumb_parts = explode('/',$thumb);
			$thumb_file = $thumb_parts[count($thumb_parts)-1];
			
			if(!is_big($thumb_file) || $return_big)
			{
				if($return_full_path)
					$thumbs[] = THUMBS_URL.'/'.$thumb_file;
				else
					$thumbs[] = $thumb_file;
			}
		}
		
		if(count($thumbs)==0)
		{
			if($count)
				return count($thumbs);
			if($multi)
					return $dthumb[0] = default_thumb();
			return default_thumb();
		}
		else
		{
			if($multi)
				return $thumbs;
			if($count)
				return count($thumbs);
			
			//Now checking for thumb
			if($num=='default')
			{
				$num = $vdetails['default_thumb'];
			}
			$vdetails['file_name'].'-'.$num;
			$default_thumb = array_find($vdetails['file_name'].'-'.$num,$thumbs);
			
			if(!empty($default_thumb))
				return $default_thumb;
			return $thumbs[0];
		}
		
	}
	
	/**
	 * Function used to check weaether given thumb is big or not
	 */
	function is_big($thumb_file)
	{
		$total = strlen($thumb_file);
		$name = substr($thumb_file,$strlen-7,3);
		if($name=='big')
			return true;
		else
			return false;
	}
	function GetThumb($vdetails,$num='default',$multi=false,$count=false)
	{

		return get_thumb($vdetails,$num,$multi,$count);
	}
	
	/**
	 * function used to get detaulf thumb of ClipBucket 
	 */
	 function default_thumb()
	 {
		return BASEURL.'/files/thumbs/processing.jpg';
	 }
	 
	/** 
	 * Function used to check weather give thumb is deafult or not
	 */
	function is_default_thumb($i)
	{
		if(getname($i)=='processing.jpg')
			return true;
		else
			return false;
	}
	 
	 
	//TEST EXCEC FUNCTION 
	function test_exec( $cmd )
	{
		echo '<div border="1px">';
		echo '<h1>' . htmlentities( $cmd ) . '</h1>';

		if (stristr(PHP_OS, 'WIN')) { 
			$cmd = $cmd;
		}else{
			$cmd = "PATH=\$PATH:/bin:/usr/bin:/usr/local/bin bash -c \"$cmd\"";
		}
		$data = shell_exec( $cmd );
		if( $data === false )
			echo "<p>FAILED: $cmd</p></div>";
		echo '<p><pre>' . htmlentities( $data ) . '</pre></p></div>';
	}
	
	
	/**
	 * Function used to get video link
	 * @param ARRAY video details
	 */
	function video_link($vdetails)
	{
		global $myquery;
		#checking what kind of input we have
		if(is_array($vdetails))
		{
			if(empty($vdetails['title']))
			{
				#check for videoid
				if(empty($vdetails['videoid']) && empty($vdetails['vid']) && empty($vdetails['videokey']))
				{
					return BASEURL;
				}else{
					if(!empty($vdetails['videoid']))
						$vid = $vdetails['videoid'];
					elseif(!empty($vdetails['vid']))
						$vid = $vdetails['vid'];
					elseif(!empty($vdetails['videokey']))
						$vid = $vdetails['videokey'];
					else
						return BASEURL;
				}
			}
		}else{
			if(is_numeric($vdetails))
				$vid = $vdetails;
			else
				return BASEURL;
		}		
		#checking if we have vid , so fetch the details
		if(!empty($vid))
			$vdetails = $myquery->get_video_details($vid);
		
		$plist = "";
		if(SEO == 'yes'){
			if($vdetails['playlist_id'])
				$plist = '?&play_list='.$vdetails['playlist_id'];
			$link = BASEURL.'/video/'.$vdetails['videokey'].'/'.SEO(clean(str_replace(' ','-',$vdetails['title']))).$plist;
		}else{
			if($vdetails['playlist_id'])
				$plist = '&play_list='.$vdetails['playlist_id'];
			$link = BASEURL.'/watch_video.php?v='.$vdetails['videokey'].$plist;
		}
		return $link;
	}
	
	
	//Function That will use in creating SEO urls
	function VideoLink($vdetails){
		return video_link($vdetails);
	} 
	
	/**
	 * Group Link
	 */
	function group_link($params)
	{
		$grp = $params['details'];
		$id = $grp['group_id'];
		$name = $grp['group_name'];
		$url = $grp['group_url'];
		
		if($params['type']=='' || $params['type']=='group')
		{
			if(SEO==yes)
				return BASEURL.'/group/'.$url;
			else
				return BASEURL.'/view_group.php?url='.$url;
		}
		
		if($params['type']=='view_members')
		{
			return BASEURL.'/view_group_members.php?url='.$url;
			if(SEO==yes)
				return BASEURL.'/group_members/'.$url;
			else
				return BASEURL.'/view_group_members.php?url='.$url;
		}
		
		if($params['type']=='view_videos')
		{
			return BASEURL.'/view_group_videos.php?url='.$url;
			if(SEO==yes)
				return BASEURL.'/group_videos/'.$url;
			else
				return BASEURL.'/view_group_videos.php?url='.$url;
		}
	}
	
	/**
	* FUNCTION USED TO GET ADVERTISMENT
	* @param : array(Ad Code, LIMIT);
	*/
	function getAd($params)
	{
		global $adsObj;
		$data = '';
		if($params['style'] || $params['class'] || $params['align'])
			$data .= '<div style="'.$params['style'].'" class="'.$params['class'].'" align="'.$params['align'].'">';
		$data .= ad($adsObj->getAd($params['place']));
		if($params['style'] || $params['class'] || $params['align'])
			$data .= '</div>';
		return $data;
	}
	
	/**
	* FUNCTION USED TO GET THUMBNAIL, MADE FOR SMARTY
	* @ param : array("FLV");
	*/
	function getSmartyThumb($params)
	{
		return get_thumb($params['vdetails'],$params['num'],$params['multi'],$params['count_only']);
	}
	
	/**
	* Function Used to format video duration
	* @param : array(videoKey or ID,videok TITLE)
	*/
	
	function videoSmartyLink($params)
	{
		return	VideoLink($params['vdetails']);
	}
	
	/**
	* FUNCTION USED TO GET VIDEO RATING IN SMARTY
	* @param : array(pullRating($videos[$id]['videoid'],false,false,false,'novote');
	*/
	function pullSmartyRating($param)
	{
		return pullRating($param['id'],$param['show5'],$param['showPerc'],$aram['showVotes'],$param['static']);	
	}
	
	/**
	* FUNCTION USED TO CLEAN VALUES THAT CAN BE USED IN FORMS
	*/
	function cleanForm($string)
	{
		$string = htmlspecialchars($string);
		return $string;
	}
	function form_val($string){return cleanForm($string); }
	
	/**
	* FUNCTION USED TO MAKE TAGS MORE PERFECT
	* @Author : Arslan Hassan <arslan@clip-bucket.com,arslan@labguru.com>
	* @param tags text unformatted
	* returns tags formatted
	*/
	function genTags($tags,$sep=',')
	{
		//Remove fazool spaces
		$tags = preg_replace(array('/ ,/','/, /'),',',$tags);
		$tags = preg_replace( "`[,]+`" , ",", $tags);
		$tag_array = explode($sep,$tags);
		foreach($tag_array as $tag)
		{
			if(isValidtag($tag))
			{
				$newTags[] = $tag;
			}
			
		}
		//Creating new tag string
		$tagString = implode(',',$newTags);
		return $tagString;
	}
	
	/**
	* FUNCTION USED TO VALIDATE TAG
	* @Author : Arslan Hassan <arslan@clip-bucket.com,arslan@labguru.com>
	* @param tag
	* return true or false
	*/
	function isValidtag($tag)
	{
		$disallow_array = array
		('of','is','no','on','off','a','the','why','how','what','in');
		if(!in_array($tag,$disallow_array))
			return true;
		else
			return false;
	}
	
	
	/**
	* FUNCTION USED TO GET CATEGORY LIST
	*/
	function getCategoryList($type='video',$with_all=false)
	{
		switch ($type)
		{
			case "video":
			default:
			{
				global $cbvid;
				if($with_all)
					$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
				$cats = $cbvid->get_categories();
				if($all_cat)
				$cats = array_merge($all_cat,$cats);
				return $cats;
			}
			break;
			case "user":
			{
				global $userquery;
				if($with_all)
					$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
				
				$cats = $userquery->get_categories();
				if($all_cat)
				$cats = array_merge($all_cat,$cats);
				return $cats;
			}
			break;
			
			case "group":
			case "groups":
			{
				global $cbgroup;
				if($with_all)
					$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
				
				$cats = $cbgroup->get_categories();
				if($all_cat)
				$cats = array_merge($all_cat,$cats);
				return $cats;
			}
			break;
		}
	}
	function getSmartyCategoryList($params)
	{
		return getCategoryList($params['type']);
	}
	
	
	//Function used to register function as multiple modifiers
	
	
	
	/**
	* Function used to insert data in database
	* @param : table name
	* @param : fields array
	* @param : values array
	* @param : extra params
	*/
	function dbInsert($tbl,$flds,$vls,$ep=NULL)
	{
		global $db ;
		$total_fields = count($flds);
		$count = 0;
		foreach($flds as $field)
		{
			$count++;
			$fields_query .= $field;
			if($total_fields!=$count)
				$fields_query .= ',';
		}
		$total_values = count($vls);
		$count = 0;
		foreach($vls as $value)
		{
			$count++;
			$val = mysql_clean($value);
			$needle = substr($val,0,3);
			
			if($needle != '|f|')
				$values_query .= "'".$val."'";
			else
			{
				$val = substr($val,3,strlen($val));
				$values_query .= "'".$val."'";
			}
			
			
			if($total_values!=$count)
				$values_query .= ',';
		}
		//Complete Query
		$query = "INSERT INTO $tbl ($fields_query) VALUES ($values_query) $ep";
		//if(!mysql_query($query)) die(mysql_error());
		$db->Execute($query);
		if(mysql_error()) die ($db->db_query.'<br>'.mysql_error());
		return $db->insert_id();
	}
	
	/**
	* Function used to Update data in database
	* @param : table name
	* @param : fields array
	* @param : values array
	* @param : Condition params
	* @params : Extra params
	*/
	function dbUpdate($tbl,$flds,$vls,$cond,$ep=NULL)
	{
		global $db ;
		
		$total_fields = count($flds);
		$count = 0;
		for($i=0;$i<$total_fields;$i++)
		{
			$count++;
			//$val = mysql_clean($vls[$i]);
			$val = ($vls[$i]);
			$needle = substr($val,0,3);
			if($needle != '|f|')
				$fields_query .= $flds[$i]."='".$val."'";
			else
			{
				$val = substr($val,3,strlen($val));
				$fields_query .= $flds[$i]."=".$val."";
			}
			if($total_fields!=$count)
				$fields_query .= ',';
		}
		//Complete Query
		$query = "UPDATE $tbl SET $fields_query WHERE $cond $ep";
		//if(!mysql_query($query)) die($query.'<br>'.mysql_error());
		$db->Execute($query);
		if(mysql_error()) die ($db->db_query.'<br>'.mysql_error());
		return $query;
	}
	
	
	
	/**
	* Function used to Delete data in database
	* @param : table name
	* @param : fields array
	* @param : values array
	* @params : Extra params
	*/
	function dbDelete($tbl,$flds,$vls,$ep=NULL)
	{
		global $db ;
		$total_fields = count($flds);
		$count = 0;
		for($i=0;$i<$total_fields;$i++)
		{
			$count++;
			$val = mysql_clean($vls[$i]);
			$needle = substr($val,0,3);
			if($needle != '|f|')
				$fields_query .= $flds[$i]."='".$val."'";
			else
			{
				$val = substr($val,3,strlen($val));
				$fields_query .= $flds[$i]."=".$val."";
			}
			if($total_fields!=$count)
				$fields_query .= ' AND ';
		}
		//Complete Query
		$query = "DELETE FROM $tbl WHERE $fields_query $ep";
		//if(!mysql_query($query)) die(mysql_error());
		$db->Execute($query);
		if(mysql_error()) die ($db->db_query.'<br>'.mysql_error());
	}
	
	
	
	/**
	 * Insert Id
	 */
	 function get_id($code)
	 {
		 global $Cbucket;
		 $id = $Cbucket->ids[$code];
		 if(empty($id)) $id = $code;
		 return $id;
	 }
	 
	/**
	 * Set Id
	 */
	 function set_id($code,$id)
	 {
		 global $Cbucket;
		 return $Cbucket->ids[$code]=$id;
	 }
	 
	
	/**
	 * Function used to select data from database
	 */
	function dbselect($tbl,$fields='*',$cond=false,$limit=false,$order=false)
	{
		global $db;
		$query_params = '';
		//Making Condition possible
		if($cond)
		$where = " WHERE ";
		else
		$where = false;
		
		$query_params .= $where;
		if($where)
		{
			$query_params .= $cond;
		}
		
		if($order)
			$query_params .= " ORDER BY $order ";
		if($limit)
			$query_params .= " LIMIT $limit ";
			
		$query = " SELECT $fields FROM $tbl $query_params ";

		//Finally Executing	
		$data = $db->Execute($query);
		$db->num_rows = $data->_numOfRows;

		//Now Get Rows and return that data
		if($db->num_rows > 0)
			return $data->getrows();
		else
			return false;
	}
	
	
	/**
	 * Function used to count fields in mysql
	 * @param TABLE NAME
	 * @param Fields
	 * @param condition
	 */
	function dbcount($tbl,$fields='*',$cond=false)
	{
		global $db;
		if($cond)
			$condition = " Where $cond ";
		$query = "Select Count($fields) From $tbl $condition";
		$result = $db->Execute($query);
		return $result->fields[0];
	}
	
	/**
	 * An easy function for erorrs and messages (e is basically short form of exception)
	 * I dont want to use the whole Trigger and Exception code, so e pretty works for me :D
	 * @param TEXT $msg
	 * @param TYPE $type (e for Error, m for Message
	 * @param INT $id Any Predefined Message ID
	 */
	
	function e($msg=NULL,$type='e',$id=NULL)
	{
		global $eh;
		if(!empty($msg))
		return $eh->e($msg,$type,$id);
	}
	
	
	/**
	 * Function used to get subscription template
	 */
	function get_subscription_template()
	{
		global $LANG;
		return $LANG['user_subscribe_message'];
	}
	
	
	/**
	 * Short form of print_r as pr
	 */
	function pr($text)
	{
		print_r($text);
	}
	
	
	/**
	 * This function is used to call function in smarty template
	 * This wont let you pass parameters to the function, but it will only call it
	 */
	function FUNC($params)
	{
		global $Cbucket;
		//Function used to call functions by
		//{func namefunction_name}
		// in smarty
		$func=$params['name'];
		if(function_exists($func))
			$func();
	}
	
	/**
	 * Function used to get userid anywhere 
	 * if there is no user_id it will return false
	 */
	function user_id()
	{
		global $userquery;
		if($userquery->userid !='') return $userquery->userid; else false;
	}
	//replica
	function userid(){return user_id();}
	
	/**
	 * Function used to get username anywhere 
	 * if there is no usern_name it will return false
	 */
	function user_name()
	{
		global $userquery;
		if($userquery->user_name)
			return $userquery->user_name;
		else
			return $userquery->get_logged_username();
	}
	function username(){return user_name();}
	
	/**
	 * Function used to check weather user access or not
	 */
	function has_access($access,$check_only=TRUE)
	{
		global $userquery;
		return $userquery->login_check($access,$check_only);
	}
	
	/**
	 * Function used to return mysql time
	 * @author : Fwhite
	 */
	function NOW()
	{
		return date('Y-m-d H:i:s', time());
	}
	
	
	/**
	 * Function used to get Regular Expression from database
	 * @param : code
	 */
	function get_re($code)
	{
		global $db;
		$results = $db->select("validation_re","*"," re_code='$code'");
		if($db->num_rows>0)
		{
			return $results[0]['re_syntax'];
		}else{
			return false;
		}
	}
	function get_regular_expression($code)
	{
		return get_re($code); 
	}
	
	/**
	 * Function used to check weather input is valid or not
	 * based on preg_match
	 */
	function check_re($syntax,$text)
	{
		preg_match('/'.$syntax.'/',$text,$matches);
		if(!empty($matches[0]))
		{
			return true;
		}else{
			return false;
		}
	}
	function check_regular_expression($code,$text)
	{
		return check_re($code,$text); 
	}
	
	/**
	 * Function used to check field directly
	 */
	function validate_field($code,$text)
	{
		$syntax =  get_re($code);
		if(empty($syntax))
			return true;
		return check_regular_expression($syntax,$text);
	}
	
	function is_valid_syntax($code,$text)
	{
		if(DEVELOPMENT_MODE && DEV_INGNORE_SYNTAX)
			return true;
		return validate_field($code,$text);
	}
	
	/**
	 * Function used to apply function on a value
	 */
	function is_valid_value($func,$val)
	{
		if(!function_exists($func))
			return true;
		elseif(!$func($val))
			return false;
		else
			return true;
	}
	
	function apply_func($func,$val)
	{
		if(is_array($func))
		{
			foreach($func as $f)
				if(function_exists($f))
					$val = $f($val);
		}else{
			$val = $func($val);
		}
		return $val;
	}
	
	/**
	 * Function used to validate YES or NO input
	 */
	function yes_or_no($input,$return=yes)
	{
		$input = strtolower($input);
		if($input!=yes && $input !=no)
			return $return;
		else
			return $input;
	}
	
	/**
	 * Function used to validate category
	 * INPUT $cat array
	 */
	function validate_vid_category($array=NULL)
	{
		global $myquery,$LANG,$cbvid;
		if($array==NULL)
			$array = $_POST['category'];
		if(count($array)==0)
			return false;
		else
		{
			
			foreach($array as $arr)
			{
				if($cbvid->category_exists($arr))
					$new_array[] = $arr;
			}
		}
		if(count($new_array)==0)
		{
			e($LANG['vdo_cat_err3']);
			return false;
		}elseif(count($new_array)>ALLOWED_CATEGORIES)
		{
			e(sprintf($LANG['vdo_cat_err2'],ALLOWED_CATEGORIES));
			return false;
		}
			
		return true;
	}
	
	/**
	 * Function used to validate category
	 * INPUT $cat array
	 */
	function validate_group_category($array=NULL)
	{
		global $cbgroup;
		return $cbgroup->validate_group_category($array);
	}
	
		
	/**
	 * Function used to check videokey exists or not
	 * key_exists
	 */
	function vkey_exists($key)
	{
		global $db;
		$db->select("video","videokey"," videokey='$key'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to check file_name exists or not
	 * as its a unique name so it will not let repost the data
	 */
	function file_name_exists($name)
	{
		global $db;
		$results = $db->select("video","videoid,file_name"," file_name='$name'");
		
		if($db->num_rows >0)
			return $results[0]['videoid'];
		else
			return false;
	}
	
	
	
	/**
	 * Function used to get video from conversion queue
	 */
	function get_queued_video($update=TRUE)
	{
		global $db;
		$results = $db->select("conversion_queue","*","cqueue_conversion='no'",1);
		$result = $results[0];
		if($update)
		$db->update("conversion_queue",array("cqueue_conversion"),array("p")," cqueue_id = '".$result['cqueue_id']."'");
		return $result;
	}

	
	
	/**
	 * Function used to get video being processed
	 */
	function get_video_being_processed()
	{
		global $db;
		$results = $db->select("conversion_queue","*","cqueue_conversion='p'");
		return $results;
	}
	
	function get_video_details($vid=NULL)
	{
		global $myquery;
		if(!$vid)
			global $vid;	
		return $myquery->get_video_details($vid);
	}
	
	
	
	/**
	 * Function used to get all video files
	 * @param Vdetails
	 * @param $count_only
	 * @param $with_path
	 */
	function get_all_video_files($vdetails,$count_only=false,$with_path=false)
	{
		$details = get_video_file($vdetails,true,$with_path,true,$count_only);
		if($count_only)
			return count($details);
		return $details;
	}
	function get_all_video_files_smarty($params)
	{
		$vdetails = $params['vdetails'];
		$count_only = $params['count_only'];
		$with_path = $params['with_path'];
		return get_all_video_files($vdetails,$count_only,$with_path);
	}
	
	/**
	 * Function use to get video files
	 */
	function get_video_file($vdetails,$return_default=true,$with_path=true,$multi=false,$count_only=false,$hq=false)
	{
		# checking if there is any other functions
		# available
		if(is_array($Cbucket->custom_video_file_funcs))
		foreach($Cbucket->custom_video_file_funcs as $funcs)
			if(function_exists($func))
				return $func($vdetails);
		
		#Now there is no function so lets continue as
		$vid_files = glob(VIDEOS_DIR."/".$vdetails['file_name']."*");

		#replace Dir with URL
		foreach($vid_files as $file)
		{
			$files_part = explode('/',$file);
			$video_file = $files_part[count($files_part)-1];
			
			if($with_path)
				$files[]	= VIDEOS_URL.'/'.$video_file;
			else
				$files[]	= $video_file;
		}
		
		if(count($files)==0 && !$multi && !$count_only)
		{
			if($return_default)
			{
				if($with_path)
					return VIDEOS_URL.'/no_video.flv';
				else
					return 'no_video.flv';
			}else{
				return false;
			}
		}else{
			if($multi)
				return $files;
			if($count_only)
				return count($files);
			
			foreach($files as $file)
			{
				if($hq)
				{
					if(getext($file)=='mp4')
					{
	 					return $file;
						break;
					}
				}else{
					return $file;
					break;
				}
			}
			return $files[0];
		}
	}
	
	/**
	 * FUnction used to get HQ ie mp4 video
	 */
	function get_hq_video_file($vdetails)
	{
		return get_video_file($vdetails,true,true,false,false,true);
	}
	
	
	/**
	 * Function used to display flash player for ClipBucket video
	 */
	function flashPlayer($param)
	{
		global $Cbucket,$swfobj;
		
		$param['player_div'] = $param['player_div'] ? $param['player_div'] : 'videoPlayer';
		
		$key 		= $param['key'];
		$flv 		= $param['flv'].'.flv';
		$code 		= $param['code'];
		$flv_url 	= $file;
		$embed 		= $param['embed'];
		$code 		= $param['code'];
		$height 	= $param['height'] = $param['height'] ? $param['height'] : config('player_height');
		$width 		= $param['width'] = $param['width'] ? $param['width'] : config('player_width');
		
		if(count($Cbucket->actions_play_video)>0)
		{
	 		foreach($Cbucket->actions_play_video as $funcs)
			{
				if(function_exists($funcs))
				{
					$func_data = $funcs($param);
				}
				if($func_data)
					return $func_data;
			}
		}
		
		if(function_exists('cbplayer'))
			return cbplayer($param,true);
		return blank_screen($param);
	}
	
	
	/**
	 * FUnctiuon used to plya HQ videos
	 */
	function HQflashPlayer($param)
	{
		return flashPlayer($param);
	}
	
	
	/**
	 * Function used to get player from website settings
	 */
	function get_player()
	{
		global $Cbucket;
		return $Cbucket->configs['player_file'];
	}
	
	
	/**
	 * Function used to get user avatar
	 * @param ARRAY $userdetail
	 * @param SIZE $int
	 */
	function avatar($param)
	{
		global $userquery;
		$udetails = $param['details'];
		$size = $param['size'];
		$uid = $param['uid'];
		return $userquery->avatar($udetails,$size,$uid);
	}
	
	
	/**
	 * This funcion used to call function dynamically in smarty
	 */
	function load_form($param)
	{
		$func = $param['name'];
		if(function_exists($func))
			return $func($param);
	}
	
	
	
	/**
	 * Function used to get PHP Path
	 */
	 function php_path()
	 {
		 if(PHP_PATH !='')
			 return PHP_PATH;
		 else
		 	return "/usr/bin/php";
	 }
	 
	 
	 
	 
	 
	 
	/**
	 * Function in case htmlspecialchars_decode does not exist
	 */
	function unhtmlentities ($string)
	{
		$trans_tbl =get_html_translation_table (HTML_ENTITIES );
		$trans_tbl =array_flip ($trans_tbl );
		return strtr ($string ,$trans_tbl );
	}
	
	
	
	/**
	 * Function used to update processed video
	 * @param Files details
	 */
	function update_processed_video($file_array,$status='Successful')
	{
		global $db;
		$file = $file_array['cqueue_name'];
		$array = explode('-',$file);
		
		if(!empty($array[0]))
			$file_name = $array[0];
		$file_name = $file;
		
		$file_path = VIDEOS_DIR.'/'.$file_array['cqueue_name'].'.flv';

		if(file_exists($file_path))
		{		
			$file_size = filesize($file_path);
			//Now we will update video where file_name = $file_name
			if($file_size>0)
			{
				//Get Duration 
				$stats = get_file_details($file_name);
				$db->update("video",array("status","duration"),array($status,$stats['duration'])," file_name='".$file_name."'");
			}
		}	
	}
	
	
	/**
	 * This function will activate the video if file exists
	 */
	function activate_video_with_file($vid)
	{
		global $db;
		$vdetails = get_video_details($vid);
		$file_name = $vdetails['file_name'];
		$results = $db->select("conversion_queue","*"," cqueue_name='$file_name' AND cqueue_conversion='yes'");
		$result = $results[0];
		update_processed_video($result);							   
	}
	
	
	/**
	 * Function Used to get video file stats from database
	 * @param FILE_NAME
	 */
	function get_file_details($file_name)
	{
		global $db;
		//$result = $db->select("video_files","*"," id ='$file_name' OR src_name = '$file_name' ");
		//Reading Log File
		$file = LOGS_DIR.'/'.$file_name.'.log';
		if(file_exists($file))
		{
			$data = file_get_contents($file);
			//$file = file_get_contents('1260270267.log');
			
			preg_match_all('/(.*) : (.*)/',trim($data),$matches);
			
			$matches_1 = ($matches[1]);
			$matches_2 = ($matches[2]);
			
			for($i=0;$i<count($matches_1);$i++)
			{
				$statistics[trim($matches_1[$i])] = trim($matches_2[$i]);
			}
			if(count($matches_1)==0)
			{
				return false;
			}
			$statistics['conversion_log'] = $data;
			return $statistics;
		}else
			return false;
	}
	
	
	/**
	 * Function used to execute command in background
	 */
	function bgexec($cmd) {
		if (substr(php_uname(), 0, 7) == "Windows"){
			//exec($cmd." >> /dev/null &");
			exec($cmd);
			//pclose(popen("start \"bla\" \"" . $exe . "\" " . escapeshellarg($args), "r")); 
		}else{
			exec($cmd . " > /dev/null &");  
		}
	}
	
	
	/**
	 * Function used to get thumbnail number from its name
	 */
	function get_thumb_num($name)
	{
		$list = explode('-',$name);
		$list = explode('.',$list[1]);
		return  $list[0];
	}
	
	
	/**
	 * Function used to remove thumb
	 */
	function delete_video_thumb($file)
	{
		global $LANG;
		$path = THUMBS_DIR.'/'.$file;
		if(file_exists($path))
		{
			unlink($path);
			e($LANG['video_thumb_delete_msg'],m);
		}else{
			e($LANG['video_thumb_delete_err']);
		}
	}
	 
	 
	/**
	 * Function used to get array value
	 * if you know partial value of array and wants to know complete 
	 * value of an array, this function is being used then
	 */
	function array_find($needle, $haystack)
	{
	   foreach ($haystack as $item)
	   {
		  if (strpos($item, $needle) !== FALSE)
		  {
			 return $item;
			 break;
		  }
	   }
	}

	
	
	/**
	 * Function used to give output in proper form 
	 */
	function input_value($params)
	{
		$input = $params['input'];
		$value = $input['value'];
		if($input['value_field']=='checked')
			$value = $input['checked'];
		if($input['return_checked'])
			return $input['checked'];
		if(function_exists($input['display_function']))
			return $input['display_function']($value);
		else
			return $input['value'];
	}
	
	/**
	 * Function used to convert input to categories
	 * @param input can be an array or #12# like
	 */
	function convert_to_categories($input)
	{
		if(is_array($input))
		{
			foreach($input as $in)
			{		
				if(is_array($in))
				{
					foreach($in as $i)
					{
						if(is_array($i))
						{
							foreach($i as $info)
							{
								$cat_details = get_category($info);
								$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
							}
						}elseif(is_numeric($i)){
							$cat_details = get_category($i);
							$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
						}
					}
				}elseif(is_numeric($in)){
					$cat_details = get_category($in);
					$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
				}
			}
		}else{
			preg_match_all('/#([0-9]+)#/',$default['category'],$m);
			$cat_array = array($m[1]);
			foreach($cat_array as $i)
			{
				$cat_details = get_category($i);
				$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
			}
		}
		
		$count = 1;
		if(is_array($cat_array))
		{
			foreach($cat_array as $cat)
			{
				echo '<a href="'.$cat[0].'">'.$cat[1].'</a>';
				if($count!=count($cat_array))
				echo ', ';
				$count++;
			}
		}
	}
	
	
	
	/**
	 * Function used to get categorie details
	 */
	function get_category($id)
	{
		global $myquery;
		return $myquery->get_category($id);
	}
	
	
	/**
	 * Sharing OPT displaying
	 */
	function display_sharing_opt($input)
	{
		foreach($input as $key => $i)
		{
			return $key;
			break;
		}
	}
	
	/**
	 * Function used to get number of videos uploaded by user
	 * @param INT userid
	 * @param Conditions
	 */
	function get_user_vids($uid,$cond=NULL,$count_only=false)
	{
		global $userquery;
		return $userquery->get_user_vids($uid,$cond,$count_only);
	}
	
	
	
	/**
	 * Function used to get error_list
	 */
	function error_list()
	{
		global $eh;
		return $eh->error_list;
	}
	
	
	/**
	 * Function used to get msg_list
	 */
	function msg_list()
	{
		global $eh;
		return $eh->message_list;
	}
	
	
	/**
	 * Function used to add tempalte in display template list
	 */
	function template_files($file)
	{
		global $ClipBucket;
		$ClipBucket->template_files[] = $file;
	}
	
	
	/** 
	 * Function used to call display
	 */
	function display_it()
	{
		global $ClipBucket;
		$dir = LAYOUT;
		foreach($ClipBucket->template_files as $file)
		{
			if(file_exists(LAYOUT.'/'.$file))
			{
				if($ClipBucket->show_page)
					$new_list[] = $file;
			}
		}
		
		assign('template_files',$new_list);
		Template('body.html');
	}
	
	
	/**
	 * Function used to display hint
	 */
	function hint($hint)
	{
		
	}
	
	
	
	function showpagination($total,$page,$link,$extra_params=NULL,$tag='<a #params#>#page#</a>')
	{
		global $pages;
		return $pages->pagination($total,$page,$link,$extra_params,$tag);
	}
	
	
	/**
	 * Function used to check username is disallowed or not
	 * @param USERNAME
	 */
	function check_disallowed_user($username)
	{
		global $Cbucket;
		$disallowed_user = $Cbucket->configs['disallowed_usernames'];
		$censor_users = explode(',',$disallowed_user);
		if(in_array($username,$censor_users))
			return false;
		else
			return true;
	}

	
	/**
	 * Function used to validate username
	 * @input USERNAME
	 */
	function username_check($username)
	{
		global $Cbucket;
		$banned_words = $Cbucket->configs['disallowed_usernames'];
		$banned_words = explode(',',$banned_words);
		foreach($banned_words as $word)
		{
			preg_match("/$word/Ui",$username,$match);
			if(!empty($match[0]))
				return false;
		}
		
		return true;
	}
	
	
	
	
	
	/**
	 * Function used to check weather username already exists or not
	 * @input USERNAME
	 */
	function user_exists($user)
	{
		global $userquery;
		return $userquery->duplicate_user($user);
	}
	
	/**
	 * Function used to check weather email already exists or not
	 * @input email
	 */
	function email_exists($user)
	{
		global $userquery;
		return $userquery->duplicate_email($user);
	}
	
	/**
	 * function used to check weather group URL exists or not
	 */
	function group_url_exists($url)
	{
		global $cbgroup;
		return $cbgroup->group_url_exists($url);
	}
	
	
	/**
	 * Function used to check weather erro exists or not
	 */
	function error()
	{
		if(count(error_list())>0)
		{
			return error_list();
		}else{
			return false;
		}
	}
	
	/**
	 * Function used to check weather msg exists or not
	 */
	function msg()
	{
		if(count(msg_list())>0)
		{
			return msg_list();
		}else{
			return false;
		}
	}
	
	
	
	/**
	 * Function used to load plugin
	 * please check docs.clip-bucket.com
	 */
	function load_plugin()
	{
		global $cbplugin;
		
	}
	
	
	
	/**
	 * Function used to create limit functoin from current page & results
	 */
	function create_query_limit($page,$result)
	{
		$limit  = $result;	
		if(empty($page) || $page == 0 || !is_numeric($page)){
		$page   = 1;
		}
		$from 	= $page-1;
		$from 	= $from*$limit;
		
		return $from.','.$result;
	}
	
	
	/**
	 * Function used to get value from $_GET
	 */
	function get_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_GET[$val]);
		else
			return $_GET[$val];
	}function get($val){ return get_form_val($val); }
	
	/**
	 * Function used to get value form $_POST
	 */
	function post_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_POST[$val]);
		else
			$_POST[$val];
	}
	
	
	/**
	 * Function used to get value from $_REQUEST
	 */
	function request_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_REQUEST[$val]);
		else
			$_REQUEST[$val];
	}
	
	
	/**
	 * Function used to return LANG variable
	 */
	function lang($var)
	{
		global $LANG;
		if($LANG[$var])
		return $LANG[$var];
		else
		return $var;
	}
	function smarty_lang($param)
	{
		if($param['assign']=='')
			return lang($param['code']);
		else
			assign($param['assign'],lang($param['code']));
	}
	
	
	
	/**
	 * function used to remove video thumbs
	 */
	function remove_video_thumbs($vdetails)
	{
		global $cbvid;
		return $cbvid->remove_thumbs($vdetails);
	}
	
	/**
	 * function used to remove video log
	 */
	function remove_video_log($vdetails)
	{
		global $cbvid;
		return $cbvid->remove_log($vdetails);
	}
	
	/**
	 * function used to remove video files
	 */
	function remove_video_files($vdetails)
	{
		global $cbvid;
		return $cbvid->remove_files($vdetails);
	}
	
	
	/**
	 * Function used to get player logo
	 */
	function get_player_logo()
	{
		return PLAYER_URL.'/logo.png';
	}
	
	/**
	 * Function used to assign link
	 */
	function cblink($params)
	{
		global $ClipBucket;
		$name = $params['name'];
		$ref = $param['ref'];
		
		if($name=='category')
		{
			return category_link($params['data'],$params['type']);
		}
		if($name=='sort')
		{
			return sort_link($params['sort'],'sort',$params['type']);
		}
		if($name=='time')
		{
			return sort_link($params['sort'],'time',$params['type']);
		}
		if($name=='tag')
		{
			return BASEURL.'/search_result.php?keywords='.urlencode($params['tag']).'&type='.$params['type'];
		}
		if($name=='category_search')
		{
			return BASEURL.'/search_result.php?category[]='.$params['category'].'&type='.$params['type'];
		}
		
		if(SEO!='yes')
			$link = BASEURL.'/'.$ClipBucket->links[$name][0];
		else
			$link = BASEURL.'/'.$ClipBucket->links[$name][1];
		
		$param_link = "";
		if(!empty($params['extra_params']))
		{
			preg_match('/\?/',$link,$matches);
			if(!empty($matches[0]))
			{
				$param_link = '&'.$params['extra_params'];
			}else{
				$param_link = '?'.$params['extra_params'];
			}
		}
			
		return $link.$param_link;
	}
	
	/**
	 * Function used to check video is playlable or not
	 * @param vkey,vid
	 */
	function video_playable($id)
	{
		global $cbvideo;
		$vdo = $cbvideo->get_video($id);
		if(!$vdo)
		{
			e("Video does not exist");
			return false;
		}elseif($vdo['status']!='Successful')
		{
			e("This video is not working properly");
			if(!has_access('admin_access',TRUE))
				return false;
			else
				return true;
		}else{
			return true;
		}
	}
	
	
	/**
	 * Function used to show rating
	 * @inputs
	 * class : class used to show rating usually rating_stars
	 * rating : rating of video or something
	 * ratings : number of rating
	 * total : total rating or out of
	 */
	function show_rating($params)
	{
		$class 		= $params['class'] ? $params['class'] : 'rating_stars';
		$rating 	= $params['rating'];
		$ratings 	= $params['ratings'];
		$total 		= $params['total'];
		
		//Checking Percent
		if($rating<=0)
			$perc = '0';
		else
		{
			if($total<=1)
				$total = 1;
			$perc = $rating*100/$total;
		}
				
		$perc = $perc.'%';
		
		$rating = '<div class="'.$class.'">
					<div class="stars_blank">
						<div class="stars_filled" style="width:'.$perc.'">&nbsp;</div>
						<div class="clear"></div>
					</div>
				  </div>';
		return $rating;
	}
	

	/**
	 * Function used to display
	 * Blank Screen
	 * if there is nothing to play or to show
	 * then who a blank screen
	 */
	function blank_screen($data)
	{
		global $swfobj;
		$code = '<div class="blank_screen" align="center">No Player or Video File Found - Unable to Play Any Video</div>';
		$swfobj->EmbedCode(unhtmlentities($code),$data['player_div']);
		return $swfobj->code;
	}
	
	
	/**
	 * Function used to check weather video has Mp4 file or not
	 */
	function has_hq($vdetails,$is_file=false)
	{
		if(!$is_file)
			$file = get_hq_video_file($vdetails);
		else
			$file = $vdetails;
			
		if(getext($file)=='mp4')
			return $file;
		else
			return false;
	}
	
	/**
	 * Function used to display an ad
	 */
	function ad($in)
	{
		return stripslashes(htmlspecialchars_decode($in));
	}
	
	
	/**
	 * Function used to get
	 * available function list
	 * for special place , read docs.clip-bucket.com
	 */
	function get_functions($name)
	{
		global $Cbucket;
		$funcs = $CBucket->$name;
		if(is_array($funcs) && count($funcs)>0)
			return $funcs;
		else
			return false;
	}
	
	
	/**
	 * Function used to add js in ClipBuckets JSArray
	 * see docs.clip-bucket.com
	 */
	function add_js($files)
	{
		global $Cbucket;
		return $Cbucket->addJS($files);
	}
	
	/**
	 * Function add_header()
	 * this will be used to add new files in header array
	 * this is basically for plugins
	 * for specific page array('page'=>'file') 
	 * ie array('uploadactive'=>'datepicker.js')
	 */
	function add_header($files)
	{
		global $Cbucket;
		return $Cbucket->add_header($files);
	}
	
	
	/**
	 * Function used to get config value
	 * of ClipBucket
	 */
	function config($input)
	{
		global $Cbucket;
		return $Cbucket->configs[$input];
	}
	function get_config($input){ return config($input); }
	
	
	/**
	 * Funcion used to call functions
	 * when video is going to watched
	 * ie in watch_video.php
	 */
	function call_watch_video_function($vdo)
	{
		global $userquery;
		
		$funcs = get_functions('watch_video_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(!function_exists($func))
				{
					$func($vdo);
				}
			}
		}
		
		increment_views($vdo['videoid']);
		
		if(userid())
			$userquery->increment_watched_vides(userid());
			
	}
	
	
	/**
	 * Funcion used to call functions
	 * when user view channel
	 * ie in view_channel.php
	 */
	function call_view_channel_functions($u)
	{
		$funcs = get_functions('view_channel_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(!function_exists($func))
				{
					$func($u);
				}
			}
		}
		
		increment_views($u['userid'],"channel");
	}
	
	
	
	
	/**
	 * Funcion used to call functions
	 * when user view topic
	 * ie in view_topic.php
	 */
	function call_view_topic_functions($tdetails)
	{
		$funcs = get_functions('view_topic_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(!function_exists($func))
				{
					$func($tdetails);
				}
			}
		}
		
		increment_views($tdetails['topic_id'],"topic");
	}
	
	/**
	 * Funcion used to call functions
	 * when user view group
	 * ie in view_group.php
	 */
	function call_view_group_functions($gdetails)
	{
		$funcs = get_functions('view_group_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(!function_exists($func))
				{
					$func($gdetails);
				}
			}
		}
		
		increment_views($gdetails['group_id'],"group");
	}
	
	
	/**
	 * Function used to incream number of view
	 * in object
	 */
	function increment_views($id,$type=NULL)
	{
		global $db;
		switch($type)
		{
			case 'v':
			case 'video':
			default:
			{
				if(!isset($_COOKIE['video_'.$id])){
					$db->update("video",array("views","last_viewed"),array("|f|views+1",NOW())," videoid='$id' OR videokey='$vkey'");
					if(userid())
					$db->update("users",array("total_watched"),array("|f|total_watched+1")," userid='".userid()."'");
					setcookie('video_'.$id,'watched',time()+3600);
				}
			}
			break;
			case 'u':
			case 'user':
			default:
			{
				if(!isset($_COOKIE['user_'.$id])){
					$db->update("users",array("profile_hits"),array("|f|profile_hits+1")," userid='$id'");
					setcookie('user_'.$id,'watched',time()+3600);
				}
			}
			break;
			case 't':
			case 'topic':
			default:
			{
				if(!isset($_COOKIE['topic_'.$id])){
					$db->update("group_topics",array("total_views"),array("|f|total_views+1")," topic_id='$id'");
					setcookie('topic_'.$id,'watched',time()+3600);
				}
			}
			break;
			break;
			case 'g':
			case 'group':
			default:
			{
				if(!isset($_COOKIE['group_'.$id])){
					$db->update("groups",array("total_views"),array("|f|total_views+1")," group_id='$id'");
					setcookie('group_'.$id,'watched',time()+3600);
				}
			}
			break;
		}
		
	}
	
	
	/**
	 * Function used to get post var
	 */
	function post($var)
	{
		return $_POST[$var];
	}
	
	
	/**
	 * Function used to show sharing form
	 */
	function show_share_form($array)
	{
		
		assign('params',$array);
		Template('blocks/share_form.html');
	}
	
	/**
	 * Function used to show flag form
	 */
	function show_flag_form($array)
	{
		assign('params',$array);
		Template('blocks/flag_form.html');
	}
	
	/**
	 * Function used to show flag form
	 */
	function show_playlist_form($array)
	{
		global $cbvid;
		assign('params',$array);
		
		$playlists = $cbvid->action->get_playlists();
		assign('playlists',$playlists);
		
		Template('blocks/playlist_form.html');
	}
	
	
	
	
	
	function cbdate($format=NULL,$timestamp=NULL)
	{
		if(!$format)
		{
			$format = "d-m-Y";
		}
		if(!$timestamp)
			return date($format);
		else
			return date($format,$timestamp);
	}
	
	
	/**
	 * Function used to count pages
	 * @param TOTAL RESULTS NUM
	 * @param NUMBER OF RESULTS to DISPLAY NUM
	 */
	function count_pages($total,$count)
	{
		if($count<1) $count = 1;
		$records = $total/$count;
		return $total_pages = round($records+0.49,0);
	}
	
	/**
	 * Function used to return level name 
	 * @param levelid
	 */
	function get_user_level($id)
	{
		global $userquery;
		return $userquery->usr_levels[$id];
	}
	
	
	
	/**
	 * This function used to check
	 * weather user is online or not
	 * @param : last_active time
	 * @param : time margin
	 */
	function is_online($time,$margin='5')
	{
		$margin = $margin*60;
		$active = strtotime($time);
		$curr = time();
		$diff = $curr - $active;
		if($diff > $margin)
			return 'offline';
		else
			return 'online';
	}
	
	
	/**
	 * ClipBucket Form Validator
	 * this function controls the whole logic of how to operate input
	 * validate it, generate proper error
	 */
	function validate_cb_form($input,$array)
	{
		
		if(is_array($input))
		foreach($input as $field)
		{
			$field['name'] = formObj::rmBrackets($field['name']);
			
			//pr($field);
			$title = $field['title'];
			$val = $array[$field['name']];
			$req = $field['required'];
			$invalid_err =  $field['invalid_err'];
			$function_error_msg = $field['function_error_msg'];
			if(is_string($val))
			$length = strlen($val);
			$min_len = $field['min_length'];
			$min_len = $min_len ? $min_len : 0;
			$max_len = $field['max_length'] ;
			$rel_val = $array[$field['relative_to']];
			
			if(empty($invalid_err))
				$invalid_err =  sprintf("Invalid '%s'",$title);
			if(is_array($array[$field['name']]))
				$invalid_err = '';
				
			//Checking if its required or not
			if($req == 'yes')
			{
				if(empty($val) && !is_array($array[$field['name']]))
				{
					e($invalid_err);
					$block = true;
				}else{
					$block = false;
				}
			}
			$funct_err = is_valid_value($field['validate_function'],$val);
			if($block!=true)
			{
				//Checking Syntax
				if(!$funct_err)
				{
					if(!empty($function_error_msg))
						e($function_error_msg);
					elseif(!empty($invalid_err))
						e($invalid_err);
				}elseif(!is_valid_syntax($field['syntax_type'],$val))
				{
					if(!empty($invalid_err))
						e($invalid_err);
				}
				elseif(isset($max_len))
				{
					if($length > $max_len || $length < $min_len)
					e(sprintf(" please enter '%s' value between '%s' and '%s'",
							  $title,$field['min_length'],$field['max_length']));
				}elseif(function_exists($field['db_value_check_func']))
				{
					$db_val_result = $field['db_value_check_func']($val);
					if($db_val_result != $field['db_value_exists'])
						if(!empty($field['db_value_err']))
							e($field['db_value_err']);
						elseif(!empty($invalid_err))
							e($invalid_err);
				}elseif($field['relative_type']!='')
				{
					switch($field['relative_type'])
					{
						case 'exact':
						{
							if($rel_val != $val)
							{
								if(!empty($field['relative_err']))
									e($field['relative_err']);
								elseif(!empty($invalid_err))
									e($invalid_err);
							}
						}
						break;
					}
				}
			}	
		}
	}


	/**
	 * Function used to check weather tempalte file exists or not
	 * input path to file
	 */
	function template_file_exists($file,$dir)
	{
		if(!file_exists($dir.'/'.$file) && !empty($file))
		{
			echo sprintf(lang("temp_file_load_err"),$file,$dir);
			return false;
		}else
			return true;
	}
	
	/** 
	 * Function used to count age from date
	 */
	function get_age($input)
	{ 
		$time = strtotime($input);
		$iMonth = date("m",$time);
		$iDay = date("d",$time);
		$iYear = date("Y",$time);
		
		$iTimeStamp = (mktime() - 86400) - mktime(0, 0, 0, $iMonth, $iDay, $iYear); 
		$iDays = $iTimeStamp / 86400;  
		$iYears = floor($iDays / 365 );  
		return $iYears; 
	}
	
	
	
	
	/**
	 * Function used to check time span
	 * A time difference function that outputs the 
	 * time passed in facebook's style: 1 day ago, 
	 * or 4 months ago. I took andrew dot
	 * macrobert at gmail dot com function 
	 * and tweaked it a bit. On a strict enviroment 
	 * it was throwing errors, plus I needed it to 
	 * calculate the difference in time between 
	 * a past date and a future date. 
	 * thanks to yasmary at gmail dot com
	 */
	function nicetime($date)
	{
		if(empty($date)) {
			return lang('no_date_provided');
		}
	   
		$periods         = array(lang("second"), lang("minute"), lang("hour"), lang("day"), lang("week"), lang("month"), lang("year"), lang("decade"));
		$lengths         = array(lang("60"),lang("60"),lang("24"),lang("7"),lang("4.35"),lang("12"),lang("10"));
	   
		$now             = time();
		$unix_date         = strtotime($date);
	   
		   // check validity of date
		if(empty($unix_date)) {   
			return lang("bad_date");
		}
	
		// is it future date or past date
		if($now > $unix_date) {   
			$difference     = $now - $unix_date;
			$tense         = "ago";
		   
		} else {
			$difference     = $unix_date - $now;
			$tense         = "from now";
		}
	   
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
	   
		$difference = round($difference);
	   
		if($difference != 1) {
			$periods[$j].= "s";
		}
	   
		return "$difference $periods[$j] {$tense}";
	}
	
	
	/**
	 * Function used to format outgoing link
	 */
	function outgoing_link($out)
	{
		preg_match("/http/",$out,$matches);
		if(empty($matches[0]))
			$out = "http://".$out;
		return '<a href="'.$out.'" target="_blank">'.$out.'</a>';
	}
	
	/**
	 * Function used to get country via country code
	 */
	function get_country($code)
	{
		global $db;
		$result = $db->select("countries","name_en,iso2"," iso2='$code' OR iso3='$code'");
		if($db->num_rows>0)
		{
			$flag = '';
			$result = $result[0];
			if(SHOW_COUNTRY_FLAG)
				$flag = '<img src="'.BASEURL.'/images/icons/country/'.strtolower($result['iso2']).'.png" alt="" border="0">&nbsp;';
			return $flag.$result['name_en'];
		}else
			return false;
	}
	
	
	/**
	 * function used to get vidos
	 */
	function get_videos($param)
	{
		global $cbvideo;
		return $cbvideo->get_videos($param);
	}
	
	
	/**
	 * function used to get vidos
	 */
	function get_users($param)
	{
		global $userquery;
		return $userquery->get_users($param);
	}
	
	/**
	 * Function used to call functions
	 */
	function call_functions($in,$params=NULL)
	{
		if(is_array($in))
		{
			foreach($in as $i)
			{
				if(function_exists($i))
					if(!$params)
						$i();
					else
						$i($params);
			}
		}else
		{
			if(function_exists($in))
					if(!$params)
						$in();
					else
						$in($params);
		}
		
	}
	
	/**
	 * Function used to check weather to include
	 * given file or not
	 * it will take array of pages
	 * if array has ACTIVE PAGE or has GLOBAL value
	 * it will return true
	 * otherwise FALSE
	 */
	function is_includeable($array)
	{
		if(in_array(THIS_PAGE,$array) || in_array('global',$array))
		{
			return true;
		}else
			return false;
	}
	
	
	/**
	 * In each plugin
	 * we will define a CONST
	 * such as plguin_installed
	 * that will be used weather plugin is installed or not
	 * ie define("editorspick_install","installed");
	 * is_installed('editorspic');
	 */
	function is_installed($plugin)
	{
		if(defined($plugin."_install"))
			return true;
		else
			return false;
	}
	
	
	/**
	 * Category Link is used to return
	 * Category based link
	 */
	function category_link($data,$type)
	{
		switch($type)
		{
			case 'video':case 'videos':case 'v':
			{
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
					
				if(SEO=='yes')
					return BASEURL.'/videos/'.$data['category_id'].'/'.SEO($data['category_name']).'/'.$_GET['sort'].'/'.$_GET['time'].'/'.$_GET['page'];
				else
					return BASEURL.'/videos.php?cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			case 'channels':case 'channel':case'c':case'user':
			{
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
					
				if(SEO=='yes')
					return BASEURL.'/channels/'.$data['category_id'].'/'.SEO($data['category_name']).'/'.$_GET['sort'].'/'.$_GET['time'].'/'.$_GET['page'];
				else
					return BASEURL.'/channels.php?cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			default:
			{
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
					
				if(SEO=='yes')
					return BASEURL.'/'.$type.'/'.$data['category_id'].'/'.SEO($data['category_name']).'/'.$_GET['sort'].'/'.$_GET['time'].'/'.$_GET['page'];
				else
					return BASEURL.'/'.$type.'.php?cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
		}
	}
	
	/**
	 * Sorting Links is used to return
	 * Sorting based link
	 */
	function sort_link($sort,$mode='sort',$type)
	{
		switch($type)
		{
			case 'video':
			case 'videos':
			case 'v':
			{
				if(!isset($_GET['cat']))
					$_GET['cat'] = 'all';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
				if(!isset($_GET['seo_cat_name']))
					$_GET['seo_cat_name'] = 'All';
				
				if($mode == 'sort')
					$sorting = $sort;
				else
					$sorting = $_GET['sort'];
				if($mode == 'time')
					$time = $sort;
				else
					$time = $_GET['time'];
					
				if(SEO=='yes')
					return BASEURL.'/videos/'.$_GET['cat'].'/'.$_GET['seo_cat_name'].'/'.$sorting.'/'.$time.'/'.$_GET['page'];
				else
					return BASEURL.'/videos.php?cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			case 'channels':
			case 'channel':
			{
				if(!isset($_GET['cat']))
					$_GET['cat'] = 'all';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
				if(!isset($_GET['seo_cat_name']))
					$_GET['seo_cat_name'] = 'All';
				
				if($mode == 'sort')
					$sorting = $sort;
				else
					$sorting = $_GET['sort'];
				if($mode == 'time')
					$time = $sort;
				else
					$time = $_GET['time'];
					
				if(SEO=='yes')
					return BASEURL.'/channels/'.$_GET['cat'].'/'.$_GET['seo_cat_name'].'/'.$sorting.'/'.$time.'/'.$_GET['page'];
				else
					return BASEURL.'/channels.php?cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			
			default:
			{
				if(!isset($_GET['cat']))
					$_GET['cat'] = 'all';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
				if(!isset($_GET['seo_cat_name']))
					$_GET['seo_cat_name'] = 'All';
				
				if($mode == 'sort')
					$sorting = $sort;
				else
					$sorting = $_GET['sort'];
				if($mode == 'time')
					$time = $sort;
				else
					$time = $_GET['time'];
					
				if(SEO=='yes')
					return BASEURL.'/'.$type.'/'.$_GET['cat'].'/'.$_GET['seo_cat_name'].'/'.$sorting.'/'.$time.'/'.$_GET['page'];
				else
					return BASEURL.'/'.$type.'.php?cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			
		}
	}
	
	
	
	
	/**
	 * Function used to get flag options
	 */
	function get_flag_options()
	{
		$action = new cbactions();
		return $action->report_opts;
	}
	
	
	/**
	 * Function used to load captcha field
	 */
	function get_captcha()
	{
		global $Cbucket;
		if(count($Cbucket->captchas)>0)
		{
			return $Cbucket->captchas[0];
		}else
			return false;
	}
	
	/**
	 * Function used to load captcha
	 */
	define("GLOBAL_CB_CAPTCHA","cb_captcha");
	function load_captcha($params)
	{
		global $total_captchas_loaded;
		switch($params['load'])
		{
			case 'function':
			{
				if($total_captchas_loaded!=0)
					$total_captchas_loaded = $total_captchas_loaded+1;
				else
					$total_captchas_loaded = 1;
				$_SESSION['total_captchas_loaded'] = $total_captchas_loaded;
				if(function_exists($params['captcha']['load_function']))
					return $params['captcha']['load_function']().'<input name="cb_captcha_enabled" type="hidden" id="cb_captcha_enabled" value="yes" />';
			}
			break;
			case 'field':
			{
				echo '<input type="text" '.$params['field_params'].' name="'.GLOBAL_CB_CAPTCHA.'" />';
			}
			break;
			
		}
	}
	
	
	/**
	 * Function used to verify captcha
	 */
	function verify_captcha()
	{
		$var = post('cb_captcha_enabled');
		if($var=='yes')
		{
			$captcha = get_captcha();
			$val = $captcha['validate_function'](post(GLOBAL_CB_CAPTCHA));
			return $val;
		}else
			return true;
	}
	
	
	/**
	 * Function used to ingore errors
	 * that are created when there is wrong action done
	 * on clipbucket ie inavalid username etc
	 */
	function ignore_errors()
	{
		global $ignore_cb_errors;
		$ignore_cb_errors = TRUE;
	}
	
	/**
	 * Function used to set $ignore_cb_errors 
	 * back to TRUE so our error catching system
	 * can generate errors
	 */
	function catch_error()
	{
		global $ignore_cb_errors;
		$ignore_cb_errors = FALSE;
	}
	
	
	/**
	 * Function used to call sub_menu_easily
	 */
	function sub_menu()
	{
		/**
		 * Submenu function used to used to display submenu links
		 * after navbar
		 */
		$funcs = get_functions('sub_menu');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(!function_exists($func))
				{
					return $func($u);
				}
			}
		}
	}

?>