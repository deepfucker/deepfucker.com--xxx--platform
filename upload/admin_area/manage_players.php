<?php
/** 
 * CLipBucket v2 Player Manage
 * Author : Arslan
 *
 * Licensed Under CBLA
 * ClipBucket 2007-2009
 */
 
require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();
$userquery->login_check('admin_access');

//Set Mode
assign('mode',$_GET['mode']);

if(isset($_POST['update'])){
	$configs = $Cbucket->configs;
	
	$rows = array(
				  	'autoplay_video',
					'use_playlist',
					);
	
	foreach($rows as $field)
	{
		$value = mysql_clean($_POST[$field]);
		$myquery->Set_Website_Details($field,$value);
	}
	e("Player Settings Have Been Updated",'m');
	
}


if($_GET['set'])
{
	$cbplayer->set_player($_GET);
}

$row = $myquery->Get_Website_Details();
Assign('row',$row);

subtitle("Manage Players");
template_files('manage_players.html');
display_it();
?>