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
include_once("lang/$language/tags.php");

if ($action == "digg") 
{
	$line = bmbdb_query_fetch("SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1");
	$threadrow = $line;
	$replyerlist = explode("|", $line['replyer']);
	$forumid = $line[forumid];

	get_forum_info("");
	tbuser();
	check_forum_permission();

	if ($row['ttrash'] == 1) {
	    $checktrash = "yes";
	} 
	if ($checktrash == "yes" && $view_recybin != "1") {
		ajax_browse_error($gl[438]);
	} 
	if ($forum_pwd <> "" && $forum_pwd <> "d41d8cd98f00b204e9800998ecf8427e" && $job <> "login" && $_COOKIE['b' . $forumid . 'mb'] <> $forum_pwd) {
	    ajax_browse_error();
	} 
	if ($p_read_post == 0 || $userpoint < $read_allow_ww) ajax_browse_error();

	eval(load_hook('int_digg_ajax'));

	if ($_SESSION["digg$filename"] != 1) {
		$_SESSION["digg$filename"] = 1;
		bmbdb_query("UPDATE {$database_up}threads SET diggcount=diggcount+1 WHERE tid='$filename'");
		echo ($line['diggcount']+1);
	} else {
		ajax_browse_error($t_l[16]);
	}
	
	exit;
} else {
    // Init
    if (empty($page)) $page = 1;
    
	include_once("include/template.php");

	$lang_zone = array("t_l"=>$t_l, "gl"=>$gl, "forum_picie"=>$forum_picie, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("digg", $temfilename, $styleidcode, $lang_zone);

    $tagname = str_replace("&amp;", "&", htmlspecialchars ($_GET['tagname']));
    // URL $tagname
    $urltagname = urlencode($tagname); 
    
    $add_title = " - Digg";


    $outputed = $countbyself = $chooseby = $add_sql = "";
    if (!empty($jinhua) && !is_numeric($jinhua)) $jinhua = "";
    if ($listby != "hit" && $listby != "reply" && $listby != "posttime" && $listby != "author" && $listby != "title" && $listby != "forum") $listby = "";
	
	$add_sql = $add_sql_2 = "";
	
    for($i = 0;$i < $forumscount;$i++) {
        if (!($bmfopt['hidebyug'] && !check_forum_permission(0, 1, $sxfourmrow[$i])) && $sxfourmrow[$i]['type'] != "category" && check_permission($username, $sxfourmrow[$i]['type']) && !$sxfourmrow[$i]['forumpass'] && $sxfourmrow[$i]['forumpass'] <> "d41d8cd98f00b204e9800998ecf8427e") {
            $forumnum["{$sxfourmrow[$i]['id']}"] = $sxfourmrow[$i]['bbsname'];
        } else {
            if ($sxfourmrow[$i]['type'] != "category" && $countbyself != 1) $countbyself = 1;
            $add_sql .= " AND forumid!='{$sxfourmrow[$i]['id']}'";
        } 
    } 

    // Count
    $count = $ttopicnum;
    if ($count <= 0) {
    	$forumid = "";
	    $sql_forumid.= "forumid!='xxxxx'";
    	$forum_name = "";
    }
    if ($forumid) {
    	$sql_forumid = "forumid='$forumid'";
    }
    if ($forum_name) $add_title = " - $forum_name" . $add_title;
    require_once("header.php");

	$jinhua = $jinhua ? $jinhua : 10;
    if (!empty($jinhua)) {
        $maxchange = $timestamp-86400 * $jinhua;
        $chooseby = " AND changetime>$maxchange";
        if ($forumid == "" && $countbyself != 1) {
            $fquery = "SELECT COUNT(tid) FROM {$database_up}threads WHERE {$sql_forumid} $add_sql $chooseby";
            $fresult = bmbdb_query($fquery);
            $frow = bmbdb_fetch_array($fresult);
            $count = $frow['COUNT(tid)'] ? $frow['COUNT(tid)'] : 0;
	    }
    } 

    if (($forumid != "" && $forum_type) || $countbyself == 1) {
        if ($forumid != "" && $forum_type) $add_sql .= " AND forumid='$forumid'";
        $fquery = "SELECT COUNT(tid) FROM {$database_up}threads WHERE {$sql_forumid} $add_sql $chooseby";
        $fresult = bmbdb_query($fquery);
        $frow = bmbdb_fetch_array($fresult);
        $count = $frow['COUNT(tid)'] ? $frow['COUNT(tid)'] : 0;
    } 

    if ($count % $perpage == 0) $maxpageno = $count / $perpage;
    else $maxpageno = floor($count / $perpage) + 1;
    if ($page == "last" || $page > $maxpageno) $page = $maxpageno;
    $pagemin = min(($page-1) * $perpage , $count-1);
    $pagemax = min($pagemin + $perpage-1, $count-1) + 1;
    $lastlimit = max(0, $perpage * ($page-1));
    
    get_forum_info("");
    
    $navimode = "newmode";
    $snavi_bar[] = "<a href=\"misc.php?p=digg&amp;action=list\">" . $t_l[14] . "</a>";
    if ($forum_name) $snavi_bar[] = "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>";
    $des = "";
    navi_bar();
 
	$orderby = "ORDER BY `diggcount` DESC";
	eval(load_hook('int_digg_beforequery'));

    $query = "SELECT * FROM {$database_up}threads WHERE {$sql_forumid} $add_sql $chooseby $orderby LIMIT $lastlimit,$perpage";
    $result = bmbdb_query($query);
    
    $prefix_file = ($bmfopt['rewrite']) ? "topic_" : "topic.php?filename=";

    while (false !== ($row = bmbdb_fetch_array($result))) {

        articlelist($row);
        $tags_output[] = array("pageLastLink" => $pageLastLink, "diggcount" => $row['diggcount'], "scores_change" => $other1, "pin_score" => $pin_score, "lmdauthor" => $lmdauthor, "lmdtime" => $lmdtime, "aimetoshow" => $aimetoshow, "urlauthor" => urlencode($row['author']), "forum_name" => $forum_name, "multipage" => $multipage, "title" => $title, "stats" => $stats, "hits" => $row['hits'], "replys" => $row['replys'], "author" => $row['author'], "forumid" => $row['forumid'], "tid" => $row['tid'], "icon" => $icon);

        $outputed = 1;
    } 


    // Mutilpages

    $nextpage = $page + 1;
    $previouspage = $page-1;
    $maxpagenum = $page + 4;
    $minpagenum = $page-4;

    $pageinfos = "<table class=\"tableborder_withoutwidth\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\"><tr><td class=\"pagenumber\"><strong>&nbsp;&nbsp;{$page}/$maxpageno&nbsp;&nbsp;</strong></td><td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='misc.php?p=digg&amp;action=list&amp;jinhua=$jinhua&amp;forumid=$forumid';\"><a href=\"misc.php?p=digg&amp;action=list&amp;jinhua=$jinhua&amp;forumid=$forumid\"><strong>&laquo;</strong></a></td>";
    for ($i = $minpagenum; $i <= $maxpagenum; $i++) {
        if ($i > 0 && $i <= $maxpageno) {
            if ($i == $page) {
                $pageinfos .= "<td class=\"pagenumber_1\"><strong><u>$i</u></strong></td>\n";
            } else {
                $pageinfos .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='misc.php?p=digg&amp;action=list&amp;jinhua=$jinhua&amp;forumid=$forumid&amp;page=$i';\"><a href=\"misc.php?p=digg&amp;action=list&amp;jinhua=$jinhua&amp;forumid=$forumid&amp;page=$i\">$i</a></td>\n";
            } 
        } 
    } 
    $pageinfos .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='misc.php?p=digg&amp;action=list&amp;jinhua=$jinhua&amp;forumid=$forumid&amp;page=$maxpageno';\"><a href=\"misc.php?p=digg&amp;action=list&amp;jinhua=$jinhua&amp;forumid=$forumid&amp;page=$maxpageno\"><strong>&raquo;</strong></a></td>\n";
    $pageinfos .= "<td class=\"pagenumber_2\" style=\"padding: 0\"><input size='2' type='text' id='pagej' onkeydown='javascript:gotopages(event);' /></td></tr></table>";

    $nextpage = $page + 1;
    $previouspage = $page-1;

	$readed_t = explode("|", $_SESSION['readpost']);
	eval(load_hook('int_digg_beforeoutput'));
    
    require($template_name);

    require_once("footer.php");
    exit;
}
function mutil_pages($row)
{
    global $read_perpage, $gl, $multipage, $otherimages;

    if (($row['replys'] + 1) % $read_perpage == 0) $maxpageno = ($row['replys'] + 1) / $read_perpage;
    else $maxpageno = floor(($row['replys'] + 1) / $read_perpage) + 1;

    $multipage = '[ <img src="' . $otherimages . '/mpages.gif" alt="" /><strong><span class="forum_page_links">';
    for ($i = 1; $i <= $maxpageno; $i++) {
        $multipage .= " <a href=\"".($bmfopt['rewrite'] ? "topic_{$row['tid']}_$i" : "topic.php?filename={$row['tid']}&amp;page=$i")."\">$i</a>";
        if ($i == 5) {
            $multipage .= " . . . <a href=\"".($bmfopt['rewrite'] ? "topic_{$row['tid']}_last" : "topic.php?filename={$row['tid']}&amp;page=last")."\">$maxpageno</a>";
            break;
        } 
    } 
    $multipage .= '</span></strong> ' . $gl[146] . ']';
	eval(load_hook('int_digg_mutil_pages'));
} 
function articlelist($row)
{
    global $stats, $other1, $pageLastLink, $bmfopt, $prefix_file, $pin_score, $minoffset, $t_l, $time_1, $forumnum, $timestamp, $username, $read_perpage, $emotrand, $icon, $gl, $title, $multipage, $otherimages, $forum_name, $aimetoshow, $lmdtime, $lmdauthor;
    $multipage = "";
    $reply = $row['replys'];
    $topic_type = @trim($row['type']);
    $topic_islock = @trim($row['islock']); 
    $toplang = utf8_strlen($row['content']);
    if (utf8_strlen($row['author']) >= 12) $viewauthor = substr($row['author'], 0, 9) . '...';
    else $viewauthor = $row['author'];
    $icon = $row['face'];

    if ($row['addinfo']) {
        list($moveinfo, $isjztitle) = explode("|", $row['addinfo']);
        list($isjztitle, $isjzcolor, $jiacu, $shanchu, $xiahuau, $xietii, $bgcolorcode, $fontsize) = explode(",", $isjztitle);
        $moveinfo = "<strong><span class='jiazhongcolor'>$moveinfo</span></strong>";
    } 
    
    

    if (($icon == "ran" || $icon == "") && $emotrand == 1) {
        $icon = mt_rand(0, 52) . '.gif';
        $icon = "<a target='_blank' href='{$prefix_file}{$row['tid']}'><img src='images/emotion/$icon' alt='$gl[160]' border='0' /></a>";
    } elseif (($icon == "ran" || $icon == "") && $emotrand != 1) {
        $icon = "&nbsp;";
    } else {
        $icon = "<a target='_blank' href='{$prefix_file}{$row['tid']}'><img src='images/emotion/$icon' alt='$gl[160]' border='0' /></a>";
    } 
    if ($topic_type == 1) {
        $stats = "<img border='0' src='$otherimages/system/statistic.gif' alt=\"\" />";
        if ($topic_islock == 1 || $topic_islock == 3) $stats = "<img border='0' src='$otherimages/system/closesta.gif' alt=\"\" />";
    } elseif ($topic_type >= 3) {
        $stats = "<img border='0' src='$otherimages/system/holdtopic.gif' alt=\"\" />";
    } else {
        if ($username != $row['author']) {
            $stats = "<img border='0' src='$otherimages/system/topicnew.gif' alt=\"\" />";
            if ($reply >= 10) $stats = "<img border='0' src='$otherimages/system/topichot.gif' alt=\"\" />";
            if ($topic_islock == 1 || $topic_islock == 3) $stats = "<img border='0' src='$otherimages/system/topiclocked.gif' alt=\"\" />";
        } else {
            $stats = "<img border='0' src='$otherimages/system/mytopicnew.gif' alt=\"\" />";
            if ($reply >= 10) $stats = "<img border='0' src='$otherimages/system/mytopichot.gif' alt=\"\" />";
            if ($topic_islock == 1 || $topic_islock == 3) $stats = "<img border='0' src='$otherimages/system/mytopiclocked.gif' alt=\"\" />";
        } 
    } 
    // -------if more than one page-----------
    if ($row['replys'] + 1 > $read_perpage) {
        mutil_pages($row);
    } 

    $row['title'] = stripslashes($row['title']);
    $row['title'] = str_replace("%a%", "<img src=\"images/attach/attach.gif\" border='0' alt=\"\" />", $row['title']);

    $title = $row['title'];

    $font_css .= $jiacu ? "font-weight: bold;" : "";
    
    if ($xiahuau && $shanchu) $font_css .= "text-decoration: line-through underline;";
      else {
        $font_css .= $shanchu ? "text-decoration: line-through;" : "";
        $font_css .= $xiahuau ? "text-decoration: underline;" : "";
      }
    $font_css .= $xietii ? "font-style: italic;" : "";
    $font_css .= $bgcolorcode ? "background-color: $bgcolorcode;" : "";
    $font_css .= $fontsize ? "font-size: {$fontsize}pt;" : "";

    if ($isjztitle == "1") {
        if (!empty($isjzcolor)) {
            $font_css .= "color: $isjzcolor;";
        } 
    } 
    $pin_score = "";
    if ($row['other1'] != 0) $row['other1'] = floor($row['other1']/10);
    if ($row['other1'] > 0) {
    	$pin_score = "<img src='$otherimages/hand2.gif' border='0' alt='+{$row['other1']}' title='+{$row['other1']}' />";
    } elseif ($row['other1'] < 0) {
    	$pin_score = "<img src='$otherimages/hand.gif' border='0' alt='{$row['other1']}' title='{$row['other1']}' />";
    }
    
    if ($font_css) $title = "<span style=\"$font_css\">$title</span>";
    
    $title = "<a href='{$prefix_file}{$row['tid']}' title='$t_l[1]{$row['ttagname']}'>{$title}</a>";

	$pageLastLink = ($bmfopt['rewrite']) ? "{$prefix_file}{$row['tid']}_last" :  "{$prefix_file}{$row['tid']}&page=last";

    $lmd = explode(",", $row['lastreply']);
    $g = $timestamp - $lmd[2];
    if ($g <= 3600) $title .= '  <img src="' . $otherimages . '/system//newred.gif" alt="" />';
    elseif ($g <= 86400) $title .= '  <img src="' . $otherimages . '/system//newblue.gif" alt="" />';
    elseif ($g <= 172800) $title .= '  <img src="' . $otherimages . '/system//newgreen.gif" alt="" />';
    $lmdauthor = "<a href=\"profile.php?job=show&amp;target=" . urlencode($lmd[1]) . "\">$lmd[1]</a>";
    if ($topic_islock == 2 || $topic_islock == 3) $title .= "  <img src=\"$otherimages/system/jhinfo.gif\" alt=\"\" />";

    $lmdtime_tmp = getfulldate($lmd[2]);
    $cmdtime_tmp = get_date($row['time']);
    if ($time_2) {
        $timetmp_a = $timestamp - $lmd[2];
        $lmdtime = get_add_date($timetmp_a);
        if ($lmdtime == "getfulldate") {
            $lmdtime = $lmdtime_tmp;
        } 
        $timedmp_b = $timestamp - $row[time];
        $aimetoshow = get_add_date($timedmp_b);
        if ($aimetoshow == "getfulldate") {
            $aimetoshow = $cmdtime_tmp;
        } 
    } else {
        $lmdtime = $lmdtime_tmp;
        $aimetoshow = $cmdtime_tmp;
    } 

    $forum_name = $forumnum["$row[forumid]"];
	eval(load_hook('int_digg_articlelist'));
} 
