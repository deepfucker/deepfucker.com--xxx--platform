<?php
##################################################################
###                                                            ###
##                                                              ##
#       this class was writtend by Arslan Hassan                 #
#       it will be used to create new language pack              #
#       add, edit or delete phrases of existing language packs   #
#       easy to manipulate and easy to use                       #
#       made for ClipBucket                                      #
##                                                              ##
###                                                            ###
##################################################################

#
# @ Author :Arslan Hassan
# @ software : ClipBucket
# @ license : cbla
# @ file : language.class.php
#



class language
{
	
	var $lang = 'en';
	var $lang_iso = 'en';
	
	/** 
	 * __Constructor
	 */
	function language()
	{
		$this->lang = $this->lang_iso = 'en';
	}
	
	/**
	 * INIT
	 */
	function init()
	{
		$lang = $_COOKIE['cb_lang'];
		
		//Setting Language
		if(isset($_GET['set_site_lang']))
		{
			$lang = $_GET['set_site_lang'];
			if($this->lang_exists($lang))
				setcookie('cb_lang',$lang,time()+3600,'/');
		}
	
		
		$lang_details = $this->lang_exists($lang);
		if(isset($lang) && $lang_details)
		{
			$default = $lang_details ;
		}else
		{
			$default = $this->get_default_language();
		}
	
		if($default['language_code'])
		{
			$this->lang = $this->lang_iso = $default['language_code'];
		}
	}
	 
	 
	/**
	 * Function used add new phrase
	 */
	function add_phrase($name,$text,$lang_code='en')
	{
		global $db;
		//First checking if phrase already exists or not
		if(empty($name))
			e(lang("phrase_code_empty"));
		elseif(empty($text))
			e(lang("phrase_text_empty"));
		elseif(!$this->lang_exists($lang_code))
			e(lang("language_does_not_exist"));
		elseif(!$this->get_phrase($name,$lang_code))
		{
			e(sprintf(lang("name_has_been_added"),$name),m);
			$db->insert(tbl("phrases"),array('lang_iso','varname','text'),array($lang_code,$name,'|no_mc|'.$text));
		}else{
			e(sprintf(lang("name_already_exists"),$name),m);
		}
	}
	 
	/**
	 * Function used to get language phrase
	 * @param STRING name
	 * @param STRING lang_code
	 */
	function get_phrase($name,$lang_code=NULL)
	{
		global $db;
		
		if($lang_code!='')
		{
			$lang_query = "AND lang_iso = '".mysql_clean($lang_code)."'";
		}
		
		$results = $db->select(tbl("phrases"),'*'," id = '".mysql_clean($name)."' OR varname = '".mysql_clean($name)."' $lang_query ");
		if($db->num_rows > 0 )
			return $results[0];
		else
			return false;
	}
	
	
	/**
	 * Function used to modify phrase
	 */
	function update_phrase($id,$text,$lang_code='en')
	{
		global $db;
		//First checking if phrase already exists or not
		if($this->get_phrase($id,$lang_code))
			$db->update(tbl("phrases"),array('text'),array(mysql_real_escape_string($text))," id = '".mysql_real_escape_string($id)."' ");
	}
	
	/**
	 * Function used to get all phrases of particular language
	 */
	function get_phrases($lang=NULL,$fields="varname,text",$limit=NULL,$extra_param=NULL)
	{
		global $db;
		$lang_details = $this->lang_exists($lang);
		$lang_code = $lang_details['language_code'];
		if(empty($lang_code))
			$lang_code = $this->lang;
		return $db->select(tbl("phrases"),$fields," lang_iso = '".$lang_code."' $extra_param",$limit," id ");
		
	}
	
	
	/**
	 * Function used to count phrases
	 */
	function count_phrases($lang=NULL,$extra_param=NULL)
	{
		global $db;
		$lang_details = $this->lang_exists($lang);
		$lang_code = $lang_details['lang_code'];
		if(empty($lang_code))
			$lang_code = $this->lang;
		
		$results = $db->select(tbl("phrases"),"COUNT(id)"," lang_iso = '".$lang_code."' $extra_param");
		if($db->num_rows>0)
			return $results[0][0];
		else
			return 0;
		
	}
	
	/**
	 * Function used to assign phrases as an array
	 */
	function lang_phrases()
	{
		$phrases = $this->get_phrases();
		foreach($phrases as $phrase)
		{
			$lang[$phrase['varname']] = $phrase['text'];
		}
		return $lang;
	}
	
	
	/**
	 * Function used to get list of languages installed
	 */
	function get_langs()
	{
		global $db;
		$results = $db->select(tbl("languages"),"*");
		return $results;
	}
	
	
	
	/**
	 * Function used to check 
	 * weather language existsor not
	 * using iso_code or its lang_id
	 */
	function lang_exists($id)
	{
		global $db;
		$results = $db->select(tbl("languages"),"*"," language_code ='$id' OR language_id = '$id'");
		if($db->num_rows>0)
			return $results[0];
		else
			return false;
	}
	
	
	/**
	 * Make Language Default
	 */
	function make_default($lid)
	{
		global $db;
		$lang = $this->lang_exists($lid);
		if($lang)
		{
			setcookie('cb_lang',$lid,time()+3600,'/');
			$db->update(tbl("languages"),array("language_default"),array("no")," language_default='yes'");
			$db->update(tbl("languages"),array("language_default"),array("yes")," language_id='$lid'");
			e($lang['language_name']." has been set as default language","m");
		}
	}
	
	/**
	 * function used to get default language
	 */
	function get_default_language()
	{
		global $db;
		$result = $db->select(tbl('languages'),"*"," language_default='yes' ");
		$result = $result[0];
		return $result;
	}
	
	/**
	 * Funcion used to get language detilas
	 */
	function get_lang($id)
	{
		return $this->lang_exists($id);
	}
	
	
	
	/**
	 * Function used to export language
	 */
	function export_lang($id)
	{
		
		//first get language details
		$lang_details = $this->get_lang($id);
		if($lang_details)
		{
			header("Pragma: public"); // required
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false); // required for certain browsers 
			header("Content-type: application/force-download");
			header("Content-Disposition: attachment; filename=\"cb_lang_".$lang_details['language_code'].".xml\""); 
			echo '<?xml version="1.0" encoding="UTF-8"?>';
			?>
			<clipbucket_language>
				<name><?=$lang_details['language_name']?></name>
				<iso_code><?=$lang_details['language_code']?></iso_code>
				<phrases>
					<?=array2xml(array('lang'=>$this->lang_phrases()));?>
				</phrases>
			</clipbucket_language>
            <?php
			exit();
		}else
			e(lang("lang_doesnt_exist"));
	}
	
	/**
	 * Function used to import language
	 */
	function import_lang()
	{
		global $db;
		//First we will move uploaded file
		$file_name = TEMP_DIR.'/cb_lang.xml';
		
		if(empty($_FILES['lang_file']['name']))
			e(lang("no_file_was_selected"));
		elseif(move_uploaded_file($_FILES['lang_file']['tmp_name'],$file_name))
		{
			//Reading Content
			$content = file_get_contents($file_name);
			if(!$content)
			{
				e(lang("err_reading_file_content"));
			}else
			{
				//Converting data from xml to array
				$data = xml2array($content,1,'tag',false);
				//now checkinf if array has lang code, phrases and name etc or not
				$data = $data['clipbucket_language'];
				$phrases = $data['phrases'];
				if(empty($data['name']))
					e(lang("cant_find_lang_name"));
				elseif(empty($data['iso_code']))
					e(lang("cant_find_lang_code"));
				elseif(count($phrases)<1)
					e(lang("no_phrases_found"));
				elseif($this->lang_exists($data['iso_code']))
					e(lang("language_already_exists"));
				else
				{
					$db->insert(tbl("languages"),array("language_code","language_name","language_regex","language_default"),
												  array($data['iso_code'],$data['name'],"/^".$data['iso_code']."/i","no"));
					$sql = '';
					foreach($phrases as $code => $phrase)
					{
						if(!empty($sql))
							$sql .=",\n";
						$sql .= "('".$data['iso_code']."','$code','".mysql_clean($phrase)."')";
					}
					$sql .= ";";
					$query = "INSERT INTO phrases (lang_iso,varname,text) VALUES \n";
					$query .= $sql;
					$db->execute($query);
					e(lang("lang_added"),"m");
				}
			}
			
		}else
			e(lang("error_while_upload_file"));
			
		if(file_exists($file_name))
			unlink($file_name);
	} 
	
	/**
	 * Function used to delete language pack
	 */
	function delete_lang($i)
	{
		global $db;
		$lang = $this->get_lang($i);
		if(!$lang)
			e(lang("language_does_not_exist"));
		elseif($lang['language_default'] == 'yes')
			e(lang("default_lang_del_error"));
		else
		{
			$db->delete(tbl('languages'),array("language_code"),array($lang['language_code']));
			$db->delete(tbl('phrases'),array("lang_iso"),array($lang['language_code']));
			e(lang("lang_deleted"),"m");
		}
	}
	
	/**
	 * Function used to update language
	 */
	function update_lang($array)
	{
		global $db;
		$lang = $this->get_lang($array['lang_id']);
		if(!$lang)
			e(lang("language_does_not_exist"));
		elseif(empty($array['name']))
			e(lang("lang_name_empty"));
		elseif(empty($array['code']))
			e(lang("lang_code_empty"));
		elseif(empty($array['regex']))
			e(lang("lang_regex_empty"));
		elseif($this->lang_exists($array['code']) && $array['code'] != $lang['language_code'])
			e(lang("lang_code_already_exist"));
		else
		{
			$db->update(tbl('languages'),array("language_name","language_code","language_regex"),
									array($array['name'],$array['code'],$array['regex'])," language_id='".$array['lang_id']."'");
			if($array['code'] != $lang['language_code'])
			$db->update(tbl("phrases"),array("lang_iso"),array($array['code'])," lang_iso='".$lang['language_code']."'");
			e(lang("lang_updated"),"m");
		}
			
	}
}

?>