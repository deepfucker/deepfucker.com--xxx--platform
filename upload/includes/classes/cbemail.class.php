<?php

/**
 * It is better to keep everything sperated
 * and code properly
 * so i created this file coz i like it this way :p
 *
 *
 * Author : Arslan
 */
 
 

class CBEmail
{
	
	var $db_tpl = 'email_templates';
	
	function cbemail()
	{
		//Constructor - do nothing
	}

	/**
	 * Function used to get email tempalate from database
	 */
	function get_email_template($code)
	{
		global $db;
		$result = $db->select($this->db_tpl,"*"," email_template_code='".$code."' OR email_template_id='$code' ");
		if($db->num_rows>0)
		{
			return $result[0];
		}else
			return false;
	}
	function get_template($code)
	{
		return $this->get_email_template($code);
	}
	
	/**
	 * Check template exists or not
	 */
	function template_exists($code)
	{
		return $this->get_email_template($code);
	}
	
	
	/**
	 * Function used to replace content
	 * of email template with variables
	 * it can either be email subject or message content
	 * @param : Content STRING 
	 * @param : array ARRAY => array({somevar}=>$isvar)
	 */
	function replace($content,$array)
	{
		//Common Varialbs
		
		$com_array = array
		('{website_title}'	=> TITLE,
		 '{baseurl}'		=> BASEURL,
		 '{website_url}'	=> BASEURL,
		 '{date_format}'	=> cbdate(DATE_FORMAT),
		 '{date}'			=> cbdate(),
		 '{username}'		=> username(),
		 '{userid}'			=> userid(),
		 '{date_year}'		=> cbdate("Y"),
		 '{date_month}'		=> cbdate("m"),
		 '{date_day}'		=> cbdate("d"),
		 '{signup_link}'	=> cblink(array('name'=>'signup')),
		 '{login_link}'		=> cblink(array('name'=>'login')),
		 );
		
		if(is_array($array) && count($array)>0)
			$array = array_merge($com_array,$array);
		else 
			$array = $com_array;
		foreach($array as $key => $val)
		{
			$var_array[] = '/'.$key.'/';
			$val_array[] = $val;
		}
		return preg_replace($var_array,$val_array,$content);
	}
	
	/**
	 * Function used to get all templates
	 */
	function get_templates()
	{
		global $db;
		$results = $db->select($this->db_tpl,"*",NULL,NULL," email_template_name DESC");
		if($db->num_rows>0)
			return $results;
		else
			return false;
	}
	
	
	/**
	 * Function used to update email template
	 */
	function update_template($params)
	{
		global $db;
		$id = mysql_clean($params['id']);
		$subj = mysql_clean($params['subj']);
		$msg = mysql_real_escape_string($params['msg']);
		
		if(!$this->template_exists($id))
			e("Email template does not exist");
		elseif(empty($subj))
			e("Email subject was empty");
		elseif(empty($msg))
			e("Email msg was empty");
		else
		{
			$db->update($this->db_tpl,array("email_template_subject","email_template"),array($subj,$msg),
									" email_template_id='$id'");
			e("Email Template has been updated","m");
		}
		
	}
}

?>