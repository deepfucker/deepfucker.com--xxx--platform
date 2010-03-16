<?php
	/**
	* @Software : ClipBucket
	* @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
	* @version :ClipBucket v2
	*/

	$BDTYPE = "mysql";
	//Database Host
	$DBHOST = "localhost";
	//Database Name
	$DBNAME = "sample_cbv2";
	//Database Username
	$DBUSER = "root";
	//Database Password
	$DBPASS = "";
	//Setting Table Prefix
	define("TABLE_PREFIX","cb_");

	require 'adodb/adodb.inc.php';

	$db = ADONewConnection($BDTYPE);
	$db->debug = false;
	$db->charpage = 'cp_utf8';
	$db->charset = 'utf8';
	if(!$db->Connect($DBHOST, $DBUSER, $DBPASS, $DBNAME))
	{
	exit($db->ErrorMsg());
	}
	$db->Connect($DBHOST, $DBUSER, $DBPASS, $DBNAME);
	
?>