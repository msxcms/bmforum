<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
if (!defined('INBMFORUM')) die("Access Denied");
require("include/template.php");

if (empty($page)) $page = 1;
if ($porank_list == 0) {
	error_page($gl[9], $gl[10], $gl[10], $gl[277]);

} 
require("header.php");
navi_bar("$gl[9]", "$gl[10]", "", "no");
$topnum = $bmfopt[view_ranking] ? $bmfopt[view_ranking] : 15; //那个10代表前10，可以自己修改~
$bmf_rank = "";

$query = "SELECT * FROM {$database_up}userlist ORDER BY `postamount` DESC  LIMIT 0,$topnum";
$result = bmbdb_query($query);

while (false !== ($row = bmbdb_fetch_array($result))) {
    $postamount = $row['postamount'];
    if ($postamount > $maxpoint) $maxpoint = $postamount;
    if (!empty($postamount)) $img_width = 300 * ($postamount) / ($maxpoint);
	
	$bmf_rank[]=array($row['userid'], $row['username'], $postamount, $img_width );

} 
eval(load_hook('int_viewtop'));
$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("viewtop", $temfilename, $styleidcode, $lang_zone);
require($template_name);


include("footer.php");

