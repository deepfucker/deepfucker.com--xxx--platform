<?php

/**
 * This will let you edit ClipBucket templatates
 * @License : CBLA
 * @Since : jan 14 2010
 * @Author : Arslan
 */
 
 
require_once '../includes/admin_config.php';
$userquery->admin_login_check();
//$pages->page_redir();


/**
 * Getting List Of Templates
 */
$templates = $cbtpl->get_templates();

#Checking if user has selected template for editing, if not, make SELECTED template for editing
$sel_dir = $_GET['dir'];
if(!$sel_dir || !$cbtpl->is_template($sel_dir))
{
	$sel_dir = TEMPLATE;
}

//Checking if still there is no template, display error
if(!$cbtpl->is_template($sel_dir))
{
	e("No Template Found");
}else
{
	assign('sel_dir',$sel_dir);
	//Getting list template layout files , i.e HTML files
	$files = $cbtpl->get_template_files($sel_dir );
	assign('tpl_files',$files);
	//Getting list of css files
	$css_files = $cbtpl->get_template_files($sel_dir,'theme');
	assign('css_files',$css_files);
	
	//Reading File
	if(isset($_GET['file']) && isset($_GET['folder']))
	{
		$file = STYLES_DIR.'/'.TEMPLATE.'/'.$_GET['folder'].'/'.$_GET['file'];
		
		if(file_exists($file))
		{
			if(isset($_POST['update_file']))
			{
				if(is_writable($file))
				{
					//echo $file;
					$data = $_POST['thecontent'];
					$open_file = fopen($file, "w");
					fwrite($open_file, $data);
					e("File has been updated","m");
				}else
					e("Unable to write file");
			}
			
			$content = htmlentities(file_get_contents($file));
			assign('content',$content);
		
			if(!is_writable($file))
				assign('writeable','no');
			else
				assign('writeable','yes');
		}

	}
}


//Getting And Listing Files
if(!file_exists(BASEDIR.'/'.TEMPLATEFOLDER.'/'.@$_GET['temp']) || @$_GET['temp']==''){
$dir = SITETEMPLATEDIR.'/layout/';
$cur_dir = TEMPLATE;
}else{
$dir = BASEDIR.'/'.TEMPLATEFOLDER.'/'.$_GET['temp'].'/layout/';
$cur_dir = $_GET['temp'];
}
if(!($dp = opendir($dir))) die("Cannot open $dir.");
while($file = readdir($dp)){
$ext = GetExt($file);
if($ext == 'html' || $ext == 'HTML'){
$files[] = $file;
}
}
closedir($dp
);
sort($files);
Assign('files',$files);

//Writng File
if(isset($_POST['save'])){
	$file = $dir.$_POST['file'];
	$data = stripslashes($_POST['data']);
	$open_file = fopen($file, "w");
	fwrite($open_file, $data);
	$msg = $_POST['file']." Has Been Saved And Updated";
}

//Getting Data from File
if(isset($_POST['file'])){
$file 	= $dir.$_POST['file'];
$_file 	= $_POST['file'];
}else{
$file 	= $dir.$files[0];
$_file 	= $files[0];
}
$open_file = fopen($file, "r");
$data = htmlentities(file_get_contents($file));

//Getting Template List
$query = "SELECT * FROM template ORDER by template_name";
$TmpExe = $db->Execute($query);
$Temps = $TmpExe->getrows();
Assign('Temps',$Temps);

//Checking Which Template Currently Using
$query = mysql_query("SELECT template_name,template_dir FROM template WHERE template_dir='".mysql_clean($cur_dir)."'");
$TempArr = mysql_fetch_assoc($query);


template_files('template_editor.html');
display_it();
?>