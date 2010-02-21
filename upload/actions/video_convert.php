<?php
 /**

  * Written by : Arslan Hassan
  * Software : ClipBucket v2
  * License : CBLA
  **/

$in_bg_cron = true;
ini_set('mysql.connect_timeout','6000');

include(dirname(__FILE__)."/../includes/config.inc.php");

$SYSTEM_OS = $row['sys_os'] ? $row['sys_os'] : 'linux';
	
//Including FFMPEG CLASS
require_once(BASEDIR.'/includes/classes/conversion/ffmpeg.class.php');

//Get Vido
$queue_details = get_queued_video(TRUE);

//Setting up details, moving files
$tmp_file = $queue_details['cqueue_name'];
$tmp_ext =  $queue_details['cqueue_tmp_ext'];
$ext =  $queue_details['cqueue_ext'];

if(!empty($tmp_file)){	
$temp_file = TEMP_DIR.'/'.$tmp_file.'.'.$tmp_ext;
$orig_file = CON_DIR.'/'.$tmp_file.'.'.$ext;

	//copy($temp_file,$orig_file);
	rename($temp_file,$orig_file);
	
	$configs = array
	(
		'use_video_rate' => true,
		'use_video_bit_rate' => true,
		'use_audio_rate' => true,
		'use_audio_bit_rate' => true,
		'use_audio_codec' => true,
		'format' => 'flv',
		'video_codec'=> config('video_codec'),
		'audio_codec'=> config('audio_codec'),
		'audio_rate'=> config("srate"),
		'audio_bitrate'=> config("sbrate"),
		'video_rate'=> config("vrate"),
		'video_bitrate'=> config("vbrate"),
		'video_width'=> config('r_width'),
		'video_height'=> config('r_height'),
		'resize'=>'max'
	);

	
	/**
	 * Calling Functions before converting Video
	 */
	if(get_functions('before_convert_functions'))
	{
		foreach(get_functions('before_convert_functions') as $func)
		{
			if(@function_exists($func))
				$func();
		}
	}
	
	
	$ffmpeg = new ffmpeg($orig_file);
	$ffmpeg->configs = $configs;
	$ffmpeg->gen_thumbs = TRUE;
	$ffmpeg->gen_big_thumb = TRUE;
	$ffmpeg->num_of_thumbs = config('num_thumbs');
	$ffmpeg->thumb_dim = config('thumb_width')."x".config('thumb_height');
	$ffmpeg->big_thumb_dim = config('big_thumb_width')."x".config('big_thumb_height');
	$ffmpeg->tmp_dir = TEMP_DIR;
	$ffmpeg->input_ext = $ext;
	$ffmpeg->output_file = VIDEOS_DIR.'/'.$tmp_file.'.flv';
	$ffmpeg->hq_output_file = VIDEOS_DIR.'/'.$tmp_file.'.mp4';
	$ffmpeg->log_file = LOGS_DIR.'/'.$tmp_file.'.log';
	//$ffmpeg->remove_input=TRUE;
	$ffmpeg->ClipBucket();
	//Converting File In HD Format
	$ffmpeg->convert_to_hd();
	unlink($ffmpeg->input_file);
	
	exec(php_path()." -q ".BASEDIR."/actions/verify_converted_videos.php &> /dev/null &");
}


?>