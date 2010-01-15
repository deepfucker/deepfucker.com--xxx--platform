<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.											|
 | @ Author   : ArslanHassan																		|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
define('THIS_PAGE','search_result');
require 'includes/config.inc.php';
$pages->page_redir();
						
$page = mysql_clean($_GET['page']);
$type = $_GET['type'] ;
$type = $type ? $type : 'videos';
$search = cbsearch::init_search($type);

$search->key = $_GET['query'];
$search->category = $_GET['category'];
$search->date_margin = $_GET['datemargin'];
$search->sort_by = $_GET['sort'];
$search->limit = create_query_limit($page,VLISTPP);
$results = $search->search();

//Collecting Data for Pagination
$total_rows = $cbvid->search->total_results;
$total_pages = count_pages($total_rows,VLISTPP);

//Pagination
$pages->paginate($total_pages,$page);


Assign('results',$results );	
Assign('template_var',$search->template_var);
Assign('display_template',$search->display_template);
Assign('search_type_title',$search->search_type[$type]['title']);


//Displaying The Template
template_files('search.html');
display_it();
pr($db);
?>