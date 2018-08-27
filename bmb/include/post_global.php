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

$onlylps = $timestamp - $lastlogin;
require("lang/$language/post_global.php");
require("lang/$language/login.php");

get_forum_info("");

$forum_admin_1 = $notrashlimit = $block_posts = "";

$xfourmrow = $sxfourmrow;
for($i = 0;$i < $forumscount;$i++) {
    if ($xfourmrow[$i]['id'] == $forumid) $forum_admin_1 .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i]['id'] == $forum_cid) $forum_admin_1 .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i]['id'] == $forum_upid) $forum_admin_1 .= $xfourmrow[$i]['adminlist'];
} 

$forum_admin = explode("|", $forum_admin_1);

$is_rec = ($can_rec && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1")) ? 1 : 0;

$add_title = " &gt; $forum_name &gt; POST";
$navi_bar_des = "$errc[3]";
$navi_bar_l2 = "<a href='forums.php?forumid=$forumid'>$forum_name</a>";

$step = $_POST["step"];

if ($login_status == 1 && $step == 2) getUserInfo();

if (defined('INPOST') && $login_status == 0 && !$guestpost) {
	header("Location: login.php");
	exit;
} 

$authinput = strtoupper($authinput);

if ($login_status == 0 && $guestpost && $step == 2) {
    $check = 0;
	if ($guestpost == 1) {
	    if (file_exists("datafile/bannames.php")) include("datafile/bannames.php");
    
        eval(load_hook('int_post_global_guestpost'));
        
        $username = 'Anonymous';
	     
        if (check_user_exi($username) == 0 && @array_search_value($username, $bannames) != "banned") {
            $login_status = 2;
        } else {
        	if ($ajax_reply == 1) ajax_reply_error($errc[7]);
			error_page($navi_bar_des, $navi_bar_l2, $gl[53], "$gl[233]<br /><br />$errc[7]", $errc[0], 1);
			$step = 0;
        } 
//        if ($log_va && $_SESSION["checkauthnum"] != $authinput) {
//			$authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
//			$_SESSION[checkauthnum] = $authnum;
//        	$_SESSION["logintry"]++;
//			eval(load_hook('int_post_global_fail'));
//        	if ($ajax_reply == 1) ajax_reply_error($errc[6]);
//			error_page($navi_bar_des, $navi_bar_l2, $gl[53], "$gl[233]<br /><br />$gl[440]", $errc[0], 1);
//			$step = 0;
//            $login_status = 0;
//        } 
//		if ($log_va) {
//			$authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
//			$_SESSION[checkauthnum] = $authnum;
//		}

    } 
} 
if (($timestamp - $lastlogin < $logon_post_second) && (($forum_admin && !in_array($username, $forum_admin)) && $usertype[22] != "1" && $usertype[21] != "1")) {
	if ($ajax_reply == 1) ajax_reply_error($errc[5]);
	error_page($errc[0], $errc[4], $errc[0], $errc[5], 1);
	$step = 0;
} 
if (($timestamp - ($regdate + $reg_posting * 3600) < 0) && (($forum_admin && !in_array($username, $forum_admin)) && $usertype[22] != "1" && $usertype[21] != "1")) {
	if ($ajax_reply == 1) ajax_reply_error($errc[11]);
	error_page($errc[0], $errc[4], $errc[0], $errc[11], 1);
	$step = 0;
}
get_forum_info("");
if ($spusergroup == "1" && $enter_this_forum == "0" && $login_status == "1") {
	if ($ajax_reply == 1) ajax_reply_error($gl[438]);
	error_page($error[3], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[192], $gl[438], $gl[192], 1);
	$step = 0;
} 

$query = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$result = bmbdb_query($query);
$row = bmbdb_fetch_array($result);

if ($row['ttrash'] == 1) {
    $checktrash = "yes";
} 
if ($login_status == 1 && !check_permission($username, $forum_type)) {
    // ---to check if the user have got the permission to post-------
    if ($ajax_reply == 1) ajax_reply_error($errc[8]);
    error_page($navi_bar_des, $navi_bar_l2, $gl[53], "$gl[233]<br /><br />$errc[8]", $errc[0], 1);
	$step = 0;
} 
if ($is_rec && $action == "modify") $notrashlimit = 1;
if ($checktrash == "yes" && $notrashlimit != 1 && !defined('NOTRASHLIMIT')) {
    // ---to check if the user have got the permission to post-------
    if ($ajax_reply == 1) ajax_reply_error($errc[ab9]);
    error_page($navi_bar_des, $navi_bar_l2, $gl[53], "$gl[233]<br /><br />$errc[ab9]", $errc[0], 1);
	$step = 0;
} 
$badmans = "datafile/badman/" . basename($forumid) . ".php";
if (file_exists($badmans) && $login_status == 1) {
    include("$badmans");
    if ($badman && in_array($username, $badman)) {
    	if ($ajax_reply == 1) ajax_reply_error($war[1]);
        error_page($navi_bar_des, $navi_bar_l2, $gl[53], "$gl[233]<br /><br />$war[1]", $errc[0], 1);
		$step = 0;
    } 
} 

$bmfemote = ' ';

// ban forbid post
if ($step == 2 && $bmfopt['block_keywords'] > 0 && badwords($articlecontent, 1) === FALSE) {
	$block_posts = 1;
}
// end

if ($login_status == 0 && $log_va) {
	$authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
	$_SESSION[checkauthnum] = $authnum;
	if ($gd_auth == 1) $tmp23s = "<img src=\"authimg.php?p=1\" alt='' onclick='javascript:randtime=Date.parse(new Date());this.src=\"authimg.php?p=1&amp;reget=1&amp;timerand=\"+randtime;' title='$gl[529]' style='cursor: pointer;' />";
		else $tmp23s = "<img src=\"authimg.php?p=1\" alt='' /><img src=\"authimg.php?p=2\" alt='' /><img src=\"authimg.php?p=3\" alt='' /><img src=\"authimg.php?p=4\" alt='' /><img src=\"authimg.php?p=5\" alt='' />";
}
if ($guestpost) {
	$anonymous = $gl[530];
	$anonymous_tips = $gl[531];
} else $anonymous_tips = $anonymous = "";
require("newtem/$temfilename/post_global.php");
$loginform = $po[0];

eval(load_hook('int_post_global_done'));

// +------------------------------------------------------------------------------------------------------
// ---------Flood Control-------------------
function array_search_value($search, $array_in)
{
    foreach ($array_in as $value) {
        if (@strstr($search, $value) !== false)
            return "banned";
    } 
    return false;
} 
function flood_limit($name, $limit)
{
    global $timestamp, $uginfo, $userddata, $usertype;

	eval(load_hook('int_post_global_flood_limit'));

    if ($usertype[22] == "1") return 1;
    if ($usertype[21] == "1") return 1;
    if ($usertype[24] == "1") return 1;
    if ($timestamp - $userddata['lastpost'] >= $limit) return 1;
    return 0;

} 

// +------------------------------------------------------------------------------------------------------
// ---------Change certain information-------------------
function send_suc()
{
    global $newlineno, $userid, $addalimit, $todaypt, $qadtvar, $timestamp, $action, $database_up, $bbsdetime, $username, $forumid, $postdontadd, $login_status, $articletitle, $filename, $id_unique, $idpath, $post_money, $post_jifen, $post_jifen_reply, $post_money_reply; 
    // change the information in last_mo.php
    if ($action == "reply" || $action == "quote") $setaddnum = "replysnum = replysnum +1";
    if ($action == "new" && $qadtvar != 1) $setaddnum = "topicnum  = topicnum +1";
    if ($action == "new") $setadd_t = "threadnum=threadnum+1,";

    if ($action == "reply" || $action == "quote") {
        $xnewlineno = $filename;
    } else {
        $xnewlineno = $newlineno;
    } 
    $lasttodaytime_f = gmdate("zY", $todaypt + $bbsdetime * 3600);
    $lasttodaytime_a = gmdate("zY", $timestamp + $bbsdetime * 3600);
    
    if ($lasttodaytime_f != $lasttodaytime_a) {
        $settodayquery = "todayp=1,todaypt ='$timestamp'";
    } else {
        $settodayquery = "todayp=todayp+1,todaypt ='$timestamp'";
    } 
    
    if (($action == "new" && $qadtvar != 1) || $action == "reply" || $action == "quote") {
    	$norecyle = ",fltitle = '$articletitle',flfname = '$xnewlineno',flposter = '$username',flposttime = '$timestamp',";
    }

    $nquery = "UPDATE {$database_up}forumdata SET {$addalimit} $setaddnum $norecyle $settodayquery WHERE id='$forumid'";
    $result = bmbdb_query($nquery); 
    // change user's data
    if ($login_status != "2") {
        if ($postdontadd != "1") {
            if ($action == "reply" || $action == "quote") {
                $nquery = "UPDATE {$database_up}userlist SET lastpost='$timestamp',postamount = postamount+1 , point = point+{$post_jifen_reply} , money = money+{$post_money_reply} WHERE userid = '$userid'";
            } else {
                $nquery = "UPDATE {$database_up}userlist SET lastpost='$timestamp',postamount = postamount+1 , point = point+{$post_jifen} , money = money+{$post_money} WHERE userid = '$userid'";
            } 
	        $result = bmbdb_query($nquery);
        } 
    } 
    // change the information in newuser.php
    $query = "SELECT * FROM {$database_up}lastest WHERE pageid='index'";
    $result = bmbdb_query($query);
    $line = bmbdb_fetch_array($result);

    $lasttodaytime = gmdate("zY", $line['lasttodaytime'] + $bbsdetime * 3600);

    if ($lasttodaytime != $lasttodaytime_a) {
        $setaddquery = "todaynew=1,lasttodaytime ='$timestamp',ydaynew='{$line['todaynew']}',";
    } else {
        $maxnewss = $line['todaynew'] + 1;
        if ($maxnewss >= $line['maxnews']) {
            $line['maxnews'] = $maxnewss;
        } 
        $setaddquery = "todaynew=todaynew+1,lasttodaytime ='$timestamp',";
    } 



    $nquery = "UPDATE {$database_up}lastest SET $setaddquery {$setadd_t}postsnum=postsnum+1,lastposts = '$articletitle',lastpostid = '$xnewlineno',lastposter = '$username',maxnews='{$line['maxnews']}',lastptime = '$timestamp' WHERE pageid='index'";
    $result = bmbdb_query($nquery);
	eval(load_hook('int_post_global_send_suc'));
    refresh_forumcach();
} 
// +------------------------------------------------------------------------------------------------------
// ---------Check data-------------------
function check_data($type = "post")
{
    global $articlecontent, $check_data_lng, $filename, $article, $max_post_length, $min_post_length, $max_post_title, $max_post_des, $articletitle, $status, $articledes, $selections, $title, $by, $address, $downaddress, $file_size, $action, $logourl;
    $check = 1;
    
    $filter_title = str_replace(" ", "", $articletitle);
    
    if (utf8_strlen($articlecontent) >= $max_post_length) {
        $status = $check_data_lng[0];
        $check = 0;
    } 
    if (utf8_strlen($articlecontent) < $min_post_length) {
        $status = $check_data_lng[1];
        $check = 0;
    } 
    if (empty($filter_title) && ($action == "new" || ($action == "modify" && $filename == $article))) {
        $status = $check_data_lng[1];
        $check = 0;
    }
    if (empty($filter_title) && ($action == "reply" || $action == "quote" || ($action == "modify" && $filename != $article))) {
    	$articletitle = $filter_title;
    }
    if (utf8_strlen($articletitle) >= $max_post_title) {
        $status = $check_data_lng[2];
        $check = 0;
    } 
    if (utf8_strlen($articledes) >= $max_post_des) {
        $status = $check_data_lng[3];
        $check = 0;
    } 
    if ($type == "vote" && empty($selections)) {
        $status = $check_data_lng[4];
        $check = 0;
    } 
	eval(load_hook('int_post_global_check_data'));
    return $check;
}
function tags_set($tags, $newlineno)
{
	global $database_up, $usertype, $tags_ex, $forumid, $max_tags_num;
	
	@include("datafile/cache/tags_topic.php");
	$tags_topic_list = explode("\n", str_replace("\r", "", ($tags_tlist[$forumid] ? $tags_tlist[$forumid] : $tags_tlist['tags_solid'])));

    $tags_ex = explode(" ", strip_tags(safe_convert(strtolower($tags))));
    $tags_ct = min(count($tags_ex), $max_tags_num);
    $tags_rct = 0;
    
    for ($i = 0; $i<$tags_ct; $i++) {
    	if ($tags_ex[$i] == "" || $tags_ex[$i] == "&nbsp;") {
    		unset($tags_ex[$i]);
    		continue;
    	}
    	$tags_rct++;
        $tags_sql .= "'$tags_ex[$i]',";
    }
	
    $tquery = "SELECT tagname,filename,tagid FROM {$database_up}tags WHERE tagname in({$tags_sql}'-1a')";
    $tresult = bmbdb_query($tquery);
    while (false !== ($tagrow = bmbdb_fetch_array($tresult))) {
    	$tagname_ext[] = $tagrow['tagname'];
    }
    for ($i = 0; $i<$tags_rct; $i++) {
    	if (@in_array($tags_ex[$i], $tagname_this)) {
            unset($tags_ex[$i]);
            continue;
        }
        $tags_ex[$i] = substrfor($tags_ex[$i], 0, 10);
        if (!@in_array($tags_ex[$i], $tagname_ext)) {
        	if ($usertype[109] == 1 && !@in_array($tags_ex[$i], $tags_topic_list)) {
        	    unset($tags_ex[$i]);
        	    continue;
        	}
            $nquery = "insert into {$database_up}tags (tagname,threads,filename) values ('$tags_ex[$i]',1,',$newlineno')";
            $result = bmbdb_query($nquery);
            $tagname_this[] = $tags_ex[$i];
        } else {
            $nquery = "UPDATE {$database_up}tags SET threads=threads+1,filename=CONCAT(filename,',$newlineno') WHERE tagname ='$tags_ex[$i]'";
            $result = bmbdb_query($nquery);
            $tagname_this[] = $tags_ex[$i];
        }
    }
    
	eval(load_hook('int_post_global_tags_set'));
    return $tagname_this;
} 
function ajax_reply_error($info = "Access Denied")
{
	eval(load_hook('int_post_global_ajax_reply_error'));
    header("HTTP/1.0 689 Access Denied");
    echo $info;
    exit;
}