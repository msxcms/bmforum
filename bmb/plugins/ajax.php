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

/*
AJAX Processor
*/
include_once("include/bmbcodes.php");
include_once("include/readpost.func.php");
include_once("include/topic.func.php");
include_once("lang/$language/topic.php");

eval(load_hook('int_ajax_int'));

if (function_exists("bmb_ajax_". $act)) {
	call_user_func("bmb_ajax_". $act, $database_up, $language);
} else {
	$incluefile = "plugins/ajax/" . basename($_GET['act'] . ".php");
	if (@file_exists($incluefile) && basename($_GET['act']) != "") 
	{
		include_once($incluefile);
	} else {
		die('Access Denied');
	}
}

function bmb_ajax_checkmessages() 
{
	global $gotNewMessage, $userddata, $login_status, $userid;
	if(!$login_status) {
		echo '{"notify":0,"pm":0,"error":"nologin"}';
	} else {
		echo '{"notify":'.$userddata['unreadnote'].',"pm":'.$gotNewMessage.',"succ":1}';
	}
}

function bmb_ajax_modifytitle($database_up, $language)
{
	global $usermoney, $ajax_online, $openhtmlcode, $view_list, $forum_allow_ww, $uginfo, $enter_this_forum, $gl, $forumid, $forum_name, $p_read_post, $pgo, $ajax_reply, $error, $language, $read_allow_ww, $spusergroup, $postamount, $userbym , $forum_ford, $footer_copyright, $headername, $footername, $read_alignment, $html_lang, $enter_allow_ww, $userpoint, $enter_tb, $plugyescolor, $usertype, $forum_type, $forumid, $username, $login_status, $limitlist, $forum_admin, $verandproname, $otherimages, $bbs_title, $add_title, $cssinfo, $login_status, $ads_select, $admin_email, $site_url, $showtime, $site_name, $writefilenum, $readfilenum, $begin_time, $temfilename, $gl, $ajax_reply, $userid, $usertype, $timestamp, $forumscount, $max_post_title, $login_status, $sxfourmrow, $forum_admin, $username, $edit_true;
	if (!is_numeric($_GET['filename'])) die("Wrong Data");
	include_once("lang/$language/forums.php");
	$filename = $_GET['filename'];
	
    $result = bmbdb_query("SELECT * FROM {$database_up}posts WHERE id='{$filename}' LIMIT 0,1");
    $row = bmbdb_fetch_array($result);
    
    $forumid = $row['forumid'];
    get_forum_info("");

    $timeleft_edit = $timestamp - ($usertype[107] * 3600);
    if ($login_status == 1 && (($usertype[107] == "" && $row['usrid'] == $userid) || $usertype[22] == "1" || $usertype[21] == "1" || ($forum_admin && in_array($username, $forum_admin)))) $check_user = 1;
    if ($login_status == 1 && $usertype[107] >= 0 && $usertype[107] != "" && $timeleft_edit <= $row['timestamp'] && $row['usrid'] == $userid && $usertype[22] != "1" && $usertype[21] != "1") $check_user = 1;
    if ($login_status == 1 && $row['usrid'] != $userid && $edit_true != "1") $check_user = 0;
    if ($login_status == 1 && $check_user == 0) {
        $xfourmrow = $sxfourmrow;
        for($i = 0;$i < $forumscount;$i++) {
            if ($xfourmrow[$i][id] == $forumid) $adminlist .= $xfourmrow[$i]['adminlist'];
            if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
            if ($xfourmrow[$i][id] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
        } 
        $arrayal = explode("|", $adminlist);
        $admincount = count($arrayal);
        for ($i = 0; $i < $admincount; $i++) {
            if ($arrayal[$i] == $username && $arrayal[$i] != "" && $arrayal[$i] != "|") $check_user = 1;
        } 
    } 

    $somepostinfo = explode("_", $row['options']);
    
    $openhtmlcode = $somepostinfo[7];
    
    $articletitle = badwords($_GET['newtitle']);
    $articletitle = str_replace(",", "，", $articletitle);
    $articletitle = safe_convert($articletitle, 1);
    
    $filter_title = str_replace(" ", "", $articletitle);
    
    if (empty($filter_title) || utf8_strlen($_GET['newtitle']) >= $max_post_title) $check_user = 0;
    
	$ajax_reply = 1;
    check_forum_permission(1);
    
    if ($check_user == 0) {
		eval(load_hook('int_ajax_modify_error'));
        header("HTTP/1.0 689 Access Denied");
        exit;
    }
    
	$editinfosql = $timestamp."%".$username;
	eval(load_hook('int_ajax_modify_beforesql'));
    
    $nquery = bmbdb_query("UPDATE {$database_up}threads SET title='$articletitle',other4='$editinfosql' WHERE id = '{$filename}'");
    $nquery = bmbdb_query("UPDATE {$database_up}posts SET articletitle='$articletitle',other4='$editinfosql' WHERE id = '{$filename}'");
	eval(load_hook('int_ajax_modify_suc'));
	echo stripslashes($articletitle);
	

}
function bmb_ajax_getcontent($database_up, $language)
{
	global $usermoney, $ajax_online, $view_list, $forum_allow_ww, $uginfo, $enter_this_forum, $gl, $forumid, $forum_name, $p_read_post, $pgo, $ajax_reply, $error, $language, $read_allow_ww, $spusergroup, $postamount, $userbym , $forum_ford, $footer_copyright, $headername, $footername, $read_alignment, $html_lang, $enter_allow_ww, $userpoint, $enter_tb, $plugyescolor, $usertype, $forum_type, $forumid, $username, $login_status, $limitlist, $forum_admin, $verandproname, $otherimages, $bbs_title, $add_title, $cssinfo, $login_status, $ads_select, $admin_email, $site_url, $showtime, $site_name, $writefilenum, $readfilenum, $begin_time, $temfilename, $gl, $ajax_reply, $userid, $usertype, $checkattachpic, $timestamp, $forumscount, $max_post_title, $login_status, $sxfourmrow, $forum_admin, $username, $edit_true;
	if (!is_numeric($_GET['filename'])) die("Wrong Data");
	$filename = $_GET['filename'];
	
    $result = bmbdb_query("SELECT * FROM {$database_up}posts WHERE id='{$filename}' LIMIT 0,1");
    $row = bmbdb_fetch_array($result);
    
    $forumid = $row['forumid'];
    get_forum_info("");

    $timeleft_edit = $timestamp - ($usertype[107] * 3600);
    if ($login_status == 1 && (($usertype[107] == "" && $row['usrid'] == $userid) || $usertype[22] == "1" || $usertype[21] == "1" || ($forum_admin && in_array($username, $forum_admin)))) $check_user = 1;
    if ($login_status == 1 && $usertype[107] >= 0 && $usertype[107] != "" && $timeleft_edit <= $row['timestamp'] && $row['usrid'] == $userid && $usertype[22] != "1" && $usertype[21] != "1") $check_user = 1;
    if ($login_status == 1 && $row['usrid'] != $userid && $edit_true != "1") $check_user = 0;
    if ($login_status == 1 && $check_user == 0) {
        $xfourmrow = $sxfourmrow;
        for($i = 0;$i < $forumscount;$i++) {
            if ($xfourmrow[$i][id] == $forumid) $adminlist .= $xfourmrow[$i]['adminlist'];
            if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
            if ($xfourmrow[$i][id] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
        } 
        $arrayal = explode("|", $adminlist);
        $admincount = count($arrayal);
        for ($i = 0; $i < $admincount; $i++) {
            if ($arrayal[$i] == $username && $arrayal[$i] != "" && $arrayal[$i] != "|") $check_user = 1;
        } 
    } 
	$ajax_reply = 1;
    check_forum_permission(1);
    
    if ($check_user == 0) {
		eval(load_hook('int_ajax_read_error'));
        header("HTTP/1.0 689 Access Denied");
        exit;
    }

	eval(load_hook('int_ajax_read_content'));
	
	echo str_replace("<br />", "\n", stripslashes($row['articlecontent']));
	exit;

}
function bmb_ajax_modifycontent($database_up, $language)
{
	global $usermoney, $bmfopt, $myusertype, $max_post_length, $min_post_length, $newcontent, $ajax_online, $openhtmlcode, $view_list, $forum_allow_ww, $uginfo, $enter_this_forum, $gl, $forumid, $forum_name, $p_read_post, $pgo, $ajax_reply, $error, $language, $read_allow_ww, $spusergroup, $postamount, $userbym , $forum_ford, $footer_copyright, $headername, $footername, $read_alignment, $html_lang, $enter_allow_ww, $userpoint, $enter_tb, $plugyescolor, $usertype, $forum_type, $forumid, $username, $login_status, $limitlist, $forum_admin, $verandproname, $otherimages, $bbs_title, $add_title, $cssinfo, $login_status, $ads_select, $admin_email, $site_url, $showtime, $site_name, $writefilenum, $readfilenum, $begin_time, $temfilename, $gl, $userid, $ajax_reply, $usertype, $somepostinfo, $countas, $attachshow, $topattachshow, $author, $author_type, $line, $row, $bcode_post, $timestamp, $levelgroupdata, $usergroupdata, $forumscount, $max_post_title, $login_status, $sxfourmrow, $forum_admin, $username, $edit_true;
	if (!is_numeric($_GET['filename'])) die("Wrong Data");
	$filename = $_GET['filename'];
	
	$myusertype = $usertype;
	
    $result = bmbdb_query("SELECT p.*,m.* FROM {$database_up}posts p LEFT JOIN {$database_up}userlist m ON m.userid=p.usrid  WHERE id='{$filename}' LIMIT 0,1");
    $row = bmbdb_fetch_array($result);
    $author = $row['username'];
    
    $forumid = $row['forumid'];
    get_forum_info("");


    $timeleft_edit = $timestamp - ($usertype[107] * 3600);
    if ($login_status == 1 && (($usertype[107] == "" && $row['usrid'] == $userid) || $usertype[22] == "1" || $usertype[21] == "1" || ($forum_admin && in_array($username, $forum_admin)))) $check_user = 1;
    if ($login_status == 1 && $usertype[107] >= 0 && $usertype[107] != "" && $timeleft_edit <= $row['timestamp'] && $row['usrid'] == $userid && $usertype[22] != "1" && $usertype[21] != "1") $check_user = 1;
    if ($login_status == 1 && $row['usrid'] != $userid && $edit_true != "1") $check_user = 0;
    if ($login_status == 1 && $check_user == 0) {
        $xfourmrow = $sxfourmrow;
        for($i = 0;$i < $forumscount;$i++) {
            if ($xfourmrow[$i][id] == $forumid) $adminlist .= $xfourmrow[$i]['adminlist'];
            if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
            if ($xfourmrow[$i][id] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
        } 
        $arrayal = explode("|", $adminlist);
        $admincount = count($arrayal);
        for ($i = 0; $i < $admincount; $i++) {
            if ($arrayal[$i] == $username && $arrayal[$i] != "" && $arrayal[$i] != "|") $check_user = 1;
        } 
    } 
	$ajax_reply = 1;
    check_forum_permission(1);
    
    if ($check_user == 0) {
		eval(load_hook('int_ajax_mc_error'));
        header("HTTP/1.0 689 Access Denied");
        exit;
    }
    
    $somepostinfo = explode("_", $row['options']);
    
    $openhtmlcode = $somepostinfo[7];
	
	$articlecontent = $newcontent;
	$articlecontent = addslashes(badwords($articlecontent));
	$articlecontent = safe_convert($articlecontent);
    
	// ban forbid post
	if ($bmfopt['block_keywords'] > 0 && badwords($newcontent, 1) === FALSE) {
		require("lang/$language/post_global.php");
		eval(load_hook('int_ajax_mc_error'));
		
		header("HTTP/1.0 689 Access Denied");
		echo $errc[12];
	    exit;
	}
    if (utf8_strlen($articlecontent) >= $max_post_length) {
		require("lang/$language/post_global.php");
		eval(load_hook('int_ajax_mc_error'));
		
		header("HTTP/1.0 689 Access Denied");
		echo $check_data_lng[0];
	    exit;
    } 
    if (utf8_strlen($articlecontent) < $min_post_length) {
		require("lang/$language/post_global.php");
		eval(load_hook('int_ajax_mc_error'));
		
		header("HTTP/1.0 689 Access Denied");
		echo $check_data_lng[1];
	    exit;
    } 
	$editinfosql = $timestamp."%".$username;
	
	eval(load_hook('int_ajax_mc_bsql'));
	if ($filename == $row['tid']) {
		bmbdb_query("UPDATE {$database_up}threads SET `content`='$articlecontent',other4='$editinfosql' WHERE tid='$filename'");
	}
	
	bmbdb_query("UPDATE {$database_up}posts SET `articlecontent`='$articlecontent',other4='$editinfosql' WHERE id='$filename'");
	
	$usertype = $row['ugnum'];
	$author_type = getLevelGroup($usertype, $usergroupdata, $forumid, $row['postamount'], $row['point']);
    list($groupname, $groupimg, $systemg, $canpost, $canreply, $canpoll, $canvote, $max_sign_length, $sign_use_bmfcode, $bcode_sign['pic'], $bcode_sign['flash'], $bcode_sign['fontsize'], $enter_tb, $send_msg, $max_post_length, $short_msg_max, $send_msg_max, $use_own_portait, $swf, $max_upload_size, $upload_type_available, $supermod, $admin, $groupimg2, $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, $opencutusericon, $openupusericon, $max_avatars_upload_size, $max_avatars_upload_post, $upload_avatars_type_available, $maxwidth, $maxheight, $p_read_post, $view_list, $lock_true, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $modban_true, $clean_true, $showpic, $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $bcode_post['pic'], $bcode_post['reply'], $bcode_post['jifen'], $bcode_post['sell'], $bcode_post['flash'], $bcode_post['mpeg'], $bcode_post['iframe'], $bcode_post['fontsize'], $bcode_post['hpost'], $bcode_post['hmoney'], $allow_forb_ub, $can_visual_post, $member_list, $search_fun, $nwpost_list, $porank_list, $gvf, $see_amuser, $view_recybin, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point) = $author_type;
    $bcode_post['table'] = $author_type[115];
    
	$topic_content = stripslashes(stripslashes($articlecontent));
	
	$line = $row;
	
	$uploadfilename = $line[other3];
	if (strpos($uploadfilename, "×")) {
		$attachshow = explode("×", $uploadfilename);
		$countas = count($attachshow)-1;
	} else {
		$attachshow[0] = $uploadfilename;
		$countas = 1;
	} 
	$topattachshow = $attachshow;
	
	define("checkattachpic",1);
	
	eval(load_hook('int_ajax_mc_output'));
	if ($somepostinfo[1] != "checkbox") $topic_content = bmbconvert($topic_content, $bmfcode_post);

	echo $topic_content;
	exit;

}
