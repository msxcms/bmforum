<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
include_once("datafile/config.php");
include_once("include/template.php");
include_once("getskin.php");
$cip = $ip;
include_once("lang/$language/index.php");

$lang_zone = array("programname"=>$programname, "fenleiq"=>$fenleiq, "onlyread"=>$onlyread, "newpost"=>$newpost, "nonewpost"=>$nonewpost, "todaybd"=>$todaybd, "poteo"=>$poteo, "forum_line"=>$forum_line, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages, "whosonlinelang"=>$whosonlinelang, "popp"=> $popp, "indexinfo" => $indexinfo);
$template_name = newtemplate("index", $temfilename, $styleidcode, $lang_zone);
    
$acount = count($usergroupdata);
$onlinests = array();
$shareforum_js =  "";
for ($i = 0; $i < $ugsocount; $i++) {
    $getit = $ugshoworder[$i];
    $onlinetushi = explode("|", $usergroupdata[$getit]);
    if ($unshowit[$getit] != "1") {
        $onlinests[] = array('icon' => $onlinetushi[1], 'name' =>$onlinetushi[0]);
    } 
} 

/* AJAX Online List */
if ($allow_ajax_browse && $ajax_online) {
    indexwhosonline();
    require($template_name);
	exit;
}

$thelastest = bmbdb_query_fetch("SELECT * FROM {$database_up}lastest WHERE pageid='index'");
require("header.php");

$announcement = array();

if ($bbs_news) {
    if (file_exists('datafile/announcement.php')) {
        $announcementmtime = filemtime('datafile/announcement.php');
        $announcementmtime = get_date($announcementmtime) . " " . get_time($announcementmtime);
        include('datafile/announcement.php');
    } 
    if ($announcement) {
        $announcementtext = str_replace("\n", " ", $announcement);
        $announcement = array();
        $announcement[0]['text'] = $announcementtex;
        $announcement[0]['time'] = $announcementmtime;
        $announcement[0]['link'] = false;
    }
    
    if (file_exists("datafile/announcesys.php")) {
        $announcelist = file("datafile/announcesys.php");
    } 

    $count = count($announcelist);
    if ($count == 0 && !$announcementtext) {
        $bbs_news = 0;
    }
    for ($i = 1;$i < $count;$i++) {
        $ic = $i + 1;
        list($aauthor, $atitle, $atime, $acontent, $amember) = explode("|", $announcelist[$i]);
        $almdtime = get_date($atime) . ' ' . get_time($atime);
        $size_gg = utf8_strlen($acontent);
        $ag = $timestamp - $atime;
        $announcement[$i]['text'] = $atitle;
        $announcement[$i]['time'] = $almdtime;
        $announcement[$i]['link'] = "announcesys.php?job=read&amp;msg=$i";
    } 
	eval(load_hook('int_index_announcement'));
} 

if ($login_status == 1) {
	$level = getUserLevel($postamount, $userbym, $username, $logonutnum);
} else {
    if ($log_va && $fastlogin) {
        $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
        $_SESSION[checkauthnum] = $authnum;
    }
} 
eval(load_hook('int_index_prepare_tips_box'));

$des = $stextnow = $simgnow = "";

if ($see_a_tags && $bmfopt["hot_tags"])
{
	$hot_tags_list = array();
    $result = bmbdb_query("SELECT * FROM {$database_up}tags ORDER BY 'threads' DESC LIMIT 0,30"); // max tags number: 30

    while (false !== ($row = bmbdb_fetch_array($result))) {
        $size_t = rand(12,14);
        $hot_tags_list[] = array('threads' => $row['threads'], 'size' => $size_t, 'urlname' => urlencode($row['tagname']), 'name' => $row['tagname']);
    } 
}

$count = count($forumlist);
for ($i = 0; $i < $count; $i++) {
    $detail = explode('|', $forumlist[$i]);
} 

$line = $sxfourmrow;
$xxxcount = $forumscount;
for($i = 0;$i < $xxxcount;$i++) {
	if ($bmfopt['hidebyug'] && !check_forum_permission(0, 1, $line[$i])) {
		continue;
	}
    if ($line[$i]['type'] == 'forum' || $line[$i]['type'] == 'jump' || $line[$i]['type'] == 'selection' || $line[$i]['type'] == 'former' || $line[$i]['type'] == 'locked' || $line[$i]['type'] == 'closed') {
    	if ($cateid) {
    		if ($this_cat == 1) {
    			forum_line($line[$i]['type'], $line[$i], $line);
    		}
    		continue;
    	} else {
	        if ($tnewrow) forum_line_row($line[$i]['type'], $line[$i], $line);
	        else forum_line($line[$i]['type'], $line[$i], $line);
	    }
    } elseif ($line[$i]['type'] == 'category') {
    	if ($cateid) {
    		if ($this_cat == 1) break;
    		if ($line[$i]['id'] == $cateid) {
    			category_line($line[$i]['bbsname'], $line[$i]['id'], $line[$i], $line[$i]['caterows']);
    			$this_cat = 1;
    			$bmforumlist[0]['TROW']="";
    		}
    	} else category_line($line[$i]['bbsname'], $line[$i]['id'], $line[$i], $line[$i]['caterows']);
    } 
    if (check_permission($username, $line[$i]['type']) && ($line[$i]['type'] == 'hidden' || $line[$i]['type'] == 'forumhid')) {
    	if ($cateid) {
    		if ($this_cat == 1) {
    			forum_line($line[$i]['type'], $line[$i], $line);
    		}
    		continue;
    	} else {
	        if ($tnewrow) forum_line_row($line[$i]['type'], $line[$i], $line);
	        else forum_line($line[$i]['type'], $line[$i], $line);
	    }
    } 
} 


// --Allied Forums------
$simgnow = $stextnow = array();
if ($cachejs != 1) {
    $query = "SELECT * FROM {$database_up}shareforum ORDER BY `showorder` ASC";
    $result = bmbdb_query($query);
    $count = bmbdb_num_rows($result);
    while (false !== ($line = bmbdb_fetch_array($result))) {
        $alliedforuminfo = str_replace("\n", "", $line['des']);
        $alliedforumtype = str_replace("\n", "", $line['type']);
        if ($alliedforumtype == "pic") {
			$simgnow[] = array('url' => $line['url'], 'name' => $line['name'], 'info' => $alliedforuminfo, 'img' => $line['gif']);
        } else {
			$stextnow[] = array('url' => $line['url'], 'name' => $line['name'], 'info' => $alliedforuminfo);
        } 
    } 
} 

$sharenum = $sharenum + $count;

if ($todayb_show) {
	$tdbdshow = array();
	$counowtd = 0;
    $thisyear = getdate($timestamp);
    $thisday = $thisyear["mday"];
    $thismonth = $thisyear["mon"];
    $thisyear = $thisyear["year"];
    $bdfile = "datafile/birthday/{$thismonth}_$thisday";
    $bddata = @file($bdfile);
    $bdccount = count($bddata);
    for($bdc = 0;$bdc < $bdccount;$bdc++) {
		$detail = explode("|", $bddata[$bdc]);
    	if ($detail[0]) {
			if ($detail[2] != 0) {
				$cluage = $thisyear - $detail[2];
			} else {
				$cluage = '';
			}
			$tdbdshow[] = array('urlname' => urlencode($detail[0]), 'name' => $detail[0], 'cluage' => $cluage);
			$counowtd++;
		}
    } 
} 
indexwhosonline();
eval(load_hook('int_index_before_output'));

require($template_name);
require("footer.php");
exit;
// 分类显示模块
function category_line($name, $categoryid, $line, $trow)
{
    global $echoedcate, $listmmlist, $current_width, $current_rows, $current_cid, $bmforumlist, $outpused, $tnewrow, $leiji, $catea, $cateb, $preoutput_row, $catec, $cated, $replaces_a, $replaces_b, $replaces_c, $replaces_d, $outputed, $temfilename, $fenleiq, $i, $otherimages, $forum_cidi;
    
    $current_rows = $current_width = $echoedcate = 0;

    $tnewrow = $trow;
	$admin_list = array();
    if (!empty($line['adminlist'])) {
        $forum_admin = explode("|", $line['adminlist']);
        $count = count($forum_admin)-1;
        for ($j = 0; $j < $count; $j++) {
            $adminname = $forum_admin[$j];
            $hasadmin = 1;
			$admin_list[] = array('name' => $adminname, 'urlname' => urlencode($adminname));
        } 
    } 
    $current_cid = $categoryid;
    $forums_stat[$current_cid] = 0;
    
    
    if ($line['caterows']) {
	    $current_rows = $line['caterows'];
	    $current_width= floor(100/$line['caterows']);
	}
    
	eval(load_hook('int_index_category_line'));
   	$bmforumlist[] = array("admin_list"=>$admin_list,"current_rows"=>$current_rows,"TROW" => $trow, "TYPE"=> "category", "ID" => $categoryid,"NAME"=>$name, "CLIST"=>$clist);

} 
// 版块横列
function forum_line_row($forum_type, $line, $allline)
{
    global $detail, $current_rows, $current_width, $forums_stat, $current_cid, $bmforumlist, $bmfopt, $echoedcate, $outpused, $forum_line, $bbsdetime, $popp, $replaces_a, $replaces_c, $th_arr, $replaces_b, $replaces_d, $leiji, $preoutput_row, $xxxcount, $forumlist, $preoutput, $po, $temfilename, $all_count, $all_lastmo, $time_2, $time_1, $script_pos, $forum_admin, $login_status, $idpath, $newpost, $onlyread, $nonewpost, $posticon, $pollicon, $ucomicon, $otherimages, $timestamp, $minoffset;

    $ztnum = 0;
    $ztnum1 = 0;
    
    $ztnum = $line['topicnum'];
    $ztnum1 = $line['replysnum'];
    $ztnum2 = $line['todayp'];

    $lasttodaytime = gmdate("zY", $line['todaypt'] + $bbsdetime * 60 * 60);
    $lasttodaytime_a = gmdate("zY", $timestamp + $bbsdetime * 60 * 60);

    $ztnum = $line['topicnum'];
    $ztnum1 = $line['replysnum'];
    $ztnum2 = $line['todayp'];
    $lasttodaytime = gmdate("zY", $line['todaypt'] + $bbsdetime * 60 * 60);
    $lasttodaytime_a = gmdate("zY", $timestamp + $bbsdetime * 60 * 60);
    
    if ($lasttodaytime != $lasttodaytime_a) {
    	$ztnum2 = 0;
    }

    for($ax = 0;$ax < $xxxcount;$ax++) {
        if ($allline[$ax]['blad'] == $line['id'] && check_permission($username, $allline[$ax]['type']) ) {
            $modifytime = $allline[$ax]['flposttime'];
            $allline[$ax]['flposttime'] = getfulldate($modifytime);
            $timea[] = $timetoshow;
            $timesa[] = $modifytime;
            
            $lasttodaytime = gmdate("zY", $allline[$ax]['todaypt'] + $bbsdetime * 60 * 60);
            
            if ($lasttodaytime == $lasttodaytime_a) {
                $ztnum2 += $allline[$ax]['todayp'];
            }

            $ztnum += $allline[$ax]['topicnum'];
            $ztnum1 += $allline[$ax]['replysnum'];
        } 
    } 

    $modifytime = $line['flposttime'];
    $line['flposttime'] = getfulldate($modifytime);


    $timea[] = $timetoshow;
    $timesa[] = $modifytime;

    $countarray = count($timesa);
    for($cai = 0;$cai < $countarray;$cai++) {
        if ($timesa[$cai] > $times) {
            $title = $titlea[$cai];
            $user = $usera[$cai];
            $time = $timea[$cai];
            $times = $timesa[$cai];
        } 
    } 
    if ($forum_type == "forum" || $forum_type == "former" || $forum_type == "forumhid") {
        $last_modify_list_time_check = get_date($times);
        $last_modify_list_time_check = explode("-", $last_modify_list_time_check);
        $nowdatetime = get_date($timestamp);
        $nowdatetime_check = explode("-", $nowdatetime); 
        // ----- is there any new post? ------
        // echo $lastvisit;echo $lastvisit-$modifytime;
        if ($nowdatetime_check[1] == $last_modify_list_time_check[1] && $nowdatetime_check[2] == $last_modify_list_time_check[2] && $nowdatetime_check[0] == $last_modify_list_time_check[0]) $forum_icon = $newpost;
        else $forum_icon = $nonewpost;
    } else {
        $forum_icon = $onlyread;
    } 

    if ($lasttodaytime != $lasttodaytime_a) {
    	$ztnum2 = 0;
    }
    
    for($ax = 0;$ax < $xxxcount;$ax++) {
        if ($allline[$ax]['blad'] == $line['id']) {
            $ztnum += $allline[$ax]['topicnum'];
            $ztnum1 += $allline[$ax]['replysnum'];
            $lasttodaytime = gmdate("zY", $allline[$ax]['todaypt'] + $bbsdetime * 60 * 60);
            
            if ($lasttodaytime == $lasttodaytime_a) {
                $ztnum2 += $allline[$ax]['todayp'];
            }
        } 
    } 

    $forumlabel = ($bmfopt['rewrite'] ? "forums_{$line['id']}" :"forums.php?forumid={$line['id']}");

    $forums_stat[$current_cid]++;

	eval(load_hook('int_index_forum_line_row'));

   	$bmforumlist[] = array('ztnum' => $ztnum, 'ztnum1' => $ztnum1, 'ztnum2' => $ztnum2, "img_forum_icon"=>$forum_icon,"current_width"=>$current_width,"current_rows"=>$current_rows,"ROWF"=>1,"admin_list_row" => $popp[14]." ".$admin_list_row, "ztnum" => $ztnum, "forum_icon" => $line['forum_icon'], "forumdes" => $line['cdes'], "forumlabel" => $forumlabel, "TYPE"=> "forum", "ID" => $line['id'], "bbsname" => $line['bbsname'], "cdes" => $line['cdes']);


} 
// 版块显示模块
function forum_line($forum_type, $line, $allline)
{
    global $detail, $current_cid, $forums_stat, $bmforumlist, $bmfopt, $enter_tb, $bbsdetime, $echoedcate, $outpused, $listmmlist, $fenleiq, $forum_line, $xxxcount, $forumlist, $preoutput, $po, $temfilename, $all_count, $all_lastmo, $time_2, $time_1, $script_pos, $forum_admin, $login_status, $idpath, $newpost, $onlyread, $nonewpost, $posticon, $pollicon, $ucomicon, $otherimages, $timestamp, $minoffset;

    $aviewpost = $line['naviewpost'];
    if ($aviewpost == "openit") {
        $filetopn = "article.php";
    } else {
        $filetopn = "topic.php";
    } 

    if ($echoedcate == 0) {
        echo $outpused;
        $echoedcate = 1;
    }

    $ztnum = $line['topicnum'];
    $ztnum1 = $line['replysnum'];
    $ztnum2 = $line['todayp'];
    $lasttodaytime = gmdate("zY", $line['todaypt'] + $bbsdetime * 60 * 60);
    $lasttodaytime_a = gmdate("zY", $timestamp + $bbsdetime * 60 * 60);
    
    if ($lasttodaytime != $lasttodaytime_a) {
    	$ztnum2 = 0;
    }


    for($ax = 0;$ax < $xxxcount;$ax++) {
        if ($allline[$ax]['blad'] == $line['id'] && check_permission($username, $allline[$ax]['type']) ) {
        	$orgtitle = $allline[$ax]['fltitle'] = stripslashes($allline[$ax]['fltitle']);
            if (utf8_strlen($allline[$ax]['fltitle']) >= 12) $allline[$ax]['fltitle'] = substrfor($allline[$ax]['fltitle'], 0, 12) . "..";
            $modifytime = $allline[$ax]['flposttime'];
            $allline[$ax]['flposttime'] = getfulldate($modifytime);
            $allline[$ax]['fltitle'] = htmlspecialchars($allline[$ax]['fltitle']);
            if ($time_2) {
                $timetmp_a = $timestamp - $modifytime;
                $timetoshow = get_add_date($timetmp_a);
                if ($timetoshow == "getfulldate") {
                    $timetoshow = $allline[$ax]['flposttime'];
                } 
            } else {
                $timetoshow = $allline[$ax]['flposttime'];
            } 
            $titlea[] = $allline[$ax]['fltitle'];
            $titlelinka[] = (($bmfopt['rewrite'] && $filetopn == "topic.php") ? "topic_{$allline[$ax]['flfname']}_last#postend" : "$filetopn?getlastpost=yes&amp;forumid={$allline[$ax]['id']}&amp;ct={$allline[$ax]['flfname']}&amp;page=last#postend");
            $orgtitlea[] = $orgtitle;
            $flpostera[] = $allline[$ax]['flposter'];
            //$usera[] = "<a href=\"profile.php?job=show&amp;target=" . urlencode($allline[$ax]['flposter']) . "\">{$allline[$ax]['flposter']}</a> <a href='".(($bmfopt['rewrite'] && $filetopn == "topic.php") ? "topic_{$allline[$ax]['flfname']}" : "$filetopn?getlastpost=yes&amp;ct={$allline[$ax]['flfname']}&amp;forumid={$allline[$ax]['id']}")."' title='$forum_line[3]'><img border='0' src='$otherimages/system/lastpost.gif' alt='' /></a>";
            $timea[] = $timetoshow;
            $timesa[] = $modifytime;
            
            $lasttodaytime = gmdate("zY", $allline[$ax]['todaypt'] + $bbsdetime * 60 * 60);
            
            if ($lasttodaytime == $lasttodaytime_a) {
                $ztnum2 += $allline[$ax]['todayp'];
            }
            
            if ($bmfopt['showsubforum'] && !($bmfopt['hidebyug'] && !check_forum_permission(0, 1, $allline[$ax]))) {
	            $showsubforum[] = "<a href=\"".($bmfopt['rewrite'] ? "forums_{$allline[$ax]['id']}" : "forums.php?forumid={$allline[$ax]['id']}")."\">{$allline[$ax]['bbsname']}</a>";
	        }
            
            $ztnum += $allline[$ax]['topicnum'];
            $ztnum1 += $allline[$ax]['replysnum'];
        } 
    } 

    $orgtitle = $line['fltitle'] = stripslashes($line['fltitle']);
    if (utf8_strlen($line['fltitle']) >= 12) $line['fltitle'] = substrfor($line['fltitle'], 0, 12) . "..";
    $modifytime = $line['flposttime'];
    $line['flposttime'] = getfulldate($modifytime);
    $line['fltitle'] = htmlspecialchars($line['fltitle']);
    if ($time_2) {
        $timetmp_a = $timestamp - $modifytime;
        $timetoshow = get_add_date($timetmp_a);
        if ($timetoshow == "getfulldate") {
            $timetoshow = $line['flposttime'];
        } 
    } else {
        $timetoshow = $line['flposttime'];
    } 

    $titlea[] = $line['fltitle'];
    $titlelinka[] = (($bmfopt['rewrite'] && $filetopn == "topic.php") ? "topic_{$line['flfname']}_last#postend" : "$filetopn?getlastpost=yes&amp;ct={$line['flfname']}&amp;forumid={$line['id']}&amp;page=last#postend");
    $orgtitlea[] = $orgtitle;
    $flpostera[] = $line['flposter'];
    //$usera[] = "<a href=\"profile.php?job=show&amp;target=" . urlencode($line['flposter']) . "\">{$line['flposter']}</a> <a href='".(($bmfopt['rewrite'] && $filetopn == "topic.php") ? "topic_{$line['flfname']}" : "$filetopn?getlastpost=yes&amp;ct={$line['flfname']}&amp;forumid={$line['id']}")."' title='$forum_line[3]'><img border='0' src='$otherimages/system/lastpost.gif' alt='' /></a>";
    $timea[] = $timetoshow;
    $timesa[] = $modifytime;

    $countarray = count($timesa);
    for($cai = 0;$cai < $countarray;$cai++) {
        if ($timesa[$cai] > $times) {
            $title = $titlea[$cai];
            $titlelink = $titlelinka[$cai];
            $orgtitle = $orgtitlea[$cai];
            $flposter = $flpostera[$cai];
            //$user = $usera[$cai];
            $time = $timea[$cai];
            $times = $timesa[$cai];
        } 
    } 
    if ($forum_type == "forum" || $forum_type == "former" || $forum_type == "forumhid") {
        $last_modify_list_time_check = get_date($times);
        $last_modify_list_time_check = explode("-", $last_modify_list_time_check);
        $nowdatetime = get_date($timestamp);
        $nowdatetime_check = explode("-", $nowdatetime); 
        // ----- is there any new post? ------
        // echo $lastvisit;echo $lastvisit-$modifytime;
        if ($nowdatetime_check[1] == $last_modify_list_time_check[1] && $nowdatetime_check[2] == $last_modify_list_time_check[2] && $nowdatetime_check[0] == $last_modify_list_time_check[0]) $forum_icon = $newpost;
        else $forum_icon = $nonewpost;
    } else {
        $forum_icon = $onlyread;
    } 
    $forumlabel = ($bmfopt['rewrite'] ? "forums_{$line['id']}" : "forums.php?forumid={$line['id']}");

    if ($line['type'] == 'locked' && !$enter_tb) {
        $title = "";
    } 

    if ($bmfopt['showsubforum'] && $showsubforum) {
    	$statforum = count($showsubforum) - 1;
    	$tsubforum = "";
        for ($i = 0; $i <= $statforum; $i++) {
            $tsubforum .= $showsubforum[$i];
            if ($i != $statforum) $tsubforum .= ", ";
        }
    }
    
    $forums_stat[$current_cid]++;
	eval(load_hook('int_index_forum_line'));
    
    $bmforumlist[] = array('titlelink' => $titlelink, 'orgtitle' => $orgtitle, 'flposter' => $flposter, "teamicon"=> $line['forum_icon'], "filetopn" => $filetopn, "subforums" => $tsubforum, "user" => $user, "admin_list" => $admin_list, "admin_list_row" => $admin_list_row, "time" => $time, "title" => $title, "ztnum2" => $ztnum2, "ztnum1" => $ztnum1, "ztnum" => $ztnum, "forum_des" => $line['cdes'], "forum_icon" => $forum_icon,"forumlabel" => $forumlabel, "TYPE"=> "forum", "ID" => $line['id'],"NAME"=>$line['bbsname'], "CLIST"=>$clist);

} 
// 谁与我同在
function indexwhosonline()
{
	global $online_show_anti;
	require_once("include/forum.inc.php");
	eval(load_hook('int_index_whosonline'));
	whosonline();
}