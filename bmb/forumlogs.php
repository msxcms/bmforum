<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
require("datafile/config.php");
require("getskin.php");
require("lang/$language/forums.php");
require("include/template.php");
include_once("include/common.inc.php");

$add_title = " &gt; $forum_name";
$xfourmrow = $sxfourmrow;

$check_user = 0;
// ######## 检测是否为管理员开始 ##########
$check_user = check_admin_permission($sxfourmrow, $forumscount, $forumid, $login_status, $check_user, $username);
// ######## 检测是否为管理员结束 ##########
if ($usertype[21] == "1") $check_user = 1;
if ($usertype[22] == "1") $check_user = 1;
if (!$modcenter_true) $check_user = 0;

if ($check_user == 0) {
    $content = "$gl[233]<br /><br />$gl[217]";
	error_page($gl[230], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);
} 
require("header.php");

get_forum_info($xfourmrow);
$up_id = $forum_upid;
for($i = 0;$i < count($xfourmrow);$i++) {
    if ($up_id == $xfourmrow[$i]['id']) {
        $up_name = $xfourmrow[$i]['bbsname'];
        break;
    } 
} 
if (empty($up_name)) {
    $navimode = newmode;
    $des = $tip[10];
    $snavi_bar[0] = "<a href='forums.php?forumid=$forumid'>$forum_name</a>";
    navi_bar();
} else {
    $navimode = newmode;
    $des = $tip[10];
    $snavi_bar[0] = "<a href='forums.php?forumid=$up_id'>$up_name</a>";
    $snavi_bar[1] = "<a href='forums.php?forumid=$forumid'>$forum_name</a>";
    navi_bar();
} 
if ($clean_true && $addon == "clean" && $verify == $log_hash) {
    $nquery = "DELETE FROM {$database_up}actlogs WHERE forumid='$forumid' ";
    $nresult = bmbdb_query($nquery);
    
    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$gl[481]','$username','','$timestamp','{$forumid}','del')";
    $result = bmbdb_query($nquery);

} 

$lang_zone = array("forumlogs"=>$forumlogs, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("forums_log", $temfilename, $styleidcode, $lang_zone);


$nquery = "SELECT * FROM {$database_up}actlogs WHERE forumid='$forumid' ORDER BY `acttime` DESC";
$nresult = bmbdb_query($nquery);
while (false !== ($line = bmbdb_fetch_array($nresult))) {
	$actionshow = "";
    $line['acttime'] = getfulldate($line['acttime']);
    $action = str_replace("\n", "", $line['actioncode']);
    switch ($action) {
        case "move":
	        $actionshow = "$gl[311]";
	        break;
        case "copy":
	        $actionshow = "$gl[312]";
	        break;
        case "del":
	        $actionshow = "$gl[430]";
	        break;
        case "lock":
	        $actionshow = "$gl[431]";
	        break;
        case "unlock":
	        $actionshow = "$gl[432]";
	        break;
        case "jihua":
	        $actionshow = "$gl[433]";
	        break;
        case "unjihua":
	        $actionshow = "$gl[434]";
	        break;
        case "recycle":
	        $actionshow = "$gl[453]";
	        break;
        case "m2del":
	        $actionshow = "$gl[474]";
	        break;
        case "m2holdfront":
	        $actionshow = "$gl[471]";
	        break;
        case "m2unhold":
	        $actionshow = "$gl[472]";
	        break;
        case "m2btfront":
	        $actionshow = "$gl[473]";
	        break;
        case "topdelone":
	        $actionshow = "$gl[476]";
	        break;
        case "topwrite":
	        $actionshow = "$gl[475]";
	        break;
        case "catedelone":
	        $actionshow = "$gl[478]";
	        break;
        case "catewrite":
	        $actionshow = "$gl[477]";
	        break;
        case "m5cancel":
	        $actionshow = "$gl[480]";
	        break;
        case "m5add":
	        $actionshow = "$gl[479]";
	        break;
        case "m4del":
	        $actionshow = "$gl[482]";
	        break;
        case "m4move":
	        $actionshow = "$gl[483]";
	        break;
        case "m4hebing":
	        $actionshow = "$gl[484]";
	        break;
        case "m4recycle":
	        $actionshow = "$gl[491]";
	        break;
        case "m4resume":
	        $actionshow = "$gl[492]";
	        break;
        case "m4lock":
	        $actionshow = "$gl[493]";
	        break;
        case "m4unlock":
	        $actionshow = "$gl[494]";
	        break;
        case "rtrash":
	        $actionshow = "$gl[490]";
	        break;
        case "refund":
	        $actionshow = "$gl[499]";
	        break;
        case "split":
	        $actionshow = "$gl[505]";
	        break;
        case "m2recyclepost":
	        $actionshow = "$gl[526]";
	        break;
        case "m2recoverpost":
	        $actionshow = "$gl[525]";
	        break;
    }
    if ($actionshow) $actionshow = "/ ". $actionshow;
    $line['actionshow'] = $actionshow;
    $line['urlacter'] = urlencode($line['acter']);
    if (empty($line['actreason'])) $line['actreason'] = "None";
    if (!empty($line['actdetail'])) {
    	$bmf_loginfo[]=$line;

    } 
} 
eval(load_hook('int_forums_log'));

require($template_name);


include("footer.php");
