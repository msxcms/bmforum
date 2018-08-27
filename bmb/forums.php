<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
require_once ("datafile/config.php");
include_once ("include/template.php");
require_once ("getskin.php");
require_once("include/forum.inc.php");
require_once ("include/forums.func.php");
$listfilename = "list.php";

// ===================================
// Get Forum Info
// ===================================
get_forum_info($sxfourmrow);


$up_id = $forum_upid;
for($i = 0;$i < $forumscount;$i++) {
    if ($up_id == $sxfourmrow[$i]['id']) {
        $up_name = $sxfourmrow[$i]['bbsname'];
        break;
    } 
} 

$amount = $thisamount;

// ===================================
// Language Pack
// ===================================

require("lang/$language/forums.php");
// ===================================
// Safe Filter
// ===================================
if ($listby != "hit" && $listby != "reply" && $listby != "posttime" && $listby != "author" && $listby != "title") $listby = "";
if ($jinhua != "jinhua" && !empty($jinhua) && !is_numeric($jinhua)) $jinhua = "";
if (isset($page) && !is_numeric($page)) $page = 1;
// ===================================
// Script to read post
// ===================================

if ($aviewpost == "openit") {
    $filetopn = "article.php";
} else {
    $filetopn = "topic.php";
} 
// ===================================
// Login for forum
// ===================================

if ($login == "ok") {
    echo '<meta http-equiv="Refresh" content="' . $refresh_allowed . '; URL=forums.php?forumid=' . $forumid . '">Checking...';
    exit;
} 
// ===================================
// Load Template
// ===================================

$lang_zone = array("popp"=>$popp, "npost"=>$npost, "npollicon"=>$npollicon, "infoan"=>$infoan, "listbyyy"=>$listbyyy, "forum_picie"=>$forum_picie, "forum_pos"=>$forum_pos, "jsinfo"=>$jsinfo, "pageinfo"=>$pageinfo, "forum_pos"=>$forum_pos, "forum_line"=>$forum_line, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages, "online_info_show"=>$online_info_show, "listbyyy"=>$listbyyy, "coninfo"=> $coninfo, "maninfo" => $maninfo);
$template_name = newtemplate("forums", $temfilename, $styleidcode, $lang_zone);

$readed_t = explode("|", $_SESSION['readpost']);

if ($trash == "trash") $listfilename = "ttrash";


// ===================================
// Process data
// ===================================

if ((empty($forumid) || empty($forum_type) || $forum_type == "category") && $trash != "trash") {
	if ($ajax_online == 1) ajax_browse_error($error[2]);
	error_page($error[0], $error[1], $error[0], $error[2]);
} 
if (empty($page)) $page = 1;

check_forum_permission(0,0,"",1);

if ($trash == "trash" && ($view_recybin != "1" || $userpoint < $recy_allow_ww)) {
	if ($ajax_online == 1) ajax_browse_error($gl[438]);
	error_page($error[3],
        "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[192], $gl[438], $gl[192]);
} 
// ===================================
// Password required
// ===================================

if ($forum_pwd <> "" && $forum_pwd <> "d41d8cd98f00b204e9800998ecf8427e" && $job <> "login" && $_COOKIE['b' . $forumid . 'mb'] <> $forum_pwd) {
	if ($ajax_online == 1) ajax_browse_error($error[8]);
    @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    @header("Cache-Control: no-cache, must-revalidate");
    @header("Pragma: no-cache");

    error_page($forum_name, $error[7], $error[8], "<form action='forums.php?forumid=$forumid&amp;job=login' method='post'>&nbsp;{$error[9]}
<input type='password' name='loginforumpwd' size='10' />
<input type='submit' value=\"GO\" /></form>
");

} 

if ($job == "login") {
   	eval(load_hook('int_forums_login'));
	if ($ajax_online == 1) ajax_browse_error($error[0]);
    $loginforumpwd = md5($loginforumpwd);

    if ($loginforumpwd == $forum_pwd) {
        $_SESSION["b" . $forumid . "mb"] = $loginforumpwd;
        setcookie("b" . $forumid . "mb", $loginforumpwd, 0, $cookie_p, $cookie_d);
        jump_page('forums.php?login=ok&amp;forumid=' . $forumid, $tip[0], "<strong>$tip[0]</strong><br /><br />$tip[1] <a href=forums.php?login=ok&amp;forumid=$forumid>$tip[2]</a>", 3);
    } else {
        @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        @header("Cache-Control: no-cache, must-revalidate");
        @header("Pragma: no-cache");
        error_page($forum_name, $error[0], $error[0], "$tip[3]<form action='forums.php?forumid=$forumid&amp;job=login' method='post'>&nbsp;$tip[4] 
<input type='password' name='loginforumpwd' size='10' />
<input type='submit' value=\"$error[10]\" />
");

    } 
    exit;
} 
$load_ajax_script = 0;
if ($view_forum_online && $allow_ajax_browse && $ajax_online) {
	$load_ajax_script = 1;
    whosonline("|f|$forumid|", array('fid' => $forumid));
	require($template_name);
    exit;
}


// ===================================
// END / Title Started
// ===================================
$oldbbs_title = $bbs_title;
$bbs_title = strip_tags($forum_name) . " &lt; " . strip_tags($bbs_title);
require("header.php");
$bbs_title = $oldbbs_title;

// ===================================
// Nav bar
// ===================================

$prefix_file = ($bmfopt['rewrite']) ? "forums_" : "forums.php?forumid=";
$_navList = array();
if ($listfilename == "ttrash" && $jinhua == "jinhua") {
	$fix_file = ($bmfopt['rewrite']) ? "_1_{$jinhua}__" : "&amp;jinhua=$jinhua";
} else {
	$fix_file = '';
}
if (!empty($up_name)) {
	$_navList[] = array('link' => "{$prefix_file}$up_id{$fix_file}", 'up_name' => $up_name);
}
$_navList[] = array('link' => "{$prefix_file}$forumid{$fix_file}", 'name' => $forum_name);

if ($listfilename == "ttrash") {
   	$_navList[] = array('name' => $tip[9]);
	$_navDes = $tip[8];
} elseif ($jinhua == "jinhua") {
   	$_navList[] = array('name' => $tip[5]);
	$_navDes = $tip[6];
} else {
	$_navDes = $tip[7];
} 
// ===================================
// Call Online Function
// ===================================

if ($view_forum_online) whosonline("|f|$forumid|", array('fid' => $forumid));

// ===================================
// Forum Rules
// ===================================

@include("datafile/rules/" . basename($forumid) . ".php");

// ===================================
// Sub forum display and call the function
// ===================================
$forum_admin_1 = "";

if ($fcaterows) {
	$current_width	=	floor(100/$fcaterows);
}

for($i = 0;$i < $forumscount;$i++) {
    $fourmrow = $sxfourmrow[$i];
    if ($fourmrow['id'] == $forumid || $fourmrow['id'] == $forum_cid || $fourmrow['id'] == $forum_upid) {
        $forum_admin_1 .= $fourmrow['adminlist'];
    } 
    if ($fourmrow['blad'] == $forumid) {
		if ($bmfopt['hidebyug'] && !check_forum_permission(0, 1, $fourmrow)) {
			continue;
		}
        if ($fourmrow['type'] == 'subforum' || $fourmrow['type'] == 'subjump' || $fourmrow['type'] == 'subselection' || $fourmrow['type'] == 'subformer' || $fourmrow['type'] == 'sublocked' || $fourmrow['type'] == 'subclosed') {
            if (!$oplined) {
                if ($fcaterows) {
                    $cate_rows_type = 1;
                }
                $oplined = 1;
            } 
            if ($fcaterows) forum_line_row($fourmrow['type'], $fourmrow, $nresult);
            else forum_line($fourmrow['type'], $fourmrow);
        } 
        if (check_permission($username, $fourmrow['type']) && ($fourmrow['type'] == 'subhidden' ||$fourmrow['type'] == 'subforumhid')) {
            if (!$oplined) {
                if ($fcaterows) {
                    $cate_rows_type = 1;
                }
                $oplined = 1;
            } 
            if ($fcaterows) forum_line_row($fourmrow['type'], $fourmrow, $nresult);
            else forum_line($fourmrow['type'], $fourmrow);
        } 
    } 
} 

// ===================================
// Admin
// ===================================

$forum_admin = explode("|", $forum_admin_1);

// ===================================
// Anouncement
// ===================================

if ($bbs_news && $page == 1) {
    if (file_exists("datafile/announcement{$forumid}.php")) {
        $announcelist = file("datafile/announcement{$forumid}.php");
    } 
   	eval(load_hook('int_forums_bbs_news'));
	if(is_array($announcelist)){
	$count = count($announcelist);
		for ($i = 1;$i < $count;$i++) {
			if ($i >= $set_forinfo) break;
			$ic = $i + 1;
			list($aauthor, $atitle, $atime, $acontent, $amember) = explode("|", $announcelist[$i]);
			$almdtime = get_date($atime) . ' ' . get_time($atime);
			$salmdtime = get_date($atime);
			$size_gg = utf8_strlen($acontent);
			$ag = $timestamp - $atime;
			$announceurl = 'announcesys.php?forumid=' . $forumid . '&amp;job=read&amp;msg=' . $i;
			$bbs_news_loop[] = array("announceurl" => $announceurl, "topic_name" => $atitle, "title" => $atitle, "almdtime" => $almdtime, "topic_author" => $aauthor, "salmdtime" => $salmdtime);
			$hereis_top = 1;
		}
	}
}

// ===================================
// Forum Admin list
// ===================================

if (isset($forum_admin)) {
    $count = count($forum_admin);
    for ($i = 0; $i < $count; $i++) {
        if ($forum_admin[$i] != "" && !@in_array($forum_admin[$i], $loaded_admin)) {
        	$loaded_admin[]=$forum_admin[$i];
            $echoinfoa .= "<option value=\"" . urlencode($forum_admin[$i]) . "\">$forum_admin[$i]</option>";
        }
    } 
} else $echoinfoa .= "<option value=\"#\">$infoan[1]</option>";

$maxpageno = 1;

// ===================================
// Process Data
// ===================================

$orderby = "ORDER BY `toptype` DESC,`changetime` DESC";
$jinhuasql = $forumsql = $forum_cid_sql = $chooseby = $hasacount = "";
if ($trash == "trash") {
    $trashq = "AND ordertrash>0";
    $hasacount = $trashcount;
} else {
    $trashq = "AND ttrash=0";
    $jinhuasql = "islock > 1 AND";
    $hasacount = $digestcount;
} 

// ===================================
// List by ...
// ===================================

if ($listby) {
    if ($listby == "hit") $orderby = "ORDER BY `hits` DESC";
    if ($listby == "reply") $orderby = "ORDER BY `replys` DESC";
    if ($listby == "posttime") $orderby = "ORDER BY `time` DESC";
    if ($listby == "author") $orderby = "ORDER BY `author` DESC";
    if ($listby == "title") $orderby = "ORDER BY `title` DESC";
} 
if ($jinhua != "jinhua" && !empty($jinhua)) {
    $maxchange = $timestamp-86400 * $jinhua;
    $chooseby = " AND changetime>$maxchange";
    $jinhuasql = $hasacount = "";
} 

// ===================================
// Mode Choose
// ===================================

if ($jinhua != "" || $trash == "trash") {
    $thislimit = $perpage * $page;
    $lastlimit = $perpage * ($page-1);
    
    // ===================================
    // SQL Query
    // ===================================
   	if ($trash == "trash" && !$forumid && !$cateid) {
   		$forumsql = "forumid!='-1'";
   	} elseif($trash == "trash" && $cateid){
		for($n = 0;$n < $forumscount;$n++) {
		    if ($cateid == $sxfourmrow[$n]['forum_cid']) {
		        $forum_cid_sql .= $forum_cid_sql ? ",".$sxfourmrow[$n]['id'] : $sxfourmrow[$n]['id'];
		    } 
		} 
   		$forumsql = "forumid in($forum_cid_sql)";
   	} else $forumsql = "forumid='$forumid'";
   	
    if (!$hasacount) {
    	if (($trash == "trash" && !$forumid) || is_numeric($jinhua)) {
            $result = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(tid) FROM {$database_up}threads WHERE $jinhuasql $forumsql $chooseby $trashq"));
            $count = $result['COUNT(tid)'];
    	} else $count = $ttopicnum;
    } else {
        $count = $hasacount;
    }
    if ($count > 0) {
        $query = "SELECT * FROM {$database_up}threads WHERE $jinhuasql $forumsql $trashq $chooseby $orderby LIMIT $lastlimit,$thislimit";
        $result = bmbdb_query($query);
    } else $perpage = 0;
    
   	eval(load_hook('int_forums_makelist_1'));

    
    // ===================================
    // Pre-Process
    // ===================================

    for ($i = 0; $i < $perpage; $i++) {
        $row = bmbdb_fetch_array($result);
        if ($row['tid'] != "") $showto[] = $row;
    } 


    // ===================================
    // Display
    // ===================================

    if ($showto) {
        if ($count % $perpage == 0) $maxpageno = max(1, $count / $perpage);
        else $maxpageno = max(1, floor($count / $perpage) + 1);
        if ($page > $maxpageno) $page = $maxpageno;
        $pagemin = min(($page-1) * $perpage , $count-1);
        $pagemax = min($pagemin + $perpage-1, $count-1);
        if(is_array($filenametop)) $coutop = count($filenametop);
        for ($i = 0; $i < $perpage; $i++) {
            if ($showto[$i]['tid'] != "") {
            	$snputed = 1;
                article_line($showto[$i]);
            }
        } 
        if ($page == 1 && $snputed == 1) $outputing = str_replace("{showits}", $coninfo[14], $importtopic);
    } else {
        $maxpageno = 1;
    } 

} else {
	// ===================================
    // Normal Mode;
    // SQL Query
    // ===================================
    
    @include_once("datafile/cache/pin_thread.php");

    $query_pagea = "";
    if (empty($forum_cid)) $forum_cid = "XXXXXXXXXXXX";
    
    $intopthread = $count_pint["$forum_cid"] ? $topthread['ALL'] : substr($topthread['ALL'] ,0 ,-1);
    $intopthread .=  substr($topthread["$forum_cid"] ,0 ,-1);

    $total_pin = $count_pint['ALL'] + $count_pint["$forum_cid"];
    
    $counttopsnum = 0;
   	eval(load_hook('int_forums_makelist_2'));

    if ($page == 1 && $total_pin > 0) {
        $query = "SELECT * FROM {$database_up}threads WHERE tid in ($intopthread) and toptype in (8,9) $trashq $chooseby $orderby";
        $xresult = bmbdb_query($query);
        $topsnum = bmbdb_num_rows($xresult);
    } elseif ($page != 1 && $total_pin > 0) {
        $query = "SELECT COUNT(tid) FROM {$database_up}threads WHERE tid in ($intopthread) and forumid=$forumid and toptype in (8,9) $trashq $chooseby $orderby";
        $xresult = bmbdb_query($query);
        $xresultrow = bmbdb_fetch_array($xresult);
        $topsnum = $xresultrow['COUNT(tid)'];
        $counttopsnum = $topsnum;
    } else {
        $counttopsnum = $topsnum;
    }
    
    
    $thislimit = $perpage * $page;
    $lastlimit = $perpage * ($page-1);
    
    if ($ttopicnum < $perpage) $perpage = $ttopicnum;

    $query = "SELECT * FROM {$database_up}threads WHERE forumid='$forumid' and toptype in (0,1) $trashq $chooseby $orderby LIMIT $lastlimit,$perpage";
    $result = bmbdb_query($query);
    $topicsn = bmbdb_num_rows($result) + $topsnum;
    $x = $bxi = $xxi = 0;
    // ===================================
    // pre-process
    // ===================================
   	eval(load_hook('int_forums_makelist_3'));

    for ($x = 0;$x < $topicsn;$x++) {
        if ($x < $topsnum) {
        	$row = bmbdb_fetch_array($xresult);
        	if ($row['forumid'] == $forumid) $counttopsnum++;
        }else $row = bmbdb_fetch_array($result);
        
        if ($row['toptype'] == '9' || $row['toptype'] == 8) {
            $xtopshows[$xxi] = $row;
            $xxi++;
        } elseif ($row['type'] >= 3 && $row['toptype'] != '9' && $row['toptype'] != 8) {
            $btopshows[$bxi] = $row;
            $bxi++;
        } else {
            $showto[$x] = $row;
        } 
    } 
    
    $count = max(0, $ttopicnum - $counttopsnum);
    
    // ===================================
    // Stat posts
    // ===================================

    if ($count == 0) {
        $pagemin = $pagemax = $page = $maxpageno = 1;
    }else{
        if ($count % $perpage == 0) $maxpageno = max(1, $count / $perpage);
        else $maxpageno = max(1, floor($count / $perpage) + 1);
        if ($page == "last" || $page > $maxpageno) $page = $maxpageno;
        $pagemin = max(1, min(($page-1) * $perpage , $count-1));
        $pagemax = max(1, min($pagemin + $perpage-1, $count-1) + 1);
    }
    
    
    // ===================================
    // Display and check permission
    // ===================================

	if ($page == 1) {
		for ($xti = 0; $xti < $xxi; $xti++) {
			article_line($xtopshows[$xti]);
		} 
	} 
	for ($bti = 0; $bti < $bxi; $bti++) {
		article_line($btopshows[$bti]);
	} 
	for ($i = 0; $i < $topicsn; $i++) {
		if ($showto[$i]['tid'] != "") {
			article_line($showto[$i]);
		}
	} 
   	eval(load_hook('int_forums_makelist_4'));
    // NEW CODE BY 15:14 2005-8-23
} 

if ($login_status == 1 && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1")) {
	$admin_allow = 1;
} else $admin_allow = 0;


$bmf_threads = array_merge((array)$topinfooutput, (array)$quinfooutput, (array)$baninfooutput, (array)$allinfooutput);


// ===================================
// Forum jumping
// ===================================

$count = $forumscount;
for ($i = 0; $i <= $count; $i++) {
   	eval(load_hook('int_forums_jumping'));
	if ($bmfopt['hidebyug'] && !check_forum_permission(0, 1, $sxfourmrow[$i])) {
		continue;
	}
    if ($sxfourmrow[$i]['type'] == "category") $listsinfo .= "<option value='index.php?cateid={$sxfourmrow[$i]['id']}'>{$sxfourmrow[$i]['bbsname']}</option>";
    if ($sxfourmrow[$i]['type'] == "forum" || $sxfourmrow[$i]['type'] == "former" || $sxfourmrow[$i]['type'] == "jump" || $sxfourmrow[$i]['type'] == "selection" || $sxfourmrow[$i]['type'] == "locked" || $sxfourmrow[$i]['type'] == "closed") $listsinfo .= "<option value='forums.php?forumid={$sxfourmrow[$i]['id']}'>&nbsp;|- {$sxfourmrow[$i]['bbsname']}</option>";
    if (check_permission($username, $sxfourmrow[$i]['type']) && ($sxfourmrow[$i]['type'] == 'hidden' || $sxfourmrow[$i]['type'] == 'forumhid')) $listsinfo .= "<option value='forums.php?forumid={$sxfourmrow[$i]['id']}'>&nbsp;|- {$sxfourmrow[$i]['bbsname']}</option>";
    if ($sxfourmrow[$i]['type'] == "subforum" || $sxfourmrow[$i]['type'] == "subformer" || $sxfourmrow[$i]['type'] == "subjump"  || $sxfourmrow[$i]['type'] == "subselection" || $sxfourmrow[$i]['type'] == "sublocked" || $sxfourmrow[$i]['type'] == "subclosed") $listsinfo .= "<option value='forums.php?forumid={$sxfourmrow[$i]['id']}'>&nbsp;|-|- {$sxfourmrow[$i]['bbsname']}</option>";
    if (check_permission($username, $sxfourmrow[$i]['type']) && ($sxfourmrow[$i]['type'] == 'subhidden' || $sxfourmrow[$i]['type'] == 'subforumhid')) $listsinfo .= "<option value='forums.php?forumid={$sxfourmrow[$i]['id']}'>&nbsp;|-|- {$sxfourmrow[$i]['bbsname']}</option>";
} 

// ===================================
// Pages
// ===================================
eval(load_hook('int_forums_beforepagemaker'));

$pagerLink = ($bmfopt['rewrite'] ? "forums_{$forumid}_{page}_{$jinhua}_{$listby}_{$trash}" : "forums.php?forumid=$forumid&amp;jinhua=$jinhua&amp;page={page}&amp;listby=$listby&amp;trash=$trash");

eval(load_hook('int_forums_afterpagemaker'));


// ===================================
// Fast New thread
// ===================================
$count_rows = $allow_fast_post = 0;

if ($fnew_select == 1) { // 开关 
	if (!(($canpost != 1 || $userpoint < $post_allow_ww)) && !($login_status == 1 && !check_permission($username, $forum_type))) {
		$allow_fast_post = 1;
    	fast_new();
    }
} //开关

eval(load_hook('int_forums_beforetpl'));

require($template_name);
require("footer.php");
exit;
