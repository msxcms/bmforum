<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
header("HTTP/1.1 301 Moved Permanently");
header("Location: ./topic.php?filename=".$_GET['filename'].($_GET['page'] ? "&page=".$_GET['page'] : ''));
exit;

require("datafile/config.php");
//
define("canceltbauto", "yes");
define("onlinedelay", "1");
//
require("getskin.php");
include("include/bmbcodes.php");
$nowadsnum = 0;
require("lang/$language/article.php");
require("lang/$language/topic.php");
include_once("include/template.php");
require("include/readpost.func.php");

$gotoforumid = $forumid;

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
$query = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$result = bmbdb_query($query);
$row = bmbdb_fetch_array($result);
$threadrow = $row;
$forumid = $row[forumid];
$topic_name = $row['title'];


$replyerlist = explode("|", $row['replyer']);
if ((!$forumid || !$row[tid]) && $getlastpost != "yes") {
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
get_forum_info("");
tbuser();

// ============
// Online 
if ($login_status == 1) add_online(1);
    else add_guest(1);
// ============

if ($forum_style <> "") {
    if (file_exists("datafile/style/" . basename($forum_style))) include("datafile/style/" . basename($forum_style));
} 
require("newtem/$temfilename/global.php");
$xfourmrow = $sxfourmrow;
for($i = 0;$i < $forumscount;$i++) {
    if ($xfourmrow[$i][id] == $row[forumid]) {
        $bbsname = $xfourmrow[$i][bbsname];
        $adminlist .= $xfourmrow[$i]['adminlist'];
    } 
    if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
} 
$forum_admin = explode("|", $adminlist);
check_forum_permission();
if ($row['ttrash'] == 1) {
    $checktrash = "yes";
} 
if ($checktrash == "yes" && $view_recybin != "1") {
	error_page($error[3],
        "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[192], $gl[438], $gl[192]);
} 
if ($getlastpost == yes) {
    $query = "SELECT COUNT(*) FROM {$database_up}threads WHERE tid='$ct' AND ttrash!='1' AND forumid!='$forumid' LIMIT 0,1";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $row = $fcount['COUNT(*)'];
    if ($row > 0) {
        $line['tid'] = $ct;
    } else {
        $query = "SELECT * FROM {$database_up}threads WHERE forumid='$gotoforumid' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $result = bmbdb_query($query);
        $line = bmbdb_fetch_array($result);
    } 
    if ($line['tid'] == "") {
        echo "<meta http-equiv=\"Refresh\" content=\"0; URL=index.php\">";
        exit;
    } 
    @setcookie('lastpath', "abcsad.php", 0, $cookie_p, $cookie_d);
   	eval(load_hook('int_article_getlastpost'));
    echo "<meta http-equiv=\"Refresh\" content=\"0; URL=article.php?filename={$line['tid']}#postend\">";
    exit;
} 

if ($forum_pwd <> "" && $forum_pwd <> "d41d8cd98f00b204e9800998ecf8427e" && $job <> "login" && $_COOKIE['b' . $forumid . 'mb'] <> $forum_pwd) {
    echo "<meta http-equiv=\"Refresh\" content=\"0; URL=forums.php?forumid=$forumid\">";
    exit;
} 

$oldbbs_title = $bbs_title;
$bbs_title = strip_tags($topic_name) . " &lt; " . strip_tags($bbs_title);
require("header.php");
$bbs_title = $oldbbs_title;


$nresult_friend = bmbdb_query("SELECT * FROM {$database_up}contacts WHERE `type`=2 and `owner`='$userid'");
while (false !== ($line_friend = bmbdb_fetch_array($nresult_friend))) {
	$iguserlist[] = $line_friend['conname'];
} 

if ($p_read_post == 0 || $userpoint < $read_allow_ww) include("footer.php");
$row['title'] = stripslashes($row['title']);
$topic_name = $row['title'];
$topic_date = getfulldate($row['time']);

$sub = 0;
$topic_hit = $row['hits'];
$topic_reply = $row['replys'];
$topic_islock = $row['islock'];
$topic_type = trim($row['type']);
$clicked = $row['hits'] + 1;

$qbgcolor = "article_color2";

if ($down_attach == 0 || $userpoint < $down_attach_ww) $checkattachpic_true = 1; 

if ($hasread != 1) {
    if ($cacheclick) $nquery = "UPDATE LOW_PRIORITY {$database_up}threads SET hits = hits+1 WHERE tid = '$filename'";
    else $nquery = "UPDATE {$database_up}threads SET hits = hits+1 WHERE tid = '$filename'";
    $result = bmbdb_query($nquery);
    
    if (!empty($browse_add_point) && $userbym < $usertype[108] && $login_status == 1) $result = bmbdb_unbuffered_query("UPDATE {$database_up}userlist SET point = point + $browse_add_point WHERE userid='$userid' LIMIT 1");
} 

$up_id = $forum_upid;
for($i = 0;$i < count($xfourmrow);$i++) {
    if ($up_id == $xfourmrow[$i]['id']) {
        $up_name = $xfourmrow[$i]['bbsname'];
        break;
    } 
} 
$navimode = newmode;
if (empty($up_name)) {
    $des = "$tip[6]<strong class='cau'>$topic_hit</strong>$tip[7],$tip[8]<strong class='cau'>$topic_reply</strong> $tip[9]";
    $snavi_bar[0] = "<a href='forums.php?forumid=$forumid'>$forum_name</a>";
    $snavi_bar[1] = $topic_name;
    navi_bar();
} else {
    $des = "$tip[6]<strong class='cau'>$topic_hit</strong>$tip[7],$tip[8]<strong class='cau'>$topic_reply</strong> $tip[9]";
    $snavi_bar[0] = "<a href='forums.php?forumid=$up_id'>$up_name</a>";
    $snavi_bar[1] = "<a href='forums.php?forumid=$forumid'>$forum_name</a>";
    $snavi_bar[2] = $topic_name;
    navi_bar();
} 

// ===================================
// Pages Count
// ===================================
if (isset($page) && !is_numeric($page) && $page != "last") {
    $page = "";
} 
if (empty($page)) $page = 1;

$count = $topic_reply + 1;

if ($count % $read_perpage == 0) $maxpageno = $count / $read_perpage;
else $maxpageno = floor($count / $read_perpage) + 1;
if ($page == "last" || $page > $maxpageno) $page = $maxpageno;
$pagemin = min(($page-1) * $read_perpage , $count - 1);
$pagemax = min($pagemin + $read_perpage-1, $count - 1);
if ($maxpageno > 1) {
    $multi_page_bar = "[ ";
    $nextpage = $page + 1;
    $previouspage = $page-1;
    $maxpagenum = $page + 3;
    $minpagenum = $page-3;

    $multi_page_bar = "<table class=\"tableborder_withoutwidth\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\"><tr><td class=\"pagenumber\"><strong>&nbsp;&nbsp;{$page}/$maxpageno&nbsp;&nbsp;</strong></td><td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='article.php?filename=$filename';\"><a href=\"article.php?filename=$filename\"><strong>«</strong></a></td>";

    for ($i = $minpagenum; $i <= $maxpagenum; $i++) {
        if ($i > 0 && $i <= $maxpageno) {
            if ($i == $page) {
                $multi_page_bar .= "<td class=\"pagenumber_1\"><strong><u>$i</u></strong></td>\n";
            } else {
                $multi_page_bar .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='article.php?filename=$filename&amp;page=$i';\"><a href=\"article.php?filename=$filename&amp;page=$i\">$i</a></td>";
            } 
        } 
    } 
    $multi_page_bar .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='article.php?filename=$filename&amp;page=$maxpageno';\"><a href=\"article.php?filename=$filename&amp;page=$maxpageno\"><strong>»</strong></a></td>";
    $multi_page_bar .= "<td class=\"pagenumber_2\" style=\"padding: 0\"><input size='2' type='text' id='pagej' onkeydown='javascript:gotopages(event,this);' /></td></tr></table>";
	eval(load_hook('int_article_multi_page'));
} 
if ($pagemin < 0) $pagemin = 0;
$pagemaxq = $pagemax + 1;

// =================

$order_article = ($bmfopt['article_desc'] == 1) ? "ORDER BY o.id DESC" : "ORDER BY o.id ASC";

$aquery = "SELECT o.*,m.* FROM {$database_up}posts o LEFT JOIN {$database_up}userlist m ON m.userid=o.usrid WHERE o.tid='$filename' {$order_article} LIMIT $pagemin,$pagemaxq ";
$aresult = bmbdb_query($aquery);

$aquery = "SELECT o.*,m.* FROM {$database_up}posts o LEFT JOIN {$database_up}userlist m ON m.userid=o.usrid WHERE o.id='$filename' LIMIT 0,1";
$tmparray = bmbdb_query($aquery);

for ($ix = $pagemin; $ix <= $pagemax; $ix++) {
    if ($ix == $pagemin) {
        $line = bmbdb_fetch_array($tmparray);
        $pagemax++;
    }else $line = bmbdb_fetch_array($aresult);
    $topic_name = stripslashes($line[articletitle]);
    $author = $topic_author = $line[username];
    $topic_content = stripslashes($line[articlecontent]);
    $topic_date = $line[timestamp];
    $aaa = $line[ip];
    $icon = $line[usericon];
    $usesign = $line[options];
    $bym = $line[other1];
    $bymuser = $line[other2];
    $uploadfilename = $line[other3];
    $editinfo = $line[other4];
    $sellmoney = $line[other5];
    $author_point = $line[point];
    $i = $line[id];
    $topic_name = $topic_name;
    $topic_date = getfulldate($topic_date);
    $usertype = $line['ugnum'];
    $author_type = getLevelGroup($usertype, $usergroupdata, $forumid, $line['postamount'], $line['point']);
    list($groupname, $groupimg, $systemg, $canpost, $canreply, $canpoll, $canvote, $max_sign_length, $sign_use_bmfcode, $bcode_sign['pic'], $bcode_sign['flash'], $bcode_sign['fontsize'], $enter_tb, $send_msg, $max_post_length, $short_msg_max, $send_msg_max, $use_own_portait, $swf, $max_upload_size, $upload_type_available, $supermod, $admin, $groupimg2, $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, $opencutusericon, $openupusericon, $max_avatars_upload_size, $max_avatars_upload_post, $upload_avatars_type_available, $maxwidth, $maxheight, $p_read_post, $view_list, $lock_true, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $modban_true, $clean_true, $showpic, $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $bcode_post['pic'], $bcode_post['reply'], $bcode_post['jifen'], $bcode_post['sell'], $bcode_post['flash'], $bcode_post['mpeg'], $bcode_post['iframe'], $bcode_post['fontsize'], $bcode_post['hpost'], $bcode_post['hmoney'], $allow_forb_ub, $can_visual_post, $member_list, $search_fun, $nwpost_list, $porank_list, $gvf, $see_amuser, $view_recybin, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point) = $author_type;
    $bcode_post['table'] = $bcode_sign['table'] = $author_type[115];
    $checkattachpic = 0;
    $somepostinfo = explode("_", $usesign);
    
    if ($somepostinfo[1] != "checkbox" && $uploadfilename != "" && (preg_match("/\[pay=(.+?)\](.+?)\[\/pay\]/is", $topic_content) || preg_match("/\[hide=(.+?)\](.+?)\[\/hide\]/is", $topic_content) || preg_match("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/is", $topic_content) || preg_match("/\[hpost=(.+?)\](.+?)\[\/hpost\]/is", $topic_content) || preg_match("/\[post\](.+?)\[\/post\]/is", $topic_content))) {
        if ($myusertype[21] != "1" && $myusertype[22] != "1" && $bmfcode_post['sell'] && preg_match("/\[pay=(.+?)\](.+?)\[\/pay\]/is", $topic_content)) {
            $checkattachpi1 = preg_replace_callback("/\[pay=(.+?)\](.+?)\[\/pay\]/is", "checkpaid", $topic_content);
            $checkattachpi1 = $code14;
        } else {
            $checkattachpi1 = 1;
        } 
        // 威望值部分
        if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bmfcode_post['jifen'] && preg_match("/\[hide=(.+?)\](.+?)\[\/hide\]/eis", $topic_content)) {
            $checkattachpi2 = preg_replace_callback("/\[hide=(.+?)\](.+?)\[\/hide\]/is", "checkhiden", $topic_content);
            $checkattachpi2 = $code4;
        } else {
            $checkattachpi2 = 1;
        } 
        // 回复查看部分
        if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bmfcode_post['reply'] && preg_match("/\[post\](.+?)\[\/post\]/eis", $topic_content)) {
            $checkattachpi3 = preg_replace_callback("/\[post\](.+?)\[\/post\]/is", function ($matches) { return checkpost($matches[1]); }, $topic_content);
            $checkattachpi3 = $code1;
        } else {
            $checkattachpi3 = 1;
        } 
        // 帖子查看部分
        if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bmfcode_post['hpost'] && preg_match("/\[hpost=(.+?)\](.+?)\[\/hpost\]/eis", $topic_content)) {
            $checkattachpi4 = preg_replace_callback("/\[hpost=(.+?)\](.+?)\[\/hpost\]/is", function ($matches) { return checkhiden($matches[1], 'hpost'); }, $topic_content);
            $checkattachpi4 = $code4;
        } else {
            $checkattachpi4 = 1;
        } 
        // 金钱查看部分
        if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bmfcode_post['hmoney'] && preg_match("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/eis", $topic_content)) {
            $checkattachpi5 = preg_replace_callback("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/is", function ($matches) { return checkhiden($matches[1], 'hmoney'); }, $topic_content);
            $checkattachpi5 = $code4;
        } else {
            $checkattachpi5 = 1;
        } 

        if ($checkattachpi1 == 1 && $checkattachpi2 == 1 && $checkattachpi3 == 1 && $checkattachpi4 == 1 && $checkattachpi5 == 1) {
            $checkattachpic = 1;
        } 
        if ($login_status == 0) $checkattachpic = 0;
    } else {
    	$checkattachpic = (!$is_rec && $line['posttrash'] == 1) ? 0 : 1;
    } 
    if ($checkattachpic_true == 1) $checkattachpic = 0;
    
    
    $countas = "";
    $usergnshow = "";
    if (strstr($somepostinfo[6], "§")) {
        $usergnshowtmp = explode("§", $somepostinfo[6]);
        $fdcoun = count($usergnshowtmp);
        for ($fds = 0;$fds < $fdcoun;$fds++) {
            if ($usergnshowtmp[$fds] != "") {
                $usergnshow[] = str_replace("new", "", $usergnshowtmp[$fds]);
            } 
        } 
    } else {
        $usergnshow[0] = "nonono";
    } 
    if (strpos($uploadfilename, "×")) {
        $attachshow = explode("×", $uploadfilename);
        $countas = count($attachshow)-1;
    } else {
        if ($uploadfilename != "") {
            $attachshow[0] = $uploadfilename;
            $countas = 1;
        } 
    } 
    $topattachshow = $attachshow;
    
    if ($somepostinfo[1] != "checkbox") $topic_content = bmbconvert($topic_content, $bmfcode_post);
    
    $topic_content = ban_user($topic_content, $topic_author);
    
	if ($bmfopt['text_wm']) {
		$topic_content = text_watermark($topic_content);
	}
    
    $uploadfileshow = diplay_attachment(0, $countas, $line['id'], 1);
	
	eval(load_hook('int_article_display_post'));

    if ($line['id'] == $filename) {
        $maintopname = $topic_name;
        $urlmainauthor = urlencode($topic_author);
        $mainauthor = $topic_author;
        $maintopic_date = $topic_date;
        $maintopic_con = $topic_content;
        $afileshow = $uploadfileshow;
        // Tags
            
        if ($row['ttagname']) {
            $tags_url = ""; 
            $ttagname = $row['ttagname'];
            $ttag_ex = explode(" ", $ttagname);
            $t_count = count($ttag_ex);
            for ($ti = 0; $ti < $t_count; $ti ++) {
                $tags_url .= "<a href='plugins.php?p=tags&tagname=". urlencode($ttag_ex[$ti]) ."'>{$ttag_ex[$ti]}</a>&nbsp;";
            }
            $maintopic_con .= "<p><strong>$read_post[45]</strong> ".$tags_url."</p>";
        }
		eval(load_hook('int_article_display_topic'));
        continue;
    }
    $newreplyes[] = array("line"=>$line, "usertype"=>$usertype, "author_point"=>$author_point, "somepostinfo"=>$somepostinfo, "author_type"=>$author_type, "i"=>$i, "uploadfileshow"=>$uploadfileshow, "topic_content"=>$topic_content, "topic_date"=>$topic_date, "topic_author"=>$topic_author, "topic_name"=>$topic_name);
} 
$allow_ajax_reply = 0;
if ($checktrash != "yes" && $topic_islock != 1 && $topic_islock != 3) {
    if ($frep_select == 1) {
    	$allow_fast_reply = 1;
    	fast_reply();
    } 
} 
similar_threads($filename, $ttag_ex, 1);
$lang_zone = array("forum_line"=>$forum_line, "l_similar"=>$l_similar, "forum_pos"=>$forum_pos, "alang"=>$alang, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("article", $temfilename, $styleidcode, $lang_zone);

if ($p_read_post != "0" || $userpoint < $read_allow_ww) {
    require($template_name);
} 
require("footer.php");
exit;

function ban_user($topic_content, $author)
{
    global $logonutnum, $line, $iguserlist, $pingbiuser, $usergnshow, $myusertype, $author_point, $author_type, $username, $read_post;
    
    $lowerauthor = strtolower($author);
    
    if (!@in_array($lowerauthor, $iguserlist)) {
        include_once("datafile/banuserposts.php");
        if ((($banuserposts && in_array($lowerauthor, $banuserposts)) || $author_point < $author_type[114]) && $username != $author) {
            $topic_content = "$pingbiuser[0]";
        } 
    } 
    if ($line['posttrash'] == 1) {
		$topic_content = "$pingbiuser[0]";
    } 
    if (@in_array($lowerauthor, $iguserlist)) {
        $topic_content = $lowerauthor . "&nbsp;" . $pingbiuser[2];
    } 
    if (@in_array($logonutnum, $usergnshow) && $myusertype[22] != "1" && $myusertype[21] != "1") {
        $topic_content = "<br /><strong>$read_post[41]</strong>";
    } 
    eval(load_hook('int_article_ban_user'));
    return $topic_content;
} 
function sellit($sellmoney, $code, $outall = "yes")
{
    global $username, $page, $line, $row, $userid, $usergroupdata, $myusertype, $post_sell_max, $id_unique, $sellit_lng, $code1, $article, $username, $bbs_money, $idpath, $author, $forumid, $filename, $admin_name, $login_status;
    if (!$line['id']) $line = $row;
    $postamount = $line['postamount'];
    $usertype = $line['ugnum'];
    $userposmt = getLevelGroup($usertype, $usergroupdata, $forumid, $line['postamount'], $line['point']);
    $post_sell_max = $userposmt[29];
    $qqmm = 0;
    if ($sellmoney < 0) $sellmoney = 0;
    if ($sellmoney > $post_sell_max) $sellmoney = $post_sell_max;
    if ($sellmoney && !preg_match("/^[0-9]{0,}$/", $sellmoney)) $sellmoney = 0;
    $count = 0;

    $buyeres = explode(",", $line['sellbuyer']);
    $count = count($buyeres)-1;

    if ($buyeres && in_array($userid, $buyeres) || $username == $author || $myusertype[21] == "1" || $myusertype[22] == "1") $qqmm = 1;
    if ($login_status == 1 && $qqmm == 1) $code1 = "<div class='quote_dialog'><strong>[$sellit_lng[1] " . $sellmoney . $bbs_money . "$sellit_lng[2] " . $count . "{$sellit_lng[3]}]</strong><hr width='100%' class='bordercolor' size='1' />" . $code . "</div>";
    else $code1 = "<br /><div class='quote_dialog'><strong>[$sellit_lng[1] " . $sellmoney . $bbs_money . "$sellit_lng[2] " . $count . " {$sellit_lng[3]}]</strong><br /><br />$buyitbutton</div><br />";
    eval(load_hook('int_article_sellit'));
    return $code1;
} 
function diplay_attachment($start, $countas, $lineid, $getcode)
{
	global $read_post, $topattachshow, $attachshow, $checkattachpic, $somepostinfo, $filename;
    if ($start > 0) $start = $start - 1;
    for ($ias = $start;$ias < $countas;$ias++) {
    	if (!$topattachshow[$ias] && $getcode == 1) continue;
    	if (!$attachshow[$ias]) continue;
        $showdesa = $loaded = "";
        if ($getcode == 1) $showdes = explode("◎", str_replace("[BMDESBõ]", "×", $topattachshow[$ias])); 
        else $showdes = explode("◎", str_replace("[BMDESBõ]", "×", $attachshow[$ias]));
        $showdes[3] = str_replace("[BMDESAõ]", "◎", $showdes[3]);
        $showdes[1] = str_replace("[BMDESAõ]", "◎", $showdes[1]);
        $showdtim = "$read_post[42]$showdes[2])";
        $showdes[4] = @round($showdes[4] / 1024, 2);
        if ($showdes[4] == "") $showdes[4] = $read_post[44];
        $showdesb = "$showdes[3] ($read_post[43]$showdes[4]kb,";
        if ($showdes[1] != "") $showdesa = "($read_post[40]{$showdes[1]})";
        if (preg_match("/\.(gif|jpg|jpeg|png|pcx|wmf|bmp)$/i", $showdes[0])) {
            if ($checkattachpic == 1 && $somepostinfo[5] != "yes") {
                $uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img alt='' src='images/attach/pic.gif' border='0' /> $read_post[22] $showdesa<br /><img border='0' alt='' src='attachment.php?am=$ias&filename=$filename&article={$lineid}'  onload='javascript:if(this.width>screen.width-333)this.width=screen.width-333' onmousewheel='return bbimg(this);' /></a><br />";
            } else {
                $uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img alt='' src='images/attach/pic.gif' border='0' /> $read_post[23] $showdesa$showdesb $showdtim</a><br />";
            } 
            $loaded = 1;
        } elseif (preg_match("/\.(zip|rar|ace|bz2|tar|gz)$/i", $showdes[0])) {
            $uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img alt='' border='0' src='images/attach/zip.gif' /> $read_post[24] $showdesa$showdesb $showdtim</a><br />";
            $loaded = 1;
        } elseif (preg_match("/\.(txt|doc|pdf|log|ini|inf)$/i", $showdes[0])) {
            $uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img alt='' border='0' src='images/attach/txt.gif' /> $read_post[25] $showdesa$showdesb $showdtim</a><br />";
            $loaded = 1;
        }
        $uploadfileshow .= process_attach($showdes, basename(pathinfo($showdes[0], PATHINFO_EXTENSION)), $ias, $filename, $lineid, $read_post, $showdesa, $showdesb, $showdtim, $checkattachpic, $somepostinfo, $bcode_post, $loaded);
         
        $topattachshow[$ias] = "";
    } 
    eval(load_hook('int_article_diplay_attachment'));
    return $uploadfileshow;
}
