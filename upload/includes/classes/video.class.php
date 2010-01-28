<?php
/**
 * Author : Arslan Hassan
 * Script : ClipBucket v2
 * LIcense : CBLA
 *
 *
 * Class : Video
 * Used to perform function swith videos
 * -- history
 * all function that were in my_query
 * has been transfered here
 * however thhey will still work from there
 * too
 */
 
define("QUICK_LIST_SESS","quick_list");

class CBvideo extends CBCategory
{
	var $embed_func_list = array(); //Function list that are applied while asking for video embed code
	var $action = ''; // variable used to call action class
	var $email_template_vars = array();
	
	var $dbtbl = array('video'=>'video');
	
	var $video_manager_links = array();
	var $video_manager_funcs = array();
	
	/**
	 * __Constructor of CBVideo
	 */	
	function CBvideo()
	{
		$this->cat_tbl = 'video_categories';
		$this->section_tbl = 'video';
		$this->init_actions();
	}
	
	
	/**
	 * Function used to check weather video exists or not
	 * @param VID or VKEY
	 */
	function video_exists($vid)
	{
		return $this->get_video($vid);
	}
	function exists($vid){return $this->video_exists($vid);}
	function videoexists($vid){return $this->video_exists($vid);}
	
	
	/**
	 * Function used to get video data
	 */
	function get_video($vid)
	{
		global $db;
		if(is_numeric($vid))
			$results = $db->select("video","*"," videoid='$vid'");
		else
			$results = $db->select("video","*"," videokey='$vid'");
		if($db->num_rows>0)
		{
			return $results[0];
		}else{
			return false;
		}
	}
	function getvideo($vid){return $this->get_video($vid);}
	function get_video_data($vid){return $this->get_video($vid);}
	function getvideodata($vid){return $this->get_video($vid);}
	function get_video_details($vid){return $this->get_video($vid);}
	function getvideodetails($vid){return $this->get_video($vid);}
	
	/**
	 * Function used to perform several actions with a video
	 */
	function action($case,$vid)
	{
		global $db;
		if(!$this->exists($vid))
			return false;
		//Lets just check weathter video exists or not
		switch($case)
		{
			//Activating a video
			case 'activate':
			case 'av':
			case 'a':
			{
				$db->update("video",array('active'),array('yes')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_vdo_act_msg"),m);
			}
			break;
			
			//Deactivating a video
			case "deactivate":
			case "dav":
			case "d":
			{
				$db->update("video",array('active'),array('no')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_vdo_act_msg1"),m);
			}
			break;
			
			//Featuring Video
			case "feature":
			case "featured":
			case "f":
			{
				$db->update("video",array('featured','featured_date'),array('yes',now())," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_vdo_fr_msg"),m);
			}
			break;
			
			
			//Unfeatured video
			case "unfeature":
			case "unfeatured":
			case "uf":
			{
				$db->update("video",array('featured'),array('no')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_fr_msg1"),m);
			}
			break;
		}
	}
	
	
	
	/**
	 * Function used to update video
	 */
	function update_video($array=NULL)
	{
		global $eh,$Cbucket,$db,$Upload;

		
			 
		$Upload->validate_video_upload_form(NULL,TRUE);
		
		if(empty($eh->error_list))
		{
			$required_fields = $Upload->loadRequiredFields($array);
			$location_fields = $Upload->loadLocationFields($array);
			$option_fields = $Upload->loadOptionFields($array);
			
			$upload_fields = array_merge($required_fields,$location_fields,$option_fields);
			
			//Adding Custom Upload Fields
			if(count($Upload->custom_upload_fields)>0)
				$upload_fields = array_merge($upload_fields,$Upload->custom_upload_fields);
			//Adding Custom Form Fields
			if(count($Upload->custom_form_fields)>0)
				$upload_fields = array_merge($upload_fields,$Upload->custom_form_fields);
			
			if(!$array)
			 $array = $_POST;
			 
			$vid = $array['videoid'];

			if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
		
			foreach($upload_fields as $field)
			{
				$name = formObj::rmBrackets($field['name']);
				$val = $array[$name];
				
				if($field['use_func_val'])
					$val = $field['validate_function']($val);
				
				
				if(!empty($field['db_field']))
				$query_field[] = $field['db_field'];
				
				if(is_array($val))
				{
					$new_val = '';
					foreach($val as $v)
					{
						$new_val .= "#".$v."# ";
					}
					$val = $new_val;
				}
				if(!$field['clean_func'] || (!apply_func($field['clean_func'],$val) && !is_array($field['clean_func'])))
					$val = mysql_clean($val);
				else
					$val = apply_func($field['clean_func'],$val);
				
				if(!empty($field['db_field']))
				$query_val[] = $val;
				
			}		

			
			#$query = "INSERT INTO video (";
			$total_fields = count($query_field);
			
			//Adding Fields to query
			$i = 0;
			
			/*for($key=0;$key<$total_fields;$key++)
			{
				$query .= query_field[$key]." = '".$query_val[$key]."'" ;
				if($key<$total_fields-1)
				$query .= ',';
			}*/
			
			if(has_access('admin_access',TRUE))
			{
				if(!empty($array['status']))
				{
					$query_field[] = 'status';
					$query_val[] = $array['status'];
				}
				
				if(!empty($array['duration']))
				{
					$query_field[] = 'duration';
					$query_val[] = $array['duration'];
				}
				
				if(!empty($array['views']))
				{
					$query_field[] = 'views';
					$query_val[] = $array['views'];
				}
				
				if(!empty($array['rating']))
				{
					$query_field[] = 'rating';
					$rating = $array['rating'];
					if(!is_numeric($rating) || $rating<0 || $rating>10)
						$rating = 1;
					$query_val[] = $rating;
				}
				
				if(!empty($array['rated_by']))
				{
					$query_field[] = 'rated_by';
					$query_val[] = $array['rated_by'];
				}
			}
			
			if(!userid())
			{
				e("You are not logged in");
			}elseif(!$this->video_exists($vid)){
				e("Video deos not exist");
			}elseif(!$this->is_video_owner($vid,userid()) && !has_access('admin_access',TRUE))
			{
				e("You cannot edit this video");
			}else{
				//pr($upload_fields);
				$db->update('video',$query_field,$query_val," videoid='$vid'");
				e("Video details have been updated",m);
			}
			
		}
	}
	
	
	/**
	 * Function used to delete a video
	 */
	function delete_video($vid)
	{
		global $db;
		
		if($this->video_exists($vid))
		{
			
			$vdetails = $this->get_video($vid);

			if($this->is_video_owner($vid,userid()) || has_access('admin_access',TRUE))
			{
				//list of functions to perform while deleting a video
				$del_vid_funcs = $this->video_delete_functions;
				if(is_array($del_vid_funcs))
				{
					foreach($del_vid_funcs as $func)
					{
						if(function_exists($func))
						{
							$func($vdetails);
						}
					}
				}
				//Finally Removing Database entry of video
				$db->execute("DELETE FROM video WHERE videoid='$vid'");
				$db->update("users",array("total_videos"),array("|f|total_videos-1")," userid='".$vdetails['userid']."'");
				e(lang("class_vdo_del_msg"),m);
			}else{
				e(lang("You cannot delete this video"));
			}
		}else{
			e(lang("class_vdo_del_err"));
		}
		
	}
	
	/**
	 * Function used to remove video thumbs
	 */
	function remove_thumbs($vdetails)
	{
		//First lets get list of all thumbs
		$thumbs = get_thumb($vdetails,1,true,false,false);
		if(!is_default_thumb($thumbs))
		{
			if(is_array($thumbs))
			{
				foreach($thumbs as $thumb)
				{
					$file = THUMBS_DIR.'/'.$thumb;
					if(file_exists($file) && is_file($file))
						unlink($file);
				}
			}else{
				$file = THUMBS_DIR.'/'.$thumbs;
					if(file_exists($file) && is_file($file))
						unlink($file);
			}
			
			e(lang("vid_thumb_removed_msg"),m);
		}
	}
	
	
	
	/**
	 * Function used to remove video log
	 */
	function remove_log($vdetails)
	{
		global $db;
		$src = $vdetails['videoid'];
		$file = LOGS_DIR.'/'.$vdetails['file_name'].'.log';
		$db->execute("DELETE FROM video_file WHERE src_name = '$src'");
		if(file_exists($file))
			unlink($file);
		e(lang("vid_log_delete_msg"),m);
	}
	
	/**
	 * Function used to remove video files
	 */
	function remove_files($vdetails)
	{
		//Getting list of files
		$files = get_video_file($vdetails,false,false,true);
		if(is_array($files))
		{
			foreach($files as $file)
			{
				if(file_exists(VIDEOS_DIR.'/'.$file) && is_file(VIDEOS_DIR.'/'.$file))
					unlink(VIDEOS_DIR.'/'.$file);
			}
		}else{
			if(file_exists(VIDEOS_DIR.'/'.$files) && is_file(VIDEOS_DIR.'/'.$files))
					unlink(VIDEOS_DIR.'/'.$files);
		}
		e(lang("vid_files_removed_msg"),m);
	}
	
	
	/**
	 * Function used to get videos
	 * this function has all options
	 * that you need to fetch videos
	 * please see docs.clip-bucket.com for more details
	 */
	function get_videos($params)
	{
		global $db;
		
		$limit = $params['limit'];
		$order = $params['order'];
		
		$cond = "";
		if(!has_access('admin_access',TRUE))
			$cond .= " video.status='Successful' AND video.active='yes' ";
		else
		{
			if($params['active'])
				$cond .= " video.active='".$params['active']."'";

			if($params['status'])
			{
				if($cond!='')
					$cond .=" AND ";
				$cond .= " video.status='".$params['status']."'";
			}
			
			
		}
		
		//Setting Category Condition
		$all = false;
		if(!is_array($params['category']))
			if(strtolower($params['category'])=='all')
				$all = true;
				
		if($params['category'] && !$all)
		{
			if($cond!='')
				$cond .= ' AND ';
				
			$cond .= " (";
			
			if(!is_array($params['category']))
			{
				$cats = explode(',',$params['category']);
			}else
				$cats = $params['category'];
				
			$count = 0;
			
			foreach($cats as $cat_params)
			{
				$count ++;
				if($count>1)
				$cond .=" OR ";
				$cond .= " video.category LIKE '%#$cat_params#%' ";
			}
			
			$cond .= ")";
		}
		
		//date span
		if($params['date_span'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".cbsearch::date_margin("date_added",$params['date_span']);
		}
		
		//uid 
		if($params['user'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " video.userid='".$params['user']."'";
		}
		
		$tag_n_title='';
		//Tags
		if($params['tags'])
		{
			//checking for commas ;)
			$tags = explode(",",$params['tags']);
			if(count($tags)>0)
			{
				if($tag_n_title!='')
					$tag_n_title .= ' OR ';
				$total = count($tags);
				$loop = 1;
				foreach($tags as $tag)
				{
					$tag_n_title .= " video.tags LIKE '%".$tag."%'";
					if($loop<$total)
					$tag_n_title .= " OR ";
					$loop++;
					
				}
			}else
			{
				if($tag_n_title!='')
					$tag_n_title .= ' OR ';
				$tag_n_title .= " video.tags LIKE '%".$params['tags']."%'";
			}
		}
		//TITLE
		if($params['title'])
		{
			if($tag_n_title!='')
				$tag_n_title .= ' OR ';
			$tag_n_title .= " video.title LIKE '%".$params['tags']."%'";
		}
		
		if($tag_n_title)
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ($tag_n_title) ";
		}
		
		//FEATURED
		if($params['featured'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " video.featured = 'yes' ";
		}
		
		//Exclude Vids
		if($params['exclude'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " video.videoid <> '".$params['exclude']."' ";
		}
		
		if(!$params['count_only'])
		{
			if(!empty($cond))
				$cond .= " AND ";
			$result = $db->select('video,users','video.*,users.userid,users.username',$cond." video.userid = users.userid",$limit,$order);	
		}
		if($params['count_only'])
			return $result = $db->count('video','*',$cond);
		if($params['assign'])
			assign($params['assign'],$result);
		else
			return $result;
	}
	
	/**
	 * Function used to count total video comments
	 */
	function count_video_comments($id)
	{
		global $db;
		$total_comments = $db->count('comments',"comment_id","type='v' AND type_id='$id'");
		return $total_comments;
	}
	
	
	/**
	 * Function used to update video comments count
	 */
	function update_comments_count($id)
	{
		global $db;
		$total_comments = $this->count_video_comments($id);
		$db->update("video",array("comments_count"),array($total_comments)," videoid='$id'");
	}
	
	/**
	 * Function used to add video comment
	 */
	function add_comment($comment,$obj_id,$reply_to=NULL)
	{
		global $myquery,$db;
		$add_comment =  $myquery->add_comment($comment,$obj_id,$reply_to,'v');
		if($add_comment)
		{
			//Loggin Comment			
			$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "comment on a video",
			 'action_obj_id' => $obj_id,
			 'action_done_id' => $add_comment,
			);
			insert_log('video_comment',$log_array);
			
			//Updating Number of comments of video
			$this->update_comments_count($obj_id);
		}
		return $add_comment;
	}
	
	/**
	 * Function used to remove video comment
	 */
	function delete_comment($cid,$is_reply=FALSE)
	{
		global $myquery,$db;
		$remove_comment =  $myquery->delete_comment($cid,'v',$is_reply);
		if($remove_comment)
		{
			//Updating Number of comments of video
			$this->update_comments_count($obj_id);
		}
		return $remove_comment;
	}
	
	
	/**
	 * Function used to generate Embed Code
	 */
	function embed_code($vdetails)
	{
		//Checking for video details
		if(!is_array($vdetails))
		{
			$vdetails = $this->get_video($vdetails);
		}
		
		$embed_code = false;
		
		$funcs = $this->embed_func_listl;
		if(is_array($funcs))
		{
			foreach($funcs as $func)
			{
				if(@function_exists($func))
				$embed_code = $func($vdetails);
			}
		}
		
		if(!$embed_code)
		{
			//Default ClipBucket Embed Code
			if(function_exists('default_embed_code'))
			{
				$embed_code = default_embed_code($vdetails);
			}
		}
		
		return $embed_code;
		
	}
	
	
	/**
	 * Function used to initialize action class
	 * in order to call actions.class.php to
	 * work with Video section, this function will be called first
	 */
	function init_actions()
	{
		$this->action = new cbactions();
		$this->action->type = 'v';
		$this->action->name = 'video';
		$this->action->obj_class = 'cbvideo';
		$this->action->check_func = 'video_exists';
		$this->action->type_tbl = $this->dbtbl['video'];
		$this->action->type_id_field = 'videoid';
	}
	
	
		
	/**
	 * Function used to create value array for email templates
	 * @param video_details ARRAY
	 */
	function set_share_email($details)
	{
		$this->email_template_vars = array
		('{video_title}' => $details['title'],
		 '{video_description}' => $details['tags'],
		 '{video_tags}' => $details['description'],
		 '{video_date}' => cbdate(DATE_FORMAT,strtotime($details['date_added'])),
		 '{video_link}' => video_link($details),
		 '{video_thumb}'=> GetThumb($details)
		 );
		
		$this->action->share_template_name = 'share_video_template';
		$this->action->val_array = $this->email_template_vars;
	}
	
	
	/**
	 * Function used to use to initialize search object for video section
	 * op=>operator (AND OR)
	 */
	function init_search()
	{
		$this->search = new cbsearch;
		$this->search->db_tbl = "video";
		$this->search->columns =array(
			array('field'=>'title','type'=>'LIKE','var'=>'%{KEY}%'),
			array('field'=>'tags','type'=>'LIKE','var'=>'%{KEY}%','op'=>'AND')
		);
		$this->search->cat_tbl = $this->cat_tbl;
		
		$this->search->display_template = LAYOUT.'/blocks/video.html';
		$this->search->template_var = 'video';
		$this->search->has_user_id = true;
		
		/**
		 * Setting up the sorting thing
		 */
		
		$sorting	= 	array(
						'date_added'=> lang("date_added"),
						'views'		=> lang("views"),
						'comments'  => lang("comments"),
						'rating' 	=> lang("rating"),
						'favorites'	=> lang("favorites")
						);
		
		$this->search->sorting	= array(
						'date_added'=> " date_added DESC",
						'views'		=> " views DESC",
						'comments'  => " comments_count DESC ",
						'rating' 	=> " rating DESC",
						'favorites'	=> " favorites DeSC"
						);
		/**
		 * Setting Up The Search Fields
		 */
		 
		$default = $_GET;
		if(is_array($default['category']))
			$cat_array = array($default['category']);		
		$uploaded = $default['datemargin'];
		$sort = $default['sort'];
		
		$this->search->search_type['videos'] = array('title'=>lang('videos'));
		
		$fields = array(
		'query'	=> array(
						'title'=> lang('keywords'),
						'type'=> 'textfield',
						'name'=> 'query',
						'id'=> 'query',
						'value'=>cleanForm($default['query'])
						),
		'category'	=>  array(
						'title'		=> lang('vdo_cat'),
						'type'		=> 'checkbox',
						'name'		=> 'category[]',
						'id'		=> 'category',
						'value'		=> array('category',$cat_array),
						),
		'uploaded'	=>  array(
						'title'		=> lang('uploaded'),
						'type'		=> 'dropdown',
						'name'		=> 'datemargin',
						'id'		=> 'datemargin',
						'value'		=> $this->search->date_margins(),
						'checked'	=> $uploaded,
						),
		'sort'		=> array(
						'title'		=> lang('sort_by'),
						'type'		=> 'dropdown',
						'name'		=> 'sort',
						'value'		=> $sorting,
						'checked'	=> $sort
							)
		);

		$this->search->search_type['videos']['fields'] = $fields;
	}
	
	
	/*
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
			e(lang('vid_thumb_changed'),m);
		}else{
			e(lang('vid_thumb_change_err'));
		}
	}	
	
	
	/**
	 * Function used to check weather current user is video owner or not
	 */
	function is_video_owner($vid,$uid)
	{
		global $db;
		
		$result = $db->count($this->dbtbl['video'],'videoid',"videoid='$vid' AND userid='$uid' ");
		if($result>0)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to display video manger link
	 */
	function video_manager_link($link,$vid)
	{
		if(function_exists($link) && !is_array($link))
		{
			return $link($vid);
		}else
		{
			if(!empty($link['title']) && !empty($link['link']))
			{
				return ' | <a href="'.$link['link'].'">'.$link['title'].'</a>';
			}
		}
	}
	
	
	
	/**
	 * Function used to get video rating details
	 */
	function get_video_rating($id)
	{
		global $db;
		if(is_numeric($vid))
		{
			$results = $db->select("video","*"," videoid='$vid'");
		}else
			$results = $db->select("video","*"," videokey='$vid'");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Function used to display rating option for videos
	 * this is an OLD TYPICAL RATING SYSTEM
	 * and yes, still with AJAX
	 */
	function show_video_rating($params)
	{
		$rating 	= $params['rating'];
		$ratings 	= $params['ratings'];
		$total 		= $params['total'];
		$id 		= $params['id'];
		$type 		= $params['type'];
		
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
		
		if($params['is_rating'])
		{
			if(error())
			{
				$rating_msg = error();
				$rating_msg = '<span class="error">'.$rating_msg[0].'</span>';
			}
			if(msg())
			{
				$rating_msg = msg();
				$rating_msg = '<span class="msg">'.$rating_msg[0].'</span>';
			}
		}
		
		assign('perc',$perc);
		assign('id',$id);
		assign('type',$type);
		assign('id',$id);
		assign('rating_msg',$rating_msg);
		assign('disable',$params['disable']);
		
		Template('blocks/rating.html');
		
	}
	
	
	/**
	 * Function used to rate video
	 */
	function rate_video($id,$rating)
	{
		global $db;

		if(!is_numeric($rating) || $rating <1)
			$rating = 1;
		if($rating>10)
			$rating = 10;

		$rating_details = $this->get_video_rating($id);
		$voter_id = $rating_details['voter_ids'];
		
		$new_by = $rating_details['rated_by'];
		$newrate = $rating_details['rating'];
		
		$niddle = "|";
		$niddle .= userid();
		$niddle .= "|";
		$flag = strstr($voter_id, $niddle);
		
		if(!empty($flag))
			e("You have already rated this video");
		elseif(!userid())
			e("Please login to rate");
		else
		{
			if(empty($voter_id))
				$voter_id .= "|";
			$voter_id .= userid();
			$voter_id .= "|";
			$t = $rating_details['rated_by'] * $rating_details['rating'];
			$new_by = $rating_details['rated_by'] + 1;
			$newrate = ($t + $rating) / $new_by;
			
			$db->update($this->dbtbl['video'],array("rating","rated_by","voter_ids"),array($newrate,$new_by,$voter_id)," videoid='$id'");
			e("Thanks for voting","m");	
		}
		
		$result = array('rating'=>$newrate,'ratings'=>$new_by,'total'=>10,'id'=>$id,'type'=>'video','disable'=>'disabled');
		return $result;
	}
	
	
	/**
	 * Function used to get playlist items
	 */
	function get_playlist_items($pid)
	{		
		global $db;
		$ptbl = $this->action->playlist_items_tbl;
		$vtbl = $this->dbtbl['video'];
		
		$tbls = $ptbl.','.$vtbl;
		$fields = $ptbl.".*,$vtbl.title,$vtbl.comments_count,$vtbl.views,$vtbl.userid,$vtbl.date_added,
		$vtbl.file_name,$vtbl.category,$vtbl.description,$vtbl.videokey,$vtbl.tags,$vtbl.videoid";
		$result = $db->select($tbls,$fields,"playlist_id='$pid' AND ".$vtbl.".videoid=".$ptbl.".object_id");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}	
	
	/**
	 * Function used to add video in quicklist
	 */
	function add_to_quicklist($id)
	{
		global $sess;
		if($this->exists($id))
		{
			$list = json_decode($sess->get(QUICK_LIST_SESS), true);
			pr($list);
			$list[] = $id;
			$new_list = array_unique($list);
			$sess->set(QUICK_LIST_SESS,json_encode($new_list));
			return true;

		}else
			return false;
	}
	
	/**
	 * Removing video from quicklist
	 */
	function remove_from_quicklist($id)
	{
		global $sess;
		$list = json_decode($sess->get(QUICK_LIST_SESS), true);
		$key = array_search($id,$list);
		unset($list[$key]);
		$sess->set(QUICK_LIST_SESS,json_encode($list));
		return true;
	}
	
	
	/**
	 * function used to count num of quicklist
	 */
	function total_quicklist()
	{
		global $sess;
		$total = $sess->get(QUICK_LIST_SESS);
		$total = json_decode($total, true);
		return count($total);
	}
	
	/**
	 * Function used to get quicklist
	 */
	function get_quicklist()
	{
		global $sess;
		return json_decode($sess->get(QUICK_LIST_SESS), true);
	}
	
	/**
	 * Function used to remove all items of quicklist
	 */
	function clear_quicklist()
	{
		global $sess;
		$sess->set(QUICK_LIST_SESS,'');
	}
	
}
?>