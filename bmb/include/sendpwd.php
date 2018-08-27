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
if ($logonutnum == 6) $cancel_guestfile = "reglog";
$add_title = " &gt; $gl[24]";

include("header.php");
navi_bar("$gl[23]", $gl[24], '', 'no');

$authinput = strtoupper($authinput);

$tplType = array('type' => 1);

if (empty($step)) {
	eval(load_hook('int_sendpwd_step0'));
    $tplType = array('type' => 1);
} elseif ($step == 2) {
    $temp = get_user_info($user);
    if ($temp == 0) {
		msg_box($gl[24], $gl[30]);
		include("footer.php");
		exit;
    } else {
        $useraskinfo = $temp['pwdask'];
        $useranswerinfo = $temp['pwdanswer'];
        $usertype = $temp['ugnum'];
        $usertype = explode("|", $usergroupdata[$usertype]);
		eval(load_hook('int_sendpwd_step1'));
        if ($useraskinfo == "") {
            msg_box($gl[24], $gl[265]);
            include("footer.php");
            exit;
        } 
        $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
        $_SESSION[checkauthnum] = $authnum;
        if ($usertype[22] != "1" && $usertype[22] != "1") {
            $tplType = array('type' => 2);
        } else {
            msg_box($gl[24], $gl[268]);
            include("footer.php");
            exit;
		} 
    } 
} elseif ($step == 3) {
    $temp = get_user_info($user);
    $useraskinfo = $temp['pwdask'];
    $useranswerinfo = $temp['pwdanswer'];
    $u_userid = $temp['userid'];
    $u_username = $temp['username'];
    $password = $temp['pwd'];
    $newpassword = rand(100000, 999999);
    $newpwd = md5($newpassword);

    $usertype = $temp['ugnum'];
    $usertype = explode("|", $usergroupdata[$usertype]);
	eval(load_hook('int_sendpwd_step2'));
    if (($useraskinfo == "" || $useranswerinfo == md5($useraskinfo) || $_SESSION["checkauthnum"] != $authinput) || ($_SESSION["logintry"] > $maxlogintry-1 && isset($maxlogintry))) {
        $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
        $_SESSION[checkauthnum] = $authnum;
        msg_box($gl[24], array($gl[265], $gl[440]));
        include("footer.php");
        exit;
    }
    if ($usertype[22] != "1" && $usertype[21] != "1") {
        if (md5($passanswer) != $useranswerinfo) {
            $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
            $_SESSION[checkauthnum] = $authnum;
            $_SESSION["logintry"]++;
			eval(load_hook('int_sendpwd_fail'));
			msg_box($gl[24], $gl[269]);
        } else {
            $atid = getCode(5);
            
            $nquery = "UPDATE {$database_up}userlist SET pwdrecovery='$atid' WHERE username='$user'";
            $result = bmbdb_query($nquery);
            
            include_once("include/sendmail.inc.php");
            $sendmail = "";

            $title = $gl[533];

            $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
            $_SESSION[checkauthnum] = $authnum;

            $ms = str_replace("{user}", $u_username, $gl[532]);
            $ms = str_replace("{usrid}", $u_userid, $ms);
            $ms = str_replace("{atid}", $atid, $ms);
        
			eval(load_hook('int_sendpwd_sendbackmail'));
            $frommail = $admin_email;
            @BMMailer($temp['mailadd'], $title, nl2br($ms), '', '', $bbs_title, $admin_email);
			
			msg_box($gl[24], $gl[270]);
        } 
    } else {
    	msg_box($gl[24], $gl[268]);
    } 
	include("footer.php");
	exit;
} elseif ($step == "v" && $uid && $id) {
    $temp = get_user_info($uid, "usrid");
	if ($temp['userid'] && $temp['pwdrecovery']) {
		if (!isset($maxlogintry)) $maxlogintry = 9999999;
		if ($temp['pwdrecovery'] == $id && $_SESSION["logintry"] <= $maxlogintry-1) {
			if ($pc != 2)
			{
				eval(load_hook('int_sendpwd_anewpwd'));
				$tplType = array('type' => 3);
			} else {
				require("lang/$language/usercp.php");
				$check = 1;
		        if ($check && utf8_strlen($addpassword) < 4) {
		            $reason = "$reglang[13]";
		            $check = 0;
		        } 
		        if ($check && $addpassword != $addpassword2) {
		            $reason = "$reglang[14]";
		            $check = 0;
		        } 
		        if ($check && empty($addpassword)) {
		            $reason = "$reglang[15]";
		            $check = 0;
		        } 
		        $addpassword = str_replace("\t", "", $addpassword);
		        $addpassword = str_replace("\r", "", $addpassword);
		        $addpassword = str_replace("\n", "", $addpassword);
		        if ($check && (strrpos($addpassword, "|") !== false || strrpos($addpassword, "<") !== false || strrpos($addpassword, ">") !== false)) {
		            $reason = "$reglang[16]";
		            $check = 0;
		        } 
				eval(load_hook('int_sendpwd_check'));
				if (!$check) {
					$echoinfos = $reason;
				} else {
					eval(load_hook('int_sendpwd_sql'));
					$newpwd = md5($addpassword);
					$newsalt = geneSalt();
	            	bmbdb_query("UPDATE {$database_up}userlist SET pwd='$newpwd',pwdrecovery='',salt='$newsalt' WHERE userid='$uid'");
					$echoinfos = $step2_suc[0];
					
					$auth = makeauth($newsalt, $bmfopt['sitekey'], $newpwd);
		            $_SESSION["bmfUsrAuth"] = $auth;
		            $_SESSION["bmfUsrId"] = $uid;
		            setcookie("bmfUsrId", $uid, 0);
		            setcookie("bmfUsrAuth", $auth, 0, true);
		            $lastlogin = $timestamp;
					eval(load_hook('int_sendpwd_suc'));
	            }
	            msg_box($gl[24], $echoinfos);
	            include("footer.php");
	            exit;
			}
		}
		else
		{
			$_SESSION["logintry"]++;
			eval(load_hook('int_sendpwd_fail'));
			msg_box($gl[24], $gl[536]);
			include("footer.php");
			exit;
		}
	}

} 

require("include/template.php");
eval(load_hook('int_sendpwd_form'));

$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("sendpwd", $temfilename, $styleidcode, $lang_zone);
require($template_name);

include("footer.php");
exit;
