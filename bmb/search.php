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

$fid = $forumid;
$add_title = " &gt; $gl[275]";
if ($search_fun == 0) {
	error_page($gl[276], $gl[275], $gl[275], $gl[277]);

} 
$forumtosearch = "";
if (empty($keyword)) {
    // ---------input keyword---------
    include("header.php");
    if (empty($page)) $page = 1;
    navi_bar($gl[276], $gl[275], '', 'no');
    print_form();
    include("footer.php");
    exit;
} 
$check = 1; 
// -----防止恶意搜索开始----//
if ($_POST['keyword'] != "" && $sea_allowed) {
    if ($timestamp - $_COOKIE['seavisit_fr'] < $sea_allowed) {
    	error_page($gl[276], $gl[275], $gl[275], $gl[278]);
    } else {
        bmb_setcookie('seavisit_fr', $timestamp);
    } 
} 
// -----防止恶意搜索结束----//
if (empty($method1)) $method1 = 1;
if ($newid != "yes") {
    if ($method != "or" && $method != "and") $method = "or";
    
    $_POST['forumid'] = $_GET['forumid'] ? $_GET['forumid'] : $_POST['forumid'];

    if (!is_array($_POST['forumid'])) $thisforumid[] = $_POST['forumid'];
        else $thisforumid = $_POST['forumid'];
        

    $xxxcount = $forumscount;
    for($i = 0;$i < $xxxcount;$i++) {
    	$nowid = $sxfourmrow[$i]['id'];
    	$ftinfo[$nowid] = $sxfourmrow[$i]['type'];
    }
    
    foreach ($thisforumid as $forumid) {
	    if (empty($forumid) || $forumid == "all") {
	        $xxxcount = $forumscount;
	        for($i = 0;$i < $xxxcount;$i++) {
				if (!check_forum_permission(1, 1, $sxfourmrow[$i])) {
					continue;
				}
	            if ($sxfourmrow[$i]['type'] != "category") {
	                if (check_permission($username, $sxfourmrow[$i]['type']) && ($sxfourmrow[$i]['type'] == "subhidden" || $sxfourmrow[$i]['type'] == "hidden" || $sxfourmrow[$i]['type'] == "subforumhid" || $sxfourmrow[$i]['type'] == "forumhid")) {
	                    $forumtosearch .= " OR forumid='{$sxfourmrow[$i]['id']}'";
	                } elseif ($sxfourmrow[$i]['type'] != "subhidden" && $sxfourmrow[$i]['type'] != "hidden" && $sxfourmrow[$i]['type'] != "subforumhid" && $sxfourmrow[$i]['type'] != "forumhid") {
	                    $forumtosearch .= " OR forumid='{$sxfourmrow[$i]['id']}'";
	                } 
	            } 
	        } 
	        $this_all = 1;
	    } else {
	    	if ($this_all != 1) {
	    		if (check_forum_permission(1, 1, $forumid) && check_permission($username, $ftinfo[$forumid]))
	        	$forumtosearch .= " OR forumid='{$forumid}'";
	        }
	    } 
	}

    $outputinformation = $digestonlysql = $tagssql = "";
    
    if ($digestonly == 1) {
        $digestonlysql = " AND islock != 1 AND islock != 0";
    }

    if ($search_renums != 0) {
        $search_limit = "LIMIT 0,$search_renums";
    } 
    
    if ($tags && $see_a_tags) {
    	$tagssql = " AND (ttagname LIKE '%" . str_replace(" ", "%' $method ttagname LIKE '%", $tags) . "%')";
    }

    if ($method1 == 1) {
        $queryinfo = "threads WHERE ((title LIKE '%" . str_replace(" ", "%' $method title LIKE '%", $keyword) . "%') OR (content LIKE '%" . str_replace(" ", "%' $method content LIKE '%", $keyword) . "%')";
    } elseif ($method1 == 2) {
        $queryinfo = "threads WHERE ((author='" . str_replace(" ", "' $method author='", $keyword) . "')";
	} elseif ($method1 == 3) { 
		//By Arbir.查找我參與的主題 
		$queryinfo = "threads WHERE ((author!='" . str_replace(" ", "' $method author='", $keyword) . " ' ) AND (replyer LIKE '{$userid}|%' OR replyer LIKE '%|{$userid}|%')"; 
    } else {
        $queryinfo = "threads WHERE ((title LIKE '%" . str_replace(" ", "%' $method title LIKE '%", $keyword) . "%') OR (content LIKE '%" . str_replace(" ", "%' $method content LIKE '%", $keyword) . "%') OR (author='" . str_replace(" ", "' $method author='%", $keyword) . "')";
    } 
    
    if ($method2) $timetonow = $timestamp - $method2 * 86400;
    else $timetonow = 0; 
    
    // $maxresult=($page*$perpage);
    $resultcount = 0;
    $more = 0;
    $nquery = "SELECT * FROM {$database_up}$queryinfo) AND (id='xxxxx'{$forumtosearch}) AND changetime>=$timetonow $tagssql $digestonlysql ORDER BY 'changetime' DESC $search_limit";
    eval(load_hook('int_search_before_query'));
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        if ($fourmrow['content'] != "" && $fourmrow['tid'] != "") {
            $fourmrow['content'] = "";
            $fourmrow['title'] = stripslashes($fourmrow['title']);
            $outputinformation .= addslashes($method1 . "|" . nl2br(implode("|", str_replace("|", "│",$fourmrow))) . "\n");
        } 
    } 
    $nquery = "insert into {$database_up}search (result,bulitdate) values ('$outputinformation',$timestamp)";
    $result = bmbdb_query($nquery);
    $newlineno = bmbdb_insert_id();
    $id = $newlineno;
    bmbdb_query("DELETE FROM {$database_up}search WHERE bulitdate < ".($timestamp-86400));
} else {
    $nquery = "SELECT * FROM {$database_up}search WHERE id='$id' LIMIT 0,1";
	eval(load_hook('int_search_getid'));
    $nresult = bmbdb_query($nquery);
    $linexx = bmbdb_fetch_array($nresult);
    $outputinformation = $linexx['result'];
} 

if (empty($page)) $page = 1;
$more = 1;
$temp = explode("\n", $outputinformation);
$counttemp = count($temp)-1;
$maxresult = ($page * $perpage);
if ($maxresult > $counttemp) $maxresult = $counttemp;
$lastpage = $maxresult - $perpage;
$clastpage = $counttemp - $maxresult;

$resultcount = $counttemp;
if ($clastpage <= 0) $more = 0;

if ($counttemp <= 0) {
    if (empty($page)) $page = 1;
    error_page($gl[276], $gl[275], $gl[275], '
<br />
' . $gl[424] . ' <br />
<ul>
<li><a href="javascript:history.back(1);">' . $gl[15] . '</a></li>
</ul>');

} 
if ($newid != "yes") {
    $keyword = urlencode($keyword);
    @header("Location: search.php?keyword=$keyword&page=&id=$id&newid=yes");
    exit;
} 
// -----显示结果-------
include("header.php");
if (empty($page)) $page = 1;
navi_bar($gl[276], $gl[275], '', 'no');

include_once("include/template.php");

$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("search_end", $temfilename, $styleidcode, $lang_zone);


//set_var(array("searchforum" => $searchforum,));

$xfourmrow = $sxfourmrow;
$forumcount = $forumscount;
$keywordurl = urlencode($keyword);
$maxresult = min($maxresult, $resultcount);
for ($i = max(0, $maxresult - $perpage); $i < $maxresult; $i++) {
    $detail = explode("|", $temp[$i]);
    article_line($detail);
} 

$gotpage = $page;
$n = ceil($counttemp / $perpage);
if (empty($gotpage)) $gotpage = 1;
$nextpage = $gotpage + 1;
$previouspage = $gotpage-1;
$maxpagenum = $gotpage + 4;
$minpagenum = $gotpage-4;

$showre .= "<table class=\"tableborder_withoutwidth\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\"><tr>";
$showre .= "<td class=\"pagenumber\"><strong>&nbsp;&nbsp;{$resultcount}&nbsp;&nbsp;</strong></td>";
$showre .= "<td class=\"pagenumber\"><strong>&nbsp;&nbsp;{$gotpage}/$n&nbsp;&nbsp;</strong></td>";
$showre .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='search.php?keyword=$keywordurl&amp;page=&amp;id=$id&amp;newid=yes';\"><a href=\"search.php?keyword=$keywordurl&amp;page=&amp;id=$id&amp;newid=yes\" ><strong>«</strong></a></td>\n";
for ($i = $minpagenum; $i <= $maxpagenum; $i++) {
    if ($i > 0 && $i <= $n) {
        if ($i == $gotpage) {
            $showre .= "<td class=\"pagenumber_1\"><strong><u>$i</u></strong></td>\n";
        } else {
            $showre .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='search.php?keyword=$keywordurl&amp;page=$i&amp;id=$id&amp;newid=yes';\"><a href=\"search.php?keyword=$keywordurl&amp;page=$i&amp;id=$id&amp;newid=yes\">$i</a></td>";
        } 
    } 
} 
$showre .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='search.php?keyword=$keywordurl&amp;page=$n&amp;id=$id&amp;newid=yes';\"><a href=\"search.php?keyword=$keywordurl&amp;page=$n&amp;id=$id&amp;newid=yes\"><strong>»</strong></a></td>";
$showre .= "<td class=\"pagenumber_2\" style=\"padding: 0\"><input size='2' type='text' id='pagej' onkeydown='javascript:gotopages(event,this);' /></td></tr></table>";

//set_var(array("id" => $id, "keywordurl" => $keywordurl, "showre" => $showre));
eval(load_hook('int_search_result'));
require($template_name);
include("footer.php");
exit;

function article_line($a_info)
{
    global $forumid, $time_1, $bmf_search, $time_2, $keywordurl, $xfourmrow, $emotrand, $forumcount, $showtmp, $keyword, $username, $timestamp, $otherimages;

    $reply = $a_info[9];
    $hit = $a_info[8];
    $des = $a_info[15];
    $last_mod_data = $a_info[5];
    $title = $a_info[14];
    $filename = $a_info[2];
    $islock = $a_info[13];
    $topic_type = $a_info[12];
    $icon = $a_info[21];
    $author = $a_info[16];
    $forumid = $a_info[7];
    if ($forumid == "" || $filename == "") return;
    for ($io = 0; $io < $forumcount; $io++) {
        if ($xfourmrow[$io]['id'] == $forumid) $forumInfo = "<a href='forums.php?forumid={$xfourmrow[$io]['id']}'>" . $xfourmrow[$io]['bbsname'] . "</a>";
    } 
    if (utf8_strlen($author) >= 12) $viewauthor = substr($author, 0, 9) . "...";
    else $viewauthor = $author;
    if ($author == $username) $mypost = "<img src='$otherimages/system/mypost.gif' border='0' width='14' height='14' alt='' />";
    else $mypost = "&nbsp;";

    if (($icon == "ran" || $icon == "") && $emotrand == 1) {
        $icon = mt_rand(0, 52) . '.gif';
        $icon = "<a target=_blank href='topic.php?filename=$filename&amp;highlight=$keywordurl'><img src='images/emotion/$icon' alt='New Window' border='0' /></a>";
    } elseif (($icon == "ran" || $icon == "") && $emotrand != 1) {
        $icon = "&nbsp;";
    } else {
        $icon = "<a target=_blank href='topic.php?filename=$filename&amp;highlight=$keywordurl'><img src='images/emotion/$icon' alt='New Window' border='0' /></a>";
    } 
    if ($topic_type == 1) {
        $stats = "<img border='0' src='$otherimages/system/statistic.gif' alt='' />";
        if ($islock == 1) $stats = "<img border='0' src='$otherimages/system/closesta.gif' alt='' />";
     } elseif ($topic_type >= 3) {
        $stats = "<img border='0' src='$otherimages/system/holdtopic.gif' alt='' />";
    } else {
        if ($username != $author) {
            $stats = "<img border='0' src='$otherimages/system/topicnew.gif' alt='' />";
            if ($reply >= 10) $stats = "<img border='0' src='$otherimages/system/topichot.gif' alt='' />";
            if ($islock == 1) $stats = "<img border='0' src='$otherimages/system/topiclocked.gif' alt='' />";
        } else {
            $stats = "<img border='0' src='$otherimages/system/mytopicnew.gif' alt='' />";
            if ($reply >= 10) $stats = "<img border='0' src='$otherimages/system/mytopichot.gif' alt='' />";
            if ($islock == 1) $stats = "<img border='0' src='$otherimages/system/mytopiclocked.gif' alt='' />";
        } 
    } 
    

    $title = str_replace($keyword, "<strong><span class='jiazhongcolor'>$keyword</span></strong>", $title);
    $title = "<a href=\"topic.php?forumid=$forumid&amp;filename=$filename&amp;highlight=$keywordurl\">$title</a>";
    $lmd = explode(",", $last_mod_data);
    $g = $timestamp - $lmd[2];
    if ($g <= 3600) $title .= '<img src="' . $otherimages . '/system/newred.gif" alt="" />';
    elseif ($g <= (3600 * 24)) $title .= '<img src="' . $otherimages . '/system/newblue.gif" alt="" />';
    elseif ($g <= (3600 * 48)) $title .= '<img src="' . $otherimages . '/system/newgreen.gif" alt="" />';
    if ($lmd[2] == $date) $lmdauthor = "------";
    else $lmdauthor = "<a href=\"profile.php?job=show&amp;target=" . urlencode($lmd[1]) . "\">$lmd[1]</a>";
    $lmdtime = get_date($lmd[2]) . ' ' . get_time($lmd[2]);
    $urlauthor = urlencode($author);
    
    if ($islock == 2 || $islock == 3) $title .= "  <img src=\"$otherimages/system/jhinfo.gif\" alt='' />";
    
    // Time and Date
    $lmdtime_tmp = getfulldate($lmd[2]);
    $cmdtime_tmp = get_date($a_info[19]);
    
   
    if ($time_2) {
        $timetmp_a = $timestamp - $lmd[2];
        $lmdtime = get_add_date($timetmp_a);
        if ($lmdtime == "getfulldate") {
            $lmdtime = $lmdtime_tmp;
        } 
        $timedmp_b = $timestamp - $a_info[19];
        $aimetoshow = get_add_date($timedmp_b);
        if ($aimetoshow == "getfulldate") {
            $aimetoshow = $cmdtime_tmp;
        } 
    } else {
        $lmdtime = $lmdtime_tmp;
        $aimetoshow = $cmdtime_tmp;
    } 
    // ...
    $bmf_search[] = array("stats" => $stats, "aimetoshow" => $aimetoshow, "filename" => $filename, "forumid" => $forumid, "lmdtime" => $lmdtime, "lmdauthor" => $lmdauthor, "hit" => $hit, "reply" => $reply, "viewauthor" => $viewauthor, "title" => $title, "des" => $des, "icon" => $icon, "forumInfo" => $forumInfo, "urlauthor" => $urlauthor);

} 
function print_form()
{
    global $template, $bmfopt, $sxfourmrow, $forumscount, $iblock, $block, $icount, $cachedstyle, $styleidcode, $language, $database_up, $openstylereplace, $gl, $temfilename, $username, $searchfid;
    
    if (!$searchfid) $selected_all = " selected='selected' ";
    
    for($i = 0;$i < $forumscount;$i++) {
        $checkeds = "";
		if (!check_forum_permission(0, 1, $sxfourmrow[$i])) {
			continue;
		}
        if ($searchfid == $sxfourmrow[$i]['id']) $checkeds = "selected";
        if ($sxfourmrow[$i]['type'] != "category") {
            if (check_permission($username, $sxfourmrow[$i]['type']) && ($sxfourmrow[$i]['type'] == "subhidden" || $sxfourmrow[$i]['type'] == "hidden" || $sxfourmrow[$i]['type'] == "subforumhid" || $sxfourmrow[$i]['type'] == "forumhid")) {
                $searchforum .= "<option value=\"{$sxfourmrow[$i]['id']}\" $checkeds >{$sxfourmrow[$i]['bbsname']}</option>";
            } elseif ($sxfourmrow[$i]['type'] != "subhidden" && $sxfourmrow[$i]['type'] != "hidden" && $sxfourmrow[$i]['type'] != "subforumhid" && $sxfourmrow[$i]['type'] != "forumhid") {
                $searchforum .= "<option value=\"{$sxfourmrow[$i]['id']}\" $checkeds >{$sxfourmrow[$i]['bbsname']}</option>";
            } 
        } else {
        	if ($optgroup == 1) {
        		$searchforum .= "</optgroup>";
        	}
            $searchforum .= "<optgroup label=\"-=-{$sxfourmrow[$i]['bbsname']}-=-\">";
            $optgroup = 1;
        } 
    } 
   	if ($optgroup == 1) {
   		$searchforum .= "</optgroup>";
   	}
    require("include/template.php");
    
	$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("search_start", $temfilename, $styleidcode, $lang_zone);


    require($template_name);
} 


