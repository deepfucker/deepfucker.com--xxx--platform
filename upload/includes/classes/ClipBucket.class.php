<?php
/**
 * @ Author Arslan Hassan
 * @ License : CBLA
 * @ Class : CLipBucket Class
 * @ date : 12 MARCH 2009
 * @ Version : v1.8
 */

class ClipBucket 
{
	var $BASEDIR;
	var $JSArray = array();
	var $AdminJSArray = array();
	var $moduleList = array();
	var $actionList = array();
	var $anchorList = array();
	var $ids = array(); //IDS WILL BE USED FOR JS FUNCTIONS
	var $AdminMenu = array();
	var $configs = array();
	var $header_files = array();// these files will be included in <head> tag
	var $admin_header_files = array();// these files will be included in <head> tag
	var $anchor_function_list = array();
	var $show_page = true;
	var $upload_opt_list = array();//this will have array of upload opts like upload file, emebed or remote upload
	var $temp_exts = array(); //Temp extensions
	var $actions_play_video = array(); 
	var $template_files = array();
	var $cur_template = 'clipbucketblue';
	var $links = array();
	var $captchas = array();
	var $clipbucket_footer = array('the_end');
	
	var $head_menu = array();
	var $foot_menu = array();
	
	var $in_footer = false;
	
	var $cbinfo = array();
	
	var $search_types = array('videos'=>'cbvid','groups'=>'cbgroup','users'=>'userquery');
	
	/**
	 * All Functions that are called
	 * before after converting a video
	 * are saved in these arrays
	 */
	 
	 var $before_convert_functions = array();
	 var $after_convert_functions = array();
	
	
	/**
	 * This array contains
	 * all functions that are called
	 * when we call video to play on watch_video page
	 */
	 var $watch_video_functions = array();
	 
	 
	 /**
	  * Email Function list
	  */
	 var $email_functions = array();
	 
	 
	function ClipBucket ()
	{
		global $pages;
		//Assign Configs
		$this->configs = $this->get_configs();
		//Get Current Page and Redirects it to without www.
		$pages->redirectOrig();
		//Get Base Directory
		$this->BASEDIR = $this->getBasedir();
		//Listing Common JS File
		$this->addJS(array
					 (
					 'ajax.js'			=> 'homeactive',
					 'jquery.js'		=> 'global',
					 'jquery.js'		=> 'global',
					 'jquery_plugs/cookie.js'		=> 'global',
					 'flashobject.js'	=> 'global',
					 'rating_update.js'	=> 'global',
					 'checkall.js'		=> 'global',
					 'functions.js'		=> 'global',
					 'swfobject.js'		=> 'global',
					 'swfobject.obj.js'		=> 'global',
					  ));
		
		
		//This is used to create Admin Menu
		$this->AdminMenu = $this->get_admin_menu();

		//Updating Upload Options
		$this->upload_opt_list = array
		(
		 'file_upload_div'	=>	array(
							  'title'		=>	'Upload File',
							  'func_class'	=> 	'Upload',
							  'load_func'	=>	'load_upload_form',
							  ),
		 'remote_upload_div' => array(
								  'title'	=> 'Remote Upload',
								  'func_class' => 'Upload',
								  'load_func' => 'load_remote_upload_form',
								  )
		 );
		
		$this->temp_exts = array('ahz','jhz','abc','xyz','cb2','tmp','olo','oar','ozz');
		$this->template = $this->configs['template_dir'];
		
		if(!defined("IS_CAPTCHA_LOADING"))
		$_SESSION['total_captchas_loaded'] = 0;
		
	}
	
	
	function getBasedir()
	{
	   $dirname = dirname(__FILE__);
	   $dirname = preg_replace(array('/includes/','/classes/'),'',$dirname);
	   $dirname = substr($dirname,0,strlen($dirname) -2);
	   return $dirname == '/' ? '' : $dirname;
	}
	
	function addJS($files)
	{
		if(is_array($files))
		{
			foreach($files as $key=> $file)
				$this->JSArray[][$key] = $file;
		}else{
			$this->JSArray[$files] = 'global';
		}
	
	}
	function add_js($files)
	{
		$this->addJS($files);
	}
	
	
	function addAdminJS($files)
	{
		if(is_array($files))
		{
			foreach($files as $key=> $file)
				$this->AdminJSArray[$key] = $file;
		}else{
			$this->AdminJSArray[$files] = 'global';
		}
	
	}
	
	/**
	 * Function add_header()
	 * this will be used to add new files in header array
	 * this is basically for plugins
	 * @param FILE
	 * @param PAGES (array)
	 */
	function add_header($file,$place='global')
	{
		if(!is_array($place))
		{
			$place = array($place);
		}
		$this->header_files[$file] = $place;
	}
	
	
	/**
	 * Function add_admin_header()
	 * this will be used to add new files in header array
	 * this is basically for plugins
	 * @param FILE
	 * @param PAGES (array)
	 */
	function add_admin_header($file,$place='global')
	{
		if(!is_array($place))
		{
			$place = array($place);
		}
		$this->admin_header_files[$file] = $place;
	
	}
	
	/**
	 * Function used to get list of function of any type
	 * @param : type (category,title,date etc)
	 */
	 function getFunctionList($type)
	 {
		return $this->actionList[$type];
	 }
	 
	 
	/**
	 * Function used to get anchors that are registered in plugins
	 */
	 function get_anchor_codes($place)
	 {
		 //Geting list of codes available for $place
		 $list = $this->anchorList[$place];
		 return $list;
	 }
	 
	 /**
	 * Function used to get function of anchors that are registered in plugins
	 */
	 function get_anchor_function_list($place)
	 {
		 //Geting list of functions
		 $list = $this->anchor_function_list[$place];
		 return $list;
	 }
	 
	 /**
	  * Function used to create admin menu
	  */
	  function get_admin_menu()
	  {
		  $menu_array = array
		  (
		   //Statistics
		   'Stats And Configurations'	=> 
		   array(
				'Reports &amp; Stats'=>'reports.php',
				'Website Configurations'=>'main.php',
				'Email Settings'=>'email_settings.php',
				'Language Settings' => 'language_settings.php',
				'Add New Phrases'	=> 'add_phrase.php',
				'Manage Pages'	=> 'manage_pages.php',
				),
		   
		   
		   //Video
		   'Videos'				=> 
		   array(
				'Videos Manager'=>'video_manager.php',
				'Manage Categories'=>'category.php',
				'List Flagged Videos'=>'flagged_videos.php',
				'Upload Videos'	=>'mass_uploader.php',
				'List Inactive Videos'=>'video_manager.php?search=search&active=no'
				),
		   
		   //Users
		   'Users'				=> 
		   array(
				 'Manage Members' => 'members.php',
				 'Add Member'=>'add_member.php',
				 'Manage categories' => 'user_category.php',
				 'User Levels'=>'user_levels.php',
				 'Search Members'=>'members.php?view=search',
				 'Inactive Only'=>'members.php?search=yes&status=ToActivate',
				 'Active Only'=>'members.php?search=yes&status=Ok',
				 'Reported Users'=>'flagged_users.php',
				 'Mass Email'=>'mass_email.php'
				),
		   
		   //Groups
		   'Groups'				=> 
		   array(
				 'Add Group'=>'add_group.php',
				 'Manage Groups'=>'groups_manager.php',
				 'Manage Categories'=>'group_category.php?view=show_category',
				 'View Inactive Groups' => 'groups_manager.php?active=no&search=yes',
				 'View Reported Groups' => 'flagged_groups.php',
				),
		   
		   //Advertisments
		   'Advertisement'		=>
		   array(
				  'Manage Advertisments'=>'ads_manager.php',
				  'Manage Placements'=>'ads_add_placements.php',
				),
		   
		   //Template Manager
		   'Templates And Players'=>
		   array(
				 'Templates Manager'=>'templates.php',
				 'Templates Editor'=>'template_editor.php',
				 'Players Manager' => 'manage_players.php'
				
				),		   
		   //Plugin Manager
		   'Plugin Manager'=>
		   array(
				'Plugin Manager'=>'plugin_manager.php'
				),
		   
		   //Tool Box
		   'Tool Box'=>
		   array(
				 'ClipBucket Module Manager'=>'module_manager.php',
				 'PHP Info'	=> 'phpinfo.php',
				 'Server Modules Info'	=> 'cb_mod_check.php',
				 'View Encoding Status'=>'',
				),
		   
		   	   
		   );
		  
		  
		  return $menu_array;
	  }	 
	
	/**
	 * Function used to assign ClipBucket configurations
	 */
	function get_configs()
	{
		global $myquery;
		return $myquery->Get_Website_Details();
	}
	
	/**
	 * Funtion cused to get list of countries
	 */
	function get_countries($type=iso2)
	{
		global $db;
		$results = $db->select(tbl("countries"),"*");
		switch($type)
		{
			case id:
			foreach($results as $result)
			{
				$carray[$result['country_id']] = $result['name_en'];
			}
			break;
			case iso2:
			foreach($results as $result)
			{
				$carray[$result['iso2']] = $result['name_en'];
			}
			break;
			case iso3:
			foreach($results as $result)
			{
				$carray[$result['iso3']] = $result['name_en'];
			}
			break;
			default:
			foreach($results as $result)
			{
				$carray[$result['country_id']] = $result['name_en'];
			}
			break;
		}
		
		return $carray;
	}
	
	/**
	 * Function used to set show_page = false or true
	 */
	function show_page($val=true)
	{
		$this->show_page = $val;
	}
	
	
	/**
	 * Function used to set template (Frontend)
	 */
	function set_the_template()
	{
		global $cbtpl,$myquery;
		$template = $this->template;
		
		if($_GET['template'])
		{
			if(is_dir(STYLES_DIR.'/'.$_GET['template']) && $_GET['template'])
				$template = $_GET['template'];
		}
		if(!is_dir(STYLES_DIR.'/'.$template) || !$template)
			$template = 'clipbucketblue';
		if(!is_dir(STYLES_DIR.'/'.$template) || !$template)
		{
			$template = $cbtpl->get_any_template();		 
		}
		 
		if(!is_dir(STYLES_DIR.'/'.$template) || !$template)
			exit("Unable to find any template, please goto <a href='http://clip-bucket.com/no-template-found'><strong>ClipBucket Support!</strong></a>");
		
		
		if($_GET['set_template'])
		{
			$myquery->set_template($template);
		}
		
		define('TEMPLATE',$template);
	}
	
	
	/**
	 * Function used to list available extension for clipbucket
	 */
	function list_extensions()
	{
		$exts = $this->configs['allowed_types'];
		$exts = preg_replace('/ /','',$exts);
		$exts = explode(',',$exts);
		$new_form = '';
		foreach($exts as $ext)
		{
			if(!empty($new_form))
				$new_form .=";";
			$new_form .= "*.$ext";
		}
		
		return $new_form;
	}
	
	
	/**
	 * Function used to load head menu
	 */
	function head_menu($params=NULL)
	{
		global $cbpage;
		$this->head_menu[] = array('name'=>lang("menu_home"),'link'=>BASEURL,"this"=>"home");
		$this->head_menu[] = array('name'=>lang("videos"),'link'=>cblink(array('name'=>'videos')),"this"=>"videos");
		$this->head_menu[] = array('name'=>lang("menu_channels"),'link'=>cblink(array('name'=>'channels')),"this"=>"channels");
		$this->head_menu[] = array('name'=>lang("groups"),'link'=>cblink(array('name'=>'groups')),"this"=>"groups");
		if(!userid())
		$this->head_menu[] = array('name'=>lang("signup"),'link'=>cblink(array('name'=>'signup')),"this"=>"signup");
		
		$this->head_menu[] = array('name'=>lang("upload"),'link'=>cblink(array('name'=>'upload')),"this"=>"upload");
		
		if($params['assign'])
			assign($params['assign'],$this->head_menu);
		else
			return $this->head_menu;
	}
	
	/**
	 * Function used to load head menu
	 */
	function foot_menu($params=NULL)
	{
		global $cbpage;
		$this->foot_menu[] = array('name'=>lang("menu_home"),'link'=>BASEURL,"this"=>"home");
		$this->foot_menu[] = array('name'=>lang("contact_us"),'link'=>cblink(array('name'=>'contact_us')),"this"=>"home");		
		if(userid())
			$this->foot_menu[] = array('name'=>lang("my_account"),'link'=>cblink(array('name'=>'my_account')),"this"=>"home");		
		
		if($cbpage->is_active(1))
			$this->foot_menu[] = array('name'=>lang("about_us"),'link'=>$cbpage->get_page_link(1),"this"=>"home");
		
		if($cbpage->is_active(2))
			$this->foot_menu[] = array('name'=>lang("privacy_policy"),'link'=>$cbpage->get_page_link(2),"this"=>"home");
		
		if($cbpage->is_active(3))
			$this->foot_menu[] = array('name'=>lang("terms_of_serivce"),'link'=>$cbpage->get_page_link(3),"this"=>"home");
		
		if($cbpage->is_active(4))
			$this->foot_menu[] = array('name'=>lang("help"),'link'=>$cbpage->get_page_link(4),"this"=>"groups");
		
		if($params['assign'])
			assign($params['assign'],$this->foot_menu);
		else
			return $this->foot_menu;
	}
	
	/**
	 * Function used to call footer
	 */
	function footer()
	{
		ANCHOR(array('place'=>'the_footer'));
	}
	
	
	/**
	 * Function used to get News From ClipBucket Blog
	 */
	function get_cb_news()
	{
		$feeds = 5;
		$text = 400;
		
		if($_SERVER['HTTP_HOST']!='localhost')
			$url = 'http://blog.clip-bucket.com/feed/';
		else
			$url = 'http://localhost/clipbucket/2.x/2/upload/tester/feed.xml';
		$news = xml2array($url);
		if(!$news)
		{
			return false;
		}else
		{
			$items = array();
			$item = $news['rss']['channel']['item'];
			for($i=0;$i<$feeds;$i++)
				$items[] = $item[$i];
			
			return $items;
		}
	}
}

?>