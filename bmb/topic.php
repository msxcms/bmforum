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
//
define("canceltbauto", "yes");
define("onlinedelay", "1");
//
require("getskin.php");
include("include/bmbcodes.php");
require("lang/$language/topic.php");
require("lang/$language/index.php");
require("newtem/$temfilename/global.php");
require("include/forum.inc.php");
require("include/topic.func.php");
require("include/template.php");
require("include/readpost.func.php");

$gotoforumid = $forumid;

// ===================================
// Read Thread
// ===================================
$query = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$result = bmbdb_query($query);
$row = bmbdb_fetch_array($result);

// ===================================
// Process data
// ===================================
$threadrow = $row;
$replyerlist = explode("|", $row['replyer']);
$forumid = $row['forumid'];
$thtoptype = $row['toptype'];
$topic_author = $row['author'];
$this_is_gift = $row['alldata'];
if (isset($page) && !is_numeric($page) && $page != "last") {
    $page = "";
} 
if ((!$forumid || !$row['tid']) && $getlastpost != "yes") {
	if ($ajax_reply == 1) ajax_browse_error($errora[2]);
	error_page($error[0], $errora[1], $error[0], $errora[2]);

} 
if ($filename != "") {
    $readed = explode("|", $_SESSION['readpost']);
    if (!in_array($filename, $readed)) {
        $_SESSION['readpost'] = $filename . "|" . $_SESSION['readpost'];
    } else {
        $hasread = 1;
    } 
} 

if ($row['ttagname']) {
	$ttagname = $row['ttagname'];
	$ttag_ex = explode(" ", $ttagname);

	eval(load_hook('int_topic_tagexplode'));
}

// ===================================
// Get Data
// ===================================
getUserInfo();
get_forum_info($xfourmrow);
// ============
// Online 
if ($login_status == 1) add_online(1);
    else add_guest(1);
// ============
if ($forum_style <> "") {
    if (file_exists("datafile/style/" . basename($forum_style))) include("datafile/style/" . basename($forum_style));
} 

// ===================================
// Load Template
// ===================================
require("newtem/$temfilename/global.php");

$lang_zone = array("npost"=>$npost, "replyicon"=>$replyicon, "gl"=>$gl, "sellit_lng"=>$sellit_lng, "l_similar"=>$l_similar, "online_info_show"=>$online_info_show, "get_sta_lng"=>$get_sta_lng, "jsinfo"=>$jsinfo, "forum_pos"=>$forum_pos, "unreguser"=>$unreguser, "read_post"=>$read_post, "tip"=>$tip, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("topic", $temfilename, $styleidcode, $lang_zone);


// Loaded
// ===================================

if ($login_status != 2 && $login_status != 0) {
    $lastuploadtime = gmdate("zY", $clastupload + $bbsdetime * 60 * 60);
    $lastuploadtime_a = gmdate("zY", $timestamp + $bbsdetime * 60 * 60);
    if ($lastuploadtime != $lastuploadtime_a) {
        $uploadfiletoday = 0;
    } 
} 

$postads = "";
if (file_exists('datafile/postads.php')) {
    $postads = file("datafile/postads.php");
    $countpostads = count($postads);
} 


// ===================================
// Sub/Father Forum
// ===================================
$xfourmrow = $sxfourmrow;
for($i = 0;$i < $forumscount;$i++) {
    if ($xfourmrow[$i][id] == $row[forumid]) {
        $bbsname = $xfourmrow[$i][bbsname];
        $adminlist .= $xfourmrow[$i]['adminlist'];
    } 
    if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i][id] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
} 


// ===================================
// Admin List
// ===================================
$forum_admin = explode("|", $adminlist);
$forum_admin1 = $forum_admin;
tbuser();
$up_id = $forum_upid;
for($i = 0;$i < count($xfourmrow);$i++) {
    if ($up_id == $xfourmrow[$i]['id']) {
        $up_name = $xfourmrow[$i]['bbsname'];
        break;
    } 
} 

check_forum_permission(1);
// ===================================
// Check if thread in the recycle bin
// ===================================
if ($row['ttrash'] == 1) $checktrash = "yes"; 

if ($checktrash == "yes" && $view_recybin != "1") {
	if ($ajax_reply == 1) ajax_browse_error($gl[438]);
	error_page($error[3],
        "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[192], $gl[438], $gl[192]);

} 
// =================================
// GET LAEST THREAD
// ===================================
if ($getlastpost == yes) {
	eval(load_hook('int_topic_getlastpost'));
    $query = "SELECT COUNT(*) FROM {$database_up}threads WHERE tid='$ct' AND ttrash!='1' AND forumid!='$forumid' LIMIT 0,1";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $row = $fcount['COUNT(*)'];
    if ($row > 0) {
        $line['tid'] = $ct;
    } else {
        $query = "SELECT * FROM {$database_up}threads WHERE forumid='$gotoforumid' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $sresult = bmbdb_query($query);
        $line = bmbdb_fetch_array($sresult);
    } 

    if ($line['tid'] == "") {
        echo "<meta http-equiv=\"Refresh\" content=\"0; URL=index.php\">";
        exit;
    } 
    @setcookie('lastpath', "abcsad.php", 0, $cookie_p, $cookie_d);
    echo "<meta http-equiv=\"Refresh\" content=\"0; URL=topic.php?filename={$line['tid']}&amp;page=$page#postend\">";
    exit;
} 
// ===================================
// Check Password
// ===================================
if ($forum_pwd <> "" && $forum_pwd <> "d41d8cd98f00b204e9800998ecf8427e" && $job <> "login" && $_COOKIE['b' . $forumid . 'mb'] <> $forum_pwd) {
    echo "<meta http-equiv=\"Refresh\" content=\"0; URL=forums.php?forumid=$forumid\">";
    exit;
} 

if ($view_forum_online && $allow_ajax_browse && $ajax_online) {
	$opened_ajax = 1;
    whosonline("|t|$filename|", array('tid' => $filename));
    require($template_name);
    exit;
}

if ($down_attach == 0 || $userpoint < $down_attach_ww) $checkattachpic_true = 1; 


// ===================================
// Read Hits Cache
// ===================================

if ($hasread != 1) {
    if ($cacheclick) $nquery = "UPDATE LOW_PRIORITY {$database_up}threads SET hits = hits+1 WHERE tid = '$filename'";
    else $nquery = "UPDATE {$database_up}threads SET hits = hits+1 WHERE tid = '$filename'";
    $result = bmbdb_unbuffered_query($nquery);
    
    if (!empty($browse_add_point) && $userbym < $usertype[108] && $login_status == 1) $result = bmbdb_unbuffered_query("UPDATE {$database_up}userlist SET point = point + $browse_add_point WHERE userid='$userid' LIMIT 1");
} 

$myusertype = $usertype;

// ===================================
// Data Process
// ===================================
$topic_name = stripslashes($row['title']);
$topic_date = getfulldate($row['time']);

if ($bmfopt['advanced_headers']) {
	define("HEADERS_SENT", 1);
	header("Last-Modified: ".date('r', $row['changetime'])); 
	header("ETag: ".md5($row['title'].$row['changetime'].$page)); 
}


// ===================================
// Igrone Members' Posts
// ===================================
$nresult_friend = bmbdb_query("SELECT * FROM {$database_up}contacts WHERE `type`=2 and `owner`='$userid'");
while (false !== ($line_friend = bmbdb_fetch_array($nresult_friend))) {
	$iguserlist[] = $line_friend['conname'];
} 

$is_ajax_poll = $sub = 0;
$topic_hit = $row['hits'];
$topic_reply = $row['replys'];
$topic_islock = $row['islock'];
$topic_type = trim($row['type']);
$clicked = $row['hits'] + 1;
// ===================================
// Pages Count
// ===================================
$count = $topic_reply + 1;
if (!$page) $page = 1;
if ($count % $read_perpage == 0) $maxpageno = $count / $read_perpage;
else $maxpageno = floor($count / $read_perpage) + 1;
if ($page == "last" || $page > $maxpageno) $page = $maxpageno;

if ($ajax_display && $_GET['floor']) {
	if (($_GET['floor']-1) <= $topic_reply && $_GET['floor'] >= 1) {
		$now_id = $_GET['floor']-1;
	    $jump_fetch = bmbdb_fetch_array(bmbdb_query("SELECT id FROM {$database_up}posts WHERE tid='$filename' ORDER BY `id` ASC LIMIT {$now_id},1"));
	    $pid = $jump_fetch['id'];
	} else $ajax_display = $_GET['floor'] = "";
}

$is_numpid = is_numeric($pid);

if ($bmfopt['return_opage'] && $extra) { 
	$url_extra = "&amp;".strip_tags($extra);
	$extra = "&amp;extra=".urlencode($extra);
} else $extra = "";


if ($is_numpid && $ajax_display == 1) {
    $maxpageno = 1;
} else {
    $pagemin = min(($page-1) * $read_perpage , $count-1);
    $pagemax = min($pagemin + $read_perpage-1, $count-1);
}
eval(load_hook('int_topic_before_multi_page'));
//
//if ($maxpageno > 1) {
//    $nextpage = $page + 1;
//    $previouspage = $page-1;
//    $maxpagenum = $page + 4;
//    $minpagenum = $page - 4;
//    
//    $current_page_url = ($bmfopt['rewrite'] ? "topic_{$filename}" : "topic.php?filename=$filename{$extra}");
//
//    $multi_page_bar = "<div class=\"bmbajaxpagebar\"></div><table class=\"tableborder_withoutwidth radius\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\"><tr><td class=\"pagenumber\"><strong>&nbsp;&nbsp;{$page}/$maxpageno&nbsp;&nbsp;</strong></td>";
//    $multi_page_bar .= $allow_ajax_browse ? "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"javascript:bmb_ajax_displaypost('1','$filename');window.scroll(0,0);window.location='#page1';\"><a href=\"$current_page_url\"></a><a onclick=\"bmb_ajax_displaypost('1','$filename');window.scroll(0,0);window.location='#page1';\" href=\"javascript:window.scroll(0,0);\"><strong>«</strong></a></td>" : "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='$current_page_url';\"><a href=\"$current_page_url\"><strong>«</strong></a></td>";
//
//    for ($i = $minpagenum; $i <= $maxpagenum; $i++) {
//        if ($i > 0 && $i <= $maxpageno) {
//            if ($i == $page) {
//                $multi_page_bar .= "<td class=\"pagenumber_1\"><strong><u>$i</u></strong></td>\n";
//            } else {
//            	$current_page_url = ($bmfopt['rewrite'] ? "topic_{$filename}_{$i}" : "topic.php?filename=$filename&amp;page=$i{$extra}");
//            	$multi_page_bar .= $allow_ajax_browse ? "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"bmb_ajax_displaypost('$i','$filename');window.scroll(0,0);window.location='#page$i';\"><a href=\"$current_page_url\"></a><a onclick=\"bmb_ajax_displaypost('$i','$filename');window.scroll(0,0);window.location='#page$i';\" href=\"javascript:window.scroll(0,0);\">$i</a></td>" : "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='$current_page_url';\"><a href=\"$current_page_url\">$i</a></td>";
//            } 
//        } 
//    } 
//    $current_page_url = ($bmfopt['rewrite'] ? "topic_{$filename}_{$maxpageno}" : "topic.php?filename=$filename&amp;page=$maxpageno{$extra}");
//    $multi_page_bar .= $allow_ajax_browse ? "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"bmb_ajax_displaypost('$maxpageno','$filename');window.scroll(0,0);window.location='#page$maxpageno';\"><a href=\"$current_page_url\"></a><a onclick=\"bmb_ajax_displaypost('$maxpageno','$filename');window.scroll(0,0);window.location='#page$maxpageno';\" href=\"javascript:window.scroll(0,0);\"><strong>»</strong></a></td><td class=\"pagenumber_2\" style=\"padding: 0\"><input size='2' type='text' onkeydown='javascript:ajax_gotopages(event,this,\"$filename\");window.scroll(0,0);' /></td><td class=\"pagenumber_2\" style=\"width:auto;padding: 0;padding-left:4px;padding-right:4px;\">$read_post[55] <input size='2' type='text' onkeydown='javascript:ajax_gotofloor(event,this,\"$filename\",\"$topic_reply\",\"$read_post[56]\",\"$read_perpage\");window.scroll(0,0);' /></td>" : "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='$current_page_url';\"><a href=\"$current_page_url\"><strong>»</strong></a></td><td class=\"pagenumber_2\" style=\"padding: 0\"><input size='2' type='text' onkeydown='javascript:gotopages(event,this,\"$extra\");window.scroll(0,0);' /></td><td class=\"pagenumber_2\" style=\"width:auto;padding: 0;padding-left:4px;padding-right:4px;\">$read_post[55] <input size='2' type='text' onkeydown='javascript:gotofloor(event,this,\"$filename\",\"$topic_reply\",\"$read_perpage\",\"$read_post[56]\");window.scroll(0,0);' /></td>";
//    $multi_page_bar .= "</tr></table>";
//} 
if ($maxpageno > 1) {
	$pagerLink = ($bmfopt['rewrite'] ? "topic_{$filename}_{page}" : "topic.php?filename=$filename&amp;page={page}{$extra}");
}


eval(load_hook('int_topic_after_multi_page'));
$pagemaxq = $pagemax + 1;
// ===================================
// Title
// ===================================
//set_var(array("script_pos" => $script_pos, "thispageurl" => "$script_pos/".($bmfopt['rewrite'] ? "topic_{$filename}_{$page}" : "topic.php?filename=$filename&amp;page=$page"), "fileusername" => urlencode($username), "bbs_title" => $bbs_title, "page" => $page, "" => , "username" => $username, "topic_name" => $topic_name, "topic_author" => $topic_author, "filename" => $filename, "forumid" => $forumid, "multi_page_bar" => $multi_page_bar,));
$url_bbs_title = urlencode($bbs_title);
$thispageurl = "$script_pos/".($bmfopt['rewrite'] ? "topic_{$filename}_{$page}" : "topic.php?filename=$filename&amp;page=$page");
if ($ajax_display != 1) {
	$keyword_site = str_replace(" ", "," ,$row['ttagname']).",".$keyword_site;
    $oldbbs_title = $bbs_title;
    $bbs_title = strip_tags($topic_name) . " &lt; " . strip_tags($bbsname) . " &lt; " . strip_tags($bbs_title);
    require("header.php");
    $bbs_title = $oldbbs_title;

    // ===================================
    // Nav Bar.
    // ===================================
    $navimode = newmode;
    
    $prefix_file = ($bmfopt['rewrite'] && !$url_extra) ? "forums_" : "forums.php?forumid=";
    
    if (empty($up_name)) {
        $des = "$tip[6]<strong class='cau'>$row[hits]</strong>$tip[7],$tip[8]<strong class='cau'>$row[replys]</strong> $tip[9]";
        $snavi_bar[0] = "<a href='{$prefix_file}$forumid{$url_extra}'>$bbsname</a>";
        $snavi_bar[1] = $topic_name;
        navi_bar();
    } else {
        $des = "$tip[6]<strong class='cau'>$row[hits]</strong>$tip[7],$tip[8]<strong class='cau'>$row[replys]</strong> $tip[9]";
        $snavi_bar[0] = "<a href='{$prefix_file}$up_id'>$up_name</a>";
        $snavi_bar[1] = "<a href='{$prefix_file}$forumid{$url_extra}'>$bbsname</a>";
        $snavi_bar[2] = $topic_name;
        navi_bar();
    } 

    if ($view_forum_online) whosonline("|t|$filename|", array('tid' => $filename));


    // ===================================
    // Check Topic Type and call function
    // ===================================
    switch ($topic_type) {
        case "1": get_sta();
            break;
        case "4": get_sta();
            break;
    } 
    eval(load_hook('int_topic_topic_init'));
}
if ($display_poll == 1) get_sta();
// ===================================
// SQL Query
// ===================================
if ($display_poll != 1) {
	if ($is_numpid && $ajax_display == 1) {
		$pagemin = $pagemax = 0;
	    $aquery = "SELECT m.*,o.* FROM {$database_up}posts o LEFT JOIN {$database_up}userlist m ON m.userid=o.usrid WHERE o.id='$pid'";
	    $aresult = bmbdb_query($aquery);
	} else {
		$queryIDtoSeek = bmbdb_query_fetch("Select id From {$database_up}posts WHERE tid='$filename' limit $pagemin,1");
		$limitPageFind = $pagemaxq - $pagemin;
	    $aquery = "SELECT p.*,m.*,o.* FROM {$database_up}posts o LEFT JOIN {$database_up}userlist m ON m.userid=o.usrid LEFT JOIN {$database_up}polls p ON p.id=o.id WHERE o.tid='$filename' and o.id>={$queryIDtoSeek['id']} LIMIT $limitPageFind";
	    $aresult = bmbdb_query($aquery);
	}

	// Load Custom Info

	if (file_exists('datafile/reg_custom.php')) {
		$reg_c = file("datafile/reg_custom.php"); 
	} else $reg_c = "";

	// ===================================
	// Begin Display Posts
	// ===================================

	display_posts();
}

if ($multi_page_bar && $ajax_display == 1) {
    require($template_name);
} 
// ===================================
// Forum Jumping
// ===================================
$count = count($xfourmrow);
for ($i = 0; $i <= $count; $i++) {
   	eval(load_hook('int_topic_jumping'));
	if ($bmfopt['hidebyug'] && !check_forum_permission(0, 1, $xfourmrow[$i])) {
		continue;
	}
    if ($xfourmrow[$i]['type'] == "category") $listsshow .= "<option value='index.php?cateid={$xfourmrow[$i]['id']}'>{$xfourmrow[$i]['bbsname']}</option>";
    if ($xfourmrow[$i]['type'] == "forum" || $xfourmrow[$i]['type'] == "former" || $xfourmrow[$i]['type'] == "jump" || $xfourmrow[$i]['type'] == "selection" || $xfourmrow[$i]['type'] == "locked" || $xfourmrow[$i]['type'] == "closed") $listsshow .= "<option value='forums.php?forumid={$xfourmrow[$i]['id']}'>&nbsp;|- {$xfourmrow[$i]['bbsname']}</option>";
    if (check_permission($username, $xfourmrow[$i]['type']) && ($xfourmrow[$i]['type'] == 'hidden' || $xfourmrow[$i]['type'] == 'forumhid')) $listsshow .= "<option value='forums.php?forumid={$xfourmrow[$i]['id']}'>&nbsp;|- {$xfourmrow[$i]['bbsname']}</option>";
    if ($xfourmrow[$i]['type'] == "subforum" || $xfourmrow[$i]['type'] == "subformer" || $xfourmrow[$i]['type'] == "subjump" || $xfourmrow[$i]['type'] == "subselection" || $xfourmrow[$i]['type'] == "sublocked" || $xfourmrow[$i]['type'] == "subclosed") $listsshow .= "<option value='forums.php?forumid={$xfourmrow[$i]['id']}'>&nbsp;|-|- {$xfourmrow[$i]['bbsname']}</option>";
    if (check_permission($username, $xfourmrow[$i]['type']) && ($xfourmrow[$i]['type'] == 'subhidden' || $xfourmrow[$i]['type'] == 'subforumhid')) $listsshow .= "<option value='forums.php?forumid={$xfourmrow[$i]['id']}'>&nbsp;|-|- {$xfourmrow[$i]['bbsname']}</option>";
} 

// ===================================
// Management Menu
// ===================================
if ($login_status == 1 && $topic_author == $username && $checktrash != "yes" && $del_self_topic == 1) $manageops .= "<strong>$reader_mang[0]</strong>[<a href='misc.php?p=manage&amp;action=del&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[1]</a>]";
if ($login_status == 1 && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1")) {
    manage_op();
} 
similar_threads($filename, $ttag_ex);
// ===================================
// Fast-Post New thread
// ===================================
if ($checktrash != "yes" && $topic_islock != 1 && $topic_islock != 3) {
    if ($frep_select == 1 && $login_status) {
    	$allow_fast_reply = 1;
    	fast_reply();
    } 
} 

require($template_name);
navi_bar();
require("footer.php");
