<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/

// ===================================
// Sub Forum (row)
// ===================================

function forum_line_row($forum_type, $line, $allline)
{
    global $detail, $bmforumlist, $forums_stat, $bmfopt, $forum_line, $popp, $bbsdetime, $replaces_a, $replaces_c, $th_arr, $replaces_b, $replaces_d, $leiji, $preoutput_row, $xxxcount, $forumlist, $preoutput, $po, $temfilename, $all_count, $all_lastmo, $time_2, $time_1, $script_pos, $forum_admin, $login_status, $idpath, $newpost, $onlyread, $nonewpost, $posticon, $pollicon, $ucomicon, $otherimages, $timestamp, $minoffset;

    $ztnum = 0;
    $ztnum1 = 0;

    $ztnum = $line['topicnum'];
    $ztnum1 = $line['replysnum'];
    $ztnum2 = $line['todayp'];

    $lasttodaytime = gmdate("zY", $line['todaypt'] + $bbsdetime * 60 * 60);
    $lasttodaytime_a = gmdate("zY", $timestamp + $bbsdetime * 60 * 60);
    
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

    $forumlabel = ($bmfopt['rewrite'] ? "forums_{$line['id']}" : "forums.php?forumid={$line['id']}");

    if ($forum_type == "subforum" || $forum_type == "subformer" || $forum_type == "subforumhid") {
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

    $forums_stat++;
	eval(load_hook('int_forums_forum_line_row'));
   	$bmforumlist[] = array('ztnum' => $ztnum, 'ztnum1' => $ztnum1, 'ztnum2' => $ztnum2, "ROWF"=>1, "img_forum_icon" => $line['forum_icon'], "forum_icon" => $forum_icon, "forumdes" => $line['cdes'], "forumlabel" => $forumlabel, "NAME"=> $line['bbsname'], "TYPE"=> "forum", "ID" => $line['id']);

} 
// ===================================
// Sub Forum
// ===================================
function forum_line($forum_type, $line)
{
    global $detail, $bmfopt, $bmforumlist, $infoan, $enter_tb, $bbsdetime, $listmmlist, $fastmanage, $preoutput, $temfilename, $t, $po, $time_2, $forum_line, $usertype, $script_pos, $forum_admin, $login_status, $idpath, $newpost, $onlyread, $nonewpost, $posticon, $pollicon, $ucomicon, $otherimages, $timestamp, $minoffset;
    $aviewpost = $line['naviewpost'];
    if ($aviewpost == "openit") {
        $filetopn = "article.php";
    } else {
        $filetopn = "topic.php";
    } 

    $line['fltitle'] = stripslashes($line['fltitle']);
    $lastmodify_title = $line['fltitle'];
    if (utf8_strlen($line['fltitle']) >= 12) $line['fltitle'] = substrfor($line['fltitle'], 0, 12) . "..";
    $modifytime = $line['flposttime'];
    if ($modifytime!="") {
        $line['flposttime'] = getfulldate($modifytime);
    }
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
    
    $title = $line['fltitle'];
    $titlelink = (($bmfopt['rewrite'] && $filetopn == "topic.php") ? "topic_{$line['flfname']}_last#postend" : "$filetopn?getlastpost=yes&amp;ct={$line['flfname']}&amp;forumid={$line['id']}&amp;page=last");
    //$user = "<a href=\"profile.php?job=show&amp;target=" . urlencode($line['flposter']) . "\">{$line['flposter']}</a> <a href='".(($bmfopt['rewrite'] && $filetopn == "topic.php") ? "topic_{$line['flfname']}" : "$filetopn?getlastpost=yes&amp;ct={$line['flfname']}&amp;forumid={$line['id']}")."' title=\"$forum_line[11]\"><img border='0' src='$otherimages/system/lastpost.gif' alt='' /></a>";
    $time = $timetoshow;
    
    
    
    if ($forum_type == "subforum" || $forum_type == "subformer" || $forum_type == "subforumhid") {
        $last_modify_list_time_check = explode(" ", $line['flposttime']);
        $last_modify_list_time_check = explode("-", $last_modify_list_time_check[0]);
        $nowdatetime = get_date($timestamp);
        $nowdatetime_check = explode("-", $nowdatetime); 
        // ----- is there any new post? ------
        // echo $lastvisit;echo $lastvisit-$modifytime;
        if ($nowdatetime_check[1] == $last_modify_list_time_check[1] && $nowdatetime_check[2] == $last_modify_list_time_check[2] && $nowdatetime_check[0] == $last_modify_list_time_check[0]) $forum_icon = $newpost;
        else $forum_icon = $nonewpost;
    } else $forum_icon = $onlyread;
    $forumlabel = ($bmfopt['rewrite'] ? "forums_{$line['id']}" : "forums.php?forumid={$line['id']}");
    
    if ($forum_type == 'sublocked' && !$enter_tb) {
        $title = "";
    } 

    $ztnum = $line['topicnum'];
    $ztnum1 = $line['replysnum'];
    $ztnum2 = $line['todayp'];

    $lasttodaytime = gmdate("zY", $line['todaypt'] + $bbsdetime * 60 * 60);
    $lasttodaytime_a = gmdate("zY", $timestamp + $bbsdetime * 60 * 60);
    
    if ($lasttodaytime != $lasttodaytime_a) {
    	$ztnum2 = 0;
    }
	eval(load_hook('int_forums_forum_line'));

    $bmforumlist[] = array("titlelink"=> $titlelink, "flposter"=> $line['flposter'], "lastmodify_title"=> $lastmodify_title, "teamicon"=> $line['forum_icon'],"filetopn" => $filetopn, "subforums" => $tsubforum, "user" => $user, "admin_list" => $admin_list_2, "admin_list_row" => $admin_list_2_row, "time" => $time, "title" => $title, "ztnum2" => $ztnum2, "ztnum1" => $ztnum1, "ztnum" => $ztnum, "forum_des" => $line['cdes'], "forum_icon" => $forum_icon,"forumlabel" => $forumlabel, "TYPE"=> "forum", "ID" => $line['id'],"NAME"=>$line['bbsname'], "CLIST"=>$clist);

//    $replaces = array("{filetopn}" => $filetopn, "{user}" => $user, "{admin_list_2}" => $admin_list_2, "{admin_list_2_row}" => $admin_list_2_row, "{time}" => $time, "{title}" => $title, "{ztnum2}" => $ztnum2, "{ztnum1}" => $ztnum1, "{ztnum}" => $ztnum, "{detail[1]}" => $line['bbsname'], "{detail[2]}" => $line['cdes'], "{detail[3]}" => $line['id'], "{detail[5]}" => $line['forum_icon'], "{forum_icon}" => $forum_icon, "{forumlabel}" => $forumlabel);
} 
// ===================================
// article list display
// ===================================
function article_line($a_info)
{
    global $atrlistat, $hereis_top, $topinfooutput, $baninfooutput, $bmfopt, $page, $allow_ajax_reply, $hasatopic, $fastmanage, $allinfooutput, $emotrand, $forum_cid, $quinfooutput, $database_up, $atrlistt, $atrlistvt, $forumid, $time_2, $filetopn, $forum_mang_t, $coninfo, $forum_cid, $listfilename, $username, $read_perpage, $timestamp, $login_status, $forum_admin, $admin_name, $idpath, $otherimages, $usertype;
    // list($title,$author,$date,$des,$icon,$filename,$reply,$hit,$last_mod_data,$islock,$topic_type)=explode("|",$a_info);
    $hasatopic = 1; // Check topics
    $filename = $a_info['id'];
    $reply = $a_info['replys'];
    $topic_type = trim($a_info['type']);
    $topic_islock = trim($a_info['islock']);
    if ($a_info['addinfo']) {
        list($moveinfo, $isjztitle) = explode("|", $a_info['addinfo']);
        list($isjztitle, $isjzcolor, $jiacu, $shanchu, $xiahuau, $xietii, $bgcolorcode, $fontsize) = explode(",", $isjztitle);
    } 
    // ///
//    if ($fastmanage == 1 && $login_status == 1 && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1")) {
//        if ($listfilename == "ttrash") {
//            $toplangg = "|<a href='misc.php?p=manage&amp;action=del&amp;filename=$filename'>$forum_mang_t[19]</a>";
//            $toplangg .= "|<a href='misc.php?p=rtrash&amp;action=move&amp;filename=$filename'>$forum_mang_t[20]</a>";
//        } else {
//            $toplangg = "|<a href='misc.php?p=manage&amp;action=del&amp;filename=$filename'>$forum_mang_t[0]</a>|";
//            if ($topic_islock != 1 && $topic_islock != 3) $toplangg .= "<a href='misc.php?p=manage&amp;action=lock&amp;filename=$filename'>$forum_mang_t[1]</a>";
//            else $toplangg .= "<a href='misc.php?p=manage&amp;action=unlock&amp;filename=$filename'>$forum_mang_t[2]</a>";
//            if ($topic_islock == 0 || $topic_islock == 1) $toplangg .= "|<a href='misc.php?p=manage&amp;action=jihua&amp;filename=$filename'>$forum_mang_t[21]</a>";
//            else $toplangg .= "|<a href='misc.php?p=manage&amp;action=unjihua&amp;filename=$filename'>$forum_mang_t[22]</a>";
//            $toplangg .= "|<a href='misc.php?p=manage&amp;action=move&amp;filename=$filename'>$forum_mang_t[3]</a>";
//            $toplangg .= "|<a href='misc.php?p=manage3&amp;action=move&amp;newforumid=trash&amp;filename=$filename'>$forum_mang_t[4]</a>";
//            $toplangg .= "|<a href='misc.php?p=manage2&amp;action=btfront&amp;filename=$filename'>$forum_mang_t[6]</a>";
//            if ($a_info['toptype'] != 9) {
//                $toplangg .= "|<a href='misc.php?p=topsys&amp;job=write&amp;step=2&amp;foruid=$forumid&amp;topid=$filename'>$forum_mang_t[7]</a>";
//            } elseif ($a_info['toptype'] == 9) {
//                $toplangg .= "|<a href='misc.php?p=topsys&amp;job=delone&amp;step=2&amp;foruid=$forumid&amp;topid=$filename'>$forum_mang_t[7]</a>";
//            } 
//            if ($a_info['toptype'] != 8) {
//                $toplangg .= "|<a href='misc.php?p=catesys&amp;cateid=$forum_cid&amp;job=write&amp;step=2&amp;foruid=$forumid&amp;topid=$filename'>$forum_mang_t[8]</a>";
//            } elseif ($a_info['toptype'] == 8) {
//                $toplangg .= "|<a href='misc.php?p=catesys&amp;cateid=$forum_cid&amp;job=delone&amp;step=2&amp;foruid=$forumid&amp;topid=$filename'>$forum_mang_t[8]</a>";
//            } 
//            if ($topic_type >= 3) $toplangg .= "|<a href='misc.php?p=manage2&amp;action=unhold&amp;filename=$filename'>$forum_mang_t[9]</a>|";
//            else $toplangg .= "|<a href='misc.php?p=manage2&amp;action=holdfront&amp;filename=$filename'>$forum_mang_t[10]</a>|";
//            if ($isjztitle == "0" || $isjztitle == "") $toplangg .= "<a href='misc.php?p=manage5&amp;action=add&amp;filename=$filename'>$forum_mang_t[11]</a>|";
//            else $toplangg .= "<a href='misc.php?p=manage5&amp;action=cancel&amp;filename=$filename'>$forum_mang_t[12]</a>|";
//        } 
//    } 
    if (utf8_strlen($a_info['author']) >= 12) $viewauthor = substrfor($a_info['author'], 0, 9) . '...';
    else $viewauthor = $a_info['author'];
    $icon = $a_info['face'];

//    if ($topic_type == 1) {
//        $stats = "<img src='$otherimages/system/statistic.gif' border='0' alt='' />";
//        if ($topic_islock == 1 || $topic_islock == 3) $stats = "<img src='$otherimages/system/closesta.gif' border='0' alt='' />";
//    } elseif ($topic_type >= 3) {
//        $stats = "<img src='$otherimages/system/holdtopic.gif' border='0' alt='' />";
//    } else {
//        if ($username != $a_info[author]) {
//            $stats = "<img src='$otherimages/system/topicnew.gif' border='0' alt='' />";
//            if ($reply >= 10) $stats = "<img src='$otherimages/system/topichot.gif' border='0' alt='' />";
//            if ($topic_islock == 1 || $topic_islock == 3) $stats = "<img src='$otherimages/system/topiclocked.gif' border='0' alt='' />";
//        } else {
//            $stats = "<img alt='($forum_mang_t[14])' src='$otherimages/system/mytopicnew.gif' border='0' />";
//            if ($reply >= 10) $stats = "<img alt='($forum_mang_t[14])' src='$otherimages/system/mytopichot.gif' border='0' />";
//            if ($topic_islock == 1 || $topic_islock == 3) $stats = "<img alt='($forum_mang_t[14])' src='$otherimages/system/mytopiclocked.gif' border='0' />";
//        } 
//    } 
    // -------if more than one page-----------
    if ($reply + 1 > $read_perpage) {
        if (($reply + 1) % $read_perpage == 0) $maxpageno = ($reply + 1) / $read_perpage;
        else $maxpageno = floor(($reply + 1) / $read_perpage) + 1;
        $multipage = (($bmfopt['rewrite'] && $filetopn == "topic.php") ? "topic_{$filename}_{page}" : "$filetopn?forumid=$forumid&amp;filename=$filename&amp;page={page}&amp;extra=page%3D$page");
    } 
    // /
    $titlelong = stripslashes($a_info['title']);
    $title = stripslashes($a_info['title']);

    if ($allow_ajax_reply && $login_status == 1 && ($username == $a_info["author"] || ($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1")) {
        $ajaxscript = true;
        $ajaxinfo = str_replace("'", "\'", $titlelong);
    }

    $font_css .= $jiacu ? "font-weight: bold;" : "";
    
    if ($xiahuau && $shanchu) $font_css .= "text-decoration: line-through underline;";
      else {
        $font_css .= $shanchu ? "text-decoration: line-through;" : "";
        $font_css .= $xiahuau ? "text-decoration: underline;" : "";
      }
    $font_css .= $xietii ? "font-style: italic;" : "";
    $font_css .= $bgcolorcode ? "background-color: $bgcolorcode;padding:7px;" : "";
    $font_css .= $fontsize ? "font-size: {$fontsize}pt;" : "";
    

    if ($isjztitle == "1") {
        if (!empty($isjzcolor)) {
            $font_css .= "color: $isjzcolor;";
        } 
    } 
    
    if ($bmfopt['display_ftag'] && $a_info['ttagname']) {
    	$tag_list = explode(" ", $a_info['ttagname']);
    	$first_tag_name	= $tag_list[0];
    	$url_tag_name	= urlencode($first_tag_name);
    }

    $lmd = explode(",", $a_info['lastreply']);
    $g = $timestamp - $lmd[2];
    $lmdauthor = urlencode($lmd[1]);
    $lmdtime_tmp = get_date($lmd[2]) . ' ' . get_time($lmd[2]);
    $cmdtime_tmp = get_date($a_info['time']);
    if ($time_2) {
        $timetmp_a = $timestamp - $lmd[2];
        $timetoshow = get_add_date($timetmp_a);
        if ($timetoshow == "getfulldate") {
            $timetoshow = $lmdtime_tmp;
        } 
        $timedmp_b = $timestamp - $a_info['time'];
        $aimetoshow = get_add_date($timedmp_b);
        if ($aimetoshow == "getfulldate") {
            $aimetoshow = $cmdtime_tmp;
        } 
    } else {
        $timetoshow = $lmdtime_tmp;
        $aimetoshow = $cmdtime_tmp;
    } 
    $hit = $a_info['hits'];
    $urlauthor = urlencode($a_info['author']);

//    if ($a_info['toptype'] == 9) {
//        $stats = "<img src='$otherimages/announce.gif' border='0' alt=''/>";
//    } elseif ($a_info['toptype'] == 8) {
//        $stats = "<img src='$otherimages/system/lockcattop.gif' border='0' alt=''/>";
//    } 
    if ($a_info['other1'] != 0) $a_info['other1'] = floor($a_info['other1']/10);

   
    $linktothread = ($bmfopt['rewrite'] && $filetopn == "topic.php") ? "topic_{$filename}" : "$filetopn?forumid=$forumid&amp;filename=$filename";
    $linktothreadPageLast = ($bmfopt['rewrite'] && $filetopn == "topic.php") ? "topic_{$filename}_last" : "$filetopn?forumid=$forumid&amp;filename=$filename&amp;page=last";

    $replacas = array("orgdata" => $a_info, "lmd" => $lmd, "titlelong" => $titlelong, "maxpageno" => $maxpageno, "first_tag_name" => $first_tag_name, "url_tag_name" => $url_tag_name, "font_css" => $font_css, "a_toptype" => $a_info['toptype'], "a_type" => $a_info['type'], "scores_change" => $a_info['other1'], "pin_score" => $pin_score, "stats" => $stats, "listfilename" => $listfilename, "filename" => $filename, "linktothreadPageLast" => $linktothreadPageLast, "linktothread" => $linktothread, "ajaxinfo" => $ajaxinfo, "ajaxscript" => $ajaxscript, "filename" => $filename, "icon" => $icon, "urlauthor" => $urlauthor, "hit" => $hit, "multipage" => $multipage, "title" => $title, "toplangg" => $toplangg, "aimetoshow" => $aimetoshow, "moveinfo" => $moveinfo, "viewauthor" => $viewauthor, "reply" => $reply, "lmdauthor" => $lmdauthor, "timetoshow" => $timetoshow);
	eval(load_hook('int_forums_article_line'));

    if ($a_info['toptype'] == 9) {
    	$hereis_top = 1;
        $topinfooutput[]= $replacas;
    } elseif ($a_info['toptype'] == 8) {
    	$hereis_top = 1;
        $quinfooutput[]= $replacas;
    } elseif ($a_info['type'] >= 3) {
    	$hereis_top = 1;
        $baninfooutput[]= $replacas;
    } else {
        $allinfooutput[]= $replacas;
    } 
	
} 
// ===================================
// online
// ===================================
function fast_new ()
{
	global $emot_every, $fast_post, $max_post_title, $forumid, $usertype, $set_a_tags, $html_codeinfo, $login_status, $username, $max_upload_size, $max_daily_upload_size, $uploadfiletoday, $min_post_length, $max_post_length, $emot_lines, $max_upload_post, $allow_upload, $upload_type_available, $forum_pos;

    $leftuploadnum = $max_daily_upload_size - $uploadfiletoday;
    $dbgcolor = "article_color1";
	$usertype_z = $usertype[110];
	eval(load_hook('int_forums_fast_new'));

	$fast_post = array("max_post_title" => $max_post_title, "extcount" => $extcount, "usertype_z" => $usertype_z, "chooser_c" => $chooser_c, "wemotinfoshow" => $wemotinfoshow, "showinfoofforum" => $showinfoofforum, "dbgcolor" => $dbgcolor, "htmlcodeinfo" => $html_codeinfo, "codeinfoc" => $codeinfoc, "uploadinfoshow" => $uploadinfoshow, "leftuploadnum" => $leftuploadnum, "actshows" => $actshows, "showuploads" => $showuploads, "addinfoone" => $addinfoone);
}
