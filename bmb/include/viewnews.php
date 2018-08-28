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
require("include/common.inc.php");

$add_title = " - $gl[150]";
if ($nwpost_list == 0) {
	error_page($gl[151], $gl[150], $gl[150], $gl[277]);

} 
require("header.php");
navi_bar($gl[151], $gl[150], '', 'yes');
$minv = $bmfopt['view_newpost'] ? $bmfopt['iew_newpost'] : 25; // Show 25 New Topics default
$add_sql = "";
for($i = 0;$i < $forumscount;$i++) {
    if (!(!check_forum_permission(0, 1, $sxfourmrow[$i])) && $sxfourmrow[$i]['type'] != "category" && check_permission($username, $sxfourmrow[$i]['type']) && !$sxfourmrow[$i]['forumpass'] && $sxfourmrow[$i]['forumpass'] <> "d41d8cd98f00b204e9800998ecf8427e") {
        $forumnum["{$sxfourmrow[$i]['id']}"] = $sxfourmrow[$i]['bbsname'];
    } else {
        $add_sql .= "&& forumid!='{$sxfourmrow[$i]['id']}'";
    } 
} 
if ($add_sql != "") $add_sql = "WHERE forumid!='xxxxx' " . $add_sql;
$bmf_news = array();
$query = "SELECT * FROM {$database_up}threads $add_sql ORDER BY `changetime` DESC LIMIT 0,$minv";
$result = bmbdb_query($query);
get_forum_info("");

while (false !== ($row = bmbdb_fetch_array($result))) {
	run_thread_list($row);
	$urlauthor = urlencode($row['author']);
	$bmf_news[]= array($row['tid'], $stats, $icon, $title, $multipage, $row['forumid'], $forum_name, $urlauthor, $row['author'], $aimetoshow, $row['replys'], $row['hits'], $lmdtime, $lmdauthor, 'maxpageno' => $maxpageno, 'titlelink' => $titlelink, 'urllmdauthor' => $urllmdauthor);
} 

$readed_t = explode("|", $_SESSION['readpost']);

$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("viewnews", $temfilename, $styleidcode, $lang_zone);
require($template_name);
eval(load_hook('int_viewnews'));

require("footer.php");

exit;

