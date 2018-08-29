<?php
/*
 BMForum Bulletin Board Systems
 Code Name: Datium! (MySQL)
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
if (!defined('INBMFORUM')) die("Access Denied");
require_once("include/version.php");
// -----------------------------------------------------------
// PHP 5.1 supported.
// -----------------------------------------------------------
require("include/db/db_{$sqltype}.php");
require("lang/$language/global.php");

if (PHP_VERSION<5.3) set_magic_quotes_runtime(0);
else date_default_timezone_set("Etc/GMT-8");

/* Set internal character encoding to UTF-8 */
if(function_exists("mb_internal_encoding"))
{
	mb_internal_encoding("UTF-8");
}

$mqgpc = get_magic_quotes_gpc();
$rggps = ini_get("register_globals");

if ($mqgpc == 0) {
    $_SESSION = addsd($_SESSION);
    $_COOKIE = addsd($_COOKIE);
    $_POST = addsd($_POST);
    $_GET = addsd($_GET);
    if ($rggps == 0) {
        @extract($_SESSION, EXTR_SKIP);
        @extract($_COOKIE, EXTR_SKIP);
        @extract($_POST, EXTR_SKIP);
        @extract($_GET, EXTR_SKIP);
    } else {
        @extract($_SESSION, EXTR_OVERWRITE);
        @extract($_COOKIE, EXTR_OVERWRITE);
        @extract($_POST, EXTR_OVERWRITE);
        @extract($_GET, EXTR_OVERWRITE);
    } 
} else {
    @extract($_SESSION, EXTR_SKIP);
    @extract($_COOKIE, EXTR_SKIP);
    @extract($_POST, EXTR_SKIP);
    @extract($_GET, EXTR_SKIP);
} 
// END
$begin_time = micro_time();
bmbdb_connect($db_server, $db_username, $db_password, $db_name, 0, $mysqlchar);
$timestamp = time();
$datestime = $timestamp + ($time_1 * 60 * 60);
$nowhours = gmdate("H", $datestime);
$ddwtail = explode("|", $scclose);

$querynum = 0;

$tmpdir = getdate($timestamp);
if ($saveattbyym == 1) $monthdir = "month" . $tmpdir['year'] . $tmpdir['mon'] . "/";
$tmpdir = $tmpdir['year'] . $tmpdir['yday'];

if (!$sess_cust) {
    @session_save_path("tmp");
} 
@session_name('bmsess');
@session_cache_limiter("private, post-check=0, pre-check=0, max-age=0");
@session_start();

$ip = $_SERVER['REMOTE_ADDR'];
$ip1 = $_SERVER['HTTP_X_FORWARDED_FOR'];
if (($ip1 != "") && ($ip1 != "unknown")) $ip = $ip1;
$check_ip = strstr($ip, ",");
if ($check_ip) $ip = str_replace(strstr($check_ip, ","), '', $ip);
$ip = str_replace('|', '', htmlspecialchars(addslashes($ip)));

// --refresh check --
$cookietime = $timestamp + 3153600;

$REQUEST_URI = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];

if ($refresh_allowed) {
    if ($REQUEST_URI == $_COOKIE['lastpath'] && ($timestamp - $_COOKIE['lastvisit_fr'] < $refresh_allowed)) {
        @header("Content-Type: $encode_info");
        die("$gl[374]");
    } 
    bmb_setcookie('lastpath', $REQUEST_URI);
    bmb_setcookie('lastvisit_fr', $timestamp);
} 
$ugsocount = 0;
include_once("datafile/cache/usergroup.php");
include_once("datafile/cache/forumdata.php");
$addquery = $plusug = "";

// Filter
if (!is_numeric($forumid)) $forumid = "";
if (!is_numeric($tid)) $tid = "";
if (!is_numeric($filename)) $filename = "";
if (!is_numeric($article) && $article != "all" && $article != "multi") $article = "";
$actionreason = htmlspecialchars ($actionreason);
// END

$username = $tcode_item = $tcode_count = "";
if (!empty($_SESSION['bmfUsrId']) && !empty($_SESSION['bmfUsrAuth']) && checkauth($_SESSION['bmfUsrId'], $_SESSION['bmfUsrAuth'])) {
    // Copyright(C) Blue Magic Board BMForum.com
    $login_status = 1;
    if ($_COOKIE['bmfUsrId'] == $_SESSION['bmfUsrId']) {
        $userAuth = $_SESSION['bmfUsrAuth'];
    } else {
        if (checkauth($_COOKIE['bmfUsrId'], $_COOKIE['bmfUsrAuth'])) {
            $userAuth = $_COOKIE['bmfUsrAuth'];
        } else {
            $usermoney = $userpoint = $userbym = $postamount = $login_status = 0;
            add_guest();
        } 
    } 
    if ($login_status != 0) {
        getUserInfo();
        if ($username != "") add_online();
        $o_username = urlencode($username); 
        // 记入上次访问时间开始
        if ($hisipa != $ip) {
            $hisipc = $hisipb;
            $hisipb = $hisipa;
            $hisipa = $ip;
        } 
        $tlastvisit = $timestamp;
    } 
} elseif (!empty($_COOKIE['bmfUsrId']) && !empty($_COOKIE['bmfUsrAuth']) && checkauth($_COOKIE['bmfUsrId'], $_COOKIE['bmfUsrAuth'])) {
    // Logon
    $login_status = 1;
    // Read vars from cookie&session;
    $userAuth = $_COOKIE['bmfUsrAuth'];
    $bmblogonskin = $_COOKIE['bmbskin'];
    $privacy = $_COOKIE['privacybym'];
    $TNM = $_COOKIE['TNM'];
    $_SESSION["bmfUsrAuth"] = $_COOKIE['bmfUsrAuth'];
    $_SESSION["bmfUsrId"] = $_COOKIE['bmfUsrId'];
    $_SESSION["privacy"] = $_COOKIE['privacybym'];
    $_SESSION["TNM"] = $_COOKIE['TNM'];
    
    // Processing Data
        
    getUserInfo();
    if ($usertype[22] == 1) error_reporting(E_ALL ^ E_NOTICE);
    $o_username = urlencode($username); 
    // Last Visit
    list($openit, $eudate, $eugroup) = explode(",", $foreuser);
    if ($openit == "yes" && $timestamp >= $eudate) {
        $addquery = " ugnum='" . str_replace("\n", "", $eugroup) . "',";
        $foreuser = "";
    } 
    if ($hisipa != $ip) {
        $hisipc = $hisipb;
        $hisipb = $hisipa;
        $hisipa = $ip;
    } 

    $tlastvisit = $timestamp;
    $lastlogin = $timestamp;
    $nquery = "UPDATE {$database_up}userlist SET $addquery foreuser='$foreuser',hisipa='$hisipa',hisipb='$hisipb',hisipc='$hisipc',tlastvisit='$tlastvisit',lastlogin='$lastlogin' WHERE userid='$userid'";
    $result = bmbdb_query($nquery);
} else {
    $usermoney = $userpoint = $userbym = $postamount = $login_status = 0;
    add_guest();
} 
// Check permission
if ($usertype[22] == 1 && $login_status == 1) error_reporting(E_ALL ^ E_NOTICE);
// Ban IP&ID
idbanned();
ipbanned();

$prefix_file = ($bmfopt['rewrite']) ? "forums_" : "forums.php?forumid=";
$log_hash = substr(md5($password.$userid), 0, 6);
eval(load_hook('int_global_start'));

// -------------- Processed Time ------------
function micro_time()
{
    preg_match('/^0(\\.\d+) (\d+)$/', microtime(), $tmp);
    return (real)$tmp[2] . $tmp[1];
} 
// ---------Display the Message Table-------------------
function msg_box($title, $content, $showtips = '1', $nobutton = false)
{
    global $bm_skin, $pgo, $temfilename, $gl;
    
    define("IN_MSGBOX", true);
    
    if ($showtips) {
        $msg_box_title = "$title";
        $msg_box_detail = "$gl[376] {$title} $gl[377]<br /><br />";
    } else {
    	$msg_box_title = "$title";
    }
    require("newtem/$temfilename/global.php");
	eval(load_hook('int_global_msg_box'));
    
    echo $pgo[0];
} 
// +------------------------------------------------------------------------------------------------------
// ---------Display the Navigation Bar-------------------
function naviBar($links, $des = '') {
	global $temfilename, $styleidcode;
	
	if(!is_array($links)) {
		$links = array(array('name' => $links));
	}
	if(!is_array($links[0])) {
		$links[0] = $links;
	}

	include_once("include/template.php");
	$lang_zone = array();
	$template_name = newtemplate("navibar", $temfilename, $styleidcode, $lang_zone);
	
	require_once($template_name);
}
function navi_bar($des = "", $l2 = "", $l3 = "", $echorefresh = "yes", $nolimit = "1")
{
    global $bbs_title, $pgo, $fjsjump, $echonaved, $snavi_bar_url, $snavi_bar, $otherimages, $navimode;
    if ($nolimit == 0 && $echonaved == 1) return;
    $echonaved = 1;
    $openpic = $pgo['breadcrumbDivider'];
    $barpic = "";
    $space = "";
    if (empty($des)) global $des;
    if(is_array($snavi_bar)){
    	$count = count($snavi_bar);
    }
    $cachehtml = $pgo[4] . $space . $closepic . "";
    if ($l3) $l2pic = $closepic;
    else $l2pic = $openpic;
    if ($navimode == 'newmode') {
        for ($i = 0; $i < $count; $i++) {
            $cachehtml .= $space . $barpic . $openpic;
            if (!empty($snavi_bar_url[$i])) $cachehtml .=  "<a href=\"$snavi_bar_url[$i]\">$snavi_bar[$i]</a>";
            else $cachehtml .= $snavi_bar[$i];
        } 
    } else {
        $cachehtml .= $space . $barpic . $openpic . "$l2";
        if ($l3) $cachehtml .= $space . $space . $barpic . $openpic . $l3;
    } 
    $cachehtml .= $pgo[5] . $des;
    $cachehtml .= $pgo[6];
	eval(load_hook('int_global_navi_bar'));
	echo $cachehtml;
} 

// +------------------------------------------------------------------------------------------------------
// +-----------read & write------------
function readfromfile($file_name)
{
    if (file_exists($file_name)) {
		eval(load_hook('int_global_readfromfile'));
    	if (PHP_VERSION >= "4.3.0") return file_get_contents($file_name);
        $filenum = fopen($file_name, "r");
        flock($filenum, LOCK_SH);
        $file_data = @fread($filenum, @filesize($file_name));
        fclose($filenum);
        return $file_data;
    } 
} 
function writetofile($file_name, $data, $method = "w")
{
	global $usertype, $gl;
	
	eval(load_hook('int_global_writetofile'));
	
    $filenum = fopen($file_name, $method);
    flock($filenum, LOCK_EX);
    if ($usertype[22] == 1) {
    	if (!($file_data = fwrite($filenum, $data)) && !empty($data)) {
    		echo "<span style='color:red;font-weight:bold;'>$gl[514] $file_name</span>";
    	}
    } else {
    	$file_data = fwrite($filenum, $data);
    }
    fclose($filenum);
    return $file_data;
} 
function big_write($file_name, $buf, $length = -1) 
{
	global $usertype, $gl;
	eval(load_hook('int_global_big_write'));

    $ret = 0;
    
    if ($length < 0) $length = strlen($buf);
    
    $fd = @fopen($file_name, "r+");
    if (!$fd) $fd = @fopen($file_name, "w");
    if (!$fd) return $ret;

    flock($fd, LOCK_EX);
    fseek($fd, 0, SEEK_SET);
    if (@fwrite($fd, $buf, $length)) {
        ftruncate($fd, $length);
        $ret = 1;
    }elseif ($usertype[22] == 1 && $length > 0) {
    	echo "<span style='color:red;font-weight:bold;'>$gl[514] $file_name</span>";
    }
    flock($fd, LOCK_UN);
    fclose($fd); 
    return $ret;
}
// +------------------------------------------------------------------------------------------------------
// Check Permission of forum
function check_permission($user, $type)
{
    global $usertype, $login_status, $forum_admin, $detail;
	eval(load_hook('int_global_check_permission'));
    if ($type == 'forum' || $type == 'subforum') return 1;
    if ($login_status == 1 && ($type == 'former' || $type == 'subformer')) return 1;
    if ($login_status == 1 && ($type == 'forumhid' || $type == 'subforumhid')) return 1;
    if ($login_status == 1 && $usertype[22] == "1") return 1;
    if ($login_status == 1 && $usertype[24] == "1" && ($forum_admin && in_array($user, $forum_admin))) return 1;
    if ($login_status == 1 && $usertype[21] == "1") return 1;
    if ($login_status == 1 && $usertype[12] == "1" && ($type == "locked" || $type == "sublocked" || $type == "hidden" || $type == "subhidden")) return 1;
    return 0;
} 
// +------------------------------------------------------------------------------------------------------
// Get Forum Information from cache
function get_forum_info($nrow)
{
    global $info_forum, $levelgroupdata, $forum_name, $level_id, $plusug, $trashcount, $digestcount, $pincount, $todaypt, $forumscount, $sxfourmrow, $fcaterows, $lock_true, $ttopicnum, $a, $thisamount, $database_up, $clean_true, $aviewpost, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $modban_true, $p_read_post, $view_list, $groupname, $spusergroup, $canpost, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point, $login_status, $canreply, $canpoll, $canvote, $enter_this_forum, $max_post_length, $bmfcode_post, $max_upload_size, $upload_type_available, $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, $allow_forb_ub, $forum_icon, $uginfo, $nopostpic, $postdontadd, $forum_upid, $forum_type, $guestpost, $noheldtop, $forum_ford, $forum_cid, $forumid, $forumlist, $forum_style, $forum_pwd, $can_visual_post;

    for($i = 0;$i < $forumscount;$i++) {
        if ($sxfourmrow[$i]['id'] == $forumid) {
            $detail = $sxfourmrow[$i];
            break;
        } 
    } 
    
    $info_forum = $detail;

    $forum_name = $detail['bbsname'];
    $forum_icon = $detail['forum_icon'];
    $forum_upid = $detail['blad'];
    $forum_type = $detail['type'];
    $forum_style = $detail['filename'];
    $forum_pwd = $detail['forumpass'];
    $forum_cid = $detail['forum_cid'];
    $guestpost = explode("_", $detail['guestpost']);
    $noheldtop = $guestpost[2];
    $nopostpic = $guestpost[1];
    $guestpost = $guestpost[0];
    $forum_ford = $detail['forum_ford'];
    $postdontadd = $detail['postdontadd'];
    $spusergroup = $detail['spusergroup'];
    $aviewpost = $detail['naviewpost'];
    $thisamount = $detail['topicnum'] + $detail['replysnum'];
    $ttopicnum = $detail['topicnum'];
    $fcaterows = $detail['caterows'];
    $todaypt = $detail['todaypt'];
    $pincount = $detail['pincount'];
    $posttrashcount = $detail['posttrash'];
    $trashcount = $detail['trashcount'];
    $digestcount = $detail['digestcount'];
    
	eval(load_hook('int_global_get_forum_info'));

	if ($forum_type == "jump" || $forum_type == "subjump") {
		header("Location: {$info_forum['jumpurl']}");
		exit;
	}

    if ($spusergroup == "1") {
        global $usertype;
        if ($login_status == 0) $uginfo = 6;
        $usergroupdata = explode("\n", $detail['usergroup']);
        if ($uginfo == 4) {
        	include_once("datafile/cache/levels/level_fid_{$forumid}.php");
        	if ($levelgroupdata[$forumid][$level_id]) {
        		$usertype = explode("|", $levelgroupdata[$forumid][$level_id]);
        	} else {
        		$usertype = explode("|", $usergroupdata[$uginfo]);
        	}
        } else {
        	$usertype = explode("|", $usergroupdata[$uginfo]);
        }
        if (is_array($plusug)) {
            $count = count($plusug);
            for ($i = 0; $i < $count; $i++){
            	if ($plusug[$i] == "" || $plusug[$i] == $uginfo) continue;
                $tmpusertype = explode("|", $usergroupdata["{$plusug[$i]}"]);
                $countut = count($tmpusertype);
                for ($s = 0; $s < $countut; $s++) {
                	if ($tmpusertype[$s] == 1 && $usertype[$s] == 0) $usertype[$s] = 1; 
                }
            }
        }

        list($groupname, , , $canpost, $canreply, $canpoll, $canvote, , , , , , $enter_this_forum, , $max_post_length, , , , , $max_upload_size, $upload_type_available, , , , $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, , , , , , , , $p_read_post, $view_list, $lock_true, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $modban_true, $clean_true, , $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $bmfcode_post['pic'], $bmfcode_post['reply'], $bmfcode_post['jifen'], $bmfcode_post['sell'], $bmfcode_post['flash'], $bmfcode_post['mpeg'], $bmfcode_post['iframe'], $bmfcode_post['fontsize'], $bmfcode_post['hpost'], $bmfcode_post['hmoney'], $allow_forb_ub, $can_visual_post, $member_list, $search_fun, $nwpost_list, $porank_list, $gvf, $see_amuser, $view_recybin, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point) = $usertype;
    } 
} 
// +------------------------------------------------------------------------------------------------------
// +------------Display the Jump-page---------------
function jump_page($URL, $title, $content, $type = 1)
{
    global $bm_skin, $username, $usertype, $_SESSION, $verandproname, $otherimages, $bbs_title, $add_title, $cssinfo, $login_status, $usertype, $ads_select, $admin_email, $site_url, $showtime, $site_name, $writefilenum, $readfilenum, $begin_time, $temfilename, $gl, $plugyescolor;
    $jumppagetrue = "yes";
    if ($title) $add_title = " &gt; " . $title;
    require("newtem/$temfilename/global.php");
	eval(load_hook('int_global_jump_page'));
    echo $pgo[7].$pgo[$type];
    exit;
} 
// +------------------------------------------------------------------------------------------------------
// +---------Ban some IP&IDs---------------

function idbanned()
{
    global $username, $id_bannedmembers, $gl, $encode_info, $bannedip;
    if (!file_exists("datafile/idbans.php")) return;
    $id_bannedmembers = file("datafile/idbans.php");
    if (empty($id_bannedmembers)) return;
    $count = count($id_bannedmembers);
    for ($i = 0; $i < $count; $i++) {
        $bannedid = trim($id_bannedmembers[$i]);
        if (!$bannedid) continue;
        if ($username == $bannedid) {
			$no_allow = 1;
            break;
        } 
    } 
	eval(load_hook('int_global_idbanned'));

    
    if ($no_allow == 1) {
    	@header("Content-Type: $encode_info");
        print ($gl[378]);
        exit;
    }
} 
function ipbanned()
{
    global $ip, $term_bannedmembers, $gl, $encode_info, $bannedip;
    if (!file_exists("datafile/ipbans.php")) return;
    $term_bannedmembers = file("datafile/ipbans.php");
    if (empty($term_bannedmembers)) return;
    $count = count($term_bannedmembers);
	eval(load_hook('int_global_ipbanned'));
    for ($i = 0; $i < $count; $i++) {
        $bannedip = trim($term_bannedmembers[$i]);
        if (!$bannedip) continue;
        if (strpos($ip , $bannedip) === 0) {
        	@header("Content-Type: $encode_info");
            print ($gl[379]);
            exit;
        } 
    } 
} 
// +------------------------------------------------------------------------------------------------------
// +-----------User functions---------------------
function get_user_portait($usericon, $returnArr = false, $email = '')
{
	global $maxwidth, $maxheight, $bmfopt, $script_pos;
	
    $portait = explode('%', $usericon);
	eval(load_hook('int_global_get_user_portait'));
    if ($portait[1] && $portait[2] && $portait[3]) {
        $nameuri = urlencode($portait[1]);
        $nameuri = str_replace("%2F", "/", $nameuri);
        $nameuri = str_replace("%3A", ":", $nameuri);
        $nameuri = str_replace("%3F", "?", $nameuri);
        $nameuri = str_replace("%3F", "?", $nameuri);
        $nameuri = str_replace("%3D", "=", $nameuri);
        $nameuri = str_replace("%26", "&", $nameuri);
        $return = $returnArr === true ? array('url' => $nameuri, 'width' => $maxwidth, 'height' => $maxheight) : "<img src='$nameuri' width='$portait[2]' alt='' height='$portait[3]' border='0' />";
    } else {
    	if($bmfopt['gravatar'] && $email) {
    		$grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($script_pos."images/avatars/no_portait.gif")."&s=".$maxwidth;
        	$return = $returnArr === true ? array('url' => $grav_url, 'width' => $maxwidth, 'height' => $maxheight) : "<img src='$grav_url' alt='' border='0' />";
        } else {
        	$return = $returnArr === true ? array('url' => "images/avatars/no_portait.gif", 'width' => $maxwidth, 'height' => $maxheight) : "<img src='images/avatars/$portait[0]' alt='' border='0' />";
        }
    }
    return $return;
} 
// ----------- Get Members' Info ----------
function getUserInfo()
{
    global $username, $levelgroupdata, $plusug, $activestatus, $baoliu1, $lock_true, $userpoint, $gotNewMessage, $sguiresult, $runned, $userid, $userddata, $thisavarts, $foreuser, $hisipc, $clastupload, $uploadfiletoday, $hisipb, $hisipa, $database_up, $usergroupdata, $logonutnum, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $modban_true, $clean_true, $showpic, $p_read_post, $view_list, $allow_upload, $uginfo, $max_upload_post, $allow_forb_ub, $opencutusericon, $openupusericon, $max_avatars_upload_size, $max_avatars_upload_post, $upload_avatars_type_available, $delrmb, $post_money, $deljifen, $post_jifen, $del_true, $del_rec, $can_rec, $regdate, $post_sell_max, $html_codeinfo, $_SESSION, $_COOKIE, $postamount, $max_upload_num, $userbym, $usermoney, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point, $level, $canpost, $canreply, $canpoll, $canvote, $max_sign_length, $send_msg_max, $sign_use_bmfcode, $bmfcode_sign, $enter_tb, $send_msg, $max_post_length, $bmfcode_post, $short_msg_max, $use_own_portait, $swf, $max_upload_size, $upload_type_available, $id_unique, $usertype, $max_daily_upload_size, $logon_post_second, $can_visual_post, $member_list, $search_fun, $nwpost_list, $porank_list, $gvf, $see_amuser, $view_recybin;

	$userid = $_SESSION['bmfUsrId'] ? $_SESSION['bmfUsrId'] : $_COOKIE['bmfUsrId'];

    if ($userid != "") {
        if ($runned == 1) {
            $row = $sguiresult;
        } else {
            $runned = 1;
            $query = "SELECT * FROM {$database_up}userlist WHERE userid='$userid' LIMIT 0,1";
            $result = bmbdb_query($query);
            $sguiresult = bmbdb_fetch_array($result);
            $row = $sguiresult;
        } 
        $username = $row['username'];
        $gotNewMessage = $row['newmess'];
        $regdate = $row['regdate'];
        $thisavarts = $row['avarts'];
        $postamount = $row['postamount'];
        $userbym = $row['point'];
        $userpoint = floor($userbym / 10);
        $userid = $row['userid'];
        $usermoney = $row['money'];
        $usertype = $row['ugnum'];
        $baoliu1 = $row['baoliu1'];
        $activestatus = $row['activestatus'];
        $userddata = $row;

        $uploadfiletoday = $row['uploadfiletoday'];
        $clastupload = $row['lastupload'];
        $foreuser = $row['foreuser'];
        $hisipc = $row['hisipc'];
        $hisipb = $row['hisipb'];
        $hisipa = $row['hisipa'];
        
        $logonutnum = $uginfo = $usertype;
        

        if ($usertype == 4) {
        	global $level_id;
        	
        	include_once("datafile/cache/levels/level_fid_0.php");
        	$level = getUserLevel($postamount, $userbym, $username, $logonutnum);
        	$usertype = explode("|", $levelgroupdata[0][$level_id]); 
        } else {
        	$usertype = explode("|", $usergroupdata[$usertype]); 
        }
        
        if ($row['baoliu1'] && $row['baoliu1'] != ",") {
            $plusug = explode(",", $row['baoliu1']);
            $count = count($plusug);
            for ($i = 0; $i < $count; $i++){
            	if ($plusug[$i] == "" || $plusug[$i] == $uginfo) continue;
                $tmpusertype = explode("|", $usergroupdata["{$plusug[$i]}"]);
                $countut = count($tmpusertype);
                for ($s = 0; $s < $countut; $s++) { if ($tmpusertype[$s] == 1 && $usertype[$s] == 0) $usertype[$s] = 1; }
            }
        }
        
        list($groupname, $groupimg, $systemg, $canpost, $canreply, $canpoll, $canvote, $max_sign_length, $sign_use_bmfcode, $bmfcode_sign['pic'], $bmfcode_sign['flash'], $bmfcode_sign['fontsize'], $enter_tb, $send_msg, $max_post_length, $short_msg_max, $send_msg_max, $use_own_portait, $swf, $max_upload_size, $upload_type_available, $supermod, $admin, $groupimg2, $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, $opencutusericon, $openupusericon, $max_avatars_upload_size, $max_avatars_upload_post, $upload_avatars_type_available, , , $p_read_post, $view_list, $lock_true, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $modban_true, $clean_true, $showpic, $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $bmfcode_post['pic'], $bmfcode_post['reply'], $bmfcode_post['jifen'], $bmfcode_post['sell'], $bmfcode_post['flash'], $bmfcode_post['mpeg'], $bmfcode_post['iframe'], $bmfcode_post['fontsize'], $bmfcode_post['hpost'], $bmfcode_post['hmoney'], $allow_forb_ub, $can_visual_post, $member_list, $search_fun, $nwpost_list, $porank_list, $gvf, $see_amuser, $view_recybin, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point) = $usertype;
		eval(load_hook('int_global_getUserInfo'));
		
    } 
} 
function getLevelGroup($ugid, $usergroupdata, $forumid, $postamount, $userbym, $p_lid = "") 
{
	global $levelgroupdata, $level_id, $spusergroup;
	
	if ($spusergroup != 1) $forumid = 0;
	
	include_once("datafile/cache/levels/level_fid_$forumid.php");
	
	$forumid = $forumid ? $forumid : 0;

	if ($ugid == 4) {
       	if (!$p_lid) {
       		$level = getUserLevel($postamount, $userbym, "", $ugid);
       	} else $level_id = $p_lid;
       	
       	$author_type = explode("|", $levelgroupdata[$forumid][$level_id]); 

	} else {
		$author_type = explode("|", $usergroupdata[$ugid]);
	}
	eval(load_hook('int_global_getLevelGroup'));
	
	return $author_type;
}
// ------------- Members' Level Title
function getUserLevel($amount, $score, $username, $usertype = "")
{
    global $mtitle, $mgraphic, $modgraphic, $admingraphic, $supmodgraphic, $level_score_mode, $level_id, $level_score_php, $mpostmark, $countmtitle, $usergroupdata, $admintitle, $id_unique, $motitle, $supmotitle;
	include_once("datafile/usertitle.php");
    
    $level_id = 0;
    
    $usertype = explode("|", $usergroupdata[$usertype]);
    $user_level = $mtitle['a0'];
    
    if ($level_score_mode == 1) $amount = $score / 10;
    elseif ($level_score_mode == 2) {
    	$score = $score / 10;
        eval($level_score_php);
    }
    $count = $countmtitle;

    for ($i = 1; $i < $count; $i++) {
        $ia = "a" . $i;
        if ($mpostmark[$ia] && $amount >= $mpostmark[$ia]) {
        	$user_level = $mtitle[$ia];
        	$level_id = $i;
        }
    } 
    
	if ($motitle && $usertype[24] == "1") $user_level = $motitle;
	if ($supmotitle && $usertype[21] == "1") $user_level = $supmotitle;
	if ($admintitle && $usertype[22] == "1") $user_level = $admintitle;
	eval(load_hook('int_global_getUserLevel'));

    return $user_level;
} 
// ------------------ Members' Level Picture
function getUserIcon($amount, $score, $username, $usertype = "")
{
    global $mgraphic, $motitle, $supmotitle, $admintitle, $level_score_mode, $level_score_php, $mpostmark, $countmtitle, $usergroupdata, $admingraphic, $id_unique, $modgraphic, $supmodgraphic;
	include_once("datafile/usertitle.php");

    $usertype = explode("|", $usergroupdata[$usertype]);
    $usericon = $mgraphic['a0'];
    
    if ($level_score_mode == 1) $amount = $score / 10;
    elseif ($level_score_mode == 2) {
    	$score = $score / 10;
        eval($level_score_php);
    }
    
    $count = $countmtitle;
    for ($i = 1; $i < $count; $i++) {
        $ia = "a" . $i;
        if ($mpostmark[$ia] && $amount >= $mpostmark[$ia]) $usericon = $mgraphic[$ia];
    } 
    
	if ($modgraphic && $usertype[24] == "1") $usericon = $modgraphic;
	if ($supmodgraphic && $usertype[21] == "1") $usericon = $supmodgraphic;
	if ($admingraphic && $usertype[22] == "1") $usericon = $admingraphic; 
	eval(load_hook('int_global_getUserIcon'));

    return $usericon;
} 
// ---------------- Get Members' Info From Database
function get_user_info($user, $type = "username")
{
    global $database_up, $userinfocache, $userinfocache_id;
    if (empty($user)) return;
    if ($type == "username") {
        $query = "SELECT * FROM {$database_up}userlist WHERE username='$user' LIMIT 0,1";
    } elseif ($type == "usrid") {
        $query = "SELECT * FROM {$database_up}userlist WHERE userid='$user' LIMIT 0,1";
    } 
    
    if ((!is_array($userinfocache[$user]) && $type == "username") || (!is_array($userinfocache_id[$user]) && $type == "usrid")) {
        $result = bmbdb_query($query, 0);
        $userinfo = bmbdb_fetch_array($result);
        $userinfocache_id["{$userinfo['userid']}"] = $userinfo;
        $userinfocache["{$userinfo['username']}"] = $userinfo;
    } else {
        if (is_array($userinfocache[$user]) && $type == "username") $userinfo = $userinfocache[$user];
        elseif (is_array($userinfocache_id[$user]) && $type == "usrid")  $userinfo = $userinfocache_id[$user];
    } 
	eval(load_hook('int_global_get_user_info'));
    return $userinfo;
} 
// --------------- Check if member is exists.
function check_user_exi($user, $nametype = 0)
{
    global $database_up;
    if (empty($user)) return 0;
    $query = "SELECT COUNT(*) FROM {$database_up}userlist WHERE ".($nametype == 1 ? "userid" : "username")."='$user'  LIMIT 0,1";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $row = $fcount['COUNT(*)'];
	eval(load_hook('int_global_check_user_exi'));
    return $row;
} 
// ------------- Check the member if online
function check_online($username)
{
    global $new_online_user, $online_limit, $timestamp;

    if (empty($new_online_user)) $new_online_user = readfromfile("datafile/online.php");
	eval(load_hook('int_global_check_online'));
    if (strstr($new_online_user ,"//|$username")) return 1;
    else return 0;
} 
// ----------------- Check Name and Password
function checkauth($userid, $auth)
{
	global $bmfopt;
	
	$userinfo = getSingleUserInfo('', $userid);
	if ($auth == makeauth($userinfo['salt'], $bmfopt['sitekey'], $userinfo['pwd'])) {
		return 1;
	} else {
		return 0;
	}
}
function makeauth($salt, $sitekey, $password) 
{
	return md5($salt.md5($password.$sitekey));
}
function checkpass($username, $password, $userid = "")
{
	$userinfo = getSingleUserInfo($username, $userid);
	eval(load_hook('int_global_checkpass'));
    
    if ($password == $userinfo['pwd']) {
    	return 1;
    } else {
    	return 0;
    }
}
function getSingleUserInfo($username, $userid = "") 
{
    global $database_up, $userinfocache, $userinfocache_id, $user_check_id;

    if ((!is_array($userinfocache[$username]) && !$userid) || (!is_array($userinfocache_id[$userid]) && $userid)) {
    	if ($userid) {
    		$query = "SELECT * FROM {$database_up}userlist WHERE userid='$userid' LIMIT 0,1";
    	} else {
    		$query = "SELECT * FROM {$database_up}userlist WHERE username='$username' LIMIT 0,1";
    	}
    	
        $result = bmbdb_query($query);
        $userinfo = bmbdb_fetch_array($result);
        $user_check_id = $userinfo['userid'];
        $userinfocache_id[$user_check_id] = $userinfo;
        $userinfocache[$username] = $userinfo;
    } else {
    	if ($username) {
    		$userinfo = $userinfocache[$username];
    	} else {
    		$userinfo = $userinfocache_id[$userid];
    	}
        $user_check_id = $userinfo['userid'];
    } 
	eval(load_hook('int_global_checkpass'));
	return $userinfo;
}
// +-------------Online Functions------------------
// -----Add members to online file--------
function add_online($nodelay = 0)
{
    global $privacy, $rcordosbr, $new_online_user, $_SESSION, $logonutnum, $_COOKIE, $browseinfo, $osinfo, $username, $timestamp, $ip, $online_limit, $gl, $filename, $forumid;
    if (defined('onlinedelay') && $nodelay != 1) return;
    $onlinefile = "datafile/online.php";
    if ($_COOKIE['privacybym']) $prioption = "yes";

    if ($username != "") {
        if (!$forumid || strlen($forumid)>15) $forumid = 0;
        if (file_exists($onlinefile)) {
            $online_user = file($onlinefile);
            $online_user_content = implode("", $online_user);
            // find the member information line number
            $online_user_count = count($online_user);
            $find_this_user = stristr($online_user_content, "|$username|");
            $below_count = count(explode("\n", $find_this_user));
            $member_line = $online_user_count - $below_count + 1;
            unset($online_user[$member_line]);

            for($i = 0; $i < $online_user_count; $i++) {
                $online_user_detail = explode("|", $online_user[$i]);
                if ($timestamp - $online_user_detail[2] <= $online_limit && $online_user_detail[2] <= $timestamp) break;
                    else unset($online_user[$i]);
            } 
            if ($rcordosbr) {
                $browseinfo = browseinfo();
                $osinfo = osinfo();
            } 
            
            $online_user[]= "<?php //$prioption|$username|$timestamp|$ip|f|$forumid|$browseinfo|$osinfo|t|$filename|$logonutnum|$prioption|\n";
            $new_online_user = implode("", $online_user);

        } else {
            if ($rcordosbr) {
                $browseinfo = browseinfo();
                $osinfo = osinfo();
            } 
            $new_online_user = "<?php //$prioption|$username|$timestamp|$ip|f|$forumid|$browseinfo|$osinfo|t|$filename|$logonutnum|$prioption|\n";
        } 
        big_write($onlinefile, $new_online_user);
    } 
	eval(load_hook('int_global_add_online'));
} 
// +-----------add guests to online file-----------------
function add_guest($nodelay = 0)
{
    global $timestamp, $usertype, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point, $ip, $rcordosbr, $usergroupdata, $browseinfo, $logonutnum, $gl, $osinfo, $online_limit, $filename, $forumid, $groupname, $groupimg, $systemg, $canpost, $canreply, $canpoll, $canvote, $max_sign_length, $sign_use_bmfcode, $bmfcode_sign, $enter_tb, $send_msg, $max_post_length, $bmfcode_post, $short_msg_max, $send_msg_max, $use_own_portait, $swf, $member_list, $search_fun, $nwpost_list, $porank_list, $gvf, $see_amuser, $view_recybin, $max_upload_size, $upload_type_available, $supermod, $admin, $groupimg2, $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, $opencutusericon, $openupusericon, $max_avatars_upload_size, $max_avatars_upload_post, $upload_avatars_type_available, $p_read_post, $view_list, $lock_true, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $modban_true, $clean_true, $showpic, $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $can_visual_post;

    if (defined('onlinedelay') && $nodelay != 1) return;
    
    $onlinefile = "datafile/guest.php";
    if (!$forumid || strlen($forumid)>15) $forumid = 0;
    if (file_exists($onlinefile)) {
        $online_user = file($onlinefile);
        $online_user_content = implode("", $online_user);
        // find the guest information line number
        $online_user_count = count($online_user);
        $find_this_user = stristr($online_user_content, "|$ip|");
        $below_count = count(explode("\n", $find_this_user));
        $member_line = $online_user_count - $below_count + 1;

        unset($online_user[$member_line]);

        for($i = 0; $i < $online_user_count; $i++) {
            $online_user_detail = explode("|", $online_user[$i]);
            if ($timestamp - $online_user_detail[2] <= $online_limit && $online_user_detail[2] <= $timestamp) break;
                else unset($online_user[$i]);
        } 
        
        if ($rcordosbr) {
            $browseinfo = browseinfo();
            $osinfo = osinfo();
        } 
        
        $online_user[]= "<?php //|$gl[174]|$timestamp|$ip|f|$forumid|$browseinfo|$osinfo|t|$filename|\n";
        $new_online_user = implode("", $online_user);
    } else {
        if ($rcordosbr) {
            $browseinfo = browseinfo();
            $osinfo = osinfo();
        } 
        $new_online_user = "<?php //|$gl[174]|$timestamp|$ip|f|$forumid|$browseinfo|$osinfo|t|$filename|";
    } 
    $logonutnum = 6;
    $usertype = explode("|", $usergroupdata[6]);
    list($groupname, $groupimg, $systemg, $canpost, $canreply, $canpoll, $canvote, $max_sign_length, $sign_use_bmfcode, $bmfcode_sign['pic'], $bmfcode_sign['flash'], $bmfcode_sign['fontsize'], $enter_tb, $send_msg, $max_post_length, $short_msg_max, $send_msg_max, $use_own_portait, $swf, $max_upload_size, $upload_type_available, $supermod, $admin, $groupimg2, $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, $opencutusericon, $openupusericon, $max_avatars_upload_size, $max_avatars_upload_post, $upload_avatars_type_available, , , $p_read_post, $view_list, $lock_true, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $modban_true, $clean_true, $showpic, $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $bmfcode_post['pic'], $bmfcode_post['reply'], $bmfcode_post['jifen'], $bmfcode_post['sell'], $bmfcode_post['flash'], $bmfcode_post['mpeg'], $bmfcode_post['iframe'], $bmfcode_post['fontsize'], $bmfcode_post['hpost'], $bmfcode_post['hmoney'], $allow_forb_ub, $can_visual_post, $member_list, $search_fun, $nwpost_list, $porank_list, $gvf, $see_amuser, $view_recybin, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point) = $usertype;
    big_write($onlinefile, $new_online_user);
	eval(load_hook('int_global_add_guest'));
} 
// +------------------------------------------------------------------------------------------------------
// +----------kick out the dangerous Char---------
function safe_convert($d, $replace = 0, $visual = 0)
{
    global $html_codeinfo, $openhtmlcode;
    if ($html_codeinfo == "yes" && $openhtmlcode != "checkbox") {
        $d = str_replace("&lt;", "&bmbamplt;", $d);
        $d = str_replace("&gt;", "&bmbampgt;", $d);
    }
    $d = htmlspecialchars($d);
	
	if ($visual == 1) $d = str_replace("&amp;", "&", $d);
	
    $d = str_replace("&#", "&amp;#", $d);
    $d = str_replace("'", "&#039;", $d);
    $d = str_replace('"', "&quot;", $d);
    $d = str_replace("\t", "", $d);
    if ($html_codeinfo == "yes" && $openhtmlcode != "checkbox") {
    	if ($visual == 0) $d = str_replace("&amp;", "&", $d);
    	if ($replace == 0) {
            $d = str_replace("&#039;", "'", $d);
            $d = str_replace("&quot;", '"', $d);
            $d = str_replace("&quot;", "\"", $d);
	    }
        $d = str_replace("&lt;", "<", $d);
        $d = str_replace("&gt;", ">", $d);
        $d = str_replace("&bmbamplt;", "&lt;", $d);
        $d = str_replace("&bmbampgt;", "&gt;", $d);
        // for AJAX, replace special chars.
        $d = str_replace('bmbajaxpagebar', '_bmbajaxpagebar', $d);
    }
    $d = str_replace("\r", "<br />", $d);
    $d = str_replace("\n", "", $d);
    $d = str_replace("  ", " &nbsp;", $d);
	eval(load_hook('int_global_safe_convert'));
    return $d;
} 
function restore_post_htmt($orcontent) 
{
	global $html_codeinfo, $openhtmlcode;
	
    $orcontent = str_replace("&#039;", "'", $orcontent);
    $orcontent = str_replace("&quot;", '"', $orcontent);
    $orcontent = str_replace("&quot;", "\"", $orcontent);
    if (!($html_codeinfo == "yes" && $openhtmlcode != "checkbox")) {
    	$orcontent = str_replace("&lt;", "<", $orcontent);
    	$orcontent = str_replace("&amp;", "&", $orcontent);
    	$orcontent = str_replace("&gt;", ">", $orcontent);
    }
	eval(load_hook('int_global_restore_post_htmt'));
    
    return $orcontent;
}
// +------------------------------------------------------------------------------------------------------
// +-------To see where the IP comes from------------------
function bmfwwz($user, $rmb, $jifen, $postn, $deltopic = "0", $delreply = "0" ,$mode = 0)
{
    global $database_up;
    if ($mode == 0) {
        $sqladd = "WHERE username='$user'";
    } else {
        $sqladd = "WHERE userid='$user'";
    }
    $nquery = "UPDATE {$database_up}userlist SET delreply=delreply+'$delreply',deltopic=deltopic+'$deltopic',postamount=postamount+'$postn',point=point+'$jifen',money=money+'$rmb' $sqladd";
	eval(load_hook('int_global_bmfwwz'));
    $result = bmbdb_query($nquery);
} 
// +--------------To tell the user's Browser--------------------------
function browseinfo()
{
    $browser = "";
    $browserver = "";
    $Browsers = array("Lynx", "MOSAIC", "AOL", "Opera", "JAVA", "MacWeb", "WebExplorer", "OmniWeb");
    $Agent = trim($_SERVER["HTTP_USER_AGENT"]);
    for ($i = 0; $i <= 7; $i++) {
        if (strpos($Agent, $Browsers[$i])) {
            $browser = $Browsers[$i];
            $browserver = "";
        } 
    } 
    if (stripos($Agent, "Mozilla") !== false && !stripos($Agent, "Netscape") === false) {
        $temp = explode("(", $Agent);
        $Part = $temp[0];
        $S_Part = $temp[1];
        $temp = explode("/", $Part);
        $browserver = $temp[1];
        if (strstr($S_Part, "http://")) {
            $AS_Part = explode(")", $S_Part);
            $AS_Part_2 = explode(";", $AS_Part[0]);
            $browser = $AS_Part_2[1];
        } else {
            $temp = explode(";", $S_Part);
            $temp = explode(")", $temp[1]);
            $browser = $temp[0];
        } 
    } 
    if (stripos($Agent, "Mozilla") !== false && stripos($Agent, "Opera") !== false) {
        $temp = explode("(", $Agent);
        $Part = $temp[1];
        $temp = explode(")", $Part);
        $browserver = $temp[1];
        $temp = explode(" ", $browserver);
        $browserver = $temp[2];
        $browserver = preg_replace("/([\d\.]+)/", "\\1", $browserver);
        $browser = "Opera";
    } 
    if (stripos($Agent, "Firefox") !== false) {
        $temp = explode("Firefox/", $Agent);
        $browserver = $temp[1];
        $browser = "Firefox";
    } 
    if (stripos($Agent, "Safari") !== false) {
        $temp = explode("Version/", $Agent);
        $temp = explode("Safari", $temp[1]);
        $browserver = $temp[0];
        $browser = "Safari";
    } 
    if (stripos($Agent, "Chrome") !== false) {
        $browser = "Google Chrome";
    } 
    if (stripos($Agent, "Edge/") !== false) {
        $browser = "Microsoft Edge";
    } 
    if (stripos($Agent, "Mozilla") !== false && stripos($Agent, "MSIE") !== false && !stripos($Agent, "Opera") === false) {
        $temp = explode("(", $Agent);
        $Part = $temp[1];
        $temp = explode(";", $Part);
        $Part = $temp[1];
        $temp = explode(" ", $Part);
        $browserver = $temp[2];
        $browserver = preg_replace("/([\d\.]+)/", "\\1", $browserver);
        $browser = "Internet Explorer";
    } 
    if ($browser != "") {
        $browseinfo = "$browser $browserver";
    } else {
        $browseinfo_t = explode("(", $Agent);
        $browseinfo = $browseinfo_t[0];
    } 
    $browseinfo = str_replace('|', '', safe_convert($browseinfo));
    if (strlen($browseinfo)>50) $browseinfo = substr($browseinfo, 0, 20);
	eval(load_hook('int_global_browseinfo'));
    return $browseinfo;
} 
// -----------------To tell the User's OS------------------------
function osinfo()
{
    $os = "";
    $Agent = trim($_SERVER["HTTP_USER_AGENT"]);
    if (stripos($Agent, 'win') !== false && strpos($Agent, ' 95')) {
        $os = "Windows 95";
    } elseif (stripos($Agent, 'win 9x') !== false && strpos($Agent, '4.90')) {
        $os = "Windows ME";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, ' 98') !== false) {
        $os = "Windows 98";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, 'nt 5\.0') !== false) {
        $os = "Windows 2000";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, 'nt 5\.1') !== false) {
        $os = "Windows XP";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, 'nt 5\.2') !== false) {
        $os = "Windows 2003";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, 'nt 6\.0') !== false) {
        $os = "Windows Vista";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, 'nt 6\.1') !== false) {
        $os = "Windows 7";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, 'nt 6\.2') !== false) {
        $os = "Windows 8";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, 'nt 6\.3') !== false) {
        $os = "Windows 8.1";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, 'nt 10') !== false) {
        $os = "Windows 10";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, 'nt') !== false) {
        $os = "Windows NT";
    } elseif (stripos($Agent, 'win') !== false && stripos($Agent, '32') !== false) {
        $os = "Windows 32";
    } elseif (stripos($Agent, 'linux') !== false) {
        $os = "Linux";
    } elseif (stripos($Agent, 'unix') !== false) {
        $os = "Unix";
    } elseif (stripos($Agent, 'sun') !== false && stripos($Agent, 'os') !== false) {
        $os = "SunOS";
    } elseif (stripos($Agent, 'ibm') !== false && stripos($Agent, 'os') !== false) {
        $os = "IBM OS/2";
    } elseif (stripos($Agent, 'Mac') !== false && stripos($Agent, 'PC') !== false) {
        $os = "Macintosh";
    } elseif (stripos($Agent, 'PowerPC') !== false) {
        $os = "PowerPC";
    } elseif (stripos($Agent, 'AIX') !== false) {
        $os = "AIX";
    } elseif (stripos($Agent, 'HPUX') !== false) {
        $os = "HPUX";
    } elseif (stripos($Agent, 'NetBSD') !== false) {
        $os = "NetBSD";
    } elseif (stripos($Agent, 'BSD') !== false) {
        $os = "BSD";
    } elseif (stripos($Agent, 'OSF1') !== false) {
        $os = "OSF1";
    } elseif (stripos($Agent, 'IRIX') !== false) {
        $os = "IRIX";
    } elseif (stripos($Agent, 'FreeBSD') !== false) {
        $os = "FreeBSD";
    } 
    if ($os == '') {
        $os_t = explode("(", $Agent);
        $os = $os_t[0];
    } 
    $os = str_replace('|', '', safe_convert($os));
    
    if (strlen($os)>50) $os = substr($os, 0, 20);
	eval(load_hook('int_global_osinfo'));
    return $os;
} 
// +----------------------------------------------------------------------
// +--------Date & Time Functions-----------------------------------------
// ------- GMT is base time - Full Date
function get_date_chi($datetime)
{
    global $minoffset, $time_1, $time_f;
    $datetime = $datetime + ($time_1 * 60 * 60);
	eval(load_hook('int_global_get_date_chi'));
    return gmdate("$time_f ", $datetime);
} 
// ------ xxxx Minutes ago. Later time.
function get_add_date($datetime)
{
    global $time_1, $gl;
    
    $datetime = $datetime + ($time_1 * 60 * 60);
    $timetmp_a = explode("|", gmdate("j|s|i|G|n", $datetime));
    $mday = $timetmp_a[0] - 1;
    $seconds = $timetmp_a[1] + 0;
    $minutes = $timetmp_a[2] + 0;
    $hours = $timetmp_a[3] - $time_1;
    $mon = $timetmp_a[4];

    if ($seconds < 60 && empty($minutes) && empty($hours) && empty($mday) && $mon < 2) {
        $date_return = "1 $gl[444]";
    } elseif ($minutes < 60 && empty($hours) && empty($mday) && $mon < 2) {
        $date_return = "$minutes $gl[444]";
    } elseif ($hours < 24 && empty($mday) && $mon < 2) {
        $date_return = "$hours $gl[445]";
    } elseif ($mday < 7 && $mon < 2) {
        $date_return = "$mday $gl[446]";
    } elseif ($mday < 14 && $mday >= 7 && $mon < 2) {
        $date_return = "1 $gl[447]";
    } elseif ($mday < 21 && $mday >= 14 && $mon < 2) {
        $date_return = "2 $gl[447]";
    } elseif ($mday < 28 && $mday >= 21 && $mon < 2) {
        $date_return =  "3 $gl[447]";
    } else {
        $date_return = "getfulldate";
    } 
	eval(load_hook('int_global_get_add_date'));
	return $date_return;
} 
// ------- Full date and time
function getfulldate($datetime)
{
    global $minoffset, $time_1, $time_f;
    $datetime = intval($datetime) + intval($time_1 * 60 * 60);
	eval(load_hook('int_global_getfulldate'));
    return gmdate("$time_f H:i", $datetime);
} 
// ------- Full Time
function get_time($datetime)
{
    global $minoffset, $time_1;
    $datetime = intval($datetime) + intval($time_1 * 60 * 60);
	eval(load_hook('int_global_get_time'));
    return gmdate("H:i", $datetime);
} 
// Full Date
function get_date($datetime)
{
    global $minoffset, $time_1, $time_f;
    $datetime = intval($datetime) + intval($time_1 * 60 * 60);
	eval(load_hook('int_global_get_date'));
    return gmdate("$time_f", $datetime);
} 
// -------- Check the post Hidden info
function checkhiden($matches, $type = "")
{
    global $code4, $username, $userbym, $postamount, $usermoney, $qbgcolor, $id_unique, $usertype, $login_status, $author, $check_sup, $forum_admin1, $otherimages, $bcode_post;
  	$code = $matches[1];
    $code4 = 0;
    if ($bcode_post['jifen'] || ($type == "hpost" && $bcode_post['hpost']) || ($type == "hmoney" && $bcode_post['hmoney'])) {
        $code = stripslashes($code);
        $code = trim($code);
        if (preg_match("/^[0-9]{1,}$/", $code)) {
            if ($login_status == 1) {
                if ($type == "hpost") {
                    $codeww = $postamount;
                } elseif ($type == "hmoney") {
                    $codeww = $usermoney;
                } else {
                    $codeww = floor($userbym / 10);
                } 
                if ($codeww >= $code || $username == $author || $usertype[22] == "1" || $usertype[21] == "1" || $check_sup == 1 || ($forum_admin1 && in_array($username, $forum_admin1))) $code4 = 1;
                else $code4 = 0;
            } else {
                $code4 = 0;
            } 
        } else {
            $code4 = 1;
        } 
    } 
	eval(load_hook('int_global_checkhiden'));
} 
// ---- Check if the post need purchase and list the buyer lis
function checkpaid($matches)
{
    global $username, $code14, $line, $bmfcode_post, $userid, $username, $login_status, $author, $usertype, $check_sup, $forum_admin1, $articlelist, $qbgcolor, $id_unique, $otherimages;
    if ($bmfcode_post['sell']) {
    	$code14 = 0;
        $buyeres = explode(',', $line['sellbuyer']);
        if ($username == $author || in_array($userid, $buyeres)) $code14 = 1;
    } else {
        $code14 = 1;
    } 
	eval(load_hook('int_global_checkpaid'));
} 
// ---- Check if the post need replys and list the repliers lis
function checkpost($matches)
{
    global $username, $code1, $userid, $replyerlist, $bmfcode_post, $username, $login_status, $author, $usertype, $check_sup, $forum_admin1, $articlelist, $qbgcolor, $id_unique, $otherimages;
    if ($bmfcode_post['reply']) {
        if ($login_status == 1 && ($username == $author || $usertype[22] == "1" || $usertype[21] == "1" || $check_sup == 1 || ($forum_admin1 && in_array($username, $forum_admin1)) || ($replyerlist && in_array($userid, $replyerlist)))) $code1 = 1;
        else $code1 = 0;
    } else {
        $code1 = 1;
    } 
	eval(load_hook('int_global_checkpost'));
} 

function error_page($info, $title, $msg_i, $msg_t, $nav_t = "", $nofooter = 0)
{
    global $closeforum, $spusergroup, $bm_skin, $footer_copyright, $headername, $footername, $read_alignment, $styleidcode, $html_lang, $enter_allow_ww, $enter_this_forum, $userpoint, $enter_tb, $plugyescolor, $usertype, $forum_type, $forumid, $username, $login_status, $limitlist, $forum_admin, $verandproname, $otherimages, $bbs_title, $add_title, $cssinfo, $login_status, $ads_select, $admin_email, $site_url, $showtime, $site_name, $writefilenum, $readfilenum, $begin_time, $temfilename, $gl;
	eval(load_hook('int_global_error_page'));
    require("header.php");
	navi_bar($info, $title, $nav_t);
	msg_box($msg_i, $msg_t);
	if ($nofooter == 0) {
		if (defined("USERCP_LOGGED")) {
			$lang_zone = array("bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
			$template_name['usercp_foot'] = newtemplate("usercp_foot", $temfilename, $styleidcode, $lang_zone);
			require($template_name['usercp_foot']);
		}
		require("footer.php");
		exit;
	}
}
function tbuser()
{
    global $closeforum, $spusergroup, $footer_copyright, $headername, $footername, $read_alignment, $html_lang, $enter_allow_ww, $enter_this_forum, $userpoint, $enter_tb, $plugyescolor, $usertype, $forum_type, $forumid, $username, $login_status, $limitlist, $forum_admin, $verandproname, $otherimages, $bbs_title, $add_title, $cssinfo, $login_status, $ads_select, $admin_email, $site_url, $showtime, $site_name, $writefilenum, $readfilenum, $begin_time, $temfilename, $gl;

    $limitlist = explode("|", $closeforum);
    $closecount = count($limitlist);
    for ($cf = 0;$cf < $closecount;$cf++) {
        if ($forumid == $limitlist[$cf] && $limitlist[$cf] <> "") {
            $thisforum = "limit";
        } 
    } 
	eval(load_hook('int_global_tbuser'));

    if ($thisforum == "limit") {
        $qqmm = 0;
        if ($usertype[21] == "1") $qqmm = 1;

        if ($login_status != 1 || $qqmm != 1) {
            require("datafile/pluginheader.php");
            require("newtem/". $headername);
            navi_bar($gl[382], $gl[383]);
            msg_box($gl[383], $gl[384]);
            require("newtem/". $footername);
            exit;
        } 
    } 
    // +--------forum type-----------------------------------------
    get_forum_info("");

    if ($forum_type == "locked" || $forum_type == "former" || $forum_type == "forumhid" || $forum_type == "subforumhid" || $forum_type == "closed" || $forum_type == "hidden" || $forum_type == "sublocked" || $forum_type == "subformer" || $forum_type == "subclosed" || $forum_type == "subhidden") {
        if ($forum_type == "locked" || $forum_type == "sublocked") {
            $bar1 = "$gl[385]";            $bar2 = "$gl[386]";            $error = "$gl[387]";
        } elseif ($forum_type == "former" || $forum_type == "subformer" || $forum_type == "forumhid" || $forum_type == "subforumhid") {
            $bar1 = "$gl[388]";            $bar2 = "$gl[389]";            $error = "$gl[390]";
        } elseif ($forum_type == "closed" || $forum_type == "subclosed") {
            $bar1 = "$gl[391]";            $bar2 = "$gl[392]";            $error = "$gl[393]";
        } elseif ($forum_type == "hidden" || $forum_type == "subhidden") {
            $bar1 = "$gl[394]";            $bar2 = "$gl[395]";            $error = "$gl[396]";
        } 
        $qqmm = 0;

        if ($usertype[24] == "1" && $login_status == 1 && $forum_admin && in_array($username, $forum_admin)) $qqmm = 1;
        if ($usertype[12] == "1" && $forum_type == "locked")    $qqmm = 1;
        if ($usertype[12] == "1" && $forum_type == "sublocked")    $qqmm = 1;
        if ($usertype[22] == "1" && $forum_type == "locked")    $qqmm = 1;
        if ($usertype[22] == "1" && $forum_type == "sublocked")    $qqmm = 1;
        if ($usertype[22] == "1" && $forum_type == "hidden")    $qqmm = 1;
        if ($usertype[22] == "1" && $forum_type == "subhidden")    $qqmm = 1;
        if ($usertype[12] == "1" && $forum_type == "hidden")   $qqmm = 1;
        if ($usertype[12] == "1" && $forum_type == "subhidden")   $qqmm = 1;
        // ************
        
        if (($login_status == 1 && $forum_type == "subforumhid") || ($login_status == 1 && $forum_type == "forumhid") || ($login_status == 1 && $forum_type == "former") || ($login_status == 1 && $forum_type == "subformer")) $qqmm = 1;
        if ($forum_type == "closed" || $forum_type == "subclosed") $qqmm = 0;
        if ($usertype[22] == "1" || $usertype[21] == "1") $qqmm = 1;

        if (($enter_tb != "1" || $userpoint < $enter_allow_ww) && ($forum_type == "locked" || $forum_type == "sublocked" || $forum_type == "subhidden" || $forum_type == "hidden")) $qqmm = 0;
        if ($enter_this_forum == "1" && $userpoint >= $enter_allow_ww && $spusergroup == 1) $qqmm = 1;
        if ($login_status != 1 || $qqmm != 1) {
            require("datafile/pluginheader.php");

            require("newtem/". $headername);
            navi_bar($bar1, $bar2);
            msg_box($bar2, $error."<br/><br/>".$gl[399]);
            require("newtem/". $footername);
            exit;
        } 
    } 
} 
// ---- process astrology
function astrology($m, $d)
{
    global $gl;
    if ($m == 1 && $d <= 19) $y = 1982;
    else $y = 1981;
    $yours = mktime(0, 0, 0, $m, $d, $y);
    if ($yours >= 348768000 && $yours < 351360000) $n = "<img src=\"images/star/z11.gif\"  width=\"16\" alt=\"$gl[400]\" align=\"middle\" />";
    if ($yours >= 351360000 && $yours < 353952000) $n = "<img src=\"images/star/z12.gif\"  width=\"16\" alt=\"$gl[401]\" align=\"middle\" />";
    if ($yours >= 353952000 && $yours < 356544000) $n = "<img src=\"images/star/z1.gif\"  width=\"16\" alt=\"$gl[402]\" align=\"middle\" />";
    if ($yours >= 356544000 && $yours < 359222400) $n = "<img src=\"images/star/z2.gif\"  width=\"16\" alt=\"$gl[403]\" align=\"middle\" />" ;
    if ($yours >= 359222400 && $yours < 361987200) $n = "<img src=\"images/star/z3.gif\"  width=\"16\" alt=\"$gl[404]\" align=\"middle\" />";
    if ($yours >= 361987200 && $yours < 364665600) $n = "<img src=\"images/star/z4.gif\"  width=\"16\" alt=\"$gl[405]\" align=\"middle\" />";
    if ($yours >= 364665600 && $yours < 367344000) $n = "<img src=\"images/star/z5.gif\"  width=\"16\" alt=\"$gl[406]\" align=\"middle\" />";
    if ($yours >= 367344000 && $yours < 370022400) $n = "<img src=\"images/star/z6.gif\"  width=\"16\" alt=\"$gl[407]\" align=\"middle\" />";
    if ($yours >= 370022400 && $yours < 372700800) $n = "<img src=\"images/star/z7.gif\"  width=\"16\" alt=\"$gl[408]\" align=\"middle\" />";
    if ($yours >= 372700800 && $yours < 375206400) $n = "<img src=\"images/star/z8.gif\"  width=\"16\" alt=\"$gl[409]\" align=\"middle\" />";
    if ($yours >= 375206400 && $yours < 377798400) $n = "<img src=\"images/star/z9.gif\"  width=\"16\" alt=\"$gl[410]\" align=\"middle\" />";
    if ($yours >= 377798400 && $yours <= 380304000) $n = "<img src=\"images/star/z10.gif\" width=\"16\" alt=\"$gl[411]\" align=\"middle\" />";
	eval(load_hook('int_global_astrology'));
    return $n;
} 
// ----- process national info
function nationalget($nationalname, $nameonly = 0)
{
    global $language, $show_form_lng, $nationalarray;
    include_once("lang/$language/usercp.php");
    include_once("include/nationallist.php");
    
    $nationalnametemp = $nationalarray["$nationalname"];
    if ($nationalnametemp == "") $nationalname = "blank";
    $nationalname = $nameonly ? $nationalnametemp : "<img src=\"images/flags/{$nationalname}.gif\" height=\"14\" alt=\"{$nationalnametemp}\" align=\"middle\" />";
	eval(load_hook('int_global_nationalget'));
    return $nationalname;
} 
// ----- chinese shengxiao
function robertsx($y, $m, $d)
{
    global $gl;

    $sx_detail = explode("|", "218|206|126|214|203|123|211|201|220|208|128|216|205|124|213|202|123|210|130|217|206|126|214|204|124|211|131|219|208|127|215|205|125|213|202|122|210|129|217|206|127|214|203|124|212|131|218|208|128|215|205|125|213|202|121|209|130|217|206|127|215|203|123|211|131|218|207|128|216|205|125|213|202|220|209|129|217|206|127|215|204|123|210|131|219|207|128|216|205|124|212|201|122|209|129|218|207|126|214|");
    $mymd = $m * 100 + $d;
    $t = $y-1912;
    $tarmd = $sx_detail[$t];
    if ($mymd < $tarmd) $y = $y-1;
    $myy = ($y-1912) % 12;
    if ($myy == 0) $mysx = "<img src=\"images/sx/sx1s.gif\"  width=\"15\" alt\"=$gl[412]\" align=\"middle\" />";
    elseif ($myy == 1) $mysx = "<img src=\"images/sx/sx2s.gif\"  width=\"15\" alt=\"$gl[413]\" align=\"middle\" />";
    elseif ($myy == 2) $mysx = "<img src=\"images/sx/sx3s.gif\"  width=\"15\" alt=\"$gl[414]\" align=\"middle\" />";
    elseif ($myy == 3) $mysx = "<img src=\"images/sx/sx4s.gif\"  width=\"15\" alt=\"$gl[415]\" align=\"middle\" />";
    elseif ($myy == 4) $mysx = "<img src=\"images/sx/sx5s.gif\"  width=\"15\" alt=\"$gl[416]\" align=\"middle\" />";
    elseif ($myy == 5) $mysx = "<img src=\"images/sx/sx6s.gif\"  width=\"15\" alt=\"$gl[417]\" align=\"middle\" />";
    elseif ($myy == 6) $mysx = "<img src=\"images/sx/sx7s.gif\"  width=\"15\" alt=\"$gl[418]\" align=\"middle\" />";
    elseif ($myy == 7) $mysx = "<img src=\"images/sx/sx8s.gif\"  width=\"15\" alt=\"$gl[419]\" align=\"middle\" />";
    elseif ($myy == 8) $mysx = "<img src=\"images/sx/sx9s.gif\"  width=\"15\" alt=\"$gl[420]\" align=\"middle\" />";
    elseif ($myy == 9) $mysx = "<img src=\"images/sx/sx10s.gif\" width=\"15\" alt=\"$gl[421]\" align=\"middle\" />";
    elseif ($myy == 10) $mysx = "<img src=\"images/sx/sx11s.gif\" width=\"15\" alt=\"$gl[422]\" align=\"middle\" />";
    else $mysx = "<img src=\"images/sx/sx12s.gif\" width=\"15\" alt=\"$gl[423]\" align=\"middle\" />";
	eval(load_hook('int_global_robertsx'));
    return $mysx;
} 
// UTF-8 substr
function substrfor($str, $start, $end, $unhtml = true)
{
	eval(load_hook('int_global_substrfor'));
	
	if($unhtml) {
		$str = bmb_unhtmlentities($str);
	}
	
	if (function_exists("mb_substr"))
	{
		$output = mb_substr($str, $start, $end);
	} else {
    	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
    	$output = join("", array_slice($info[0], $start, $end));
    }
    
    return $output;
} 
// ------- html_entity_decode
function bmb_unhtmlentities($string) 
{
	eval(load_hook('int_global_bmb_unhtmlentities'));
	if (@function_exists("html_entity_decode")) {
        return html_entity_decode($string);
	} else {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        return strtr($string, $trans_tbl);
    }
}
// --------- Replace the vars in pre-processed templates
function reparray($a, $input)
{ 
    // 模板快速替换函数 by 2003-12-29
    foreach ($a as $k => $v) {
        $input = str_replace("$k", "$v", $input);
    } 
	eval(load_hook('int_global_reparray'));
    return $input;
} 
// Safe filter
function addsd($array)
{ 
    // 安全过滤函数 16:44 2008/7/24
    if (!$array) { return $array;}
    foreach($array as $key => $value) {
        if (!is_array($array[$key])) {
            $array[$key] = addslashes($value);
        } else {
            $array[$key] = addsd($array[$key]);
        } 
    } 
	eval(load_hook('int_global_addsd'));
    return $array;
} 
// UTF-8 Strlen
function utf8_strlen($str)
{
	eval(load_hook('int_global_utf8_strlen'));
	
	if (function_exists("mb_strlen"))
	{
		return mb_strlen($str);
	} else {
		return preg_match_all('/[\x00-\x7F\xC0-\xFD]/', $str, $dummy);
	}
} 
// Refresh the Forum's Cache to file from database
function refresh_forumcach($type = "forumdata", $lname = "sxfourmrow", $cn = "forumscount", $oby = "showorder")
{
    global $database_up;
    
    $nquery = "SELECT * FROM {$database_up}$type ORDER BY `$oby` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        $xfourmrow[] = $fourmrow;
    } 
    $wrting = "<?php \n";
    if(is_array($xfourmrow)) $count = count($xfourmrow);
    for ($i = 0; $i < $count; $i++) {
        $tmp = $xfourmrow[$i];
        foreach ($tmp as $key => $value) {
            if ($key != "fltitle") $wrting .= "\${$lname}[$i]['{$key}']='" . str_replace("\\\'", "\\\\\'", str_replace("'", "\'", $value)) . "'; \n";
            else $wrting .= "\${$lname}[$i]['{$key}']='" . str_replace("&amp;#039;", "&#039;", str_replace("'", "&#039;", htmlspecialchars(bmb_unhtmlentities($value)))) . "'; \n";
        } 
        $forumscount++;
    } 
    $wrting .= "\${$cn}='$forumscount';";
	eval(load_hook('int_global_refresh_forumcach'));
    big_write("datafile/cache/{$type}.php", $wrting);
} 
// Rand numbers and letters
function getCode ($length = 9, $type = 0)
{
    $str = $type ? 'ABCEFHJKMNPRSTUVWXY13456789' : 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $result = '';
    $l = strlen($str) - 1;

    for($i = 0;$i < $length;$i ++){
        $num = rand(0, $l);
        $result .= $str{$num};
    }
	eval(load_hook('int_global_getCode'));
    return $result;
}
// Show row's ads.
function show_row_ads ($openvar, $forumid = "")
{
	eval(load_hook('int_global_show_row_ads'));
	if ($forumid && @file_exists("datafile/rowads/{$forumid}.php")) {
		$forumid = basename($forumid);
		@include_once("datafile/rowads/{$forumid}.php");
		if ($row_show[$openvar] && $ads_showit) {
			echo $ads_showit;
			return;
		}
	}
    if (@file_exists("datafile/row_text.php")) {
        @include_once("datafile/row_text.php");
        if ($row_show[$openvar]) echo $ads_showit;
    } 
}
// check forum permission
function check_forum_permission($no_read_post = 0, $no_output = 0, $this_line = "", $no_forum_limit = 0)
{
    global $usermoney, $plusug, $level_id, $ajax_online, $levelgroupdata, $view_list, $forum_allow_ww, $uginfo, $enter_this_forum, $gl, $forumid, $forum_name, $p_read_post, $pgo, $ajax_reply, $error, $language, $read_allow_ww, $spusergroup, $postamount, $userbym , $forum_ford, $footer_copyright, $headername, $footername, $read_alignment, $html_lang, $enter_allow_ww, $userpoint, $enter_tb, $plugyescolor, $usertype, $forum_type, $forumid, $username, $login_status, $limitlist, $forum_admin, $verandproname, $otherimages, $bbs_title, $add_title, $cssinfo, $login_status, $ads_select, $admin_email, $site_url, $showtime, $site_name, $writefilenum, $readfilenum, $begin_time, $temfilename, $gl;
	// ===================================
	// Permission
	// ===================================
	
	$thissp_forumid = $forumid;
	$thissp_spusergroup = $spusergroup;
	$thissp_forum_ford = $forum_ford;
	$thissp_enter_this_forum = $enter_this_forum;
	eval(load_hook('int_global_check_forum_permission'));
	
	if ($this_line) {
		$thissp_forum_ford	=	$this_line['forum_ford'];
		$thissp_spusergroup	=	$this_line['spusergroup'];
		$thissp_forumid	=	$this_line['id'];
		
        if ($login_status == 0) $uginfo = 6;
        
        $group_usergroupdata = explode("\n", $this_line['usergroup']);
        
        if ($thissp_spusergroup == 1) {
	        if ($uginfo == 4) {
	        	include_once("datafile/cache/levels/level_fid_{$thissp_forumid}.php");
	        	if ($levelgroupdata[$thissp_forumid][$level_id]) {
	        		$group_usertype = explode("|", $levelgroupdata[$thissp_forumid][$level_id]);
	        	} else {
	        		$group_usertype = explode("|", $group_usergroupdata[$uginfo]);
	        	}
	        } else {
	        	$group_usertype = explode("|", $group_usergroupdata[$uginfo]);
	        }
	        
	        if (is_array($plusug)) {
	            $count = count($plusug);
	            for ($i = 0; $i < $count; $i++){
	            	if ($plusug[$i] == "" || $plusug[$i] == $uginfo) continue;
	                $tmpusertype = explode("|", $group_usergroupdata["{$plusug[$i]}"]);
	                $countut = count($tmpusertype);
					if ($tmpusertype[12] == 1 && $group_usertype[12] == 0) $group_usertype[12] = 1; 
	            }
	        }
	        
			$thissp_enter_this_forum	=	$group_usertype[12];
	    }
        

	}

	$ford = explode("_", $thissp_forum_ford);
	if ($ford[0] == 1 && ($postamount < $ford[1] || $userbym < $ford[2] || $usermoney < $ford[3]) && $usertype[21] != "1" && $usertype[22] != "1") {
		if ($no_output) return 0;
		else {
			if ($ajax_reply == 1) ajax_browse_error($error[6]);
			if ($ajax_online == 1) ajax_browse_error($error[6]);
			$ford[2] = floor($ford[2] / 10);
			$userbym = floor($userbym / 10);
			include("lang/$language/global.php");
		    include("header.php");
		    navi_bar("$gl[191]",
		        "<a href=\"forums.php?forumid=$thissp_forumid\">$forum_name</a>",
		        "$gl[192]", "no");
		    msg_box($gl[193], "$gl[194]");
		    include("footer.php");
		    exit;
		}
	} 

	// ===================================
	// Usergroup in this forum
	// ===================================

	if (($thissp_spusergroup == "1" && $thissp_enter_this_forum == "0" && $login_status == "1") || ($no_forum_limit == 1  && ($view_list == "0" || $userpoint < $forum_allow_ww)) || ($no_read_post == 1 && ($p_read_post == "0" || $userpoint < $read_allow_ww))) {
		if ($no_output) return 0;
		else {
			if ($ajax_reply == 1) ajax_browse_error($gl[438]);
			if ($ajax_online == 1) ajax_browse_error($gl[438]);
			include("header.php");
		    navi_bar($error[3],
		        "<a href=\"forums.php?forumid=$thissp_forumid\">$forum_name</a>",
		        $gl[192], "no");
		    msg_box($gl[192], $gl[438]);
		    include("footer.php");
		    exit;
		}
	}     
	if ($no_output) return 1;
} 
function ajax_browse_error($info = "Access Denied")
{
	eval(load_hook('int_global_ajax_browse_error'));
    header("HTTP/1.0 689 Access Denied");
    echo $info;
    exit;
}
function safe_upload_name($upload)
{
	$upload = str_replace("(", "", $upload);
	$upload = str_replace("'", "", $upload);
	$upload = str_replace('"', "", $upload);
	//$upload = basename($upload);
	$upload = htmlspecialchars($upload);
	eval(load_hook('int_global_safe_upload_name'));
	
	return $upload;
}
function geneSalt()
{
	return substr(uniqid(), 0, 8);
}
/**
 * 发送通知
 */
function insertNotification($receiverid, $primaryKey, $type, $values, $senderid = 0, $sendername = '')
{
	global $database_up, $timestamp;
	
	if(is_array($receiverid) && $receiverid) {
		foreach($receiverid as $id) {
			insertNotification($id, $primaryKey, $type, $values, $senderid, $sendername);
		}
		return true;
	}
	
	$userBlockResult = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}noticefilter WHERE uid='$receiverid'"));
	$userBlockData = $userBlockResult['filterrule'] ? unserialize($userBlockResult['filterrule']) : array();
	
	if($userBlockData[$type]) {
		return 2;
	}
	
	$text = addslashes(serialize($values));
	bmbdb_query("REPLACE INTO {$database_up}notification (senderid, sendername, receiverid, ntype, nvalue, timestamp, pkey) VALUES ('$senderid', '$sendername', '$receiverid', '$type', '$text', '$timestamp', '$primaryKey')");
	bmbdb_query("UPDATE {$database_up}userlist SET `unreadnote`=unreadnote+1 WHERE userid='$receiverid'");
	
	return 1;
}
function pager($page, $maxpageno, $pageLink, $jsJump = true) 
{
	global $temfilename, $styleidcode;
	
	include_once("include/template.php");
	$lang_zone = array();
	$template_name = newtemplate("pager", $temfilename, $styleidcode, $lang_zone);
	
	$nextpage = $page + 1;
	$previouspage = $page-1;
	$maxpagenum = $page + 4;
	$minpagenum = $page - 4;
	
	$pageArr = array();
	
	for ($i = $minpagenum; $i <= $maxpagenum; $i++) {
	    if ($i > 0 && $i <= $maxpageno) {
	        if ($i == $page) {
	        	$pageArr[$i] = '';
	        } else {
	        	$pageArr[$i] = str_replace('{page}', $i, $pageLink);
	        } 
	    } 
	} 
	
	require_once($template_name);
	template_page($page, $pageLink, str_replace('{page}', 1, $pageLink), array('page' => $maxpageno, 'link' => str_replace('{page}', $maxpageno, $pageLink)), $pageArr, $jsJump);
}
function checkUserAdd($userid)
{
	global $database_up;
	
	$userResult = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}user_add WHERE uid='$userid'"));
	if(!$userResult['uid']) {
		bmbdb_query("INSERT {$database_up}user_add (uid) VALUES ('$userid')");
	}
}
function small_pagelist($maxpageno, $pageLink, $stoppage = 5) 
{
	global $temfilename, $styleidcode;
	
	if ($maxpageno < 2) return;
	
	include_once("include/template.php");
	$lang_zone = array();
	$template_name = newtemplate("pager", $temfilename, $styleidcode, $lang_zone);
	
	$pageArr = array();
	
	for ($i = 1; $i <= $maxpageno; $i++) {
		$pageArr[$i] = str_replace('{page}', $i, $pageLink);
		if ($i == $stoppage && $i != $maxpageno && $maxpageno != ($stoppage+1)) {
			$pageArr[$maxpageno]['str'] = str_replace('{page}', $maxpageno, $pageLink);
			$pageArr[$maxpageno]['max'] = true;
			break;
		}
	} 
	
	require_once($template_name);
	template_smallpage($pageArr, $maxpageno);
}
