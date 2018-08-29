<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
$thisprog = "login.php";
include_once("datafile/config.php");
$forumid = "";
include_once("getskin.php");
include_once("lang/$language/login.php");
require_once("include/common.inc.php");
include_once("include/template.php");

$lang_zone = array("programname"=>$programname, "gl"=>$gl, "pr_1n"=>$pr_1n, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("login", $temfilename, $styleidcode, $lang_zone);

if ($logonutnum == 6) {
	$cancel_guestfile = "reglog";
} elseif ($usertype[118] == 1 || $userpoint < $usertype[119]) {
	$job = "";
}

get_forum_info("");
if (!$job) $job = "relogin";

$add_title = " > $programname";

$ablelist = $fast_switch = $selected = "";

$authinput = strtoupper($authinput);

if ($_SESSION["logintry"] > $maxlogintry-1 && isset($maxlogintry)) {
	error_page("", $programname, $programname, $error_3n);
} 
if (empty($gotourl)) $gotourl = $_SERVER['HTTP_REFERER'];
if ($job == "relogin") {
    if ($step == 2) {
    	eval(load_hook('int_login_step_2'));
    	
    	$loginpwd = $_POST['loginpwd'];
    	
    	if (!$bmfopt['noswitchuser'] && $login_status == 1 && $accountid && $verify == $log_hash) {
			$ablelist = make_loginable_list($userddata);
			if ($accountid && @in_array($accountid, $selected)) {
				$fast_switch = 1;
				$loginuser = $accountid;
				$login_with = "id";
				$cookie_time = $_COOKIE['cookie_time_bmb'];
            	$infos = bmbdb_fetch_array(bmbdb_query("SELECT pwd FROM {$database_up}userlist WHERE userid='$accountid' LIMIT 0,1"));
            	$loginpwd = $infos['pwd'];
            	$log_va = 0;
			}
    	}
    	
        $check = 0;

        if ($loginuser && $loginpwd) {
        	if ($fast_switch != 1) {
            	$loginpwd = md5(stripslashes($loginpwd));
            }
            $check = $login_with == "id" ? checkpass("", $loginpwd, $loginuser) : checkpass($loginuser, $loginpwd);
        } 
        if ($log_va && $_SESSION["checkauthnum"] != $authinput) {
        	$_SESSION["logintry"]++;
        	error_page($tip_2, $programname, $programname, $gl[440], "", 1);
            $check = 2;
            $step = 0;
        } 
        $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
        $_SESSION['checkauthnum'] = $authnum;
        if (!check_name_valid($loginuser)) {
            error_page($tip_2, $programname, $programname, $error_1n, "", 1);

            $check = 2;
            $step = 0;
        } 
        $skinselectname = safe_convert($skinselectname);
        $auth = makeauth($userinfocache_id[$user_check_id]['salt'], $bmfopt['sitekey'], $loginpwd);
        if ($check == 1) {
            $nowtime = time();
            
            bmb_setcookie("bmfUsrAuth", "", $nowtime-3600, true);
            bmb_setcookie("bmfUsrId", "", $nowtime-3600);
            if ($fast_switch != 1) {
	            bmb_setcookie("bmbskin", "", $nowtime-3600);
	            bmb_setcookie("privacybym", "", $nowtime-3600);
	            bmb_setcookie("TNM", "", $nowtime-3600);
	            bmb_setcookie("close_ajax", "", $nowtime-3600);
				$_SESSION["privacy"] = $_SESSION["cookie_time_bmb"] = $_SESSION["TNM"] = $_SESSION["bmblogonskin"] = "";
	        }

            $_SESSION["bmfUsrAuth"] = $_SESSION["bmfUsrId"] = "";

            if ($privacy != 1) $privacy = 0;
            if ($TNM != 'yes') $TNM = 0;
            if ($_POST['close_ajax'] != 1) $close_ajax = 0;
              else $close_ajax = 1;
            $login_status = 1;
            
            if ($cookie_time == "") $cookie_time = - $nowtime;
            
            if ($cookie_time != "0" && $cookie_time != "") {
                bmb_setcookie("bmfUsrId", $user_check_id, $nowtime + $cookie_time);
                bmb_setcookie("bmfUsrAuth", $auth, $nowtime + $cookie_time, true);
                if ($fast_switch != 1) {
	                bmb_setcookie("cookie_time_bmb", $cookie_time, $nowtime + $cookie_time);
	                bmb_setcookie("privacybym", $privacy, $nowtime + $cookie_time);
	                bmb_setcookie("TNM", $TNM, $nowtime + $cookie_time);
	                bmb_setcookie("close_ajax", $close_ajax, $nowtime + $cookie_time);
	                if ($fnew_skin == 1 && $skinselectname != "notchange!") {
	                    $bmblogonskin = $skinselectname;
	                    bmb_setcookie("bmbskin", $bmblogonskin, $nowtime + $cookie_time);
	                } 
	            }
            } else {
                bmb_setcookie("bmfUsrId", $user_check_id, 0);
                bmb_setcookie("bmfUsrAuth", $auth, 0, true);
                if ($fast_switch != 1) {
	                bmb_setcookie("cookie_time_bmb", $cookie_time, 0);
	                bmb_setcookie("privacybym", $privacy, 0);
	                bmb_setcookie("TNM", $TNM, 0);
	                bmb_setcookie("close_ajax", $close_ajax, 0);
	                if ($fnew_skin == 1 && $skinselectname != "notchange!") {
	                    $bmblogonskin = $skinselectname;
	                    bmb_setcookie("bmbskin", $bmblogonskin, 0);
	                } 
	            }
            }

            //$username = $bmforumerboardid; 
            $login_forumid = $_POST['forumid'];

            if (!empty($login_forumid)) {
                $url = "forums.php?forumid=" . $login_forumid;
                $forumjumpnow = ' | <a href="' . $url . '">' . $suc_1n . '</a>';
            }
            
            $url = $url ? $url : "index.php";
            if (!empty($gourl) && $url == "index.php") $url = $gourl;
            if (empty($gourl) && !empty($gotourl)) $url = $gotourl;
            if (@basename($url) == "login.php") $url = "index.php";
            $returnjumpnow = ' | <a href="' . $url . '">' . $tip_1 . '</a>';
           	eval(load_hook('int_login_step_2_bjp'));
           	if ($_SESSION['oauth']) {
           		jump_page('oauth.php', $suc_2n, "<strong>$suc_3n</strong><br /><br /> <a href='oauth.php'>{$bindLang['toBind']}</a>", 3);
           	} else {
            	jump_page($url, $suc_2n, "<strong>$suc_3n</strong><br /><br /> <a href='index.php'>$suc_3n_1s</a>$forumjumpnow $returnjumpnow", 3);
            }
        } elseif ($check == 0) {
            $_SESSION["logintry"]++;
            eval(load_hook('int_login_step_2_fail'));
            error_page($tip_2, $programname, $programname, $error_2n . $_SESSION["logintry"] . '. <br /><ul><li><a href="javascript:history.back(1);">' . $return . '</a></li><li><a href="misc.php?p=sendpwd">' . $pr_1n[4] . '</a></li></ul>', "", 1);
            
            $step = 0;
        } 
    } 
    if (!$step) {
    	if (!$bmfopt['noswitchuser'] && $login_status == 1) {
			$ablelist = make_loginable_list($userddata);
    	}
		eval(load_hook('int_login_step_1'));
        include_once("header.php");
        printbar();
        printLoginForm();
        include("footer.php");
        exit;
    } 
} elseif ($job == "switch") {
	@bmb_setcookie('lastpath', "abcsad.php");

	if ($_COOKIE['privacybym']) setcookie("privacybym", "", $nowtime-3600, $cookie_p, $cookie_d);
		else bmb_setcookie("privacybym", "1");

	if ($_SERVER['HTTP_REFERER'] == "" || $_GET['goto'] == "demo") $_SERVER['HTTP_REFERER'] = "index.php";
	eval(load_hook('int_login_switch_status'));
	echo "<meta http-equiv=\"Refresh\" content=\"0; URL={$_SERVER['HTTP_REFERER']}\">";
} elseif ($job == "quit") {
    eval(load_hook('int_logout_begin'));
	if ($log_hash != $verify) error_page("", $programname, $programname, $error_4n);
	$nowtime = time();
	
    bmb_setcookie("bmfUsrId", "", $nowtime-3600);
    bmb_setcookie("bmfUsrAuth", "", $nowtime-3600);
    bmb_setcookie("privacybym", "", $nowtime-3600);
    bmb_setcookie("TNM", "", $nowtime-3600);
    bmb_setcookie("close_ajax", "", $nowtime-3600);
    bmb_setcookie("cookie_time_bmb", "", $nowtime-3600);
    bmb_setcookie("bmbskin", "", $nowtime-3600);

    $_SESSION["bmfUsrAuth"] = $_SESSION["bmfUsrId"] = $_SESSION["privacy"] = $_SESSION["TNM"] = $_SESSION["bmblogonskin"] = "";

    $onlinedata = file("datafile/online.php");
    $count = count($onlinedata);
    for($i = 0;$i < $count;$i++) {
        $onlinedetail = explode("|", $onlinedata[$i]);
        if (strtolower($onlinedetail[1]) == strtolower($username)) {
            unset($onlinedata[$i]);
            break;
        } 
    } 
    $writeto = @implode("", $onlinedata);
    big_write("datafile/online.php", $writeto);

    if (!empty($gourl)) $gourl = $gourl;
    elseif (empty($gourl) && !empty($gotourl)) $gourl = $gotourl;
    else $gourl = "index.php";
    eval(load_hook('int_logout'));
    jump_page($gourl, $suc_4n, "<strong>$suc_5n</strong><br /><br /><a href='index.php'>$suc_3n_1s</a> | <a href='$gourl'>$tip_1</a>", 3);
} 

function printbar()
{
    global $tip_2, $programname;
    navi_bar($tip_2, $programname, 0, 0, 0);
} 
function make_loginable_list($userddata)
{
	global $database_up, $selected, $usergroupdata;
	
	if ($userddata['parusrid']) {
		$sql_other = " OR `parusrid`='{$userddata['parusrid']}' OR `userid`='{$userddata['parusrid']}'";
	}
	
	$result = bmbdb_query("SELECT * FROM {$database_up}userlist WHERE `parusrid`='$userddata[userid]' {$sql_other}");
	
	while (false !== ($row = bmbdb_fetch_array($result))) {
		if(!@in_array($row['userid'], $selected) && $userddata['userid'] != $row['userid']) {
			$usericon = get_user_portait($row['avarts'], false, $row['mailadd']);
			
			$ablelist[] = array("usericon"=>$usericon, "name" => $row['username'], "userid" => $row['userid']);
			$selected[] = $row['userid'];
		}
	}
	
	return $ablelist;
}
function printLoginForm()
{
    global $forumlist, $log_hash, $ablelist, $template_name, $gd_auth, $gotourl, $login_with, $sxfourmrow, $forumscount, $loginuser, $fnew_skin, $temfilename, $programname, $t, $authnum, $log_va, $gl, $gourl;
    
    $gourl = $gotourl;
    for($i = 0;$i < $forumscount;$i++) {
        if ($sxfourmrow[$i]['type'] != "category" && $sxfourmrow[$i]['type'] != "subhidden" && $sxfourmrow[$i]['type'] != "hidden" && $sxfourmrow[$i]['type'] != "subforumhid" && $sxfourmrow[$i]['type'] != "forumhid" && $sxfourmrow[$i]['id']) $aaa .= "<option value=\"{$sxfourmrow[$i]['id']}\">{$sxfourmrow[$i]['bbsname']}</option>";
    } 
    $aaa .= "</select>";
    
    if ($login_with == "id") $checked_iname = "checked='checked'";
       else $checked_wname = "checked='checked'";

    if ($log_va) {
        $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
        $_SESSION[checkauthnum] = $authnum;
		$tmp23s = $gd_auth;
    } 
    if ($fnew_skin == 1) {
        $dh = file("datafile/stylelist.php");
        $cdh = count($dh);
        for($cid = 0;$cid < $cdh;$cid++) {
            $cdhtail = explode("|", $dh[$cid]);
            $usecutskin .= "<option value=\"$cdhtail[1]\">$cdhtail[2]</option>";
        } 


    } 

	require($template_name);
} 
function printLoginSuccess()
{
    global $programname;
    msg_box($programname, '');
} 
