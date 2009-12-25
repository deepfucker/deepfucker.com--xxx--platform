<?php

/**
 * Function : Checks all requirments for ClipBucket
 * Author : Frank White, Arslan Hassan
 * Software : CLipBucket
 * Since : 25-12-2009
 */
 
 	define("BASEDIR","..");
	function is_files_writeable($subdir=NULL)
    {
		if(!$subdir)
		{
			if(!is_writable(BASEDIR.'/files'))
			{
				return false;
			}else
				return true;
		}else
		{
			if(!is_writable(BASEDIR.'/files/'.$subdir))
			{
				return false;
			}else
				return true;
		}
    }

    function is_images_writeable($subdir=NULL)
    {
		if(!$subdir)
		{
			if(!is_writable(BASEDIR.'/images'))
			{
				return false;
			}else
				return true;
		}else
		{
			if(!is_writable(BASEDIR.'/images/'.$subdir))
			{
				return false;
			}else
				return true;
		}
    }

    function FilterArray($value)
    {
		if(preg_match('/share/i', $value) || preg_match('/:/', $value) ||
		preg_match('/etc/', $value)|| preg_match('/lib/', $value) ||
		preg_match('/include/', $value) || preg_match('/src/', $value)
		|| preg_match('/man/', $value))
		{
		return false;
		}
		return true;
    }

    function LocCheck($bin)
    {
		$new        = array();
		$check      = whereis($bin);
		if($check)
		{
		$check      = explode(' ',$check);
		$filtered   = array_filter($check,'FilterArray');
		$filtered   = array_merge((array)$new, (array)$filtered);
		return $filtered;
		}
		$check  = array('Not Installed');
		return $check;
    }


    function is_exec_enabled()
    {
		$safe_mode          = ini_get('safe_mode');
		$safe_mode_execdir  = ini_get('safe_mode_execdir');
		if(!empty($safe_mode_execdir))
		{
			return false;
		}else
			return true;
    }


    function check_upload_size_valid($mb,$value)
    {
		$check  = ini_get($value);
		if(preg_match('/M/i', $check))
		{
			$check = str_replace('M','',$check);
			if($mb > $check)
			{
				return 1;
			}
		}
		elseif(preg_match('/G/i', $check))
		{
			$check = str_replace('G','',$check);
			$check = $check * 1024;
			if($mb > $check)
			{
				return 1;
			}
		}
		elseif(preg_match('/K/i', $check))
		{
			$mb = $mb * 8192;
			$check = str_replace('K','',$check);
			if($mb > $check)
			{
				return 1;
			}
		}
		else
		{
			$mb = $mb * 1048576;
			if($mb > $check)
			{
				return 1;
			}
		}
    }
?>