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
include_once("include/common.inc.php");

require_once("lang/$language/topic.php");

if ($login_status == 0) {
	error_page("", $report[0], $gl[53], $gl[51]);

} 

$result = bmbdb_query("SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1");
$row = bmbdb_fetch_array($result);
$threadrow = $row;
$forumid = $row[forumid];
tbuser();
$xfourmrow = $sxfourmrow;
if (!$row['tid']) exit;
for($i = 0;$i < $forumscount;$i++) {
    if ($xfourmrow[$i][id] == $forumid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i][id] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i][id] == $row[forumid]) $bbsname = $xfourmrow[$i][bbsname];
} 
get_forum_info($xfourmrow);

if ($adminlist && $adminlist != "|") $arrayal = explode("|", $adminlist);
    else $arrayal = explode(",", $admin_idname);

$admincount = count($arrayal);
for ($i = 0; $i < $admincount; $i++) {
    if ($arrayal[$i]) {
    	if (!@in_array($arrayal[$i], $forum_admin)) {
	        $admin_show .= "<a href='profile.php?job=show&amp;target=". urlencode($arrayal[$i]) ."'>$arrayal[$i]</a>  ";
	        $forum_admin[] = $arrayal[$i];
	    }
    } 
} 

check_forum_permission();

if ($forum_pwd <> "" && $forum_pwd <> "d41d8cd98f00b204e9800998ecf8427e" && $job <> "login" && $_COOKIE['b' . $forumid . 'mb'] <> $forum_pwd) {
    echo "<meta http-equiv=\"Refresh\" content=\"0; URL=forums.php?forumid=$forumid\">";
    exit;
} 

$up_id = $forum_upid;
for($i = 0;$i < count($xfourmrow);$i++) {
    if ($up_id == $xfourmrow[$i]['id']) {
        $up_name = $xfourmrow[$i]['bbsname'];
        break;
    } 
} 

$topic_name = $row[title];
$topic_author = $row[author];
$topic_content = $row[content];
$topic_date = $row[time];
$aaa = $row[ip];
$icon = $row[usericon];
$usesign = $row[options];
$bym = $row[other1];
$bymuser = $row[other2];
$uploadfilename = $row[other3];
$editinfo = $row[other4];
$sellmoney = $row[other5];

$topic_date = getfulldate($topic_date);

if ($action == "submit" && $_POST['message']) {
    $counts = count($forum_admin);
	eval(load_hook('int_report_send'));
    for($i = 0;$i < $counts;$i++) {
        mtou($forum_admin[$i], $message); // Send Report sqled@2004-5-1
    } 
    jump_page("topic.php?forumid=$forumid&filename=$filename", $report[12],
        "<strong>$report[13]</strong><br /><br />
	$others[9] <a href='forums.php?forumid=$forumid'>$report[9]</a> | <a href='topic.php?forumid=$forumid&filename=$filename'>$report[10]</a> | <a href='index.php'>$report[11]</a>", 3);
} else {
    $add_title = " &gt; $forum_name &gt; $topic_name";
    require_once("header.php");
    $navimode = newmode;
    if (empty($up_name)) {
        $des = "$report[0]";
        $snavi_bar[0] = "<a href='forums.php?forumid=$forumid'>$forum_name</a>";
        $snavi_bar[1] = $topic_name;
        navi_bar();
    } else {
        $des = "$report[0]";
        $snavi_bar[0] = "<a href='forums.php?forumid=$up_id'>$up_name</a>";
        $snavi_bar[1] = "<a href='forums.php?forumid=$forumid'>$forum_name</a>";
        $snavi_bar[2] = $topic_name;
        navi_bar();
    } 

	require("include/template.php");
	eval(load_hook('int_report_page'));

	$lang_zone = array("report"=>$report, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("report", $temfilename, $styleidcode, $lang_zone);
	require($template_name);

} 

include("footer.php");

function mtou($ruser, $xcx)
{
    global $userid, $username, $gl, $report, $timestamp, $database_up, $topic_name, $filename, $forumid, $bbs_title, $short_msg_max;
    $content = str_replace("&username&", $username, $report[8]);
    $content = str_replace("&url&", "topic.php?filename=$filename&forumid=$forumid", $content);
    $content = str_replace("&topic_name&", $topic_name, $content);
    $content = str_replace("&detail&", $xcx, $content);

	eval(load_hook('int_report_mtou'));

	announce_user($ruser, $actionshow, $username, $content, $report[7]);
} 
