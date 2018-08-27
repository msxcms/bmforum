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

$maxtop = 50; //Max Hot Tags

include_once("lang/$language/tags.php");
require_once("include/template.php");

$add_title = " - $t_l[0]";
eval(load_hook('int_tags_int'));
if ($see_a_tags == 0) {
	error_page('', $t_l[0], $t_l[0], $gl[277]);

} 
if ($tagname != "") {
    // Init
    if (empty($page)) $page = 1;
    
	$lang_zone = array("t_l"=>$t_l, "gl"=>$gl, "forum_picie"=>$forum_picie, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("tags", $temfilename, $styleidcode, $lang_zone);

    $tagname = str_replace("&amp;", "&", htmlspecialchars ($_GET['tagname']));
    // URL $tagname
    $urltagname = urlencode($tagname); 
    
    $add_title = " - $tagname - $t_l[0]";
    if ($forum_name) $add_title = " - $forum_name" . $add_title;
    require_once("header.php");

    $outputed = $countbyself = $chooseby = $add_sql = "";
    if (!empty($jinhua) && !is_numeric($jinhua)) $jinhua = "";
    if ($listby != "hit" && $listby != "reply" && $listby != "posttime" && $listby != "author" && $listby != "title" && $listby != "forum") $listby = "";

    for($i = 0;$i < $forumscount;$i++) {
        if (!($bmfopt['hidebyug'] && !check_forum_permission(0, 1, $sxfourmrow[$i])) && $sxfourmrow[$i][type] != "category" && check_permission($username, $sxfourmrow[$i][type]) && !$sxfourmrow[$i][forumpass] && $sxfourmrow[$i][forumpass] <> "d41d8cd98f00b204e9800998ecf8427e") {
            $forumnum["{$sxfourmrow[$i][id]}"] = $sxfourmrow[$i][bbsname];
        } else {
            if ($sxfourmrow[$i][type] != "category" && $countbyself != 1) $countbyself = 1;
            $add_sql .= " AND forumid!='{$sxfourmrow[$i][id]}'";
        } 
    } 
    if ($add_sql != "") $add_sql = "WHERE forumid!='xxxxx' " . $add_sql;
	eval(load_hook('int_tags_beforesql'));

    $query = "SELECT * FROM {$database_up}tags WHERE tagname='$tagname' ORDER BY 'tagid' DESC LIMIT 1";
    $result = bmbdb_query($query); 
    // Tags Row
    $tag_row = bmbdb_fetch_array($result);
    $th_tags = substr($tag_row['filename'], 1); 
    // Threads
    $th_tags_ex = implode("','", explode(",", $th_tags));
    $add_sql .= " AND tid in('$th_tags_ex')"; 
    // Count
    $count = $tag_row['threads'] ? $tag_row['threads'] : 0;

    if (!empty($jinhua)) {
        $maxchange = $timestamp-86400 * $jinhua;
        $chooseby = " AND changetime>$maxchange";
        if ($forumid == "" && $countbyself != 1) {
            $fquery = "SELECT COUNT(tid) FROM {$database_up}threads $add_sql $chooseby";
            $fresult = bmbdb_query($fquery);
            $frow = bmbdb_fetch_array($fresult);
            $count = $frow['COUNT(tid)'] ? $frow['COUNT(tid)'] : 0;
	    }
    } 

    if (($forumid != "" && $forum_type) || $countbyself == 1) {
        if ($forumid != "" && $forum_type) $add_sql .= " AND forumid='$forumid'";
        $fquery = "SELECT COUNT(tid) FROM {$database_up}threads $add_sql $chooseby";
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
    
    $navimode = newmode;
    if ($forum_name) $snavi_bar[] = "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>";
    $snavi_bar[] = "<a href=\"plugins.php?p=tags&amp;forumid=$forumid\">" . $t_l[0] . "</a>";
    $snavi_bar[] = $tagname;
    $des = "{$t_l[10]}{$count}<br />";
    navi_bar();
 


	$orderby = "ORDER BY `changetime` DESC";
    if ($listby) {
        if ($listby == "hit") $orderby = "ORDER BY `hits` DESC";
        if ($listby == "reply") $orderby = "ORDER BY `replys` DESC";
        if ($listby == "posttime") $orderby = "ORDER BY `time` DESC";
        if ($listby == "author") $orderby = "ORDER BY `author` DESC";
        if ($listby == "title") $orderby = "ORDER BY `title` DESC";
        if ($listby == "forum") $orderby = "ORDER BY `forumid` DESC";
    } 

    $query = "SELECT * FROM {$database_up}threads $add_sql $chooseby $orderby LIMIT $lastlimit,$perpage";
    $result = bmbdb_query($query);
    
    $prefix_file = ($bmfopt['rewrite']) ? "topic_" : "topic.php?filename=";

    while (false !== ($row = bmbdb_fetch_array($result))) {

        articlelist($row);
        $tags_output[] = array("pageLastLink" => $pageLastLink, "scores_change" => $other1, "pin_score" => $pin_score, "lmdauthor" => $lmdauthor, "lmdtime" => $lmdtime, "aimetoshow" => $aimetoshow, "urlauthor" => urlencode($row['author']), "forum_name" => $forum_name, "multipage" => $multipage, "title" => $title, "stats" => $stats, "hits" => $row['hits'], "replys" => $row['replys'], "author" => $row['author'], "forumid" => $row['forumid'], "tid" => $row['tid'], "icon" => $icon);

        $outputed = 1;
    } 


    // Mutilpages

    $nextpage = $page + 1;
    $previouspage = $page-1;
    $maxpagenum = $page + 4;
    $minpagenum = $page-4;

    $pageinfos = "<table class=\"tableborder_withoutwidth\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\"><tr><td class=\"pagenumber\"><strong>&nbsp;&nbsp;{$page}/$maxpageno&nbsp;&nbsp;</strong></td><td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='plugins.php?p=tags&amp;jinhua=$jinhua&amp;listby=$listby&amp;tagname=$urltagname&amp;forumid=$forumid';\"><a href=\"plugins.php?p=tags&amp;jinhua=$jinhua&amp;listby=$listby&amp;tagname=$urltagname&amp;forumid=$forumid\"><strong>&laquo;</strong></a></td>";
    for ($i = $minpagenum; $i <= $maxpagenum; $i++) {
        if ($i > 0 && $i <= $maxpageno) {
            if ($i == $page) {
                $pageinfos .= "<td class=\"pagenumber_1\"><strong><u>$i</u></strong></td>\n";
            } else {
                $pageinfos .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='plugins.php?p=tags&amp;jinhua=$jinhua&amp;listby=$listby&amp;tagname=$urltagname&amp;forumid=$forumid&amp;page=$i';\"><a href=\"plugins.php?p=tags&amp;jinhua=$jinhua&amp;listby=$listby&amp;tagname=$urltagname&amp;forumid=$forumid&amp;page=$i\">$i</a></td>\n";
            } 
        } 
    } 
    $pageinfos .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='plugins.php?p=tags&amp;jinhua=$jinhua&amp;listby=$listby&amp;tagname=$urltagname&amp;forumid=$forumid&amp;page=$maxpageno';\"><a href=\"plugins.php?p=tags&amp;jinhua=$jinhua&amp;listby=$listby&amp;tagname=$urltagname&amp;forumid=$forumid&amp;page=$maxpageno\"><strong>&raquo;</strong></a></td>\n";
    $pageinfos .= "<td class=\"pagenumber_2\" style=\"padding: 0\"><input size='2' type='text' id='pagej' onkeydown='javascript:gotopages(event);' /></td></tr></table>";

    $nextpage = $page + 1;
    $previouspage = $page-1;
    
	$readed_t = explode("|", $_SESSION['readpost']);
	eval(load_hook('int_tags_beforeop'));

    require($template_name);

    require_once("footer.php");
    exit;
} else {
	$lang_zone = array("t_l"=>$t_l, "gl"=>$gl, "forum_picie"=>$forum_picie, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("tags_list", $temfilename, $styleidcode, $lang_zone);
	
    require_once("header.php");
    // Nav Bar
    $navimode = newmode;
    
    if ($forum_name) $snavi_bar[] = "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>";
    $snavi_bar[] = $t_l[0];
    $des = "";
    navi_bar();
    

    $tagresults = "";

    $query = "SELECT * FROM {$database_up}tags ORDER BY 'threads' DESC LIMIT 0,$maxtop";
    $result = bmbdb_query($query);

    while (false !== ($row = bmbdb_fetch_array($result))) {
    	if (empty($max_size)) $max_size = $row['threads'];
        $title_tagname = $row['tagname'];
        if (!$forumid) $title_tagname.=" ({$row['threads']})";
        $size_t = get_tag_size ($row['threads'], $max_size);
        $tagresults[0].= "<a title='$title_tagname' style='text-decoration:underline;font-size:" . $size_t . "pt' href='plugins.php?p=tags&amp;forumid=$forumid&amp;tagname=" . urlencode($row['tagname']) . "'>{$row['tagname']}</a>&nbsp;&nbsp;\n";
    } 


    $query = "SELECT * FROM {$database_up}tags ORDER BY RAND() LIMIT 0,$maxtop";
    $result = bmbdb_query($query);

    while (false !== ($row = bmbdb_fetch_array($result))) {
        $size_t = get_tag_size ($row['threads'], $max_size);
        $title_tagname = $row['tagname'];
        if (!$forumid) $title_tagname.=" ({$row['threads']})";
        $tagresults[1].= "<a style='text-decoration:underline;font-size:{$size_t}pt' title='$title_tagname' href='plugins.php?p=tags&amp;forumid=$forumid&amp;tagname=" . urlencode($row['tagname']) . "'>{$row['tagname']}</a>&nbsp;&nbsp;\n";
    } 


    $query = "SELECT * FROM {$database_up}tags ORDER BY 'tagid' DESC LIMIT 0,$maxtop";
    $result = bmbdb_query($query);

    while (false !== ($row = bmbdb_fetch_array($result))) {
        $size_t = get_tag_size ($row['threads'], $max_size);
        $title_tagname = $row['tagname'];
        if (!$forumid) $title_tagname.=" ({$row['threads']})";
        $tagresults[2].= "<a style='text-decoration:underline;font-size:{$size_t}pt' title='{$title_tagname}' href='plugins.php?p=tags&amp;forumid=$forumid&amp;tagname=" . urlencode($row['tagname']) . "'>{$row['tagname']}</a>&nbsp;&nbsp;\n";
    } 
	eval(load_hook('int_tags_bop'));

    
    require($template_name);
    require_once("footer.php");
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
	eval(load_hook('int_tags_mutil_pages'));
} 
function articlelist($row)
{
    global $stats, $bmfopt, $pageLastLink, $other1, $prefix_file, $pin_score, $minoffset, $t_l, $time_1, $forumnum, $timestamp, $username, $read_perpage, $emotrand, $icon, $gl, $title, $multipage, $otherimages, $forum_name, $aimetoshow, $lmdtime, $lmdauthor;
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
        if ($username != $row[author]) {
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
    $cmdtime_tmp = get_date($row[time]);
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
	eval(load_hook('int_tags_artlist'));
} 

function get_tag_size($hits, $max) { //To decide how big the tag name has to be displayed
    $tagmaxsize = 35;
    $tagminsize = 12;
	if ($max == 0) $max = 1;
	$fsize = floor(($hits/$max) * $tagmaxsize);
	if ($fsize > $tagmaxsize) $fsize = $tagmaxsize;
	if ($fsize < $tagminsize) $fsize = $tagminsize;
	eval(load_hook('int_tags_get_tag_size'));
	return $fsize;
}