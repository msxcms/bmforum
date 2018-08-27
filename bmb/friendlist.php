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
include("include/template.php");
require("lang/$language/usercp.php");


if ($login_status == 0)	error_page($navi_bar_des, $navi_bar_l2, $gl[53], $gl[73], $gl[53]);

$fusertype = ($fusertype == "f") ? 0 : $fusertype;

$username = strtolower($username);
if ($action == "clean") {
	eval(load_hook('int_friendlist_clean'));
    bmbdb_query("DELETE FROM {$database_up}contacts WHERE `owner`='$userid' ");
    jump_page("friendlist.php", $gl[2], "<strong>$gl[2]</strong><br /><br />$gl[3] <a href='javascript:history.back(1)'>$gl[15]</a>", 3);
} 
if ($action == "add") {
    if ($fusertype > 2 || $fusertype < 0) {
    	error_page($gl[52], $gl[53], $gl[53], $gl[54]);
    } 
	eval(load_hook('int_friendlist_add'));
    $target_user = bmbdb_query_fetch("SELECT userid FROM {$database_up}userlist WHERE username='$friendname' LIMIT 0,1");

	$exists = bmbdb_query_fetch("SELECT COUNT(`id`) FROM {$database_up}contacts WHERE type='$fusertype' and `owner`='$userid' and `contacts`='{$target_user['userid']}'");

    if ($exists['COUNT(`id`)'] == 0) {
        bmbdb_query("INSERT INTO {$database_up}contacts (`type`,`adddate`,`owner`,`contacts`,`conname`) VALUES ('$fusertype', '$timestamp', '$userid', '{$target_user['userid']}','$friendname')");
        $echoInfo = "$gl[57]";
        if ($frdduan && $fusertype == 0) {
            mtou($friendname);
        } 
    } else {
        $echoInfo = "$gl[58]";
    } 

	eval(load_hook('int_friendlist_add_bjump'));
    jump_page("friendlist.php", $echoInfo,
        "<strong>$echoInfo</strong><br /><br />$gl[3] <a href='friendlist.php'>$gl[15]</a>", 3);
} 
if (empty($step) && $action == "del") {
    $content = "$gl[171]<br />
<br />$gl[172]<ul>
<li><a href='javascript:history.back(1)'>$gl[15]</a><br /><br /></li>
<li><a href='friendlist.php?action=del&friendname=$friendname&step=2&type=$type'>$gl[173]</a>
";
    $content .= "</ul>";
	eval(load_hook('int_friendlist_del_form'));

	error_page($gl[165], $gl[166], $gl[170], $content);

} elseif ($type != "" && !empty($step) && $action == "del") {
    // ------get the line from the original list------
	eval(load_hook('int_friendlist_del'));

	bmbdb_query("DELETE FROM {$database_up}contacts WHERE `type`='$type' and `owner`='$userid' and `conname`='{$friendname}'");

    jump_page("friendlist.php", "$gl[2]",
        "<strong>$gl[2]</strong><br /><br />$gl[3] <a href='friendlist.php'>$gl[15]</a> | <a href='index.php'>$gl[5]</a>", 3);
//} elseif (!empty($step) && !empty($type) && $action == "del") {
//	error_page($gl[50], $gl[50], $gl[53], $gl[168], $gl[50]);
} 

if (empty($page)) $page = 1;
$forum_name = "$gl[176]";
$add_title = " &gt; $forum_name";
require("header.php");

$result = bmbdb_query_fetch("SELECT COUNT(`id`) FROM {$database_up}contacts where `owner`='$userid'");

$count = $amount = $result['COUNT(`id`)'];

$des = "$gl[177]<br />";
$des23 = "$gl[178]";
navi_bar($des, $des23);

$lang_zone = array("mmssms"=>$mmssms, "navbarshow"=>$navbarshow, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);

$currentMod['friendlist'] = true;

$template_name['usercp'] = newtemplate("usercp", $temfilename, $styleidcode, $lang_zone);
$template_name['usercp_foot'] = newtemplate("usercp_foot", $temfilename, $styleidcode, $lang_zone);
eval(load_hook('int_friendlist_outputusercp'));
require($template_name['usercp']);

$total = (max($page, 10) * $perpage);

if ($count % $perpage == 0) $maxpageno = $count / $perpage;
else $maxpageno = floor($count / $perpage) + 1;
if ($page > $maxpageno) $page = $maxpageno;
$pagemin = min(($page-1) * $perpage , $count-1);
$pagemax = min($pagemin + $perpage-1, $count-1);
if ($pagemin < 0) $pagemin = 0;
$result = bmbdb_query("SELECT * FROM {$database_up}contacts where `owner`='$userid' LIMIT $pagemin,$perpage");

if ($count > 0) {
	for ($i = $pagemin; $i <= $pagemax; $i++) {
	    $a_info = bmbdb_fetch_array($result);
	    article_line();
	} 
} else {
    $maxpageno = 1;
}

$pageLink = 'friendlist.php?page={page}';


$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name['fr'] = newtemplate("friendlist", $temfilename, $styleidcode, $lang_zone);
eval(load_hook('int_friendlist_output'));
require($template_name['fr']);
require($template_name['usercp_foot']);
require("footer.php");
exit;
// ------Pages list end---
function article_line()
{
    global $a_info, $friend_arr, $onlineww, $gl, $username, $read_perpage, $otherimages;
    $fusertype = $a_info['type'];
    $xiutime = getfulldate($a_info['adddate']);
    $title = $a_info['conname'];

    $title_back = $title;
    $friendname = $title;
    $urlfriend = urlencode($friendname);;
	
	if (trim($xiutime)) $friend_arr[]=array(check_online($friendname), $a_info['contacts'], $friendname, $fusertype, $urlfriend, $xiutime, $fusertype);
} 
function mtou($ruser)
{
    global $gl, $database_up, $username, $userid, $timestamp, $short_msg_max;
    
    $urlusername = urlencode($username);

    $user = "$gl[60]";
    $content = "<span class=\"jiazhongcolor\"><a href=\"friendlist.php?action=add&fusertype=f&friendname=$urlusername\">$username</a></span> $gl[61]";
    $title = "$gl[60]";

    $lines = bmbdb_query_fetch("SELECT userid FROM {$database_up}userlist WHERE username='$ruser' LIMIT 0,1");
    $ruserid = $lines['userid'];

	$result = bmbdb_query_fetch("SELECT COUNT(`id`) FROM {$database_up}contacts where `type`=1 and `owner`='$userid' and `contacts`='$ruserid'");

    $uisbadu = ($result['COUNT(`id`)'] > 0) ? "yes" : "";

    if ($uisbadu != "yes") {
        $nquery = "insert into {$database_up}primsg (belong,sendto,prtitle,prtime,prcontent,prread,prother,prtype,prkeepsnd,stid,blid) values ('$username','$ruser','$title','$timestamp','$content','0','','r','','$ruserid','$userid')";
        $result = bmbdb_query($nquery);
        $nquery = "UPDATE {$database_up}userlist SET newmess=newmess+1 WHERE userid='$ruserid'";
        $result = bmbdb_query($nquery);
    } 
    eval(load_hook('int_friendlist_pmtouser'));

} 

?> 