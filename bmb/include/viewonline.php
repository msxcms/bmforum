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
require("header.php");
if (empty($page)) $page = 1;
$check_admin = 0;
if ($usertype[22] == "1") {
    $check_admin = 1;
} 
if ($usertype[21] == "1") {
    $check_admin = 1;
} 

$add_title = "$gl[325]";
navi_bar($gl[326], $gl[327], '', 'yes');

$onlinefile = "datafile/online.php";
$guestfile = "datafile/guest.php";
if (file_exists($onlinefile) && $action == "") {
    $user_array = file($onlinefile);
} 
if (file_exists($onlinefile) && $action == "guest") {
    $user_array = file($guestfile);
} 

	
$threadLink = ($bmfopt['rewrite']) ? "topic_" : "topic.php?filename=";

$count = count($user_array);
if ($count % $perpage == 0) $maxpageno = floor($count / $perpage);
else $maxpageno = floor($count / $perpage) + 1;
if ($page > $maxpageno) {
    $page = $maxpageno;
    $pagemax = $count-1;
    $pagemin = max(0, $count - $perpage-1);
} else {
    if ($page == 1) {
        $pagemin = 0;
        $pagemax = min($count-1, $perpage-1);
    } else {
        $pagemin = min($count-1, $perpage * ($page-1));
        $pagemax = min($count-1, $pagemin + $perpage-1);
    } 
} 
$bmf_ollist = "";
for ($counter = $pagemin; $counter <= $pagemax; $counter++) {
    if (!trim($user_array[$counter])) continue;

    $online_user_info = explode("|", $user_array[$counter]);
    $timetmp = get_date($online_user_info[2]) . " " . get_time($online_user_info[2]);
    if ($check_admin == 1) {
   	    if ($bmfopt["ip_address"]) $iptmp = "<a href='{$bmfopt[ip_address]}$online_user_info[3]' target='_blank'>$online_user_info[3]</a>";
   	      else $iptmp = $online_user_info[3];
    }else $iptmp = "$gl[333]";

    $action_2_info = "$gl[334]";
    $action_info = get_action_info($online_user_info[5]);
    if ($action == "guest")
        $nametmp = "<img border='0' src='$otherimages/system/messages1.gif' alt='' /> " . $online_user_info[1];
    elseif ($online_user_info[11] == "yes") {
        if ($see_amuser == 1) $useryinfo = " (<a href='profile.php?job=show&amp;target=" . urlencode($online_user_info[1]) . "'>{$online_user_info[1]}</a>)";
        $nametmp = "<img border='0' src='$otherimages/system/messages1.gif' alt='' /> " . $gl[175] . $useryinfo;
    } elseif ($online_user_info[1] != "" && $action != "guest")
        $nametmp = "<a href=\"messenger.php?job=write&amp;target=$online_user_info[1]\" ><img border='0' src='$otherimages/system/messages1.gif' alt='$gl[335]' /></a> <a title='$gl[336]' href='profile.php?job=show&amp;target=" . urlencode($online_user_info[1]) . "'>$online_user_info[1]</a>";
	eval(load_hook('int_viewonline_maker'));
    $bmf_ollist[]=array($nametmp, $iptmp, $timetmp, $online_user_info[7], $online_user_info[6], $action_info);
} 


$pageinfos = "<strong>$gl[337]: " . $page . "/" . $maxpageno . "$gl[146]&nbsp;&nbsp;" . $perpage . "$gl[148]/$gl[146] </strong> ";
$nextpage = $page + 1;
$previouspage = $page-1;
if ($page >= 2) $pageinfos.= "<a href=\"misc.php?p=viewonline&amp;action=$action&amp;page=$previouspage\"><strong>$gl[338]</strong></a> ";
if ($page <= $maxpageno-1) $pageinfos.= "<a href=\"misc.php?p=viewonline&amp;action=$action&amp;page=$nextpage\"><strong>$gl[339]</strong></a> ";

$pageinfos.= "
  $gl[340]<input type='text' name='page' size='3' maxlength='3' />$gl[146]
<input type='submit' value='OK' name='submit' />	
";

$useryno = 0;
$userno = 0;
$guestno = 0;
$guestinfo = file($guestfile);
$guestcount = count($guestinfo) -1;
$guestno = $guestcount;

$onlinefile = "datafile/online.php";
$user_array = explode("\n", readfromfile($onlinefile));
$count = count($user_array) -1;
for ($i = 0; $i < $count; $i++) {
    $online_user_info = explode("|", $user_array[$i]);
    if ($online_user_info[11] == "yes") $useryno++;
    else $userno++;
} 
if (file_exists('zy.php')) $zyinfo = explode('|', readfromfile('zy.php'));
$usernoa = $userno + $guestno + $useryno;
$zy_info = get_date($zyinfo[1]);
$usernoa = $userno + $guestno + $useryno;
$usernob = $userno + $useryno;
$time_here = $online_limit / 60;

require("lang/$language/global.php");
$nowinfo = $gl[341];
$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("viewonline", $temfilename, $styleidcode, $lang_zone);
eval(load_hook('int_viewonline_output'));
require($template_name);

require("footer.php");
exit;
// 模块：获得活动信息
function get_action_info($fid)
{
    global $forumlist, $threadLink, $sxfourmrow, $forumscount, $online_user_info, $gl, $action_2_info;
	eval(load_hook('int_viewonline_get_action_info'));

    for($i = 0;$i < $forumscount;$i++) {
        if ($sxfourmrow[$i]['id'] == $fid) {
            if (!empty($online_user_info[9])) {
                return "<a href=\"{$threadLink}$online_user_info[9]\">$gl[316]</a>";
            } else {
                return "<a href=\"forums.php?forumid=$fid\">{$sxfourmrow[$i][bbsname]}</a>";
            } 
        } 
    } 
    if ($fid == 0) return "<a href=\"index.php\">$gl[317]</a>";
    if ($fid == 101) return "<a href=\"misc.php?p=viewonline\">$gl[318]</a>";
    if ($fid == 102) return "<a href=\"search.php\">$gl[319]</a>";
    if ($fid == 103) return "<a href=\"misc.php?p=viewnews\">$gl[320]</a>";
    if ($fid == 104) return "<a href=\"userlist.php\">$gl[321]</a>";
    if ($fid == 105) return "<a href=\"register.php\">$gl[322]</a>";
    if ($fid == 106) return "<a href=\"misc.php?p=viewtop\">$gl[323]</a>";
    if ($fid == 107) return "$gl[324]";
} 

